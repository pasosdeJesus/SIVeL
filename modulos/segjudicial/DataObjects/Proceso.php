<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Objeto tabla proceso
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


require_once 'DB_DataObject_SIVeL.php';
require_once  $GLOBALS['dirsitio'] . "/conf.php";
require_once "Tipo_proceso.php";
require_once "Etapa.php";


/**
 * Definicion para la tabla proceso
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Proceso extends DB_DataObject_SIVeL
{

    var $__table = 'proceso';                       // table name
    var $id;                              // int4(4)  not_null primary_key
    var $id_caso;                              // int4(4)  not_null primary_key
    var $id_tipo;                        // int4(4)
    var $id_etapa;                        // int4(4)
    var $proximafecha;                         // date
    var $demandante;                           // varchar(-1)
    var $demandado;
    var $poderdante;
    var $telefono;
    var $observaciones;

    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

        $this->nom_tabla = _('Proceso');
        $this->fb_fieldLabels = array(
            'id_tipo' => _('Tipo'),
            'id_etapa' => _('Etapa'),
            'proximafecha' => _('Próxima fecha'),
            'demandante' => _('Demandante'),
            'demandado' => _('Demandado'),
            'poderdante' => _('Apoderado'),
            'telefono' => _('Teléfono'),
            'observaciones' => _('Observaciones'),
        );

    }


    var $fb_preDefOrder = array(
        'id_tipo', 'proximafecha',
        'demandante', 'demandado', 'poderdante', 'telefono',
        'observaciones',
    );
    var $fb_fieldsToRender = array(
        'id_tipo', 'proximafecha',
        'demandante', 'demandado', 'poderdante', 'telefono',
        'observaciones',
    );
    var $fb_addFormHeader = false;
    var $fb_textFields = array(
        'observaciones',
    );

    static function camposSinInfo()
    {
        return array(
            'id_tipo'=> DataObjects_Tipo_Proceso::idSinInfo(),
            'id_etapa'=> DataObjects_Etapa::idSinInfo(),
        );
    }


    /**
     * Prepara antes de generar formulario.
     *
     * @param object &$$formbuilder Generador DataObject_FormBuilder
     *
     * @return void
     */
    function preGenerateForm(&$formbuilder)
    {
        parent::preGenerateForm($formbuilder);
        $this->fb_preDefElements = array('id' =>
        HTML_QuickForm::createElement('hidden', 'id')
        );
        $this->fb_preDefElements = array('id_caso' =>
        HTML_QuickForm::createElement('hidden', 'id_caso')
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

//        $sel =& $form->removeElement('id_etapa');
        $sel =& $form->getElement('id_tipo');
        $p = objeto_tabla('proceso');
        $db = $p->getDatabaseConnection();

        //$form->setDefaults(array('test' => array('4','15')));
        $sel = $form->createElement('hierselect', 'tipoetapa', 'Tipo/Etapa', null, '/');
        $mainOptions = htmlentities_array(
            $db->getAssoc('SELECT id, nombre FROM tipo_proceso')
        );
        $sel->setMainOptions($mainOptions);

        $result = $db->query("SELECT id_tipo, id, nombre FROM etapa ORDER BY 1,2");
        while ($result->fetchInto($row)) {
            $secOptions[$row[0]][$row[1]] = $row[2];
        }
        $sel->setSecOptions(htmlentities_array($secOptions));

        $e =& $form->getElement('demandante');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setSize(55);
            $e->setMaxLength(100);
        }
        $e =& $form->getElement('demandado');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setSize(55);
            $e->setMaxLength(100);
        }
        $e =& $form->getElement('poderdante');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setSize(55);
            $e->setMaxLength(100);
        }
        $e =& $form->getElement('telefono');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setSize(55);
            $e->setMaxLength(50);
        }
        $e =& $form->getElement('observaciones');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setCols(75);
            $e->setRows(2);
        }
        $e =& $form->getElement('id');
        $e =& $form->addElement('hidden', 'id_proceso', $e->getValue());
    }
}

?>
