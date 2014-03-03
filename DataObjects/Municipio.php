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
 * Definicion para la tabla municipio.
 */

require_once 'DataObjects/Basica.php';

/**
 * Definicion para la tabla municipio.
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Municipio extends DataObjects_Basica
{

    var $__table = 'municipio';                       // table name
    var $id_pais;                 // int4(4)  multiple_key
    var $id_departamento;                 // int4(4)  multiple_key
    var $latitud;
    var $longitud;

    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

        $this->nom_tabla = _('Municipio');
        $this->fb_fieldLabels = array(
            'id_pais' => _('País'),
            'id_departamento' => _('Departamento'),
            'nombre' => _('Nombre'),
            'latitud'=> _('Latitud'),
            'longitud'=> _('Longitud'),
            'fechacreacion' => _('Fecha de Creación'),
            'fechadeshabilitacion' => _('Fecha de Deshabilitación'),
        );

    }


    var $fb_linkDisplayFields = array('nombre', 'id_departamento', 'id_pais');
    var $fb_preDefOrder = array(
        'id',
        'id_pais',
        'id_departamento',
        'nombre',
        'fechacreacion',
        'fechadeshabilitacion',
    );
    var $fb_fieldsToRender = array(
        'nombre',
        'id_departamento',
        'fechacreacion',
        'fechadeshabilitacion',
    );

    var $fb_hidePrimaryKey = false;

    var $fb_linkDisplayLevel = 3;

    /**
     * Convierte valor de base a formulario.
     *
     * @param string &$valor Valor en base
     *
     * @return Valor para formulario
     */
    function getid_departamento(&$valor)
    {
        $valor = $this->id_departamento . "-" . $this->id_pais;
        return $valor;
    }

    /**
     * Pone un valor en la base diferente al recibido del formulario.
     *
     * @param string $valor Valor en formulario
     *
     * @return Valor para BD
     */
    function setid_departamento($valor)
    {
        // Si se cambia la identificación del municipio, se cambia la
        // llave por lo que más bien debe hacerse una eliminación y
        // después añadir uno nuevo.
        $v = explode("-", $valor);
        $this->id_departamento = $v[0];
        $this->id_pais = $v[1];
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

        parent::preGenerateForm($formbuilder);
        $s =& HTML_QuickForm::createElement(
            'select', 'id_departamento',
            'Departamento: ', array()
        );
        $db = $this->getDatabaseConnection();
        $result = hace_consulta(
            $db, "SELECT departamento.id, departamento.id_pais, " .
            "departamento.nombre, pais.nombre " .
            "FROM departamento, pais " .
            " WHERE id_pais=pais.id " .
            " ORDER BY departamento.nombre, pais.nombre"
        );
        $options = array();
        $row = array();
        while ($result != null && !PEAR::isError($result)
            && $result->fetchInto($row)
        ) {
            $options["{$row[0]}-{$row[1]}"] = 
                "{$row[2]} ({$row[3]})";
        }
        $k = $this->id_departamento . "-" . $this->id_pais;
        $s->loadArray($options, $k);

        $this->fb_preDefElements = array('id_departamento' => $s,
            'id_pais' => HTML_QuickForm::createElement(
                'hidden',
                'id_pais'
            ),
            'id' => HTML_QuickForm::createElement('hidden', 'id')
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
        parent::postGenerateform($form, $formbuilder);

        if (isset($this->id_departamento)) {
            $h =& $form->getElement('id_departamento');
            $k= $this->id_departamento . "-" . $this->id_pais;
            $h->setValue($k);
        }
    }

}

?>
