<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:

/**
 * Prueba Autenticación
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2010 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
*/

/**
 * Prueba autenticación
 */

if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}

require_once "ambiente.php";
require_once "aut.php";
require_once $_SESSION['dirsitio'] . '/conf.php';
require_once "misc.php";
require_once "misc_caso.php";
require_once "DB/DataObject/FormBuilder.php";

$aut_usuario = "";
autentica_usuario($dsn, $aut_usuario, 11);

echo "Si paso autenticausuario\n";

/* Verificando */
exit(0);
?>
