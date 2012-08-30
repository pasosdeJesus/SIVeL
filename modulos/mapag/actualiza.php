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
require_once "confv.php";
require_once "misc.php";
require_once "misc_actualiza.php";


$aut_usuario = "";
$db = autentica_usuario($dsn, $aut_usuario, 21);


$act = objeto_tabla('Actualizacionbase');

$idac = 'mg-1';
if (!aplicado($idac)) {

    hace_consulta($db, "INSERT INTO opcion (id_opcion, descripcion, id_mama, nomid) VALUES ('491', 'Mapa en Googlemap', '40', 'opcion?num=300')", false);

    aplicaact(
        $act, $idac, 'Opción del menú para ingresar al mapa'
    );
}

?>
