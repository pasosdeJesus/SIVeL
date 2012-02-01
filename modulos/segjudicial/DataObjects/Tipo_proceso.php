<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Objeto tabla tipo_proceso
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: Tipo_proceso.php,v 1.13.2.2 2011/10/22 12:55:08 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

require_once 'DataObjects/Basica.php';

/**
 * Definicion para la tabla tipo_proceso
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Tipo_proceso extends DataObjects_Basica
{
    var $__table = 'tipo_proceso';                         // table name
    var $observaciones;                        // varchar(-1)  not_null

    var $nom_tabla = 'Tipo de proceso';

    var $fb_preDefOrder = array(
        'id',
        'nombre',
        'observaciones',
        'fechacreacion',
        'fechadeshabilitacion',
    );

    var $fb_fieldsToRender = array(
        'nombre',
        'observaciones',
        'fechacreacion',
        'fechadeshabilitacion',
    );

    /**
     * Identificacion de registro 'SIN INFORMACIÓN'
     *
     * @return integer Id del registro SIN INFORMACIÓN
     */
    static function idSinInfo()
    {
        return 1;
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
