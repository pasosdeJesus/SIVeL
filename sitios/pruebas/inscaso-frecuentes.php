<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:

/**
* Inserción de fuentes frecuentes de un caso
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
 * Inserción de fuentes frecuentes de un caso
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";

/*** FUENTES FRECUENTES ***/

$post = array();
$post['id_prensa'] = '1';
$post['fecha']['d'] = '10';
$post['fecha']['M'] = '10';
$post['fecha']['Y'] = '2007';
$post['ubicacion'] = 'ubicación';
$post['clasificacion'] = 'clasificacion';
$post['ubicacion_fisica'] = 'ubicacion';
$post['_qf_frecuentes_siguienteMultiple'] = 'Fuente siguiente';
$post['_qf_default'] = 'frecuentes:siguiente';
pasaPestanaFicha($db, array("caso_ffrecuente"), $post, 1);

exit(0);
?>
