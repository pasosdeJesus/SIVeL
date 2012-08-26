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
 * eliminaCaso(db, idcaso) elimina el caso número idcaso usando la conexión
 * a la base db.
 *
 * @param handle  &$db    Conexión a base de datos
 * @param integer $idcaso Id. del caso
 *
 * @return void
 */
function eliminaCaso(&$db, $idcaso)
{
    if (!isset($idcaso) || $idcaso == "") {
        die("Sólo se eliminan casos ya ingresados");
    }
    foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
        list($n, $c, $o) = $tab;
        $bo[$o] = $c;
    }
    ksort($bo);
    foreach ($bo as $k => $c) {
        if (($d = strrpos($c, "/"))>0) {
            $c = substr($c, $d+1);
        }
        if (is_callable(array($c, 'eliminaDep'))) {
            call_user_func(array($c, 'eliminaDep'), $db, $idcaso);
        } else {
            echo_esc("Falta eliminaDep en $k, $c");
        }
    }
    $q = "DELETE FROM caso_funcionario WHERE id_caso='$idcaso'";
    hace_consulta($db, $q);
    $q = "DELETE FROM caso WHERE id='$idcaso'";
    $res = hace_consulta($db, $q);

    if (PEAR::isError($res)) {
        die($res->getMessage() ." - " . $res->getUserInfo());
    }
}


/**
 * Llama función actGlobales de cada una de los tabuladores de la
 * ficha de captura.
 *
 * @return void
 */
function actGlobales()
{
    foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
        list($n, $c, $o) = $tab;
        if (($d = strrpos($c, "/"))>0) {
            $c = substr($c, $d+1);
        }
        if (is_callable(array($c, 'actGlobales'))) {
            call_user_func_array(array($c, 'actGlobales'), array());
        } else {
            echo_esc("Falta actGlobales en ($n, $c)");
        }
    }
}

?>
