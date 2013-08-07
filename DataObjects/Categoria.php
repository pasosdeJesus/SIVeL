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
 * Definicion para la tabla categoria.
 */
require_once 'DataObjects/Basica.php';

/**
 * Definicion para la tabla categoria.
 * Ver documentación de DataObjects_Basica.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Basica
 */
class DataObjects_Categoria extends DataObjects_Basica
{

    var $__table = 'categoria';                       // table name
    var $id;                              // int4(4)  not_null primary_key
    var $nombre;                          // varchar(-1)  not_null
    var $id_supracategoria;               // int4(4)
    var $id_tviolencia;               // varchar(-1)
    var $id_pconsolidado;              // int4(4)
    var $contadaen;                      // int4(4)
    var $tipocat;                      // char
    var $fechacreacion;                  // date(4)  not_null
    var $fechadeshabilitacion;           // date(4)

    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

        $this->nom_tabla = _('Categoria');
        $this->fb_fieldLabels = array('id' => _('Código de Categoría'),
            'id_supracategoria' => _('Supracategoria'),
            'id_tviolencia' => _('Tipo de Violencia'),
            'nombre' => _('Nombre'),
            'fechacreacion' => _('Fecha de Creación'),
            'fechadeshabilitacion' => _('Fecha de Deshabilitación'),
            'id_pconsolidado' => _('Columna en Rep. Consolidado'),
            'contadaen' => _('Contada también como Categoria'),
            'tipocat' => _('Tipo de Categoria')
        );
    }


    var $fb_linkDisplayFields = array('nombre',
        'id_tviolencia',
        'id_supracategoria'
    );
    var $fb_preDefOrder = array('id', 'nombre', 'fechacreacion',
        'fechadeshabilitacion', 'id_supracategoria', 'id_pconsolidado',
        'contadaen', 'tipocat'
    );
    var $fb_fieldsToRender = array('id', 'nombre', 'fechacreacion',
        'fechadeshabilitacion', 'id_supracategoria', 'id_pconsolidado',
        'contadaen', 'tipocat'
    );
    var $fb_selectAddEmpty = array('fechadeshabilitacion');
    var $fb_hidePrimaryKey = false;
    var $fb_enumFields = array('tipocat');
    var $es_enumOptions = array('tipocat' => array('I' => 'Individual',
        'C' => 'Colectiva',
        'O' => 'Otra',
    )
    );

    /**
     * Pone un valor en la base diferente al recibido del formulario.
     *
     * @param string $valor Valor en formulario
     *
     * @return Valor para BD
     */
    function setfechadeshabilitacion($valor)
    {
        $this->fechadeshabilitacion = ($valor == '0000-00-00') ? 'null' : $valor;
    }

    /**
     * Pone un valor en la base diferente al recibido del formulario.
     *
     * @param string $valor Valor en formulario
     *
     * @return Valor para BD
     */
    function setcontadaen($valor)
    {
        $this->contadaen= ($valor == '') ? 'null' : $valor;
    }

    /**
     * Pone un valor en la base diferente al recibido del formulario.
     *
     * @param string $valor Valor en formulario
     *
     * @return Valor para BD
     */
    function setid_supracategoria($valor)
    {
        $a = explode(':', $valor);
        $this->id_tviolencia = $a[0];
        $this->id_supracategoria = $a[1];
    }

     /**
     * Requerido por versiones de FormBuilder de 0.10, hasta versión 1.121 en
     * el CVS de FormBuilder.php.
     * Cambio sucitado por bug #3469.
     *
     * @param string $table Tabla
     * @param string $key   Llave
     *
     * @return opción enumeada asociada a la llave.
     */
    function enumCallback($table, $key)
    {

         return $this->es_enumOptions[$key];
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
        $formbuilder->enumOptionsCallback = array($this,
            "enumCallback"
        );
        if (!isset($this->tipocat)) {
            $this->tipocat = 'I';
        }
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
        $h =& $form->getElement('__header__');
        if (PEAR::isError($h)) {
            $h =& $form->getElement(null);
        }
        $s =& $form->getElement('id_supracategoria');
        $db = $formbuilder->_do->getDatabaseConnection();
        $s->_options = array();
        $q = "SELECT tviolencia.id || ':' || supracategoria.id,
            supracategoria.nombre || ' (' || tviolencia.nombre || ')'
            FROM supracategoria, tviolencia
            WHERE tviolencia.id = supracategoria.id_tviolencia";
        $op = $db->getAssoc($q);
        sin_error_pear($op);
        $r = $s->loadArray(htmlentities_array($op));
        $s->setValue(
            $this->id_tviolencia.':' .
            $this->id_supracategoria
        );
    }

    /**
     * Para evitar que intente numeración automática cuando se
     * agregan categorias
     *
     * @return array Indica no numerar automáticamente
     */
    function sequenceKey()
    {
        return array(false, false, false);
    }




}

?>
