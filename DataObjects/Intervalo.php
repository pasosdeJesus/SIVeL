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
 * Definicion para la tabla intervalo.
 */
require_once 'DataObjects/Basica.php';

/**
 * Definicion para la tabla intervalo.
 * Ver documentación de DataObjects_Basica.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Basica
 */
class DataObjects_Intervalo extends DataObjects_Basica
{
    var $__table = 'intervalo';                       // table name
    var $rango;                           // varchar(-1)  not_null

    var $fb_linkDisplayFields = array('nombre');
    var $fb_hidePrimaryKey = true;

    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

        $this->nom_tabla = _('Intervalo');
        $this->fb_fieldLabels['rango'] = _('Rango');
    }

    var $fb_preDefOrder = array(
        'id',
        'nombre',
        'rango',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsToRender = array(
        'nombre',
        'rango',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsRequired = array(
        'nombre',
        'rango',
        'fechacreacion',
    );


    /**
     * Identificacion de registro 'SIN INFORMACIÓN'
     *
     * @return string Identificación
     */
    static function idSinInfo()
    {
        return 5;
    }


}
?>
