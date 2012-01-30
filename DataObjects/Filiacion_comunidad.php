<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
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
 * @version   CVS: $Id: Filiacion_comunidad.php,v 1.14.2.2 2011/09/14 14:56:18 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

/**
 * Definicion para la tabla filiacion_comunidad.
 */
require_once 'DB_DataObject_SIVeL.php';

/**
 * Definicion para la tabla filiacion_comunidad.
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Filiacion_comunidad extends DB_DataObject_SIVeL
{
    // START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'filiacion_comunidad';             // table name
    var $id_filiacion;                    // int4(4)  multiple_key
    var $id_grupoper;                  // int4(4)  multiple_key
    var $id_caso;                  // int4(4)  multiple_key

    /* the code above is auto generated do not remove the tag below */
    // END_AUTOCODE


    var $fb_preDefOrder = array('id_filiacion');
    var $fb_fieldsToRender = array('id_filiacion');
    var $fb_addFormHeader = false;
    var $fb_excludeFromAutoRules = array('id_filiacion');
    var $fb_fieldLabels = array(
        'id_filiacion' => 'Filiacion Politica'
    );
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
        if ($field == 'id_antecedente') {
            $q = 'id IN (SELECT id_antecedente FROM ' .
                'antecedente_caso WHERE id_caso=\'' .
                $_SESSION['basicos_id'] . '\')';
            $opts->whereAdd($q);
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
        $sel =& $form->getElement('id_filiacion');
        if (isset($sel) && !PEAR::isError($sel)) {
            $sel->setMultiple(true);
            $sel->setSize(5);
/*            if (isset($GLOBALS['etiqueta']['filiacion'])) {
                $sel->setLabel($GLOBALS['etiqueta']['filiacion']);
} */
        }

        $form->removeElement('id_grupoper');
        $form->removeElement('id_caso');
    }

}

?>
