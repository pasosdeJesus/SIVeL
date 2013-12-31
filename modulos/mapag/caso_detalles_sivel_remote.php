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
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2011 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * Acceso: CONSULTA PÚBLICA
 * @link      http://sivel.sf.net
 */

require_once "../../misc.php";
require_once "mapag_misc.php";

$host = determina_host();

if (!isset($_GET['codigo'])) {
    header("Content-type: application/json");
    echo "{}";
    return;
}
$id_caso = (int) $_GET['codigo'];

$requestUrl = $host . "/consulta_web.php?_qf_consultaWeb_consulta=Consulta"
    . "&mostrar=relato&caso_memo=1&m_victimas=1&m_presponsables=1"
    . "&m_ubicacion=1&m_tipificacion=1&id_casos=" . $id_caso;

// generar documento JSON
if (!empty($id_caso) && $id_caso != 0) {
    // carga datos del archivo XML de Sivel
    $ca = file_get_contents($requestUrl);
    $xmlSivel = simplexml_load_string($ca);
    if ($xmlSivel === false) {
        errores_xml($xmlSivel, $ca);
        die("No pudo cargarse del url '" . $requestUrl . "'");
    }
    // todo bien, crear documento json
    $presp = array();
    foreach ($xmlSivel->relato->grupo as $grupo) {
        if (!empty($grupo->nombre_grupo)) {
            $presp[(string)$grupo->id_grupo] = (string)$grupo->nombre_grupo;
        }
    }
    $victimas = array();
    foreach ($xmlSivel->relato->persona as $persona) {
        if (!empty($persona->nombre)) {
            $victimas[(string)$persona->id_persona]
                = trim(
                    trim((string)$persona->nombre) . " "
                    . trim((string)$persona->apellido)
                );
        }
    }
    $rta = array();
    $rta["caso"] = array(
        'id' => $id_caso,
        'titulo' => (string)$xmlSivel->relato->titulo,
        'hechos' => (string)$xmlSivel->relato->hechos,
        'fecha' => (string)$xmlSivel->relato->fecha,
        'hora' => (string)$xmlSivel->relato->hora,
        'departamento' => (string)$xmlSivel->relato->departamento,
        'municipio' => (string)$xmlSivel->relato->municipio,
        'centro_poblado' => (string)$xmlSivel->relato->centro_poblado,
        'presponsables' => $presp,
        'victimas' => $victimas,
    );
}

header("Content-type: application/json");
echo json_encode($rta);

?>
