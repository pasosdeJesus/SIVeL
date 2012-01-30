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
 * @copyright 2009 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: Relacion_personas.php,v 1.14.2.2 2011/09/14 14:56:18 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

/**
 * Definicion  para la tabla relacion_personas
 */
require_once 'DB_DataObject_SIVeL.php';
require_once 'HTML/QuickForm/Action.php';


/**
 * Acción que responde al botor Agregar Familiar
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class AgregarFamiliar extends HTML_QuickForm_Action
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
        if ($page->procesa($page->_submitValues, true)) {
            $page->_submitValues = array();
            $page->_defaultValues = array();
            $page->nullVar();
        }
        $page->handle('display');
    }
}



/**
 * Definicion para la tabla relacion_personas
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Relacion_personas extends DB_DataObject_SIVeL
{
    // START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'relacion_personas';         // table name
    var $id_persona1;                           // int4(4)  not_null primary_key
    var $id_persona2;                           // int4(4)  not_null primary_key
    var $id_tipo;                              // varchar(-1)  not_null
    var $observaciones;                    // varchar(-1)  not_null



    /* the code above is auto generated do not remove the tag below */
    // END_AUTOCODE

    var $fb_preDefOrder = array('id_tipo', 'observaciones');
    var $fb_fieldsToRender = array('id_tipo', 'observaciones');
    var $fb_addFormHeader = false;
    var $fb_selectAddEmpty = array();
    var $fb_fieldsRequired = array('id_tipo');
    var $fb_hidePrimaryKey = array('id_tipo');
    var $fb_fieldLabels = array();

    /**
     * Campos que pueden ser SIN INFORMACION y el código correspondiente
     *
     * @return array Arreglo de campos que pueden ser sin información
     */
    static function camposSinInfo()
    {
        return array();
    }

    /**
     * Prepara antes de generar formulario.
     *
     * @param object &$form Generador DataObject_FormBuilder
     *
     * @return void
     */
    function preGenerateForm(&$formbuilder)
    {
        $formbuilder->enumOptionsCallback = array($this, "enumCallback");
        $csin = $this->camposSinInfo();
        foreach ($csin as $c => $v) {
            if (!isset($this->$c)) {
                $this->$c = $csin[$c];
            }
        }
        $f = array();
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
/*        foreach ($this->fb_fieldLabels as $c => $et) {
            $e =& $form->getElement($c);
            if (isset($e) && !PEAR::isError($e)
                && isset($GLOBALS['etiqueta'][$c])
            ) {
                    $e->setLabel($GLOBALS['etiqueta'][$c]);
                }
} */

        $form->removeElement('observaciones');
        $t = '<table id="tablaRelaciones" width="100%"><thead><tr><th>Nombres</th><th>Apellidos</th>' .
            '<Th>Tipo</th><th>Observaciones</th><th>Casos</th></tr></thead><tbody>';
        $p = clone $formbuilder->_do;
        $db = $p->getDatabaseConnection();
        $p->id_persona2=null;
        $p->id_tipo = null;
        $p->observaciones = null;
        $p->find();
        while ($p->id_persona1!=null && $p->fetch()) {
            $dp = $p->getLink('id_persona2');
            $dt = $p->getLink('id_tipo');
            $comovic = "";
            $comofam = "";
            enlaces_casos_persona(
                $db, $_SESSION['basicos_id'], 
                $dp->id, $comovic, $comofam
            );
            if ($comovic != '' && $comofam != '') {
                $comofam = ", " . $comofam;
            }
            $t .= '<tr><td>' . $dp->nombres.'</td>' .
                '<td>' . $dp->apellidos.'</td>' .
                '<td>' . substr($dt->nombre, 0, 6) .'</td>' .
                '<td>' . $p->observaciones.'</td>' .
                '<td>' . $comovic . $comofam . '</td>' .
                '<td><a href="'.$_SERVER['PHP_SELF'] . '?eliminarel=' .
                $p->id_persona1.":".$p->id_persona2.":".$p->id_tipo.
                '">Eliminar</a></td>';
        }
        $t .= '</tbody></table>';
        $sel =& $form->addElement('static', 'familiares', 'Familiares', $t);
        $form->removeElement('id_persona1');

        $fm = array();
        $sel =& $form->createElement('text', 'fnombres', 'fnombres');
        $sel->updateAttributes( array('id' => "nombres-relacionado"));
        $sel->setSize(10);
        $fm[] =& $sel;
        $sel =& $form->createElement('text', 'fapellidos', 'fapellidos');
        $sel->updateAttributes( array('id' => "apellidos-relacionado"));
        $sel->setSize(10);
        $fm[] =& $sel;
        $sel =& $form->createElement('select', 'ftipo', 'ftipo', array());
        $sel->loadArray(
            htmlentities_array(
                $db->getAssoc("SELECT id, nombre FROM tipo_relacion")
            )
        );
        $fm[] =& $sel;
        $sel =& $form->createElement(
            'text', 'fobservaciones',
            'fobservaciones'
        );
        $sel->setSize(10);
        $fm[] =& $sel;
        $form->addAction(
            'agregarFamiliar',
            new AgregarFamiliar()
        );
        $sel =& $form->createElement(
            'static','','',
            "<a href=\"javascript:abrirBusquedaPersona('relacionado')\">" .
            "Buscar</a>"
        );
        $fm[] =& $sel;

        $sel =& $form->createElement(
            'submit',
            $form->getButtonName('agregarFamiliar'),'Añadir'
        );
        $fm[] =& $sel;

        $form->addGroup(
            $fm, 'familiar', '',
            '&nbsp;', false
        );

    }

}

?>
