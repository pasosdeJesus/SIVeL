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
 *
 * Acceso: SÓLO DEFINICIONES
 */

/**
 * Punto de entrada
 *
 * @return void
 */
function muestra($dsn) 
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
        echo "<tr><td>" . htmlentities($num, ENT_COMPAT, 'UTF-8') 
            . "</td><td>" . htmlentities($p1->nombres, ENT_COMPAT, 'UTF-8') 
            . " " . htmlentities($p1->apellidos, ENT_COMPAT, 'UTF-8') 
            . "</td><td>";
        $q = "SELECT id_caso FROM victima WHERE id_persona="
            . var_escapa($d->id_persona1, $db);
        $r = hace_consulta($db, $q);
        $fila = array();
        $sep_html = "";
        while ($r->fetchInto($fila)) {
            echo $sep_html . htmlentities($fila[0], ENT_COMPAT, 'UTF-8') ;
            $sep_html =", ";
        }
        echo "</td><td>"
                .  htmlentities($p2->nombres, ENT_COMPAT, 'UTF-8') 
                .  " " . htmlentities($p2->apellidos, ENT_COMPAT, 'UTF-8') 
                . "</td><td>";
        $q = "SELECT id_caso FROM victima WHERE id_persona=" 
            . var_escapa($d->id_persona2, $db);
        $r = hace_consulta($db, $q);
        $fila = array();
        $sep_html = "";
        while ($r->fetchInto($fila)) {
            echo $sep_html . htmlentities($fila[0], ENT_COMPAT, 'UTF-8') ;
            $sep_html =", ";
        }
        echo "</td></tr>";
    }

    echo "</table>";

    pie_envia();
}

?>
