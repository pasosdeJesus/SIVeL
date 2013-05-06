<?php
//  vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Funciones para importar relatos
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */


/**
 * Reporta observación
 *
 * @param string  $nobs   Nueva observación
 * @param string  &$obs   Colchon de observaciones
 * @param boolean $coneco Reportar también en pantalla?
 *
 * @return void
 **/
function rep_obs($nobs, &$obs, $coneco = false)
{
    $obs .= " " . $nobs;
    if ($coneco) {
        echo_esc($nobs);
    }
}



/**
 * Convierte violación
 *
 * @param object &$db      Conexión a base de datos
 * @param string $tipoi    Nombre de violación
 * @param string $id_presp Id. del presunto responsable
 * @param string &$obs     Colchon para agregar observaciones
 *
 * @return integer Código del tipo de violencia o 0 si no encontró
 */
function conv_violacion(&$db, $tipoi, $id_presp, &$obs)
{
    $tipo = a_mayusculas(trim($tipoi));

    $d = objeto_tabla('categoria');
    if ((int)$tipo > 0) {
        $d->id = (int)$tipo;
    } else {
        $d->nombre = a_mayusculas($tipo);
    }
    $d->orderBy('id');
    $d->find(1);
    if (PEAR::isError($d)) {
        die($d->getMessage());
    }
    if (!isset($d->id)) {
        rep_obs("Tipo de Violencia desconocido '$tipo'\n", $obs);
        $pr = 0;
    } else {
        $pr = $d->id;
    }
    return $pr;
}


/**
 * Quita tildes de una cadena
 *
 * @param string $s Cadena
 *
 * @return Cadena sin tildes
 */
function sin_tildes($s)
{
    $r = str_replace(
        array('á', 'é', 'í', 'ó', 'ú', 'ü', 'Á', 'É', 'Í',
        'Ó', 'Ú', 'Ü'
        ),
        array('a', 'e', 'i', 'o', 'u', 'u', 'A', 'E', 'I', 'O', 'U', 'U'),
        $s
    );

    return $r;
}


/**
 * Convierte localización
 *
 * @param object &$db          Conexion a base de datos
 * @param string $departamento Departamento
 * @param string $municipio    Municipio
 * @param string $cenp         Centro poblado
 * @param string &$obs         Colchon para agregar observaciones
 *
 * @return array (idd, idm, idc)  Identificaciones de departamento, municipio
 *  y clase
 */
function conv_localizacion(&$db, $departamento, $municipio, $cenp, &$obs)
{
    if ($departamento == 'BOGOTÁ DC') {
        $departamento = 'DISTRITO CAPITAL';
    }
    if ($departamento == 'GUAJIRA') {
        $departamento = 'LA GUAJIRA';
    }
    if ($municipio == 'TIERRA ALTA') {
        $municipio = 'TIERRALTA';
    }

    //echo "OJO conv_localización comienzo: departamento=$departamento, "
    //    . "municipio=$municipio, observaciones=$obs\n";

    $lugar = '';
    $sitio = '';
    $idd = 1000;
    $idm = 1000;
    $idc = null;
    if ($departamento != '') {
        $d = objeto_tabla('departamento');
        $d->nombre = trim($departamento);
        $d->find(1);
        if (!isset($d->id)) {
            $d->nombre = a_mayusculas($d->nombre);
            $d->find(1);
            $pr = preg_match('/[ÁÉÍÓÚÜ]/', $d->nombre);
            if (!isset($d->id) && $pr > 0) {
                $d->nombre = str_replace(
                    array('Á', 'É', 'Í', 'Ó', 'Ú', 'Ü'),
                    array('A', 'E', 'I', 'O', 'U', 'U'),
                    $d->nombre
                );
                $d->find(1);
            }
        }
        if (PEAR::isError($d)) {
            die($d->getMessage());
        }
        if (!isset($d->id)) {
            rep_obs(
                "Localización: Departamento desconocido '$departamento'",
                $obs
            );
            $idd = 1000;
        } else {
            $idd = $d->id;
        }
    }

    $m = objeto_tabla('municipio');
    if (PEAR::isError($m)) {
        die($m->getMessage());
    }
    if ($municipio != '') {
        $municipiost = str_replace(
            array('Á', 'É', 'Í', 'Ó', 'Ú', 'Ü'),
            array('A', 'E', 'I', 'O', 'U', 'U'),
            a_mayusculas($municipio)
        );
        foreach (array($municipio, a_mayusculas($municipio), $municipiost)
            as $nommun) {
            if ($idd == 1000) {
                $q = "SELECT id, id_departamento FROM municipio "
                    . " WHERE nombre = '$nommun';";
            } else {
                $q = "SELECT id, id_departamento FROM municipio "
                    . " WHERE nombre = '$nommun' "
                    . "AND id_departamento='$idd';";
            }
            $cmun = hace_consulta($db, $q);
            $nr = $cmun->numRows();
            if ($nr > 0) {
                break;
            }
            if ($idd == 1000) {
                $q = "SELECT id, id_departamento FROM municipio "
                    . " WHERE nombre like '%$nommun%';";
            } else {
                $q = "SELECT id, id_departamento FROM municipio "
                    . " WHERE nombre like '%$nommun%' "
                    . "AND id_departamento='$idd';";
            }
            $cmun = hace_consulta($db, $q);
            $nr = $cmun->numRows();
            if ($nr > 0) {
                break;
            }
        }
        $rows = array();
        if ($cmun->fetchInto($rows)) {
            if (PEAR::isError($cmun)) {
                die($cmun->getMessage());
            }
            $idm = $rows[0];
            $idd = $rows[1];
            if ($cmun->fetchInto($rows)) {
                rep_obs(
                    "Hay $nr municipios en departamento $idd " .
                    "con nombre como $municipio, " .
                    "escogido el primero\n", $obs
                );
            }
        } else {
            rep_obs(
                "Localización: Municipio desconocido '$municipio'",
                $obs
            );
            $idm = 1000;
        }
    }
    if ($cenp != '') {
        $cenpst = str_replace(
            array('Á', 'É', 'Í', 'Ó', 'Ú', 'Ü'),
            array('A', 'E', 'I', 'O', 'U', 'U'),
            a_mayusculas($cenp)
        );
        foreach (array($cenp, a_mayusculas($cenp), $cenpst) as $nomc) {
            if ($idd == 1000) {   //$idm tambien es 1000
                $q = "SELECT id, id_departamento, id_municipio FROM clase " .
                    " WHERE nombre = '$nomc';";
            } else if ($idm == 1000) {
                $q = "SELECT id, id_departamento, id_municipio FROM clase " .
                    " WHERE nombre = '$nomc' AND id_departamento='$idd' ";
            } else {
                $q = "SELECT id, id_departamento, id_municipio FROM clase " .
                    " WHERE nombre = '$nomc' AND id_departamento='$idd' " .
                    " AND id_municipio='$idm';";
            }
            $ccla = hace_consulta($db, $q);
            $nr = $ccla->numRows();
            if ($nr > 0) {
                break;
            }
            if ($idd == 1000) {   //$idm tambien es 1000
                $q = "SELECT id, id_departamento, id_municipio FROM clase " .
                    " WHERE nombre like '%$nomc%';";
            } else if ($idm == 1000) {
                $q = "SELECT id, id_departamento, id_municipio FROM clase " .
                    " WHERE nombre like '%$nomc%' AND id_departamento='$idd' ";
            } else {
                $q = "SELECT id, id_departamento, id_municipio FROM clase " .
                    " WHERE nombre like '%$nomc%' AND id_departamento='$idd' " .
                    " AND id_municipio='$idm';";
            }
            $ccla = hace_consulta($db, $q);
            $nr = $ccla->numRows();
            if ($nr > 0) {
                break;
            }
        }
        $rows = array();
        if ($ccla->fetchInto($rows)) {
            if (PEAR::isError($ccla)) {
                die($ccla->getMessage());
            }
            $idc = $rows[0];
            $idd = $rows[1];
            $idm = $rows[2];
            if ($ccla->fetchInto($rows)) {
                rep_obs(
                    "Hay $nr clases con nombre como $cenp, " .
                    "escogido el primero\n", $obs
                );
            }
        } else {
            rep_obs(
                "Localización: Clase desconocida '$cenp' en municipio " .
                "'$idm' y departamento '$idd'",
                $obs
            );
            $idc = 1000;
        }
    }

    //echo "OJO conv_localización final: idd=$idd, idm=$idm, "
    //    . "idc=$idc, observaciones=$obs\n";
    return array($idd, $idm, $idc);
}


