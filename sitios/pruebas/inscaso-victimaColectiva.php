<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:

/**
* Inserción de víctima colectiva un caso
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
 * Inserción de víctima colectiva un caso
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";

/*** VÍCTIMA COLECTIVA ***/

$post = array();
$post['nombre'] = 'nombre';
$post['anotaciones'] = 'anotaciones';
$post['personasaprox'] = '3';
$post['organizacionarmada'] = '1';
$post['id_antecedente']['0'] = '1';
$post['id_rango']['0'] = '1';
$post['id_rango']['1'] = '6';
$post['id_sector']['0'] = '1';
$post['id_vinculoestado']['0'] = '1';
$post['id_filiacion']['0'] = '1';
$post['id_profesion']['0'] = '1';
$post['id_organizacion']['0'] = '1';
$post['_qf_victimaColectiva_siguienteMultiple'] = 'Vic. colectiva siguiente';
$post['_qf_default'] = 'victimaColectiva:siguiente';
pasaPestanaFicha(
    $db, array("victimacolectiva",
    "antecedente_comunidad",
    "comunidad_rangoedad",
    "vinculoestado_comunidad",
    "comunidad_filiacion",
    "comunidad_profesion",
    "comunidad_organizacion",
    "p_responsable_agrede_comunidad"
    ),
    $post, 1
);

exit(0);
?>
