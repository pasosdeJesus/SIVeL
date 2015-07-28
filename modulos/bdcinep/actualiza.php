<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
/**
* Actualiza personalización
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2015 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html DP
 *            Dominio Público. Sin garantías.
 * @version   CVS: $$
 * @link      http://sivel.sf.net
 */

/**
 * Actualiza base de datos después de actualizar fuentes 
*/
require_once "aut.php";
require_once $_SESSION['dirsitio'] . '/conf.php';
require_once "confv.php";
require_once "misc.php";
require_once "DataObjects/Categoria.php";
require_once "misc_actualiza.php";


$aut_usuario = "";
$db = autenticaUsuario($dsn, $accno, $aut_usuario, 21);


$act = objeto_tabla('Actualizacion_base');

$idac = 'bd-1';
if (!aplicado($idac)) {

    hace_consulta(
        $db, "INSERT INTO opcion 
        (id_opcion, descripcion, id_mama, nomid) 
        VALUES ('57', 'Tablas Consolidado General de Víctimas', '50', 'opcion?num=1000')", false
    ); 
    aplicaact($act, $idac, 'Opciones de BD');
}





?>
