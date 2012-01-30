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
 * @copyright 2004 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: Accion.php,v 1.17.2.2 2011/09/14 14:56:19 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

/**
 * Definicion para la tabla accion
 */
require_once 'DB_DataObject_SIVeL.php';
require_once 'Tipo_accion.php';
require_once 'Despacho.php';


/**
 * Definicion para la tabla accion
 * Ver documentación de DataObjects_Basica.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público
 * @link     http://sivel.sf.net/tec
 * @see      DB_DataObject_SIVeL
 */
class DataObjects_Accion extends DB_DataObject_SIVeL
{
    var $__table = 'accion';                    // table name
    var $id;
    var $id_proceso;
    var $id_tipo_accion;
    var $id_despacho;
    var $fecha;
    var $numero_radicado;                // varchar(-1)
    var $observaciones_accion;                // varchar(-1)
    var $respondido;                // varchar(-1)


    var $fb_hidePrimaryKey = true;
    var $fb_preDefOrder = array('id_tipo_accion', 'id_despacho', 'fecha',
         'numero_radicado', 'observaciones_accion', 'respondido'
     );
    var $fb_fieldsToRender = array('id_tipo_accion', 'id_despacho', 'fecha',
         'numero_radicado', 'observaciones_accion', 'respondido'
     );
    var $fb_addFormHeader = true;
    var $fb_fieldLabels = array(
        'id_tipo_accion'        => 'Tipo de Acción',
        'id_despacho'           => 'Despacho',
        'fecha'                 => 'Fecha de la Acción',
        'numero_radicado'       => 'No. Radicado',
        'observaciones_accion'  => 'Observaciones',
        'respondido'            => 'Respondido'
    );
    var $fb_textFields = array ('observaciones_accion');
    var $fb_booleanFields = array ('respondido');


    /**
     * Campos que pueden ser SIN INFORMACION y el código correspondiente
     *
     * @return array Arreglo de campos que pueden ser sin información
     */
    static function camposSinInfo()
    {
        return array(
            'id_tipo_accion'    => DataObjects_Tipo_accion::idSinInfo(),
            'id_despacho'       => DataObjects_Despacho::idSinInfo(),
        );
    }


    /**
    * Convierte de formulario a base de datos
    *
    * @param string $value Valor recibido de formulario
    *
    * @return Valor para base de datos
    */
    function setrespondido($value)
    {
        $this->respondido = ((isset($value) && $value == 1)) ? 't' : 'f';
    }

    /**
    * Convierte de base de datos a formulario
    *
    * @param string $value Valor recibido de formulario
    *
    * @return Valor para base de datos
    */
    function getrespondido()
    {
        return (isset($this->respondido) && $this->respondido == 't') ? 1 : 0;
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
        $csin = $this->camposSinInfo();
        foreach ($csin as $c => $v) {
            if (!isset($this->$c)) {
                $this->$c = $csin[$c];
            }
        }
        $this->fb_preDefElements = array('id_proceso' =>
                    HTML_QuickForm::createElement('hidden', 'id_proceso')
        );
    }


    /**
     * Ajusta formulario generado.
     *
     * @param object &$form      Formulario HTML_QuickForm
     * @param object &$formbuilder Generador DataObject_FormBuilder
     *
     * @return void
     */
    function postGenerateForm(&$form, &$formbuilder)
    {
        parent::postGenerateForm($form, $formbuilder);

/*        foreach ($this->fb_fieldsToRender as $c) {
            $e =& $form->getElement($c);
            if (isset($e) && !PEAR::isError($e)
                && isset($GLOBALS['etiqueta'][$c])
            ) {
                $e->setLabel($GLOBALS['etiqueta'][$c]);
            }
} */

        $fa = array();
        $obs =& $form->removeElement('observaciones_accion');
        $res =& $form->removeElement('respondido');
        $fa[] =& $form->removeElement('id');
        $fa[] =& $form->removeElement('id_proceso');
        $fa[] =& $form->removeElement('id_tipo_accion');
        $fa[] =& $form->removeElement('id_despacho');
        $sf = $fa[] =& $form->removeElement('fecha');
        $sf->setLabel('Fecha de la Acción');
        $fa[] =& $form->removeElement('numero_radicado');
        $fa[] =& $obs;
        $fa[] = $res;

        $t = '<table width="100%"><tr><th>Tipo</th><th>Despacho</th>' .
            '<th>Fecha</th><th>N. Rad.</th><th>Observaciones</th><th>Resp</th></tr>';
        $p = objeto_tabla('accion');
        $db = $p->getDatabaseConnection();
        $p->id_proceso = $formbuilder->_do->id_proceso;
        $p->orderby('fecha desc');
        $p->find();
        while ($p->id_proceso != null && $p->fetch()) {
            $dtipo = $p->getLink('id_tipo_accion');
            $ddespacho = $p->getLink('id_despacho');
            $n = "fobs_{$p->id_proceso}_{$p->id_tipo_accion}_" .
                "{$p->id_despacho}_{$p->fecha}";
            $t .= "<tr><td>" . htmlentities($dtipo->nombre) . "</td>" .
                "<td>" . htmlentities($ddespacho->nombre) . "</td>" .
                "<td>" . htmlentities($p->fecha) . "</td>" .
                "<td>" . htmlentities($p->numero_radicado) . "</td>" .
                "<td>" . htmlentities($p->observaciones_accion) . "</td>" .
                "<td>" . ($p->respondido == 't'?'Si':'No') . "</td>" .
                "<td><a href=\"{$_SERVER['PHP_SELF']}?eliminaaccionj=" .
                $p->id . "\">Eliminar</a></td>";
        }
        $t .= '</table>';
        $sel =& $form->addElement('static', null, '', $t);

        foreach ($fa as $e) {
            $form->addElement($e);
        }
        $form->addAction(
            'agregaraccionj',
            new AgregarAccionJ()
        );
        $sel =& $form->createElement(
            'submit',
            $form->getButtonName('agregaraccionj'),'Añadir Acción'
        );
        $form->addElement($sel);

        $e =& $form->getElement('numero_radicado');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setSize(55);
            $e->setMaxLength(50);
        }

        $e =& $form->getElement('observaciones_accion');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setCols(75);
            $e->setRows(2);
        }

    }

}

?>
