<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Importa relato
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 */

/**
 * Importa relato
 */
require_once "aut.php";
require_once $_SESSION['dirsitio'] . "/conf.php";
$aut_usuario = "";
autentica_usuario($dsn, $aut_usuario, 61);

require_once $_SESSION['dirsitio'] . "/conf_int.php";
require_once "misc.php";
require_once "misc_importa.php";
require_once 'HTML/QuickForm/Controller.php';

require_once 'HTML/QuickForm/Action/Display.php';
require_once 'HTML/QuickForm/Action/Next.php';
require_once 'HTML/QuickForm/Action/Back.php';
require_once 'HTML/QuickForm/Action/Jump.php';
require_once 'HTML/QuickForm/header.php';
require_once 'HTML/QuickForm/date.php';
require_once 'HTML/QuickForm/text.php';

require_once 'PagTipoViolencia.php';
require_once 'PagFuentesFrecuentes.php';
require_once 'ResConsulta.php';
require_once 'DataObjects/Presponsable.php';
require_once 'DataObjects/Profesion.php';
require_once 'DataObjects/Rangoedad.php';
require_once 'DataObjects/Filiacion.php';
require_once 'DataObjects/Sectorsocial.php';
require_once 'DataObjects/Organizacion.php';
require_once 'DataObjects/Vinculoestado.php';
require_once 'DataObjects/Tsitio.php';
require_once 'DataObjects/Categoria.php';

foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
    list($n, $c, $o) = $tab;
    if (($d = strrpos($c, "/"))>0) {
        $c = substr($c, $d+1);
    }
    // @codingStandardsIgnoreStart
    require_once "$c.php";
    // @codingStandardsIgnoreEnd
}


