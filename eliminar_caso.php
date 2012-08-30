<?php
//  vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:

/**
* Elimina caso en el que está (se supone que la eliminación ya fue confirmada)
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2005 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
*/

/**
 * Elimina caso en el que está (se supone que la eliminación ya fue confirmada)
 */
require_once 'misc.php';
require_once 'aut.php';
require_once $_SESSION['dirsitio'] . '/conf.php';
require_once 'misc_caso.php';

$aut_usuario = "";
$db = autentica_usuario($dsn, $aut_usuario, 21);

encabezado_envia();
$idcaso = $_SESSION['basicos_id'];

elimina_caso($db, $idcaso);

echo "Caso " . $idcaso . " eliminado<br/>";
echo '<table border="0" width="100%" ' .
    'style="white-space: nowrap; background-color:#CCCCCC;"><tr>' .
    '<td align = "right"><a href = "index.php"><b>Menú Principal</b></a></td>' .
    '</tr></table>';

pie_envia();
?>
