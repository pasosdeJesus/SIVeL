<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
/**
 * Clase base para objetos que representan registros/tablas
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2009 Dominio p�blico. Sin garant�as.
 * @license   Dominio p�blico. Sin garant�as.
 * @version   CVS: $Id: DB_DataObject_SIVeL.php,v 1.27.2.2 2011/09/14 14:56:18 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

require_once "DB/DataObject.php";
require_once "aut.php";
require_once $_SESSION['dirsitio'] . "/conf.php";


/**
 * Objeto tabla
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 */
abstract class DB_DataObject_SIVeL extends DB_DataObject
{

    /**
     * Nombre de la tabla en SQL y min�sculas
     */
    var $__table = 'nom_tabla';

    /**
     * Nombre de la tabla para presentar en formularios (puede incluir espacios)
     */
    var $nom_tabla = 'nombre de la tabla';

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
     * Retorna un valor. Est�tico.
     *
     * @param mixed $k Llave
     * @param mixed $v Valor
     *
     * @return object Registro cuya llave sea el valor.
     */
    function staticGet($k, $v = null)
    {
        return DB_DataObject::staticGet(
            'DataObjects_' .
            ucfirst($this->__table), $k, $v
        );
    }


    /**
     * Funciona legada
     * Como ocurria en FormBuilder 0.10, hasta versi�n 1.121 en el
     * CVS de FormBuilder.php. Cambio sucitado por bug #3469
     *
     * @param string $table Tabla
     * @param string $key   Llave
     *
     * @return string opci�n enumerada asociada a la llave.
     */
    function enumCallback($table, $key)
    {
        return $this->es_enumOptions[$key];
    }


    /**
     * Opciones de fecha para un campo
     *
     * @param string &$field campo
     *
     * @return array arreglo de opciones
     */
    function dateOptions(&$field)
    {
        return array('language' => 'es',
        'format' => 'dMY',
        'minYear' => $GLOBALS['anio_min'],
        'maxYear' => 2025
        );
    }


    /**
     * Identificacion de registro 'SIN INFORMACI�N'
     *
     * @return integer C�digo del registro SIN INFORMACI�N
     **/
    static function idSinInfo()
    {
        return -1;
    }


    /**
     * Campos que pueden ser SIN INFORMACION y el c�digo correspondiente
     *
     * @return array Arreglo de campos que pueden ser sin informaci�n
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
            echo _("Definir etiquetas en fb_fieldLabels<br>");
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
        }
        $f =& $form->getElement('fechadeshabilitacion');
        if (!PEAR::isError($f) && !isset($this->fechadeshabilitacion)) {
            $f->_elements[0]->setValue('');
            $f->_elements[1]->setValue('');
            $f->_elements[2]->setValue('');
        }
        //fechacreacion la dejamos en valor por defecto

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
     * @param array $dad Datos adicionales par la conversi�n.
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


}

?>
