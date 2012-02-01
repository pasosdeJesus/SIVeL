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
 * @copyright 2009 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: Tipo_relacion.php,v 1.12.2.1 2011/09/14 14:56:18 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

/**
 * Definicion  para la tabla tipo_relacion
 */
require_once 'DB_DataObject_SIVeL.php';
require_once 'HTML/QuickForm/Action.php';

class DataObjects_Tipo_relacion extends DataObjects_Basica
{
    var $__table = 'tipo_relacion';         // table name
    var $dirigido;                        // boolean
    var $observaciones;                   // varchar(-1)  not_null

    var $nom_tabla = 'Tipo de Relación';

    var $fb_preDefOrder = array(
        'id',
        'nombre',
        'dirigido',
        'observaciones',
        'fechacreacion',
        'fechadeshabilitacion'
    );
    var $fb_fieldsToRender = array(
        'id',
        'nombre',
        'dirigido',
        'observaciones',
        'fechacreacion',
        'fechadeshabilitacion'
    );
    var $fb_hidePrimaryKey = false;
    var $fb_booleanFields = array(
        'dirigido'
    );


    /**
     * Identificacion de registro 'SIN INFORMACIÓN'
     *
     * @return integer Id del registro SIN INFORMACIÓN
     */
    static function idSinInfo()
    {
        return 'SI';
    }

}

?>
