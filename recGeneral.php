<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Recupera información de un reporte general
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2015 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 */

/**
 * Recupera información de un reporte general
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

function reperror_txt($arc, $nlin, $mens) {
    echo "<font style='color: red'>$arc:$nlin: $mens</font><br>\n";
}

function pres_frecs($titulo, $frecs, $minsuma) 
{
    echo "$titulo<br>";
    $tc = 0;
    arsort($frecs);
    $sep = "  ";
    foreach($frecs as $v => $c) {
        echo $sep . "$v => $c";
        $tc += $c;
        $sep =", ";
    }
    echo "<br>  Suma de modificaciones: $tc<br>";
    if ($tc < $minsuma) {
        reperror_txt("", 0, "Suma de frecuencias debía ser superior a $minsuma");
    }
}

/**
 * @param object $obj     Dataobject
 * @param string $campo   CAmpo de obj
 * @param mixed  $nvalor  Nuevo valor de $obj->$campo
 * @param int    $depura  Nivel de depuración (mayor o igual a 2 muestra)
 * @param null|array  $frecs   Frecuencias de cambio de valores
 *
 * @return void
 */
function cambia_campo($obj, $campo, $nvalor, $depura = 0, &$frecs = null) 
{
    if ($obj->$campo != $nvalor) {
        if ($depura >= 2) {
            echo "Debe actualizar $campo de {$obj->$campo}" .
                "a $nvalor<br>";
        }
        $obj->campo = $nvalor;
        if (is_array($frecs)) {
            $frecs[$nvalor] = isset($frecs[$nvalor]) ? $frecs[$nvalor] + 1 : 1;
        }
    } else {
        if ($depura >= 2) {
            echo "No requiere actualizar $campo<br>";
        }
    }
}

function procesa_fuente(&$db, $linea, &$dff, &$ffexistente, &$ffex, 
    &$dof, &$dofc, &$ofexistente , &$ofex, $pArchivo, $nlin, $idcaso, 
    $depura, $obs) 
{
    if ($depura >= 4) {
        echo "Depura: linea=$linea<br>\n";
    }
    $a = preg_split('/ - /', $linea);
    if (count($a) < 3 || count($a) > 4) {
        reperror_txt($pArchivo, $nlin, 
            'Fuente extraña');
        die("x");
    }
    if (count($a) == 3) {
        $n = $a[0];
        $o = $a[1];
        $fecha = conv_fecha($a[2], $obs);
    } else {
        $n = $a[0] . " - " . $a[1];
        $o = $a[2];
        $fecha = conv_fecha($a[3], $obs);
    }
    if ($depura >= 3) {
        echo "Depura: fuente vista n=$n, o=$o, fecha=$fecha<br>\n";
    }
    $idff = (int)conv_basica($db, 'ffrecuente', $n, $obs);
    if ($depura >= 3) {
        echo "Depura: ffrecuente convertida $idff<br>\n";
    }
    if ($idff != -1) {
        $f = objeto_tabla('ffrecuente');
        $f->id = $idff;
        $f->find();
        $f->fetch();
        if ($f->nombre == $n) { 
            if ($depura >= 3) {
                echo "Depura: confirmado ffrecuente convertida $idff<br>\n";
            }
            $d = objeto_tabla('caso_ffrecuente');
            $d->id_caso = $idcaso;
            $d->id_ffrecuente = $idff;
            $d->fecha = $fecha;
            $d->find();
            if ($d->fetch()) {
                $ffexistente[] = true;
            } else {
                $ffexistente[] = false;
            }
            cambia_campo($d, 'ubicacion', $o, $depura, $ffex);
            $dff[] = $d;
        } else {
            $idff = -1;
        }
    }
    if ($idff == 1) {
        //Intentando no frecuente
        if ($depura >= 3) {
            echo "Depura: intentando $n como fotra<br>\n";
        }
        $idof = (int)conv_basica($db, 'fotra', $n, $obs);
        if ($depura >= 3) {
            echo "Depura: fotra convertida $idof<br>\n";
        }
        if ($idof != -1) {
            $f = objeto_tabla('fotra');
            $f->id = $idof;
            $f->find();
            $f->fetch();
            if ($f->nombre == $n) { 
                if ($depura >= 3) {
                    echo "Depura: confirmado fotra convertida $idof<br>\n";
                }
                $d = objeto_tabla('caso_fotra');
                $d->id_caso = $idcaso;
                $d->id_fotra = $idof;
                $d->fecha = $fecha;
                $d->find();
                if ($d->fetch()) {
                    $ofexistente[] = true;
                } else {
                    $ofexistente[] = false;
                }
                cambia_campo($d, 'ubicacionfisica', $o, $depura, $ofex);
                $dofc[] = $d;
                $dof[] = null;
            } else {
                $idof = -1;
            }
        }
        if ($idof == -1) {
            if ($depura >= 3) {
                echo "Depura: creando nueva fotra $n, $o, $fecha<br>\n";
            }
            $ofexistente[] = false;
            $f = objeto_tabla('fotra');
            $f->id = null;
            $f->nombre = $n;
            $d = objeto_tabla('caso_fotra');
            $d->id_caso = $idcaso;
            $d->id_fotra = -1;
            $d->fecha = $fecha;
            cambia_campo($d, 'ubicacionfisica', $o, $depura, $ofex);
            $dofc[] = $d;
            $dof[] = $f;
        }
    }
}


/**
 * @return void
 */
function verifica_categoria($idc, $pArchivo, $nlin)
{
    $dcategoria = objeto_tabla('categoria');
    $dcategoria->id = substr($idc, 1);
    $dcategoria->find();
    if (!$dcategoria->fetch()) {
        reperror_txt($pArchivo, $nlin, "Categoria errada $idc");
        die("x");
    }
    $tc = $dcategoria->tipocat;
    $dcategoria->free();
    return $tc;
}

