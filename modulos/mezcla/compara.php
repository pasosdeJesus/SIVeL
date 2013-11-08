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
    echo "<tr><th colspan='2'>Primero</th><th colspan='2'>Segundo</th><th>Confirma</th></tr>";

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
        $err = "";
        $nid = 2;
        if ($nid != 2 || $id1 <=0 || $id2 <= 0 || $id1 == $id2) {
            error_valida("Debe se&ntilde;alar dos casos en lugar de $nid", null);
            return false;
        }
        $v1_html = enlace_edita($id1);
        $v2_html = enlace_edita($id2);
        $r1 = ResConsulta::reporteRelato(
            $id1, $db, 
            $GLOBALS['cw_ncampos'] + array('m_fuentes' => 'Fuentes')
        );
        $r2 = ResConsulta::reporteRelato(
            $id2, $db, 
            $GLOBALS['cw_ncampos'] + array('m_fuentes' => 'Fuentes')
        );
        echo "<tr>"
            . "</td>"
            . "<td>" . $v1_html . "</td>"
            . "<td>" . $r1 . "</td>"
            . "<td>" . $v2_html . "</td>"
            . "<td>" . $r2 . "</td>"
            . "<td>" 
            . "<input type='checkbox' name='m_{$id1}_{$id2}' checked/>"
            . "</td>"
            . "</tr>";
        $id1 = null;
        $id2 = null;
    }
    echo "</table></center>";
    echo "<center><input type='submit' value='Mezclar Segundo en Primero y Eliminar Segundo'/></center>";
    echo "</form>";


}


?>
