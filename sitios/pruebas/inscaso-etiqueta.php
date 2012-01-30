<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:

/**
 * Inserción de etiqueta
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2011 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: inscaso-etiqueta.php,v 1.4.2.1 2011/09/14 14:56:19 vtamara Exp $
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
$post['fetiqueta'] = '7';
$post['fobservaciones'] = 'con color';
$post['_qf_etiquetas_agregarEtiqueta'] = 'Añadir';
$post['_qf_default'] = 'etiquetas:siguiente';
pasaPestanaFicha($db, array("etiquetacaso"), $post, 1);

exit(0);
?>
