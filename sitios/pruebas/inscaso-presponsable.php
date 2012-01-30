<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:

/**
* Inserción de presuntos responsables de un caso
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2007 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: inscaso-presponsable.php,v 1.9.2.1 2011/09/14 14:56:19 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Inserción de presuntos responsables de un caso
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";

/*** PRESUNTO RESPONSABLE ***/

$post = array();
$post['id_p_responsable'] = '1';
$post['tipo'] = '0';
$post['bloque'] = 'bloque';
$post['frente'] = 'frente';
$post['brigada'] = 'brigada';
$post['division'] = 'division';
$post['batallon'] = 'batallon';
$post['otro'] = 'otro';
$post['clasificacion']['0'] = 'T:1000:1000';
$post['_qf_pResponsables_siguienteMultiple'] = 'Responsable siguiente';
$post['_qf_default'] = 'pResponsables:siguiente';
pasaPestanaFicha(
    $db,
    array("presuntos_responsables_caso",
    "categoria_p_responsable_caso"
    ),
    $post, 1
);

exit(0);
?>
