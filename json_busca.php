<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Busca y retorna información de una tabla en formato JSON
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara  Patiño <vtamara@pasosdeJesus.org>
 * @copyright 2013 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * Acceso: CONSULTA PÚBLICA
 * @link      http://sivel.sf.net
 */


require_once "aut.php";
require_once $_SESSION['dirsitio'] . "/conf.php";
require_once 'misc.php';

global $dsn;
$aut_usuario = "";
$db = autentica_usuario($dsn, $aut_usuario, 11);

require_once $_SESSION['dirsitio'] . '/conf_int.php';
require_once 'misc_caso.php';


$ret = array();
if (isset($_GET['tabla'])) {
    $tabla = var_escapa($_GET['tabla']);
    act_globales();
    $u = html_menu_toma_url($GLOBALS['menu_tablas_basicas']);
    if (!in_array($tabla, $u)) {
        die(_("La tabla '") . $tabla . _("' no es básica"));
    }
    $do = objeto_tabla($tabla);
    sin_error_pear($do);
    $db =& $do->getDatabaseConnection();
    sin_error_pear($db);
    $q = "SELECT * FROM $tabla WHERE fechadeshabilitacion IS NULL ";
    $sep = " AND ";
    foreach ($_GET as $cs => $vs) {
        $c = var_escapa($cs);
        if (in_array($c, $do->fb_preDefOrder)) {
            $v = var_escapa($vs);
            $q .= $sep . " $c = '$v'";
            $sep = " AND ";
        }
    }
    $q .= " ORDER BY nombre";
    //trigger_error("q=" . $q);
    $db->setFetchMode(DB_FETCHMODE_ASSOC);
    $r = hace_consulta($db, $q);
    sin_error_pear($r);
    $row = array();
    while ($r->fetchInto($row)) {
        $ret[] = $row;
    }
}

echo json_encode($ret);

//trigger_error("json_municipios terminó");

?>
