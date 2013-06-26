<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Actualiza modulo desplazamiento
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

$idac = 'des-e1';
if (!aplicado($idac)) {
    $r = consulta_archivo($db, 'modulos/desplazamiento/estructura.sql');
    if ($r) {
        aplicaact($act, $idac, 'Estructura desplazamiento');
    } else {
        echo_esc("No pudo abrir $na");
    }
}

$idac = 'des-d1';
if (!aplicado($idac)) {
    $r = consulta_archivo($db, 'modulos/desplazamiento/datos.sql');
    if ($r) {
        aplicaact($act, $idac, 'Datos Desplazamiento');
    } else {
        echo_esc("No pudo abrir $na");
    }
}


$idac = 'des-ext';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "UPDATE desplazamiento SET departamentodecl = 10000
        WHERE departamentodecl = 0", false
    );
    hace_consulta(
        $db, "DELETE FROM municipio WHERE id_departamento = '0'", false
    );
    hace_consulta(
        $db, "DELETE FROM departamento WHERE id = '0'", false
    );
    aplicaact($act, $idac, 'Cambio de código EXTERIOR de 0 a 10000');
}

echo "Actualizando indices<br>";
actualiza_indice($db, 'clasifdesp');
actualiza_indice($db, 'tipodesp');
actualiza_indice($db, 'declaroante');
actualiza_indice($db, 'inclusion');
actualiza_indice($db, 'acreditacion');
actualiza_indice($db, 'modalidadtierra');



?>