/**
 * Indica en observaciones como es conversión de fecha
 *
 * @param string $d       Dia
 * @param string $m       Mes
 * @param string $a       Año
 * @param string $orig    Orig
 * @param string &$dia_s  Para retornar día
 * @param string &$mes_s  Retorna mes
 * @param string &$anio_s Retorna año
 * @param string &$obs    Colchon para agregar observaciones
 *
 * @return array (idd, idm, idc)  Identificaciones de departamento, municipio
 *  y clase
 */
function conv_dia_mes_anio($d, $m, $a, $orig, &$dia_s, &$mes_s, &$anio_s, &$obs)
{
    $dia_s = (int)$d;
    $mes_s = (int)$m;
    $anio_s = (int)$a;

    if ($dia_s == 0) {
        $dia_s = 1;
        $o .= " Falta dia.";
    }
    if ($mes_s == 0) {
        $mes_s = 1;
        $o .= " Falta mes.";
    }
    if ($anio_s == 0) {
        $anio_s = 1970;
        $o .= " Falta año.";
    }

    $m = 'especial';
    if ($o != '') {
        $obs .= " Fecha: $o ($orig).";
        $m = 'incompleta';
    }
}


/**
 * Busca convertir una fecha al formato esperado por la base de datos
 *
 * @param string  $fecha  Fecha
 * @param string  &$obs   Colchon para agregar observaciones
 * @param boolean $depura Mensajes de depuración?
 *
 * @return array (d, m, c) Identificaciones de departamento, municipio y clase
 */
