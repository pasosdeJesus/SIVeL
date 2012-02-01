<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:

/**
* Inserción de un caso
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2007 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: inscaso-basico.php,v 1.6.2.1 2011/09/14 14:56:19 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Inserción de un caso
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";

/*** BÁSICOS ***/
$post = array();
$post['_qf_basicos_ubicacion'] = 'Ubicación';
$post['busid'] = '';
$post['titulo'] = 'Título';
$post['fecha']['d'] = '7';
$post['fecha']['M'] = '8';
$post['fecha']['Y'] = '2007';
$post['hora'] = '15:00';
$post['duracion'] = '3:00';
$post['id_intervalo'] = '1';
$post['tipo_ubicacion'] = 'S';
$post['id_caso'] = '';
$post['evita_csrf'] = '1234';
$post['_qf_default'] = 'basicos:siguiente';

pasaPestanaFicha($db, array("caso"), $post, null);

assert(false); // No llega
?>
