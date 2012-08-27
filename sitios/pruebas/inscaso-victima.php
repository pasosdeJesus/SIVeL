<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:

/**
* Inserción de víctima de un caso
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
 * Inserción de víctima de un caso
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";

/*** VÍCTIMA ***/

$post = array();
$post['id_departamento'] = '1';
$post['id_municipio'] = '1';
$post['id_clase'] = '1';
$post['tipodocumento'] = 'CC';
$post['numerodocumento'] = '1';
$post['nombres'] = 'nombres';
$post['apellidos'] = 'apellidos';
$post['anionac'] = '2006';
$post['mesnac'] = '1';
$post['dianac'] = '1';
$post['sexo'] = 'F';
$post['hijos'] = '3';
$post['id_profesion'] = '1';
$post['id_rangoedad'] = '1';
$post['id_filiacion'] = '1';
$post['id_sectorsocial'] = '1';
$post['id_organizacion'] = '1';
$post['organizacionarmada'] = '1';
$post['id_antecedente']['0'] = '1';
$post['id_vinculoestado'] = '1';
$post['anotaciones'] = 'anotaciones';
$post['_qf_victimaIndividual_siguienteMultiple'] = 'Victima siguiente';
$post['_qf_default'] = 'victimaIndividual:siguiente';
pasaPestanaFicha(
    $db, array("persona",
    "victima", "antecedente_victima"
    ),
    $post, 1
);


exit(0);
?>
