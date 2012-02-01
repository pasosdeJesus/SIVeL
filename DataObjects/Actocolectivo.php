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
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: Actocolectivo.php,v 1.11.2.2 2011/09/14 14:56:18 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

/**
 * Definicion para la tabla actocolectivo
 */
require_once 'DB_DataObject_SIVeL.php';

/**
 * Definicion para la tabla actocolectivo
 * Ver documentaci�n de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Actocolectivo extends DB_DataObject_SIVeL
{
    // START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'actocolectivo';                      // table name
    var $id_p_responsable;                        // int4(4)  multiple_key
    var $id_categoria;                    // int4(4)  multiple_key
    var $id_grupoper;                 // int4(4)  multiple_key
    var $id_caso;                         // int4(4)  multiple_key

    var $nom_tabla = "Actos";

    /* the code above is auto generated do not remove the tag below */
    // END_AUTOCODE
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
            '<th>P. Responsable</th><th>Categoria</th>' .
            '<th>V�ctima Colectiva</th><th></th></thead><tbody>';
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
                . htmlentities($pp->nombre)
                . "</td><td>" . htmlentities($ca->id_tipo_violencia)
                . (int)$ca->id . " "
                . htmlentities($ca->nombre) . "</td><td>" 
                . htmlentities($vc->nombre) . "</td>" .
                "<td><a href='{$_SERVER['PHP_SELF']}?eliminaactocolectivo=" 
                . (int)$p->id_p_responsable . ":" 
                . (int)$p->id_categoria . ":"
                . (int)$p->id_grupoper
                . "'>Eliminar</a></td>";
        }
        $t .= '</tbody></table>';
        $sel =& $form->addElement('static', 'colectivas', '', $t);


    }

}

?>