/**
 * Responde a botón importar
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class AccionImportaRelato extends HTML_QuickForm_Action
{

    /**
     * Ejecuta acción
     *
     * Basado en caso_detalles_sivel_remoto.php de Luca
     *
     * @param object &$page      Página
     * @param string $actionName Acción
     *
     * @return void
     */
    function perform(&$page, $actionName)
    {
        $dcaso = objeto_tabla('caso');

        $db =& $dcaso->getDatabaseConnection();

        $idetiqueta = (int)$db->getOne(
            "SELECT id FROM etiqueta "
            .  " WHERE nombre = 'IMPORTA_RELATO'"
        );
        if ($idetiqueta == 0) {
            die(_("Debe haber una etiqueta IMPORTA_RELATO.") . " "
                . _("Favor") . " <a href='actualiza.php'>"
                . _("actualizar") . "</a>."
            );
        }
        $iderrorimportacion = (int)$db->getOne(
            "SELECT id FROM etiqueta "
            . " WHERE nombre = 'ERROR_IMPORTACIÓN'"
        );
        if ($iderrorimportacion == 0) {
            die(_("Debe haber una etiqueta ERROR_IMPORTACIÓN.") . " "
                . _("Favor") . " <a href='actualiza.php'>"
                . _("actualizar") . "</a>."
            );
        }

        // $pArchivo = var_post_escapa('archivo');

        // $f=$this->banexo->getForm();
        $p = $page->controller->_pages['importaRelato'];
        $e = $p->_submitFiles['archivo_sel'];

        $pArchivo = $e['name'];

        if ($e['size'] <= 0) {
            $u = ini_get('upload_max_filesize');
            $p = ini_get('post_max_size');
            die(
                sprintf(
                    _(
                        "No pudo subirse archivo, revisar que el tamaño sea "
                        . "mayor que cero y menor que %s y que %s"
                    ), $u, $p
                )
            );
        }
        move_uploaded_file(
            $e['tmp_name'], $GLOBALS['dir_anexos'] . "/" .
            $pArchivo
        );
        $intensivo = isset($_POST['intensivo']) && $_POST['intensivo'] == '1';
        $aper = array();
        $agr = array();
        if ($intensivo) {
            list($aper, ) = extrae_per($db);
            $agr = extrae_grupos($db);
        }

        if (substr($pArchivo, strlen($pArchivo) - 3, 3) == '.gz') {
            if (!function_exists('readgzfile')) {
                die(_("Falta soporte para Zlib en su instalación de PHP.") . " "
                    . _("Descomprima manualmente e importe el descomprimido")
                );
            }
            $narc = $GLOBALS['dir_anexos'] . "/$pArchivo";
            $cont = readgzfile($narc);
        } else {
            $narc = $GLOBALS['dir_anexos'] . "/$pArchivo";
            $cont = file_get_contents($narc);
            if ($cont == '') {
                die_esc('No puede importar archivo vacío ' . $pArchivo);
            }
        }

        $relatos = simplexml_load_string($cont);
        if (!$relatos) {
            $e = libxml_get_errors();
            die(_("No pudo cargarse") . " '" . $pArchivo . "': " . $e);
        }

        $yaesta = array(); // Indica cuales pestañas ya importaron
        foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
            list($n, $c, ) = $tab;
            $yaesta[$c] = false;
        }

        encabezado_envia("Importa Relato");
        foreach ($relatos->relato as $r) {
            $obs = "";
            $id_presp = array();  // Presuntos responsables identificados
            $id_vcol = array();
            $datgrupo = array();
            $dcaso = objeto_tabla('caso');
            if (isset($r->titulo)) {
                $dcaso->titulo = $r->titulo;
            }
            $dcaso->memo = ereg_replace(
                "\n", " ",
                trim($r->hechos)
            );
            $dcaso->fecha = conv_fecha($r->fecha, $obs);
            if (isset($r->duracion) && $r->duracion != "") {
                $dcaso->duracion = trim($r->duracion);
            }
            if (isset($r->hora) && $r->hora!= "") {
                $dcaso->hora = trim($r->hora);
            }
            $pf = explode('-', $dcaso->fecha);
            $aniocaso = (int)$pf[0];
            $mescaso = (int)$pf[1];
            $diacaso = (int)$pf[2];
            $dcaso->id_intervalo = dato_basico_en_obs(
                $db,
                $obs, $r, 'intervalo', 'intervalo', '', 0
            );
            if ($dcaso->id_intervalo == 0) {
                $dcaso->id_intervalo = DataObjects_Intervalo::idSinInfo();
            }
            $dcaso->grconfiabilidad = str_pad(
                dato_en_obs(
                    $r,
                    'grconfiabilidad'
                ), 5
            );
            $dcaso->gresclarecimiento = str_pad(
                dato_en_obs(
                    $r,
                    'gresclarecimiento'
                ), 5
            );
            $dcaso->grimpunidad = str_pad(
                dato_en_obs(
                    $r,
                    'grimpunidad'
                ), 5
            );
            $dcaso->grinformacion = str_pad(
                dato_en_obs(
                    $r,
                    'grinformacion'
                ), 5
            );
            $dcaso->bienes = dato_en_obs($r, 'bienes');
            if (!$dcaso->insert()) {
                //var_dump($dcaso);
                die(_("No pudo insertar caso ") . $dcaso->id);
            }
            $idcaso = $dcaso->id;
            if ($idcaso == 0) {
                die(_("idcaso es 0"));
            }
            $yaesta['PagBasicos'] = true;
            $yaesta['PagMemo'] = true;
            $yaesta['PagEvaluacion'] = true;


            PagUbicacion::importaRelato($db, $r, $idcaso, $obs);
            $yaesta['PagUbicacion'] = true;

            $anexof = objeto_tabla('anexo');
            $anexof->id_caso = $idcaso;
            $anexof->fecha =  @date('Y-m-d');
            $anexof->archivo = '';
            $anexof->descripcion = sprintf(
                _("Fuente extraida automaticamente de %s"), $narc
            );
            $anexof->insert();

            $rx = $GLOBALS['enc_relato']
                . "<relatos>\n"
                . $r->asXml()
                . "\n</relatos>\n" ;
            $ax = $idcaso . "_" . $anexof->id . "_relatoimportado.xrlt";
            $cax = $GLOBALS['dir_anexos'] . "/" . $ax;
            file_put_contents($cax, $rx);

            $anexof->archivo = $ax;
            $anexof->update();
            PagFuentesFrecuentes::importaRelato(
                $db, $r, $idcaso,
                $obs
            );
            $nomf = $r->organizacion_responsable;
            $fecha = @date('Y-m-d');
            $orgfuente = PagFuentesFrecuentes::busca_inserta(
                $db, $idcaso, $nomf, $fecha,
                $r->id_relato,
                _('Organización responsable incluida automáticamente'),
                '', $obs
            );
            if ($orgfuente > 0) {
                $anexof->id_ffrecuente = $orgfuente;
                $anexof->fecha_prensa = $fecha;
                $anexof->update();
            }

            $yaesta['PagFuentesFrecuentes'] = true;
            $yaesta['modulos/anexos/PagFrecuenteAnexo'] = true;


            PagOtrasFuentes::importaRelato($db, $r, $idcaso, $obs);
            if ($orgfuente <= 0) {
                $orgfuente = PagOtrasFuentes::busca_inserta(
                    $db, $idcaso, $nomf, $fecha,
                    $r->id_relato,
                    _('Organización responsable incluida automáticamente'),
                    'Indirecta', $obs
                );
                $anexof->id_fotra = $orgfuente;
                $anexof->update();
            }
            $yaesta['PagOtrasFuentes'] = true;
            $yaesta['modulos/anexos/PagOtraAnexo'] = true;

            PagTipoViolencia::importaRelato(
                $db, $r, $idcaso,
                $obs
            );
            $yaesta['PagTipoViolencia'] = true;


            // Grupo
            foreach ($r->grupo as $grupo) {
                if (!empty($grupo->nombre_grupo)) {
                    $idg = (string)$grupo->id_grupo;
                    $datgrupo[$idg] = $grupo;
                }
            }
            $yaesta['PagPResponsable'] = true;
            $yaesta['PagVictimaColectiva'] = true;

            // Victimas
            $id_pers = array();
            $aedad = array();
            foreach ($r->persona as $persona) {
                //echo "OJO Persona <br>";
                if (!empty($persona->nombre)) {
                    $anionac = null;
                    $mesnac = null;
                    $dianac = null;
                    $edad = null;
                    if (isset($persona->fecha_nacimiento)) {
                        $fn = conv_fecha(
                            (string)$persona->fecha_nacimiento,
                            $obs
                        );
                        $pfn = explode('-', $fn);
                        $anionac = (int)$pfn[0];
                        $mesnac = (int)$pfn[1];
                        $dianac = (int)$pfn[2];
                        $edad = edad_de_fechanac(
                            $anionac, $aniocaso, $mesnac,
                            $mescaso, $dianac, $diacaso
                        );
                    } else if (isset($persona->observaciones)) {
                        $edad = dato_en_obs($persona, 'edad');
                        if ($edad !== null && strlen($edad)>0 ) {
                            $anionac = $aniocaso - lnat_a_numero($edad);
                        }
                    }
                    $sexo = 'S';
                    if (isset($persona->sexo)) {
                        switch (strtoupper($persona->sexo)) {
                        case 'M':
                        case 'MASCULINO':
                            $sexo = 'M';
                            break;
                        case 'FEMENINO':
                            $sexo = 'F';
                            break;
                        default:
                            $sexo = 'S';
                            break;
                        }
                    }
                    $ndep = dato_en_obs($persona, 'departamento');
                    $nmun = dato_en_obs($persona, 'municipio');
                    $ncla = dato_en_obs($persona, 'clase');
                    //echo "OJO ndep=$ndep, nmun=$nmun, ncla=$ncla<br>";
                    $idd = $idm = $idc = null;
                    if (($ndep !== null && strlen($ndep) > 0) || 
                        ($nmun !== null && strlen($nmun) > 0) || 
                        ($ncla !== null && strlen($ncla) > 0)
                    ) {
                        list($idd, $idm, $idc) = conv_localizacion(
                            $db, $ndep, $nmun, $ncla, $obs
                        );
                        //echo "OJO idd=$idd, idm=$idm, idc=$idc<br>";
                        if ($idd == 1000) {
                            $idd = null;
                        }
                        if ($idm == 1000) {
                            $idm = null;
                        }
                        if ($idc == 1000) {
                            $idc = null;
                        }
                    }
                    $docid = dato_en_obs($persona, 'docid');
                    $tipo_documento = $numero_documento = null;
                    if (!empty($docid)) {
                        $numero_documento = $docid;
                    }
                    $cper = conv_persona(
                        $db, $aper, $obs,
                        $persona->nombre,
                        $persona->apellido, $anionac,
                        $mesnac, $dianac, $sexo,
                        $idd, $idm, $idc, $tipo_documento,
                        $numero_documento
                    );
                    $id_pers[(string)$persona->id_persona] = $cper;
                    if ($edad != null) {
                        $aedad[$cper] = $edad;
                    }
                }
            }
            foreach ($r->victima as $victima) {
                if (!empty($victima->id_persona)) {
                    if (!isset($id_pers[(string)$victima->id_persona])) {
                        rep_obs(
                            sprintf(
                                _("Acto: No hay definida persona con id '%s'"),
                                (string)$victima->id_persona
                            ),
                            $obs
                        );
                        break;
                    } else {
                        $dvictima = objeto_tabla('victima');
                        $dvictima->id_caso = (int)$idcaso;

                        $hijos = dato_en_obs($victima, 'hijos');
                        if ($hijos !== null && strlen($hijos) > 0) {
                            $dvictima->hijos= lnat_a_numero($hijos);
                        }
                        $dvictima->id_persona
                            = $id_pers[(string)$victima->id_persona];
                        $idredad = -1;
                        $edad = isset($aedad[$dvictima->id_persona]) ?
                            $aedad[$dvictima->id_persona] : null;
                        if ($edad != null) {
                            //echo "OJO id_persona=" . $dvictima->id_persona
                            //    . " edad=$edad<br>\n";
                            $idredad = rango_de_edad($edad);
                        } else {
                            $redad = dato_en_obs($victima, 'rangoedad');
                            //echo "OJO redad=$redad<br>\n";
                            if ($redad !== null && strlen($redad) > 0) {
                                $idredad = (int)conv_basica(
                                    $db, 'rangoedad',
                                    $redad, $obs, false, 'rango'
                                );
                            }
                        }
                        //echo "OJO 2 idredad=$idredad<br>\n";
                        if ($idredad == -1) {
                            $idredad = DataObjects_Rangoedad::idSinInfo();
                        }
                        $dvictima->id_rangoedad = $idredad;
                        foreach (array('ocupacion' => 'profesion',
                            'iglesia' => 'filiacion',
                            'sector_condicion' => 'sectorsocial',
                            'organizacion' =>  'organizacion'
                        ) as $cr => $cs
                        ) {
                                //echo "OJO cr=$cr<br>";
                            $ncs = "id_" . $cs;
                            //echo "OJO ncs=$ncs<br>";
                            if (isset($victima->$cr)) {
                                $dvictima->$ncs = (int)conv_basica(
                                    $db,
                                    "$cs",
                                    $victima->$cr->__toString(),
                                    $obs
                                );
                            } else if (is_callable(
                                array("DataObjects_$cs",
                                'idSinInfo'
                                )
                            )
                            ) {
                                $v = call_user_func(
                                    array("DataObjects_$cs",
                                    "idSinInfo")
                                );
                                $dvictima->$ncs = $v;
                            }
                            //echo "OJO dvictima->ncs=" .  $dvictima->$ncs . "<br>";
                        }
                    }
                    foreach (array('filiacion' => 'filiacion',
                        'vinculoestado' => 'vinculoestado',
                        'organizacion_armada' => 'presponsable')
                        as $cs => $cs2
                    ) {
                        $ncs = "id_" . $cs;
                        //echo "OJO cs=$cs, ncs=$ncs<br>";
                        $v = dato_en_obs($victima, $cs);
                        $dvictima->$ncs = (int)conv_basica(
                            $db, $cs2,
                            $v, $obs, true
                        );
                        //echo "OJO dvictima->ncs=" .  $dvictima->$ncs . "<br>";
                    }
                    $v = dato_en_obs($victima, "organizacionarmada");
                    if ($v === null || strlen($v) == 0) {
                        $v = dato_en_obs($victima, "organizacion_armada");
                    }
                    if ($v !== null && strlen($v) > 0) {
                        $dvictima->organizacionarmada = (int)conv_basica(
                            $db, 'presponsable', $v, $obs
                        );
                    } else {
                        $dvictima->organizacionarmada
                            = DataObjects_Presponsable::idSinInfo();
                    }
                    if (!$dvictima || !$dvictima->insert()) {
                        $m = _("No pudo insertar víctima") ." '"
                            . $dvictima->id_persona . "'\n";
                        if (PEAR::isError($dvictima)) {
                            $m .= $dvictima->getMessage() . " "
                                . $dvictima->getUserInfo();
                        }
                        rep_obs($m, $obs);
                        break;
                    }
                    foreach (array('antecedentes' => 'antecedente',  )
                        as $cs => $cs2
                    ) {
                        //echo "OJO cs=$cs, cs2=$cs2<br>";
                        $v = dato_en_obs($victima, $cs);
                        //echo "OJO v=$v<br>";
                        if ($v !== null && strlen($v) > 0) {
                            $la = explode(';', $v);
                            foreach ($la as $ant) {
                                //echo "OJO ant=$ant<br>";
                                $idant = (int)conv_basica(
                                    $db, $cs2,
                                    $ant, $obs
                                );
                                //echo "OJO idant=$idant<br>";
                                if ($idant > 0) {
                                    $dantv= objeto_tabla('antecedente_victima');
                                    $dantv->id_caso = (int)$idcaso;
                                    $dantv->id_persona = (int)$dvictima->id_persona;
                                    $dantv->id_antecedente = $idant;
                                    $dantv->insert();
                                }
                            }
                        }
                    }


                }
            }
            $yaesta['PagVictimaIndividual'] = true;

            // Actos
            foreach ($r->acto as $acto) {
                //echo "OJO acto->agresion_particular="
                //    . $acto->agresion_particular . "<br>";
                if (!empty($acto->agresion_particular)) {
                    $idp = (string)$acto->id_presunto_grupo_responsable;
                    if (isset($id_presp[$idp])) {
                        // Ya registrado presunto responsable
                        $pr = $id_presp[$idp];
                    } else if (isset($datgrupo[$idp])) {
                        $g = $datgrupo[$idp];
                        $pr = conv_presp(
                            $db, $idcaso, $idp, $g, $id_presp, $obs, true
                        );
                    } else {
                        rep_obs(
                            _("No hay datos de p. resp.") . " '" .
                            $idp . "'", $obs
                        );
                        break;
                    }
                    $id_categoria = conv_categoria(
                        $db, $obs,
                        $acto->agresion_particular, $pr
                    );
                    if ($id_categoria == 0) {
                        break;
                    }
                    // echo "OJO "; print_r($acto);
                    if (!empty($acto->id_victima_individual)) {
                        $dacto= objeto_tabla('acto');
                        $dacto->id_caso = $idcaso;
                        $dacto->id_presponsable = $pr;
                        $dacto->id_categoria = $id_categoria;
                        if (!isset($id_pers[(string)$acto->id_victima_individual])) {
                            rep_obs(
                                _("No hay definida persona con id.") ." '" .
                                ((string)$acto->id_victima_individual) . "'",
                                $obs
                            );
                        } else {
                            $idvi = (string)$acto->id_victima_individual;
                            $dacto->id_persona = $id_pers[$idvi];
                            $dacto->insert();
                            //print_r($dacto);
                        }
                    } else if (!empty($acto->id_grupo_victima)) {
                        $ia = (string)$acto->id_grupo_victima;
                        $g = $datgrupo[$ia];
                        $cg = "";
                        if (isset($id_vcol[$ia])) {
                            $cg = $id_vcol[$ia];
                        } else if (!empty($g->nombre_grupo)) {
                            $cg = conv_victima_col(
                                $db, $agr, $idcaso, $g,
                                $obs
                            );
                            $id_vcol[$ia] = $cg;
                        }
                        $dactocolectivo = objeto_tabla('actocolectivo');
                        $dactocolectivo->id_caso = $idcaso;
                        $dactocolectivo->id_presponsable = $pr;
                        $dactocolectivo->id_categoria = $id_categoria;
                        $dactocolectivo->id_grupoper = $cg;
                        if (!$dactocolectivo->insert()) {
                            rep_obs(
                                _("Acto: No pudo insertar acto col.")
                                . " '$cg', '"
                                . ((string)$acto->id_grupo_victima) . "'",
                                $obs
                            );
                        }
                    } else {
                        rep_obs(_("No es individual ni colectiva"), $obs);
                        print_r($acto);
                    }
                } else {
                    rep_obs(_("Agresión particular vacía"), $obs);
                }
            }
            $yaesta['PagActo'] = true;

            // Completamos victimas colectivas suponiendo que también son
            // los grupos que no son presuntos responsables y que no fueron
            // nombrados como víctimas en actos
            foreach ($datgrupo as $idg => $g) {
                //echo "OJO revisando idg=$idg\n";
                if (!isset($id_presp[$idg]) && !isset($id_vcol[$idg])) {
                    //echo "OJO convirtiendo a victima colectiva idg=$idg\n";
                    $idp = conv_presp($db, $idcaso, $idg, $g, $id_presp, $obs);
                    if ($idp == -1) { // Asumimos que es víctima colectiva
                        $cg = conv_victima_col(
                            $db, $agr, $idcaso, $g,
                            $obs
                        );
                        $id_vcol[$idg] = $cg;
                    }
                }
            }

            foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
                list($n, $c, ) = $tab;
                if (!$yaesta[$c]) {
                    if (($d = strrpos($c, "/"))>0) {
                        $c = substr($c, $d+1);
                    }
                    if (is_callable(array($c, 'importaRelato'))) {
                        call_user_func_array(
                            array($c, 'importaRelato'),
                            array(&$db, $r, $idcaso, &$obs)
                        );
                    } else {
                        echo_esc(_("Falta importaRelato en") . " $n, $c");
                    }
                }
            }
            caso_usuario($idcaso);

            $html_rep = ResConsulta::reporteGeneralHtml(
                $idcaso, $db,
                $GLOBALS['cw_ncampos'] + array('m_fuentes' => 'Fuentes')
            );
            echo "<hr><pre>$html_rep</pre>";
            if (trim($obs) != '') {
                echo_esc(_("Observaciones"). ": $obs");
            }

            $ec = objeto_tabla('caso_etiqueta');
            $ec->fecha = @date('Y-m-d');
            $ec->id_caso = $idcaso;
            $ec->id_etiqueta = $idetiqueta;
            $ec->id_usuario = $_SESSION['id_usuario'];
            $ec->fecha = @date('Y-m-d');
            $ec->observaciones = "";
            if (isset($r->id_relato)) {
                $ec->observaciones = trim($r->id_relato);
            }
            $ec->insert();

            if (trim($obs) != '') {
                $ec = objeto_tabla('caso_etiqueta');
                $ec->fecha = @date('Y-m-d');
                $ec->id_caso = $idcaso;
                $ec->id_etiqueta = $iderrorimportacion;
                $ec->id_usuario = $_SESSION['id_usuario'];
                $ec->observaciones = $obs;
                $ec->insert();
            }
        }

    }

}


