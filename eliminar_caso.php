<?php
//  vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:

/**
* Elimina caso en el que est� (se supone que la eliminaci�n ya fue confirmada)
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2005 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: eliminar_caso.php,v 1.19 2011/05/19 04:18:44 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Elimina caso en el que est� (se supone que la eliminaci�n ya fue confirmada)
 */
require_once 'misc.php';
require_once 'aut.php';
require_once $_SESSION['dirsitio'] . '/conf.php';
require_once 'misc_caso.php';

$aut_usuario = "";
$db = autenticaUsuario($dsn, $accno, $aut_usuario, 21);

encabezado_envia();
$idcaso = $_SESSION['basicos_id'];

eliminaCaso($db, $idcaso);

echo "Caso " . $idcaso . " eliminado<br/>";
echo '<table border="0" width="100%" ' .
    'style="white-space: nowrap; background-color:#CCCCCC;"><tr>' .
    '<td align = "right"><a href = "index.php"><b>Men� Principal</b></a></td>' .
    '</tr></table>';

pie_envia();
?>
