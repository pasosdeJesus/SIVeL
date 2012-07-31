<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:

/**
 * Presenta registros de una tabla básica
 * Referencias:
 * - http://www.21st.de/downloads/rapidprototyping.pdf
 * - http://pear.php.net/manual/en/package.database.db-dataobject.intro-purpose.php
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
 * Presenta registros de una tabla básica
 */
require_once "aut.php";
require_once $_SESSION['dirsitio'] . '/conf.php';
require_once "misc.php";

$aut_usuario = "";
$db = autenticaUsuario($dsn, $aut_usuario, 11);

require_once $_SESSION['dirsitio'] . '/conf_int.php';
require_once "misc_caso.php";

/**
 * Muestra un registro
 *
 * @param object &$d     DataObject con registro por mostrar
 * @param string $titulo Título
 * @param string $k      Llave del elemento d
 *
 * @return array ($pk, $t) donde $pk es llave primaria y $t es texto por mostrar
 */
function registro(&$d, $titulo, $k)
{
    $vd = get_object_vars($d);

    if (is_array($titulo)) {
        $t = "";
        $sep = "";
        $psep = "";
        $p = " (";
        foreach ($titulo as $c) {
            // getLink no soporta llaves foraneas múltiples
            // Debemos hacerlo a mano en caso de clase y categoria
            $ds = null;
            if ($c == 'id_municipio') {
                $ds = objeto_tabla('Municipio');
                $ds->id = $d->id_municipio;
                $ds->id_departamento = $d->id_departamento;
                $ds->find(1);
            } else if ($c == 'id_supracategoria') {
                $ds = objeto_tabla('Supracategoria');
                $ds->id = $d->id_supracategoria;
                $ds->id_tipo_violencia = $d->id_tipo_violencia;
                $ds->find(1);
            } else if (in_array($c, $k)) {
                $ds = $d->getLink($c);
            }
            if ($ds != null && !PEAR::isError($ds)) {
                $n = $ds->fb_select_display_field;
                $p .= $psep . $ds->$n;
                $psep = ", ";
            } else {
                $t .= $sep . $vd[$c];
            }
            $sep = ", ";
        }
        if ($p != " (") {
            $t .= $p . ")";
        }
    } else {
        $t = $vd[$titulo];
    }

    $pk = "";
    if (is_array($k)) {
        $sep = "";
        foreach ($k as $nl) {
            $pk .= $sep . $nl . "=".($d->$nl);
            $sep = ",";
        }
    } else {
        $pk = $d->$k;
    }
    return array($pk, $t);
}


/**
 * Presenta rama de árbol.
 * En la tabla se requiere que sea una llave simple con nombre id
 * y que el elemento papa se llame id_papa
 *
 * @param string  $tabla   Nombre de tabla
 * @param string  $titulo  Titulo por mostrar
 * @param string  $idpapa  Id. de papá
 * @param boolean $arbol   Es árbol?
 * @param boolean $indenta Cadena con espacios para indentar
 *
 * @return void
 **/
function rama($tabla, $titulo, $idpapa, $arbol, $indenta)
{
    $d = objeto_tabla($tabla);
    if (PEAR::isError($d)) {
        die($d->getMessage());
    }
    if ($arbol) {
        $d->id_papa = $idpapa;
    }
    if (is_array($titulo)) {
        $d->orderBy(implode(",", $titulo));
    } else {
        $d->orderBy($titulo);
    }
    $d->find();
    $pm = array();
    $k = $d->keys();
    while ($d->fetch()) {
        if (!$arbol || $d->id_papa == $idpapa) {
            $pm[] = $d->id;
        }
    }
    foreach ($pm as $tid) {
        $d2 = objeto_tabla($tabla);
        if (PEAR::isError($d2)) {
            die($d2->getMessage());
        }
        $d2->get($tid);
        if ($d2->id_papa == $idpapa) {
            list($pk, $t) = registro($d2, $titulo, $k);
            $html_l = sprintf(
                '%s<a href="detalle.php?id=%s&tabla=%s">
                %s</a><br>',
                $indenta,
                urlencode($pk),
                urlencode($tabla),
                htmlentities($t, ENT_COMPAT, 'UTF-8')
            );
            echo $html_l;
        }
        if ($arbol) {
            rama($tabla, $titulo, $d2->id, true, $indenta . "&nbsp;&nbsp;&nbsp;");
        }
    }
}

if (!isset($_GET['tabla'])) {
    die(_('Por favor especificar parametro "tabla"'));
}
$tabla = var_escapa($_GET['tabla'], $db);

actGlobales();
$u = html_menu_toma_url($GLOBALS['menu_tablas_basicas']);
if (!in_array($tabla, $u)) {
    die(_("La tabla '") . $tabla . _("' no es básica"));
}

$d = objeto_tabla($tabla);
if (isset($d->nom_tabla)) {
    $nom_tabla=  $d->nom_tabla;
} else if (isset($GLOBALS['etiqueta'][$d->__table])) {
    $nom_tabla= $GLOBALS['etiqueta'][$d->__table];
} else {
    $nom_tabla = $tabla;
}

encabezado_envia(_("Tabla ") . $nom_tabla);

echo '<table border = "0" width = "100%"><tr>'
    . ' <td style = "white-space: nowrap;'
    . 'background-color:#CCCCCC;" align = "center" '
    . 'valign = "top" colspan = "2"><b>'
    . htmlentities($nom_tabla, ENT_COMPAT, 'UTF-8') . '</b></td></tr></table>';

$k = $d->keys();
$titulo = $_DB_DATAOBJECT_FORMBUILDER['CONFIG']['select_display_field'];

if (isset($d->fb_linkDisplayFields)) {
    $titulo = $d->fb_linkDisplayFields;
} else if (isset($d->fb_select_display_field)) {
    $titulo = $d->fb_select_display_field;
}

$vd = get_object_vars($d);
if (in_array('id_papa', array_keys($vd))) { /** jerarquía */
    rama($tabla, $titulo, null, true, "");
} else { /** Lineal */
    if (is_array($titulo)) {
            $d->orderBy(implode(",", $titulo));
    } else {
            $d->orderBy($titulo);
    }
    $d->find();
    while ($d->fetch()) {
            list($pk, $t) = registro($d, $titulo, $k);
            echo sprintf(
                '<a href="detalle.php?id=%s&tabla=%s">
                %s</a><br>',
                urlencode($pk),
                urlencode($tabla),
                htmlentities($t, ENT_COMPAT, 'UTF-8')
            );
    }
}



echo '<pr>&nbsp;</pr><table border="0" width="100%" ' .
    'style="white-space: nowrap; background-color:#CCCCCC;"><tr>' .
    '<td align = "left">' .
    '<a href="detalle.php?tabla=' . urlencode($tabla) . '">' .
    _('Nuevo') . '</a>' .
    '</td><td align="right">' .
    '<a href="index.php"><b>' . _('Men&uacute; Principal') . '</b></a>' .
    '</td></tr></table>';
pie_envia();


?>
