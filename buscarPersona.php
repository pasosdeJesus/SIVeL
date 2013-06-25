<?php
//  vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Permite elegir una persona
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2007 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 */

require_once 'aut.php';
require_once $_SESSION['dirsitio'] . "/conf.php";
require_once "DataObjects/Persona.php";
require_once 'misc.php';
require_once 'misc_importa.php';

/**
 * Muestra lista de personas en base
 *
 * @return void
 */
function muestra()
{
    global $dsn, $accno;

    $aut_usuario = "";
    $db = autentica_usuario($dsn, $aut_usuario, 0);
    $nombres = trim(utf8_decode(var_req_escapa('nombres', $db, 100)));
    $apellidos  = trim(utf8_decode(var_req_escapa('apellidos', $db, 100)));
    $rol = var_req_escapa('rol', $db, 32);

    $pNomvic = trim($nombres . " " . $apellidos);
    //echo "OJO nombres=$nombres, apellidos=$apellidos";
    $x =&  objeto_tabla('persona');
    $consNomVic =  trim(a_minusculas(sin_tildes($pNomvic)));
    $consNomvic = preg_replace("/ +/", " & ", $consNomVic);
    $where = " to_tsvector('spanish', unaccent(persona.nombres) "
        . " || ' ' || unaccent(persona.apellidos)) @@ "
        . "to_tsquery('spanish', '$consNomvic')";
    $q = "SELECT id, nombres, apellidos, anionac, mesnac, dianac, "
        . "sexo, id_departamento, id_municipio, id_clase, "
        . "tipodocumento, numerodocumento " 
        . " FROM persona " 
        . " WHERE " . $where
        . " ORDER by nombres, apellidos "; 


    //echo "OJO q=\"$q\"<br>";
    $result = hace_consulta($db, $q);

    $row = array();
    echo "<html><head>";
    echo "  <title>Personas</title>";
    echo "  <meta charset=\utf-8\">";
    echo "</head>";
    echo "<body>";
    echo '<script language="JavaScript" src="sivel.js" type="text/javascript">'
        . '</script>';
    echo_esc("Coincidencias: " . $result->numRows());
    echo "<hr>";
    //echo "rol=$rol";
    echo "<table>";
    echo "<tr>" .
        "<th>Nombres y apellidos</th><th>V&iacute;ctima en</th><th>Familiar en</th>" .
        "</tr>";

    while ($result->fetchInto($row)) {
        echo "<tr>";
        echo '<td><a href="#" onClick="enviar_persona(\''
            . htmlentities($rol, ENT_COMPAT, 'UTF-8') . '\'';
        $html_sep = ", ";
        foreach ($row as $v) {
            echo $html_sep . "'" . htmlentities($v, ENT_COMPAT, 'UTF-8') . "'";
            $html_sep = ", ";
        }
        echo ')">';
        echo htmlentities($row[1] . " ".$row[2], ENT_COMPAT, 'UTF-8') . "</a></td>";
        $html_comovic = "";
        $html_comofam = "";
        enlaces_casos_persona_html(
            &$db, 0, $row[0], &$html_comovic, &$html_comofam
        );
        echo "<td align='center'>" . $html_comovic . "</td>";
        echo "<td align='center'>" . $html_comofam . "</td>";

        echo "</tr>\n";
    }
    echo "</table>";
    echo "</body></html>";
}

muestra();
?>
