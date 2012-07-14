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
 * Definicion para la tabla municipio_caso.
 */
require_once 'DB_DataObject_SIVeL.php';

/**
 * Definicion para la tabla municipio_caso.
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Municipio_caso extends DB_DataObject_SIVeL
{

    var $__table = 'municipio_caso';                  // table name
    var $id_municipio;                    // int4(4)  multiple_key
    var $id_departamento;                 // int4(4)  multiple_key
    var $id_caso;                         // int4(4)  multiple_key


    var $fb_preDefOrder = array('id_municipio');
    var $fb_fieldsToRender = array('id_municipio');
    var $fb_selectAddEmpty = array('id_municipio');
    var $fb_addFormHeader = false;


}

?>
