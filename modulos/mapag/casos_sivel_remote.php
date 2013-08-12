<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Listado de casos restringiendo por filtro
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Luca Urech <lucaurech@yahoo.de> 
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org> 
 *   integrando a SIVeL y exportando a JSON
 * @copyright 2011 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * Acceso: CONSULTA PÚBLICA
 * @link      http://sivel.sf.net
 */

require_once "../../misc.php";
require_once "mapag_misc.php";


$host = determina_host();

// leer filtros desde los parametros GET
$filtro = array(
    'desde' => (!empty($_GET['desde'])) 
        ? var_req_escapa('desde') : "2007-01-01",
	'hasta' => var_req_escapa('hasta'),
	'departamento' => var_req_escapa('departamento'),
	'prresp' => var_req_escapa('prresp'),
	'tvio' => var_req_escapa('tvio'),
);

// generar cadena de solicitud para sivel consulta web (responde XML)
$requestUrl = $host . "consulta_web.php?_qf_consultaWeb_consulta=" 
    . "Consulta&mostrar=relato&m_ubicacion=1&concoordenadas=1"; 
// applicar filtros
$requestUrl .= (!empty($filtro['desde'])) ? 
    "&fini[d]=" . substr($filtro['desde'], -2) 
    . "&fini[M]=" . substr($filtro['desde'], 5, 2) 
    . "&fini[Y]=" . substr($filtro['desde'], 0, 4) : "";
$requestUrl .= (!empty($filtro['hasta'])) ? 
    "&ffin[d]=" . substr($filtro['hasta'], -2) 
    . "&ffin[M]=" . substr($filtro['hasta'], 5, 2) 
    . "&ffin[Y]=" . substr($filtro['hasta'], 0, 4) : "";
$requestUrl .= (!empty($filtro['departamento'])) ? 
    "&id_departamento=" . $filtro['departamento'] : "";
$requestUrl .= (!empty($filtro['prresp'])) ? 
    "&presponsable=" . $filtro['prresp'] : "";
$requestUrl .= (!empty($filtro['tvio'])) ? 
    "&tipo_violencia=" . $filtro['tvio'] : "";
//trigger_error($requestUrl);
if (($ca = file_get_contents($requestUrl)) === false) {
	die('No pudo leerse URL: \'' . $requestUrl . '\'');
}
if (strpos($ca, "Por favor refine su consulta") !== false) {
    die($ca);
}
$casos = array();
// carga datos del archivo XML de Sivel
$xmlSivel = simplexml_load_string($ca);
if ($xmlSivel === false) {
    errores_xml($xmlSivel, $ca);
    die("El url '" . $requestUrl . "' no está cargando");
}

foreach ($xmlSivel->relato as $relato) {
    $id_relato = utf8_decode($relato->id_relato);
    $latitud = (string)$relato->latitud;
    $longitud = (string)$relato->longitud;
    $titulo = (string)$relato->titulo;
    $fecha = (string)$relato->fecha;

    if (!empty($id_relato) && !empty($latitud) && !empty($longitud)) {
        $casos[$id_relato] = array (
            'latitud' => $latitud,
            'longitud' => $longitud,
            'titulo' => $titulo,
            'fecha' => $fecha
        );
    }
}
// generar JSON
header("Content-type: application/json"); 
if (count($casos) > 0) {
    echo json_encode($casos);
} else {
    echo "{}";
}


?>
