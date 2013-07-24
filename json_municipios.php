<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Municipios de un departamento en JSON
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Luca Urech <lucaurech@yahoo.de> y Vladimir Támara  Patiño <vtamara@pasosdeJesus.org>
 * @copyright 2011 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * Acceso: CONSULTA PÚBLICA
 * @link      http://sivel.sf.net
 */


require_once "aut.php";
require_once $_SESSION['dirsitio'] . "/conf.php";
require_once 'misc.php';

$arrmunicipios = array();
if (isset($_GET['id_departamento'])
    && ($iddep = (int)$_GET['id_departamento']) > 0) {
        //trigger_error("dep=" . $iddep);
        $municipios = array();
        $d = objeto_tabla('municipio');
        $db =& $d->getDatabaseConnection();
        $r = hace_consulta($db, 
            "SELECT id, nombre FROM municipio WHERE id_departamento='$iddep' "
            . " ORDER BY nombre"
        );
        $row = array();
        while (!PEAR::isError($r) && $r->fetchInto($row)){
            $arrmunicipios[] = array(
                'id' => (int)$row[0],
                'nombre' => $row[1]
            );
        }
}

echo json_encode($arrmunicipios);

//trigger_error("json_municipios terminó");

?>
