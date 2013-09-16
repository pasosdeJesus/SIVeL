<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Funciones utiles
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org> 
 * @copyright 2013 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   $$
 * Acceso: CONSULTA PÚBLICA
 * @link      http://sivel.sf.net
 */


function determina_host() {
    if (isset($_SERVER['HTTP_REFERER'])) {
        $pu = parse_url($_SERVER['HTTP_REFERER']); 
    } else {
        $pu['scheme'] = isset($_SERVER['HTTPS']) &&  $_SERVER['HTTPS'] == 'on' ?
            "https" : "http";
        $pu['host'] = $_SERVER['HTTP_HOST'];
        $pu['path'] = str_replace("modulos/mapag/", "", $_SERVER['REQUEST_URI']);
    }
    $host = $pu['scheme'] . '://' . $pu['host'] . dirname($pu['path']) . "/";
//trigger_error("host=$host");
//trigger_error(var_export($pu, true));
//trigger_error(print_r($_GET,true));

    return $host;
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

function errores_xml($xml, $ca) {
    $lxml = explode("\n", $ca);
    $errors = libxml_get_errors();
    foreach ($errors as $error) {
        echo display_xml_error($error, $lxml);
    }
    libxml_clear_errors();
}

