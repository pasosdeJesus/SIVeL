<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
/**
* Actualiza base de datos despu�s de actualizar fuentes
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2006 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: misc_actualiza.php,v 1.23.2.4 2011/10/22 14:57:56 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

/** Actualiza base de datos despu�s de actualizar fuentes */
require_once "aut.php";
require_once $_SESSION['dirsitio'] . '/conf.php';
require_once "confv.php";
require_once "misc.php";
require_once "DataObjects/Categoria.php";



/**
 * Retorna valor de una variable de un archivo de configuraci�n SH (ultima)
 *
 * @param string $a Archivo de configuraci�n
 * @param string $v Archivo de configuraci�n
 *
 * @return null si no encuentra variable
 */
function valVarConf($a, $v)
{
    assert($v != "");
    if (!($f = fopen($a, "r"))) {
        die("Falta archivo de configuraci�n '$a'");
    }

    $r = null;
    while (!feof($f)) {
        $buffer = fgets($f, 4096);
        if (substr(trim($buffer), 0, strlen($v))==$v) {
            $r = trim(preg_replace('/.*= *"(.*)"/', '$1', $buffer));
        }
    }
    fclose($f);
    return  $r;
}



/** Extrae variables globales de una fuente PHP
 *  http://us.php.net/manual/en/tokenizer.examples.php
 *
 * @param string $f Nombre del archivo .php
 *
 * @return array vector de variables
 **/
function extraeVarPHP($f)
{
    $source = file_get_contents($f);
    $tokens = token_get_all($source);

    $ttokens = count($tokens);
    $estado = 0;
    $avar = array(); // Arreglo con variables encontradas
    $nomvar = ""; //Nombre de �ltima variable encontrada
    $ultcom = ""; //Ultimo comentario encontrado
    for ($i = 0; $i < count($tokens); $i++) {
        $token = $tokens[$i];
        /* Estados:
         * 0 Buscando variable izq
         * 1 Variable izq encontrada, buscando = -> 2 o 4
         * 2 = encontrado, esperando valor
         *
         * 4 [ encontrado, buscando indice
         * 5 indice encontrado buscando ]
         * ] -> 2
         */
        if (is_string($token)) {
            // simple 1-character token
            switch ($token) {
            case '=':
                if ($estado == 1) {
                    $estado = 2;
                } else {
                    $estado = 0;
                }
                break;
            case '[':
                if ($estado == 1) {
                    $estado = 4;
                } else {
                    $estado = 0;
                }
                break;
            case ']':
                if ($estado == 5) {
                    $estado = 1;
                } else {
                    $estado = 0;
                }
                break;

            default:
                $estado = 0;
            }
        } else {
            // token array
            list($id, $text) = $token;
            switch ($id) {
            case T_VARIABLE :
                if ($estado == 0) {
                    $estado = 1;
                    $nomvar = $text;
                } else {
                    $estado = 0;
                }
                break;
            case T_CONSTANT_ENCAPSED_STRING:
                if ($estado == 2) {
                    $avar+=array($nomvar => array($text, $ultcom));
                    $estado = 0;
                } else if ($estado == 4) {
                    $nomvar .= "[$text]";
                    $estado = 5;
                } else {
                    $estado = 0;
                }
                break;
            case T_DOC_COMMENT:
                $ultcom = $text;
                break;
            case T_WHITESPACE:
                break;
            default:
                $estado = 0;
                $ultcom = '';
                break;
            }
        }
    }

    return $avar;
}


/**
 * Presenta en HTML variables PHP
 *
 * @param array $avar Vector de variables
 *
 * @return void
 */
function muestraVarPhpEnHTML($avar)
{
    echo "<pre>";
    foreach ($avar as $nv => $ld) {
        list($vv, $cv) = $ld;
        echo htmlentities($cv)."<br/>";
        echo htmlentities($nv)." = ".htmlentities($vv).";<br/><br/>";
    }
    echo "</pre>";
}

/**
 * Retorna l�neas que definen una variable  en archivo de conf. PHP
 *
 * @param string $a Archivo de configuraci�n
 * @param string $v v
 *
 * @return string null si no encuentra variable
 */