/**
 * Formulario para Importar un Relato
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class PagImportaRelato extends HTML_QuickForm_Page
{

    /**
     * Constructora.
     *
     * @return void
     */
    function PagImportaRelato()
    {
        if (!isset($GLOBALS['dir_anexos'])) {
            die(_("Se requiere módulo anexos con variable dir_anexos"));
        }
        if (!is_writable($GLOBALS['dir_anexos'])) {
            die(sprintf(
                _("El directorio '%s' debería permitir escritura"),
                $GLOBALS['dir_anexos']
            ));
        }

        $ec =& objeto_tabla('caso_etiqueta');
        if (PEAR::isError($ec)) {
            echo _("Se requiere módulo etiquetas");
        }

        $this->HTML_QuickForm_Page('importaRelato', 'post', '_self', null);

        $this->addAction('importa', new AccionImportaRelato());
    }


    /**
     * Construye formulario
     *
     * @return void
     */
    function buildForm()
    {
        encabezado_envia();
        $this->_formBuilt = true;

        $e =& $this->addElement(
            'header', null,
            _('Importa Relatos')
        );

        //    $e =& $this->addElement('static', 'fini', 'Victimas ');

        $archivo_sel =& $this->addElement(
            'file', 'archivo_sel',
            _('Archivo con relatos')
        );
        $sel =& $this->addElement(
            'checkbox',
            'intensivo', 'Buscar victimas y grupos en otros casos',
            'Intensivo.  Requiere memoria y disco proporcional al '
            . 'número de casos en la base.'
        );

        /* $this->addGroup(
            $opch, null, 'Coordenadas', '&nbsp;', false
        ); */
        agrega_control_CSRF($this);

        $prevnext = array();
        $sel =& $this->createElement(
            'submit',
            $this->getButtonName('importa'), _('Importar')
        );
        $prevnext[] =& $sel;

        $this->addGroup($prevnext, null, '', '&nbsp;', false);


        $this->setDefaultAction('importa');

        $tpie = "<div align=right><a href=\"index.php\">" .
            _("Men&uacute; Principal") . "</a></div>";
        $e =& $this->addElement('header', null, $tpie);

    }

}

global $mreq;
$wizard = new HTML_QuickForm_Controller('Importa', false);
$consweb = new PagImportaRelato($mreq);

$wizard->addPage($consweb);

$wizard->addAction('display', new HTML_QuickForm_Action_Display());
$wizard->addAction('jump', new HTML_QuickForm_Action_Jump());

$wizard->addAction('process', new AccionImportaRelato());

$wizard->run();
?>
