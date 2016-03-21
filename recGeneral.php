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
        $obj->$campo = $nvalor;
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
        echo "Depura: fuente vista n='$n', o='$o', fecha='$fecha'<br>\n";
    }
    $n = trim($n);
    $idff = (int)conv_basica($db, 'ffrecuente', $n, $obs, false);
    if ($depura >= 3) {
        echo "Depura: ffrecuente convertida $idff<br>\n";
    }
    if ($idff != -1) {
        $f = objeto_tabla('ffrecuente');
        $f->id = $idff;
        $f->find();
        $f->fetch();
        if ($depura >= 3) {
            echo "Depura: ffrecuente recuperada '{$f->nombre}'<br>\n";
        }
        if (trim($f->nombre) == $n) { 
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
    } /* Sin else porque if anterior puede poner $idff en -1 */

    if ($idff == -1) {
        if ($depura >= 3) {
            echo "Depura: intentando $n como fotra<br>\n";
        }
        $idof = (int)conv_basica($db, 'fotra', $n, $obs, false);
        if ($depura >= 3) {
            echo "Depura: fotra convertida $idof<br>\n";
        }
        if ($idof != -1) {
            $f = objeto_tabla('fotra');
            $f->id = $idof;
            $f->find();
            $f->fetch();
            if (trim($f->nombre) == $n) { 
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
                    cambia_campo($d, 'ubicacionfisica', $o, $depura, $ofex);
                    $dofc[] = $d;
                    $dof[] = $f;
                } else {
                    $idof = -1;
                }
            } else {
                $idof = -1;
            }
        } // no es else porque if anterior puede dejar idof en -1
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

function actualiza_do($do, $existente, $campo, $escribe, $depura) 
{
    if ($do != null) {
        if ($existente) {
            $op = 'actualizado';
            if ($escribe) {
                $do->update();
            }
        } else {
            $op = 'insertado';
            if ($escribe) {
                $do->insert();
            }
        }
        if ($depura >= 3) {
            $campos = preg_split('/, */', $campo);
            echo "Depura, al actualizar, $op registro con ";
            $sep = "";
            foreach ($campos as $c) {
                echo $sep . "$c={$do->$c}";
                $sep = ", ";
            }
            echo " en {$do->__table}<br>";
        }
    } 
}


function inserta_do($do, $campo, $escribe, $depura, $geninsert = false) 
{
    if ($do != null) {
        $campos = preg_split('/, */', $campo);
        if ($escribe) {
            if (!$geninsert) {
                $valant = array();
                foreach ($campos as $c) {
                    $valant[$c] = $do->$c;
                }
                $do->insert();
                foreach ($campos as $c) {
                    if ($valant[$c] != null && $valant[$c] != $do->$c) {
                        debug_print_backtrace();
                        die("Insert que modifica $c conv valor no nulo {$valant[$c]}, pase geninsert en true");
                    }
                }
            } else {
                $db =& $do->getDatabaseConnection();
                $q = "INSERT INTO $do->__table ($campo) VALUES (";
                $sep = "";
                foreach ($campos as $c) {
                    $q .= $sep . "'" . $db->escapeSimple($do->$c) .  "'";
                    $sep = ", ";
                }
                $q .= ")";
                hace_consulta($db, $q);
            }
        }
        if ($depura >= 3) {
            echo "Depura, escrito registro con ";
            $sep = "";
            foreach ($campos as $c) {
                echo $sep . "$c={$do->$c}";
                $sep = ", ";
            }
            echo " en {$do->__table}<br>";
        }
    } 
}

function inserta_arreglo_do($ado, $campo, $escribe, $depura) 
{
    foreach($ado as $dobj) {
        inserta_do($dobj, $campo, $escribe, $depura);
    }
}

/**
 * $ado y $arrexistente están sincronizados mediante indices
 */
function actualiza_arreglo_do($ado, $arrexistente, $campo, $escribe, $depura) 
{
    foreach($ado as $i => $dobj) {
        if (!isset($arrexistente[$i])) {
            echo "no esta definido {$arrexistente}[{$i}]<br>";
            debug_print_backtrace();
            die("x");
        }
        actualiza_do($dobj, $arrexistente[$i], $campo, $escribe, $depura);
    }
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
    $escribe = true;
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

    $depura = 0; 
    // 1 Existente o no
    // 2 cambios a base de datos
    // 3 variables internas
    // 5 estados       
    // 6 líneas


    hace_consulta($db, 'DROP MATERIALIZED VIEW IF EXISTS persona_nomap' );
    hace_consulta($db, 'CREATE MATERIALIZED VIEW persona_nomap 
        AS (SELECT id, UPPER(TRIM(TRIM(nombres) || \' \' || TRIM(apellidos))) AS nomap
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
    $vicc_nuevas = 0;
    $vic_existentes = 0;
    $vicc_existentes = 0;
    $victimascolex = array();
    $actosnuevos = 0;
    $actosex = 0;
    $actoscnuevos = 0;
    $actoscex= 0;

    // Por inicializar con cada caso
    // Debe repetirse en ultimo estado

    $caso_existente = false;
    $ubicacion_existente = false;
    $caso_region_existente = false;
    $caso_frontera_existente = false;
    $ffexistente = array();
    $ofexistente = array();
    $caso_contexto_existente = array();
    $caso_presponsable_existente = array();
    $victima_existente = array();
    $victimacol_existente = array();
    $caso_etiqueta_existente = array();
    $caso_usuario_existente = array();

    $lvicr = null; // Lista de victimas repetidas

    $idcaso = 0;
    $obs = ""; // Observaciones por poner en etiqueta error_importacion
    $dcaso = null;
    $dubicacion = null;
    $dcaso_region = null;
    $dcaso_frontera = null;
    $dff = array(); // Fuentes frecuentes
    $dof = array(); // Otras fuentes_caso
    $dofc = array(); // Otras fuentes
    $dcategoria = null;
    $dcaso_contexto = array();
    $dcaso_presponsable = array();
    $dvictima_esp = array();
    $dvictimacol_esp = array();
    $dcaso_etiqueta = array();
    $dcaso_usuario = array();
    $encaso = false;
    $repgen = "";

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
    // 14 Etiquetas
    // 15 Analistas
    // -1 error no recuperable
    foreach ($lineas as $nlin => $linea) {
        if ($encaso) { 
            $repgen .= $linea;
        }
        if ($depura >= 6) {
            echo "Depura: línea $nlin, estado=$estado, linea=$linea<br>";
        }
        switch ($estado) {
        case 0: 
            if (comienza_con($linea, 'CASO No. ')) {
                $repgen .= $linea;
                $encaso = true;
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
                array_shift($pal);
                array_shift($pal);
                $l = trim(implode(' ', $pal));
                if ($depura >= 3) {
                    echo "Depura: linea implode '$l'<br>\n";
                }
                $coi = array();
                $tsitio = DataObjects_Tsitio::idSinInfo();
                if (preg_match('/(.*) Tip. Ub: (.*)$/', $l, $coi)) {
                    $tsitio = $coi[2];
                    if ($depura >= 3) {
                        echo "Depura: tsitio detectado '$tsitio' en '$l' <br>\n";
                    }
                    $l = trim($coi[1]);
                    $tsitio = (int)conv_basica($db, 'tsitio', 
                        $tsitio, $obs );
                    if ($depura >= 3) {
                        echo "Depura: tsitio convertido $tsitio<br>\n";
                    }
                }
                $int = null;
                $coi = array();
                if (termina_con($l, 'SIN INFORMACIÓN')) {
                    $int =  'SIN INFORMACIÓN';
                    if ($depura >= 3) {
                        echo "Depura: intervalo sin info detectado en '$l'<br>\n";
                    }
                    $l = substr($l, 0, strlen($l) - 16);
                } else if (strpos($l, ' ') === FALSE) {
                    $int = $l;
                    if ($depura >= 3) {
                        echo "Depura: intervalo como palabra completa final en '$l'<br>\n";
                    }
                    $l = "";
                } else if (preg_match(
                    '/^(.*) ([^ ]+)$/', $l, $coi)
                ) {
                    $int = $coi[2];  
                    if ($depura >= 3) {
                        echo "Depura: intervalo '$int' detectado en '$l'<br>\n";
                    }
                    $l = trim($coi[1]);
                } else {
                    if ($depura >= 3) {
                        echo "Depura: no hay palabra final en '$l'<br>\n";
                    }
                }

                if ($int != null) {
                    $nint = (int)conv_basica($db, 'intervalo', $int, $obs, 
                        false);
                    if ($depura >= 3) {
                        echo "Depura: intervalo convertido $nint<br>\n";
                    }
                    if ($nint >= 0) {
                        cambia_campo($dcaso, 'id_intervalo', $nint, $depura, 
                            $intervalosex);
                    } else {
                        $l .= $int;
                    }
                }
                $horac = trim($l);
                if ($depura >= 3) {
                    echo "Depura: horac detectada '$horac' en '$l'<br>\n";
                }
                if ($horac != '') {
                    cambia_campo($dcaso, 'hora', $horac, $depura, $horasex);
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
            break;

        case 2:
            if (comienza_con($linea, 'Región: ')) {
                $rg = substr($linea, 8);
                if ($depura >= 3) {
                    echo "Depura: región detectada $rg<br>\n";
                }
                $rg = (int)conv_basica($db, 'region', $rg, $obs, false);
                if ($depura >= 3) {
                    echo "Depura: región convertida $rg<br>\n";
                }
                if ($rg>= 0) {
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
                $fr = (int)conv_basica($db, 'frontera', $fr, $obs, false);
                if ($depura >= 3) {
                    echo "Depura: frontera convertida $rg<br>\n";
                }
                if ($fr>= 0) {
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
                    reperror_txt($pArchivo, $nlin, 
                        "Localización errada $linea");
                    $estado = -1;
                    break;
                }
                cambia_campo($dubicacion, 'id_departamento', $loc[0], 
                    $depura, $depex);
                if (count($loc) >= 2) {
                    if ($loc[1] != 1000) {
                        cambia_campo($dubicacion, 'id_municipio', $loc[1], 
                            $depura, $munex);
                    }
                    if (count($loc) >= 3 && $loc[2] != 1000) {
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
                $sepmemo = "";
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
                $sepmemo = "";
                $estado = 7;
                break;
            }
            break;
        case 7: // Procesando Memo
            if (trim($linea) == '') {
                if ($depura >= 3) {
                    echo "Depura en memo, línea en blanco<br>";
                }
                $sepmemo = "\n";
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
                        echo "Depura: memo aumentado $linea, sepmemo es " .
                            ord($sepmemo) . "<br>\n";
                    }
                    $memo .= $sepmemo . $linea;
                    $sepmemo = "\n";
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
                    $con = (int)conv_basica($db, 'contexto', $c, $obs, false);
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
                    reperror_txt($pArchivo, $nlin, 
                        "Sin categoria $linea");
                    $estado = -1;
                    break;
                }
                if ($lidc[0][1] == 'I') {
                    // Viene víctima individual
                    $l = $linea;
                    $id_organizacion= DataObjects_Organizacion::idSinInfo();
                    $coi = array();
                    if (preg_match( 
                        '/^(.*) Organización Social: (.*)$/', $l, $coi)
                    ) {
                        $orgsocial = $coi[2];
                        $l = $coi[1];
                        if ($depura >= 3) {
                            echo "Encontrad org. social $orgsocial, l=$l<br>";
                        }
                        $id_organizacion = (int)conv_basica($db, 
                            'organizacion', 
                            $orgsocial, $obs, false);
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

                    if ($depura >= 3) {
                        echo "Depura, antes de fil. l es '$l'<br>";
                    }
                    $id_filiacion = DataObjects_Filiacion::idSinInfo();
                    $coi = array();
                    $pf = preg_split('/ Filiación Política: /', $l);
                    if (count($pf) == 2) {
                        $fpol = trim(str_replace('.', ' ', $pf[1]));
                        if ($depura >= 3) {
                            echo "Encontrada Fil. Pol. '$fpol' en l='$l'<br>";
                        }
                        $l = $pf[0];
                        $id_filiacion = (int)conv_basica($db, 'filiacion', 
                            $fpol, $obs, false);
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
                    $id_prof = DataObjects_Profesion::idSinInfo();
                    $id_ss = DataObjects_Sectorsocial::idSinInfo();
                    if (count($pper) > 3) {
                        reperror_txt($pArchivo, $nlin, "Mas de 3 partes $l");
                        $estado = -1;
                        break;
                    } else if (count($pper) == 3) {
                        if ($depura >= 4) {
                            echo "Depura, caso pper 3<br>";
                        }
                        $ssol = $pper[1];
                        $id_ss = (int)conv_basica($db, 'sectorsocial', 
                            $ssol, $obs, false);
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
                            $prof , $obs, false);
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
                        if ($depura >= 4) {
                            echo "Depura, caso pper 2<br>";
                        }
                        $id_ss = DataObjects_Sectorsocial::idSinInfo();
                        $id_prof = (int)conv_basica($db, 'profesion', 
                            $pper[1], $obs, false);
                        if ($id_prof < 0) {
                            $id_prof = DataObjects_Profesion::idSinInfo();
                            if ($depura >= 3) {
                                echo "Depura, no profesión {$pper[1]}<br>";
                            }
                            $id_ss = (int)conv_basica($db, 'sectorsocial', 
                                $pper[1], $obs, false);
                            if ($depura >= 3) {
                                echo "Depura, id_ss=$id_ss<br>";
                            }
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
                                $pnom .= $sepn . trim($pna[$i]);
                                $sepn = ' ';
                            } else {
                                $pap .= $sepa . trim($pna[$i]);
                                $sepa = ' ';
                            }
                        }
                        if ($depura >= 3) {
                            echo "Depura, na4, $pnom, $pap<br>";
                        }
                    } else if (count($pna) == 3) {
                        $pnom = trim($pna[0]) . ' ' . trim($pna[1]);
                        $pap = trim($pna[2]);
                        if ($depura >= 3) {
                            echo "Depura, na3, $pnom, $pap<br>";
                        }
                    } else if (count($pna) == 2) {
                        $pnom = trim($pna[0]);
                        $pap = trim($pna[1]);
                        if ($depura >= 3) {
                            echo "Depura, na2, $pnom, $pap<br>";
                        }
                    } else if (count($pna) == 1) {
                        $pnom = trim($pna[0]);
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
/*                        if ($nper == 'N N' || $nper == 'PERSONA SIN IDENTIFICAR') {
                            $nper = 'N N';
                            $pnom = 'N';
                            $pap = 'N';
} */
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
                            if ($depura >= 3) {
                                echo "lvicr con " . count($lvicr) . "<br>";
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
                            if ($depura >= 3) {
                                echo "Depura, elegida {$dv->id_persona}<br>";
                            }
                        } else {
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
                                echo "Depura, unica {$dv->id_persona}<br>";
                            }
                        }
                        $dp = $dv->getLink('id_persona');
                        cambia_campo($dv, 'id_sectorsocial', 
                            $id_ss, $depura, $victimasssex);
                        cambia_campo($dv, 'id_profesion', 
                            $id_prof, $depura, $victimasprofex);
                        cambia_campo($dv, 'hijos', 
                            $nhijos, $depura, $victimashijex);
                        cambia_campo($dv, 'id_filiacion', 
                            $id_filiacion, $depura, $victimasfilex);
                        cambia_campo($dv, 'anotaciones', 
                            $anotaciones, $depura, $victimasanotex);
                        cambia_campo($dv, 'id_organizacion', 
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
                        $dp->apellidos = $pap;
                        $dp->sexo = 'S';

                        $victima_existente[] = false;
                    }
                    $dvictima_esp[] = [$dv, $dp, $lidp, $lidc];

                } else if ($lidc[0][1] == 'C') {
                    $personasaprox = null;
                    $coi = array();
                    if (preg_match('/^ *(.*) \(([0-9]+)\)/', $linea, $coi)) {
                        $personasaprox = $coi[2];
                        $nombre = a_mayusculas($coi[1]);
                        if ($depura >= 3) {
                            echo "Depura, personasaprox $personasaprox<br>";
                        }
                    } else {
                        $nombre = a_mayusculas(trim($linea));
                    }
                    $q = "SELECT victimacolectiva.id_grupoper
                        FROM victimacolectiva, grupoper
                        WHERE 
                        victimacolectiva.id_caso='$idcaso' AND
                        victimacolectiva.id_grupoper=grupoper.id AND
                        UPPER(grupoper.nombre) = '" . 
                        $db->escapeSimple($nombre) .  "'";
                    if ($depura >= 3) {
                        echo "Depura, q=$q<br>";
                    }
                    $dvc = objeto_tabla('victimacolectiva');
                    $dvc->id_caso = $idcaso;
                    $res = hace_consulta($db, $q);
                    $numres = $res->numRows();
                    if ($numres > 1) {
                        reperror_txt($pArchivo, $nlin, "colectivas repetidas no soportado");
                        /*$estado = -1;
                        break;*/
                    } else if ($numres == 1) {
                        if ($depura >= 3) {
                            echo "Victima colectiva encontrada<br>";
                        }
                        $vicc_existentes++;
                        $row = array();
                        $res->fetchInto($row);
                        $dvc->id_grupoper = $row[0];
                        $dvc->find();
                        $dvc->fetch();
                        $dg = $dvc->getLink('id_grupoper');
                        cambia_campo($dvc, 'personasaprox', 
                            $personasaprox, $depura, $victimascolex);
                        $victimacol_existente[] = true;
                    } else {
                        if ($depura >= 3) {
                            echo "Depura, insertar grupo y vicc $nombre<br>";
                        }
                        $vicc_nuevas++;
                        $dvc->personasaprox = $personasaprox;

                        $dg = objeto_tabla('grupoper');
                        $dg->nombre = $nombre;

                        $victimacol_existente[] = false;
                    }
                    $dvictimacol_esp[] = [$dvc, $dg, $lidp, $lidc];
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
                    $pr = (int)conv_basica($db, 'presponsable', 
                        $cpr, $obs, false);
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
                    $oe = "";
                }
                if ($depura >= 3) {
                    echo "Depura, et=$et, oe=$oe<br>";
                }
                $idet = (int)conv_basica($db, 'etiqueta', $et, $obs, false);
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
                    $de->id_usuario = $_SESSION['id_usuario'];
                    $de->observaciones =  trim($oe);
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
                    echo "Depura. Escribiendo caso $idcaso<br>";
                }
                if ($caso_existente) {
                    if ($depura >= 2) {
                        echo "Depura. Actualiza caso<br>";
                    }
                    actualiza_do($dcaso, true,
                        'id, fecha, hora, memo, id_intervalo',
                        $escribe, $depura);
                    assert($dcaso->id == $idcaso);

                    actualiza_do($dubicacion, $ubicacion_existente,
                        'id, id_caso, id_departamento,' .
                        ' id_municipio, id_clase, id_tsitio', 
                        $escribe, $depura);

                    actualiza_do($dcaso_region, $caso_region_existente,
                        'id_caso, id_region', 
                        $escribe, $depura);

                    actualiza_do($dcaso_frontera, 
                        $caso_frontera_existente, 'id_caso, id_frontera', 
                        $escribe, $depura);

                    actualiza_arreglo_do($dff, $ffexistente,
                        'id_caso, id_ffrecuente, fecha, ubicacion', 
                        $escribe, $depura
                    );

                    foreach($dof as $i => $df) {
                        actualiza_do($df, $ofexistente[$i], 
                            'id, nombre', $escribe, $depura);
                        $dofc[$i]->id_fotra = $df->id;
                        actualiza_do($dofc[$i], $ofexistente[$i], 
                            'id_caso, id_fotra, fecha, ubicacionfisica', 
                            $escribe, $depura);
                    }

                    actualiza_arreglo_do($dcaso_contexto, 
                        $caso_contexto_existente,
                        'id_caso, id_contexto', $escribe, $depura
                    );
                    actualiza_arreglo_do($dcaso_presponsable, 
                        $caso_presponsable_existente,
                        'id_caso, id_presponsable', $escribe, $depura
                    );

                    foreach($dvictima_esp as $i => $dmv) {
                        list($dv, $dp, $lidp, $lidc) = $dmv;
                        actualiza_do($dp, $victima_existente[$i],
                            'id, nombres, apellidos, sexo', 
                            $escribe, $depura);
                        $dv->id_persona = $dp->id;
                        actualiza_do($dv, $victima_existente[$i],
                            'id_caso, id_persona, ' .
                            'id_profesion, id_filiacion, id_sectorsocial, ' .
                            'id_organizacion, anotaciones', 
                            $escribe, $depura);
                        foreach($lidp as $id_pr) {
                            foreach($lidc as $id_c) {
                                $dacto = objeto_tabla('acto');
                                $dacto->id_presponsable = $id_pr;
                                $dacto->id_categoria = $id_c[0];
                                $dacto->id_persona = $dp->id;
                                $dacto->id_caso = $idcaso;
                                if ($depura >= 3) {
                                    echo "Depura, examinando si ya está acto " .
                                        "$id_pr, {$id_c[0]}, {$dp->id}, " .
                                        "$idcaso<br>";
                                }
                                $dacto->find();
                                if (!$dacto->fetch()) {
                                    if ($depura >= 3) {
                                        echo "Depura, no esta, insertando";
                                    }
                                    $actosnuevos++;
                                    inserta_do($dacto, 
                                        'id_presponsable, id_categoria, ' .
                                        'id_persona, id_caso', 
                                        $escribe, $depura);
                                } else {
                                    $actosex++;
                                }
                            }
                        }
                    }
                    foreach($dvictimacol_esp as $i => $dmv) {
                        list($dvc, $dg, $lidp, $lidc) = $dmv;
                        actualiza_do($dg, $victimacol_existente[$i],
                            'id, nombre', $escribe, $depura);
                        $dvc->id_grupoper = $dg->id;
                        actualiza_do($dvc, $victimacol_existente[$i],
                            'id_caso, id_grupoper, personasaprox', 
                            $escribe, $depura);
                        foreach($lidp as $id_pr) {
                            foreach($lidc as $id_c) {
                                $dactoc = objeto_tabla('actocolectivo');
                                $dactoc->id_presponsable = $id_pr;
                                $dactoc->id_categoria = $id_c[0];
                                $dactoc->id_grupoper = $dg->id;
                                $dactoc->id_caso = $idcaso;
                                $dactoc->find();
                                if (!$dactoc->fetch()) {
                                    $actoscnuevos++;
                                    inserta_do($dactoc, 
                                        'id_presponsable, id_categoria, ' .
                                        'id_grupoper, id_caso', 
                                        $escribe, $depura);
                                } else {
                                    $actoscex++;
                                }
                            }
                        }
                    }

                    actualiza_arreglo_do($dcaso_etiqueta, 
                        $caso_etiqueta_existente,
                        'id_caso, id_etiqueta, id_usuario, fecha, ' .
                        ' observaciones',
                        $escribe, $depura);
                    actualiza_arreglo_do($dcaso_usuario, 
                        $caso_usuario_existente, 
                        'id_caso, id_usuario, fechainicio',
                        $escribe, $depura);

                    reperror_txt($pArchivo, $nlin, 
                        "Al actualizar no se manejan casos en los que
                        se debe borrar información.
                        Revisar manualmente."
                    );

                    // OJO que debe borrarse lo que esté en 
                    // caso y no en memoria --excepto detalles
                    // de victimas existentes por ejemplo que
                    // no aparezcan en rep. gen
                } else {
                    if ($depura >= 2) {
                        echo "Depura. Inserta caso y todo<br>";
                    }
                    inserta_do($dcaso, 'id, fecha, hora, memo, id_intervalo',
                        $escribe, $depura, true);
                    assert($dcaso->id == $idcaso);

                    inserta_do($dubicacion, 'id, id_caso, id_departamento,' .
                        ' id_municipio, id_clase, id_tsitio', 
                        $escribe, $depura);

                    inserta_do($dcaso_region, 'id_caso, id_region', 
                        $escribe, $depura);

                    inserta_do($dcaso_frontera, 'id_caso, id_frontera', 
                        $escribe, $depura);

                    inserta_arreglo_do($dff,
                        'id_caso, id_ffrecuente, fecha, ubicacion', 
                        $escribe, $depura
                    );

                    foreach($dof as $i => $df) {
                        inserta_do($df, 'id, nombre', $escribe, $depura);
                        $dofc[$i]->id_fotra = $df->id;
                        inserta_do($dofc[$i], 
                            'id_caso, id_fotra, fecha, ubicacionfisica', 
                            $escribe, $depura);
                    }

                    inserta_arreglo_do($dcaso_contexto, 
                        'id_caso, id_contexto', $escribe, $depura
                    );
                    inserta_arreglo_do($dcaso_presponsable, 
                        'id_caso, id_presponsable', $escribe, $depura
                    );

                    foreach($dvictima_esp as $dmv) {
                        list($dv, $dp, $lidp, $lidc) = $dmv;
                        inserta_do($dp, 'id, nombres, apellidos, sexo', 
                            $escribe, $depura);
                        $dv->id_persona = $dp->id;
                        inserta_do($dv, 'id_caso, id_persona, ' .
                            'id_profesion, id_filiacion, id_sectorsocial, ' .
                            'id_organizacion, anotaciones', 
                            $escribe, $depura);
                        foreach($lidp as $id_pr) {
                            foreach($lidc as $id_c) {
                                $dacto = objeto_tabla('acto');
                                $dacto->id_presponsable = $id_pr;
                                $dacto->id_categoria = $id_c[0];
                                $dacto->id_persona = $dp->id;
                                $dacto->id_caso = $idcaso;
                                inserta_do($dacto, 
                                    'id_presponsable, id_categoria, ' .
                                    'id_persona, id_caso', 
                                    $escribe, $depura);
                                $actosnuevos++;
                            }
                        }
                    }
                    foreach($dvictimacol_esp as $dmv) {
                        list($dvc, $dg, $lidp, $lidc) = $dmv;
                        inserta_do($dg, 'id, nombre', $escribe, $depura);
                        $dvc->id_grupoper = $dg->id;
                        inserta_do($dvc, 
                            'id_caso, id_grupoper, personasaprox', 
                            $escribe, $depura);
                        foreach($lidp as $id_pr) {
                            foreach($lidc as $id_c) {
                                $dactoc = objeto_tabla('actocolectivo');
                                $dactoc->id_presponsable = $id_pr;
                                $dactoc->id_categoria = $id_c[0];
                                $dactoc->id_grupoper = $dg->id;
                                $dactoc->id_caso = $idcaso;
                                inserta_do($dactoc, 
                                    'id_presponsable, id_categoria, ' .
                                    'id_grupoper, id_caso', 
                                    $escribe, $depura);
                                $actoscnuevos++;
                            }
                        }
                    }

                    inserta_arreglo_do($dcaso_etiqueta, 
                        'id_caso, id_etiqueta, id_usuario, fecha, ' .
                        ' observaciones',
                        $escribe, $depura);
                    inserta_arreglo_do($dcaso_usuario, 
                        'id_caso, id_usuario, fechainicio',
                        $escribe, $depura);
                }

                // Compara reportes generales
                $html_rep = ResConsulta::reporteGeneralHtml(
                    $idcaso, $db,
                    $GLOBALS['cw_ncampos'] + array('m_fuentes' => 'Fuentes'),
                    false
                );
                $trep = preg_replace('/<a  *href *= *.[^"\']+. *>/', 
                    '', $html_rep);
                $trep = preg_replace('|</a>|', '', $trep);
                $trep = preg_replace('|&nbsp;|', '', $trep);
                file_put_contents("/tmp/$idcaso.ini.txt", $repgen);
                file_put_contents("/tmp/$idcaso.fin.txt", $trep);
                $r=`/usr/bin/diff -w /tmp/$idcaso.ini.txt /tmp/$idcaso.fin.txt | /usr/bin/grep -v "^<[^A-Za-z0-9]*$" | grep -v "^[0-9]"`; # |  /usr/bin/grep -v "^[0-9,dc]*[^A-Za-z0-9]*$" `;
                if (trim($r) != '') {                    
                    echo "Diferencias en caso $idcaso:<br>";
                    echo "<pre>";
                    print_r($r);
                    echo "</pre>";
                }

                // Limpia, 
                $dcaso->free();
                $dubicacion->free();
                if ($dcaso_region != null) {
                    $dcaso_region->free();
                }
                if ($dcaso_frontera) {
                    $dcaso_frontera->free();
                }
                foreach($dff as $df) {
                    $df->free();
                }
                foreach($dof as $df) {
                    $df->free();
                }
                foreach($dofc as $df) {
                    $df->free();
                }
                if ($dcategoria != null) {
                    $dcategoria->free();
                }
                foreach($dcaso_contexto as $dc) {
                    $dc->free();
                }
                foreach($dcaso_presponsable as $dp) {
                    $dp->free();
                }
                foreach($dvictima_esp as $dmv) {
                    list($dv, $dp, $lidp, $lidc) = $dmv;
                    $dv->free();
                    $dp->free();
                }
                foreach($dvictimacol_esp as $dmv) {
                    list($dvc, $dg, $lidp, $lidc) = $dmv;
                    $dvc->free();
                    $dg->free();
                }
                foreach($dcaso_etiqueta as $de) {
                    $de->free();
                }
                foreach($dcaso_usuario as $du) {
                    $du->free();
                }

                // Inicializa debe ser igual al bloque antes del ciclo
                $caso_existente = false;
                $ubicacion_existente = false;
                $caso_region_existente = false;
                $caso_frontera_existente = false;
                $ffexistente = array();
                $ofexistente = array();
                $caso_contexto_existente = array();
                $caso_presponsable_existente = array();
                $victima_existente = array();
                $victimacol_existente = array();
                $caso_etiqueta_existente = array();
                $caso_usuario_existente = array();

                $lvicr = null; // Lista de victimas repetidas

                $idcaso = 0;
                $obs = ""; // Observaciones por poner en etiqueta error_importacion
                $dcaso = null;
                $dubicacion = null;
                $dcaso_region = null;
                $dcaso_frontera = null;
                $dff = array(); // Fuentes frecuentes
                $dof = array(); // Otras fuentes_caso
                $dofc = array(); // Otras fuentes
                $dcategoria = null;
                $dcaso_contexto = array();
                $dcaso_presponsable = array();
                $dvictima_esp = array();
                $dvictimacol_esp = array();
                $dcaso_etiqueta = array();
                $dcaso_usuario = array();
                $encaso = false;
                $repgen = "";

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
                $du->id_usuario = $idus;
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
    pres_frecs("Frecuencia de ubicaciones en otras fuentes modificadas", $ofex, 0);
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
    pres_frecs("Frecuencia de victimas colectivas modificadas", 
        $victimascolex, 0);
    pres_frecs("Frecuencia de etiquetas existentes modificadas", 
        $etiquetaex, 0);
    pres_frecs("Frecuencia de usuarios existentes modificadas", 
        $usuarioex, $cuentanoexistentes);

    echo "Víctimas nuevas: $vic_nuevas<br>";
    echo "Víctimas existentes: $vic_existentes<br>";
    echo "Actos nuevos: $actosnuevos<br>";
    echo "Actos existentes: $actosex<br><br>";
    echo "Víctimas colectivas nuevas: $vicc_nuevas<br>";
    echo "Víctimas colectivas existentes: $vicc_existentes<br>";
    echo "Actos colectivos nuevos: $actoscnuevos<br>";
    echo "Actos colectivos existentes: $actoscex<br><br>";

}



perform();
