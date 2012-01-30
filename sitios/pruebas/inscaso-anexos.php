<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:

/**
 * Inserción de anexo
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2011 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: inscaso-anexos.php,v 1.4.2.1 2011/09/14 14:56:19 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Inserción de un anexo
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";

$post = array();
$post['fecha']['d'] = '7';
$post['fecha']['M'] = '3';
$post['fecha']['Y'] = '2011';
$post['descripcion'] = 'desc';
$post['id'] = '';
$post['MAX_FILE_SIZE'] = '2097152';
$post['_qf_anexos_siguienteMultiple'] = 'Anexo siguiente';
$post['_qf_default'] = 'anexos:siguiente';

$files = array();
$files['archivo_sel']['name'] = 'anexo.txt';
$files['archivo_sel']['type'] = 'text/plani';
$files['archivo_sel']['tmp_name'] = 'anexo.txt';

pasaPestanaFicha($db, array("anexo"), $post, 1, true, $files);

exit(0);
?>
