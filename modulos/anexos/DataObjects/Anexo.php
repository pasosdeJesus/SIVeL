<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
/**
 * Objeto de tabla anexo
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: Anexo.php,v 1.8.2.1 2011/09/14 14:56:18 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

require_once 'DB/DataObject.php';

/**
 * Definicion para la tabla anexo
 * Ver documentaci�n de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Anexo extends DB_DataObject_SIVeL
{
    var $__table = 'anexo';                    // table name
    var $id;
    var $id_caso;
    var $fecha;                           // date(4)
    var $descripcion;                     // varchar(-1)
    var $archivo;                        // varchar(-1)
    var $id_prensa;
    var $fecha_prensa;
    var $id_fuente_directa;

    var $nom_tabla = "Anexo";

    var $fb_textFields = array('descripcion');
    var $fb_hidePrimaryKey = true;
    var $fb_fieldsToRender = array('fecha', 'descripcion');
    var $fb_addFormHeader = false;
    var $fb_fieldLabels = array(
        'fecha' => 'Fecha',
        'descripcion' => 'Descripci�n'
    );

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

        $e =& $form->getElement('descripcion');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setCols(75);
            $e->setRows(3);
        }

        $e =& $form->getElement('fecha');
        if (isset($e) && !PEAR::isError($e)) {
            $e->_options['language'] = 'es';
            $e->_options['format'] = 'd-M-Y';
            $e->_options['minYear']=1990;
        }

        if (!isset($this->id)) {
            $form->addElement('file', 'archivo_sel', 'Archivo');
        }
    }

}

?>