function valVarConfPHP($a, $v)
{
    assert($v != "");
    if (!($f = fopen($a, "r"))) {
        die("Falta archivo de configuraci�n '$a'");
    }

    $r = null;
    $com = ""; /* Comentario */
    $lv = ""; /* Lineas que definen variable */
    $estado = 0;
    /* Estado de automata 0 no encontrado, 1 comentario
        econtrado guardando, 2 variable encontrada, 3 ya procesado
     */
    while ($estado != 3 && !feof($f)) {
        $buffer = fgets($f, 4096);
        //echo "buffer=$buffer, estado=$estado, com=$com, lv=$lv<br>";
        if ($estado == 0 && substr(trim($buffer), 0, strlen($v))==$v) {
            $lv = $com;
            $estado = 2;
        }
        if (substr(trim($buffer), 0, 3)=='/**') {
            if ($estado == 2) {
                $estado = 3;
            } else if ($estado == 0) {
                $estado = 1;
                $com = "";
            }
        }
        if ($estado == 1) {
            $com .= $buffer;
        }
        if (strstr($buffer, '*/')) {
            if ($estado == 2) {
                $estado = 3;
            } else if ($estado == 1 || $estado == 0) {
                $estado = 0;
            }
        }
        if (trim($buffer) == ('?'. '>') && $estado == 2) {
            $estado = 3;
        }
        if ($estado == 2) {
            $lv .= $buffer;
        }
    }

    fclose($f);
    return  $lv;
}


/**
 * Revisa si una versi�n de actualizaci�n ya se aplic�
 *
 * @param string $ver Versi�n
 *
 * @return bool si se aplic� o no
 */
function aplicado($ver)
{
    echo_esc("Revisando aplicaci�n de actualizaci�n $ver: ", false);
    $ds = objeto_tabla('Actualizacion_base');
    $ds->fecha = null;
    $ds->get($ver);
    if (PEAR::isError($ds) || $ds->id != $ver || $ds->fecha == null) {
        echo_esc("No");
        return false;
    }
        echo_esc("Si");
    return true;
}

/**
 * Aplica una actualizaci�n con la fecha de hoy
 *
 * @param object  &$act DataObject a tabla actualizacion_base
 * @param integer $idac Identificaci�n de la actualizaci�n
 * @param string  $desc Descripci�n
 *
 * @return void
 */
function aplicaact(&$act, $idac, $desc)
{
    $act->fecha = date('Y-M-d');
    $act->id = $idac;
    $act->descripcion = $desc;
    $act->insert();

    echo_esc($act->getMessage());
    echo_esc("  Aplicada actualizaci�n $idac ($desc)");
}



/**
 * Actualiza el �ndice de la tabla dada cuya llave es id
 *
 * @param object  &$db       Conexi�n a base de datos
 * @param string  $tabla     Tabla cuyo �ndice se actualizar�
 * @param string  $nid       Nombre del campo con llave de $tabla
 * @param integer $maxreserv M�ximo c�digo reservado para datos de SIVeL
 * nuclear (los datos personalizados tendr�n codigos superiores o
 * iguales a este).
 *
 * @return void
 */
function actualiza_indice(&$db, $tabla, $nid = 'id', $maxreserv = 0)
{
    $q = "SELECT setval('{$tabla}_seq', max($nid)) FROM $tabla";
    $r = hace_consulta($db, $q, false);
    $t = array();
    if (PEAR::isError($r)) {
        echo_esc(
            "Error: " . $r->getMessage() ." - " . $r->getUserInfo() . "<br>"
        );
    }
    if ($maxreserv > 0 && !PEAR::isError($r)
        && $r->fetchInto($t) && $t[0] < $maxreserv
    ) {
        $r = hace_consulta(
            $db, "SELECT setval('{$tabla}_seq', $maxreserv) " .
            " FROM $tabla"
        );
    }
}

/**
 * Cambia el tipo de una columna de una tabla en una base de datos
 *
 * @param object &$db       Conexi�n a base de datos
 * @param string $tabla     Nombre de la tabla
 * @param string $columna   Nombre de la columna
 * @param string $nuevotipo Nuevo tipo de la columna
 *
 * @return void
 */
