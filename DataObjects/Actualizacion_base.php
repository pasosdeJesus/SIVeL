<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8: */

/**
 * Objeto asociado a una tabla de la base de datos.
 * Parcialmente generado por DB_DataObject.
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio publico. Sin garantias.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantias.
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */


/**
 * Definicion para la tabla actualizacion_base.
 */

require_once 'DB_DataObject_SIVeL.php';

/**
 * Definicion para la tabla actualizacion_base.
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Actualizacion_base extends DB_DataObject_SIVeL
{

    var $__table = 'actualizacion_base';                     // table name
    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        $this->nom_tabla = _('Actualizacion Base');
    }

    var $id;                              // varcha(10)  not_null primary_key
    var $fecha;                           // date(4)  not_null
    var $descripcion;                          // varchar(-1)  not_null

}

?>
