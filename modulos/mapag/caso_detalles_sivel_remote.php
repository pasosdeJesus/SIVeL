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
 * @version   $$
 * @link      http://sivel.sf.net
 */


// $host = "https://172.16.0.91"; 
$pu = parse_url($_SERVER['HTTP_REFERER']);
$host = $pu['scheme'] . '://' . $pu['host'] . dirname($pu['path']);
$id_caso = (int) $_GET['codigo'];

$requestUrl = $host . "/consulta_web.php?_qf_consultaWeb_consulta=Consulta&mostrar=relato&caso_memo=1&m_victimas=1&m_presponsables=1&m_ubicacion=1&m_tipificacion=1&id_casos=" . $id_caso; //archivo XML de Sivel

// datos para clasificar los casos
/* tal vez lo utilizamos mas tarde
$classificacion[1] = array('rotulo' => 'MUERTOS', 'tipo_violencia' => 'DH', 'clasificacion' => 'VIDA');
$classificacion[2] = array('rotulo' => 'MUERTOS', 'tipo_violencia' => 'DIH', 'clasificacion' => 'VIDA');
$classificacion[3] = array('rotulo' => 'MUERTOS', 'tipo_violencia' => 'VP', 'clasificacion' => 'VIDA');
$classificacion[4] = array('rotulo' => 'TORTURA', 'tipo_violencia' => 'DH', 'clasificacion' => 'INTEGRIDAD');
$classificacion[5] = array('rotulo' => 'HERIDOS', 'tipo_violencia' => 'DH', 'clasificacion' => 'INTEGRIDAD');
$classificacion[6] = array('rotulo' => 'ATENTADOS', 'tipo_violencia' => 'DH', 'clasificacion' => 'INTEGRIDAD');
$classificacion[7] = array('rotulo' => 'AMENAZAS', 'tipo_violencia' => 'DH', 'clasificacion' => 'INTEGRIDAD');
$classificacion[8] = array('rotulo' => 'VIOLENCIA SEXUAL', 'tipo_violencia' => 'DH', 'clasificacion' => 'INTEGRIDAD');
$classificacion[9] = array('rotulo' => 'TORTURA', 'tipo_violencia' => 'DIH', 'clasificacion' => 'INTEGRIDAD');
$classificacion[10] = array('rotulo' => 'HERIDOS', 'tipo_violencia' => 'DIH', 'clasificacion' => 'INTEGRIDAD');
$classificacion[11] = array('rotulo' => 'AMENAZAS', 'tipo_violencia' => 'DIH', 'clasificacion' => 'INTEGRIDAD');
$classificacion[12] = array('rotulo' => 'VIOLENCIA SEXUAL', 'tipo_violencia' => 'DIH', 'clasificacion' => 'INTEGRIDAD');
$classificacion[13] = array('rotulo' => 'TORTURA', 'tipo_violencia' => 'VP', 'clasificacion' => 'INTEGRIDAD');
$classificacion[14] = array('rotulo' => 'HERIDOS', 'tipo_violencia' => 'VP', 'clasificacion' => 'INTEGRIDAD');
$classificacion[15] = array('rotulo' => 'ATENTADOS', 'tipo_violencia' => 'VP', 'clasificacion' => 'INTEGRIDAD');
$classificacion[16] = array('rotulo' => 'AMENAZAS', 'tipo_violencia' => 'VP', 'clasificacion' => 'INTEGRIDAD');
$classificacion[17] = array('rotulo' => 'DESAPARICIÃ“N', 'tipo_violencia' => 'DH', 'clasificacion' => 'LIBERTAD');
$classificacion[18] = array('rotulo' => 'DETENCION ARBITRARIA', 'tipo_violencia' => 'DH', 'clasificacion' => 'LIBERTAD');
$classificacion[19] = array('rotulo' => 'DEPORTACIÃ“N', 'tipo_violencia' => 'DH', 'clasificacion' => 'LIBERTAD');
$classificacion[20] = array('rotulo' => 'RECLUTAMIENTO DE MENORES', 'tipo_violencia' => 'DIH', 'clasificacion' => 'LIBERTAD');
$classificacion[21] = array('rotulo' => 'TOMA DE REHENES', 'tipo_violencia' => 'DIH', 'clasificacion' => 'LIBERTAD');
$classificacion[22] = array('rotulo' => 'ESCUDO', 'tipo_violencia' => 'DIH', 'clasificacion' => 'LIBERTAD');
$classificacion[23] = array('rotulo' => 'RAPTO', 'tipo_violencia' => 'VP', 'clasificacion' => 'LIBERTAD');
$classificacion[24] = array('rotulo' => 'SECUESTRO', 'tipo_violencia' => 'VP', 'clasificacion' => 'LIBERTAD');
$classificacion[25] = array('rotulo' => 'COLECTIVO CONFINADO', 'tipo_violencia' => 'DIH', 'clasificacion' => 'INTEGRIDAD');
*/

// generar documento XML
header("Content-type: text/xml"); 
$dom = new DOMDocument("1.0");

if (!empty($id_caso) && $id_caso != 0) {

    // carga datos del archivo XML de Sivel
    $xmlSivel = simplexml_load_string(file_get_contents($requestUrl)) or die("url '" . $requestUrl . "' not loading");
    
    // todo bien, crear documento xml
    $node = $dom->createElement("casos");
    $parnode = $dom->appendChild($node);
    $node2 = $dom->createElement("caso");
    $node2->setAttribute("id", $id_caso);
    $subnode = $parnode->appendChild($node2);
    
    $output['titulo'] = $dom->createElement('titulo', $xmlSivel->relato->titulo);
    $output['hechos'] = $dom->createElement('hechos', $xmlSivel->relato->hechos);
    $output['fecha'] = $dom->createElement('fecha', $xmlSivel->relato->fecha);
    $output['hora'] = $dom->createElement('hora', $xmlSivel->relato->hora);
    $output['departamento'] = $dom->createElement('departamento', $xmlSivel->relato->departamento);
    $output['municipio'] = $dom->createElement('municipio', $xmlSivel->relato->municipio);
    $output['centro_poblado'] = $dom->createElement('centro_poblado', $xmlSivel->relato->centro_poblado);
    
    foreach ($output as $value) {
        $subnode->appendChild($value);
    }
    
    $prresp = $dom->createElement("presuntos_responsables");
    $prrespnode = $subnode->appendChild($prresp);
    foreach ($xmlSivel->relato->grupo as $grupo) {
        if (!empty($grupo->nombre_grupo)) {
        $outputGrupo = $dom->createElement('presunto_responsable', $grupo->nombre_grupo);
        $outputGrupo->setAttribute("id", utf8_encode($grupo->id_grupo));
        $prrespnode->appendChild($outputGrupo);
        }
    }
    
    $victimas = $dom->createElement("victimas");
    $victimasnode = $subnode->appendChild($victimas);
    foreach ($xmlSivel->relato->persona as $persona) {
        $id_persona = (int) $persona->id_persona;
        $outputVictima = $dom->createElement('persona', $persona->nombre);
        
        $outputVictima->setAttribute("id", utf8_encode($persona->id_persona));
        $outputVictima->setAttribute("sexo", utf8_encode($persona->sexo));
        $victimasnode->appendChild($outputVictima);
    }
            
}

echo $dom->saveXML();

?>
