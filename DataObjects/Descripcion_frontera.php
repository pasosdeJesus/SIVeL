<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Objeto asociado a una tabla de la base de datos.
 * Parcialmente generado por DB_DataObject.
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

/**
 * Definicion para la tabla descripcion_frontera.
 */
require_once 'DB_DataObject_SIVeL.php';

/**
 * Definicion para la tabla descripcion_frontera.
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Descripcion_frontera extends DB_DataObject_SIVeL
{

    var $__table = 'descripcion_frontera';            // table name
    var $id;                              // int4(4)  not_null primary_key
    var $id_frontera;                     // int4(4)
    var $lugar;                           // varchar(-1)  not_null
    var $sitio;                           // varchar(-1)
    var $tipo;                            // varchar(-1)

}

?>
