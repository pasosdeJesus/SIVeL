<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Carga una opción no estándar del menú
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2010 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 */

require_once 'misc.php';
require_once 'aut.php';
require_once $_SESSION['dirsitio'] . '/conf.php';

/*$aut_usuario = "";
$db = autentica_usuario($dsn, $aut_usuario, 0);
Debe autenticarse en la función muestra del modulo */

if (!isset($_REQUEST['num'])) {
    die("Falta parámetro num");
}

if (!isset($GLOBALS['modulo'][(int)$_REQUEST['num']])) {
    die("No se ha definido \$GLOBALS['modulo']["
        . (int)$_REQUEST['num'] . "] en conf.php");
}

require_once $GLOBALS['modulo'][(int)$_REQUEST['num']];
muestra($dsn, $accno);
?>
