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
 * @version   CVS: $Id: Antecedente.php,v 1.12.2.2 2011/09/14 14:56:18 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

/**
 * Definicion para la tabla antecedente.
 */
require_once 'DataObjects/Basica.php';

/**
 * Definicion para la tabla antecedente.
 * Ver documentación de DataObjects_Basica.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Basica
 */
class DataObjects_Antecedente extends DataObjects_Basica
{
    var $__table = 'antecedente';                     // table name
    var $id;                              // int4(4)  not_null primary_key
    var $nombre;                          // varchar(-1)  not_null
    var $fechacreacion;                  // date(4)  not_null
    var $fechadeshabilitacion;           // date(4)

    var $fb_linkDisplayFields = array('nombre');
    var $fb_select_display_field = 'nombre';
    var $fb_hidePrimaryKey = true;
    var $fb_selectAddEmpty = array('fechadeshabilitacion');

    var $nom_tabla = 'Antecedentes';

    /**
     * Pone valor nulo
     *
     * @param string $valor Valor
     *
     * @return Nuevo valor
     */
    function setfechadeshabilitacion($valor)
    {
        $this->fechadeshabilitacion = ($valor == '0000-00-00') ? 'null' : $valor;
    }

    /**
     * Opciones de fecha para un campo
     *
     * @param string &$field campo
     *
     * @return arreglo de opciones
     */
    function dateOptions(&$field)
    {
        return array('language' => 'es',
        'format' => 'dMY',
        'minYear' => $GLOBALS['anio_min'],
        'maxYear' => 2025
        );
    }

    /**
     * Ajusta formulario generado.
     *
     * @param object &$form        Formulario
     * @param object &$formbuilder Instancia de DataObject_FormBuilder
     *
     * @return void
     */
    function postGenerateForm(&$form, &$formbuilder)
    {
        parent::postGenerateForm($form, $formbuilder);
/*        $h =& $form->getElement('__header__');
        if (PEAR::isError($h)) {
            $h =& $form->getElement(null);
        }
        $h->setValue($this->nom_tabla); */
/*        $f =& $form->getElement('fechadeshabilitacion');
        if (!isset($this->fechadeshabilitacion)
            || $this->fechadeshabilitacion == ''
        ) {
            $f->_elements[0]->setValue('');
            $f->_elements[1]->setValue('');
            $f->_elements[2]->setValue('');
        } */

/*        $sel =& $form->getElement('id_antecedente');
        if (isset($sel) && !PEAR::isError($sel)
            && $sel->getType() == 'select'
        ) {
            if (isset($GLOBALS['etiqueta']['id_antecedente'])) {
                $sel->setLabel($GLOBALS['etiqueta']['id_antecedente']);
            }
            $sel->_options = htmlentities_array($sel->_options);
        } */
    }

}

?>