function conv_fecha($fecha, &$obs, $depura = false)
{
    $fecha = ereg_replace("  *", " ", trim($fecha));
    $v = explode("/", $fecha);
    $dia_s = 1;
    $mes_s = 1;
    $anio_s = 1990;
    $nummesp = array(
        'ene' => 1, 'enero' => 1, 1 => 1, '01' => 1, 'en' => 1, 'ene-' => 1,
        'Enero' => 1, 'ENERO' => 1,
        'feb' => 2, 'febrero' => 2, 2 => 2, '02' => 2, 'feb-' => 2,
        'Febrero' => 2, 'febero' => 2, 'febr' => 2, 'FEBRERO' => 2,
        'mar' => 3, 'marzo' => 3, 'marz' => 3, 'marz.' => 3, 3 => 3,
        '03' => 3, 'mar-' => 3, 'Marzo' => 3, 'MARZO' => 3,
        'abr' => 4, 'abril' => 4, 4=> 4, '04' => 4, 'abr-' => 4,
            'Abril' => 4, 'ABRIL' => 4,
        'may' => 5, 'mayo' => 5, 5=>5, '05' => 5, 'may-' => 5,
        'Mayo' => 5, 'MAYO' => 5,
        'jun' => 6, 'junio' => 6, 6=>6, '06' => 6, 'jun-' => 6,
        'Junio' => 6, 'JUNIO' => 6,
        'jul' => 7, 'julio' => 7, 7=>7, '07' => 7, 'jul-' => 7,
        'Julio' => 7, 'jilio' => 7, 'jl' => 7, 'JULIO' => 7,
        'ago' => 8, 'agosto' => 8, 'ago-' => 8, 8=>8, '08' => 8,
            'ago.' => 8, 'ago-' => 8, 'ag' => 8, 'agos-' => 8, 'Agosto' => 8,
            'AGOSTO' => 8,
        'sep' => 9, 'septiembre' => 9, 9=>9, '09' => 9, 'sept' => 9,
            'sep-' => 9, 'Septiembre' => 9, 'SEPTIEMBRE' => 9,
        'oct' => 10, 'octubre' => 10, 10=>10, 'oct-' => 10, 'oct.' => 10,
        'Octubre' => 10, 'OCTUBRE' => 10,
        'nov' => 11, 'noviembre' => 11, 'nov-' => 11, 11 => 11,
            'novoembre' => 11, 'nov-' => 11, 'Noviembre' => 11,
            'NOVIEMBRE' => 11,
        'dic' => 12, 'diciembre' => 12, 12 => 12, 'dic-' => 12,
            'Diciembre' => 12,
            'DICIEMBRE' => 12
    );

    $diasmes = array(
        1=>31 , 2=>29, 3=>31, 4=>30, 5=>31, 6=>30,
        7=>31, 8=>31, 9=>30, 10=>31, 11=>30, 12=>31
    );

    if (count($v)==3 && (ctype_digit($v[0]) || $v[0] == '**')
        && (ctype_digit($v[1]) || $v[1] == '**')
        && (ctype_digit($v[2]) || $v[2] == '****')
    ) {
        if ($depura) {
            echo "caso 1";
        }
        $des = '';
        if ((int)$v[0] > 0) {
            $dia_s = (int)$v[0];
        } else {
            $des = 'incompleta';
            $dia_s = 1;
            $observacion = "Fecha: dia por completar. ";
        }
        $mes_s = (int)$v[1];
        if ((int)$v[1] > 0) {
            $mes_s = (int)$v[1];
        } else {
            $des = 'incompleta';
            $mes_s = 1;
            $observacion = "Fecha: mes por completar. ";
        }
        $anio_s = (int)$v[2];
        if ((int)$v[2] > 0) {
            $anio_s = (int)$v[2];
        } else {
            $anio_s = 2;
            $observacion = "Fecha: anio por completar. ";
            $des = 'incompleta';
        }
    } else if (count($v)==3 && (int)$v[0] > 0 && strpos($v[1], '-')>0) {
        if ($depura) {
            echo "caso 2";
        }
        list($a) = explode("-", $v[1]);
        if ($a < 100) {
            $a+=1900;
        }
        $dia_s = 1;
        $mes_s = (int)$v[0];
        $anio_s = $a;
        rep_obs(
            "Fecha incompleta '$fecha' convertida a $dia_s . $mes_s . $anio_s",
            $obs
        );
    } else if (count($v)==4 && (int)$v[0] > 0 && (int)$v[2] > 0 && (int)$v[3] > 0
        && $v[1] == ''
    ) {

        if ($depura) {
            echo "caso 3";
        }
        $dia_s = (int)$v[0];
        $mes_s = (int)$v[2];
        $anio_s = (int)$v[3];
    } else if (count($v)==5 && (int)$v[0] > 0 && (int)$v[1] > 0
        && (int)substr($v[2], 0, 4)>1900
    ) {

        if ($depura) {
            echo "caso 4";
        }
        $dia_s = (int)$v[0];
        $mes_s = (int)$v[1];
        $anio_s = (int)substr($v[2], 0, 4);
    } else if (count($v)==2 && $v[0] == '21706') {
        if ($depura) {
            echo "caso 5";
        }
        $dia_s = '21';
        $mes_s = '6';
        $anio_s = $v[1];
    } else {
        if ($depura) {
            echo "caso 6";
        }
        $pe = explode("-", $v[0]);
        if (count($pe) == 3) {
            if ($pe[0] > 1900) {
                if ($depura) {
                    echo "caso 6.0";
                }
                $dia_s = (int)$pe[2];
                $mes_s = (int)$pe[1];
                $anio_s = (int)$pe[0];
            }
        } else {
            $pu = explode(" ", $v[0]);
            for ($i = 0;$i < count($pu);$i++) {
                $pu[$i] = trim(str_replace("-", "", $pu[$i]));
            }
            if (count($pu)==3) {
                if ($depura) {
                    echo "caso 6.1";
                }
                if ((int)$pu[2] >= 1 && strcasecmp($pu[1], 'de')==0
                    && isset($nummesp[$pu[0]])
                ) { //'mes de anio'
                    if ($depura) {
                        echo "caso 6.1.1";
                    }
                    $dia_s = 1;
                    $mes_s = $nummesp[$pu[0]];
                    $anio_s = str_replace('.', '', $pu[2]);
                    rep_obs(
                        "Fecha incompleta '$fecha' convertida a " .
                        "1 . $mes_s . $anio_s", $obs
                    );
                } else if ($pu[0] >= 1900 && ($pu[1] == ''
                    || strcasecmp($pu[1], 'a')==0)
                ) {
                    if ($depura) {
                        echo "caso 6.1.2";
                    }
                    $dia_s = 1;
                    $mes_s = 1;
                    $anio_s = (int)$pu[0];
                    rep_obs(
                        "Fecha incompleta '$fecha' convertida a " .
                        "$dia_s . $mes_s . $anio_s", $obs
                    );
                } else if ($pu[0] >= 0 && $pu[0] <= 31 && $pu[1] >= 0
                    && $pu[1] <= 12 && $pu[2] >= 1900
                ) {
                    if ($depura) {
                        echo "caso 6.1.3";
                    }
                    conv_dia_mes_anio(
                        $pu[0], $pu[1], $pu[2], $fecha,
                        $dia_s, $mes_s, $anio_s, $obs
                    );
                } else if (strcasecmp($pu[1], 'de')==0
                    && isset($nummesp[$pu[2]]) && (int)$pu[0] > 0
                ) { //'dia de mes'
                    if ($depura) {
                        echo "caso 6.1.4";
                    }
                    $dia_s = (int)$pu[0];
                    $mes_s = $nummesp[$pu[2]];
                    $anio_s = 1970;
                    rep_obs(
                        "Fecha incompleta '$fecha' convertida a " .
                        "$dia_s . $mes_s . $anio_s", $obs
                    );
                } else {
                    if ($depura) {
                        echo "caso 6.1.5";
                    }
                    rep_obs(
                        "Fecha con formato no reconocido $fechan",
                        $obs
                    );
                }
            } else if (count($pu)==5 && strcasecmp($pu[1], 'de')==0
                && strcasecmp($pu[3], 'de')==0
            ) { //'dia de mes de anio'
                if ($depura) {
                    echo "caso 6.2";
                }
                $dia_s = $pu[0];
                $mes_s = $nummesp[$pu[2]];
                $anio_s = $pu[4];
            } else if (count($pu)==4 && (int)$pu[0] > 0
                && strcasecmp($pu[2], 'de')==0
            ) { //'dia mes de anio'
                if ($depura) {
                    echo "caso 6.3";
                }
                $dia_s = $pu[0];
                $mes_s = $nummesp[$pu[1]];
                $anio_s = $pu[3];
            } else if (count($pu)==4 && strcasecmp($pu[0], 'desde')==0
                && strcasecmp($pu[2], 'de')==0
                && isset($nummesp[$pu[1]]) && (int)$pu[3] > 1900
            ) { //'desde mes de anio'
                if ($depura) {
                    echo "caso 6.4";
                }
                $dia_s = 1;
                $mes_s = $nummesp[$pu[1]];
                $anio_s = $pu[3];
                rep_obs(
                    "Fecha incompleta '$fecha' convertida a " .
                    "$dia_s . $mes_s . $anio_s", $obs
                );
            } else if (count($pu)>=4 && (strcasecmp($pu[2], 'aprox.')==0
                || strcasecmp($pu[2], '-aprox.')==0) && $pu[3] > 0
            ) {
                if ($depura) {
                    echo "caso 6.5";
                }
                $dia_s = 1;
                $mes_s = 1;
                $anio_s = $pu[3];
                rep_obs(
                    "Fecha incompleta '$fecha' convertida a " .
                    "$dia_s . $mes_s . $anio_s", $obs
                );
            } else if (count($pu)>=5 && (strcasecmp($pu[2], 'aprox.')==0
                || strcasecmp($pu[2], '-aprox.')==0) && $pu[4] > 0
            ) {
                if ($depura) {
                    echo "caso 6.6";
                }
                $dia_s = 1;
                $mes_s = 1;
                $anio_s = $pu[4];
                rep_obs(
                    "Fecha incompleta '$fecha' convertida a " .
                    "$dia_s . $mes_s . $anio_s", $obs
                );
            } else if (count($pu)>=7 && (strcasecmp($pu[3], 'aprox.')==0
                || strcasecmp($pu[3], '-aprox.')==0) && (int)$pu[6] > 0
            ) {
                if ($depura) {
                    echo "caso 6.7";
                }
                $dia_s = 1;
                $mes_s = 1;
                $anio_s = (int)$pu[6];
                rep_obs(
                    "Fecha incompleta '$fecha' convertida a " .
                    "$dia_s . $mes_s . $anio_s", $obs
                );
            } else if (count($pu)>=4 && (strcasecmp($pu[2], 'aprox.')==0
                || strcasecmp($pu[2], '-aprox.')==0)
                && isset($nummesp[$pu[3]])
            ) {
                if ($depura) {
                    echo "caso 6.8";
                }
                $dia_s = 1;
                $mes_s = $nummesp[$pu[3]];
                $anio_s = $pu[4];
                rep_obs(
                    "Fecha incompleta '$fecha' convertida a " .
                    "$dia_s . $mes_s . $anio_s", $obs
                );
            } else if (count($pu)>=4 && strcasecmp($pu[2], 'aprox.')==0
                && $pu[3] == 'de' && $pu[4] > 0
            ) {
                if ($depura) {
                    echo "caso 6.9";
                }
                $dia_s = 1;
                $mes_s = 1;
                $anio_s = $pu[4];
                rep_obs(
                    "Fecha incompleta '$fecha' convertida a " .
                    "$dia_s . $mes_s . $anio_s", $obs
                );
            } else if (count($pu)>=2 && strcasecmp($pu[0], 'de')==0
                && (int)$pu[1] > 0
            ) {
                if ($depura) {
                    echo "caso 6.10";
                }
                $dia_s = 1;
                $mes_s = 1;
                $anio_s = $pu[1];
                rep_obs(
                    "Fecha incompleta '$fecha' convertida a " .
                    "$dia_s . $mes_s . $anio_s", $obs
                );
            } else if ((int)$v[0] > 1900) {
                if ($depura) {
                    echo "caso 6.11";
                }
                $dia_s = 1;
                $mes_s = 1;
                $anio_s = (int)$v[0];
                rep_obs(
                    "Fecha incompleta '$fecha' convertida a " .
                    "$dia_s . $mes_s . $anio_s", $obs
                );
            } else if ((int)$pu[0] > 1900) {
                if ($depura) {
                    echo "caso 6.12";
                }
                $dia_s = 1;
                $mes_s = 1;
                $anio_s = (int)$pu[0];
                rep_obs(
                    "Fecha incompleta '$fecha' convertida a " .
                    "$dia_s . $mes_s . $anio_s", $obs
                );
            } else {
                if ($depura) {
                    echo "caso 6.13";
                }
                $vg = preg_split('/[-.]+/', $v[0]);
                for ($i = 0;$i < count($vg);$i++) {
                    $vg[$i] = trim($vg[$i]);
                }
                if (count($vg) == 3) {
                    $des = 'especial';
                    if ($vg[0] == '00') {
                        $obs .= " Fecha: Dia desconocido. ($fecha)";
                        $dia_s = 1;
                        $des = 'incompleta';
                    } else {
                        $dia_s = (int)$vg[0];
                    }
                    if ($vg[1] == '00') {
                        $obs .= " Fecha: Mes desconocido. ($fecha)";
                        $mes_s = 1;
                        $des = 'incompleta';
                    } else {
                        $mes_s = (int)$vg[1];
                    }
                    $anio_s = (int)$vg[2];
                    if ($anio_s < 15) {
                        $anio_s+=2000;
                    } else if ($anio_s < 100) {
                        $anio_s+=1900;
                    }
                } else if (count($vg)==1 && $vg[0] > 0) {
                    $dia_s = 1;
                    $mes_s = 1;
                    $anio_s = $vg[0];
                    rep_obs(
                        "Fecha incompleta '$fecha' convertida a " .
                        "$dia_s . $mes_s . $anio_s", $obs
                    );
                } else {
                    rep_obs(
                        "Fecha con formato no reconocido $fecha, " .
                        "se esperaban 3 partes", $obs
                    );
                }
            }
        }
    }

    if ($anio_s > 50 && $anio_s < 100) {
        $anio_s+=1900;
    }
    if ($mes_s > 12 && $dia_s <= 12) {
        $t = $mes_s;
        $mes_s = $dia_s;
        $dia_s = $t;
        rep_obs("Fecha intercambiando mes y día $fecha", $obs);
    }
    if ($anio_s < 1900 || $anio_s>(int)@date('Y')) {
        rep_obs("Fecha: año errado ($fecha), dejando 1970.", $obs);
        $anio_s = 1970;
    }
    if ($mes_s < 1 || $mes_s > 12) {
        rep_obs(" Fecha: mes errado ($fecha), dejando 1.", $obs);
        $mes_s = 1;
    }
    if ($dia_s < 1 || $dia_s > $diasmes[$mes_s]) {
        rep_obs(" Fecha: dia errado ($fecha), dejando 1.", $obs);
        $dia_s = 1;
    }

    return $anio_s . "-" . $mes_s . "-".$dia_s;
}

