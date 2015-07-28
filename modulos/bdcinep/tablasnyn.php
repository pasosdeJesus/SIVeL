<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker: 
/**
 *  Tablas para revista NyN
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2014 Dominio público. Sin garantías.
 * @license   http://creativecommons.org/licenses/publicdomain/ Dominio Público. Sin garantías.
 * @version   $$
 * @link      http://sivel.sf.net
 */

/** Actualiza base de datos después de actualizar fuentes */
require_once "aut.php";
require_once $_SESSION['dirsitio'].'/conf.php';
require_once "confv.php";
#require_once "misc.php";
#require_once "DataObjects/Categoria.php";
#require_once "misc_actualiza.php";

$aut_usuario = "";
$db = autentica_usuario($dsn, $aut_usuario, 21);


/**
 * Llamada desde formulario de estadísticas individuales para
 * dar la posibilidad de añadir elementos.
 *
 * @param object &$db   Conexión a B.D
 * @param object &$form Formulario
 *
 * @return Cadena por presentar
 */
function agregar_categoria_nombre(&$db, &$form) {

        sin_error_pear($db);

         $sel =& $form->addElement( 
             'checkbox', 
             'cat_horizontales', 
             'Categorias en eje horizontal y sin supracategoria', 
             'Categorias en eje horizontal y sin supracategoria'
         );
}

/**
 * Llamada desde estadisticas.php para completar primera consulta SQL
 * que genera estadísticas
 *
 * @param object &$db     Conexión a B.D
 * @param string &$where  Consulta SQL que se completa
 * @param string &$tablas Tablas incluidas en consulta
 * @param string &$pSegun Nombre de columna resultante en cons
 * @param string &$campoSegun Columnas por extraer para ser pSegun
 *
 * @return void Puede modificar $tablas, $where y $pSegun
 */
function consulta_categoria_nombre(&$db, &$where, &$tablas, &$pSegun, 
    &$campoSegun)
{
    echo "OJO consulta_categoria_nombre(db, $where, $tablas, $pSegun, $campoSegun)";
    $pCatHorizontales= var_req_escapa('cat_horizontales', $db, 32);
    if ($pCatHorizontales == "1") {
/*        $pSegun .= $pSegun == "" ? "" : ", ";
        $pSegun .= 'categoria_nombre';
$campoSegun .= 'categoria.nombre'; */
    }
}

/**
 * Llamada desde estadisticas.php para completar tercera consulta SQL
 * que genera estadísticas con SELECT $campos3 FROM $tablas3 WHERE $cond3
 *
 * @param object &$db       Conexión a B.D
 * @param string &$campos3  Campos que genera, debe terminar en el que se agrupa
 * @param string &$tablas3  Tablas sobre las que se hace
 * @param string &$cond3    Condición
 * @param string &$pMuestra Forma de presentar
 *
 * @return void Puede modificar $tablas, $where y $pSegun
 */
