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
 * Definicion para la tabla presponsable.
 */
require_once 'DataObjects/Basica.php';

/**
 * Definicion para la tabla presponsable.
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Presponsable extends DataObjects_Basica
{

    var $__table = 'presponsable';          // table name
    var $id;                              // int4(4)  not_null primary_key
    var $nombre;                          // varchar(-1)  not_null
    var $id_papa;                            // int4
    var $fechacreacion;                  // date(4)  not_null
    var $fechadeshabilitacion;           // date(4)

    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

        $this->nom_tabla = _('Presuntos Responsables');
        $this->fb_fieldLabels = array(
            'nombre' => _('Nombre'),
            'id_papa' => _('Subestructura de'),
            'fechacreacion' => _('Fecha de Creación'),
            'fechadeshabilitacion' => _('Fecha de Deshabilitación'),
        );

    }


    var $fb_linkDisplayFields = array('nombre');
    var $fb_addFormHeader = false;
    var $fb_hidePrimaryKey = true;
    var $fb_selectAddEmpty = array('fechadeshabilitacion', 'id_papa');
    var $fb_preDefOrder = array(
        'nombre',
        'id_papa',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsToRender = array(
        'nombre',
        'id_papa',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsRequired = array(
        'nombre',
        'fechacreacion',
    );




    /**
     * Pone un valor en la base diferente al recibido del formulario.
     *
     * @param string $valor Valor en formulario
     *
     * @return Valor para BD
     */
    function setfechadeshabilitacion($valor)
    {
        $this->fechadeshabilitacion = ($valor == '0000-00-00') ?
            'null' : $valor;
    }


    /**
     * Ajusta formulario generado.
     *
     * @param object &$form      Formulario HTML_QuickForm
     * @param object &$formbuilder Generador DataObject_FormBuilder
     *
     * @return void
     */
    function postGenerateForm(&$form, &$formbuilder)
    {
        parent::postGenerateForm($form, $formbuilder);
    }

    /**
     * Identificacion de registro 'SIN INFORMACIÓN'
     *
     * @return int Id.
     */
    static function idSinInfo()
    {
        return 35;
    }

    function valorRelato()
    {
        return $this->id;
    }

}

?>