/**
 * Convierte datos de persona y los inserta/actualiza en la base
 *
 * @param object &$db             Conexion a base de datos
 * @param object &$idsiguales     Arreglo para completar con iguales
 * @param object &$idssimilares   Arreglo para completar con similares
 * @param array  $aper            Listado de personas de la base
 * @param string $nom             Nombre buscado, 
 *      si es null supone que se busca apellidos nombres que vienen en $ap
 * @param string $ap              Apellido buscado, 
 *      si es null supone que se busca nombres apellidos que vienen en $nom
 * @param string $mdlev           Distancia Levenshtein maxima para similares
 * @param string $anionac         Año de nacimiento
 * @param string $mesnac          Mes de nacimiento
 * @param string $dianac          Dia de nacimiento
 * @param string $sexo            Sexo
 * @param string $id_departamento Código del dep. de procedencia
 * @param string $id_municipio    Código del mun. de procedencia
 * @param string $id_clase        Código de la clase de procedencia
 * @param string $tipodocumento   Tipo de documento de identidad
 * @param string $numerodocumento Número de documento de identidad
 *
 * @return integer  Iguales mas similares.  Las ids de los iguales loas
 * deja en idsiguales, los ids de similares en idssimilares
 */

function ubica_persona(&$db, &$idsiguales, &$idssimilares, $aper,
    $nom, $ap, $mdlev = 3, $anionac = null, $mesnac = null, $dianac = null,
    $sexo = 'S', $id_departamento = null, $id_municipio = null,
    $id_clase = null, $tipodocumento = null, $numerodocumento = null
) {
    //echo "OJO ubica_persona(nom=$nom, $ap, $mdlev, ...)<br>";
    $idper = -1;
    if (isset($GLOBALS['estilo_nombres'])
        && $GLOBALS['estilo_nombres'] == 'MAYUSCULAS'
    ) {
        $nombres = a_mayusculas($nom);
        $apellidos = a_mayusculas($ap);
    } else if (isset($GLOBALS['estilo_nombres'])
        && $GLOBALS['estilo_nombres'] == 'a_minusculas'
    ) {
        $nombres = prim_may($nom);
        $apellidos = prim_may($ap);
    } else {
        $nombres = $nom;
        $apellidos = $ap;
    }

    $nomap = trim(trim($nombres) . " " . trim($apellidos));
    $apnom = trim(trim($apellidos) . " " . trim($nombres));
    //echo "OJO nomap=$nomap, apnom=$apnom<br>";
    if ($nomap != 'NN' && $nomap != 'N N' && $nomap != 'N.N'
        && $nomap != 'N.N.' && $nomap != 'N N.'
        && substr($nomap, 0, 4) != 'N.N '
    ) {
        foreach ($aper as $k=>$na) {
            if (isset($GLOBALS['estilo_nombres'])
                && $GLOBALS['estilo_nombres'] == 'MAYUSCULAS'
            ) {
                $anombres = a_mayusculas($na[0]);
                $aapellidos = a_mayusculas($na[1]);
                /*$nombres = a_mayusculas($nom);
                $apellidos = a_mayusculas($ap); */
            } else if (isset($GLOBALS['estilo_nombres'])
                && $GLOBALS['estilo_nombres'] == 'a_minusculas'
            ) {
                $anombres = prim_may($na[0]);
                $aapellidos = prim_may($na[1]);
            } else {
                $anombres = $na[0];
                $aapellidos = $na[1];
            }

            $anomap = trim(trim($anombres) . " " . trim($aapellidos));
            $aapnom = trim(trim($aapellidos) . " " . trim($anombres));
            //echo "OJO comp $anombres, $nombres y $aapellidos, "
            //. "$apellidos, anomap=$anomap, aapnom=$aapnom, "
            //. "nomap=$nomap, apnom=$apnom<br>";
            if ((strcasecmp($anombres, $nombres) == 0
                && strcasecmp($aapellidos, $apellidos) == 0)
                || (strcasecmp($anomap, $nomap) == 0)
                || (strcasecmp($aapnom, $apnom) == 0)
            ) {
                //echo "OJO iguales<br>";
                $idsiguales[] = $k;
            } else {
                $dn = levenshtein($anombres, $nombres);
                $da = levenshtein($aapellidos, $apellidos);
                $dna = levenshtein($anomap, $nomap);
                $dan = levenshtein($aapnom, $apnom);
                //echo "OJO mdlev=$mdlev,dn=$dn,da=$da,dna=$dna,dan=$dan<br>";
                if (($dn <= $mdlev && $da <= $mdlev) || $dna <= $mdlev 
                    || $dan <= $mdlev
                ) {
                    //echo "OJO similares<br>";
                    $idssimilares[] = $k;
                }
            }
        }
    }
}

