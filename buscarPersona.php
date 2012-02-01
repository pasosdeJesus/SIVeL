<?php
//  vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
/**
 * Permite elegir una persona
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2007 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: buscarPersona.php,v 1.12.2.3 2011/10/22 12:51:52 vtamara Exp $
 * @link      http://sivel.sf.net
 */

require_once 'aut.php';
require_once $_SESSION['dirsitio'] . "/conf.php";
require_once "DataObjects/Persona.php";
require_once 'misc.php';

/**
 * Muestra lista de personas en base
 *
 * @return void
 */
function muestra()
{
    global $dsn, $accno;

    $aut_usuario = "";
    $db = autenticaUsuario($dsn, $accno, $aut_usuario, 0);
    $nombres = trim(utf8_decode(var_req_escapa('nombres', $db, 100)));
    $apellidos  = trim(utf8_decode(var_req_escapa('apellidos', $db, 100)));
    $rol = var_req_escapa('rol', $db, 32);
    $pn = explode(' ', $nombres);
    $pa = explode(' ', $apellidos);
    $cn = array();
    foreach ($pn as $p) {
        if ($p != '') {
            $cn[] = $p;
        }
    } 
    foreach ($pa as $p) {
        if ($p != '') {
            $cn[] = $p;
        }
    } 

    $patron = crea_patron($cn);

    //echo "OJO nombres=$nombres, apellidos=$apellidos, b=$b, patron='$patron'";
    $x =&  objeto_tabla('persona');
    $q = "SELECT id, nombres, apellidos, anionac, mesnac, dianac, sexo, " .
        " id_departamento, id_municipio, id_clase, tipodocumento, " .
        " numerodocumento " .
        " FROM persona " .
        " WHERE (nombres || apellidos NOT IN ('NN', 'N.N', 'N.N.')) " .
        " AND ((nombres || apellidos) ~* '$patron') " .
        " ORDER BY nombres, apellidos ";
    //echo "OJO q=\"$q\"<br>";
    $result = hace_consulta($db, $q);

    echo_esc("Coincidencias: " . $result->numRows());
    echo "<hr>";
    $row = array();
    echo "<html><head><title>Personas</title></head>";
    echo "<body>";
    echo '<script language="JavaScript" src="sivel.js" type="text/javascript">'
        . '</script>';
    //echo "rol=$rol";
    echo "<table>";
    echo "<tr>" .
        "<th>Nombres y apellidos</th><th>V�ctima en</th><th>Familiar en</th>".
        "</tr>";

    while ($result->fetchInto($row)) {
        echo "<tr>";
        echo '<td><a href="#" onClick="enviar_persona(\'' 
            . htmlentities($rol) . '\'';
        $html_sep = ", ";
        foreach ($row as $v) {
            echo $html_sep . "'" . htmlentities($v) . "'";
            $html_sep = ", ";
        }
        echo ')">';
        echo htmlentities($row[1] . " ".$row[2]) . "</a></td>";
        $comovic = "";
        $comofam = "";
        enlaces_casos_persona(
            &$db, 0, $row[0], &$comovic, &$comofam
        );
        echo "<td align='center'>" . htmlentities($comovic) . "</td>";
        echo "<td align='center'>" . htmlentities($comofam) . "</td>";

        echo "</tr>\n";
    }
    echo "</table>";
    echo "</body></html>";
}

muestra();
?>
