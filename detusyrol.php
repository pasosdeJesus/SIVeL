<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:

/**
* Editar usuarios y roles de usuario
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
 * Editar usuarios y roles de usuario
 */
require_once 'aut.php';
require_once $_SESSION['dirsitio'] . '/conf.php';
require_once "misc.php";
require_once "DB/DataObject/FormBuilder.php";

$aut_usuario = "";
autentica_usuario($dsn, $aut_usuario, 12);
$tabla = 'usuario';

$d = objeto_tabla($tabla);
if (PEAR::isError($d)) {
    die($d->getMessage());
}
$db = $d->getDatabaseConnection();
if (isset($_GET['id'])) {
    $a = explode(',', var_escapa($_GET['id'], $db));
    foreach ($a as $v) {
        $r = explode('=', $v);
        $n = $r[0];
        $d->$n = $r[1];
    }
    if ($d->find()!=1) {
        die(_("Se esperaba un sólo registro"));
    }
    $d->fetch();
}

$b =& DB_DataObject_FormBuilder::create($d);
$b->createSubmit = 0;
$b->useMutators = true;
$f = $b->getForm(htmlspecialchars($_SERVER['REQUEST_URI']));
global $mreq;
$f->setRequiredNote($mreq);
$h =& $f->getElement('__header__');
$h->setText(_($d->nom_tabla));

$ed = array();
if (!isset($_GET['id'])) {
    $e =& $f->createElement('submit', 'añadir', _('Añadir'));
    $ed[] =& $e;
} else {
    $s=& $f->getElement('id');
    $s->freeze();
    $e =& $f->createElement('submit', 'actualizar', _('Actualizar'));
    $ed[] =& $e;
}
$f->addGroup($ed, null, '', '&nbsp;', false);
$f->addElement(
    'header', null, '<div align=right>' .
    '<a href = "index.php">' . _('Men&uacute; Principal') . '</a></div>'
);

$actsincambiarclave = isset($f->_submitValues['actualizar'])
    && $f->_submitValues['password'] == '';
if ($actsincambiarclave || $f->validate()) {
    if (!verifica_sin_CSRF($f->_submitValues)) {
        die(_("Datos enviados no pasaron verificación CSRF"));
    }
    //echo "OJO 1\n";
    if (isset($GLOBALS['deshabilita_manejo_usuarios'])
        && $GLOBALS['deshabilita_manejo_usuarios'] === true
    ) {
            die(_("Funcionalidad deshabilitada"));
    }

    //echo "OJO 2\n";
    if (isset($f->_submitValues['actualizar'])) {
        //echo "OJO 2.1\n";
        $b->forceQueryType(DB_DATAOBJECT_FORMBUILDER_QUERY_FORCEUPDATE);
    } else {
        $b->forceQueryType(DB_DATAOBJECT_FORMBUILDER_QUERY_FORCEINSERT);
    }
    //echo "OJO 3\n";
    if (isset($f->_submitValues['actualizar'])
        || isset($f->_submitValues['añadir'])
    ) {
        //echo "OJO 4\n";
        if ($b->sign_in_count == null) {
            $b->sign_in_count == 0;
        }
        $res = $f->process(array($b, 'processForm'), false);
        //echo "OJO 5 res=$res\n";
        if ($_SESSION['id_usuario'] == $f->_submitValues['id']) {
            idioma(var_escapa($f->_submitValues['idioma']));
        }
        if (isset($f->_submitValues['añadir']) ) {
            $db = $d->getDatabaseConnection();
            if (PEAR::isError($db)) {
                die($db->getMessage());
            }
        }
    } else {
        foreach ($f->_submitValues as $k => $v) {
            $d->$k = $v;
        }
        //$res=& $d->delete();
    }
    if ($res) {
        /*ambiente();
        return;die("OJO quitar"); */
        header('Location: usyroles.php');
    }
    if (PEAR::isError($d)) {
        echo_esc($d->getMessage());
        echo_esc($d->userinfo());
    }
}


agrega_control_CSRF($f);

encabezado_envia(_("Detalle de usuario"));
echo $f->toHtml();
pie_envia();

?>