/**
 * Convierte datos de persona y los inserta/actualiza en la base
 *
 * @param object &$db             Conexion a base de datos
 * @param array  &$aper           Listado de personas de la base
 * @param string &$obs            Colchon para agregar observaciones
 * @param string $nom             Nombre buscado
 * @param string $ap              Apellido buscado
 * @param string $anionac         Año de nacimiento
 * @param string $mesnac          Mes de nacimiento
 * @param string $dianac          Dia de nacimiento
 * @param string $sexo            Sexo
 * @param string $id_departamento Código del dep. de procedencia
 * @param string $id_municipio    Código del mun. de procedencia
 * @param string $id_clase        Código de la clase de procedencia
 * @param string $tipodocumento   Tipo de documento de identidad
 * @param string $numerodocumento Número de documento de identidad
 *
 * @return integer  Identificación de registro persona insertado/actualizado
 */
function conv_persona(&$db, &$aper, &$obs, $nom, $ap, $anionac,
    $mesnac = null, $dianac = null, $sexo = 'S',
    $id_departamento = null, $id_municipio = null, $id_clase = null,
    $tipodocumento = null, $numerodocumento = null
) {
    //echo "OJO conv_persona(db, aper, obs, $nom, $ap, $anionac,...)<br>\n";
    if (isset($GLOBALS['estilo_nombres'])
        && $GLOBALS['estilo_nombres'] == 'MAYUSCULAS'
    ) {
        $nombres = a_mayusculas($nom);
        $apellidos = a_mayusculas($ap);
    } else if (isset($GLOBALS['estilo_nombres'])
        && $GLOBALS['estilo_nombres'] == 'a_minusculas'
    ) {
        $nombres = prim_may($nom);
        $apellidos = prim_may($ap);
    } else {
        $nombres = $nom;
        $apellidos = $ap;
    }

    //echo "OJO conv_persona. nombres=$nombres, apellidos=$apellidos<br>\n";
    $idper = -1;
    $nomap = trim(trim($nombres) . " " . trim($apellidos));
    if ($nomap != 'NN' && $nomap != 'N N' && $nomap != 'N.N'
        && $nomap != 'N.N.' && $nomap != 'N N.'
        && substr($nomap, 0, 4) != 'N.N '
    ) {
        foreach ($aper as $k=>$na) {
            $anombres = $na[0];
            $aapellidos = $na[1];
            $cper = '';
            $sep = ', como víctima en casos ';
            foreach ($na[2] as $nc) {
                $cper .= $sep . $nc;
                $sep = ", ";
            }
            //echo "OJO comp $anombres, $nombres y $aapellidos, $apellidos<br>";
            if (strcasecmp($anombres, $nombres) == 0
                && strcasecmp($aapellidos, $apellidos) == 0
            ) {
                rep_obs(
                    " Persona repetida $k - '$nomap' $cper.",
                    $obs
                );
                $idper = $k;
                break;
            } else if (levenshtein($anombres, $nombres)<3
                && levenshtein($aapellidos, $apellidos)<3
            ) {
                rep_obs(
                    "Persona posiblemente repetida $k - '$anombres' " .
                    "'$aapellidos' -- '$nombres' '$apellidos' $cper ",
                    $obs
                );
            }
        }
    }
    if ($idper==-1) {
        $dpersona = objeto_tabla('persona');
        $dpersona->nombres = $nombres;
        $dpersona->apellidos = $apellidos;
        $dpersona->sexo = $sexo;
        $dpersona->insert();
        //print_r($dpersona);
        $idper = $dpersona->id;
        $aper[$idper] = array($nombres, $apellidos, array());
    }
    $dpersona = objeto_tabla('persona');
    $dpersona->id = $idper;
    $dpersona->find(1);
    foreach (array(
        'anionac', 'mesnac', 'dianac', 'id_departamento', 'id_municipio',
        'id_clase', 'tipodocumento', 'numerodocumento'
    ) as $c
    ) {
        //echo "OJO c=$c, \$c=" . $$c ."<br>";
        if ($$c != null && $dpersona->$c == null) {
            $dpersona->$c = $$c;
        }
    }

    $dpersona->update();

    return $idper;
}


