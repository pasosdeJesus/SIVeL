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
 * Definicion para la tabla antecedente_caso.
 */
require_once 'DB_DataObject_SIVeL.php';

/**
 * Definicion para la tabla antecedente_caso.
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Antecedente_caso extends DB_DataObject_SIVeL
{

    var $__table = 'antecedente_caso';                // table name
    var $id_antecedente;                  // int4(4)  nn pk mk
    var $id_caso;                         // int4(4)  nn pk multiple_key


    var $fb_preDefOrder = array('id_antecedente');
    var $fb_fieldsToRender = array('id_antecedente');
    var $fb_addFormHeader = false;
    var $fb_excludeFromAutoRules = array('id_antecedente');
    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        $this->fb_fieldLabels= array(
           'id_antecedente' => _('Antecedente'),
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
        if ($field = 'id_antecedente') {
            $opts->whereAdd('fechadeshabilitacion IS NULL');
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
        $this->fb_preDefElements = array('id_caso' =>
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
        $e =& $form->getElement('id_antecedente');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setSize(5);
            $e->setMultiple(true);
            if (isset($GLOBALS['etiqueta']['antecedente'])) {
                $e->setLabel($GLOBALS['etiqueta']['antecedente']);
            }
        }

        $form->removeElement('id_caso');
    }

}

?>
