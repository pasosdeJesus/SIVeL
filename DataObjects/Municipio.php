<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
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
 * @version   CVS: $Id: Municipio.php,v 1.12.2.1 2011/09/14 14:56:18 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

/**
 * Definicion para la tabla municipio.
 */

require_once 'DataObjects/Basica.php';

/**
 * Definicion para la tabla municipio.
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Municipio extends DataObjects_Basica
{
    // START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'municipio';                       // table name
    var $id_departamento;                 // int4(4)  multiple_key

    var $nom_tabla = 'Municipio';

    var $fb_linkDisplayFields = array('nombre', 'id_departamento');
    var $fb_preDefOrder = array(
        'id_departamento',
        'nombre',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsToRender = array(
        'id_departamento',
        'nombre',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldLabels = array(
        'id_departamento' => 'Departamento',
        'nombre' => 'Nombre',
        'fechacreacion' => 'Fecha de Creación',
        'fechadeshabilitacion' => 'Fecha de Deshabilitación',
    );

    var $fb_hidePrimaryKey = false;

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
        $form->removeElement('id');
    }


}
?>