/**
 * Extrae todos los datos de personas de la base
 *
 * @param object &$db Conexion a base de datos
 *
 * @return array($aper, $maxidper) arreglo
 * de personas indexado por identificación en base, maxima identificación.
 * Cada entrada tiene es 
 *   idpersona => array(nombre, apellido, casoscomovictima, casoscomofamiliar)
 */
function extrae_per(&$db)
{
    $aper = array(); // aper[idper] es arreglo con nombre, apellidos,
    // arreglo de casos en los que aparece

    $options =& PEAR::getStaticProperty('DB_DataObject', 'options');
    $options['dont_die'] = true;

    $maxidper = 0;
    $rp = hace_consulta(
        $db, 'SELECT id, nombres, apellidos FROM persona ORDER BY id'
    );
    /*$row = array():
    $pe = objeto_tabla('persona');
    $pe->orderBy('id');
    $pe->find(); */
    $datp= array();
    while ($rp->fetchInto($datp)) {
        $idp = $datp[0];
        $rv = hace_consulta(
            $db, "SELECT id_caso FROM victima WHERE id_persona='$idp' " 
            . " ORDER BY id_caso"
        );
        /* $cvi = objeto_tabla('victima');
        $cvi->orderBy('id_caso');
        $cvi->id_persona = $idp;
        $cvi->find(); */
        $vcasos = array();
        $datv = array();
        while ($rv->fetchInto($datv)) {
            $vcasos[$datv[0]] = $datv[0];
        }

        $rf = hace_consulta(
            $db, "SELECT DISTINCT id_caso, persona1 "
            . " FROM persona_trelacion, victima " 
            . " WHERE persona2='$idp' AND id_persona=persona1" 
            . " ORDER BY persona1"
        );
        $fcasos = array(); // Casos en los que es familiar
        $datf = array();
        while ($rf->fetchInto($datf)) {
            $fcasos[$datf[0]] = $datf[0];
        }

        /*$cr = objeto_tabla('persona_trelacion');
        $cr->orderBy('persona1');
        $cr->persona2=$idp;
        $cr->find();
        if ($cr->fetch()) {
            //echo "--OJO Encontrado como familiar de ".$cr->persona1."\n";
            $cvi = objeto_tabla('Victima');
            $cvi->orderBy('id_caso');
            $cvi->id_persona = $cr->persona1;
            $cvi->find();
            while ($cvi->fetch()) {
                $fcasos[$cvi->id_caso] = $cvi->id_caso;
            }
        } */

        if ($maxidper < $idp) {
            $maxidper = $idp;
        }
        if (count($vcasos) > 0 || count($fcasos) > 0) {
            $aper[$idp][0] = $datp[1];
            $aper[$idp][1] = $datp[2];
            $aper[$idp][2] = $vcasos; // Casos en los que es víctima
            $aper[$idp][3] = $fcasos; // Casos en los que es familiar
        }
    }

    return array($aper, $maxidper);
}


/**
 * Convierte víctimas colectivas insertando los datos de requerirse
 *
 * @param object &$db    Conexion a base de datos
 * @param array  $agr    Listado de grupos de la base
 * @param string $idcaso Identificación del caso que se edita
 * @param string $grupo  Grupo buscado
 * @param string &$obs   Colchon para agregar observaciones
 *
 * @return integer identificación el grupo en base
 */
