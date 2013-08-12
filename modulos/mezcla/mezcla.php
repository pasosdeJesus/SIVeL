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

require_once "aut.php";
require_once $_SESSION['dirsitio'] . "/conf.php";
require_once 'misc.php';

$aut_usuario = "";
$db = autentica_usuario($dsn, $accno, $aut_usuario, 31);

require_once $_SESSION['dirsitio'] . "/conf_int.php";

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
 * Retorna una subcadena de $s desde la izquierda hasta la primera
 * ocurrencia de $c.
 *
 * @param string $s Cadena en la cual buscar
 * @param string $c Catacter buscado
 *
 * @return Subcadena de $s
 */
function subizq_hasta_car($s, $c)
{
    $p = strpos($s, $c);
    $r = $s;
    if ($p !== false) {
        $r = substr($s, 0, $p); 
    }
    return $r;
}

/**
 * Punto de entrada a formulario
 * 
 * @param string $dsn URL a base de datos
 *
 * @return void
 */
function muestra($dsn)
{
    global $db;
    $nid = 0;
    $id1 = (int)$_POST['id1'];
    $id2 = (int)$_POST['id2'];
    $nid = (int)$_POST['caso_id'];
    encabezado_envia('Mezcla de casos');
    $err = "";
    if ($id1 == $id2) {
        die_esc("Los códigos de los casos por mezclar deben ser diferentes");
    }
    $r = array();
    foreach ($_POST as $l => $v) {
        $t = subizq_hasta_car($l, '-');
        if ($t != $l) {
            $c = substr($l, strlen($t) + 1);
            //$nid = $t . "-" . $c;
            $r[$t][$c] = $v;
        }
    }

    $dec = objeto_tabla('caso_etiqueta');
    $obs = "";
    $idet = (int)conv_basica (
        $db, 'etiqueta', 'MEZCLA_CASOS', $obs, false, 'nombre'
    );
    if ($idet == -1) {
        die_act("No se encontró etiqueta MEZCLA_CASOS");
    }
    $dec->id_etiqueta = $idet;
    $dec->id_funcionario = $_SESSION['id_funcionario'];
    $dec->fecha = @date('Y-m-d');
    $r1 = ResConsulta::reporteRelato(
        $id1, $db, 
        $GLOBALS['cw_ncampos'] + array('m_fuentes' => 'Fuentes')
    );
    $r2 = ResConsulta::reporteRelato(
        $id2, $db, 
        $GLOBALS['cw_ncampos'] + array('m_fuentes' => 'Fuentes')
    );

    if ($nid == 1 || $nid == 2) {
        if ($nid == 2) {  // Asegura mezclar siempre segundo en primero
            $idt= $id1;
            $id1 = $id2;
            $id2 = $idt;
        }
        $idn = $id1;
        $dec->observaciones = "Caso original ($id1): " . strip_tags($r1)
            . "\nCaso mezclado ($id2): " . strip_tags($r2);
    } else {
        $dcaso = objeto_tabla('caso');
        $dcaso->fecha = '2001-01-01';
        $dcaso->memo = '2001-01-01';
        $dcaso->insert();
        $idn = $dcaso->id;
        $dec->observaciones = "Primer caso ($id1): " . strip_tags($r1)
            . "\nSegundo caso ($id2): " . strip_tags($r2);
    }
    
    $dec->id_caso = $idn;
    $dec->insert();

    echo "<p>Mezclando informaci&oacute;n de casos " .
        enlace_edita($id1) . " y " .
        enlace_edita( $id2) . " en caso " .
        enlace_edita($idn) . "</p><hr>";
    foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
        list($n, $c, $o) = $tab;
        if (($d = strrpos($c, "/")) > 0) {
            $c = substr($c, $d + 1);
        }
        $o = new $c('f');
        if (is_callable(array($c, 'mezcla'))) {
            call_user_func_array(
                array($c, 'mezcla'),
                array(&$db, $r, $id1, $id2, $idn, $o->clase_modelo)
            );
        } else {
            echo_esc("Falta mezcla en $n, $c");
        }
    }
    echo ResConsulta::reporteGeneralHtml(
        $idn, $db, 
        $GLOBALS['cw_ncampos'] + array('m_fuentes' => 'Fuentes')
    );
    pie_envia('Mezcla de casos');


}

?>