function consulta3_categoria_nombre(&$db, &$campos3, &$tablas3, &$cond3, 
    $pMuestra)
{
    echo "OJO consulta3_categoria_nombre(db, $campos3, $tablas3, $cond3, $pMuestra)";
    $pCatHorizontales= var_req_escapa('cat_horizontales', $db, 32);
    if ($pCatHorizontales == "1") {
        $campos3 = str_replace("departamento.id, ", "", $campos3);
        $campos3 = str_replace(" trim(tviolencia.nombre), ", "", $campos3);
        $campos3 = str_replace(" trim(supracategoria.nombre), ", "", $campos3);

        $tablas3 = str_replace(" tviolencia, ", "", $tablas3);
        $tablas3 = str_replace(" supracategoria, ", "", $tablas3);

        $cond3 = str_replace(
            "cons2.id_tviolencia = tviolencia.id", "", $cond3
        );
        $cond3 = str_replace(
            "AND cons2.id_tviolencia = supracategoria.id_tviolencia", "", 
            $cond3
        );
        $cond3 = str_replace(
            "AND cons2.id_supracategoria = supracategoria.id", "", $cond3
        );
        $cond3 = str_replace(
            "AND cons2.id_tviolencia = categoria.id_tviolencia", "", $cond3
        );
        $cond3 = str_replace(
            "AND cons2.id_supracategoria = categoria.id_supracategoria", "", 
            $cond3
        );
        $cond3 = str_replace(
            "AND cons2.id_categoria = categoria.id", 
            "cons2.id_categoria = categoria.id", $cond3
        );

        $pMuestra = "categoria_horizontal";
    }

    /**
     * Presenta resultados como tabla con categoria horizontal
     *
     * @param object &$db        Conexión a B.D
     * @param string &$resultado Resultado de consulta
     *
     * @return void Presenta tabla en html
     */
    function muetra_horizontal_html(&$db, &$resultado, $pMuestra, $cab)
    {
        echo "OJO muestra_horizontal_html(db, $resultado, $pMuestra, $cab)";
        if ($pMuestra == 'categoria_horizontal') {
            $res = array();  // Resultados como tabla
            $enc = array();  // Encabezados son categorias de violencia
            while ($resultado->fetchInto($row)) {
                $ini = "";
                $sep = "";
                foreach($row as $k => $t) {
                    if ($k < count($row) - 2) {
                        $ini .= $sep . $t;
                        $sep = "_";
                    }
                }
                $cat = $row[count($row) - 2];
                $val = $row[count($row) - 1];
                $res[$ini][$cat] = $val;
                $tot[$ini] = isset($tot[$ini]) ? $tot[$ini] + $val : $val;
                $enc[$cat] = 1;
                $totcat[$cat] = isset($totcat[$cat]) ? $totcat[$cat] + $val : $val;
            }
            ksort($enc);
            ksort($res);
            encabezado_envia();
            echo "<table border=\"1\"><tr>";
            $colenc = false;
            foreach ($cab as $k => $t) {
                if ($t != "C. Dep." && $t != "Tipo de Violencia" &&
                    $t != "Supracategoria" && $t != "Categoria" &&
                    $t != "N. Víctimizaciones" && $t != 'N. Actos') { 
                        echo "<th>" . htmlentities($t, ENT_COMPAT, 'ISO-8859-1') 
                            . "</th>";
                        $colenc = true;
                    }
            }
            if (!$colenc) {
                echo "<th></th>";
            }
            foreach ($enc as $k => $t) {
                echo "<th> " . htmlentities($k, ENT_COMPAT, 'ISO-8859-1') 
                    . "</th>";
            }
            echo "<th>Total</th>";
            echo "</tr>\n";
            foreach($res as $cini => $catval) {
                echo "<tr>";
                $cini2 = explode("_", $cini);
                foreach($cini2 as $k => $c) {
                    echo "<td>" . htmlentities($c, ENT_COMPAT, 'ISO-8859-1') 
                        . "</td>";
                }
                foreach ($enc as $k => $t) {
                    echo "<td> ";
                    if (isset($catval[$k])) {
                        echo htmlentities($catval[$k], ENT_COMPAT, 'ISO-8859-1');
                    }
                    echo "</td>";
                }
                echo "<td>" . htmlentities($tot[$cini], ENT_COMPAT, 'ISO-8859-1') 
                    . "</td>";
                echo "</tr>";
            }
            echo "<tr><th>Total General</th>";
            $primer = true;
            foreach($cini2 as $k => $c) {
                if (!$primer) {
                    echo "<td></td>";
                } else {
                    $primer = false;
                }
            }
            $gt = 0;
            foreach ($enc as $k => $t) {
                echo "<td> ";
                if (isset($totcat[$k])) {
                    echo htmlentities($totcat[$k], ENT_COMPAT, 'ISO-8859-1');
                    $gt += $totcat[$k];
                }
                echo "</td>";
            }
            echo "<td>" . htmlentities($gt, ENT_COMPAT, 'ISO-8859-1') 
                . "</td>";
            echo "</tr>";


        /*$row = array();
        $nf = 0;
        while ($resultado->fetchInto($row)) {
            echo "<tr>";
            foreach ($cab as $k => $t) {
                echo "<td>";
                echo htmlentities($row[$k], ENT_COMPAT, 'ISO-8859-1');
                echo "</td>";
            }
            echo "</tr>\n";
            $nf++;
        }
        echo "</table>";
        if ($nf > 0) {
            echo '<div align = "right"><a href = "index.php">' .
                '<b>Menú principal</b></a></div>';
        }
        pie_envia(); */
        }
    }
}