function proc_categorias($cats, $pArchivo, $nlin)
{
    global $_proc_categorias_pa;
    $_proc_categorias_pa = $pArchivo;
    global $_proc_categorias_nl;
    $_proc_categorias_nl = $nlin;
    global $_proc_categorias_tc;
    $_proc_categorias_tc = null;
    $lc = preg_split('/ \/ /', trim($cats));
    $lidc = array_map(function($scod) {
        global $_proc_categorias_pa;
        global $_proc_categorias_nl;
        global $_proc_categorias_tc;
        $tc = verifica_categoria($scod, $_proc_categorias_pa, $_proc_categorias_nl);
        if ($_proc_categorias_tc == null) {
            $_proc_categorias_tc = $tc;
        } else if ($_proc_categorias_tc != $tc) {
            reperror_txt($pArchivo, $nlin, "Categoria anterior era {$_proc_categoria_tc} pero esta es $tc");
            die("x");
        };
        return [(int)substr($scod, 1), $tc];
    } , $lc);
    return $lidc;
}

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
    function perform()
    {
        encabezado_envia("Recupera de General");
        $dcaso = objeto_tabla('caso');

        $db =& $dcaso->getDatabaseConnection();

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

        //$narc = $GLOBALS['dir_anexos'] . "/$pArchivo";
        //$lineas = file($narc);
        $pArchivo = $narc = 
            '/htdocs/sivel/sitios/sivel12/recupera-31.ago.2015/revision.txt';
        $lineas = file($narc);
        if ($lineas === FALSE) {
            die_esc('No se pudo leer ' . $pArchivo);
        }

        $depura = 5; 
        // 1 Existente o no
        // 2 cambios a base de datos
        // 3 variables internas
        // 5 estados       
        // 6 líneas


        hace_consulta($db, 'DROP MATERIALIZED VIEW IF EXISTS persona_nomap' );
        hace_consulta($db, 'CREATE MATERIALIZED VIEW persona_nomap 
            AS (SELECT id, UPPER(TRIM(nombres || \' \' || apellidos)) AS nomap
            FROM persona)'
        );
        // Conteos para depurar
        $ncasos = 0;
        $cuentaexistentes = 0;
        $cuentanoexistentes = 0;
        $fechasex = array(); 
        $horasex = array();
        $intervalosex = array();
        $tsitiosex = array(); 
        $regionesex = array(); 
        $fronterasex = array(); 
        $depex = array(); 
        $munex = array(); 
        $claex = array(); 
        $ffex = array(); 
        $ofex = array(); 
        $memosex = array(); 
        $contextosex = array(); 
        $presponsabelesex = array(); 
        $etiquetaex = array();
        $usuarioex = array();
        $victimasssex = array();
        $victimasprofex = array();
        $victimashijex = array();
        $victimasfilex = array();
        $victimasanotex = array();
        $victimasorgex = array();
        $vic_nuevas = 0;
        $vic_existentes = 0;

        // Por inicializar con cada caso
        // Debe repetirse en ultimo estado
        
        $caso_existente = false;
        $ubicacion_existente = false;
        $caso_region_existente = false;
        $caso_frontera_existente = false;
        $caso_contexto_existente = array();
        $caso_presponsable_existente = array();
        $victima_existente = array();
        $caso_etiqueta_existente = array();
        $caso_usuario_existente = array();

        $lvicr = null; // Lista de victimas repetidas

        $idcaso = 0;
        $obs = ""; // Observaciones por poner en etiqueta error_importacion
        $dubicacion = null;
        $dcaso_region = null;
        $dcaso_frontera = null;
        $dff = array(); // Fuentes frecuentes
        $dffexistente = array();
        $dof = array(); // Otras fuentes_caso
        $dofc = array(); // Otras fuentes
        $dofexistente = array();
        $dcategoria = null;
        $dcaso_contexto = array();
        $dcaso_presponsable = array();
        $dvictima_esp = array();
        $dcaso_etiqueta = array();
        $dcaso_usuario = array();

        $estado = 0; // esperando  CASO No.
        // 1 esperando FECHA:
        // 2 esperando Región: (puede no haber)
        // 3 reconocer Frontera: y/o depto / mcpio
        // 4 Fuente: (puede no haber)
        // 5 Fuente
        // 6 Memo: 
        // 7 Memo: 
        // 8 Contexto
        // 9 Categorias
        // 10 Categorias
        // 13 Etiquetas
        // 14 Etiquetas
        // 15 Analistas
        // -1 error no recuperable
        foreach ($lineas as $nlin => $linea) {
            if ($depura >= 6) {
                echo "Depura: línea $nlin, estado=$estado<br>";
            }
            switch ($estado) {
            case 0: 
                if (comienza_con($linea, 'CASO No. ')) {
                    $idcaso = intval(substr($linea, 9));
                    if ($idcaso <= 0) {
                        reperror_txt($pArchivo, $nlin, 
                            'Número de caso menor o igual a 0, salta');
                        $estado = -1;
                        break;
                    } else {
                        if ($depura >=3) {
                            echo "Depura: idcaso detectado $idcaso<br>\n";
                        }
                        //echo "<script>alert('idcaso detectado $idcaso');</script>\n";
                        $ncasos++;
                        if ($dcaso != null) {
                            $dcaso->free();
                        }
                        $obs = ""; // Observaciones por incluir en caso
                        $dcaso = objeto_tabla('caso');
                        $dcaso->id = $idcaso;
                        $dcaso->find();
                        if ($dcaso->fetch()) {
                            $caso_existente = true;
                            $cuentaexistentes++;
                            if ($depura >= 1) {
                                echo "Caso $ncasos existente $idcaso<br>";
                            }
                        } else {
                            $caso_existente = false;
                            $cuentanoexistentes++;
                            if ($depura >= 1) {
                                echo "Caso $ncasos no existente $idcaso<br>";
                            }
                        }
                        $estado = 1;
                    }
                }
                break;
            case 1:
                if (comienza_con($linea, 'FECHA: ')) {
                    $pal = explode(' ', $linea);
                    $fechac = $pal[1];
                    if ($depura >= 3) {
                        echo "Depura: fechac detectada $fechac<br>\n";
                    }
                    $obs = "";
                    $fechac = conv_fecha($fechac, $obs);
                    if ($obs != "") {
                        reperror_txt($pArchivo, $nlin, $obs);
                        $estado = -1;
                        break;
                    }  else {
                        if ($depura >= 3) {
                            echo "Depura: fecha reconocida $fechac<br>\n";
                        }
                        cambia_campo($dcaso, 'fecha', $fechac, $depura, $fechasex);
                    }
                    $sig = 2;
                    if (strpos($pal[2], ':') !== FALSE) {
                        // hora
                        if ($pal[3] == 'AM'  || $pal[3] == 'PM') {
                            $horac = $pal[2] . " " . $pal[3];
                            $sig = 4;
                        } else {
                            $horac = $pal[2];
                            $sig = 3;
                        }
                        if ($depura >= 3) {
                            echo "Depura: horac detectada $horac<br>\n";
                        }
                        cambia_campo($dcaso, 'hora', $horac, $depura, $horasex);
                    }
                    $int = $sep = "";
                    while ($sig + 2 < count($pal) && $pal[$sig] != 'Tip.') {
                        $int .= $sep . $pal[$sig];
                        $sep = " ";
                        $sig++;
                    }
                    if ($pal[$sig] != 'Tip.' || $pal[$sig + 1] != 'Ub:' 
                        || !isset($pal[$sig + 2])
                    ) {
                        print_r($pal);
                        echo " sig = $sig ";
                        reperror_txt($pArchivo, $nlin, 
                            'No se encuentra Tip. Ub: ');
                        $estado = -1;
                        break;
                    } else {
                        if ($depura >= 3) {
                            echo "Depura: intervalo detectado $int<br>\n";
                        }
                        $int = (int)conv_basica($db, 'intervalo', $int, $obs);
                        if ($depura >= 3) {
                            echo "Depura: intervalo convertido $int<br>\n";
                        }
                        if ($int >= 1 && $int <= 5) {
                            cambia_campo($dcaso, 'id_intervalo', $int, $depura, 
                                $intervalosex);
                        } else {
                            reperror_txt($pArchivo, $nlin, 
                                'Intervalo loco $int');
                            $estado = -1;
                            break;
                        }
                        $tsitio = $pal[$sig + 2];
                        if ($depura >= 3) {
                            echo "Depura: tsitio detectado $tsitio <br>\n";
                        }
                        $tsitio = (int)conv_basica($db, 'tsitio', $tsitio, $obs);
                        if ($depura >= 3) {
                            echo "Depura: tsitio convertido $tsitio<br>\n";
                        }
                        if ($dubicacion != null) {
                            $dubicacion->free();
                        }
                        $dubicacion  = objeto_tabla('ubicacion');
                        $dubicacion->id_caso = $idcaso;
                        if ($caso_existente) {
                            $dubicacion->find();
                            if (!$dubicacion->fetch()) {
                                $obs .= " Caso existente pero sin ubicación.";
                                $ubicacion_existente = false;
                            } else {
                                $ubicacion_existente = true;
                                if ($dubicacion->fetch()) {
                                    reperror_txt($pArchivo, $nlin, 
                                        'Caso existente tiene más de una ubicación, corregir antes');
                                    $estado = -1;
                                    break;
                                }
                            }
                        }
                        cambia_campo($dubicacion, 'id_tsitio', $tsitio, $depura, 
                            $tsitiosex);
                        $estado = 2;
                    }
                }
                break;

            case 2:
                if (comienza_con($linea, 'Región: ')) {
                    $rg = substr($linea, 8);
                    if ($depura >= 3) {
                        echo "Depura: región detectada $rg<br>\n";
                    }
                    $rg = (int)conv_basica($db, 'region', $rg, $obs);
                    if ($depura >= 3) {
                        echo "Depura: región convertida $rg<br>\n";
                    }
                    if ($rg>= 5 && $rg <= 11) {
                        if ($dcaso_region != null) {
                            $dcaso_region->free();
                        }
                        $dcaso_region = objeto_tabla('caso_region');
                        $dcaso_region->id_caso = $idcaso;
                        $dcaso_region->id_region = $rg;
                        $dcaso_region->find();
                        if (!$dcaso_region->fetch()) {
                            $caso_region_existente = false;
                            $regionesex[$rg] = isset($regionesex[$rg]) ?
                                $regionesex[$rg] + 1 : 1;
                            if ($depura >= 2) {
                                echo "Depura: por insertar región $rg<br>\n";
                            }
                        } else  {
                            $caso_region_existente = true;
                        }
                        $estado = 3;
                        break;
                    } else {
                        reperror_txt($pArchivo, $nlin, "Región errada $rg");
                        $estado = -1;
                        break;
                    }
                    break;
                } else if (trim($linea) != "") {
                    // No tiene región, pasar a localización 
                    $estado = 3;
                    // sin break
                } else {
                    break;
                }
                // break;  No hacemos break por si debe pasar de 2 a 3

            case 3:
                if (comienza_con($linea, "Frontera: ")) {
                    $fr = substr($linea, 10);
                    if ($depura >= 3) {
                        echo "Depura: frontera detectada $fr<br>\n";
                    }
                    $fr = (int)conv_basica($db, 'frontera', $fr, $obs);
                    if ($depura >= 3) {
                        echo "Depura: frontera convertida $rg<br>\n";
                    }
                    if ($fr>= 1) {
                        if ($dcaso_frontera != null) {
                            $dcaso_frontera->free();
                        }
                        $dcaso_frontera = objeto_tabla('caso_frontera');
                        $dcaso_frontera->id_caso = $idcaso;
                        $dcaso_frontera->id_frontera = $fr;
                        $dcaso_frontera->find();
                        if (!$dcaso_frontera->fetch()) {
                            $caso_frontera_existente = false;
                            $fronterasex[$fr] = isset($fronterasex[$fr]) ?
                                $fronterasex[$fr] + 1 : 1;
                            if ($depura >= 2) {
                                echo "Depura: por insertar frontera $fr<br>\n";
                            }
                        } else  {
                            $caso_frontera_existente = true;
                        }
                        $estado = 3; //seguir aqui para depto/mun
                    } else {
                        reperror_txt($pArchivo, $nlin, "Frontera errada $rg");
                        $estado = -1;
                    }
                    break;

                } else {
                    $p = explode("/", $linea);
                    $loc = null;
                    if (count($p) == 1) {
                        $loc = conv_localizacion($db, trim($p[0]), null, null, $obs);
                    } else if (count($p) == 2) {
                        $loc = conv_localizacion($db, trim($p[0]), trim($p[1]), null, $obs);
                    } else if (count($p) == 3) {
                        $loc = conv_localizacion($db, trim($p[0]), trim($p[1]), 
                        trim($p[2]), $obs);
                    } else {
                        reperror_txt($pArchivo, $nlin, "Localización errada $linea");
                        $estado = -1;
                        break;
                    }

                    if ($depura >= 3) {
                        echo "Depura, localización: ";
                        print_r($loc);
                        echo "<br>";
                    }
                    if (count($loc)<1 || count($loc)>3) {
                        reperror_txt($pArchivo, $nlin, "Localización errada $linea");
                        $estado = -1;
                        break;
                    }
                    cambia_campo($dubicacion, 'id_departamento', $loc[0], $depura, 
                        $depex);
                    if (count($loc) >= 2) {
                        cambia_campo($dubicacion, 'id_municipio', $loc[1], $depura, 
                            $munex);
                        if (count($loc) >= 3) {
                            cambia_campo($dubicacion, 'id_clase', $loc[2], 
                                $depura, $claex);
                        }
                    }
                    $estado = 4;
                }
                break;
            case 4: // Esperando y reconociendo primera fuente
                if (comienza_con($linea, 'Fuente:')) {
                    procesa_fuente($db, substr($linea, 8), 
                    $dff, $ffexistente, $ffex, 
                    $dof, $dofc, $ofexistente , $ofex, $pArchivo, $nlin, 
                    $idcaso, $depura, $obs);
                    
                    $estado = 5;
                    break;
                } else if (comienza_con($linea, 'Memo:')) {
                    $memo = "";
                    $sep = "";
                    $estado = 7;
                    break;
                }
                if (trim($linea) != '') {
                    // Había error en reporte genera faltaba Fuente: cuando 
                    // había solo no frecuentes
                    // Suponiendo que estamos en no frecuentes
                    procesa_fuente($db, $linea, $dff, $ffexistente, $ffex, 
                        $dof, $dofc, $ofexistente , $ofex, $pArchivo, $nlin, 
                        $idcaso, $depura, $obs);
                    //sigue en 5
                     
                    $estado = 5;
                    break;

                }
                break;
            case 5: // Procesando de segunda fuente en adelante
                if (trim($linea) == '') {
                    $estado = 6;
                    break;
                } else {
                    procesa_fuente($db, $linea, $dff, $ffexistente, $ffex, 
                        $dof, $dofc, $ofexistente , $ofex, $pArchivo, $nlin, 
                        $idcaso, $depura, $obs);
                    //sigue en 5
                }

                break;
            case 6: // Esperando Memo:
                if (comienza_con($linea, 'Memo:')) {
                    $memo = "";
                    $sep = "";
                    $estado = 7;
                    break;
                }
                break;
            case 7: // Procesando Memo
                if (trim($linea) == '') {
                    $sep = "\n";
                    // Sigue en 7
                    break;
                } if (comienza_con($linea, 'Contexto: ')) {
                    $estado = 8;
                    // Pase
                } else if (comienza_con($linea, 'ANALISTA(s):')) {
                    $estado = 15;
                    break;
                } else {
                    $l = explode('.', $linea);
                    $salta = false;
                    if (count($l) == 2) {
                        $idc = $l[0];
                        if ($depura >= 3) {
                            echo "Depura, posible idc: $idc<br>";
                        }
                        $pc = explode('-', $l[1]);
                        if (strlen($idc) < 10 && count($pc) >= 2) {
                            $estado = 8;
                            $salta = true;
                        }
                    }
                    if (!$salta) {
                        if ($depura >= 3) {
                            echo "Depura: memo aumentado $linea<br>\n";
                        }
                        $memo .= $sep . $linea;
                        $sep = " ";
                        // Sigue en 7
                        break;
                    }
                }
                // sin break porque puede pasar a 8
            case 8: // Procesamiento de contexto si hay 
                if (trim($memo) != "") {
                    cambia_campo($dcaso, 'memo', $memo, $depura, $memosex);
                    $memo = "";
                }
                if (trim($linea) == '') {
                    // sigue en 8
                    break;
                } else if (comienza_con($linea, 'Contexto: ')) {
                    if (count($dcaso_contexto) > 0) {
                        foreach($dcaso_contexto as $dc) {
                            if ($dc != null) {
                                $dc->free();
                            }
                        }
                        $dcaso_contexto = array();
                        $caso_contexto_existente = array();
                    }
                    $mc = substr($linea, 10);
                    if ($depura >= 3) {
                        echo "Depura: contexto(s) detectados $mc<br>\n";
                    }
                    $lc = preg_split('/, /', $mc);
                    foreach($lc as $c) {
                        $con = (int)conv_basica($db, 'contexto', $c, $obs);
                        if ($depura >= 3) {
                            echo "Depura: contexto convertido $con<br>\n";
                        }
                        if ($con <= 0) {
                            reperror_txt($pArchivo, $nlin, "Contexto errado $c");
                            $estado = -1;
                            break;
                        }
                        $dc = objeto_tabla('caso_contexto');
                        $dc->id_caso = $idcaso;
                        $dc->id_contexto = $con;
                        $dc->find();
                        if (!$dc->fetch()) {
                            $caso_contexto_existente[] = false;
                            $contextosex[$con] = isset($contextosex[$con]) ?
                                $contextosex[$con] + 1 : 1;
                            if ($depura >= 2) {
                                echo "Depura: por insertar caso_contexto $con<br>\n";
                            }
                        } else  {
                            $caso_contexto_existente[] = true;
                        }
                        $dcaso_contexto[] = $dc;
                    }
                    $estado = 9;
                    break;
                } else if (comienza_con($linea, 'ANALISTA(s):')) {
                    $estado = 15;
                    break;
                } else {
                    $estado = 9;
                }
                // break; no hay porque pasa de 8 a 9
            case 9: // Procesamiento de categorias
                if (trim($linea) == '') {
                    $estado = 11;
                } else {
                    if ($depura >= 3) {
                        echo "Depura, categoria: $linea<br>";
                    }
                    $l = explode('.', $linea);
                    $idc = $l[0];
                    if ($depura >= 3) {
                        echo "Depura, idc: $idc<br>";
                    }
                    verifica_categoria($idc, $pArchivo, $nlin);
                    // Sigue en 9
                }
                break;
            case 10:
                // Víctimas y/o categorias
                if (comienza_con($linea, 'Presunto Responsable: ') ||
                    comienza_con($linea, 'Presuntos Responsables: ')
                ) {
                     if ($depura >= 3) {
                        echo "Depura, OJO procesar lidp y lidc si hay<br>";
                    }

                     $estado = 11;
                } else if (comienza_con($linea, 'ANALISTA(s):')) {
                    $estado = 15;
                    break;
                } else if (comienza_con($linea, 'Etiquetas:')) {
                    $estado = 14;
                    break;
                } else if (trim($linea) == '') {
                    // sigue en 10
                    break;
                } else {
                    if ($depura >= 3) {
                        echo "Depura, estado 10, linea = $linea<br>";
                    }
                    if (strpos($linea, " / ") !== FALSE ||
                        preg_match("/ [A-Z][0-9]+/", $linea)
                    ) { // Cambio de categorias, manteniendo mismos PR
                        $lidc = proc_categorias(trim($linea), $pArchivo, $nlin);
                        if ($depura >= 3) {
                            echo "Depura, nuevo lidc=";
                            print_r($lidc);
                            echo "<br>";
                        }
                        // Continuamos en 10
                        $estado = 10;
                        break;
                    }
                    if (count($lidc) == 0) {
                            reperror_txt($pArchivo, $nlin, "Sin categoria $linea");
                            $estado = -1;
                            break;
                    }
                    if ($lidc[0][1] == 'I') {
                        // Viene víctima individual
                        $l = $linea;
                        $id_organizacion= null;
                        $coi = array();
                        if (preg_match( 
                            '/^(.*) Organización Social: (.*)$/', $l, $coi)
                        ) {
                            $orgsocial = $coi[2];
                            $l = $coi[1];
                            if ($depura >= 3) {
                                echo "Encontrad org. social $orgsocial, l=$l<br>";
                            }
                            $id_organizacion = (int)conv_basica($db, 'organizacion', 
                                $orgsocial, $obs);
                            if ($id_organizacion < 0) {
                                reperror_txt($pArchivo, $nlin, 
                                    "No hay organización $id_organizacion en $l"
                                );
                                $estado = -1;
                                break;
                            }
                            if ($depura >= 3) {
                                echo "Depura, organización es $id_organizacion<br>";
                            }

                        }
                        $anotaciones = null;
                        $coi = array();
                        if (preg_match('/^(.*) Anotaciones: (.*)$/', $l, $coi)) {
                            $anotaciones= $coi[2];
                            $l = $coi[1];
                            if ($depura >= 3) {
                                echo "Encontradas Anotaciones $anotaciones, l=$l<br>";
                            }
                        }
                        $id_filiacion = null;
                        $coi = array();
                        if (preg_match(
                            '/^(.*) Filiación Política: (.*) *\. *$/', $l, $coi)
                        ) {
                            $fpol= $coi[2];
                            $l = $coi[1];
                            if ($depura >= 3) {
                                echo "Encontrada Fil. Pol. $fpol, l=$l<br>";
                            }
                            $id_filiacion = (int)conv_basica($db, 'filiacion', 
                                $fpol, $obs);
                            if ($id_filiacion < 0) {
                                reperror_txt($pArchivo, $nlin, 
                                    "No hay filiacion $id_filiacion en $l"
                                );
                                $estado = -1;
                                break;
                            }
                            if ($depura >= 3) {
                                echo "Depura, filiacion es $id_ss<br>";
                            }
                        }

                        $coi = array();
                        $nhijos = null;
                        if (preg_match('/^(.*) ([0-9]+) hijos/', $l, $coi)) {
                            $nhijos = $coi[2];
                            $l = $coi[1];
                            if ($depura >= 3) {
                                echo "Depura, hijos $nhijos, l=$l<br>";
                            }
                        }
                        $pper = preg_split('/ - /', $l);
                        $nper = trim($pper[0]);
                        if ($depura >= 3) {
                            echo "Depura, nomap $nper, l=$l<br>";
                        }
                        $prof = null;
                        $id_prof = null;
                        $id_ss = null;
                        if (count($pper) > 3) {
                            reperror_txt($pArchivo, $nlin, "Mas de 3 partes $l");
                            $estado = -1;
                            break;
                        } else if (count($pper) == 3) {
                            $ssol = $pper[1];
                            $id_ss = (int)conv_basica($db, 'sectorsocial', 
                                $ssol, $obs);
                            if ($id_ss < 0) {
                                reperror_txt($pArchivo, $nlin, 
                                    "No hay sector social $ssol en $l"
                                );
                                $estado = -1;
                                break;
                            }
                            if ($depura >= 3) {
                                echo "Depura, ssol es $id_ss<br>";
                            }
                            $prof = $pper[2];
                            $id_prof = (int)conv_basica($db, 'profesion', 
                                $prof , $obs);
                            if ($id_prof < 0) {
                                reperror_txt($pArchivo, $nlin, 
                                    "No hay profesión $prof en $l");
                                $estado = -1;
                                break;
                            }
                            if ($depura >= 3) {
                                echo "Depura, prof es $id_prof<br>";
                            }
                        } else if (count($pper) == 2) {
                            $id_ss = null;
                            $id_prof = (int)conv_basica($db, 'profesion', 
                                $pper[1], $obs);
                            if ($id_prof < 0) {
                                $id_prof = null;
                                if ($depura >= 3) {
                                    echo "Depura, no profesión {$pper[1]}<br>";
                                }
                                $id_ss = (int)conv_basica($db, 'sectorsocial', 
                                    $pper[1], $obs);
                                if ($id_ss < 0) {
                                    reperror_txt($pArchivo, $nlin, 
                                        "Ni ss ni prof {$pper[1]}");
                                    $estado = -1;
                                    break;
                                }
                                if ($depura >= 3) {
                                    echo "Depura, es ss {$pper[1]}<br>";
                                }
                            } else {
                                if ($depura >= 3) {
                                    echo "Depura, es prof {$pper[1]}<br>";
                                }
                            }
                        }

                        $pna = preg_split('/  */', a_mayusculas(trim($nper)));
                        $pnom = null;
                        $pap = null;
                        if (count($pna) >= 4) {
                            $sepn = '';
                            $sepa = ' ';
                            for($i = 0; $i < count($pna); $i++) {
                                if ($i < count($pna) - 2) {
                                    $pnom .= $sepn . $pna[$i];
                                    $sepn = ' ';
                                } else {
                                    $pap .= $sepa . $pna[$i];
                                    $sepa = ' ';
                                }
                            }
                            if ($depura >= 3) {
                                echo "Depura, na4, $pnom, $pap<br>";
                            }
                        } else if (count($pna) == 3) {
                            $pnom = $pna[0] + ' ' + $pna[1];
                            $pap = $pna[2];
                            if ($depura >= 3) {
                                echo "Depura, na3, $pnom, $pap<br>";
                            }
                        } else if (count($pna) == 2) {
                            $pnom = $pna[0];
                            $pap = $pna[1];
                            if ($depura >= 3) {
                                echo "Depura, na2, $pnom, $pap<br>";
                            }
                        } else if (count($pna) == 1) {
                            $pnom = $pna[0];
                            $pap = null;
                            if ($depura >= 3) {
                                echo "Depura, na1, $pnom<br>";
                            }
                        } else {
                            print_r($pna);
                            reperror_txt($pArchivo, $nlin, "Sin nombre $l");
                            $estado = -1;
                            break;
                        }
                        if ($nper == 'N N' || $nper == 'PERSONA SIN IDENTIFICAR') {
                            $nper = 'N N';
                            $pnom = 'N';
                            $pap = 'N';
                        }
                        $q = "SELECT victima.id_persona 
                            FROM victima, persona_nomap 
                            WHERE 
                            victima.id_caso='$idcaso' AND
                            victima.id_persona=persona_nomap.id AND
                            nomap='" . a_mayusculas(var_escapa($nper)) .  "'";
                        if ($depura >= 3) {
                            echo "Depura, q=$q<br>";
                        }

                        $dv = objeto_tabla('victima');
                        $dv->id_caso = $idcaso;
                        $r = hace_consulta($db, $q);
                        if ($r->numRows() >= 1) {
                            if ($r->numRows() > 1) {
                                if ($depura >= 3) {
                                    echo "Depura, hay varias víctima para $nper<br>";
                                }
                                // $lvicr comienza en null para cada caso
                                if ($lvicr == null) {
                                    $lvicr = array();
                                    while ($r->fetchInto($row)) {
                                        $lvicr[] = $row[0];
                                    }
                                }
                                if (count($lvicr) == 0) {
                                    reperror_txt($pArchivo, $nlin, 
                                        "Se agotó lvicr, puede haber varios grupos de víctimas repetidas que no se maneja<br>");
                                    $estado = -1;
                                    break;
                                }
                                $dv->id_persona = array_shift($lvicr);
                                $dv->find();
                                $dv->fetch();
                                // Escoger un $dv 
                                break;
                            } else {
                                echo "OJO numRows=" . $r->numRows() . "<br>";
                                $row = array();
                                $r->fetchInto($row);
                                if (isset($row[0])) {
                                    $dv->id_persona = $row[0];
                                } else {
                                    print_r($row);
                                    reperror_txt($pArchivo, $nlin, "row[0] no definido");
                                    $estado = -1;
                                    break;
                                }
                                $dv->find();
                                $dv->fetch();
                                if ($depura >= 3) {
                                    echo "Depura, hay una víctima para $nper<br>";
                                }
                            }
                            $dp = $dv->getLink('id_persona');
                            cambia_campo($dv, 'id_sectorsocial', 
                                $id_ss, $depura, $victimasssex);
                            cambia_campo($dv, 'id_profesion', 
                                $id_prof, $depura, $victimasprofex);
                            cambia_campo($dv, 'hijos', 
                                $nhijos, $depura, $victimashijex);
                            cambia_campo($dv, 'filiacion', 
                                $id_filiacion, $depura, $victimasfilex);
                            cambia_campo($dv, 'anotaciones', 
                                $anotaciones, $depura, $victimasanotex);
                            cambia_campo($dv, 'organizacion', 
                                $id_organizacion, $depura, $victimasorgex);
                            $vic_existentes++;
                            $victima_existente[] = true;

                        } else {
                            if ($depura >= 3) {
                                echo "Depura, insertar per y vic $nper<br>";
                                $vic_nuevas++;
                            }
                            $dv = objeto_tabla('victima');
                            $dv->id_caso = $idcaso;
                            $dv->id_profesion = $id_prof;
                            $dv->id_sectorsocial = $id_ss;
                            $dv->hijos = $nhijos;

                            $dp = objeto_tabla('persona');
                            $dp->nombres = $pnom;
                            $dv->apellidos = $pap;
                            $dv->sexo = 'S';

                            $victima_existente[] = false;
                        }
                        $dvictima_esp[] = [$dv, $dp, $lidp, $lidc];

                    } else if ($lidc[0][1] == 'C') {
                        $personasaprox = null;
                        $coi = array();
                        if (preg_match('/^ *(.*) \(([0-9]+)\)/', $linea, $coi)) {
                            $personasaprox = $coi[2];
                            $nombre = a_mayuscuals($coi[1])
                            if ($depura >= 3) {
                                echo "Depura, personasapxo $personasaprox<br>";
                            }
                        } else {
                            $nombre = a_mayusculas(trim($linea));
                        }
                        $q = "SELECT victimacolectiva.id_grupoper
                            FROM victimacolectiva, grupoper
                            WHERE 
                            victimacolectiva.id_caso='$idcaso' AND
                            victimacolectiva.id_grupoper=grupoper.id AND
                            UPPER(nombre)='" . var_escapa($nombre) .  "'";
                        if ($depura >= 3) {
                            echo "Depura, q=$q<br>";
                        }
                        $dvc = objeto_tabla('victimacolectiva');
                        $dvc->id_caso = $idcaso;
                        $r = hace_consulta($db, $q);
                        if ($r->numRows() > 1) {
                            reperror_txt($pArchivo, $nlin, "colectivas repetidas no soportado");
                            $estado = -1;
                            break;
                        }
                        if ($r->numRows() == 1) {
                            if ($depura >= 3) {
                                echo "Victima colectiva encontrada<br>";
                            }
                            $row = array();
                            $r->fetchInto($row);
                            $dvc->id_grupoper = $row[0];
                            $dvc->find();
                            $dvc->fetch();
                            cambia_campo($dvc, 'personasaprox', 
                                $personasaprox, $depura, $victimascolex);
                        } else {

                        }


                        //datos_victima_colectiva($linea);
                    }
                    $estado = 10;
                    break;
                }
                // sin break porque puede pasar al 11
            case 11:
                // Presponsables y categorias
                if (trim($linea) == '') {
                    $estado = 11;
                    break;
                } else if (comienza_con($linea, 'Presunto Responsable: ') ||
                    comienza_con($linea, 'Presuntos Responsables: ')
                ) {
                    if ($depura >= 3) {
                        echo "Depura, linea con pr y cat: $linea<br>";
                    }
                    $par = split(':', $linea);
                    $lp = split(',', $par[1]);
                    if (strpos($lp[count($lp) - 1], ' Y ') !== FALSE) {
                        $lpa = preg_split('/ Y /', $lp[count($lp) - 1]);
                        $lp[count($lp) - 1] = $lpa[0];
                        $lp[count($lp)] = $lpa[1];
                    }
                    if ($depura >= 3) {
                        echo "Depura, presponsables: ";
                        print_r($lp);
                        echo "<br>";
                    }
                    $lidp = array_map(function($cpr) {
                        $pr = (int)conv_basica($db, 'presponsable', $cpr, $obs);
                        if ($pr <= 0) {
                            reperror_txt($pArchivo, $nlin, 
                                "Presunto responsable errado $cpr"
                            );
                            die("x");
                        } 
                        return $pr;
                    } , $lp);
                    foreach($lidp as $idp) {
                        $dp = objeto_tabla('caso_presponsable');
                        $dp->id_caso = $idcaso;
                        $dp->id_presponsable = $idp;
                        $dp->find();
                        if (!$dp->fetch()) {
                            $caso_presponsable_existente[] = false;
                            $presponsablesex[$idp] = 
                                isset($presponsablesex[$idp]) ?
                                $presponsablesex[$idp] + 1 : 1;
                            if ($depura >= 2) {
                                echo "Depura: por insertar caso_presponsable $idp<br>\n";
                            }
                        } else  {
                            $caso_presponsable_existente[] = true;
                            cambia_campo($dp, 'id', 1, $depura, $presponsalesex);
                        }
                        $dcaso_presponsable[] = $dp;
                    }

                    if ($depura >= 3) {
                        echo "Depura, lidp=";
                        print_r($lidp);
                        echo "<br>";
                    }
                    $lidc = proc_categorias($par[2], $pArchivo, $nlin);
                    if ($depura >= 3) {
                        echo "Depura, lidc=";
                        print_r($lidc);
                        echo "<br>";
                    }
                    $estado = 10;
                    break;
                } 
                $estado = 0;
                break;
            case 12:
                // Víctimas

            case 13:
                // Etiquetas

                break;

            case 14:
                // Etiquetas

                if (trim($memo) != "") {
                    cambia_campo($dcaso, 'memo', $memo, $depura, $memosex);
                    $memo = "";
                }
                if (trim($linea) == "") {
                    $estado = 14;
                    break;
                } else if (comienza_con($linea, 'ANALISTA(s):')) {
                    $estado = 15;
                    break;
                } else {
                    $coi = array();
                    if ($depura >= 3) {
                        echo "Depura, etiqueta $linea<br>";
                    }
                    $oe = null;
                    if (preg_match('/^([^:]*): (.*)$/', $linea, $coi)) {
                        $et = $coi[1];
                        $oe = $coi[2];
                    } else {
                        $et = trim($linea);
                    }
                    $idet = (int)conv_basica($db, 'etiqueta', $et, $obs);
                    if ($depura >= 3) {
                        echo "Depura, etiqueta convertida $idet<br>";
                    }
                    if ($idet <= 0) {
                        reperror_txt($pArchivo, $nlin, 
                            "etiqueta desconocida {$et}"
                        );
                        $estado = -1;
                        break;
                    }
                    $de = objeto_tabla('caso_etiqueta');
                    $de->id_caso = $idcaso;
                    $de->id_etiqueta = $idet;
                    $de->find();
                    if (!$de->fetch()) {
                        $de->fecha =  @date('Y-m-d');
                        $de->observacioens =  $oe;
                        $caso_etiqueta_existente[] = false;
                        $etiquetaex[$idet] = 
                            isset($etiquetaex[$idet]) ?
                            $etiquetaex[$idet] + 1 : 1;
                        if ($depura >= 2) {
                            echo "Depura: por insertar etiqueta $idet<br>\n";
                        }
                    } else  {
                        $caso_etiqueta_existente[] = true;
                        cambia_campo($de, 'observaciones', trim($oe), 
                            $depura, $etiquetaex
                        );
                    }
                    $dcaso_etiqueta[] = $de;

                }
                // Seguimos en 14 
                break;
            case 15:
                // Analistas (pasada linea ANALISTA(s):
                if (trim($memo) != "") {
                    cambia_campo($dcaso, 'memo', $memo, $depura, $memosex);
                    $memo = "";
                }
                if (trim($linea) == '') {
                    if ($depura >= 1) {
                        echo "Depura. Escribiendo<br>";
                    }
                    if ($caso_existente) {
                        if ($depura >= 2) {
                            echo "Depura. Actualiza caso<br>";
                        }

                    } else {
                        if ($depura >= 2) {
                            echo "Depura. Inserta caso y todo<br>";
                        }
                    }
                    // Limpia, debe ser igual al bloque antes del ciclo
                    $caso_existente = false;
                    $ubicacion_existente = false;
                    $caso_region_existente = false;
                    $caso_frontera_existente = false;
                    $caso_contexto_existente = array();
                    $caso_presponsable_existente = array();
                    $victima_existente = array();
                    $caso_etiqueta_existente = array();
                    $caso_usuario_existente = array();

                    $lvicr = null; // Lista de victimas repetidas

                    $idcaso = 0;
                    $obs = ""; // Observaciones por poner en etiqueta error_importacion
                    $dubicacion = null;
                    $dcaso_region = null;
                    $dcaso_frontera = null;
                    $dff = array(); // Fuentes frecuentes
                    $dffexistente = array();
                    $dof = array(); // Otras fuentes_caso
                    $dofc = array(); // Otras fuentes
                    $dofexistente = array();
                    $dcategoria = null;
                    $dcaso_contexto = array();
                    $dcaso_presponsable = array();
                    $dvictima_esp = array();
                    $dcaso_etiqueta = array();
                    $dcaso_usuario = array();

                    $estado = 0; // esperando  CASO No.

                    break;
                } else {
                    // Procesa analista
                    $p = preg_split('/  */', trim($linea));
                    if ($depura >= 3) {
                        echo "Depura, analista $p[0]<br>";
                    }
                    if (count($p) != 2) {
                        print_r($p);
                        reperror_txt($pArchivo, $nlin, "analista errado $linea");
                        $estado = -1;
                        break;
                    }
                    $idus = (int)conv_basica($db, 'usuario', $p[0], $obs, false,
                        'nusuario');
                    if ($depura >= 3) {
                        echo "Depura, usuario convertido $idus<br>";
                    }
                    if ($idus <= 0) {
                        reperror_txt($pArchivo, $nlin, 
                            "usuario deaconocido {$p[0]}"
                        );
                        $estado = -1;
                        break;
                    }
                    $du = objeto_tabla('caso_usuario');
                    $du->id_caso = $idcaso;
                    $du->id_usuario = $idet;
                    $du->fechainicio = conv_fecha($p[1], $obs);
                    $du->find();
                    if (!$du->fetch()) {
                        $caso_usuario_existente[] = false;
                        $usuarioex[$idus] = 
                            isset($usuarioex[$idus]) ?
                            $usuarioex[$idus] + 1 : 1;
                        if ($depura >= 2) {
                            echo "Depura: por insertar usuario $idus<br>\n";
                        }
                    } else  {
                        $caso_usuario_existente[] = true;
                    }
                    $dcaso_usuario[] = $du;
                    
                }
                $estado = 15;
                break;
            default:
                if ($depura) {
                    echo "Depura: $nlin no reconocida<br>";
                    die("x");
                }
            }
        }

        echo "Casos reconocidos: $ncasos<br>";
        echo "Existentes: $cuentaexistentes, No existentes: $cuentanoexistentes<br>";
        if ($ncasos != ($cuentaexistentes + $cuentanoexistentes)) {
            reperror_txt($pArchivo, $nlin, "ncasos=$ncasos no da con suma de existentes mas no existentes");
        }
        pres_frecs("Frecuencia de fechas modificadas", $fechasex, $cuentanoexistentes) ;
        pres_frecs("Frecuencia de horas modificadas", $horasex, 0);
        pres_frecs("Frecuencia de intervalos modificados", $intervalosex, $cuentanoexistentes);
        pres_frecs("Frecuencia de tipos de sitios modificados", $tsitiosex, $cuentanoexistentes);
        pres_frecs("Frecuencia de regiones modificadas", $regionesex, 0);
        pres_frecs("Frecuencia de fronteras modificadas", $fronterasex, 0);
        pres_frecs("Frecuencia de departamentos modificados", $depex, $cuentanoexistentes);
        pres_frecs("Frecuencia de municpios modificados", $munex, 0);
        pres_frecs("Frecuencia de clases modificados", $claex, 0);
        pres_frecs("Frecuencia de fuentes frecuente modificadas", $ffex, 0);
        pres_frecs("Frecuencia de ubicaciones en fuentes modificadas", $ofex, 0);
        echo "Cantidad de memos cambiados " . count($memosex) . "<br>";
        pres_frecs("Frecuencia de contextos modificados", $contextosex, 0);
        pres_frecs("Frecuencia de p. responsabeles modificados", 
            $presponsablesex, $cuentanoexistentes);
        pres_frecs("Frecuencia de sectores de víc existentes modificados", 
            $victimasssex, 0);
        pres_frecs("Frecuencia de profesiones de víc existentes modificadas", 
            $victimasprofex, 0);
        pres_frecs("Frecuencia de hijos de vic existentes modificados", 
            $victimashijex, 0);
        pres_frecs("Frecuencia de filiaciones de vic existentes modificados", 
            $victimasfilex, 0);
        pres_frecs("Frecuencia de anotacioens de vic existentes modificados", 
            $victimasanotex, 0);
        pres_frecs("Frecuencia de organizacioens de vic existentes modificadas", 
            $victimasorgex, 0);
        pres_frecs("Frecuencia de etiquetas existentes modificadas", 
            $etiquetaex, 0);
        pres_frecs("Frecuencia de usuarios existentes modificadas", 
            $usuarioex, $cuentanoexistentes);

        echo "Víctimas nuevas: $vic_nuevas<br>";
        echo "Víctimas existentes: $vic_existentes<br>";


        die("Aqui vamos completo");


        $yaesta = array(); // Indica cuales pestañas ya importaron
        foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
            list($n, $c, ) = $tab;
            $yaesta[$c] = false;
        }

        
        encabezado_envia("Recupera de General");
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



perform();