function conv_victima_col(&$db, $agr, $idcaso, $grupo, &$obs)
{
    $nombre = ereg_replace(
        "  *", " ",
        trim($grupo->nombre_grupo)
    );
    $nombre = str_replace("*", "", $nombre);

    if (isset($GLOBALS['estilo_nombres'])
        && $GLOBALS['estilo_nombres'] == 'MAYUSCULAS'
    ) {
        $nombre = a_mayusculas($nombre);
    } else if (isset($GLOBALS['estilo_nombres'])
        && $GLOBALS['estilo_nombres'] == 'a_minusculas'
    ) {
        $nombre = prim_may($nombre);
    }

    $idgr = -1;
    foreach ($agr as $k => $na) {
        $anombre = $na[0];
        $cper = '';
        $sep = ', como víctima en casos ';
        foreach ($na[2] as $nc) {
            $cper .= $sep . $nc;
            $sep = ", ";
        }
        if ($anombre == $nombre) {
            rep_obs(
                "-- Usando grupo existente $k - $nombre $cper.\n",
                $obs
            );
            $idgr = $k;
            break;
        } else if (levenshtein($anombre, $nombre)<3) {
            rep_obs(
                "-- Grupo posiblemente existente $k - " .
                "'$anombre'  -- '$nombre' $cper", $obs
            );
        }
    }
    if ($idgr ==-1) {
        $dgrupo = objeto_tabla('grupoper');
        $dgrupo->nombre = $nombre;
        if (!$dgrupo->insert()) {
            rep_obs("No pudo insertarse grupo '$nombre'");
            return 0;
        }
        $idgr = $dgrupo->id;
        $agr[$idgr] = array($nombre, array());
    }

    // Inserta Víctima Colectiva
    $dvictimacol= objeto_tabla('victimacolectiva');
    $dvictimacol->id_caso = $idcaso;
    $dvictimacol->id_grupoper = $idgr;
    $dvictimacol->personasaprox = dato_en_obs($grupo, 'personasaprox');
    $dvictimacol->anotaciones = dato_en_obs($grupo, 'anotaciones');
    $oa = dato_en_obs($grupo, 'organizacion_armada');
    if ($oa != '') {
        $dvictimacol->organizacionarmada
            = (int)conv_basica(
                $db, 'presponsable', $oa, $obs
            );
    }
    if (!$dvictimacol->insert()) {
        rep_obs(
            "Acto: No pudo insertar víctima col. '$idgr', '",
            $obs
        );
    }

    $atradrel = DataObjects_Victimacolectiva::tradRelato();
    foreach ($atradrel as $t => $vt) {
        $cx = $vt[0];
        $idt = $vt[1];
        //echo "OJO t=$t, cx=$cx, idt=$idt<br>\n";
        $partes = explode(";", dato_en_obs($grupo, $cx));
        foreach ($partes as $v) {
            if ($v != null && $v != '') {
                $drviccol = objeto_tabla($t);
                $l = $drviccol->links();
                $drviccol->id_caso = $idcaso;
                $drviccol->id_grupoper = $idgr;
                $pl = explode(':', $l[$idt]); //vinculoestado:id
                $drviccol->$idt = (int)conv_basica($db, $pl[0], $v, $obs);
                if (!$drviccol->insert()) {
                    rep_obs(
                        "Colectiva: No pudo insertar '$v' en "
                        . " $t:$idt,$id_caso,$id_grupoper\n", $obs
                    );
                }
            }
        }
    }

    return $idgr;
}


/**
 * Retorna información de todos grupos en la base
 *
 * @param object &$db Conexion a base de datos
 *
 * @return array $agr[$idgr] = array($nom, $lc) indexado por identificación
 * de grupos, cada uno tiene nombre y arreglo de casos en los que aparece
 */
function extrae_grupos(&$db)
{
    $agr = array();
    // agr[idper] es arreglo con nombre arreglo de casos en los que aparece

    $options =& PEAR::getStaticProperty('DB_DataObject', 'options');
    $options['dont_die'] = true;

    $maxidper = 0;
    $pe = objeto_tabla('grupoper');
    $pe->orderBy('id');
    $pe->find();
    while ($pe->fetch()) {
        $agr[$pe->id] = array(0 => $pe->nombre);
        $cvi = objeto_tabla('Victimacolectiva');
        $cvi->orderBy('id_caso');
        $cvi->id_grupoper= $pe->id;
        $cvi->find();
        $vcasos = array();
        while ($cvi->fetch()) {
            $vcasos[$cvi->id_caso] = $cvi->id_caso;
        }
        $agr[$pe->id][2] = $vcasos; // Casos en los que son víctima
    }

    return $agr;
}


/**
 * Retorna información de todos los casos en la base
 *
 * @return array ($fechacaso, $cat, $ubicacion) arreglos de fechas, categorias
 * y ubicaciones indexados por id. de caso
 */
function extrae_casos()
{
    $obs = array(); // Arreglo de observaciones
    $fechacaso = array(); //Arreglo de fechas de los casos
    $cat = array(); // Arreglo de categorias por caso
    $ubicacion = array(); // Arreglo de ubicaciones de los casos
    $dcaso = objeto_tabla('caso');
    $db = $dcaso->getDatabaseConnection();
    $maxidcaso = 0;
    $dcaso->orderBy('id');
    $dcaso->find();
    while ($dcaso->fetch()) {
        $obs[$dcaso->id]=""; // No hay observaciones para esta conversión en BD
        $fechacaso[$dcaso->id] = $dcaso->fecha;
        $cat[$dcaso->id] = array();
        $dcat = objeto_tabla('acto');
        $dcat->orderBy('id_categoria');
        $dcat->id_caso = $dcaso->id;
        $dcat->find();
        while ($dcat->fetch()) {
            $cat[$dcaso->id][$dcat->id_categoria] = $dcat->id_categoria;
        }
        $dubi = objeto_tabla('ubicacion');
        $dubi->id_dcaso = $dcaso->id;
        $dubi->find();
        $ubicaso[$dcaso->id]= array(
            $dubi->id_departamento,
            $dubi->id_municipio
        );
    }

    return array($fechacaso, $cat, $ubicacion);
}


/**
 * Busca convertir un número de lenguaje natural a entero
 *
 * @param string $n Número en lenguaje natural
 *
 * @return integer Con número o 0 si no lo logra
 */
function lnat_a_numero($n)
{
    if ((int)$n > 0) {
        return (int)$n;
    }
    if ((string)$n === "0") {
        return 0;
    }
    switch (a_mayusculas(trim($n))) {
    case "UNO" :
    case "UN" :
    case "UNA" :
        return 1;
    case "DOS" :
        return 2;
    case "TRES" :
        return 3;
    case "CUATRO" :
        return 4;
    case "CINCO" :
        return 5;
    case "SEIS" :
        return 6;
    case "SIETE" :
        return 7;
    case "OCHO" :
        return 8;
    case "NUEVE" :
        return 9;
    case "DIEZ" :
        return 10;
    }

    return 0;
}

/**
 * Indica si un elmento XML tiene el atributo con nombre nomat
 *
 * @param object $oxml  Objeto XML
 * @param string $nomat Nombre del atributo
 *
 * @return boolean si tiene o no el atributo
 * Referencia: http://php.net/manual/en/simplexmlelement.attributes.php
 * inge at elektronaut dot no 26-May-2004 05:53
 */
function sxml_tiene_atributo($oxml, $nomat)
{
    foreach ($oxml->attributes() as $a => $b) {
        if ($a == $nomat) {
            return true;
        }
    }
    return false;
}

/**
 * Retorna valor de un atributo de un elmento XML
 *
 * @param object $oxml  Objeto XML
 * @param string $nomat Nombre del atributo
 *
 * @return Valor del atributo;
 * Pre: El elemento tiene el atributo
 */
function sxml_valor_atributo($oxml, $nomat)
{
    foreach ($oxml->attributes() as $a => $b) {
        if ($a == $nomat) {
            return $b;
        }
    }
    echo_esc("objeto no tiene atributo esperado $nomat");
    assert(false);
}


/**
 * Determina si en las observaciones de un objeto hay un dato buscado
 * y de haberlo lo retorna
 *
 * @param object &$oxml Objeto XML
 * @param string $id    Identificación por buscar en observaciones
 *
 * @return null si no hay observacion con el tipo dado o la observación
 */
