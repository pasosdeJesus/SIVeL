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
 * Definicion para la tabla ubicacion.
 */
require_once 'DB_DataObject_SIVeL.php';
require_once 'Tsitio.php';

/**
 * Definicion para la tabla ubicacion.
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Ubicacion extends DB_DataObject_SIVeL
{

    var $__table = 'ubicacion';                       // table name
    var $id;                              // int4(4)  not_null primary_key
    var $lugar;                           // varchar(-1)
    var $sitio;                           // varchar(-1)
    var $id_clase;                        // int4(4)
    var $id_municipio;                    // int4(4)
    var $id_departamento;                 // int4(4)
    var $id_tsitio;                   // varchar(-1)
    var $id_caso;                         // varchar(-1)
    var $latitud;
    var $longitud;



    var $fb_linkDisplayFields = array(
        'id_municipio,id_departamento', 'id_departamento', 'lugar'
    );
    var $fb_linkDisplayLevel = 0;
    var $fb_preDefOrder = array(
        'id_departamento', 'id_municipio', 'id_clase',
        'lugar', 'sitio', 'latitud', 'longitud', 'id_tsitio'
    );
    var $fb_fieldsToRender = array(
        'lugar', 'sitio', 'latitud', 'longitud', 'id_tsitio'
    );
    var $fb_addFormHeader = false;
    var $fb_hidePrimaryKey = true;

    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

        $this->fb_fieldLabels= array(
            'id_departamento' => _('Departamento'),
            'id_municipio' => _('Municipio'),
            'id_clase' => _('Centro Poblado'),
            'lugar' => _('Lugar'),
            'sitio' => _('Sitio'),
            'id_tsitio' => _('Tipo de Ubicación'),
            'latitud' => _('Latitud'),
            'longitud' => _('Longitud'),
        );
        if (isset($GLOBALS['etiqueta']['ubicacionlugar'])) {
            $this->fb_fieldLabels['lugar']
                = $GLOBALS['etiqueta']['ubicacionlugar'];
        }
    }

    var $fb_excludeFromAutoRules = array('id_tsitio');

    /**
     * Campos y valores SIN INFORMACION
     *
     * @return array Arreglo con campo y valor SIN INFORMACION
     */
    static function camposSinInfo()
    {
        $a = array(
            'id_tsitio'=> DataObjects_Tsitio::idSinInfo(),
        );
        return $a;
    }

    /**
    * Modifica el valor de la longitud antes de incluirlo en base de datos.
    * Para funcionar con versiones nuevas de DB_DataObject requiere
    * <b>useMutator</b> en <b>true</b>
    *
    * @param string $value Valor recibido de formulario
    *
    * @return Valor para base de datos
    */
    function setlongitud($value)
    {
        $this->longitud = ($value == '') ? 'null' : $value;
    }

    /**
    * Modifica el valor de la latitud antes de incluirlo en base de datos.
    * Para funcionar con versiones nuevas de DB_DataObject requiere
    * <b>useMutator</b> en <b>true</b>
    *
    * @param string $value Valor recibido de formulario
    *
    * @return Valor para base de datos
    */
    function setlatitud($value)
    {
        $this->latitud = ($value == '') ?
            DB_DataObject_Cast::sql('NULL') : $value;
    }

    /**
    * Modifica valor de municipio antes de incluirlo en base de datos.
    * Para funcionar con versiones nuevas de DB_DataObject requiere
    * <b>useMutator</b> en <b>true</b>
    *
    * @param string $value Valor recibido de formulario
    *
    * @return Valor para base de datos
    */
    function setid_municipio($value)
    {
        $this->id_municipio = ($value == '') ?
            DB_DataObject_Cast::sql('NULL') : (int)$value;
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
        parent::preGenerateForm($formbuilder);
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

        //parent::postGenerateForm($form, $formbuilder);

        $e =& $form->getElement('id_tsitio');
        if (!isset($this->id_tsitio)) {
            $this->id_tsitio = DataObjects_Tsitio::idSinInfo();
        }
        $e->setValue($this->id_tsitio);
        $e->_options = htmlentities_array($e->_options);

        $e =& $form->getElement('lugar');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setSize(65);
            $e->setMaxlength(200);
        }
        $e =& $form->getElement('sitio');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setSize(65);
            $e->setMaxlength(200);
        }
        $e =& $form->getElement('id');
        $dep =& $form->createElement(
            'select', 'id_departamento',
            $GLOBALS['etiqueta']['departamento'],
            array()
        );
        $form->insertElementBefore($dep, 'lugar');
        $mun =& $form->createElement(
            'select', 'id_municipio',
            $GLOBALS['etiqueta']['municipio'],
            array()
        );
        $form->insertElementBefore($mun, 'lugar');
        $cla =& $form->createElement(
            'select', 'id_clase',
            $GLOBALS['etiqueta']['clase'],
            array()
        );
        $form->insertElementBefore($cla, 'lugar');

    }


    /**
     * Convierte registro a relato (arreglo de elementos) que agrega a $ar
     *
     * @param object &$ar Arreglo de elementos
     * @param object $dad Datos adicionales para conversión
     *
     * @return void
     */
    function aRelato(&$ar, $dad = array())
    {
        parent::aRelato($ar, $dad);
        $d = '';
        if (isset($this->id_departamento)) {
            $od = $this->getLink('id_departamento');
            $d = $od->nombre;
        }
        $ar['departamento'] = $d;
        $m = '';
        if (isset($this->id_municipio)) {
            $om = objeto_tabla('municipio');
            $om->id_departamento = $this->id_departamento;
            $om->id = $this->id_municipio;
            $om->find();
            if ($om->fetch()) {
                $m = $om->nombre;
            }
        }
        $ar['municipio'] = $m;
        $c = '';
        if (isset($this->id_municipio) && isset($this->id_clase)) {
            $oc = objeto_tabla('clase');
            $oc->id_departamento = $this->id_departamento;
            $oc->id_municipio = $this->id_municipio;
            $oc->id = $this->id_clase;
            $oc->find();
            if ($oc->fetch()) {
                $c = $oc->nombre;
            }
        }
        $ar['centro_poblado'] = $c;
        return $ar;
    }

}

?>
