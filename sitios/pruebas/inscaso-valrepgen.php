<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:

/**
* Inserción de evaluación y paso a reporte general en un caso
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2007 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: inscaso-valrepgen.php,v 1.4.2.2 2011/10/18 16:05:05 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Inserción de evaluación y paso a reporte general en un caso
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";
require_once "PagBasicos.php";

/*** Validación y Reporte General ***/

ReporteGeneral::reporte(1);

exit(0);
?>
