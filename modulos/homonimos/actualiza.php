<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Actualiza modulo 
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2013 Dominio público. Sin garantías.
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
$db = autentica_usuario($dsn, $aut_usuario, 21);


$act = objeto_tabla('Actualizacionbase');

$idac = 'hom-1';
if (!aplicado($idac)) {
    $r = hace_consulta(
        $db, "CREATE TABLE homonimosim (
            id_persona1 INTEGER REFERENCES persona,
            id_persona2 INTEGER CHECK (id_persona2 > id_persona1) REFERENCES persona,

            PRIMARY KEY(id_persona1, id_persona2)
        );", false
    );
    aplicaact($act, $idac, 'Homonimos');
}

$idac = 'hom-d1';
if (!aplicado($idac)) {
    $na = 'modulos/homonimosim/datos.sql';
    $r = consulta_archivo($db, $na);
    if ($r) {
        aplicaact($act, $idac, 'Datos de Seguimiento Judicial');
    } else {
        echo_esc("No pudo abrir $na");
    }
}


echo "Actualizando indices<br>";


?>