function cambia_tipocol(&$db, $tabla, $columna, $nuevotipo)
{
    hace_consulta(
        $db, "ALTER TABLE $tabla " .
        " ADD COLUMN {$columna}2t $nuevotipo", false
    );
    hace_consulta($db, "UPDATE $tabla SET {$columna}2t={$columna};", false);
    hace_consulta($db, "ALTER TABLE $tabla DROP COLUMN {$columna};", false);
    hace_consulta(
        $db, "ALTER TABLE $tabla " .
        " RENAME COLUMN {$columna}2t TO {$columna}", false
    );
}


/**
 * Cambia datos de una tabla
 *
 * @param object &$db          Conexi�n a base de datos
 * @param string $tabla        Nombre de tabla por cambiar
 * @param array  $deshabilitar valores por deshabilitar
 * @param array  $agregar      ids y valores por a�adir
 * @param array  $homologar    valores antiguos por cambiar a nuevos
 *                              homologables
 * @param string $cfechades    nombre del campo con fecha de deshabilitaci�n
 * @param string $cfechacre    nombre del campo con fecha de creaci�n
 *
 * @return void
 */
function cambia_datos(&$db, $tabla, $deshabilitar, $agregar, $homologar,
    $cfechades = "fechadeshabilitacion", $cfechacre = "fechacreacion"
) {
    foreach ($deshabilitar as $n) {
        $q = "UPDATE $tabla " .
            " SET $cfechades = '" . date('Y-m-d') . "' " .
            " WHERE nombre = '$n'";
        //echo $q . "<br>";
        hace_consulta($db, $q, false);
    }
    foreach ($agregar as $cod => $nom) {
        $q = "INSERT INTO $tabla " .
            "(id, nombre, $cfechacre) " .
            " VALUES ('$cod', '$nom', '" . date('Y-m-d') . "')";
        //echo $q . "<br>";
        hace_consulta($db, $q, false);
    }
    foreach ($homologar as $act => $nue) {
        $q = "UPDATE $tabla " .
            "SET nombre='$nue',  $cfechades=NULL " .
            " WHERE nombre in ('$act', '$nue')";
        //echo $q . "<br>";
        hace_consulta($db, $q, false);
    }
}


/**
 * Ejecuta consultas SQL del archivo $na.  Se espera que cada
 * consulta este en una l�nea del archivo (de m�ximo 4096 catacteres).
 *
 * @param object &$db   Conexi�n a base de datos
 * @param string $na    Nombre del archivo
 * @param bool   $derr  Detener en caso de error
 * @param bool   $merr  Mostrar mensajes de error
 * @param bool   $mcons Mostrar consultas por ejecutar
 *
 * @return true si y solo si puede leer y ejecutar consultas.
 */
function consulta_archivo(&$db, $na, $derr = false, $merr = false,
    $mcons = false
) {
    $h = @fopen("$na", "r");
    if ($h) {
        $nl = 0;
        while (!feof($h)) {
            $nl++;
            $buffer = fgets($h, 4096);
            if (strlen($buffer) > 4094) {
                echo_esc(
                    "$na:$nl: L�nea de m�s de 4096 caracteres cortada"
                );
            }
            if (substr(trim($buffer), 0, 2) != '--' && trim($buffer) != '') {
                if ($mcons) {
                    echo (int)$nl . "<tt>" 
                        . htmlentities($buffer)
                        . "</tt><br>";
                }
                hace_consulta($db, $buffer, $derr, $merr);
            }
        }
        fclose($h);
        return true;
    }
    return false;
}


/**
 * Agrega fechacreacion y fechadeshabilitacion a una tabla b�sica
 *
 * @param object &$db          Conexi�n a base de datos
 * @param string $nt           Nombre de la tabla b�sica
 * @param bool   $finerror     Indica si termina en caso de error o no
 * @param bool   $muestraerror Indica si debe mostrar mensaje de error
 *
 * @return void
 */
function agrega_fechas(&$db, $nt, $finerror = false, $muestraerror = true)
{
    hace_consulta(
        $db, "ALTER TABLE $nt " .
        "ADD COLUMN fechacreacion DATE NOT NULL DEFAULT '2001-01-01'",
        $finerror, $muestraerror
    );
    hace_consulta(
        $db, "ALTER TABLE $nt " .
        "ADD COLUMN fechadeshabilitacion DATE " .
        "CHECK (fechadeshabilitacion IS NULL OR " .
        "fechadeshabilitacion >= fechacreacion)", 
        $finerror, $muestraerror
    );
}



?>
