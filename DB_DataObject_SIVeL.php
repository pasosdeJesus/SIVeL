<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Clase base para objetos que representan registros/tablas
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2009 Dominio público. Sin garantías.
 * @license   Dominio público. Sin garantías.
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

require_once "DB/DataObject.php";
require_once "aut.php";
//require_once $_SESSION['dirsitio'] . "/conf.php";


/**
 * Objeto tabla
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
abstract class DB_DataObject_SIVeL extends DB_DataObject
{

    /**
     * Nombre de la tabla en SQL y minúsculas
     */
    var $__table = 'nom_tabla';

    /**
     * Nombre de la tabla para presentar en formularios (puede incluir espacios)
     */
    var  $nom_tabla = 'nombre de la tabla';

    /**
     * Constructora
     * return @void
     */
    public function __construct()
    { // __construct()
    }

    /**
     * Truco de compatibilidad con ZE2
     *
     * @return object copia del objeto
     */
    function __clone()
    {
        return $this;
    }

    /**
     * Retorna un valor. Estático.
     *
     * @param mixed $k Llave
     * @param mixed $v Valor
     *
     * @return object Registro cuya llave sea el valor.
     */
    function &doStaticGet($k, $v = null)
    {
        return DB_DataObject::staticGet(
            'DataObjects_' .
            ucfirst($this->__table), $k, $v
        );
    }


    /**
     * Funciona legada
     * Como ocurria en FormBuilder 0.10, hasta versión 1.121 en el
     * CVS de FormBuilder.php. Cambio sucitado por bug #3469
     *
     * @param string $table Tabla
     * @param string $key   Llave
     *
     * @return string opción enumerada asociada a la llave.
     */
    function enumCallback($table, $key)
    {
        return $this->es_enumOptions[$key];
    }


    /**
     * Opciones de fecha para un campo
     *
     * @param string $field campo
     *
     * @return array arreglo de opciones
     */
    function dateOptions($field)
    {
        $slan = "es";
        if (isset($_SESSION['LANG'])) {
            $slan = substr($_SESSION['LANG'], 0, 2);
        }

        return array(
            'language' => $slan,
            'format' => 'd-M-Y',
            'minYear' => $GLOBALS['anio_min'],
            'maxYear' => @date('Y')+10
        );
    }


    /**
     * Identificacion de registro 'SIN INFORMACIÓN'
     *
     * @return integer Código del registro SIN INFORMACIÓN
     **/
    static function idSinInfo()
    {
        return -1;
    }


    /**
     * Campos que pueden ser SIN INFORMACION y el código correspondiente
     *
     * @return array Arreglo de campos que pueden ser sin información
     */
    static function camposSinInfo()
    {
        return array(
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
                $this->$c = $csin[$c];
            }
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
        /*var_dump($form);
        debug_print_backtrace();*/
        if (!is_array($this->fb_fieldLabels)) {
            echo _("Definir etiquetas en fb_fieldLabels") . "<br>";
            print_r($this);
        } else {
            foreach ($this->fb_fieldLabels as $c => $et) {
                $e =& $form->getElement($c);
                if (isset($e) && !PEAR::isError($e)) {
                    if (isset($GLOBALS['etiqueta'][$c])) {
                        $e->setLabel($GLOBALS['etiqueta'][$c]);
                    }
                    if ($e->getType() == 'select') {
                        $e->_options = htmlentities_array($e->_options);
                    }
                }
            }
        }
        // Titulo
        $h =& $form->getElement('__header__');
        if (PEAR::isError($h)) {
            $h =& $form->getElement(null);
        }
        if (PEAR::isError($h)) {
            $h =& $form->addElement('header', '__header__', $this->nom_tabla);
        }        $f =& $form->getElement('fechadeshabilitacion');
        if (!PEAR::isError($f) && !isset($this->fechadeshabilitacion)) {
            $f->_elements[0]->setValue('');
            $f->_elements[1]->setValue('');
            $f->_elements[2]->setValue('');
        }
        //fechacreacion la dejamos en valor por defecto

        // Espacio amplio para campos de texto por defecto
        if (isset($this->fb_textFields)) {
            foreach ($this->fb_textFields as $t) {
                $e =& $form->getElement($t);
                if (isset($e) && !PEAR::isError($e)) {
                    $e->setCols(75);
                    $e->setRows(2);
                }
            }
        }

    }

    /**
     * Cadena para un texto XML que identifica el registro
     *
     * @return string Cadena para relato
     **/
    function valorRelato()
    {
        $sep = $r = "";
        foreach ($this->fb_linkDisplayFields as $v) {
            $r .= $sep . $this->$v;
            $sep = ":";
        }
        return $r;
    }

    /**
     * Convierte registro a relato (arreglo de elementos)
     *
     * @param array &$ar Arreglo con datos que se completan para convertir
     * posteriormente a XML
     * @param array $dad Datos adicionales par la conversión.
     *
     * @return void  No retorna un dato pero modifica $ar
     */
    function aRelato(&$ar, $dad = array())
    {
        $alfinal = array();
        foreach ($this->fb_fieldsToRender as $v) {
            $mandarfinal = false;
            $val = $this->$v;
            $nomc = $v;
            if (isset($dad[$v])) {
                $cmds = explode(';', $dad[$v]);
                foreach ($cmds as $cmd) {
                    switch ($cmd) {
                    case 'REL':
                        $o = $this->getLink($v);
                        $val = '';
                        if (!PEAR::isError($o) && isset($o->nombre)) {
                            $val = $o->nombre;
                        }
                        break;
                    default:
                        $nomc = $cmd;
                        if (substr($cmd, 0, 13) == 'observaciones') {
                            $mandarfinal = true;
                        }
                        break;
                    }
                }
            }
            if ($mandarfinal) {
                $alfinal[$nomc] = $val;
            } else {
                $ar[$nomc] = $val;
            }
        }
        foreach ($alfinal as $k => $v) {
            $ar[$k] = $v;
        }
    }

    /**
     * Mezcla automáticamente datos de otro objeto
     *
     * @param object $otro DataObject que se mezcla con este
     * @param string &$obs Colchón para observaciones
     *
     * @return void Mezcla $otro en $this y agrega observaciones a $obs
     */
    function mezclaAutom($otro, &$obs)
    {
        $alfinal = array();
        $t = $this->table();
        foreach ($this->fb_fieldLabels as $v => $et) {
            if ($otro->$v != $this->$v) {
                if ((($t[$v] & DB_DATAOBJECT_STR)
                    || ($t[$v] & DB_DATAOBJECT_TXT))
                    && !($t[$v] & DB_DATAOBJECT_DATE)
                ) {
                    //$obs .= " OJO texto";
                    if (trim($otro->$v) != ""
                        && strstr($this->$v, $otro->$v) == false
                    ) {
                        // Si no está concatenamos texto
                        if (trim($this->$v) == "") {
                            $this->$v = $otro->$v;
                            $obs .= " ={$this->__table}.$v";
                        } else {
                            $this->$v .= ". " . $otro->$v;
                            $obs .= " +{$this->__table}.$v";
                        }
                    }
                } elseif ($t[$v] == DB_DATAOBJECT_BOOL) {
                    // O lógico para booleanos
                    $this->$v |= $otro->$v;
                    $obs .= " +{$this->__table}.$v: \"" .
                        ($otro->$v ? 'V' : 'F') . "\"";
                } elseif ($this->$v == null) {
                    // Si es vacío mezclamos
                    $this->$v = $otro->$v;
                    $obs .= " ={$this->__table}.$v: \"" .
                        $otro->$v . "\"";
                } elseif ($otro->$v == null || $otro->$v === "") {
                } else {
                    $l =$this->links();
                    if (isset($l[$v])) {
                        $tr = $this->getLink($v);
                        $tro = $otro->getLink($v);
                        $vpm = $otro->$v;
                        if (isset($tro->nombre)) {
                            $vpm = $tro->nombre;
                        }
                        $sin = -1;
                        if (method_exists($tr, "idSinInfo")) {
                            $sin = $tr->idSinInfo();
                        }
                        if ($sin != -1 && $this->$v == $sin) {
                            // Actual es SIN INFO
                            $this->$v = $otro->$v;
                            $obs .= " S={$this->__table}.$v: \"" .
                                $vpm . "\"";
                        } elseif (isset($tr->nombre)
                            && $tr->nombre == "POR DETERMINAR"
                        ) {
                            $this->$v = $otro->$v;
                            $obs .= " P={$this->__table}.$v: \"" .
                                $vpm . "\"";
                        } else {
                            $obs .= " No se mezcló {$this->__table}.$v: \""
                                . $vpm . "\"";
                        }
                    } else {
                        // Los demás tipos no los podemos mezclar
                        $obs .= " No se puede mezclar {$this->__table}.$v: \"" .
                            $otro->$v . "\"";
                    }
                }
            }
        }
    }


}

?>
