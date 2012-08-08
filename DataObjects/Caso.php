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
 * Definicion  para la tabla caso.
 */
require_once 'DB_DataObject_SIVeL.php';
require_once 'Intervalo.php';
require_once 'aut.php';
require_once $_SESSION['dirsitio'] . '/conf.php';


/**
 * Definicion  para la tabla caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see DB_DataObject_SIVe
 */
class DataObjects_Caso extends DB_DataObject_SIVeL
{

    var $__table = 'caso';                            // table name
    var $id;                              // int4(4)  not_null primary_key
    var $titulo;                          // varchar(-1)
    var $fecha;                           // date(4)  not_null
    var $hora;                            // varchar(-1)
    var $duracion;                        // varchar(-1)
    var $memo;                            // text(-1)  not_null
    var $gr_confiabilidad;                // varchar(-1)
    var $gr_esclarecimiento;              // varchar(-1)
    var $gr_impunidad;                    // varchar(-1)
    var $gr_informacion;                  // varchar(-1)
    var $bienes;                          // text(-1)
    var $id_intervalo;                    // int4(4)


    /**
     * Campos por presentar en areas de texto.
     * Ver detalle de propiedades posibles para DB_DataObject_FormBuilder
     * en {@link http://pear.reversefold.com/dokuwiki/doku.php? \
     * id = pear:db_dataobject_formbuilder:options_summary}
     */
    var $fb_textFields = array('memo', 'bienes');

    /**
    * Campos select a los cuales agregar un registro vacío (NULL).
    */
    var $fb_selectAddEmpty = array();

    /**
    * Deshabilita encabezado del formulario.
    */
    var $fb_addFormHeader = false;

    /**
    * Esconde llave primaria.
    */
    var $fb_hidePrimaryKey = true;

    /**
    * Orden de campos.
    */
    var $fb_preDefOrder = array('titulo', 'fecha', 'hora',
        'duracion', 'id_intervalo'
    );

    /**
     * Campos por presentar.
     */
    var $fb_fieldsToRender = array('titulo', 'fecha', 'hora',
        'duracion', 'id_intervalo'
    );

    /**
     * No marcar campos requeridos
     **/
    var $fb_excludeFromAutoRules = array('fecha', 'id_intervalo',
        'memo'
    );

    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

    /**
     * Nombres por presentar para cada campo.
     */
        $this->fb_fieldLabels= array(
           'titulo' => _('Nombre'),
           'fecha' => _('Fecha'),
           'hora' => _('Hora'),
           'duracion' => _('Duracion'),
           'memo' => _('Memo'),
           'gr_confiabilidad' => _('Gr. Confiabilidad Fuente'),
           'gr_esclarecimiento' => _('Gr.Esclarecimiento'),
           'gr_impunidad' => _('Gr. Impunidad'),
           'gr_informacion' => _('Gr. Informacion'),
           'bienes' => _('Bienes Afectados'),
           'id_intervalo' => _('Intervalo'),
        );
    }


    /**
    * Campos que deben presentarse como SELECTs con opciones
    * controladas desde esta clase.
    */
    var $fb_enumFields = array('gr_confiabilidad',
        'gr_esclarecimiento', 'gr_impunidad', 'gr_informacion'
    );
        /* Opción 'S' también usada en PagBasicos::datosBusqueda */

    /**
    * Posibles opciones de los SELECTs de los campos indicados en
    * fb_enumFields.
    */
    var $es_enumOptions = array('gr_confiabilidad' => array('' => '',
        /* Cada llave debe ser de 5 caracteres o FormBuilder/QuickForm (?) no
           pueden */
            'Alta '=>'Alta',
            'Media'=>'Media',
            'Baja '=>'Baja'
    ),
        'gr_esclarecimiento' => array('' => '',
            'Alto ' => 'Alto',
            'Medio' => 'Medio',
            'Bajo ' => 'Bajo'
        ),
        'gr_impunidad' => array('' => '',
            'Nula ' => 'Nula',
            'Parc ' => 'Parcial',
            'Total' => 'Total'
        ),
        'gr_informacion' => array('' => '',
            'Parc ' => 'Parcial',
            'Total' => 'Total'
        )
    );

    /**
    * Indica si deben usarse o no códigos Sin Información en caso de
    * campos vacíos que puedan requerirlos.
    */
    var $defSinInf = true;


    /**
     * Opciones para un campo fecha.
     *
     * @param string &$nomCampo Nombre del campo fecha
     *
     * @return Opciones fecha
     */
    function dateOptions($nomCampo)
    {
	return parent::dateOptions($nomCampo);
    }


    /**
     * Requerido por versiones de FormBuilder de 0.10, hasta versión 1.121 en
     * el CVS de FormBuilder.php.
     * Cambio sucitado por bug #3469.
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
        $formbuilder->enumOptionsCallback = array($this,
            "enumCallback"
        );
        if (!isset($this->id_intervalo)) {
            if ($this->defSinInf) {
                $this->id_intervalo = DataObjects_Intervalo::idSinInfo();
            } else {
                $this->id_intervalo = '';
            }
        }
    }

    /**
     * Función llamada después de que FormBuilder genera formulario.
     *
     * @param object &$form        HTML_QuickForm Formulario
     * @param object &$formbuilder Generador
     *
     * @return void
     */
    function postGenerateForm(&$form, &$formbuilder)
    {
        parent::postGenerateForm($form, $formbuilder);
        $form->removeElement('id');
        $e =& $form->getElement('titulo');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setSize(55);
            $e->setMaxlength(50);
/*            if (isset($GLOBALS['etiqueta']['titulo'])) {
                $e->setLabel($GLOBALS['etiqueta']['titulo']);
}*/

        }

        $e =& $form->getElement('fecha');
        if (isset($e) && !PEAR::isError($e)) {
            if (!isset($this->fecha) || $this->fecha== '') {
                $e->_elements[0]->setValue('');
                $e->_elements[1]->setValue('');
                $e->_elements[2]->setValue('');
            }
        }

        $e =& $form->getElement('hora');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setSize(10);
            $e->setMaxlength(10);
            /*if (isset($GLOBALS['etiqueta']['hora'])) {
                $e->setLabel($GLOBALS['etiqueta']['hora']);
            }*/
        }

        $e =& $form->getElement('duracion');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setSize(10);
            $e->setMaxlength(10);
            /*if (isset($GLOBALS['etiqueta']['duracion'])) {
                $e->setLabel($GLOBALS['etiqueta']['duracion']);
            } */
        }

        $e =& $form->getElement('memo');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setCols(75);
            $e->setRows(18);
            /*if (isset($GLOBALS['etiqueta']['memo'])) {
                $e->setLabel($GLOBALS['etiqueta']['memo']);
            } */
        }

