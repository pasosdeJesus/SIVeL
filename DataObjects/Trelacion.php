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
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

/**
 * Definicion  para la tabla trelacion
 */
require_once 'DB_DataObject_SIVeL.php';
require_once 'HTML/QuickForm/Action.php';

/**
 * Definicion para la tabla trelacion
 * Ver documentación de DataObjects_Basica.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Basica
 */
class DataObjects_Trelacion extends DataObjects_Basica
{
    var $__table = 'trelacion';         // table name
    var $inverso;                        // integer
    var $observaciones;                   // varchar(-1)  not_null

    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

        $this->nom_tabla = _('Tipo de Relación');
        $this->fb_fieldLabels = array(
            'id' => _('Identificación'),
            'nombre' => _('Nombre'),
            'fechacreacion' => _('Fecha de Creación'),
            'fechadeshabilitacion' => _('Fecha de Deshabilitación'),
            'inverso' => _('Relación Inversa'),
            'observaciones' => _('Observaciones'),
        );

    }


    var $fb_preDefOrder = array(
        'id',
        'nombre',
        'inverso',
        'observaciones',
        'fechacreacion',
        'fechadeshabilitacion'
    );
    var $fb_fieldsToRender = array(
        'id',
        'nombre',
        'inverso',
        'observaciones',
        'fechacreacion',
        'fechadeshabilitacion'
    );
    var $fb_hidePrimaryKey = false;

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
