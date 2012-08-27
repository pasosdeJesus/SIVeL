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
$db = autenticaUsuario($dsn, $aut_usuario, 21);


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
        id_resultado_agresion INTEGER NOT NULL REFERENCES resultado_agresion,
        id_profesion INTEGER REFERENCES profesion,
        id_rango_edad    INTEGER REFERENCES rango_edad,
        id_filiacion    INTEGER    REFERENCES filiacion,
        id_sector_social    INTEGER    REFERENCES sector_social,
        id_organizacion    INTEGER REFERENCES organizacion,
        id_vinculo_estado INTEGER REFERENCES vinculoestado,
        id_caso    INTEGER REFERENCES caso,
        id_organizacion_armada INTEGER REFERENCES presponsable
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

    hace_consulta($db, "INSERT INTO opcion (id_opcion, descripcion, id_mama, nomid) VALUES ('52', 'V. Combatientes', '50', 'opcion?num=200')", false);
    hace_consulta($db, "INSERT INTO opcion VALUES ('46', 'Revista Bélicas', 40, 'consulta_web?mostrar=revista&categoria=belicas&sincampos=caso_id')", false);
    hace_consulta($db, "INSERT INTO opcion VALUES ('47', 'Revista Memo Bélicas', 40, 'consulta_web?mostrar=revista&categoria=belicas&sincampos=caso_id,m_victimas,m_presponsables,m_tipificacion,m_fuentes')", false);
    hace_consulta($db, "INSERT INTO opcion VALUES ('48', 'Revista NO Bélicas', 40, 'consulta_web?mostrar=revista&categoria=nobelicas&sincampos=caso_id')", false);
    hace_consulta($db, "INSERT INTO opcion VALUES ('49', 'Revista Memo NO Bélicas', 40, 'consulta_web?mostrar=revista&categoria=nobelicas&sincampos=caso_id,m_victimas, m_presponsables, m_tipificacion, m_fuentes')", false);
    hace_consulta($db, "INSERT INTO opcion (id_opcion, descripcion, id_mama, nomid) VALUES ('54', 'Colectivas con Rotulos de Rep. Cons.', '50', 'opcion?num=101')", false);

    aplicaact($act, $idac, 'Opciones de bélicas en menu');
}

$idac = 'sbel-3';
if (!aplicado($idac)) {

    hace_consulta($db, "INSERT INTO opcion_rol VALUES ('46', '1')", false);
    hace_consulta($db, "INSERT INTO opcion_rol VALUES ('46', '2')", false);
    hace_consulta($db, "INSERT INTO opcion_rol VALUES ('47', '1')", false);
    hace_consulta($db, "INSERT INTO opcion_rol VALUES ('47', '2')", false);
    hace_consulta($db, "INSERT INTO opcion_rol VALUES ('48', '1')", false);
    hace_consulta($db, "INSERT INTO opcion_rol VALUES ('48', '2')", false);
    hace_consulta($db, "INSERT INTO opcion_rol VALUES ('49', '1')", false);
    hace_consulta($db, "INSERT INTO opcion_rol VALUES ('49', '2')", false);

    aplicaact($act, $idac, 'Roles');
}


echo "Actualizando indices<br>";
actualiza_indice($db, 'combatiente');




?>
