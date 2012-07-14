<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Municipios de un departamento
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


require_once "../../aut.php";
require_once $_SESSION['dirsitio'] . "/conf.php";
require_once 'misc.php';

$id_departamento = (int)$_GET['id_departamento'];

if ($id_departamento != 0) {
	
    trigger_error("dep=" . $id_departamento);
    $municipios = array();
    $d = objeto_tabla('municipio');
    $d->id_departamento = $id_departamento;
    $d->find();
    trigger_error(print_r($d, true));
    while ($d->fetch()){
        trigger_error("OJO 1");
        $municipios[$d->id_departamento][] = array(
            'id_municipio' => $d->id,
            'name' => $d->nombre
        );
        trigger_error("dep=" . $d->id_departamento . ", mun=" 
            . $d->id . ", nombre=" . $d->nombre);
    }
    trigger_error(print_r($d, true));
	foreach ($municipios[$id_departamento] as $municipio) {
		$municipiosContainer[] = array(
				'id' => (int)$municipio['id_municipio'],
				'name' => utf8_encode($municipio['name'])
			);
	}
	
	echo json_encode($municipiosContainer);
}

?>
