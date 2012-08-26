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
 * Definicion para la tabla fotra.
 */
require_once 'DB_DataObject_SIVeL.php';

/**
 * Definicion para la tabla fotra.
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Fotra extends DB_DataObject_SIVeL
{

    var $__table = 'fotra';                  // table name
    var $id;                              // int4(4)  not_null primary_key
    var $nombre;                          // varchar(-1)  not_null

    var $fb_linkDisplayFields = array('nombre');
    var $fb_addFormHeader = false;
    var $fb_preDefOrder = array('nombre');
    var $fb_fieldsToRender = array('nombre');
    var $fb_hidePrimaryKey = false;
    var $fb_excludeFromAutoRules = array('nombre');
    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

        $this->fb_fieldLabels= array(
           'nombre' => _('Nombre'),
        );
    }



    /**
     * Prepara antes de generar formulario.
     *
     * @param object &$form Generador DataObject_FormBuilder
     *
     * @return void
     */
    function preGenerateForm(&$form)
    {
        $this->fb_preDefElements = array('id' =>
                     HTML_QuickForm::createElement('hidden', 'id')
        );
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
        $e =& $form->getElement('nombre');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setSize(70);
            $e->setMaxlength(200);
        }
    }

    /**
     * Prepara procesamiento de formulario diligenciado
     *
     * @param array  &$valores   Valores llenados por usuario
     * @param object &$formbuilder Generador DataObject_FormBuilder
     *
     * @return void
     */
    function preProcessForm(&$valores, &$formbuilder)
    {
        if ($this->id != null
            && (!isset($valores['id']) || $valores['id'] == ''))
        {
            $valores['id'] = $this->id;
        }
    }

}

?>
