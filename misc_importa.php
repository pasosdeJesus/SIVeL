<?php
//  vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
/**
 * Funciones para importar relatos
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: misc_importa.php,v 1.32.2.4 2011/10/22 14:57:56 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */


/**
 * Reporta observaci�n
 *
 * @param string  $nobs   Nueva observaci�n
 * @param string  &$obs   Colchon de observaciones
 * @param boolean $coneco Reportar tambi�n en pantalla?
 *
 * @return void
 **/
function repObs($nobs, &$obs, $coneco = false)
{
    $obs .= " " . $nobs;
    if ($coneco) {
        echo_esc($nobs);
    }
}


/**
 * Busca dato en una tabla b�sica
 *
 * @param object &$db    Conexi�n a base de datos
 * @param string $tabla  Tabla en la cual buscar
 * @param string $nombre Nombre por buscar
 * @param string &$obs   Colchon para agregar observaciones
 *
 * @return integer C�digo en tabla o 0 si no lo encuentra
 */
function convBasica(&$db, $tabla, $nombre, &$obs)
{
    //echo "OJO convBasica(db, $tabla, $nombre, $obs)<br>";
    $d = objeto_tabla($tabla);
    $nom0 = $d->nombre = ereg_replace(
        "  *", " ",
        trim(var_escapa($nombre, $db))
    );
    $d->find(1);
    if (PEAR::isError($d)) {
        die($d->getMessage());
    }
    $nom1 = a_mayusculas($nom0);
    if (!isset($d->id)) {
        $d->nombre = $nom1;
        $d->find(1);
    }
    if (!isset($d->id)) {
        $nom2 = $d->nombre
            = a_mayusculas(sinTildes(var_escapa($nom0, $db)));
        $d->find(1);
    }
    if (!isset($d->id)) {
        $q = "SELECT id FROM $tabla WHERE nombre ILIKE '%${nom0}%'";
        $r = $db->getOne($q);
        if (PEAR::isError($r) || $r == null) {
            $q = "SELECT id FROM $tabla WHERE nombre ILIKE '%${nom1}%'";
            $r = $db->getOne($q);
        }
        if (PEAR::isError($r) || $r == null) {
            $q = "SELECT id FROM $tabla WHERE nombre ILIKE '%${nom2}%'";
            //echo " q=$q";
            $r = $db->getOne($q);
        }

        if (PEAR::isError($r) || $r == null) {
            repObs("-- $tabla: desconocido '$nombre'", $obs);
            if (is_callable(array("DataObjects_$tabla", 'idSinInfo'))) {
                $r = call_user_func(
                    array("DataObjects_$tabla",
                    "idSinInfo"
                    )
                );
            } else {
                $r = 0;
            }
        } else {
            $d = objeto_tabla($tabla);
            $d->id = $r;
            $d->find(1);
            if (trim($d->nombre) != trim($nom0)) {
                repObs(
                    "$tabla: elegido registro '$r' con nombre '" .
                    $d->nombre . "' que es similar a '$nom0'", $obs
                );
            }
        }
    } else {
        $r = $d->id;
    }

    return $r;
}


