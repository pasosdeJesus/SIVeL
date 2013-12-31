<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:

/**
* Inserción de un usuario
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2007 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
*/

/**
 * Inserción de un usuario
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";

$na = (int)$db->getOne("SELECT COUNT(id) FROM usuario;");

$_REQUEST = $_POST = $_GET = array();
$_REQUEST['_qf__dataobjects_usuario'] = $_POST['_qf__dataobjects_usuario'] = '';
$_REQUEST['nusuario'] = $_POST['nusuario'] = 'inv1';
$_REQUEST['password'] = $_POST['password'] = 'b';
$_REQUEST['nombre'] = $_POST['nombre'] = 'c';
$_REQUEST['descripcion'] = $_POST['descripcion'] = 'd';
$_REQUEST['rol'] = $_POST['rol'] = '1';
$_REQUEST['idioma'] = $_POST['idoma'] = 'es_CO';
$_REQUEST['fechacreacion']['Y'] = $_POST['fechacreacion']['Y'] = '2013';
$_REQUEST['fechacreacion']['M'] = $_POST['fechacreacion']['M'] = '12';
$_REQUEST['fechacreacion']['d'] = $_POST['fechacreacion']['d'] = '31';
$_REQUEST['añadir'] = $_POST['añadir'] = 'Añadir';
$_REQUEST['evita_csrf'] = $_SESSION['sin_csrf'] = $_POST['evita_csrf'] = 1234;

$_SERVER['REQUEST_URI'] = 'pruebas';

require_once "detusyrol.php";


/* Verificando */
$nd = (int)$db->getOne("SELECT COUNT(id) FROM usuario;");

echo "insusu nd=$nd\n";

if (($nd-$na)!= 1) {
    echo "No insertó";
    exit(1);
}
exit(0);
?>
