<?php
//  vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Listado de homonimos
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2014 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
*/

/**
 * Punto de entrada
 *
 * @param string $dsn URL de base
 *
 * @return void
 */
function muestra() 
{
    $tabla = "homonimosim";

    $d = objeto_tabla($tabla);
    $db = $d->getDatabaseConnection();

    encabezado_envia(_("Homonimos o similares (pero diferentes)"));

    echo '<table border = "0" width = "100%"><tr>'
        . ' <td style = "white-space: nowrap;'
        . 'background-color:#CCCCCC;" align = "center" '
        . 'valign = "top" colspan = "2"><b>Homonimos o Similares (pero diferentes)'
        . '</b></td></tr></table>';

    echo "<table border = '1'><tr><th>Número</th>
        <th colspan='2' align='center'>Persona 1</th>
        <th colspan='2' align='center'>Persona 2</th></tr>";
    echo "<tr><th></th><th>Nombre</th><th>En casos como Víctima</th>
        <th>Nombre</th><th>En casos como Víctima</th>
        </tr>";
    $d->find();
    $num = 0;
    while ($d->fetch()) {
        $num++;
        $p1 = $d->getLink('id_persona1');
        $p2 = $d->getLink('id_persona2');
        echo "<tr><td>$num</td><td>{$p1->nombres} {$p1->apellidos}</td><td>";
        $q = "SELECT id_caso FROM victima WHERE id_persona={$d->id_persona1}";
        $r = hace_consulta($db, $q);
        $fila = array();
        $sep = "";
        while ($r->fetchInto($fila)) {
            echo $sep . $fila[0];
            $sep =", ";
        }
        echo "</td><td>{$p2->nombres} {$p2->apellidos}</td><td>";
        $q = "SELECT id_caso FROM victima WHERE id_persona={$d->id_persona2}";
        $r = hace_consulta($db, $q);
        $fila = array();
        $sep = "";
        while ($r->fetchInto($fila)) {
            echo $sep . $fila[0];
            $sep =", ";
        }
        echo "</td></tr>";
    }

    echo "</table>";

    pie_envia();
}

?>
