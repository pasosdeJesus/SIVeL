<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:

/**
 * Pruebas a estadísticas
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2007 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: estadisticas.php,v 1.6.2.1 2011/09/14 14:56:19 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Estadísticas
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";
require_once "misc.php";


$post = array();

$_POST['_qf_default'] = 'estadisticas:consulta';
$_POST['fini']['d'] = '';
$_POST['fini']['M'] = '';
$_POST['fini']['Y'] = '';
$_POST['ffin']['d'] = '';
$_POST['ffin']['M'] = '';
$_POST['ffin']['Y'] = '';
$_POST['id_tipo_violencia'] = '';
$_POST['segun'] = '';
$_POST['que'] = 'victimas';
$_POST['departamento'] = '1';
$_POST['municipio'] = '1';
$_POST['muestra'] = 'tabla';
$_POST['_qf_estadisticas_consulta'] = 'Consulta';


//$_POST = $post;
$_REQUEST = $_POST;
$_REQUEST['evita_csrf'] = $_SESSION['sin_csrf'] = $_POST['evita_csrf'] = 1234;
require "estadisticas.php";

?>
