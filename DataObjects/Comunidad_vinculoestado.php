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
 * Definicion para la tabla comunidad_vinculoestado.
 */
require_once 'DB_DataObject_SIVeL.php';

/**
 * Definicion para la tabla comunidad_vinculoestado.
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DB_DataObject_SIVeL
 * Acceso: SÓLO DEFINICIONES
 */
class DataObjects_Comunidad_vinculoestado extends DB_DataObject_SIVeL
{

    var $__table = 'comunidad_vinculoestado';                // table name
    var $id_vinculoestado;                  // int4(4)  multiple_key
    var $id_grupoper;                  // int4(4)  multiple_key
    var $id_caso;                  // int4(4)  multiple_key


    var $fb_preDefOrder = array('id_vinculoestado');
    var $fb_fieldsToRender = array('id_vinculoestado');
    var $fb_addFormHeader = false;
    var $fb_excludeFromAutoRules = array('id_vinculoestado');
    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

        $this->fb_fieldLabels= array(
           'id_vinculoestado' => _('Vínculo con el Estado'),
        );
    }

    var $fb_hidePrimaryKey = false;


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
        case 'id_vinculoestado':
            $opts->whereAdd('fechadeshabilitacion IS NULL');
            break;

        }
    }

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
        $sel =& $form->getElement('id_vinculoestado');
        if (isset($sel) && !PEAR::isError($sel)) {
            $sel->setMultiple(true);
            $sel->setSize(5);
        }
        $form->removeElement('id_caso');

    }

}

?>
