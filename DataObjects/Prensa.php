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

require_once 'DataObjects/Basica.php';

/**
 * Definicion para la tabla prensa.
 * Ver documentación de DataObjects_Basica.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Basica
 */
class DataObjects_Prensa extends DataObjects_Basica
{
    var $__table = 'prensa';                          // table name
    var $tipo_fuente;                     // varchar(-1)  not_null

    var $nom_tabla = 'Fuentes frecuentes';

    var $fb_fieldLabels= array(
        'tipo_fuente'=>'Tipo de Fuente',
        'fechacreacion' => 'Fecha de Creación',
        'fechadeshabilitacion' => 'Fecha de Deshabilitación',
    );
    var $fb_enumFields = array('tipo_fuente');
    var $es_enumOptions = array(
        'tipo_fuente' => array(
            'Directa' => 'Directa',
            'Indirecta' => 'Indirecta'
        )
    );
    var $fb_preDefOrder = array(
        'nombre',
        'tipo_fuente',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsToRender = array(
        'nombre',
        'tipo_fuente',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsRequired = array(
        'nombre',
        'tipo_fuente',
        'fechacreacion',
    );




    /**
     * Identificacion de registro 'SIN INFORMACIÓN'
     *
     * @return integer Id
     */
    static function idSinInfo()
    {
        return 0;
    }

}

?>
