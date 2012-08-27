<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:

/**
* Inserción de ubicación de un caso
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
 * Inserción de ubicación de un caso
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";

/*** UBICACIÓN ***/

$post = array();
$post['lugar'] = 'lugar';
$post['id_departamento'] = '1';
$post['id_municipio'] = '1';
$post['id_clase'] = '1';
$post['longitud'] = '10';
$post['latitud'] = '10';
$post['id_caso'] = '1';
$post['id_trelacionsitio'] = '1';
$post['sitio'] = 'sitio';
$post['_qf_ubicacion_siguienteMultiple'] = 'Ubicación siguiente';
$post['_qf_default'] = 'ubicacion:siguiente';
pasaPestanaFicha($db, array("ubicacion"), $post, 1);

exit(0);
?>
