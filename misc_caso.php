<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8: */

/**
* Funciones diversas útiles en fuentes que requieren interfaz de captura
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2006 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */


/**
 * Funciones diversas útiles en fuentes que requieren interfaz de captura
 */
require_once "aut.php";
require_once $_SESSION['dirsitio'] . "/conf.php";

foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
    list($n, $c, $o) = $tab;
    // @codingStandardsIgnoreStart
    require_once "$c.php";
    // @codingStandardsIgnoreEnd
}

/**
 * elimina_caso(db, idcaso) elimina el caso número idcaso usando la conexión
 * a la base db.
 *
 * @param handle  &$db    Conexión a base de datos
 * @param integer $idcaso Id. del caso
 *
 * @return void
 */
function elimina_caso(&$db, $idcaso)
{
    if (!isset($idcaso) || $idcaso == "") {
        die("Sólo se eliminan casos ya ingresados");
    }
    $q = "DELETE FROM homonimosim WHERE id_persona1 IN
        (SELECT id_persona FROM victima WHERE id_caso='$idcaso') OR
        id_persona2 IN
        (SELECT id_persona FROM victima WHERE id_caso='$idcaso') 
        ";
    hace_consulta($db, $q, false, false);

    foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
        list(, $c, $o) = $tab;
        //echo "OJO 1 o=$o, c=$c<br>";
        $bo[$o . $c] = $c;
    }
    ksort($bo);
    foreach ($bo as $k => $c) {
        if (($d = strrpos($c, "/"))>0) {
            $c = substr($c, $d+1);
        }
        //echo "OJO c=$c<br>";
        if (is_callable(array($c, 'eliminaDep'))) {
            call_user_func(array($c, 'eliminaDep'), $db, $idcaso);
        } else {
            echo_esc("Falta eliminaDep en $k, $c");
        }
    }
    $q = "DELETE FROM caso_usuario WHERE id_caso='$idcaso'";
    hace_consulta($db, $q);
    $q = "DELETE FROM caso WHERE id='$idcaso'";
    $res = hace_consulta($db, $q);

    if (PEAR::isError($res)) {
        foreach ($bo as $k => $c) {
            if (($d = strrpos($c, "/"))>0) {
                $c = substr($c, $d+1);
            }
            echo "Se intentó eliminar " 
                . htmlentities($c,  ENT_COMPAT, 'UTF-8') . "<br>";
        }
        die($res->getMessage() ." - " . $res->getUserInfo());
    }
}


/**
 * Llama función act_globales de cada una de los tabuladores de la
 * ficha de captura.
 *
 * @return void
 */
function act_globales()
{
    foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
        list($n, $c, ) = $tab;
        if (($d = strrpos($c, "/"))>0) {
            $c = substr($c, $d+1);
        }
        if (is_callable(array($c, 'act_globales'))) {
            call_user_func_array(array($c, 'act_globales'), array());
        } else {
            echo_esc("Falta act_globales en ($n, $c)");
        }
    }
}

?>
