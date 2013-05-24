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
require_once "Causadesp.php";
require_once "Clasifdesp.php";
require_once "Inclusion.php";
require_once "Declaroante.php";
require_once "Modalidadtierra.php";
require_once "DataObjects/Presponsable.php";
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
	var $expulsion;
	var $fechallegada;
	var $llegada;
	var $id_clasifdesp;
	var $id_tipodesp;
	var $id_presponsable;
	var $id_causadesp;
	var $descripcion;
	var $otrosdatos;
	var $declaro;
	var $hechosdeclarados;
	var $fechadeclaracion;
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
            'expulsion' => _('Sitio de Expulsión'),
            'fechallegada' => _('Fecha de Llegada'),
            'llegada' => _('Sitio de Llegada'),
            'id_clasifdesp' => _('Clasificación'),
            'id_tipodesp' => _('Tipo'),
            'id_presponsable' => _('Presunto Responsable'),
            'id_causadesp' => _('Causa'),
            'descripcion' => _('Descripción'),
            'otrosdatos' => _('Otros Datos'),
            'declaro' => _('Declaró'),
            'hechosdeclarados' => _('Hechos Declarados'),
            'fechadeclaracion' => _('Fecha de Declaración'),
            'departamentodecl' => _('Departamento Declaración'),
            'municipiodecl' => _('Municipio Declaración'),
            'id_declaroante' => _('Declaro Ante'),
            'id_inclusion' => _('Inclusión'),
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

    var $fb_hidePrimaryKey = false;

    var $fb_preDefOrder = array(
        'fechaexpulsion' ,
        'expulsion' ,
        'fechallegada' ,
        'llegada' ,
        'id_clasifdesp' ,
        'id_tipodesp' ,
        'id_presponsable' ,
        'id_causadesp' ,
        'descripcion' ,
        'otrosdatos' ,
        'declaro' ,
        'hechosdeclarados' ,
        'fechadeclaracion' ,
        'departamentodecl' ,
        'municipiodecl' ,
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
        'expulsion' ,
        'fechallegada' ,
        'llegada' ,
        'id_clasifdesp' ,
        'id_tipodesp' ,
        'id_presponsable' ,
        'id_causadesp' ,
        'descripcion' ,
        'otrosdatos' ,
        'declaro' ,
        'hechosdeclarados' ,
        'fechadeclaracion' ,
        'departamentodecl' ,
        'municipiodecl' ,
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
    var $fb_addFormHeader = false;
    var $fb_textFields = array(
        'descripcion',
        'hechosdeclarados',
        'oficioantes',
        'materialesperdidos',
        'inmaterialesperdidos',
        'documentostierra',
    );
    var $fb_boolFields = array(
        'retornado',
        'reubicado',
        'connacionalretorno',
        'acompestado',
        'connacionaldeportado',
        'protegiorupta',
    );

    static function camposSinInfo()
    {
        return array(
            'id_clasifdesp'=> DataObjects_Clasifdesp::idSinInfo(),
            'id_tipodesp'=> DataObjects_Tipodesp::idSinInfo(),
            'id_causadesp'=> DataObjects_Causadesp::idSinInfo(),
            'id_presponsable'=> DataObjects_Presponsable::idSinInfo(),
            'id_declaroante'=> DataObjects_Declaroante::idSinInfo(),
            'id_inclusion'=> DataObjects_Inclusion::idSinInfo(),
            'id_acreditacion'=> DataObjects_Acreditacion::idSinInfo(),
            'id_modalidadtierra'=> DataObjects_Modalidadtierra::idSinInfo(),
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

        $sel =& $form->getElement('id_caso');
        $p = objeto_tabla('caso');
        $db = $p->getDatabaseConnection();

/*        $e =& $form->getElement('demandante');
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
        $e =& $form->addElement('hidden', 'id_desplazamiento', $e->getValue()); */
    }
}

?>
