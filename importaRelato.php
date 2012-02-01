<?php
//  vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
/**
 * Importa relato
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: importaRelato.php,v 1.36.2.4 2011/10/21 03:58:22 vtamara Exp $
 * @link      http://sivel.sf.net
 */

require_once "aut.php";
require_once $_SESSION['dirsitio'] . "/conf.php";
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
require_once 'DataObjects/Presuntos_responsables.php';
require_once 'DataObjects/Profesion.php';
require_once 'DataObjects/Rango_edad.php';
require_once 'DataObjects/Filiacion.php';
require_once 'DataObjects/Sector_social.php';
require_once 'DataObjects/Organizacion.php';
require_once 'DataObjects/Vinculo_estado.php';
require_once 'DataObjects/Tipo_sitio.php';
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
 * Responde a bot�n importar
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 */
class AccionImportaRelato extends HTML_QuickForm_Action
{

    /**
     * Ejecuta acci�n
     *
     * Basado en caso_detalles_sivel_remoto.php de Luca
     *
     * @param object &$page      P�gina
     * @param string $actionName Acci�n
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
            die("Debe haber una etiqueta IMPORTA_RELATO. "
                . " Favor <a href='actualiza.php'>actualizar</a>."
            );
        }
        $iderrorimportacion = (int)$db->getOne(
            "SELECT id FROM etiqueta "
            . " WHERE nombre = 'ERROR_IMPORTACI�N'"
        );
        if ($iderrorimportacion == 0) {
            die("Debe haber una etiqueta ERROR_IMPORTACI�N. "
                . " Favor <a href='actualiza.php'>actualizar</a>."
            );
        }

        // $pArchivo = var_post_escapa('archivo');

        // $f=$this->banexo->getForm();
        $p = $page->controller->_pages['importaRelato'];
        $e = $p->_submitFiles['archivo_sel'];

        $pArchivo = $e['name'];

        if ($e['size'] <= 0) {
            die("Tama�o de archivo debe ser mayor que 0");
        }
        move_uploaded_file(
            $e['tmp_name'], $GLOBALS['dir_anexos'] . "/" .
            $pArchivo
        );

        list($aper, $maxidper, $fechacaso, $ubicaso, $catcaso, $obscaso)
            = extrae_per($db);
        $agr = extrae_grupos($db, $fechacaso, $ubicaso, $catcaso, $obscaso);

        $cont = "";
        if (substr($pArchivo, strlen($pArchivo) - 3, 3) == '.gz') {
            if (!function_exists('readgzfile')) {
                die("Falta soporte para Zlib en su instalaci�n de PHP. "
                    . " Descomprima manualmente e importe el descomprimido"
                );
            }
            $narc = $GLOBALS['dir_anexos'] . "/$pArchivo";
            $cont = readgzfile($narc);
        } else {
            $narc = $GLOBALS['dir_anexos'] . "/$pArchivo";
            $cont = file_get_contents($narc);
        }

        $relatos = simplexml_load_string($cont);
        if (!$relatos) {
            $e = libxml_get_errors();
            var_dump($e);
            die("No pudo cargarse '" . $pArchivo . "'");
        }

        $yaesta = array(); // Indica cuales pesta�as ya importaron
        foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
            list($n, $c, $o) = $tab;
            $yaesta[$c] = false;
        }

        foreach ($relatos->relato as $r) {
            $obs = "";
            $id_presp = array();
            $id_pers = array();
            $id_vcol = array();
            $datgrupo = array();
            $dcaso = objeto_tabla('caso');
            if (isset($r->titulo)) {
                $dcaso->titulo = utf8_decode($r->titulo);
            }
            $dcaso->memo = ereg_replace(
                "\n", " ",
                utf8_decode(trim($r->hechos))
            );
            $dcaso->fecha = conv_fecha($r->fecha, $obs);
            if (isset($r->duracion) && $r->duracion != "") {
                $dcaso->duracion = $r->duracion;
            }
            if (isset($r->hora) && $r->hora!= "") {
                $dcaso->hora = $r->hora;
            }
            $pf = explode('-', $dcaso->fecha);
            $aniocaso = (int)$pf[0];
            $mescaso = (int)$pf[1];
            $diacaso = (int)$pf[2];
            $dcaso->id_intervalo = dato_basico_en_obs(
                $db,
                $obs, $r, 'intervalo', 'intervalo', '', 0
            );
            $dcaso->gr_confiabilidad = str_pad(
                dato_en_obs(
                    $r,
                    'gr_confiabilidad'
                ), 5
            );
            $dcaso->gr_esclarecimiento = str_pad(
                dato_en_obs(
                    $r,
                    'gr_esclarecimiento'
                ), 5
            );
            $dcaso->gr_impunidad = str_pad(
                dato_en_obs(
                    $r,
                    'gr_impunidad'
                ), 5
            );
            $dcaso->gr_informacion = str_pad(
                dato_en_obs(
                    $r,
                    'gr_informacion'
                ), 5
            );
            $dcaso->bienes = dato_en_obs($r, 'bienes');
            $dcaso->insert();
            $idcaso = $dcaso->id;
            if ($idcaso == 0) {
                die("idcaso es 0");
            }
            $yaesta['PagBasicos'] = true;
            $yaesta['PagMemo'] = true;
            $yaesta['PagEvaluacion'] = true;

            PagUbicacion::importaRelato($db, $r, $idcaso, $obs);
            $yaesta['PagUbicacion'] = true;

            PagFuentesFrecuentes::importaRelato(
                $db, $r, $idcaso,
                $obs
            );
            $idprensa = null;
            $nomf = utf8_decode($r->organizacion_responsable);
            $fecha = date('Y-m-d');
            $orgfuente = PagFuentesFrecuentes::busca_inserta(
                $db, $idcaso, $nomf, $fecha, 
                utf8_decode($r->id_relato),
                'Organizaci�n responsable incluida autom�ticamente',
                '', $obs
            );
            $yaesta['PagFuentesFrecuentes'] = true;
            $yaesta['modulos/anexos/PagFrecuenteAnexo'] = true;


            PagOtrasFuentes::importaRelato($db, $r, $idcaso, $obs);
            if (!$orgfuente) {
                $orgfuente = PagOtrasFuentes::busca_inserta(
                    $db, $idcaso, $nomf, $fecha, 
                    utf8_decode($r->id_relato),
                    'Organizaci�n responsable incluida autom�ticamente',
                    'Indirecta', $obs
                );
            }
            $yaesta['PagOtrasFuentes'] = true;
            $yaesta['modulos/anexos/PagOtraAnexo'] = true;

            PagTipoViolencia::importaRelato(
                $db, $r, $idcaso,
                $obs
            );
            $yaesta['PagTipoViolencia'] = true;


            // Grupo
            $pr = -1;
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
            foreach ($r->persona as $persona) {
                if (!empty($persona->nombre)) {
                    $anionac = null;
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
                        if ($edad != null) {
                            $anionac = $aniocaso - lnat_a_numero($edad);
                        }
                    }
                    $sexonac = 'S';
                    if (isset($persona->sexo)
                        && ($persona->sexo == 'M' || $persona->sexo == 'F')
                    ) {
                        $sexonac = $persona->sexo;
                    }
                    $ndep = dato_en_obs($persona, 'id_departamento');
                    $nmun = dato_en_obs($persona, 'id_municipio');
                    $ncla = dato_en_obs($persona, 'id_clase');
                    $idd = $idm = $idc = null;
                    if ($ndep != null || $nmun != null
                        || $ncla != null
                    ) {
                        list($idd, $idm, $idc) = conv_localizacion(
                            $db,
                            $ndep, $nmun, $ncla, $obs
                        );
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
                        utf8_decode($persona->nombre),
                        utf8_decode($persona->apellido), $anionac,
                        $sexonac, $idd, $idm, $idc, $tipo_documento,
                        $numero_documento
                    );
                    $id_pers[(string)$persona->id_persona] = $cper;
                }
            }
            foreach ($r->victima as $victima) {
                if (!empty($victima->id_persona)) {
                    if (!isset($id_pers[(string)$victima->id_persona])) {
                        repObs(
                            "Acto: No hay definida persona con id '" .
                            (string)$acto->id_victima_individual .  "'",
                            $obs
                        );
                    } else {
                        $dvictima = objeto_tabla('victima');
                        $dvictima->id_caso = (int)$idcaso;

                        $hijos = dato_en_obs($victima, 'hijos');
                        if ($hijos != null) {
                            $dvictima->hijos= lnat_a_numero($hijos);
                        }
                        $dvictima->id_persona
                            = $id_pers[(string)$victima->id_persona];
                        if ($edad != null) {
                            $dvictima->id_rango_edad = rango_de_edad($edad);
                        } else {
                            $dvictima->id_rango_edad
                                = DataObjects_Rango_edad::idSinInfo();
                        }
                        foreach (array('ocupacion' => 'profesion',
                            'iglesia' => 'filiacion',
                            'sector_condicion' => 'sector_social',
                            'organizacion' =>  'organizacion'
                        ) as $cr => $cs
                        ) {
                                //echo "OJO cr=$cr<br>";
                            $ncs = "id_" . $cs;
                            //echo "OJO ncs=$ncs<br>";
                            if (isset($victima->$cr)) {
                                $dvictima->$ncs = (int)convBasica(
                                    $db,
                                    "$cs",
                                    utf8_decode($victima->$cr),
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
                        'vinculo_estado' => 'vinculo_estado',
                        'organizacion_armada' => 'presuntos_responsables')
                        as $cs => $cs2
                    ) {
                            $ncs = "id_" . $cs;
                            //echo "OJO cs=$cs, ncs=$ncs<br>";
                        $v = dato_en_obs($victima, $cs); // ya hace utf8_decode
                        if ($v != null) {
                            $dvictima->$ncs = (int)convBasica(
                                $db, $cs2,
                                $v, $obs
                            );
                        }

                        //echo "OJO dvictima->ncs=" .  $dvictima->$ncs . "<br>";
                    }

                    if (!$dvictima->insert()) {
                        var_dump($dvictima);
                        repObs(
                            "No pudo insertar v�ctima '"
                            . $dvictima->id_persona . " "
                            . $dvictima->getMessage() . " "
                            .  $dvictima->getUserInfo()
                            . "'", $obs
                        );
                    }
                }
            }
            $yaesta['PagVictimaIndividual'] = true;

            // Actos
            foreach ($r->acto as $acto) {
                if (!empty($acto->agresion_particular)) {
                    $idp = (int)$acto->id_presunto_grupo_responsable;
                    $pr = null;
                    if (isset($id_presp[$idp])) {
                        // Ya registrado presunto responsable
                        $pr = $id_presp[$idp];
                    } else if (isset($datgrupo[$idp])) {
                        $g = $datgrupo[$idp];
                        $nomg = utf8_decode($g->nombre_grupo);
                        $pr = convBasica(
                            $db, 'presuntos_responsables',
                            $nomg, $obs
                        );
                        $id_presp[$idp] = $pr;
                        $dpresp = objeto_tabla('presuntos_responsables_caso');
                        $dpresp->id_caso = $idcaso;
                        $dpresp->id_p_responsable = $pr;
                        $dpresp->tipo = 0;
                        $dpresp->id = 1;
                        foreach (array('bloque', 'frente', 'brigada',
                            'batallon', 'division', 'otro'
                        ) as $c
                        ) {
                            $dpresp->$c = dato_en_obs($g, $c);
                        }
                        $ids = DataObjects_Presuntos_responsables::idSinInfo();
                        if ($pr == $ids
                            && $nomg != 'SIN INFORMACI�N'
                            && $nomg != 'SIN INFORMACION'
                        ) {
                            $dpresp->otro = $nomg;
                        }
                        if (!$dpresp->insert()) {
                            repObs(
                                "No pudo insertar p. resp '" .
                                $dpresp->id_p_responsable . "'",
                                $obs
                            );
                        }
                        foreach ($g->agresion_sin_vicd as $ag) {
                            if (!empty($ag)) {
                                $idc = conv_categoria(
                                    $db, $obs,
                                    utf8_decode((string)$ag), $pr
                                );
                                $ocp = objeto_tabla('categoria_p_responsable_caso');
                                $ocp->id_caso = $idcaso;
                                $ocp->id_p_responsable = $pr;
                                $ocp->id = $dpresp->id;
                                $ocp->id_categoria = $idc;
                                $ocat = objeto_tabla('Categoria');
                                $ocat->id = (int)$idc;
                                $ocat->find(1); $ocat->fetch();
                                if (PEAR::isError($ocat)) {
                                    repObs(
                                        "No se reconoci� categoria $ag",
                                        $obs
                                    );
                                } else {
                                    $ocp->id_tipo_violencia
                                        = $ocat->id_tipo_violencia;
                                    $ocp->id_supracategoria
                                        = $ocat->id_supracategoria;
                                    $ocp->insert();
                                }
                            }
                        }
                    } else {
                        repObs(
                            "No hay datos de p. resp. '" .
                            $idp . "'", $obs
                        );
                        break;
                    }
                    $id_categoria = conv_categoria(
                        $db, $obs,
                        utf8_decode($acto->agresion_particular), $pr
                    );
                    if ($id_categoria == 0) {
                        break;
                    }
                    //echo "OJO "; print_r($acto);
                    if (!empty($acto->id_victima_individual)) {
                        $dacto= objeto_tabla('acto');
                        $dacto->id_caso = $idcaso;
                        $dacto->id_p_responsable = $pr;
                        $dacto->id_categoria = $id_categoria;
                        if (!isset($id_pers[(string)$acto->id_victima_individual])) {
                            repObs(
                                "No hay definida persona con id. '" .
                                ((string)$acto->id_victima_individual) . "'",
                                $obs
                            );
                        } else {
                            $idvi = (string)$acto->id_victima_individual;
                            $dacto->id_persona = $id_pers[$idvi];
                            $dacto->insert();
                        }
                    } else if (!empty($acto->id_grupo_victima)) {
                        $ia = (string)$acto->id_grupo_victima;
                        $g = $datgrupo[$ia];
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
                        $dactocolectivo->id_p_responsable = $pr;
                        $dactocolectivo->id_categoria = $id_categoria;
                        $dactocolectivo->id_grupoper = $cg;
                        if (!$dactocolectivo->insert()) {
                            repObs(
                                "Acto: No pudo insertar acto col. '$cg', '"
                                . ((string)$acto->id_grupo_victima) . "'",
                                $obs
                            );
                        }
                    } else {
                        repObs("No es individual ni colectiva", $obs);
                        print_r($acto);
                    }
                } else {
                    repObs("Agresi�n particular vac�a", $obs);
                }
            }
            $yaesta['PagActo'] = true;

            // Completamos victimas colectivas suponiendo que tambi�n son
            // los grupos que no son presuntos responsables y que no fueron
            // nombrados como v�ctimas en actos
            foreach ($datgrupo as $idg => $g) {
                //echo "OJO revisando idg=$idg\n";
                if (!isset($id_presp[$idg]) && !isset($id_vcol[$idg])) {
                    //echo "OJO convirtiendo a victima colectiva idg=$idg\n";
                    $cg = conv_victima_col(
                        $db, $agr, $idcaso, $g,
                        $obs
                    );
                    $id_vcol[$idg] = $cg;
                }
            }

            foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
                list($n, $c, $o) = $tab;
                if (!$yaesta[$c]) {
                    if (($d = strrpos($c, "/"))>0) {
                        $c = substr($c, $d+1);
                    }
                    if (is_callable(array($c, 'importaRelato'))) {
                        call_user_func_array(
                            array($c, 'importaRelato'),
                            array($db, $r, $idcaso, $obs)
                        );
                    } else {
                        echo_esc("Falta importaRelato en $n, $c");
                    }
                }
            }
            funcionario_caso($idcaso);

            $html_rep = ResConsulta::reporteGeneralHtml(
                $idcaso, $db,
                $GLOBALS['cw_ncampos'] + array('m_fuentes' => 'Fuentes')
            );
            echo "<hr><pre>$html_rep</pre>";
            echo_esc("Observaciones: $obs");

            $ec = objeto_tabla('etiquetacaso');
            $ec->fecha = date('Y-M-d');
            $ec->id_caso = $idcaso;
            $ec->id_etiqueta = $idetiqueta;
            $ec->id_funcionario = $_SESSION['id_funcionario'];
            $ec->fecha = date('Y-M-d');
            $ec->observaciones = "";
            if (isset($r->id_relato)) {
                $ec->observaciones = utf8_decode(trim($r->id_relato));
            }
            $ec->insert();

            if (trim($obs) != '') {
                $ec = objeto_tabla('etiquetacaso');
                $ec->fecha = date('Y-M-d');
                $ec->id_caso = $idcaso;
                $ec->id_etiqueta = $iderrorimportacion;
                $ec->id_funcionario = $_SESSION['id_funcionario'];
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
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
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
            die("Se requiere m�dulo anexos con variable dir_anexos");
        }
        if (!is_writable($GLOBALS['dir_anexos'])) {
            die("El directorio '" . $GLOBALS['dir_anexos'] .
                " deber�a permitir escritura"
            );
        }

        $ec =& objeto_tabla('etiquetacaso');
        if (PEAR::isError($ec)) {
            echo "Se requiere m�dulo etiquetas";
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
            'Importa Relatos'
        );

        //    $e =& $this->addElement('static', 'fini', 'Victimas ');

        $archivo_sel =& $this->addElement(
            'file', 'archivo_sel',
            'Archivo con relatos'
        );

        agrega_control_CSRF($this);

        $prevnext = array();
        $sel =& $this->createElement(
            'submit',
            $this->getButtonName('importa'), 'Importar'
        );
        $prevnext[] =& $sel;

        $this->addGroup($prevnext, null, '', '&nbsp;', false);


        $this->setDefaultAction('importa');

        $tpie = "<div align=right><a href=\"index.php\">" .
            "Men� Principal</a></div>";
        $e =& $this->addElement('header', null, $tpie);

    }

}

$aut_usuario = "";
autenticaUsuario($dsn, $accno, $aut_usuario, 61);

$wizard =& new HTML_QuickForm_Controller('Importa', false);
$consweb = new PagImportaRelato($mreq);

$wizard->addPage($consweb);

$wizard->addAction('display', new HTML_QuickForm_Action_Display());
$wizard->addAction('jump', new HTML_QuickForm_Action_Jump());

$wizard->addAction('process', new AccionImportaRelato());

$wizard->run();
?>
