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
 * Definicion para la tabla desplazamiento
 */

require_once 'DB_DataObject_SIVeL.php';
require_once  $GLOBALS['dirsitio'] . "/conf.php";

require_once "Acreditacion.php";
require_once "Clasifdesp.php";
require_once "Inclusion.php";
require_once "Declaroante.php";
require_once "Modalidadtierra.php";
require_once "Tipodesp.php";

/**
 * Definicion para la tabla desplazamiento
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Desplazamiento extends DB_DataObject_SIVeL
{

    var $__table = 'desplazamiento';                       // table name

    var $id_caso;
    var $fechaexpulsion;
    var $id_expulsion;
    var $fechallegada;
    var $id_llegada;
    var $id_clasifdesp;
    var $id_tipodesp;
    var $descripcion;
    var $otrosdatos;
    var $declaro;
    var $hechosdeclarados;
    var $fechadeclaracion;
    var $paisdecl;
    var $departamentodecl;
    var $municipiodecl;
    var $id_declaroante;
    var $id_inclusion;
    var $id_acreditacion;
    var $retornado;
    var $reubicado;
    var $connacionalretorno;
    var $acompestado;
    var $connacionaldeportado;
    var $oficioantes;
    var $id_modalidadtierra;
    var $materialesperdidos;
    var $inmaterialesperdidos;
    var $protegiorupta;
    var $documentostierra;

    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

        $this->nom_tabla = _('Desplazamiento');
        $this->fb_fieldLabels = array(
            'fechaexpulsion' => _('Fecha Expulsión'),
            'id_expulsion' => _('Sitio de Expulsión'),
            'fechallegada' => _('Fecha de Llegada'),
            'id_llegada' => _('Sitio de Llegada'),
            'id_clasifdesp' => _('Clasificación'),
            'id_tipodesp' => _('Tipo'),
            'descripcion' => _('Descripción'),
            'otrosdatos' => _('Otros Datos'),
            'declaro' => _('Declaró'),
            'hechosdeclarados' => _('Hechos Declarados'),
            'fechadeclaracion' => _('Fecha de Declaración'),
            'paisdecl' => _('País Declaración'),
            'departamentodecl' => _('Departamento Declaración'),
            'municipiodecl' => _('Municipio Declaración'),
            'id_declaroante' => _('Declaro Ante'),
            'id_inclusion' => _('Inclusión RUV'),
            'id_acreditacion' => _('Acreditación'),
            'retornado' => _('Retornado'),
            'reubicado' => _('Reubicado'),
            'connacionalretorno' => _('Connacional con Retorno Voluntario'),
            'acompestado' => _('Acompañamiento Estado'),
            'connacionaldeportado' => _('Connacional Deportado'),
            'oficioantes' => _('Oficio antes del Desplazamiento'),
            'id_modalidadtierra' => _('Modalidad de Tenencia de Tierra'),
            'materialesperdidos' => _('Bienes Materiales Perdidos'),
            'inmaterialesperdidos' => _('Bienes Inmateriales Perdidos'),
            'protegiorupta' => _('Protegió predio en RUPTA'),
            'documentostierra' => _('Documentos sobre la Tierra'),
        );

    }


    var $fb_selectAddEmpty = array('expulsion');

    var $fb_hidePrimaryKey = false;

    var $fb_preDefOrder = array(
        'fechaexpulsion' ,
        'id_expulsion' ,
        'fechallegada' ,
        'id_llegada' ,
        'id_clasifdesp' ,
        'id_tipodesp' ,
        'descripcion' ,
        'otrosdatos' ,
        'declaro' ,
        'hechosdeclarados' ,
        'fechadeclaracion' ,
        'id_declaroante' ,
        'id_inclusion' ,
        'id_acreditacion' ,
        'retornado' ,
        'reubicado' ,
        'connacionalretorno' ,
        'acompestado' ,
        'connacionaldeportado' ,
        'oficioantes' ,
        'id_modalidadtierra' ,
        'materialesperdidos' ,
        'inmaterialesperdidos' ,
        'protegiorupta' ,
        'documentostierra' ,
    );
    var $fb_fieldsToRender = array(
        'fechaexpulsion' ,
        'id_expulsion' ,
        'fechallegada' ,
        'id_llegada' ,
        'id_clasifdesp' ,
        'id_tipodesp' ,
        'descripcion' ,
        'otrosdatos' ,
        'declaro' ,
        'hechosdeclarados' ,
        'fechadeclaracion' ,
        'id_declaroante' ,
        'id_inclusion' ,
        'id_acreditacion' ,
        'retornado' ,
        'reubicado' ,
        'connacionalretorno' ,
        'acompestado' ,
        'connacionaldeportado' ,
        'oficioantes' ,
        'id_modalidadtierra' ,
        'materialesperdidos' ,
        'inmaterialesperdidos' ,
        'protegiorupta' ,
        'documentostierra' ,

    );
    var $fb_linkDisplayLevel = 2;
    var $fb_addFormHeader = false;
    var $fb_textFields = array(
        'descripcion',
        'hechosdeclarados',
        'oficioantes',
        'materialesperdidos',
        'inmaterialesperdidos',
        'documentostierra',
    );
    var $fb_booleanFields = array(
        'retornado',
        'reubicado',
        'connacionalretorno',
        'acompestado',
        'connacionaldeportado',
        'protegiorupta',
    );
    var $fb_enumFields = array(
        'declaro'
    );
    var $es_enumOptions = array(
        'declaro' => array(
            'S' => 'SI',
            'N' => 'NO',
            'R'=> 'NO SABE/NO RESPONDE'
        ),
    );

    /**
     * Retorna campos sin información
     *
     * @return array Campos que podrían ser sin información y su valor
     */
    static function camposSinInfo()
    {
        return array(
            'id_clasifdesp'=> DataObjects_Clasifdesp::idSinInfo(),
            'id_tipodesp'=> DataObjects_Tipodesp::idSinInfo(),
            'id_declaroante'=> DataObjects_Declaroante::idSinInfo(),
            'id_inclusion'=> DataObjects_Inclusion::idSinInfo(),
            'id_acreditacion'=> DataObjects_Acreditacion::idSinInfo(),
            'id_modalidadtierra'=> DataObjects_Modalidadtierra::idSinInfo(),
            'declaro'=> 'R',
        );
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

        $p = objeto_tabla('caso');
        $db = $p->getDatabaseConnection();
        $c =& $form->getElement('id_caso');
        $idcaso = $c->getValue();

        $seln =& $form->getElement('fechaexpulsion');
        if ($this->fechaexpulsion != null && $this->fechaexpulsion > 0) {
            $seln->freeze();
        }

        $s =& $form->getElement('id_expulsion');
        $s->_options = array();
        $q = "
            SELECT ubicacion.id, trim(pais.nombre || ', ' || lugar)
            FROM ubicacion, pais
            WHERE ubicacion.id_caso = '$idcaso'
            AND ubicacion.id_municipio IS NULL
            AND ubicacion.id_departamento IS NULL
            AND ubicacion.id_pais = pais.id
            UNION SELECT ubicacion.id, trim(departamento.nombre || 
            ', ' || pais.nombre || ', ' || lugar)
            FROM ubicacion, departamento, pais
            WHERE ubicacion.id_caso = '$idcaso'
            AND ubicacion.id_municipio IS NULL
            AND ubicacion.id_departamento = departamento.id
            AND ubicacion.id_pais = pais.id
            AND departamento.id_pais = pais.id
            UNION SELECT ubicacion.id, trim(municipio.nombre || ', '
            || departamento.nombre || ', ' || pais.nombre || ', ' || lugar)
            FROM ubicacion, pais, departamento, municipio
            WHERE ubicacion.id_caso = '$idcaso'
            AND ubicacion.id_pais = pais.id
            AND ubicacion.id_municipio = municipio.id
            AND ubicacion.id_departamento = municipio.id_departamento
            AND departamento.id_pais = pais .id
            AND municipio.id_pais = pais .id
            AND municipio.id_departamento = departamento.id";
        $op = $db->getAssoc($q);
        sin_error_pear($op);
        $r = $s->loadArray(array('' => '') + htmlentities_array($op));
        $s->setValue(
            $this->id_expulsion
        );

        $s =& $form->getElement('id_llegada');
        $s->_options = array();
        $op = $db->getAssoc($q);
        sin_error_pear($op);
        $r = $s->loadArray(htmlentities_array($op));
        $s->setValue(
            $this->id_llegada
        );


        $s =& $form->getElement('otrosdatos');
        $s->setSize(75);
    }
}

?>
