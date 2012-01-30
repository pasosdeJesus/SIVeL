<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:

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
 * @version   CVS: $Id: inscaso-evaluacion-valida.php,v 1.6.2.1 2011/09/14 14:56:19 vtamara Exp $
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
$post['gr_confiabilidad'] = 'Alta';
$post['gr_esclarecimiento'] = 'Alto';
$post['gr_impunidad'] = 'Nula';
$post['gr_informacion'] = 'Parc';

$dcaso = objeto_tabla('caso');
$dcaso->get(1);
foreach (array('gr_confiabilidad', 'gr_esclarecimiento',
    'gr_impunidad', 'gr_informacion'
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
