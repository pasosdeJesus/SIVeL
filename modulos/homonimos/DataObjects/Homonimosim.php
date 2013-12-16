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
 * @copyright 2009 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

/**
 * Definicion  para la tabla homonimosim
 */
require_once 'DB_DataObject_SIVeL.php';
require_once 'HTML/QuickForm/Action.php';


/**
 * Acción que responde al botor Agregar Homonimo
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class AgregarHomonimo extends HTML_QuickForm_Action
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
 * Definicion para la tabla homonimosim
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Homonimosim extends DB_DataObject_SIVeL
{

    var $__table = 'homonimosim';         // table name
    var $persona1;                           // int4(4)  not_null primary_key
    var $persona2;                           // int4(4)  not_null primary_key

    var $fb_preDefOrder = array();
    var $fb_fieldsToRender = array();
    var $fb_addFormHeader = false;
    var $fb_selectAddEmpty = array();
    var $fb_fieldsRequired = array();
    var $fb_hidePrimaryKey = array();

    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

        $this->fb_fieldLabels= array(
        );
    }


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
     * @param object &$formbuilder Generador DataObject_FormBuilder
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

        $t = '<table id="tablaHomonimos" width="100%">' .
            '<thead><tr><th>Nombres</th><th>Apellidos</th>' .
            '<th>Caso</th>' .
            '</tr></thead><tbody>';
        $p = clone $formbuilder->_do;
        $db = $p->getDatabaseConnection();
        $p->persona2=null;
        $p->find();
        while ($p->persona1!=null && $p->fetch()) {
            $dp = $p->getLink('persona2');
            $comovic = "";
            $comofam = "";
            enlaces_casos_persona_html(
                $db, $_SESSION['basicos_id'],
                $dp->id, $comovic, $comofam
            );
            if ($comovic != '' && $comofam != '') {
                $comofam = ", " . $comofam;
            }
            $t .= '<tr><td>' . $dp->nombres.'</td>' .
                '<td>' . $dp->apellidos.'</td>' .
                '<td>' . $dp->id . '</td>' .
                '<td><a href="'.$_SERVER['PHP_SELF'] . '?eliminahom=' .
                $p->persona1.":".$p->persona2
                '">'. _('Eliminar') . '</a></td>';
        }
        $t .= '</tbody></table>';
        $sel =& $form->addElement(
            'static', 'homonimos', _('Homonimos'), $t
        );
        $form->removeElement('persona1');

        $fm = array();
        $sel =& $form->createElement('text', 'fnombres', 'fnombres');
        $sel->updateAttributes(array('id' => "nombres-relacionado"));
        $sel->setSize(10);
        $fm[] =& $sel;
        $sel =& $form->createElement('text', 'fapellidos', 'fapellidos');
        $sel->updateAttributes(array('id' => "apellidos-relacionado"));
        $sel->setSize(10);
        $fm[] =& $sel;
        $sel =& $form->createElement('select', 'ftipo', 'ftipo', array());
        $sel->loadArray(
            htmlentities_array(
                $db->getAssoc("SELECT id, nombre FROM trelacion")
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
            'agregarHomonimo',
            new AgregarHomonimo()
        );
        $sel =& $form->createElement(
            'static', '', '',
            "<a href=\"javascript:abrirBusquedaPersona('relacionado')\">" .
            "Buscar</a>"
        );
        $fm[] =& $sel;

        $sel =& $form->createElement(
            'submit', $form->getButtonName('agregarHomonimo'), 'Añadir'
        );
        $fm[] =& $sel;

        $form->addGroup($fm, 'hominimo', '', '&nbsp;', false);

    }

}

?>
