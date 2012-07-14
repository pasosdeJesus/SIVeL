<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:

/**
 * Permite editar o agregar registros a una tabla
 * Referencias: http://www.21st.de/downloads/rapidprototyping.pdf
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
 * Permite editar o agregar registros a una tabla
 */
require_once "aut.php";
require_once $_SESSION['dirsitio'] . '/conf.php';
require_once "misc.php";

$aut_usuario = "";
autenticaUsuario($dsn, $aut_usuario, 11);

require_once $_SESSION['dirsitio'] . '/conf_int.php';
require_once "misc_caso.php";
require_once "DB/DataObject/FormBuilder.php";

if (!isset($_REQUEST['tabla'])) {
    die('Por favor especificar parametro "tabla"');
}
$tabla = var_req_escapa('tabla');

actGlobales();
$u = html_menu_toma_url($GLOBALS['menu_tablas_basicas']);
if (!in_array($tabla, $u)) {
    die("La tabla '$tabla' no es básica");
}
$d = objeto_tabla($tabla);
$db = $d->getDatabaseConnection();

if (isset($_GET['id'])) {
    $a = explode(',', var_escapa($_GET['id'], $db));
    foreach ($a as $v) {
        $r = explode('=', $v);
        $n = $r[0];
        $d->$n = $r[1];
    }
    if ($d->find()!=1) {
        die("Se esperaba un sólo registro");
    }
    $d->fetch();
}
$d->fb_addFormHeader = true;
$d->useMutators = true;
$d->hidePrimaryKey = false;
$b =& DB_DataObject_FormBuilder::create($d);
$b->useMutators = true;
$b->createSubmit = 0;
$f = $b->getForm(htmlspecialchars($_SERVER['REQUEST_URI']));


$f->setRequiredNote($mreq);

$ed = array();
if (!isset($_GET['id'])) {
    $e =& $f->createElement('submit', 'añadir', 'Añadir');
    $ed[] =& $e;
} else {
    $e =& $f->createElement('submit', 'actualizar', 'Actualizar');
    $ed[] =& $e;
    $e =& $f->createElement('submit', 'eliminar', 'Eliminar');
    $ed[] =& $e;
    $f->addElement('hidden', 'tabla', $tabla);
}
$f->addGroup($ed, null, '', '&nbsp;', false);
$f->addElement(
    'header', null,
    '<div align = "right"><a href = "index.php">Menú Principal</a></div>'
);


if ($f->validate()) {
    if (!$d->masValidaciones($f->_submitValues)) {
        echo "No pasaron validaciones adicionales";
    } else if (!verifica_sin_CSRF($f->_submitValues)) {
        die("Datos enviados no pasaron verificación CSRF");
    } else {
        $res = null;
        if (isset($f->_submitValues['actualizar'])
            || isset($f->_submitValues['añadir'])
        ) {
            if (isset($f->_submitValues['actualizar'])) {
                $b->forceQueryType(
                    DB_DATAOBJECT_FORMBUILDER_QUERY_FORCEUPDATE
                );
            } else {
                $b->forceQueryType(
                    DB_DATAOBJECT_FORMBUILDER_QUERY_FORCEINSERT
                );
            }
            $res = $f->process(array($b, 'processForm'), false);
        } else {
            foreach ($f->_submitValues as $k => $v) {
                $d->$k = $v;
            }
            $res =& $d->delete();
        }
        if (PEAR::isError($res)) {
            echo_esc($res->getMessage());
        } else {
            // prevenimos HRHS
            // http://www.securiteam.com/securityreviews/5WP0E2KFGK.html
            $ntabla = str_replace(array("\n", "\r"), array("", ""), $tabla);

            header('Location: tabla.php?tabla=' . $ntabla);
        }
    }
}

agrega_control_CSRF($f);

echo $f->toHtml();

?>