/**
 * Convierte violaci�n
 *
 * @param object &$db      Conexi�n a base de datos
 * @param string $tipoi    Nombre de violaci�n
 * @param string $id_presp Id. del presunto responsable
 * @param string &$obs     Colchon para agregar observaciones
 *
 * @return integer C�digo del tipo de violencia o 0 si no encontr�
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
        repObs("Tipo de Violencia desconocido '$tipo'\n", $obs);
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
function sinTildes($s)
{
    $r = str_replace(
        array('�', '�', '�', '�', '�', '�', '�', '�', '�',
        '�', '�', '�'
        ),
        array('a', 'e', 'i', 'o', 'u', 'u', 'A', 'E', 'I', 'O', 'U', 'U'),
        $s
    );

    return $r;
}


/**
 * Convierte localizaci�n
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
    if ($departamento == 'BOGOT� DC') {
        $departamento = 'DISTRITO CAPITAL';
    }
    if ($departamento == 'GUAJIRA') {
        $departamento = 'LA GUAJIRA';
    }
    if ($municipio == 'TIERRA ALTA') {
        $municipio = 'TIERRALTA';
    }

    //echo "OJO conv_localizaci�n comienzo: departamento=$departamento, "
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
            $pr = preg_match('/[������]/', $d->nombre);
            if (!isset($d->id) && $pr > 0) {
                $d->nombre = str_replace(
                    array('�', '�', '�', '�', '�', '�'),
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
            repObs(
                "Localizaci�n: Departamento desconocido '$departamento'",
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
            array('�', '�', '�', '�', '�', '�'),
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
                repObs(
                    "Hay $nr municipios en departamento $idd " .
                    "con nombre como $municipio, " .
                    "escogido el primero\n", $obs
                );
            }
        } else {
            repObs(
                "Localizaci�n: Municipio desconocido '$municipio'",
                $obs
            );
            $idm = 1000;
        }
    }
    if ($cenp != '') {
        $cenpst = str_replace(
            array('�', '�', '�', '�', '�', '�'),
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
                repObs(
                    "Hay $nr clases con nombre como $cenp, " .
                    "escogido el primero\n", $obs
                );
            }
        } else {
            repObs(
                "Localizaci�n: Clase desconocida '$cenp' en municipio " .
                "'$idm' y departamento '$idd'",
                $obs
            );
            $idc = 1000;
        }
    }

    //echo "OJO conv_localizaci�n final: idd=$idd, idm=$idm, "
    //    . "idc=$idc, observaciones=$obs\n";
    return array($idd, $idm, $idc);
}


/**
 * Indica en observaciones como es conversi�n de fecha
 *
 * @param string $d       Dia
 * @param string $m       Mes
 * @param string $a       A�o
 * @param string $orig    Orig
 * @param string &$dia_s  Para retornar d�a
 * @param string &$mes_s  Retorna mes
 * @param string &$anio_s Retorna a�o
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
        $o .= " Falta a�o.";
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
 * @param boolean $depura Mensajes de depuraci�n?
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
        repObs(
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
                    repObs(
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
                    repObs(
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
                    repObs(
                        "Fecha incompleta '$fecha' convertida a " .
                        "$dia_s . $mes_s . $anio_s", $obs
                    );
                } else {
                    if ($depura) {
                        echo "caso 6.1.5";
                    }
                    repObs(
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
                repObs(
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
                repObs(
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
                repObs(
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
                repObs(
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
                repObs(
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
                repObs(
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
                repObs(
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
                repObs(
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
                repObs(
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
                    repObs(
                        "Fecha incompleta '$fecha' convertida a " .
                        "$dia_s . $mes_s . $anio_s", $obs
                    );
                } else {
                    repObs(
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
        repObs("Fecha intercambiando mes y d�a $fecha", $obs);
    }
    if ($anio_s < 1900 || $anio_s>(int)date('Y')) {
        repObs("Fecha: a�o errado ($fecha), dejando 1970.", $obs);
        $anio_s = 1970;
    }
    if ($mes_s < 1 || $mes_s > 12) {
        repObs(" Fecha: mes errado ($fecha), dejando 1.", $obs);
        $mes_s = 1;
    }
    if ($dia_s < 1 || $dia_s > $diasmes[$mes_s]) {
        repObs(" Fecha: dia errado ($fecha), dejando 1.", $obs);
        $dia_s = 1;
    }

    return $anio_s . "-" . $mes_s . "-".$dia_s;
}


/**
 * Convierte datos de persona y los inserta/actualiza en la base
 *
 * @param object &$db             Conexion a base de datos
 * @param array  &$aper           Listado de personas de la base
 * @param string &$obs            Colchon para agregar observaciones
 * @param string $nom             Nombre buscado
 * @param string $ap              Apellido buscado
 * @param string $anionac         A�o de nacimiento
 * @param string $sexo            Sexo
 * @param string $id_departamento C�digo del dep. de procedencia
 * @param string $id_municipio    C�digo del mun. de procedencia
 * @param string $id_clase        C�digo de la clase de procedencia
 * @param string $tipodocumento   Tipo de documento de identidad
 * @param string $numerodocumento N�mero de documento de identidad
 *
 * @return array (d, m, c) Identificaciones de departamento, municipio y clase
 */