/*        $e =& $form->getElement('gr_confiabilidad');
        if (isset($e) && !PEAR::isError($e)
            && isset($GLOBALS['etiqueta']['gr_confiabilidad'])
        ) {
                $e->setLabel($GLOBALS['etiqueta']['gr_confiabilidad']);
        }

        $e =& $form->getElement('gr_esclarecimiento');
        if (isset($e) && !PEAR::isError($e)
            && isset($GLOBALS['etiqueta']['gr_esclarecimiento'])
        ) {
                $e->setLabel($GLOBALS['etiqueta']['gr_esclarecimiento']);
        }

        $e =& $form->getElement('gr_impunidad');
        if (isset($e) && !PEAR::isError($e)
            && isset($GLOBALS['etiqueta']['gr_impunidad'])
        ) {
                $e->setLabel($GLOBALS['etiqueta']['gr_impunidad']);
        }

        $e =& $form->getElement('gr_informacion');
        if (isset($e) && !PEAR::isError($e)
            && isset($GLOBALS['etiqueta']['gr_informacion'])
        ) {
            $e->setLabel($GLOBALS['etiqueta']['gr_informacion']);
        }

        $e =& $form->getElement('id_intervalo');
        if (isset($e) && !PEAR::isError($e)
            && $e->getType() == 'select'
        ) {
            $e =& $form->getElement('id_intervalo');
            if (isset($GLOBALS['etiqueta']['intervalo'])) {
                $e->setLabel($GLOBALS['etiqueta']['intervalo']);
            }
            $e->_options = htmlentities_array($e->_options);
        }
 */
        $e =& $form->getElement('bienes');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setCols(70);
            $e->setRows(1);
/*            if (isset($GLOBALS['etiqueta']['bienes'])) {
                $e->setLabel($GLOBALS['etiqueta']['bienes']);
} */
        }

    }

    /**
     * Convierte registro a relato (arreglo de elementos) que agrega a $ar
     *
     * @param object &$ar   Arreglo de elementos
     * @param object &$dad  Datos adicionales para conversión
     *
     * @return void
     */
    function aRelato(&$ar, $dad = array())
    {
        parent::aRelato($ar, $dad);
//        $ar['hechos'] = $this->memo;
//        $ar['hora'] = $this->hora;
//        $ar['duracion'] = $this->duracion;
        return $ar;
    }

}

?>
