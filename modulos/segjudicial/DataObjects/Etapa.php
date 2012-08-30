<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Objeto tabla etapa
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2007 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

require_once 'DataObjects/Basica.php';

/**
 * Definicion para la tabla etapa
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Etapa extends DataObjects_Basica
{
    var $__table = 'etapa';                         // table name
    var $id_tproceso;                              // int4(4)  not_null
    var $observaciones;                        // varchar(-1)  not_null

    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

        $this->nom_tabla = _('Etapa');
        $this->fb_fieldLabels['id_tproceso'] = _('Tipo de Proceso');
    }


    var $fb_preDefOrder = array(
        'id',
        'id_tproceso',
        'nombre',
        'observaciones',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsToRender = array(
        'id_tproceso',
        'nombre',
        'observaciones',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_addFormHeader = false;
    var $fb_fieldsRequired = array(
        'id_tproceso',
        'nombre',
        'fechacreacion',
    );
    var $fb_linkDisplayFields = array(
        'nombre',
        'id_tproceso',
    );

     /**
     * Identificacion de registro 'SIN INFORMACIÓN'
     *
     * @return string Identificación
     */
    static function idSinInfo()
    {
        return 20;
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

        $gr = array();
        $sel =& $form->getElement('nombre');
        $sel->setSize(30);
        $sel->setMaxlength(150);
        $gr[] =& $sel;

        $sel =& $form->getElement('observaciones');
        $sel->setSize(30);
        $sel->setMaxlength(500);
        $gr[] =& $sel;
    }
}

?>