function conv_persona(&$db, &$aper, &$obs, $nom, $ap, $anionac, $sexo = 'S',
    $id_departamento = null, $id_municipio = null, $id_clase = null,
    $tipodocumento = null, $numerodocumento = null
) {
    $nombres = $nom;
    $apellidos = $ap;
    $idper = -1;
    $nomap = trim(trim($nombres) . " " . trim($apellidos));
    if ($nomap != 'NN' && $nomap != 'N N' && $nomap != 'N.N'
        && $nomap != 'N.N.' && $nomap != 'N N.'
    ) {
        foreach ($aper as $k=>$na) {
            $anombres = $na[0];
            $aapellidos = $na[1];
            $cper = '';
            $sep = ', como v�ctima en casos ';
            foreach ($na[2] as $nc) {
                $cper .= $sep . $nc;
                $sep = ", ";
            }
            if (strcasecmp($anombres, $nombres) == 0
                && strcasecmp($aapellidos, $apellidos) == 0
            ) {
                repObs(
                    " Persona repetida $k - '$nomap' $cper.",
                    $obs
                );
                $idper = $k;
                break;
            } else if (levenshtein($anombres, $nombres)<3
                && levenshtein($aapellidos, $apellidos)<3
            ) {
                repObs(
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
            $idper = $dpersona->id;
            $aper[$idper] = array($nombres, $apellidos, array());
    }
    $dpersona = objeto_tabla('persona');
    $dpersona->id = $idper;
    $dpersona->find(1);
    foreach (array(
        'anionac', 'id_departamento', 'id_municipio',
        'id_clase', 'tipodocumento', 'numerodocumento'
    ) as $c
    ) {
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
 * @return array($aper, $maxidper, $fechacaso, $ubicaso, $cat, $obs) arreglo
 * de persona, maxima identificaci�, arreglo de fechas de los casos, arreglo
 * de ubicaciones de los casos, arreglo de categorias, arreglo de observaciones.
 * Indexados por la identificaci�n en base.
 */
function extrae_per(&$db)
{
    $aper = array(); // aper[idper] es arreglo con nombre, apellidos,
    // arreglo de casos en los que aparece
    $fechacaso = array(); // fechacaso[idcaso] es fecha en la que ocurri� caso
    $ubicaso = array(); // ubicaso[idcaso] es arreglo con id_dep, id_mun
    $cat = array(); // cat[idcaso] es arreglo de categorias del caso
    $obs = array(); // obs[idcaso] es cadena con observaciones de la conversi�n

    $options =& PEAR::getStaticProperty('DB_DataObject', 'options');
    $options['dont_die'] = true;

    $maxidper = 0;
    $pe = objeto_tabla('Persona');
    $pe->orderBy('id');
    $pe->find();
    while ($pe->fetch()) {
        $aper[$pe->id] = array(0=>$pe->nombres, 1=>$pe->apellidos);
        $cvi = objeto_tabla('victima');
        $cvi->orderBy('id_caso');
        $cvi->id_persona = $pe->id;
        $cvi->find();
        $vcasos = array();
        while ($cvi->fetch()) {
            $vcasos[$cvi->id_caso] = $cvi->id_caso;
        }
        $aper[$pe->id][2] = $vcasos; // Casos en los que es v�ctima
        $fcasos = array(); // Casos en los que es familiar
        $cr = objeto_tabla('Relacion_personas');
        $cr->orderBy('id_persona1');
        $cr->id_persona2=$pe->id;
        $cr->find();
        if ($cr->fetch()) {
            //echo "--OJO Encontrado como familiar de ".$cr->id_persona1."\n";
            $cvi = objeto_tabla('Victima');
            $cvi->orderBy('id_caso');
            $cvi->id_persona = $cr->id_persona1;
            $cvi->find();
            while ($cvi->fetch()) {
                $fcasos[$cvi->id_caso] = $cvi->id_caso;
            }
        }
        $aper[$pe->id][3] = $fcasos; // Casos en los que es familiar

        if ($maxidper < $pe->id) {
            $maxidper = $pe->id;
        }
    }

    return array($aper, $maxidper, $fechacaso, $ubicaso, $cat, $obs);
}


/**
 * Convierte v�ctimas colectivas insertando los datos de requerirse
 *
 * @param object &$db    Conexion a base de datos
 * @param array  $agr    Listado de grupos de la base
 * @param string $idcaso Identificaci�n del caso que se edita
 * @param string $grupo  Grupo buscado
 * @param string &$obs   Colchon para agregar observaciones
 *
 * @return integer identificaci�n el grupo en base
 */
function conv_victima_col(&$db, $agr, $idcaso, $grupo, &$obs)
{
    $nombre = ereg_replace(
        "  *", " ",
        trim(utf8_decode($grupo->nombre_grupo))
    );
    $nombre = str_replace("*", "", $nombre);

    $idgr = -1;
    foreach ($agr as $k => $na) {
        $anombre = $na[0];
        $cper = '';
        $sep = ', como v�ctima en casos ';
        foreach ($na[2] as $nc) {
            $cper .= $sep . $nc;
            $sep = ", ";
        }
        if ($anombre == $nombre) {
            repObs(
                "-- Usando grupo existente $k - $nombre $cper.\n",
                $obs
            );
            $idgr = $k;
            break;
        } else if (levenshtein($anombre, $nombre)<3) {
            repObs(
                "-- Grupo posiblemente existente $k - " .
                "'$anombre'  -- '$nombre' $cper", $obs
            );
        }
    }
    if ($idgr ==-1) {
        $dgrupo = objeto_tabla('grupoper');
        $dgrupo->nombre = $nombre;
        if (!$dgrupo->insert()) {
            repObs("No pudo insertarse grupo '$nombre'");
            return 0;
        }
        $idgr = $dgrupo->id;
        $agr[$idgr] = array($nombre, array());
    }

    // Inserta V�ctima Colectiva
    $dvictimacol= objeto_tabla('victima_colectiva');
    $dvictimacol->id_caso = $idcaso;
    $dvictimacol->id_grupoper = $idgr;
    $dvictimacol->personas_aprox = dato_en_obs($grupo, 'personas_aprox');
    $dvictimacol->anotaciones = dato_en_obs($grupo, 'anotaciones');
    $oa = dato_en_obs($grupo, 'organizacion_armada');
    if ($oa != '') {
        $dvictimacol->id_organizacion_armada
            = (int)convBasica(
                $db, 'presuntos_responsables', $oa, $obs
            );
    }
    if (!$dvictimacol->insert()) {
        repObs(
            "Acto: No pudo insertar v�ctima col. '$idgr', '",
            $obs
        );
    }

    $atradrel = DataObjects_Victima_colectiva::tradRelato();
    foreach ($atradrel as $t => $vt) {
        $cx = $vt[0];
        $idt = $vt[1];
        //echo "OJO t=$t, cx=$cx, idt=$idt<br>\n";
        $partes = explode(";", dato_en_obs($grupo, $cx)); // ya hace utf8_decode
        foreach ($partes as $v) {
            if ($v != null && $v != '') {
                $drviccol = objeto_tabla($t);
                $l = $drviccol->links();
                $drviccol->id_caso = $idcaso;
                $drviccol->id_grupoper = $idgr;
                $pl = explode(':', $l[$idt]); //vinculo_estado:id
                $drviccol->$idt = (int)convBasica($db, $pl[0], $v, $obs);
                if (!$drviccol->insert()) {
                    repObs(
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
 * Retorna informaci�n de todos grupos en la base
 *
 * @param object &$db        Conexion a base de datos
 * @param array  &$fechacaso Fecha del caso
 * @param string &$ubicaso   Ubicaci�n
 * @param string &$cat       Categor�a de violencia
 * @param string &$obs       Colchon para agregar observaciones
 *
 * @return array $agr[$idgr] = array($nom, $lc) indexado por identificaci�n
 * de grupos, cada uno tiene nombre y arreglo de casos en los que aparece
 */
function extrae_grupos(&$db, &$fechacaso, &$ubicaso, &$cat, &$obs)
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
        $cvi = objeto_tabla('Victima_colectiva');
        $cvi->orderBy('id_caso');
        $cvi->id_grupoper= $pe->id;
        $cvi->find();
        $vcasos = array();
        while ($cvi->fetch()) {
            $vcasos[$cvi->id_caso] = $cvi->id_caso;
        }
        $agr[$pe->id][2] = $vcasos; // Casos en los que son v�ctima
    }

    return $agr;
}


/**
 * Retorna informaci�n de todos los casos en la base
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
        $obs[$dcaso->id]=""; // No hay observaciones para esta conversi�n en BD
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
 * Busca convertir un n�mero de lenguaje natural a entero
 *
 * @param string $n N�mero en lenguaje natural
 *
 * @return integer Con n�mero o 0 si no lo logra
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
    case "UNA" : return 1;
    case "DOS" : return 2;
    case "TRES" : return 3;
    case "CUATRO" : return 4;
    case "CINCO" : return 5;
    case "SEIS" : return 6;
    case "SIETE" : return 7;
    case "OCHO" : return 8;
    case "NUEVE" : return 9;
    case "DIEZ" : return 10;
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
 * @param object $oxml Objeto XML
 * @param string $id   Identificaci�n por buscar en observaciones
 *
 * @return null si no hay observacion con el tipo dado o la observaci�n
 */
function dato_en_obs($oxml, $id)
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
        echo "Problema en funci�n <tt>datoenObservaciones(oxml, id="
            . htmlentities($id)
            . ")</tt>." 
            . "  Hay varias observaciones con tipo buscado<br>";
    }
    $r = utf8_decode((string)$po[0]);
    return $r;
}


/**
 * De ser posible inserta un dato en una tabla b�sica sancandolo
 * de las observaciones de un objeto
 *
 * @param object  &$db        Conexion a base de datos
 * @param string  &$obs       Para quejarse
 * @param object  $oxml       Objeto XML
 * @param string  $ntipoobs   Nombre del campo en observaciones
 * @param string  $ntablabas  Nombre de la tabla b�sica
 * @param string  $ntablacaso Nombre de la tabla que relaciona la b�sica
 *   con caso, si se deja en blanco no intenta agregar registro
 * @param integer $idcaso     Identificaci�n del caso
 * @param string  $sepv       Separador si vienen varios datos
 * @param string  $ncampo     Nombre de campo en tabla b�sica, '' es ntipoobs
 *
 * @return integer Id. del dato en la tabla b�sica o 0 si no se encuentra
 */
function dato_basico_en_obs(&$db, &$obs, $oxml,
    $ntipoobs, $ntablabas, $ntablacaso, $idcaso, $sepv = null, $ncampo = ''
) {
    if ($ncampo == '') {
        $ncampo = $ntipoobs;
    }
    //echo "OJO dato_basico_en_obs(db, observaciones, oxml, "
    // . "ntipoobs=$netiqueta, ntablabas=$ntablabas, ntablacaso=$ntablacaso,"
    // . " idcaso=$idcaso, sepv=$sepv)<br>";

    $noms = dato_en_obs($oxml, $ntipoobs);
    if ($noms != null) {
        if ($sepv != null) {
            $adat = explode($sepv, $noms);
        } else {
            $adat = array(0 => $noms);
        }
        foreach ($adat as $nom) {
            $idb = convBasica($db, $ntablabas, $nom, $obs);
            //echo "OJO itera nom=$nom, idb=$idb<br>";
            if ($idb != 0 && $ntablacaso != '') {
                $dt = objeto_tabla($ntablacaso);
                $dt->id_caso = $idcaso;
                $dt->$ncampo = $idb;
                $dt->insert();
            }
        }
        return $idb;
    }
    return 0;
}


/**
 * Convierte categoria de cadena a n�mero (especialmente si tiene el c�digo
 * al final entre par�ntesis).
 *
 * @param object &$db  Conexion a base de datos
 * @param string &$obs Colchon para reportar notas de conversi�n
 * @param string $agr  Cadena con agresi�n
 * @param string $pr   Presunto responsable
 *
 * @return Identificaci�n de categoria
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

?>
