<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
* Actualiza modulo bélicas
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
$db = autentica_usuario($dsn, $aut_usuario, 21);


$act = objeto_tabla('Actualizacionbase');

$idac = 'sbel-1';
if (!aplicado($idac)) {

    hace_consulta($db, "CREATE SEQUENCE combatiente_seq", false);
    hace_consulta(
        $db, "CREATE TABLE combatiente (
        id INTEGER PRIMARY KEY DEFAULT(nextval('combatiente_seq')),
        nombre VARCHAR(100) NOT NULL,
        alias VARCHAR(100),
        edad INTEGER CHECK (edad IS NULL OR edad >= 0),
        sexo    CHAR(1)  NOT NULL CHECK (sexo = 'S' OR sexo='M' OR sexo='F'),
        id_resagresion INTEGER NOT NULL REFERENCES resagresion,
        id_profesion INTEGER REFERENCES profesion,
        id_rangoedad    INTEGER REFERENCES rangoedad,
        id_filiacion    INTEGER    REFERENCES filiacion,
        id_sectorsocial    INTEGER    REFERENCES sectorsocial,
        id_organizacion    INTEGER REFERENCES organizacion,
        id_vinculoestado INTEGER REFERENCES vinculoestado,
        id_caso    INTEGER REFERENCES caso,
        organizacionarmada INTEGER REFERENCES presponsable
        )", false
    );

    hace_consulta(
        $db, "CREATE TABLE antecedente_combatiente (
            id_antecedente INTEGER REFERENCES antecedente,
            id_combatiente INTEGER REFERENCES combatiente,
            PRIMARY KEY(id_antecedente, id_combatiente)
        )", false
    );

    aplicaact($act, $idac, 'Tablas bélicas');
}

$idac = 'sbel-2';
if (!aplicado($idac)) {
    aplicaact($act, $idac, 'Opciones de bélicas en menu');
}

$idac = 'sbel-3';
if (!aplicado($idac)) {
    aplicaact($act, $idac, 'Roles');
}

$idac = '1.2-ra';
if (!aplicado($idac)) {
    hace_consulta(
        $db,
        "ALTER TABLE p_responsable_agrede_combatiente RENAME TO "
        . "combatiente_presponsable", false
    );

    aplicaact($act, $idac, 'Renombra tablas');
}

$idac = '1.2-bel2';
if (!aplicado($idac)) {
    hace_consulta(
        $db,
        "ALTER TABLE combatiente RENAME COLUMN id_resultado_agresion TO "
        . "id_resagresion", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE combatiente RENAME COLUMN id_rango_edad TO "
        . "id_rangoedad", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE combatiente RENAME COLUMN id_sector_social TO "
        . "id_sectorsocial", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE combatiente RENAME COLUMN id_vinculo_estado TO "
        . "id_vinculoestado", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE combatiente RENAME COLUMN id_organizacion_armada TO "
        . "organizacionarmada", false
    );


    aplicaact($act, $idac, 'Renombra campos');
}


echo "Actualizando indices<br>";
actualiza_indice($db, 'combatiente');




?>
