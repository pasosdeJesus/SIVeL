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
 * Definicion para la tabla persona
 */
require_once 'DB_DataObject_SIVeL.php';
require_once 'misc.php';

/**
 * Definicion para la tabla persona.
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Persona extends DB_DataObject_SIVeL
{

    var $__table = 'persona';                         // table name
    var $id;                              // int4(4)  not_null primary_key
    var $nombres;                          // varchar(-1)  not_null
    var $apellidos;
    var $anionac;                            // int4(4)
    var $mesnac;                            // int4(4)
    var $dianac;                            // int4(4)
    var $sexo;                            // varchar(-1)
    var $id_departamento;                            // varchar(-1)
    var $id_municipio;                            // varchar(-1)
    var $id_clase;                            // varchar(-1)
    var $tipodocumento;                  // varchar(-1)
    var $numerodocumento;                // varchar(-1)



    var $fb_preDefOrder = array('nombres', 'apellidos', 'anionac',
        'mesnac', 'dianac', 'sexo',   'tipodocumento',
        'numerodocumento'
    );

    var $fb_fieldsToRender = array('nombres', 'apellidos', 'anionac',
        'mesnac', 'dianac', 'sexo',   'tipodocumento',
        'numerodocumento'
    );

    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        $this->fb_fieldLabels= array(
           'nombres' => _('Nombres'),
           'apellidos' => _('Apellidos'),
           'anionac' => _('Año Nacimiento'),
           'mesnac' => _('Mes Nacimiento'),
           'dianac' => _('Día Nacimiento'),
           'sexo' => _('Sexo'),
           'tipodocumento' => _('Tipo de Docuento'),
           'numerodocumento' => _('Número de Documento'),
           'id_departamento' => _('Departamento'),
           'id_municipio' => _('Municipio'),
           'id_clase'  => _('Clase'),
        );
    }



    var $fb_enumFields = array(
        'anionac', 'mesnac', 'dianac', 'sexo', 'tipodocumento'
    );
    var $es_enumOptions = array(
        'sexo' => array(
            'F' => 'Femenino',
            'M' => 'Masculino', 'S'=> 'SIN INFORMACIÓN'
        ),
        'tipodocumento' => array (
            'CC' => 'Cédula de Ciudadania',
            'CE' => 'Cédula de Extranjería',
            'RC' => 'Registro Civil',
            'TI' => 'Tarjeta de Identidad',
            'OT' => 'Otro',
        ),
        'mesnac' => array(
            1=>'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul',
            'Ago', 'Sep', 'Oct', 'Nov', 'Dic'
        ),
        'dianac' => array(
            '1' => '1', '2', '3', '4', '5', '6', '7',
            '8', '9', '10', '11', '12', '13', '14', '15', '16',
            '17', '18', '19', '20', '21', '22', '23', '24', '25',
            '26', '27', '28', '29', '30', '31'
        ),
        'anionac' => array(),
    );
    var $fb_addFormHeader = false;
    var $fb_selectAddEmpty = array();
    var $fb_fieldsRequired = array('nombres');
    var $fb_useMutators = true;
    var $fb_hidePrimaryKey = true;
    var $fb_linkDisplayFields = array('nombres', 'apellidos');

    /**
     * Funciona legada
     * Como ocurria en FormBuilder 0.10, hasta versión 1.121 en el
     * CVS de FormBuilder.php. Cambio sucitado por bug #3469
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
     * Modifica el año de nacimiento antes de incluirlo en base de datos.
     * Para funcionar con versiones nuevas de DB_DataObject requiere
     * <b>useMutator</b> en <b>true</b>
     *
     * @param string $value Valor recibido de formulario
     *
     * @return Valor para base de datos
     */
    function setanionac($value)
    {
        $this->anionac= ($value == 0) ? null : $value;
    }

    /**
     * Modifica el mes de nacimiento antes de incluirlo en base de datos.
     * Para funcionar con versiones nuevas de DB_DataObject requiere
     * <b>useMutator</b> en <b>true</b>
     *
     * @param string $value Valor recibido de formulario
     *
     * @return Valor para base de datos
     */
    function setmesnac($value)
    {
        $this->mesnac= ($value == 0) ? null : $value;
    }

    /**
     * Modifica el dia de nacimiento antes de incluirlo en base de datos.
     * Para funcionar con versiones nuevas de DB_DataObject requiere
     * <b>useMutator</b> en <b>true</b>
     *
     * @param string $value Valor recibido de formulario
     *
     * @return Valor para base de datos
     */
    function setdianac($value)
    {
        $this->dianac= ($value == 0) ? null : $value;
    }

    /**
     * Campos que pueden ser SIN INFORMACION y el código correspondiente
     *
     * @return array Arreglo de campos que pueden ser sin información
     */
    static function camposSinInfo()
    {
        return array(
            'sexo'=>'S',
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

        $formbuilder->enumOptionsCallback = array($this,
            "enumCallback"
        );
        $csin = $this->camposSinInfo();
        foreach ($csin as $c => $v) {
            if (!isset($this->$c)) {
                $this->$c = $v;
            }
        }
        $f = array();
        $aa = max(date('Y'), 2007);
        $anios = array();
        for ($i = 1900; $i <= $aa; $i++) {
            $anios[$i] = $i;
        }
        $this->es_enumOptions['anionac'] = $anios;
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
        $gr = array();

        $sel =& $form->getElement('id');
        //        $sel->freeze();
        $gr[] =& $sel;
        $form->removeElement('id');

        $tipdoc =& $form->getElement('tipodocumento');
        $form->removeElement('tipodocumento');
        $numdoc =& $form->getElement('numerodocumento');
        $form->removeElement('numerodocumento');
        if (in_array('numerodocumento', $this->fb_fieldsToRender)) {
            if (isset($this->tipodocumento)) {
                $tipdoc->setValue($this->tipodocumento);
            }
            $gr[] =& $tipdoc;

            $numdoc->setSize(30);
            $numdoc->setMaxlength(50);
            $gr[] =& $numdoc;

            $form->addGroup(
                $gr, 'numerodocumento', _('Documento de Identidad'),
                '&nbsp;', false
            );
        }

        $gr = array();
        $sel =& $form->getElement('nombres');
        $sel->setSize(30);
        $sel->setMaxlength(100);
        $sel->updateAttributes(
            array('id' => "nombres-persona")
        );
        $gr[] =& $sel;
        $form->removeElement('nombres');

        $sel =& $form->getElement('apellidos');
        $sel->setSize(30);
        $sel->setMaxlength(100);
        $sel->updateAttributes(
            array('id' => "apellidos-persona")
        );
        $gr[] =& $sel;
        $form->removeElement('apellidos');

        if ($this->id == null) {
            $sel =& $form->createElement(
                'static','','',
                "<a href=\"javascript:abrirBusquedaPersona('persona')\">" .
                "Buscar persona</a>"
            );
            $gr[] =& $sel;
        }

        $form->addGroup(
            $gr, 'nom', _('Nombres y Apellidos'),
            '&nbsp;', false
        );

        $gr = array();

        $sel =& $form->getElement('anionac');
        $gr[] =& $sel;
        $form->removeElement('anionac');

        $sel =& $form->getElement('mesnac');
        $gr[] =& $sel;
        $form->removeElement('mesnac');

        $sel =& $form->getElement('dianac');
        $gr[] =& $sel;
        $form->removeElement('dianac');

        $sel =& $form->getElement('sexo');
        $gr[] =& $sel;
        $form->removeElement('sexo');

        $seln =& $form->createElement('static', 'pi', '', '(Edad:');
        $gr[] =& $seln;

        $seln =& $form->createElement('text', 'edad', 'Edad');
        $seln->setSize(3);
        $seln->setMaxlength(3);
        $gr[] =& $seln;
        if ($this->anionac != null && $this->anionac > 0) {
            $na ='19';  // el valor lo pone formularioValores de PagVictimaIndividual
            //            $na = edad_de_fechanac($this->anionac, $aniohecho, $this->mesnac, $meshecho, $this->dianac, $diahecho)
            $seln->setValue($na);
            $seln->freeze();
        }
        $seln =& $form->createElement('static', 'pd', '', ')');
        $gr[] =& $seln;

        $form->addGroup(
            $gr, 'nacimiento', _('Fecha Nac. y Sexo'),
            '&nbsp;', false
        );

        $form->addElement('hidden', 'aniocaso', '', '');
        $form->addElement('hidden', 'mescaso', '', '');
        $form->addElement('hidden', 'diacaso', '', '');
    }


    /**
     * Modifica cantidad de hijos antes de incluirlo en base de datos.
     *
     * @param string $value Valor recibido de formulario
     *
     * @return Valor para base de datos
     */
    function sethijos($value)
    {
        $this->hijos= ($value == '') ? 'null' : $value;
    }


    /** Convierte registro a relato (arreglo de elementos) que agrega a $ar
     * dad son datos adicionales que pueden requerirse para la conversión.
     */
    function aRelato(&$ar, $dad = array())
    {
        parent::aRelato($ar, $dad);
        if ($this->id_departamento != null && $this->id_municipio != null) {
            $dmun = objeto_tabla('municipio');
            $dmun->id_departamento = $this->id_departamento;
            $dmun->id = $this->id_municipio;
            $dmun->find(1);
            $dmun->fetch();
            $ar['observaciones{tipo->municipio}'] = $dmun->nombre;
            if ($this->id_clase != null) {
                $dcla = objeto_tabla('clase');
                $dcla->id_departamento = $this->id_departamento;
                $dcla->id_municipio = $this->id_municipio;
                $dcla->id = $this->id_clase;
                $dcla->find(1);
                $dcla->fetch();
                $ar['observaciones{tipo->clase}'] = $dcla->nombre;
            }
        }
        return $ar;
    }

    /**
     * Valida datos de persona recibidos por formulario
     * @param string $fecharef Fecha de referencia para calcular año nac.
     * @param bool   $valrango Decide si se valida/autocompleta rango de edad
     * @param array  &$valores Valores recibidos en formulario
     * @param string &$merr    Colchon para mensajes de error
     *
     * @return bool Verdadero sii valida bien y autocompleta valores
     *    (edad, anionac, mesnac, dianac, id_rango_edad),
     *    si hay error de validación queda en merr
     */
    function valida($fecharef, $valrango, &$valores, &$merr)
    {
        if (!is_array($fecharef)) {
            $fecharef = fecha_a_arr($fecharef);
        }
        $fhanio = $fecharef['Y'];
        $fhmes = $fecharef['M'];
        $fhdia = $fecharef['d'];

        if ((int)$valores['edad'] > 0
            && (!isset($valores['anionac']) || $valores['anionac'] == '')
                ) {
                    $valores['anionac'] = $fhanio - (int)$valores['edad'];
                }
        if ($valrango
            && $valores['id_rango_edad'] != DataObjects_Rango_edad::idSinInfo()
                && $valores['anionac'] != ''
            ) {
                $r = (int)$valores['id_rango_edad'];
                //print_r($valores);
                $e = edad_de_fechanac(
                    (int)$valores['anionac'], $fhanio,
                    (int)$valores['mesnac'], $fhmes,
                    (int)$valores['dianac'], $fhdia
                );
                if (!verifica_edad_y_rango($e, $r)) {
                    $merr = _("La edad ") . (int)$e .
                        _(" (fecha del hecho menos fecha de nacimiento) ") .
                        _(" debe corresponder al rango de edad");
                    return false;
                }
            } else if ($valrango && $valores['anionac'] != '') { //Autocompleta
                $e = edad_de_fechanac(
                    (int)$valores['anionac'], $fhanio,
                    (int)$valores['anionac'], $fhmes,
                    (int)$valores['dianac'], $fhdia
                );
                $re = rango_de_edad($e);
                if ($re == 0) {
                    $merr = _(
                        'La fecha de nacimiento no corresponde a un rango'
                    );
                    return false;
                }
                $valores['id_rango_edad'] = $re;
            }
        return true;
    }

}

?>
