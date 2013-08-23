<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Objeto asociado a una tabla de la base de datos.
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2013 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

/**
 * Clase base para tablas multiseleccion
 */
require_once 'DB_DataObject_SIVeL.php';


/**
 * Clase base para añadir
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class BaseAgrega extends HTML_QuickForm_Action
{

    var $tabla = '';

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
        $do = objeto_tabla($this->tabla);
        foreach ($do->fb_fieldsToRender as $k) {
            if (isset($_REQUEST['f' . $k])) {
                $page->_submitValues[$k] = $_REQUEST['f' . $k];
                //echo "$k <br>";
            }
        }
        if ($page->procesa($page->_submitValues, $this->tabla)) {
            $page->_submitValues = array();
            $page->_defaultValues = array();
            $page->nullVar();
        }
        $page->handle('display');
    }
}


/**
 * Acción que responde al enlace Eliminar AyudaEstado
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  http://creativecommons.org/licenses/publicdomain/ Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class BaseElimina extends HTML_QuickForm_Action
{

    var $tabla = '';
    var $nomboton = '';

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
        $nb = $page->getButtonName($this->nomboton);
        assert($_REQUEST[$nb] != null);

        $do =& objeto_tabla($this->tabla);
        $a = explode(':', var_escapa($_REQUEST[$nb]));
        foreach ($a as $i => $v) {
            $nc = $do->llaveselimina[$i];
            $do->$nc = $v;
        }

        $do->id_caso = (int)$_SESSION['basicos_id'];
        $do->delete();

        $page->_submitValues = array();
        $page->_defaultValues = array();
        $page->nullVar();
        $page->handle('display');
    }
}

/**
 * Definicion para la tabla ayudaestado_respuesta
 * Ver documentación de DB_DataObject_SIVeL.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DB_DataObject_SIVeL
 */
class DataObjects_Multitabla extends DB_DataObject_SIVeL
{
    var $__table = '';                       

    /** Usada a la izquierda de la tabla */
    var $nom_tabla = '';

    /** 
     * Llaves que son compartidas con la pestaña, 
     * e.g id_caso y fechaatencion
     */
    var $llavescomunes = array();

    /** 
     * Llaves para eliminar
     * e.g fechaatencion e id_ayudaestado
     */
    var $llaveselimina = array();

    /** 
     * Campos asociados a tablas básicas que deben mostrarse como cuadros
     * de selección
     * e.g id_ayudaestado => ayudaestado
     */
    var $camposselect = array();

    /** Nombre del botón para eliminar */
    var $botonelimina = 'eliminaayudaestado';

    /** Nombre del botón para añadir */ 
    var $botonagrega = 'agregaayudaestado';

    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /** Campos por incluir en la tabla */
    var $fb_fieldsToRender = array();
    var $fb_addFormHeader = false;
    var $fb_hidePrimaryKeys = false;

    /**
     * Prepara antes de generar formulario.
     *
     * @param object &$formbuilder Generador DataObject_FormBuilder
     *
     * @return void
     */
    function preGenerateForm(&$formbuilder)
    {
        parent::preGenerateForm($formbuilder);
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

        $t = '<table id="tabla' . $this->__table 
            . '" width="100%"><thead><tr>';
        foreach ($this->fb_fieldsToRender as $c) {
            $t .= "<th>" . $this->fb_fieldLabels[$c] . "</th>";
        }
        $t .= '<th></th></thead><tbody>';
        $p = objeto_tabla($this->__table);
        $db = $p->getDatabaseConnection();
        foreach ($this->fb_fieldLabels as $c => $l) {
            $p->$c = null;
        }
        foreach ($this->llavescomunes as $c) {
            $p->$c = $this->$c;
        }
        $p->find();
        while ($p->$c != null && $p->fetch()) {
            $t .= "<tr> ";
            foreach ($this->fb_fieldsToRender as $c) {
                $t .= "<td>";
                if (isset($this->camposselect[$c])) {
                    $dd = $p->getLink($c);
                    $t .= $dd->nombre;
                } else if (isset($this->fb_booleanFields) 
                    && in_array($c, $this->fb_booleanFields)
                ) {
                    $t .= $p->$c === true || $p->$c === 't' ? "Si" : "No";
                } else {
                    $t .= $p->$c;
                }
                $t .= "</td>";
            }
            $t .= "<td><a href='{$_SERVER['PHP_SELF']}?" 
                . $form->getButtonName($this->botonelimina) 
                . "=";
            $sep = "";
            foreach ($this->llaveselimina as $k) {
                $t .= $sep . $p->$k;
                $sep = ":";
            }
            $t .= "'>Eliminar</a></td></tr>";
        }
        $t .= '</tbody></table>';
        $sel =& $form->addElement(
            'static', 't' . $this->__table, $this->nom_tabla, $t
        );

        $fm = array();
        foreach ($this->fb_fieldsToRender as $c) {
            $sel =& $form->removeElement($c);
            $sel->setName('f' . $c);
            if (isset($this->camposselect[$c])) {
                $sel =& $form->createElement(
                    'select', 'f' . $c, 'f' . $c, array()
                );
                $sel->loadArray(
                    htmlentities_array( $db->getAssoc(
                            "SELECT id, nombre "
                            . " FROM {$this->camposselect[$c]} "
                            . " ORDER BY nombre"
                    ))
                );
            }
            $sel->setValue(null);
            $fm[] =& $sel;
        }
        $sel =& $form->createElement(
            'submit',
            $form->getButtonName($this->botonagrega), 'Añadir'
        );
        $fm[] =& $sel;

        $form->addGroup(
            $fm, 'g' . $this->__table, '',
            '&nbsp;', false
        );

    }
}
?>
