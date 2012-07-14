<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Definición de objeto tabla etiquetacaso
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

require_once 'DB/DataObject.php';
require_once 'HTML/QuickForm/Action.php';


/**
 * Acción que responde al boton Agregar Etiqueta
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class AgregarEtiqueta extends HTML_QuickForm_Action
{
    /**
     *
     * @param object &$page      Página
     * @param string $actionName Acción
     *
     * @return void
     */
    function perform(&$page, $actionName)
    {
        if ($page->procesa($page->_submitValues, true)) {
            $page->_submitValues = array();
            $page->_defaultValues = array();
     //       $page->nullVar();
        }
        $page->handle('display');
    }
}

/**
 * Acción que responde al boton Eliminar
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class EliminaEst extends HTML_QuickForm_Action
{

    /**
     * Ejecuta acción
     *
     * @param object &$page      Página
     * @param string $actionName Acción
     *
     * @return void
     */
    function perform(&$page, $actionName)
    {
    }
}


/**
 * Definicion para la tabla etiquetacaso
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Etiquetacaso extends DB_DataObject_SIVeL
{
    var $__table = 'etiquetacaso';                         // table name
    var $id_caso;                          // int4(4)  not_null primary_key
    var $id_etiqueta;                        // int4(4)  not_null primary_key
    var $id_funcionario;                   // varchar(-1)  not_null
    var $fecha;                            // date
    var $observaciones;                    // varchar(-1)  not_null

    var $fb_preDefOrder = array();
    var $fb_fieldsToRender = array();
    var $fb_addFormHeader = false;
    var $fb_selectAddEmpty = array();
    var $fb_hidePrimaryKey = true;

    var $fb_fieldLabels = array(
        'fecha' => 'Fecha',
        'observaciones' => 'Observaciones',
        'id_funcionario' => 'Funcionario',
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
        $t = '<table width="100%"><tr><th>Fecha</th><th>Etiqueta</th>' .
            '<th>Observaciones</th><th>Funcionario</th></tr>';
        $p = clone $formbuilder->_do;
        $db = $p->getDatabaseConnection();
        $p->id_caso = $_SESSION['basicos_id'];
        $p->id_etiqueta = null;
        $p->id_funcionario = null;
        $p->fecha = null;
        $p->observaciones = null;
        $p->orderby('fecha');
        $p->find();
        while ($p->fetch() && $p->id_caso == $_SESSION['basicos_id'] ) {
            $dp = $p->getLink('id_etiqueta');
            $fn = $p->getLink('id_funcionario');
            $n = 'fobs_' . (int)$p->id_caso . "_" 
                . (int)$p->id_etiqueta . "_" 
                . (int)$p->id_funcionario . "_" . $p->fecha;
            $t .= '<tr><td>' . $p->fecha . '</td><td>'
                . htmlentities($dp->nombre) . '</td>'
                . '<td><textarea name="' . $n . '" cols="20" rows="3">'
                . htmlentities($p->observaciones) . '</textarea></td><td>' 
                . $fn->nombre . '</td><td><a href="' 
                . htmlspecialchars($_SERVER['PHP_SELF']) . '?eliminaest='
                . (int)$p->id_caso . ":" . (int)$p->id_etiqueta . ":"
                . (int)$p->id_funcionario. ":" . $p->fecha 
                . '">Eliminar</a></td>';
        }
        $t .= '</table>';
        $sel =& $form->addElement('static', null, 'Etiquetas', $t);
        $form->removeElement('id_caso');
        $form->removeElement('observaciones');
        $form->removeElement('fecha');

        $fm = array();
        $sel =& $form->createElement(
            'static', 'ffecha', 'ffecha',
            date('Y-m-d')
        );
        $fm[] =& $sel;
        $sel =& $form->createElement('select', 'fetiqueta', 'fetiqueta', array());
        $sel->loadArray(
            htmlentities_array(
                $db->getAssoc("SELECT id, nombre FROM etiqueta ORDER BY 2"))
            );
        $fm[] =& $sel;
        $sel =& $form->createElement(
            'textarea', 'fobservaciones',
            'fobservaciones'
        );
        $sel->setCols(20);
        $sel->setRows(3);
        $fm[] =& $sel;
        $form->addAction(
            'agregarEtiqueta',
            new AgregarEtiqueta()
        );
        $sel =& $form->createElement(
            'submit',
            $form->getButtonName('agregarEtiqueta'),'Añadir'
        );
        $fm[] =& $sel;

        $form->addGroup(
            $fm, 'etiqueta', 'Nueva',
            '&nbsp;', false
        );
    }
}

?>
