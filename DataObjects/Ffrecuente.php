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
 * Definicion para la tabla ffrecuente.
 * Ver documentación de DataObjects_Basica.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Basica
 */
class DataObjects_Ffrecuente extends DataObjects_Basica
{
    var $__table = 'ffrecuente';                          // table name
    var $tfuente;                     // varchar(-1)  not_null

    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

        $this->nom_tabla = _('Fuentes frecuentes');
        $this->fb_fieldLabels= array(
            'tfuente'=> _('Tipo de Fuente'),
            'fechacreacion' => _('Fecha de Creación'),
            'fechadeshabilitacion' => _('Fecha de Deshabilitación'),
        );

    }

    var $fb_enumFields = array('tfuente');
    var $es_enumOptions = array(
        'tfuente' => array(
            'Directa' => 'Directa',
            'Indirecta' => 'Indirecta'
        )
    );
    var $fb_preDefOrder = array(
        'id',
        'nombre',
        'tfuente',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsToRender = array(
        'nombre',
        'tfuente',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsRequired = array(
        'nombre',
        'tfuente',
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
