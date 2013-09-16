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
 * Definicion para la tabla comunidad_sectorsocial.
 */
require_once 'DB_DataObject_SIVeL.php';

/**
 * Definicion para la tabla comunidad_sectorsocial.
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Comunidad_sectorsocial extends DB_DataObject_SIVeL
{

    var $__table = 'comunidad_sectorsocial';         // table name
    var $id_sectorsocial;                       // int4(4)  multiple_key
    var $id_grupoper;                  // int4(4)  multiple_key
    var $id_caso;                  // int4(4)  multiple_key


    var $fb_preDefOrder = array('id_sectorsocial');
    var $fb_fieldsToRender = array('id_sectorsocial');
    var $fb_addFormHeader = false;
    var $fb_excludeFromAutoRules = array('id_sectorsocial');
    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

        $this->fb_fieldLabels= array(
           'id_sectorsocial' => _('Sector Social'),
        );
    }

    var $fb_hidePrimaryKey = false;


    /**
     * Prepara antes de generar formulario.
     *
     * @param object &$formbuilder Generador DataObject_FormBuilder
     *
     * @return void
     */
    function preGenerateForm(&$formbuilder)
    {
        $this->fb_preDefElements = array('id_grupoper' =>
            HTML_QuickForm::createElement('hidden', 'id_grupoper'),
            'id_caso' =>
            HTML_QuickForm::createElement('hidden', 'id_caso')
        );
    }

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
        switch ($field) {
        case 'id_antecedente':
            $q = 'id IN (SELECT id_antecedente FROM ' .
                'antecedente_caso WHERE id_caso=\'' .
                $_SESSION['basicos_id'] . '\')';
            $opts->whereAdd($q);
            break;
        case 'id_sectorsocial':
            $opts->whereAdd('fechadeshabilitacion IS NULL');
            break;
        }
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
        $sel =& $form->getElement('id_sectorsocial');
        if (isset($sel) && !PEAR::isError($sel)) {
            $sel->setMultiple(true);
            $sel->setSize(5);
            if (isset($GLOBALS['etiqueta']['sectorsocial'])) {
                $sel->setLabel($GLOBALS['etiqueta']['sectorsocial']);
            }
        }
        $form->removeElement('id_grupoper');
        $form->removeElement('id_caso');
    }

}

?>
