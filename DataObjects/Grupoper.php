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
 * Definicion para la tabla grupoper
 */
require_once 'DB_DataObject_SIVeL.php';

/**
 * Definicion para la tabla grupoper.
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Grupoper extends DB_DataObject_SIVeL
{

    var $__table = 'grupoper';                         // table name
    var $id;                              // int4(4)  not_null primary_key
    var $nombre;                          // varchar(-1)  not_null
    var $anotaciones;



    var $fb_preDefOrder = array('nombre', 'anotaciones');

    var $fb_fieldsToRender = array('nombre', 'anotaciones');

    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

        $this->fb_fieldLabels= array(
           'nombre' => _('Nombre'),
           'anotaciones' => _('Anotaciones'),
        );
    }

    var $fb_linkDisplayFields = array('nombre');
    var $fb_addFormHeader = false;
    var $fb_selectAddEmpty = array();
    var $fb_fieldsRequired = array('nombre');
    var $fb_useMutators = true;
    var $fb_hidePrimaryKey = true;

    /**
     * Funcion legada
     * Como ocurria en FormBuilder 0.10, hasta versión 1.121 en el
     * CVS de FormBuilder.php. Cambio sucitado por bug #3469
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
     * Campos que pueden ser SIN INFORMACION y el código correspondiente
     *
     * @return array Arreglo de campos que pueden ser sin información
     */
    static function camposSinInfo()
    {
        return array();
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
        $csin = $this->camposSinInfo();
        foreach ($csin as $c => $v) {
            if (!isset($this->$c)) {
                $this->$c = $csin[$c];
            }
        }
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


        $sel =& $form->getElement('nombre');
        if (isset($sel) && !PEAR::isError($sel)) {
            $sel->setSize(70);
            $sel->setMaxlength(150);
            $sel->updateAttributes(
                array('id' => "nombre")
            );
        }

        $anota =& $form->getElement('anotaciones');
        if (isset($anota) && !PEAR::isError($anota)) {
            $anota->setSize(70);
            $anota->setMaxlength(1000);
        }

        $gr = array();
        $sel =& $form->getElement('id');
//        $sel->freeze();
        $gr[] =& $sel;
        $form->removeElement('id');

        $sel =& $form->getElement('nombre');
        if (isset($GLOBALS['etiqueta']['nombre_grupoper'])) {
            $sel->setLabel($GLOBALS['etiqueta']['nombre_grupoper']);
        }
        $gr[] =& $sel;
        $form->removeElement('nombre');

        $form->removeElement('anotaciones');

        $sel =& $form->createElement(
            'static','','',
            "<a href=\"javascript:abrirBusquedaGrupoper()\">Buscar Grupo</a>"
        );
        $gr[] =& $sel;

        $form->addGroup($gr, 'gid', 'Nombre', '&nbsp;', false);

        $form->addElement($anota);
    }


    /** Convierte registro a relato (arreglo de elementos) que agrega a $ar
     * dad son datos adicionales que pueden requerirse para la conversión.
     */
    function aRelato(&$ar, $dad = array())
    {
        if (isset($dad['id'])) {
            $ar[$dad['id']] = $this->id;
        } else {
            $ar['id'] = $this->id;
        }
        parent::aRelato($ar, $dad);
        return $ar;
    }
}

?>
