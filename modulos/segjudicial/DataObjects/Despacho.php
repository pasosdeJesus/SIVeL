<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Objeto tabla despacho
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
 * Definicion para la tabla despacho
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */

class DataObjects_Despacho extends DataObjects_Basica
{
    var $__table = 'despacho';                         // table name

    var $id_tipo;                              // int4(4)  not_null
    var $observaciones;                        // varchar(-1)  not_null

    var $nom_tabla = 'Despacho de una actuación judicial';

    var $fb_preDefOrder = array(
        'id',
        'id_tipo',
        'nombre',
        'observaciones',
        'fechacreacion',
        'fechadeshabilitacion',
    );

    var $fb_fieldsToRender = array(
        'id_tipo',
        'nombre',
        'observaciones',
        'fechacreacion',
        'fechadeshabilitacion',
    );

    var $fb_addFormHeader = false;
    var $fb_fieldsRequired = array(
        'id_tipo',
        'nombre',
        'fechacreacion'
    );
    var $fb_linkDisplayFields = array(
        'nombre',
        'id_tipo',
    );
    var $fb_fieldLabels = array(
        'id_tipo' => 'Tipo de proceso',
        'nombre' => 'Nombre',
        'observaciones' => 'Observaciones',
        'fechacreacion' => 'Fecha de creación',
        'fechadeshabilitacion' => 'Fecha de deshabilitación',
    );

    /**
     * Identificacion de registro 'SIN INFORMACIÓN'
     *
     * @return string Identificación
     */
    static function idSinInfo()
    {
        return 10;
    }

    /**
     * Ajusta formulario generado.
     *
     * @param object &$form        Formulario HTML_QuickForm
     * @param object &$formbuilder Generador DataObject_FormBuilder
     *
     * @return void
     */
    function postGenerateForm(&$form, &$formbuilder)
    {
        parent::postGenerateForm($form, $formbuilder);

        $gr = array();
        $sel =& $form->getElement('nombre');
        $sel->setSize(30);
        $sel->setMaxlength(150);
        $gr[] =& $sel;

        $sel =& $form->getElement('observaciones');
        $sel->setSize(30);
        $sel->setMaxlength(500);
        $gr[] =& $sel;
    }
}

?>
