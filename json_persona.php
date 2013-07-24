<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Retorna listado de nombres de personas en base de datos en formato JSON,
 * útil para búsquedas
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2010 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 */

require_once 'misc.php';
require_once 'misc_importa.php';
require_once 'aut.php';
require_once $_SESSION['dirsitio'] . '/conf.php';

/*$aut_usuario = "";
$db = autentica_usuario($dsn, $aut_usuario, 0);
Debe autenticarse en la función muestra del modulo */

$persona = objeto_tabla('persona');
$db = $persona->getDatabaseConnection();

if (!isset($_GET['term'])) {
    encabezado_envia();
    die("Error: Falta variable <i>term</i> con nombre buscado");
}
//retrieve the search term that autocomplete sends
$term = trim(strip_tags($_GET['term'])); 


//trigger_error("term=$term");
$consNomVic =  trim(a_minusculas(sin_tildes($term)));
$consNomvic = preg_replace("/ +/", ":* & ", $consNomVic);
if (strlen($consNomvic) > 0) {
    $consNomvic .= ":*";
}
$where = " to_tsvector('spanish', unaccent(persona.nombres) "
    . " || ' ' || unaccent(persona.apellidos) "
    . " || ' ' || COALESCE(persona.numerodocumento::TEXT, '')) @@ "
    . "to_tsquery('spanish', '$consNomvic')";

/*$where = " to_tsvector('spanish', unaccent(persona.nombres) "
    . " || ' ' || unaccent(persona.apellidos) ) @@ "
    . "to_tsquery('spanish', '$consNomvic')"; */

$penc = isset($GLOBALS['persona_en_caso']) ? $GLOBALS['persona_en_caso'] : '';
$partes = array(
    'nombres', 
    'apellidos', 
    'COALESCE(numerodocumento::TEXT, \'\')',
/*    'ARRAY_TO_STRING(ARRAY('
        . ' SELECT id_caso FROM victima WHERE victima.id_persona=id '
        . ' UNION SELECT id_caso FROM persona_trelacion, victima '
        . ' WHERE persona1 = id_persona AND persona2 = id '
        . $penc 
        . '), \', \')' */
);
$s = "";
$l = " id ";
$seps = "";
$sepl = " || ';' || ";
foreach($partes as $p) {
    $s .= $seps . $p;
    $l .= $sepl . "char_length($p)";
    $seps = " || ' ' || ";
}
$qstring = "SELECT TRIM($s), $l FROM persona WHERE $where ORDER BY 1";

//trigger_error("$qstring");
$res = hace_consulta($db, $qstring);

$row = array();
$resrow = array();
$row_set = array();
while ($res->fetchInto($row)) {
    $idcaso = isset($_SESSION['basicos_id']) ? $_SESSION['basicos_id'] : null;
    $pid = explode(";", $row[1]);
    $idp = $pid[0];
    $cf = $cv = "";
    enlaces_casos_persona_html($db, $idcaso, $idp, $cv, $cf);
    if ($cv != "" && $cf != "") {
        $e = $cv . ", " . $cf;
    } else {
        $e = $cv . $cf;
    }
    $resrow['id'] = $row[1];
    $resrow['value'] = stripslashes($row[0]);
    $resrow['urls'] = $e;
    $row_set[] = $resrow;
}

echo json_encode($row_set);

?>
