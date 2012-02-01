<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
/**
 * Carga una opci�n no est�ndar del men�
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2010 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: opcion.php,v 1.9.2.3 2011/12/31 19:28:47 vtamara Exp $
 * @link      http://sivel.sf.net
 */

require_once 'misc.php';
require_once 'aut.php';
require_once $_SESSION['dirsitio'] . '/conf.php';

/*$aut_usuario = "";
$db = autenticaUsuario($dsn, $accno, $aut_usuario, 0); 
Debe autenticarse en la funci�n muestra del modulo */

if (!isset($_REQUEST['num'])) {
    die("Falta par�metro num");
}

if (!isset($GLOBALS['modulo'][(int)$_REQUEST['num']])) {
    die("No se ha definido \$GLOBALS['modulo'][" 
        . (int)$_REQUEST['num'] . "] en conf.php");
}

require_once $GLOBALS['modulo'][(int)$_REQUEST['num']];
muestra($dsn, $accno);
?>
