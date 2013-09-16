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
 * Definicion para la tabla victimacolectiva.
 */
require_once 'DB_DataObject_SIVeL.php';

/**
 * Definicion para la tabla victimacolectiva.
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Victimacolectiva extends DB_DataObject_SIVeL
{

    var $__table = 'victimacolectiva';               // table name
    var $id_grupoper;                     // int4(4)  not_null primary_key
    var $id_caso;                     // int4(4)  not_null primary_key
    var $personasaprox;                  // int4(4)
    var $organizacionarmada;          // int4(4)

    var $fb_preDefOrder = array('personasaprox', 'organizacionarmada', );
    var $fb_fieldsToRender = array('personasaprox',
        'organizacionarmada'
    );
    var $fb_addFormHeader = false;
    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

        $this->fb_fieldLabels= array(
           'organizacionarmada'=> _('Organización Armada Víctima'),
           'personasaprox' => _('Num. Aprox. Personas'),
        );
    }

    var $fb_hidePrimaryKey = true;

    /**
     * Pone un valor en la base diferente al recibido del formulario.
     *
     * @param string $value Valor en formulario
     *
     * @return Valor para BD
     */
    function setpersonasaprox($value)
    {
        $this->personasaprox= ($value == '') ? 'null' : $value;
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
        if (!isset($this->organizacionarmada)) {
            include_once "Presponsable.php";
            $this->organizacionarmada = DataObjects_Presponsable::idSinInfo();
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

        $sel =& $form->getElement('personasaprox');
        if (isset($sel) && !PEAR::isError($sel)) {
            $sel->setSize(5);
        }
        if (isset($this->personasaprox) && $this->personasaprox == 'null') {
            $sel->setValue('');
        }
    }

    /**
     * Cadena para un texto XML que identifica el registro
     *
     * @return string Identificacion
     **/
    function valorRelato()
    {
        return $this->id_grupoper;
    }

    /**
     * Arreglo para ayudar a conversión entre base de datos y relato
     *
     * @return array Cada elemento es tabla->array(etiqueta, campo_bd)
     */
    function tradRelato()
    {
        return array(
            'comunidad_vinculoestado' =>
            array('comunidad_vinculoestado', 'id_vinculoestado'),
            'comunidad_profesion' =>
            array('profesion', 'id_profesion'),
            'antecedente_comunidad' =>
            array('antecedente', 'id_antecedente'),
            'comunidad_filiacion' =>
            array('filiacion', 'id_filiacion'),
            'comunidad_organizacion' =>
            array('organizacion', 'id_organizacion'),
            'comunidad_rangoedad' =>
            array('rangoedad', 'id_rango'),
            'comunidad_rangoedad' =>
            array('rangoedad', 'id_rango'),
            'comunidad_sectorsocial' =>
            array('sectorsocial', 'id_sector'),
        );
    }



}

?>
