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
 * Definicion para la tabla departamento.
 */
require_once 'DataObjects/Basica.php';

/**
 * Definicion para la tabla departamento.
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Departamento extends DataObjects_Basica
{
    var $__table = 'departamento';                    // table name
    var $id_pais;
    var $latitud;
    var $longitud;

    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();
        $this->nom_tabla = _('Departamento');
        $this->fb_fieldLabels = array(
            'id_pais' => _('País'),
            'nombre' => _('Nombre'),
            'latitud'=> _('Latitud'),
            'longitud'=> _('Longitud'),
            'fechacreacion' => _('Fecha de Creación'),
            'fechadeshabilitacion' => _('Fecha de Deshabilitación'),
        );

    }

    var $fb_linkDisplayFields = array('nombre', 'id_pais');

    var $fb_preDefOrder = array(
        'id',
        'id_pais',
        'nombre',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsToRender = array(
        'id_pais',
        'nombre',
        'fechacreacion',
        'fechadeshabilitacion',
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

    var $fb_linkDisplayLevel = 2;

}

?>
