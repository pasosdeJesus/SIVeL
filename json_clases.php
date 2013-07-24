<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Clases de un municipios en JSON
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara  Patiño <vtamara@pasosdeJesus.org>
 * @copyright 2013 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * Acceso: CONSULTA PÚBLICA
 * @link      http://sivel.sf.net
 */


require_once "aut.php";
require_once $_SESSION['dirsitio'] . "/conf.php";
require_once 'misc.php';

$arrclases = array();
if (isset($_GET['id_departamento'])
    && ($iddep = (int)$_GET['id_departamento']) > 0
    && isset($_GET['id_municipio'])
    && ($idmun = (int)$_GET['id_municipio']) > 0
) {
        //trigger_error("dep=$iddep, mun=$idmun");
        $d = objeto_tabla('clase');
        $db =& $d->getDatabaseConnection();
        $r = hace_consulta($db, 
            "SELECT id, nombre FROM clase WHERE "
            . " id_departamento='$iddep' "
            . " AND id_municipio ='$idmun' "
            . " ORDER BY nombre"
        );
        if (PEAR::isError($r)) {
            trigger_error($r->getMessage . ": " . $r->getUserInfo);
            die("x");
        }
        $row = array();
        while (!PEAR::isError($r) && $r->fetchInto($row)){
            $arrclases[] = array(
                'id' => (int)$row[0],
                'nombre' => $row[1]
            );
        }
}

echo json_encode($arrclases);

//trigger_error("json_municipios terminó");

?>
