<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
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
 * @version   CVS: $Id: Tipo_clase.php,v 1.12.2.2 2011/10/22 14:58:19 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

/**
 * Definicion para la tabla tipo_clase.
 */
require_once 'DataObjects/Basica.php';

/**
 * Definicion para la tabla tipo_clase.
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Basica
 */
class DataObjects_Tipo_clase extends DataObjects_Basica
{

    var $__table = 'tipo_clase';                      // table name

    var $nom_tabla = 'Tipo Clase';

    var $fb_hidePrimaryKey = false;
    var $fb_fieldsToRender = array(
        'id',
        'nombre',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsRequired = array(
        'id',
        'nombre',
        'fechacreacion',
    );

}

?>
