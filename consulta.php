<?php
//  vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker: -->
/**
 * Consulta Detallada.
 * Aprovecha el formulario de captura (captura_caso), se diferencia
 * con una variable de sesión (forma_modo).
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: consulta.php,v 1.25 2011/05/19 04:18:44 vtamara Exp $
 * @link      http://sivel.sf.net
 */

/**
 * Consulta Detallada.
 */
require_once 'misc_caso.php';
require_once 'aut.php';
require_once $_SESSION['dirsitio'] . '/conf.php';
require_once 'DataObjects/Caso.php';

$aut_usuario = "";
autenticaUsuario($dsn, $accno, $aut_usuario, 21);

$idbus = $GLOBALS['idbus'];
$_SESSION['basicos_id'] = $idbus;
$_SESSION['forma_modo'] = 'busqueda';

$dCaso = objeto_tabla('caso');
if (PEAR::isError($dCaso)) {
     die($dCaso->getMessage());
}
$db =& $dCaso->getDatabaseConnection();

eliminaCaso($db, $idbus);

header('Location: captura_caso.php');

?>

