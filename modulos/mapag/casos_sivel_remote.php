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
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org> integrando a SIVeL y exportando a JSON
 * @copyright 2011 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   $Id: casos_sivel_remote.php,v 1.1.2.10 2013/04/15 14:10:40 vtamara Exp $
 * Acceso: CONSULTA PÚBLICA
 * @link      http://sivel.sf.net
 */

require_once "../../misc.php";

if (isset($_SERVER['HTTP_REFERER'])) {
    $pu = parse_url($_SERVER['HTTP_REFERER']); 
} else {
    $pu['scheme'] = isset($_SERVER['HTTPS']) &&  $_SERVER['HTTPS'] == 'on' ?
        "https" : "http";
    $pu['host'] = $_SERVER['HTTP_HOST'];
    $pu['path'] = str_replace("modulos/mapag/", "", $_SERVER['REQUEST_URI']);
}
$host = $pu['scheme'] . '://' . $pu['host'] . dirname($pu['path']);
//trigger_error("host=$host");
//trigger_error(var_export($pu, true));
//trigger_error(print_r($_GET,true));

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
trigger_error($requestUrl);

if (($ca = file_get_contents($requestUrl)) === false) {
	die('No pudo leerse URL: \'' . $requestUrl . '\'');
}
if (strpos($ca, "Por favor refine su consulta") !== false) {
    die($ca);
}

$casos = array();
// carga datos del archivo XML de Sivel
$xmlSivel = simplexml_load_string($ca);

if (!$xmlSivel) {
    $xml = explode("\n", $ca);
    $errors = libxml_get_errors();
    foreach ($errors as $error) {
        echo display_xml_error($error, $xml);
    }
    libxml_clear_errors();
    die("El url '" . $requestUrl . "' no está cargando");
}


function display_xml_error($error, $xml)
{
    $return  = $xml[$error->line - 1] . "\n";
    $return .= str_repeat('-', $error->column) . "^\n";

    switch ($error->level) {
    case LIBXML_ERR_WARNING:
        $return .= "Warning $error->code: ";
        break;
    case LIBXML_ERR_ERROR:
        $return .= "Error $error->code: ";
        break;
    case LIBXML_ERR_FATAL:
        $return .= "Fatal Error $error->code: ";
        break;
    }

    $return .= trim($error->message) .
        "\n  Line: $error->line" .
        "\n  Column: $error->column";

    if ($error->file) {
        $return .= "\n  File: $error->file";
    }

    return "$return\n\n--------------------------------------------\n\n";
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
