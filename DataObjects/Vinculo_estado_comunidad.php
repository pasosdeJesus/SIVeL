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
 * @version   CVS: $Id: Vinculo_estado_comunidad.php,v 1.16.2.2 2011/09/14 14:56:18 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

/**
 * Definicion para la tabla vinculo_estado_comunidad.
 */
require_once 'DB_DataObject_SIVeL.php';

/**
 * Definicion para la tabla vinculo_estado_comunidad.
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
class DataObjects_Vinculo_estado_comunidad extends DB_DataObject_SIVeL
{
    // START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'vinculo_estado_comunidad';                // table name
    var $id_vinculo_estado;                  // int4(4)  multiple_key
    var $id_grupoper;                  // int4(4)  multiple_key
    var $id_caso;                  // int4(4)  multiple_key

    /* the code above is auto generated do not remove the tag below */
    // END_AUTOCODE

    var $fb_preDefOrder = array('id_vinculo_estado');
    var $fb_fieldsToRender = array('id_vinculo_estado');
    var $fb_addFormHeader = false;
    var $fb_excludeFromAutoRules = array('id_vinculo_estado');
    var $fb_fieldLabels = array(
        'id_vinculo_estado' => 'Vinculo Estado'
    );
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
        case 'id_vinculo_estado':
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

    /*    $d = objeto_tabla('caso');
        $db =& $d->getDatabaseConnection();
        sin_error_pear($db);
        $p =  htmlentities_array(
            $db->getAssoc("SELECT  id, nombre FROM vinculo_estado " .
                "ORDER BY id"
            )
        );
        sin_error_pear($p);
        print_r($p);
        $v =& HTML_QuickForm::createElement('select', 'id_vinculo_estado',
            'Vinculo Estado: ', array());
        $options= array('' => '') + $p;
        $v->loadArray($options);
        $this->fb_preDefElements = array('id_vinculo_estado' => $v); */
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
        $sel =& $form->getElement('id_vinculo_estado');
        if (isset($sel) && !PEAR::isError($sel)) {
            $sel->setMultiple(true);
            $sel->setSize(5);
/*            if (isset($GLOBALS['etiqueta']['vinculo_estado'])) {
                $sel->setLabel($GLOBALS['etiqueta']['vinculo_estado']);
} */
        }
        $form->removeElement('id_caso');

    }

}

?>
