<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:

/**
 * Verifica inserción de evaluación un caso
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
 * Verifica inserción de evaluación un caso
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";
require_once "misc.php";

$post = array();
$post['grconfiabilidad'] = 'Alta';
$post['gresclarecimiento'] = 'Alto';
$post['grimpunidad'] = 'Nula';
$post['grinformacion'] = 'Parc';

$dcaso = objeto_tabla('caso');
$dcaso->get(1);
foreach (array('grconfiabilidad', 'gresclarecimiento',
    'grimpunidad', 'grinformacion'
) as $g
) {
    if ($dcaso->$g != $post[$g]) {
        echo "***No modificó en evaluacion $g (" . $dcaso->$g . " - "
                .$post[$g] . ")";
        exit(1);
    }
}

exit(0);
?>
