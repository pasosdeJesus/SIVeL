<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Permite elegir una persona de la lista de las que están en la base
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2007 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: buscarGrupo.php,v 1.11.2.2 2011/10/13 13:41:06 vtamara Exp $
 * @link      http://sivel.sf.net
 */

require_once 'aut.php';
require_once 'misc.php';
require_once $_SESSION['dirsitio'] . "/conf.php";
require_once "DataObjects/Persona.php";

/**
 * Muestra lista de grupos en base
 *
 * @return void
 */
function muestra()
{
    global $dsn, $accno;

    $aut_usuario = "";
    $db = autenticaUsuario($dsn, $accno, $aut_usuario, 0);

    $nombre = trim(utf8_decode(var_req_escapa('nombre', $db, 100)));
    $pn = explode(' ', $nombre);
    $cn = array();
    foreach ($pn as $p) {
        if ($p != '') {
            $cn[] = $p;
        }
    }
    $patron = crea_patron($cn);

    //echo "OJO nombre=$nombre, patron='$patron'";
    $x =&  objeto_tabla('grupoper');
    $q = "SELECT id, nombre, anotaciones " .
        " FROM grupoper" .
        " WHERE (nombre NOT IN ('NN', 'N.N', 'N.N.')) " .
        " AND (nombre ~* '$patron') " .
        " ORDER BY nombre ";
    //echo "OJO q=\"$q\"<br>";
    $result = hace_consulta($db, $q);


    echo_esc("Coincidencias: " . $result->numRows());
    echo "<hr>";

    $row = array();
    echo "<html><head><title>Grupos</title></head>";
    echo "<body>";
    echo '<script language="JavaScript" src="sivel.js" type="text/javascript">'
        . '</script>';
    echo "<table>";
    echo "<tr>" .
        "<th>Nombre</th><th>Víctima en</th>" .
        "</tr>";
    while ($result->fetchInto($row)) {
        echo "<tr>";
        echo '<td><a href="#" onClick="enviar_grupoper(';
        $html_sep = "";
        foreach ($row as $v) {
            echo $html_sep . "'" . htmlentities($v) . "'";
            $html_sep = ", ";
        }
        echo ')">';
        echo htmlentities($row[1] . " ".$row[2]) . "</td>";
        $comovic = "";
        enlaces_casos_grupoper(&$db, 0, $row[0], &$comovic);
        echo "<td align='center'>" . htmlentities($comovic) . "</td>";
        echo "</tr>\n";
    }
    echo "</table>";
    echo "</body></html>";

}

muestra();
?>
