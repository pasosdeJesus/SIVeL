<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:

/**
 * Inserción de acto
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2011 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
*/

/**
 * Inserción de un acto en un caso
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";

$post = array();
$post['presponsables'][0] = '1';
$post['categorias'][0] = '1000';
$post['victimas'][0] = '1';
$post['id_caso'] = '1';
$post['_qf_acto_agregarActo'] = 'Añadir';
$post['_qf_default'] = 'acto:siguiente';

pasaPestanaFicha($db, array("acto"), $post, 1);

exit(0);
?>
