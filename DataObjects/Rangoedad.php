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
 * Definicion para la tabla rangoedad.
 */
require_once 'DataObjects/Basica.php';

/**
 * Definicion para la tabla rangoedad.
 * Ver documentación de DataObjects_Basica.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Basica
 */
class DataObjects_Rangoedad extends DataObjects_Basica
{
    var $__table = 'rangoedad';                      // table name
    var $rango;                           // varchar(-1)  not_null
    var $limiteinferior;
    var $limitesuperior;

    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

        $this->nom_tabla = _('Rango de Edad');
        $this->fb_fieldLabels = array(
            'nombre' => _('Nombre'),
            'rango' => _('Rango'),
            'limiteinferior' => _('Límite Inferior'),
            'limitesuperior' => _('Límite Superior'),
            'fechacreacion' => _('Fecha de Creación'),
            'fechadeshabilitacion' => _('Fecha de Deshabilitación'),
        );


    }


    var $fb_linkDisplayFields = array('rango');
    var $fb_select_display_field = 'rango';
    var $fb_linkOrderFields = array('nombre');
    var $fb_preDefOrder = array(
        'id',
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
     * Prepara consulta agregando objeto enlazado a este por
     * campo field.
     *
     * @param object &$opts  objeto DB para completar consulta
     * @param string &$field campo por el cual enlazar
     *
     * @return void
     */
    function prepareLinkedDataObject(&$opts, &$field)
    {
        $opts->whereAdd('fechadeshabilitacion IS NULL');
    }

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