function dato_en_obs(&$oxml, $id)
{
    assert(isset($oxml) && get_class($oxml) == 'SimpleXMLElement');
    assert(isset($id) && $id != '');

    //echo "OJO dato_en_obs(oxml, id=$id)<br>";
    $po = $oxml->xpath("observaciones[@tipo='$id']");
    //echo "po tras xpath"; print_r($po); #die("x");
    if (count($po) == 0) {
        return null;
    }
    if (count($po) > 1) {
        echo "Problema en función <tt>datoenObservaciones(oxml, id="
            . htmlentities($id, ENT_COMPAT, 'UTF-8')
            . ")</tt>."
            . "  Hay varias observaciones con tipo buscado<br>";
    }
    $r = (string)$po[0];
    return $r;
}


/**
 * De ser posible inserta un dato en una tabla básica sancandolo
 * de las observaciones de un objeto
 *
 * @param object  &$db        Conexion a base de datos
 * @param string  &$obs       Para quejarse
 * @param object  $oxml       Objeto XML
 * @param string  $ntipoobs   Nombre del campo en observaciones
 * @param string  $ntablabas  Nombre de la tabla básica
 * @param string  $ntablacaso Nombre de la tabla que relaciona la básica
 *   con caso, si se deja en blanco no intenta agregar registro
 * @param integer $idcaso     Identificación del caso
 * @param string  $sepv       Separador si vienen varios datos
 * @param string  $ncampo     Nombre de campo en tabla básica, '' es ntipoobs
 *
 * @return integer Id. del dato en la tabla básica o 0 si no se encuentra
 */
function dato_basico_en_obs(&$db, &$obs, $oxml,
    $ntipoobs, $ntablabas, $ntablacaso, $idcaso, $sepv = null, $ncampo = ''
) {

    $ret = 0;
    if ($ncampo == '') {
        $ncampo = $ntipoobs;
    }
/*    echo "OJO dato_basico_en_obs(db, observaciones, oxml, "
     . "ntipoobs=$netiqueta, ntablabas=$ntablabas, ntablacaso=$ntablacaso,"
     . " idcaso=$idcaso, sepv=$sepv)<br>"; */

    $noms = dato_en_obs($oxml, $ntipoobs);
    if ($noms != null) {
        if ($sepv != null) {
            $adat = explode($sepv, $noms);
        } else {
            $adat = array(0 => $noms);
        }
        foreach ($adat as $nom) {
            $idb = conv_basica($db, $ntablabas, $nom, $obs);
            //echo "OJO itera nom=$nom, idb=$idb<br>";
            if ($idb != 0 && $ntablacaso != '') {
                $dt = objeto_tabla($ntablacaso);
                $dt->id_caso = $idcaso;
                $dt->$ncampo = $idb;
                $dt->insert();
            }
        }
        $ret = $idb;
    } else {
        $dt = objeto_tabla($ntablabas);
        $ret = $dt->idSinInfo();
    }
    return $ret;
}


/**
 * Convierte categoria de cadena a número (especialmente si tiene el código
 * al final entre paréntesis).
 *
 * @param object &$db  Conexion a base de datos
 * @param string &$obs Colchon para reportar notas de conversión
 * @param string $agr  Cadena con agresión
 * @param string $pr   Presunto responsable
 *
 * @return Identificación de categoria
 * @see conv_violacion
 */
function conv_categoria(&$db, &$obs, $agr, $pr)
{
    $id_categoria = 0;
    if (($pi = strrpos($agr, "("))>0 && ($pd = strrpos($agr, ")"))>0) {
        $id_categoria = (int)substr($agr, $pi+1, $pd-$pi-1);
    }
    if ($id_categoria == 0) {
        $id_categoria = conv_violacion($db, $agr, $pr, $obs);
    }
    return $id_categoria;
}


/**
 * Convierte presunto responsable si hay uno en tabla básica
 *
 * @param object &$db       Conexion a base de datos
 * @param int    $idcaso    Id. Caso
 * @param int    $idp       Id. presunto responsable leido en relato
 * @param object $g         Datos del presunto responsable como grupo
 * @param array  &$id_presp Arreglo de pr. resp ya identificados
 * @param string &$obs      Colchon para reportar notas de conversión
 *
 * @return integer Identificación de presunto responsable en base o -1 si no hay
 */

function conv_presp(&$db, $idcaso, $idp, $g, &$id_presp, &$obs)
{
    $nomg = $g->nombre_grupo;
    $pr = conv_basica(
        $db, 'presponsable',
        $nomg, $obs, false
    );
    if ($pr == -1) {
        return -1;
    }
    $id_presp[$idp] = $pr;
    //echo "OJO asignando id_presp[$idp] = $pr<br>";
    $dpresp = objeto_tabla('caso_presponsable');
    $dpresp->id_caso = $idcaso;
    $dpresp->id_presponsable = $pr;
    $dpresp->tipo = 0;
    $dpresp->id = 1;
    foreach (array('bloque', 'frente', 'brigada',
        'batallon', 'division', 'otro') as $c
    ) {
        $dpresp->$c = dato_en_obs($g, $c);
    }
    $ids = DataObjects_Presponsable::idSinInfo();
    if ($pr == $ids
        && $nomg != 'SIN INFORMACIÓN'
        && $nomg != 'SIN INFORMACION'
    ) {
        $dpresp->otro = $nomg;
    }
    if (!$dpresp->insert()) {
        rep_obs(
            "No pudo insertar p. resp '" .
            $dpresp->id_presponsable . "'",
            $obs
        );
    }
    foreach ($g->agresion_sin_vicd as $ag) {
        if (!empty($ag)) {
            $idc = conv_categoria(
                $db, $obs, (string)$ag, $pr
            );
            $ocp = objeto_tabla('caso_categoria_presponsable');
            $ocp->id_caso = $idcaso;
            $ocp->id_presponsable = $pr;
            $ocp->id = $dpresp->id;
            $ocp->id_categoria = $idc;
            $ocat = objeto_tabla('Categoria');
            $ocat->id = (int)$idc;
            $ocat->find(1); $ocat->fetch();
            if (PEAR::isError($ocat)) {
                rep_obs(
                    "No se reconoció categoria $ag",
                    $obs
                );
            } else {
                $ocp->id_tviolencia
                    = $ocat->id_tviolencia;
                $ocp->id_supracategoria
                    = $ocat->id_supracategoria;
                $ocp->insert();
            }
        }
    }
    return $pr;
}

?>
