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
 * @return integer Prefijo izquierdo de z hasta .
 */
function hastapunto($s)
{
    $p = strpos($s, '.');
    $r = $s;
    if ($p !== false) {
        $r = substr($s, 0, $p);
    }
    return $r;
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
    $db = autentica_usuario($dsn, $aut_usuario, 31);
    encabezado_envia("Comparación y mezcla de 2 casos");

    if (isset($_SESSION['mezcla_ids'])) {
        $pIds = $_SESSION['mezcla_ids'];
        unset($_SESSION['mezcla_ids']);
    } else {
        if (!isset($_REQUEST['ids'])) {
            error_valida(
                "No hay parejas de casos "
                . "(si es el caso intente con menos)", null
            );
            return false;
        }
        $pIds   = var_escapa($_REQUEST['ids']);
    }

    $par = array();
    $pp = preg_split("/[\s]+/", $pIds);
    $id1 = $id2 = null;
    foreach ($pp as $id) {
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

    echo "<p>Por confirmar " . count($par) . " parejas de casos</p>";
    echo "<p>En los que confirme, se mezclarán los segundos casos 
        en los primeros y se eliminaran los segundos.</p>";
    echo "<form action='opcion.php?num=1005' method='POST' target='_blank'>";
    echo "<center><table border='1'>";
    echo "<tr><th>Código</th><th>Fecha</th>"
        . "<th>Departamento</th><th>Víctimas</th><th>Descripción</th>"
        . "<th>Etiquetas</th>"
        . "<th>Confirma</th><th>Homónimos</th></tr>";


    $col1 = "#FFFFFF";
    $col2 = "#BBBBBB";
    $html_coltr = $col1;
    foreach ($par as $p) {
        list($id1, $id2) = $p;
        if ($html_coltr == $col1) {
            $html_coltr = $col2;
        } else {
            $html_coltr = $col1;
        }
        foreach ($p as $id) {
            $c = "SELECT DISTINCT caso.id, caso.fecha,
                array(SELECT departamento.nombre FROM departamento, ubicacion
                WHERE departamento.id = ubicacion.id_departamento
                AND ubicacion.id_caso = caso.id),
            array(SELECT persona.nombres || ' ' || persona.apellidos
            FROM victima, persona where victima.id_persona = persona.id
            AND victima.id_caso = caso.id), caso.memo,
            array(SELECT etiqueta.nombre FROM etiqueta, caso_etiqueta
            WHERE caso_etiqueta.id_etiqueta=etiqueta.id 
            AND caso_etiqueta.id_caso=caso.id)
            FROM caso where caso.id = $id";
            $r = hace_consulta($db, $c);
            sin_error_pear($r);
            echo "<tr style='background-color:$html_coltr;'>\n";
            $rows = array();
            $r->fetchInto($rows);
            foreach ($rows as $n => $c) {
                if ($n == 0) {
                    $html_v1 = enlace_edita($c);
                } else {
                    $html_v1 = $c;
                }
                echo "<td>" . $html_v1 . "</td>";
            }
            if ($id == $id1) {
                // Busca si es homonimo para poner como homonimo por defecto.
                $q = "SELECT COUNT(*) FROM victima, homonimia WHERE 
                    victima.id_caso='$id1'
                    AND victima.id_persona = homonimia.id_persona1
                    AND homonimia.id_persona2 IN 
                    (SELECT id_persona FROM victima
                    WHERE id_caso='$id2')";
                $nh = $db->getOne($q);
                sin_error_pear($nh);
                $chm = "";
                $chh = "";
                if ($nh > 0) {
                    $chh = "checked";
                    $chm = "";
                }
                echo "<td rowspan='2'><input type='checkbox' "
                    . "name='m_" . (int)$id1 . "_" . (int)$id2 
                    . "' $chm/></td>";
                echo "<td rowspan='2'><input type='checkbox' "
                    . "name='h_" . (int)$id1 . "_" . (int)$id2
                    . "' $chh/></td>";
            }
            echo "</tr>\n";
        }
    }
    echo "</table></center>";
    echo "<center><input type='submit' 
        value='Mezclar Segundo en Primero y Eliminar Segundo'/></center>";
    echo "</form>";
}

?>
