<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
* Actualiza modulo mapag
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2011 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 */

/** Actualiza base de datos después de actualizar fuentes */
require_once "aut.php";
require_once $_SESSION['dirsitio'] . '/conf.php';

$aut_usuario = "";
$db = autentica_usuario($dsn, $aut_usuario, 21);

require_once $_SESSION['dirsitio'] . '/conf_int.php';
require_once "confv.php";
require_once "misc.php";
require_once "misc_actualiza.php";




$act = objeto_tabla('Actualizacionbase');


?>
