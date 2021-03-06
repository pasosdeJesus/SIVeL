<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Actualiza modulo seguimiento judicial
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

$idac = 'sjud-1';
if (!aplicado($idac)) {
    $r = hace_consulta($db, "CREATE SEQUENCE tproceso_seq;", false);
    $r = hace_consulta(
        $db, "CREATE TABLE tproceso (
            id  INTEGER PRIMARY KEY DEFAULT (nextval('tproceso_seq')),
            nombre VARCHAR(50) NOT NULL,
            observaciones VARCHAR(200)
        ); ", false
    );
    $r = hace_consulta($db, "CREATE SEQUENCE despacho_seq; ", false);
    $r = hace_consulta(
        $db, "CREATE TABLE despacho (
            id  INTEGER PRIMARY KEY DEFAULT (nextval('despacho_seq')),
            id_tproceso INTEGER NOT NULL REFERENCES tproceso,
            nombre VARCHAR(150) NOT NULL,
            observaciones VARCHAR(500)
        ); ", false
    );
    $r = hace_consulta($db, "CREATE SEQUENCE etapa_seq; ", false);
    $r = hace_consulta(
        $db, "CREATE TABLE etapa (
            id  INTEGER PRIMARY KEY DEFAULT (nextval('etapa_seq')),
            id_tproceso INTEGER NOT NULL REFERENCES tproceso,
            nombre VARCHAR(50) NOT NULL,
            observaciones VARCHAR(200)
        ); ", false
    );
    $r = hace_consulta($db, "CREATE SEQUENCE proceso_seq; ", false);
    $r = hace_consulta(
        $db, "CREATE TABLE proceso (
            id  INTEGER PRIMARY KEY DEFAULT (nextval('proceso_seq')),
            id_caso INTEGER REFERENCES caso NOT NULL,
            id_tproceso INTEGER NOT NULL REFERENCES tproceso,
            id_etapa INTEGER NOT NULL REFERENCES etapa,
            proximafecha DATE,
            demandante  VARCHAR(100),
            demandado VARCHAR(100),
            poderdante VARCHAR(100),
            telefono VARCHAR(50),
            observaciones VARCHAR(500)
        ); ", false
    );
    $r = hace_consulta($db, "CREATE SEQUENCE taccion_seq; ", false);
    $r = hace_consulta(
        $db, "CREATE TABLE taccion (
            id  INTEGER PRIMARY KEY DEFAULT (nextval('taccion_seq')),
            nombre  VARCHAR(50) NOT NULL,
            observaciones VARCHAR(200)
        ); ", false
    );
    $r = hace_consulta(
        $db, "CREATE SEQUENCE accion_seq;", false
    );
    $r = hace_consulta(
        $db, "CREATE TABLE accion (
            id      INTEGER PRIMARY KEY DEFAULT (nextval('accion_seq')),
            id_proceso INTEGER NOT NULL REFERENCES proceso,
            id_taccion INTEGER NOT NULL REFERENCES taccion,
            id_despacho INTEGER NOT NULL REFERENCES despacho,
            fecha DATE NOT NULL,
            numero_radicado VARCHAR(50),
            observaciones_accion    VARCHAR(4000),
            respondido  BOOLEAN
        ); "
    );

    aplicaact($act, $idac, 'Seguimiento Judicial');
}

$idac = 'sjud-d1';
if (!aplicado($idac)) {
    $na = 'modulos/segjudicial/datos.sql';
    $r = consulta_archivo($db, $na);
    if ($r) {
        aplicaact($act, $idac, 'Datos de Seguimiento Judicial');
    } else {
        echo_esc("No pudo abrir $na");
    }
}


$idac = 'sj-fe';
if (!aplicado($idac)) {
    foreach (array('taccion', 'etapa', 'despacho', 'tproceso') as $nt) {
        agrega_fechas($db, $nt);
    }

    aplicaact($act, $idac, 'Fecha en tablas básicas');
}


$idac = '1.2-rs';
if (!aplicado($idac)) {
    hace_consulta(
        $db,
        "ALTER TABLE tipo_accion RENAME TO taccion", false
    );
    hace_consulta(
        $db,
        "ALTER SEQUENCE tipo_accion_seq RENAME TO taccion_seq ", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE tipo_proceso RENAME TO tproceso", false
    );
    hace_consulta(
        $db,
        "ALTER SEQUENCE tipo_proceso_seq RENAME TO tproceso_seq ", false
    );

    aplicaact($act, $idac, 'Renombra tablas');
}

$idac = '1.2-sjrc';
if (!aplicado($idac)) {
    hace_consulta(
        $db,
        "ALTER TABLE despacho RENAME COLUMN id_tipo TO id_tproceso", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE etapa RENAME COLUMN id_tipo TO id_tproceso", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE proceso RENAME COLUMN id_tipo TO id_tproceso", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE accion RENAME COLUMN id_tipo_accion TO id_taccion", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE accion RENAME COLUMN observaciones_accion 
        TO observacionesaccion", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE accion RENAME COLUMN numero_radicado TO numeroradicado", 
        false
    );

    aplicaact($act, $idac, 'Renombra campos');
}

$idac = '1.2-jl';
if (!aplicado($idac)) {
    foreach (array('tproceso', 'despacho', 'etapa', 'taccion', ) as $t) {
        hace_consulta(
            $db, "ALTER TABLE $t ALTER nombre "
            . " TYPE VARCHAR(500) COLLATE es_co_utf_8", false
        );
    }
    aplicaact($act, $idac, 'Localización');
}

echo "Actualizando indices<br>";
actualiza_indice($db, 'tproceso');
actualiza_indice($db, 'despacho');
actualiza_indice($db, 'etapa');
actualiza_indice($db, 'proceso');
actualiza_indice($db, 'taccion');
actualiza_indice($db, 'accion');




?>
