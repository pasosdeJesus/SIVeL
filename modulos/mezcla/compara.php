<?php
//  vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Mezcla dos casos
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2011 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: victimasrep.php,v 1.1 2012/01/11 17:41:30 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Detecta víctimas repetidas
 */

require_once "aut.php";
require_once $_SESSION['dirsitio'] . "/conf.php";
require_once 'misc.php';


foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
    list($n, $c, $o) = $tab;
    if (($d = strrpos($c, "/"))>0) {
        $c = substr($c, $d+1);
    }
    // @codingStandardsIgnoreStart
    require_once "$c.php";
    // @codingStandardsIgnoreEnd
}

/**
 * Retorna subcadena izquierda de $s hasta el punto
 *
 * @param string $s Cadena
 *
 * @return Prefijo izquierdo de z hasta .
 */
function hastapunto($s) 
{
    $p = strpos($s, '.');
    $r = $s;
    if ($p !== false) {
        $r = substr($s, 0, $p); 
    }
    return $p;
}

/**
 * Punto de entrada
 *
 * @param string $dsn URl de la base
 *
 * @return void
 */
function muestra($dsn)
{
    $aut_usuario = "";
    $db = autentica_usuario($dsn, $accno, $aut_usuario, 31);
    encabezado_envia("Comparación y mezcla de 2 casos");

    echo "Se mezclará los segundos casos en los primeros y se eliminaran los segundos.";
    echo "<form action='opcion.php?num=1005' method='POST' target='_blank'>";
    echo "<center><table border='1'>";
    echo "<tr><th>Código</th><th>Fecha</th>"
        . "<th>Departamento</th><th>Víctimas</th><th>Descripción</th>"
        . "<th>Confirma</th></tr>";

    if (!isset($_GET['ids'])) {
        error_valida(
            "No hay parejas de casos "
            . "(si es el caso intente con menos)", null
        );
        return false;
    }

    $pIds   = var_escapa($_GET['ids']);
    $pp = preg_split("/[\s]+/", $pIds);
    $id1 = $id2 = null;
    foreach($pp as $id) {
        if ($id1 == null) {
            $id1 = $id;
            continue;
        } else {
            $id2 = $id;
        }
        $par[] = array($id1, $id2);
        $id1 = null;
        $id2 = null;
    }
    foreach($par as $p) {
        list($id1, $id2) = $p;
        foreach($p as $id) {
            $c = "SELECT DISTINCT caso.id, caso.fecha,
                array(select departamento.nombre from departamento, ubicacion
                where departamento.id=ubicacion.id_departamento 
                and ubicacion.id_caso=caso.id),
            array(select persona.nombres || ' ' || persona.apellidos
            from victima, persona where victima.id_persona=persona.id
            and victima.id_caso=caso.id), caso.memo
            FROM caso where caso.id = $id";
            $r = hace_consulta($db, $c);
            sin_error_pear($r);
            $rows = array();
            $r->fetchInto($rows);
            foreach($rows as $n => $c) {
                if ($n == 0) {
                    $v1_html = enlace_edita($c);
                } else {
                    $v1_html = $c;
                }
                echo "<td>" . $v1_html . "</td>";
            }
            if ($id == $id1) {
                echo "<td rowspan='2'>" 
                    . "<input type='checkbox' name='m_{$id1}_{$id2}' checked/>"
                    . "</td>";
            } 
            echo "</tr>\n";
        }
    }
    echo "</table></center>";
    echo "<center><input type='submit' value='Mezclar Segundo en Primero y Eliminar Segundo'/></center>";
    echo "</form>";


}


?>
