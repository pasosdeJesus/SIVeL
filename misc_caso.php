<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker: */

/**
* Funciones diversas �tiles en fuentes que requieren interfaz de captura
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2006 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: misc_caso.php,v 1.35.2.2 2011/10/18 16:05:03 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */


/**
 * Funciones diversas �tiles en fuentes que requieren interfaz de captura
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
 * eliminaCaso(db, idcaso) elimina el caso n�mero idcaso usando la conexi�n
 * a la base db.
 *
 * @param handle  &$db    Conexi�n a base de datos
 * @param integer $idcaso Id. del caso
 *
 * @return void
 */
function eliminaCaso(&$db, $idcaso)
{
    if (!isset($idcaso) || $idcaso == "") {
        die("S�lo se eliminan casos ya ingresados");
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
    $q = "DELETE FROM funcionario_caso WHERE id_caso='$idcaso'";
    hace_consulta($db, $q);
    $q = "DELETE FROM caso WHERE id='$idcaso'";
    $res = hace_consulta($db, $q);

    if (PEAR::isError($res)) {
        die($res->getMessage() ." - " . $res->getUserInfo());
    }
}


/**
 * Llama funci�n actGlobales de cada una de los tabuladores de la
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
