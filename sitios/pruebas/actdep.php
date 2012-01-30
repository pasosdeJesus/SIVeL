<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:

/**
* Actualiza departamento.
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2006 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: actdep.php,v 1.9.2.2 2011/10/22 14:58:19 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Actualiza departamento
 */

require_once "ambiente.php";

if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}

$iddep = (int)$db->getOne("SELECT id FROM departamento LIMIT 1;");
$na = (int)$db->getOne(
    "SELECT COUNT(nombre) FROM departamento WHERE " .
    "id = '$iddep';"
);
if ($na!= 1) {
    echo 'No hay un departamento con id = $iddep';
    exit(1);
}

$_REQUEST['tabla'] = $_GET['tabla'] = 'departamento';
$_REQUEST['_qf__dataobjects_departamento'] = '';
$_POST['_qf__dataobjects_departamento'] = '';
$_REQUEST['id'] = $_POST['id'] = $_GET['id'] = "$iddep";
$_REQUEST['nombre'] = $_POST['nombre'] = 'y';
$fc = array('d' => date('d'), 'M' => date('m'), 'Y' => date('Y'));
$_REQUEST['fechacreacion'] = $_POST['fechacreacion'] = $fc;
$_REQUEST['actualizar'] = $_POST['actualizar'] = 'Actualizar';
$_REQUEST['evita_csrf'] = $_SESSION['sin_csrf'] = $_POST['evita_csrf'] = 1234;


require_once "detalle.php";

/* Verificando */
hace_consulta($db, "SELECT COUNT(nombre) FROM departamento;");
$nd = $db->getOne("SELECT nombre FROM departamento WHERE id = '$iddep';");
echo "nd = $nd\n";

if ($nd!= 'y') {
    echo "No actualizó";
    exit(1);
}
exit(0);
?>
