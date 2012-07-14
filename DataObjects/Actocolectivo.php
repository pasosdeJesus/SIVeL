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
 * Definicion para la tabla actocolectivo
 */
require_once 'DB_DataObject_SIVeL.php';

/**
 * Definicion para la tabla actocolectivo
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Actocolectivo extends DB_DataObject_SIVeL
{

    var $__table = 'actocolectivo';                      // table name
    var $id_p_responsable;                        // int4(4)  multiple_key
    var $id_categoria;                    // int4(4)  multiple_key
    var $id_grupoper;                 // int4(4)  multiple_key
    var $id_caso;                         // int4(4)  multiple_key

    var $nom_tabla = "Actos";

    var $fb_preDefOrder = array('id_p_responsable');
    var $fb_fieldsToRender = array('id_p_responsable');
    var $fb_selectAddEmpty = array('id_p_responsable');
    var $fb_addFormHeader = false;
    var $fb_fieldLabels = array(
        'id_p_responsable' => 'Presunto Responsable',
        'id_categoria' => 'Categoria',
        'id_grupoper' => 'Grupo de Personas',
        'id_caso' => 'Caso'
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

        $t = '<table id="tablaActos" width="100%"><thead><tr>' .
            '<th>'. _('P. Responsable') . '</th><th>' . _('Categoria') .
            '</th><th>' . _('Víctima Colectiva') .
            '</th><th></th></thead><tbody>';
        $p = clone $formbuilder->_do;
        $db = $p->getDatabaseConnection();
        $p->id_p_responsable = null;
        $p->id_categoria = null;
        $p->id_grupoper= null;
        $n = $p->find();
        while ($p->id_caso != null && $p->fetch()) {
            $pp =& $p->getLink('id_p_responsable');
            $ca =& $p->getLink('id_categoria');
            $vc =& $p->getLink('id_grupoper');
            $t .= "<tr><td>"
                . htmlentities($pp->nombre, ENT_COMPAT, 'UTF-8')
                . "</td><td>" . htmlentities($ca->id_tipo_violencia, ENT_COMPAT, 'UTF-8')
                . (int)$ca->id . " "
                . htmlentities($ca->nombre, ENT_COMPAT, 'UTF-8') . "</td><td>"
                . htmlentities($vc->nombre, ENT_COMPAT, 'UTF-8') . "</td>" .
                "<td><a href='{$_SERVER['PHP_SELF']}?eliminaactocolectivo="
                . (int)$p->id_p_responsable . ":"
                . (int)$p->id_categoria . ":"
                . (int)$p->id_grupoper
                . "'>" . _("Eliminar") . "</a></td>";
        }
        $t .= '</tbody></table>';
        $sel =& $form->addElement('static', 'colectivas', '', $t);


    }

}

?>
