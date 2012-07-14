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
 * Definicion para la tabla rango_edad.
 */
require_once 'DataObjects/Basica.php';

/**
 * Definicion para la tabla rango_edad.
 * Ver documentación de DataObjects_Basica.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Basica
 */
class DataObjects_Rango_edad extends DataObjects_Basica
{
    var $__table = 'rango_edad';                      // table name
    var $rango;                           // varchar(-1)  not_null
    var $limiteinferior;
    var $limitesuperior;

    var $nom_tabla = 'Rango de edad';

    var $fb_linkDisplayFields = array('rango');
    var $fb_select_display_field = 'rango';
    var $fb_linkOrderFields = array('nombre');
    var $fb_preDefOrder = array(
        'nombre',
        'rango',
        'limiteinferior',
        'limitesuperior',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsToRender = array(
        'nombre',
        'rango',
        'limiteinferior',
        'limitesuperior',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsRequired = array(
        'nombre',
        'rango',
        'limiteinferior',
        'limitesuperior',
        'fechacreacion',
    );



    /**
     * Nombres por presentar para cada campo.
     */
    var $fb_fieldLabels = array(
        'nombre' => 'Nombre',
        'Rango' => 'Rango',
        'limiteinferior' => 'Límite Inferior',
        'limitesuperior' => 'Límite Superior',
        'fechacreacion' => 'Fecha de Creación',
        'fechadeshabilitacion' => 'Fecha de Deshabilitación',
    );

    /**
     * Identificacion de registro 'SIN INFORMACIÓN'
     *
     * @return int Id.
     */
    static function idSinInfo()
    {
        return 6;
    }

}

?>
