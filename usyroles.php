<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:

/**
 * Usuarios y Roles.
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: usyroles.php,v 1.28.2.1 2011/09/07 02:52:24 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Usuarios y Roles.
 */
require_once 'aut.php';
require_once $_SESSION['dirsitio'] . '/conf.php';
require_once 'misc.php';

$aut_usuario = "";
autenticaUsuario($dsn, $accno, $aut_usuario, 12);

$tabla = 'usuario';

encabezado_envia("Usuarios");
echo '<table border="0" width="100%"><tr>
    <td style = "white-space: nowrap;background-color:#CCCCCC;" align="center"
    valign = "top" colspan="2"><b>Usuarios</b></td></tr></table>';

$d = objeto_tabla($tabla);
if (PEAR::isError($d)) {
    die($d->getMessage());
}

$k = $d->keys();

$titulo = $_DB_DATAOBJECT_FORMBUILDER['CONFIG']['select_display_field'];

if (isset($d->fb_select_display_field)) {
    $titulo = $d->fb_select_display_field;
}
$d->find();

while ($d->fetch()) {
    $vd = get_object_vars($d);
    $t = $vd['nombre'] . " (" . $vd['id_usuario'] . ")";

    $pk = "";
    if (is_array($k)) {
        $sep = "";
        foreach ($k as $nl) {
            $pk .= $sep . $nl . "=".($d->$nl);
            $sep = ",";
        }
    }
    echo sprintf(
        '<a href = "detusyrol.php?id=%s">
            %s</a><br>',
        urlencode($pk),
        htmlentities($t)
    );
}

echo '<pr>&nbsp;</pr><table border="0" width="100%"
    style = "white-space: nowrap; background-color:#CCCCCC;"><tr>' .
    '<td align = "left">' .
    '<a href = "detusyrol.php">Nuevo</a>' .
    '</td><td align = "right">' .
    '<a href = "index.php"><b>Men� Principal</b></a>' .
    '</td></tr></table>';

pie_envia()
?>
