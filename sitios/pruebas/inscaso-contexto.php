<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:

/**
* Inserción de tipo de violencia de un caso
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
 * Inserción de tipo de violencia de un caso
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";

/*** TIPO DE VIOLENCIA ***/

$post= array();
$post['id_contexto'] = array();
$post['id_contexto']['0'] = '1';
$post['id_antecedente'] = array();
$post['id_antecedente']['0'] = '1';
$post['bienes'] = 'bienes';
$post['_qf_tipoViolencia_siguiente'] = 'Siguiente >>';
$post['_qf_default'] = 'tipoViolencia:siguiente';
pasaPestanaFicha($db, array("caso_contexto", "antecedente_caso"), $post, 1);

assert(false); // No llega
?>
