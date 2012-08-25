<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Objeto de tabla anexo
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

require_once 'DB/DataObject.php';

/**
 * Definicion para la tabla anexo
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
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
    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

        $this->fb_fieldLabels= array(
           'fecha' => _('Fecha'),
           'descripcion' => _('Descripción'),
           'archivo' => _('Archivo'),
           'id_prensa' => _('Id. Prensa'),
           'id_fuente_directa' => _('Id. Fuente Directa'),
        );
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

        $e =& $form->getElement('descripcion');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setCols(75);
            $e->setRows(3);
        }

        $e =& $form->getElement('fecha');
        if (isset($e) && !PEAR::isError($e)) {
        $e->_options['language'] = isset($_SESSION['LANG'])
            ? $_SESSION['LANG'] : 'es';
            $e->_options['format'] = 'd-M-Y';
            $e->_options['minYear']=1990;
        }

        if (!isset($this->id)) {
            $form->addElement('file', 'archivo_sel', _('Archivo'));
        }
    }

}

?>
