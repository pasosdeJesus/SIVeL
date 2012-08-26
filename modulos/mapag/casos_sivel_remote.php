<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Detalls de un caso
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Luca Urech <lucaurech@yahoo.de>
 * @copyright 2011 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * Acceso: CONSULTA PÚBLICA
 * @link      http://sivel.sf.net
 */

require_once "../../misc.php";

//$host = "https://172.16.0.91";
$pu = parse_url($_SERVER['HTTP_REFERER']);
$host = $pu['scheme'] . '://' . $pu['host'] . dirname($pu['path']);
//trigger_error(var_export($pu, true));

trigger_error(print_r($_GET,true));

// leer filtros desde los parametros GET
$filtro = array(
    'desde' => (!empty($_GET['desde']))
        ? var_req_escapa('desde') : "2007-01-01",
    'hasta' => var_req_escapa('hasta'),
    'departamento' => var_req_escapa('departamento'),
    'prresp' => var_req_escapa('prresp'),
    'tvio' => var_req_escapa('tvio'),
);


// generar cadena de solicitud para sivel consulta web
    $requestUrl = $host . "/consulta_web.php?_qf_consultaWeb_consulta=Consulta&mostrar=relato&m_ubicacion=1&concoordenadas=1"; // consulta del SIVeL que genera XML
    // applicar filtros
    $requestUrl .= (!empty($filtro['desde'])) ? "&fini[d]=" . substr($filtro['desde'], -2) . "&fini[M]=" . substr($filtro['desde'], 5, 2) . "&fini[Y]=" . substr($filtro['desde'], 0, 4) : "";
    $requestUrl .= (!empty($filtro['hasta'])) ? "&ffin[d]=" . substr($filtro['hasta'], -2) . "&ffin[M]=" . substr($filtro['hasta'], 5, 2) . "&ffin[Y]=" . substr($filtro['hasta'], 0, 4) : "";
    $requestUrl .= (!empty($filtro['departamento'])) ? "&id_departamento=" . $filtro['departamento'] : "";
    $requestUrl .= (!empty($filtro['prresp'])) ? "&presponsable=" . $filtro['prresp'] : "";
    $requestUrl .= (!empty($filtro['tvio'])) ? "&tviolencia=" . $filtro['tvio'] : "";

    trigger_error($requestUrl);

    if (($ca = file_get_contents($requestUrl)) === false) {
        die('Hey, No pudo leerse: \'' . $requestUrl . '\'');
    }


$casos = array();

// carga datos del archivo XML de Sivel
$xmlSivel = simplexml_load_string($ca)
    or die("url '" . $requestUrl . "' not loading");
//var_dump($xmlSivel);die("x");

header("Content-type: text/xml");
foreach ($xmlSivel->relato as $relato) {

    $id_relato = $relato->id_relato;
    $latitud = $relato->latitud;
    $longitud = $relato->longitud;
    $titulo = $relato->titulo;
    $fecha = $relato->fecha;


    if (!empty($id_relato) && !empty($latitud) && !empty($longitud)) {

        $casos[] = array (
            'id_relato' => $id_relato,
            'latitud' => $latitud,
            'longitud' => $longitud,
            'titulo' => $titulo,
            'fecha' => $fecha
        );

    }
}

// generar documento XML

$dom = new DOMDocument("1.0");
$node = $dom->createElement("casos");
$parnode = $dom->appendChild($node);

foreach ($casos as $key => $value) {
    $node = $dom->createElement("caso");
    $newnode = $parnode->appendChild($node);
    $newnode->setAttribute("id_relato", utf8_encode($value['id_relato']));
    $newnode->setAttribute("latitud", utf8_encode($value['latitud']));
    $newnode->setAttribute("longitud", utf8_encode($value['longitud']));
    $newnode->setAttribute("titulo", utf8_encode($value['titulo']));
    $newnode->setAttribute("fecha", utf8_encode($value['fecha']));
}

echo $dom->saveXML();


?>
