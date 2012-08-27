<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:

/**
* Inserción de evaluación un caso
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
 * Inserción de evaluación un caso
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";

/*** EVALUACIÓN ***/

$post = array();
$post['grconfiabilidad'] = 'Alta';
$post['gresclarecimiento'] = 'Alto';
$post['grimpunidad'] = 'Nula';
$post['grinformacion'] = 'Parc';
$post['_qf_evaluacion_basicos'] = 'Datos básicos';
$post['_qf_default'] = 'evaluacion:siguiente';
pasaPestanaFicha($db, array(), $post, 1);
assert(false); // No llega

exit(0);
?>
