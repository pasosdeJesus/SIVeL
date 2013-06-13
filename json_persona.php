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
$term = trim(strip_tags($_GET['term'])); //retrieve the search term that autocomplete sends

//trigger_error("term=$term");
$consNomVic =  trim(a_minusculas(sin_tildes($term)));
$consNomvic = preg_replace("/ +/", ":* & ", $consNomVic);
if (strlen($consNomvic) > 0) {
    $consNomvic .= ":*";
}
$where = " to_tsvector('spanish', unaccent(persona.nombres) "
    . " || ' ' || unaccent(persona.apellidos)) @@ "
    . "to_tsquery('spanish', '$consNomvic')";
$qstring = "SELECT trim(nombres || ' ' || apellidos), id || ';' || char_length(nombres) || ';' || char_length(apellidos) FROM persona WHERE $where";
//trigger_error("$qstring");
$res = hace_consulta($db, $qstring);

$row = array();
$resrow = array();
$row_set = array();
while ($res->fetchInto($row)) {
		$resrow['id'] = $row[1];
		$resrow['value'] = stripslashes($row[0]);
		$row_set[] = $resrow;
}

echo json_encode($row_set);

?>
