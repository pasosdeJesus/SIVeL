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
 * Definicion para la tabla acto
 */
require_once 'DB_DataObject_SIVeL.php';

/**
 * Definicion para la tabla acto
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Acto extends DB_DataObject_SIVeL
{

    var $__table = 'acto';                      // table name
    var $id_presponsable;                        // int4(4)  multiple_key
    var $id_categoria;                    // int4(4)  multiple_key
    var $id_persona;                 // int4(4)  multiple_key
    var $id_caso;                         // int4(4)  multiple_key


    var $fb_preDefOrder = array('id_presponsable');
    var $fb_fieldsToRender = array('id_presponsable');
    var $fb_selectAddEmpty = array('id_presponsable');
    var $fb_addFormHeader = false;
    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

        $this->fb_fieldLabels= array(
           'id_presponsable' => _('Presunto Responsable'),
           'id_categoria' => _('Categoria'),
           'id_persona' => _('Persona'),
           'id_caso' => _('Caso'),
       );
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

        $t = '<table id="tablaActos" width="100%"><thead><tr>' .
            '<th>' . _('P. Responsable') . '</th><th>' . _('Categoria') .
            '</th>' . '<th>' . _('Víctima') . '</th><th></th></thead><tbody>';
        $p = clone $formbuilder->_do;
        $db = $p->getDatabaseConnection();
        $p->id_presponsable = null;
        $p->id_categoria = null;
        $p->id_persona = null;
        $p->find();
        while ($p->id_caso != null && $p->fetch()) {
            $pp =& $p->getLink('id_presponsable');
            $ca =& $p->getLink('id_categoria');
            $vi =& $p->getLink('id_persona');
            $t .= "<tr><td>" .  htmlentities($pp->nombre, ENT_COMPAT, 'UTF-8')
                . "</td><td>" . $ca->id_tviolencia
                . (int)$ca->id . " "
                . htmlentities($ca->nombre, ENT_COMPAT, 'UTF-8') . "</td>"
                . "<td>" . htmlentities("{$vi->nombres}  {$vi->apellidos}", ENT_COMPAT, 'UTF-8')
                . "</td>"
                . "<td><a href='{$_SERVER['PHP_SELF']}?eliminaacto="
                . (int)$p->id_presponsable . ":"
                . (int)$p->id_categoria . ':'
                . (int)$p->id_persona . "'>" . _('Eliminar') . "</a></td>";
        }
        $t .= '</tbody></table>';
        $sel =& $form->addElement('static', 'individuales', '', $t);


    }

}

?>
