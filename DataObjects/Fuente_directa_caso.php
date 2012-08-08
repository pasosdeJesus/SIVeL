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
 * Definicion para la tabla fuente_directa_caso.
 */
require_once 'DB_DataObject_SIVeL.php';
require_once 'aut.php';
require_once $_SESSION['dirsitio'] . '/conf.php';

/**
 * Definicion para la tabla fuente_directa_caso.
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Fuente_directa_caso extends DB_DataObject_SIVeL
{

    var $__table = 'fuente_directa_caso';             // table name
    var $id_caso;                         // int4(4)  multiple_key
    var $id_fuente_directa;               // int4(4)  multiple_key
    var $anotacion;                       // varchar(-1)
    var $fecha;                           // date(4)  multiple_key
    var $ubicacion_fisica;                // varchar(-1)
    var $tipo_fuente;                     // varchar(-1)


    var $fb_addFormHeader = false;
    //var $fb_selectAddEmpty = array('id_fuente_directa');
    var $fb_preDefOrder = array('anotacion', 'fecha',
        'ubicacion_fisica', 'tipo_fuente'
    );
    var $fb_fieldsToRender = array('anotacion', 'fecha',
        'ubicacion_fisica', 'tipo_fuente'
    );
    var $fb_enumFields = array('tipo_fuente');
    var $fb_enumOptions = array('tipo_fuente' =>
        array('Directa', 'Indirecta')
    );
    var $fb_excludeFromAutoRules = array('fecha');
    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

        $this->fb_fieldLabels= array(
           'anotacion' => _('Anotación'),
           'fecha' => _('Fecha'),
           'ubicacion_fisica' => _('Ubicación Física'),
           'tipo_fuente' => _('Tipo de Fuente'),
        );
    }

    var $fb_hidePrimaryKey = false;


    /**
     * Opciones de fecha para un campo
     *
     * @param string $fieldName campo
     *
     * @return arreglo de opciones
     */
    function dateOptions($fieldName)
    {
        $fv = isset($GLOBALS['fechaPuedeSerVacia'])
            && $GLOBALS['fechaPuedeSerVacia'];
        return array('minYear' => $GLOBALS['anio_min'],
            'maxYear' => date('Y'),
            'addEmptyOption' => $fv
        );
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
            HTML_QuickForm::createElement('hidden', 'id_caso'),
            'id_fuente_directa' =>
            HTML_QuickForm::createElement('hidden', 'id_fuente_directa'),
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

        $e =& $form->getElement('ubicacion');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setSize(70);
            $e->setMaxlength(100);
/*            if (isset($GLOBALS['etiqueta']['ubicacion_fuente'])) {
                $e->setLabel($GLOBALS['etiqueta']['ubicacion_fuente']);
} */
        }

        $e =& $form->getElement('fecha');
        if (isset($e) && !PEAR::isError($e)) {
            $e->_options['language'] = isset($_SESSION['LANG']) 
		? $_SESSION['LANG'] : 'es';
            $e->_options['format'] = 'dMY';
            $e->_options['addEmptyOption'] = true;
            $e->_options['minYear'] = $GLOBALS['anio_min'];
            if (isset($GLOBALS['etiqueta']['fecha_fuente'])) {
                $e->setLabel($GLOBALS['etiqueta']['fecha_fuente']);
            }
        }


        $e =& $form->getElement('ubicacion_fisica');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setSize(70);
            $e->setMaxlength(100);
        }

        $e =& $form->getElement('anotacion');
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
        if ($this->id_fuente_directa != null
            && (!isset($valores['id_fuente_directa'])
            || $valores['id_fuente_directa'] == ''
        )
        ) {
            $valores['id_fuente_directa'] = $this->id_fuente_directa;
        }
    }

}

?>
