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
 * Definicion para la tabla caso_presponsable.
 */
require_once 'DB_DataObject_SIVeL.php';
require_once "Presponsable.php";

/**
 * Definicion para la tabla caso_presponsable.
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Caso_presponsable extends DB_DataObject_SIVeL
{

    var $__table = 'caso_presponsable';     // table name
    var $id_caso;                         // int4(4)  multiple_key
    var $id_p_responsable;                // int4(4)  multiple_key
    var $tipo;                            // int4(4)  not_null
    var $bloque;                          // varchar(-1)
    var $frente;                          // varchar(-1)
    var $division;                        // varchar(-1)
    var $brigada;                         // varchar(-1)
    var $batallon;                        // varchar(-1)
    var $otro;                            // varchar(-1)
    var $id;                              // int4(4)  multiple_key

    var $fb_preDefOrder = array('id_p_responsable',
        'tipo', 'bloque', 'frente', 'division', 'brigada', 'batallon',
        'otro'
    );
    var $fb_fieldsToRender = array('id_p_responsable',
        'tipo', 'bloque', 'frente', 'division', 'brigada', 'batallon',
        'otro'
    );
    var $fb_enumFields = array('tipo');
    var $es_enumOptions = array('tipo' => array(0 => 'A',
        1 => 'B', 2=> 'C'
    )
    );
    var $fb_addFormHeader = false;
    var $fb_excludeFromAutoRules = array('id_p_responsable', 'tipo');

    function __construct()
    {
        parent::__construct();

        $this->fb_fieldLabels = array (
            'id_p_responsable' => _('Presunto Responsable'),
            'tipo' => _('Bando'),
            'bloque' => _('Bloque'),
            'frente' => _('Frente'),
            'division' => _('División'),
            'brigada' => _('Brigada'),
            'batallon' => _('Batallón'),
            'otro' => _('Otro')
        );
    }
    var $fb_hidePrimaryKey = false;

    /**
     * Funciona legada
     *
     * @param string $table Tabla
     * @param string $key   Llave
     *
     * @return opción enumeada asociada a la llave.
     */
    function enumCallback($table, $key)
    {
        return $this->es_enumOptions[$key];
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

        if (!isset($this->id_p_responsable)) {
            $this->id_p_responsable= '';
        }
        $formbuilder->enumOptionsCallback = array($this,
            "enumCallback"
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
        $e =& $form->getElement('id_p_responsable');
        $e->addOption('', '');

        $sel =& $form->getElement('bloque');
        if (isset($sel) && !PEAR::isError($sel)) {
            $sel->setSize(50);
            $sel->setMaxlength(50);
        }

        $e =& $form->getElement('frente');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setSize(50);
            $e->setMaxlength(50);
        }

        $e =& $form->getElement('brigada');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setSize(50);
            $e->setMaxlength(50);
        }

        $e =& $form->getElement('batallon');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setSize(50);
            $e->setMaxlength(50);
        }
        $e =& $form->getElement('division');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setSize(50);
            $e->setMaxlength(50);
        }

        $sel =& $form->getElement('otro');
        if (isset($sel) && !PEAR::isError($sel)) {
            $sel->setSize(50);
            $sel->setMaxlength(50);
        }
        $form->removeElement('id_caso');
    }

    /**
     * Convierte registro a relato (arreglo de elementos) que agrega a $ar
     *
     * @param array &$ar Arreglo
     * @param array $dad Datos adicionales que pueden requerirse en conv.
     *
     * @return array Arreglo de elementos. El mismo $ar.
     */
    function aRelato(&$ar, $dad = array())
    {
        parent::aRelato($ar, $dad);

        $pr = $this->getLink('id_p_responsable');
        $ar['nombre'] = $pr->nombre;

        return $ar;
    }

}

?>
