<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
* Actualiza modulo estrotulos
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2009 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 */

/** Actualiza base de datos después de actualizar fuentes */
require_once "aut.php";
require_once $_SESSION['dirsitio'] . '/conf.php';
require_once "confv.php";
require_once "misc.php";
require_once "DataObjects/Categoria.php";
require_once "misc_actualiza.php";


$aut_usuario = "";
$db = autenticaUsuario($dsn, $aut_usuario, 21);


$act = objeto_tabla('Actualizacion_base');

$idac = 'rot-1';
if (!aplicado($idac)) {

    hace_consulta($db, "INSERT INTO opcion (id_opcion, descripcion, id_mama, nomid) VALUES ('53', 'Individuales con Rotulos de Rep. Cons.', '50', 'opcion?num=100')", false);
    hace_consulta($db, "INSERT INTO opcion (id_opcion, descripcion, id_mama, nomid) VALUES ('54', 'Colectivas con Rotulos de Rep. Cons.', '50', 'opcion?num=101')", false);

    aplicaact(
        $act, $idac, 'Opciones menu'
    );
}






?>
