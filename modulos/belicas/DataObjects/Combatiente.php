<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Objeto asociado a una tabla combatiente
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

require_once 'DB_DataObject_SIVeL.php';
require_once 'DataObjects/Filiacion.php';
require_once 'DataObjects/Organizacion.php';
require_once 'DataObjects/Presuntos_responsables.php';
require_once 'DataObjects/Profesion.php';
require_once 'DataObjects/Rango_edad.php';
require_once 'DataObjects/Resultado_agresion.php';
require_once 'DataObjects/Sector_social.php';
require_once 'DataObjects/Vinculo_estado.php';

/**
 * Definicion para la tabla combatiente.
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Combatiente extends DB_DataObject_SIVeL
{
    var $__table = 'combatiente';                     // table name
    var $id;                              // varchar(-1)  not_null primary_key
    var $nombre;                          // varchar(-1)  not_null
    var $alias;                           // varchar(-1)
    var $edad;                            // int4(4)
    var $sexo;                            // varchar(-1)  not null
    var $id_resultado_agresion;           // int4(4)
    var $id_profesion;                    // int4(4)
    var $id_rango_edad;                   // int4(4)
    var $id_filiacion;                    // int4(4)
    var $id_sector_social;                // int4(4)
    var $id_organizacion;                 // int4(4)
    var $id_vinculo_estado;               // int4(4)
    var $id_caso;                         // int4(4)
    var $id_organizacion_armada;          // int4(4)

    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

        $this->nom_tabla = _('Combatiente');
        $this->fb_fieldLabels = array(
            'nombre'=> _('Nombre'),
            'alias'=> _('Alias'),
            'edad'=> _('Edad'),
            'sexo'=> _('Sexo'),
            'id_rango_edad' => _('Rango de Edad'),
            'id_sector_social'=> _('Sector Social'),
            'id_vinculo_estado'=> _('Vínculo Estado'),
            'id_filiacion'=> _('Filiación Política'),
            'id_profesion'=> _('Profesion'),
            'id_organizacion'=> _('Organización Social'),
            'id_organizacion_armada'=> _('Organización Armada'),
            'id_resultado_agresion'=> _('Resultado Agresión')
        );


    }


    var $fb_preDefOrder = array('nombre', 'alias', 'edad', 'sexo',
        'id_rango_edad', 'id_sector_social', 'id_vinculo_estado',
         'id_filiacion', 'id_profesion', 'id_organizacion',
        'id_organizacion_armada', 'id_resultado_agresion'
    );
    var $fb_fieldsToRender = array('nombre', 'alias', 'edad', 'sexo',
        'id_rango_edad', 'id_sector_social', 'id_vinculo_estado',
         'id_filiacion', 'id_profesion', 'id_organizacion',
        'id_organizacion_armada', 'id_resultado_agresion'
    );
    var $fb_enumFields = array('sexo');
    var $es_enumOptions = array('sexo' => array('F' => 'Femenino',
        'M' => 'Masculino', 'S'=> 'SIN INFORMACIÓN'
    )
    );
    var $fb_addFormHeader = false;
    var $fb_excludeFromAutoRules = array('nombre', 'sexo');
    /**
     * Funciona legada
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
     * Pone un valor en la base diferente al recibido del formulario.
     *
     * @param string $valor Valor en formulario
     *
     * @return Valor para BD
     */
    function setedad($value)
    {
        $this->edad= ($value == '') ? 'null' : $value;
    }

    /**
     * Campos que pueden ser SIN INFORMACION y el código corresp
     *
     * @return array Arreglo de campos que pueden ser sin información
     */
    static function camposSinInfo()
    {
        return array(
            'sexo'=>'S',
            'id_rango_edad'=> DataObjects_Rango_edad::idSinInfo(),
            'id_profesion'=> DataObjects_Profesion::idSinInfo(),
            'id_filiacion' => DataObjects_Filiacion::idSinInfo(),
            'id_sector_social' => DataObjects_Sector_social::idSinInfo(),
            'id_organizacion' => DataObjects_Organizacion::idSinInfo(),
            'id_vinculo_estado' => DataObjects_Vinculo_estado::idSinInfo(),
            'id_organizacion_armada' =>
                DataObjects_Presuntos_responsables::idSinInfo(),
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

        $sel =& $form->getElement('nombre');
        if (isset($sel) && !PEAR::isError($sel)) {
            $sel->setSize(70);
            $sel->setMaxlength(100);
/*            if (isset($GLOBALS['etiqueta']['nombre'])) {
                $sel->setLabel($GLOBALS['etiqueta']['nombre']);
} */
        }

        $e =& $form->getElement('alias');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setSize(70);
            $e->setMaxlength(100);
/*            if (isset($GLOBALS['etiqueta']['alias'])) {
                $e->setLabel($GLOBALS['etiqueta']['alias']);
}*/
        }

        $e =& $form->getElement('edad');
        if (isset($e) && !PEAR::isError($e)) {
            $e->setSize(5);
            $e->setMaxlength(5);
/*            if (isset($GLOBALS['etiqueta']['edad'])) {
                $e->setLabel($GLOBALS['etiqueta']['edad']);
}*/
        }
/*        $e =& $form->getElement('sexo');
        if (isset($e) && !PEAR::isError($e)
            && isset($GLOBALS['etiqueta']['sexo'])
        ) {
            $e->setLabel($GLOBALS['etiqueta']['sexo']);
        }  */

        $e =& $form->getElement('id_rango_edad');
        if (isset($e) && !PEAR::isError($e)
            && isset($GLOBALS['etiqueta']['rango_edad'])
        ) {
            $e->setLabel($GLOBALS['etiqueta']['rango_edad']);
        }

        $e =& $form->getElement('id_sector_social');
        if (isset($e) && !PEAR::isError($e)
            && isset($GLOBALS['etiqueta']['sector_social'])
        ) {
            $e->setLabel($GLOBALS['etiqueta']['sector_social']);
        }

        $e =& $form->getElement('id_vinculo_estado');
        if (isset($e) && !PEAR::isError($e)
            && isset($GLOBALS['etiqueta']['vinculo_estado'])
        ) {
            $e->setLabel($GLOBALS['etiqueta']['vinculo_estado']);
        }

        $e =& $form->getElement('id_filiacion');
        if (isset($e) && !PEAR::isError($e)
            && isset($GLOBALS['etiqueta']['filiacion'])
        ) {
            $e->setLabel($GLOBALS['etiqueta']['filiacion']);
        }

        $e =& $form->getElement('id_profesion');
        if (isset($e) && !PEAR::isError($e)
            && isset($GLOBALS['etiqueta']['profesion'])
        ) {
            $e->setLabel($GLOBALS['etiqueta']['profesion']);
        }

        $e =& $form->getElement('id_organizacion');
        if (isset($e) && !PEAR::isError($e)
            && isset($GLOBALS['etiqueta']['organizacion'])
        ) {
            $e->setLabel($GLOBALS['etiqueta']['organizacion']);
        }

        $e =& $form->getElement('id_organizacion_armada');
        if (isset($e) && !PEAR::isError($e)
            && isset($GLOBALS['etiqueta']['organizacion_armada'])
        ) {
            $e->setLabel($GLOBALS['etiqueta']['organizacion_armada']);
        }

        $e =& $form->getElement('id_resultado_agresion');
        if (isset($e) && !PEAR::isError($e)
            && isset($GLOBALS['etiqueta']['resultado_agresion'])
        ) {
            $e->setLabel($GLOBALS['etiqueta']['resultado_agresion']);
        }

    }

    /**
     * Convierte registro a relato (arreglo de elementos) que agrega a $ar
     * dad son datos adicionales que pueden requerirse para la conversión.
     *
     * @param array &$ar Arreglo
     * @param array $dad Arreglo
     *
     * @return array $ar modificado
     */
    function aRelato(&$ar, $dad = array())
    {
        parent::aRelato($ar, $dad);
        $ar['id_persona'] = $this->id;
        return $ar;
    }

}

?>
