<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:

/**
 * Usuarios y Roles.
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
*/

/**
 * Usuarios y Roles.
 */
require_once 'aut.php';
require_once $_SESSION['dirsitio'] . '/conf.php';
require_once 'misc.php';

$aut_usuario = "";
autenticaUsuario($dsn, $aut_usuario, 12);

$tabla = 'usuario';

encabezado_envia(_("Usuarios"));
echo '<table border="0" width="100%"><tr>
    <td style = "white-space: nowrap;background-color:#CCCCCC;" align="center"
    valign = "top" colspan="2"><b>' . _('Usuarios') 
    . '</b></td></tr></table>';

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
        htmlentities($t, ENT_COMPAT, 'UTF-8')
    );
}

echo '<pr>&nbsp;</pr><table border="0" width="100%"
    style = "white-space: nowrap; background-color:#CCCCCC;"><tr>' .
    '<td align = "left">' .
    '<a href = "detusyrol.php">' . _('Nuevo') . '</a>' .
    '</td><td align = "right">' .
    '<a href = "index.php"><b>' . _('Men&uacute; Principal') . '</b></a>' .
    '</td></tr></table>';

pie_envia()
?>
