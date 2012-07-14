<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Busca datos repetidos
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

require_once "aut.php";
require_once $_SESSION['dirsitio'] . '/conf.php';
require_once "confv.php";
require_once "misc.php";


$aut_usuario = "";
$db = autenticaUsuario($dsn, $aut_usuario, 65);

echo '<table width="100%">'
    . '<td style="white-space: nowrap; background-color: #CCCCCC;" '
    . 'align="left" valign="top" colspan="2"><b>'
    . '<div align=center>Reporte de casos repetidos ' . date('Y-m-d H:m') .
    '</div></b></td></table><p/>';

$res =& hace_consulta(
    $db, "SELECT c1.id, trim(c1.memo), count(c1.id) as num " .
    " FROM caso c1 JOIN caso c2 ON c1.memo=c2.memo " .
    " GROUP BY c1.id, c1.memo having count(c2.memo) > 1 " .
    " ORDER BY c1.memo, c1.id"
);

echo "<table width='100%' border='1'><tr>" .
    "<th width='50%'>Memo</th><th>Casos</th></tr>";
$reg = array();
while ($res->fetchInto($reg)) {
    echo "<tr><td>" . htmlentities($reg[1]) . "</td><td>" .
        "<a href='captura_caso.php?id=" . urlencode($reg[0]) .
        "'>" . htmlentities($reg[0]) . "</a> ";
    for ($i = 1; $i < $reg[2]; $i++) {
        $reg2 =& $res->fetchRow();
        echo "<a href='captura_caso.php?id=" . urlencode($reg2[0]) .
            "'>" . htmlentities($reg2[0]) . "</a> ";
    }
    echo "</td></tr>\n";
}
echo "</table>";
echo "<p/>";

echo '<table width="100%">'
    . '<td style="white-space: nowrap; background-color: #CCCCCC;" '
    . 'align="left" valign="top" colspan="2"><b><div align=right>'
    . '<a href="index.php">Menú Principal</a></div></b></td></table>';

?>
