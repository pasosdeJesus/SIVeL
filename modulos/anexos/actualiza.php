<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
* Actualiza modulo anexos
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2009 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   $$
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
$db = autenticaUsuario($dsn, $accno, $aut_usuario, 21);


/* ==========================  OJO ==================================
 * Al agregar cambios a la estructura o datos de la base de datos en archivos
 * sql usados en instalaciones frescas, también poner el INSERT respectivo
 * en actualizacion_base en datos.sql
 * ==================================================================
 */


$act = objeto_tabla('Actualizacion_base');

$idac = 'anexo-1';
if (!aplicado($idac)) {
    $r = hace_consulta($db, "CREATE SEQUENCE anexo_seq;", false);
    $r = hace_consulta(
        $db, "CREATE TABLE anexo (
        id      INTEGER PRIMARY KEY DEFAULT (nextval('anexo_seq')),
        id_caso INTEGER REFERENCES caso NOT NULL,
        fecha   DATE NOT NULL,
        descripcion     VARCHAR(1500) NOT NULL,
        archivo VARCHAR(255) NOT NULL
    );", false
);

    aplicaact($act, $idac, 'Anexos');
}

$idac = 'anexo-1b';
if (!aplicado($idac)) {
    $r = hace_consulta(
        $db, "ALTER TABLE anexo ADD COLUMN fecha_prensa DATE",
        false
    );
    $r = hace_consulta(
        $db, "ALTER TABLE anexo ADD COLUMN id_prensa INTEGER " .
        "REFERENCES prensa", false
    );
    $r = hace_consulta(
        $db, "ALTER TABLE anexo ADD COLUMN id_fuente_directa " .
        "INTEGER REFERENCES fuente_directa", false
    );

    aplicaact($act, $idac, 'Anexos relacionados con fuentes');
}


echo "Actualizando indices<br>";
actualiza_indice($db, 'anexo');



?>
