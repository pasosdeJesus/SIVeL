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
 * Definicion para la tabla usuario.
 */
require_once 'DB_DataObject_SIVeL.php';
require_once 'confv.php';

/**
 * Definicion para la tabla usuario.
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Usuario extends DB_DataObject_SIVeL
{

    var $__table = 'usuario';                         // table name
    var $id_usuario;                      // varchar(-1)  not_null primary_key
    var $password;                        // varchar(-1)  not_null
    var $nombre;                          // varchar(-1)
    var $descripcion;                     // varchar(-1)
    var $id_rol;                          // int4(4)
    var $dias_edicion_caso;               // int4(4)
    var $idioma;


    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

        $this->nom_tabla = _("Usuario");
        $this->fb_fieldLabels= array(
            'id_usuario' => _('Identificación'),
            'password' => _('Clave'),
            'nombre' => _('Nombre'),
            'descripcion' => _('Descripcion'),
            'id_rol' => _('Rol'),
            'idioma' => _('Idioma'),
        );
        global $LENGDISP, $ROLESDISP;
        $ld = explode(" ", $LENGDISP);
        foreach ($ld as $l) {
            $this->es_enumOptions['idioma'][$l] = $l;
        }
        $rd = explode(" ", $ROLESDISP);
        foreach ($rd as $er) {
            $pr = explode(",", $er);
            if ((int)$pr[0] <= 0) {
                die_esc("Identficacion de rol errada $er en \$ROLESDISP");
            }
            $this->es_enumOptions['id_rol'][$pr[0]] = $pr[1];
        }
    }

    var $fb_preDefOrder = array(
        'id_usuario', 'password', 'nombre', 'descripcion', 'id_rol',
        'idioma'
    );
    var $fb_fieldsToRender = array(
        'id_usuario', 'password', 'nombre', 'descripcion', 'id_rol',
        'idioma'
    );
    var $fb_linkDisplayFields = array('id_usuario');
    var $fb_select_display_field= 'id_usuario';
    var $fb_hidePrimaryKey = false;

    var $fb_enumFields = array('id_rol', 'idioma');


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
     * Pone un valor en la base diferente al recibido del formulario.
     *
     * @param string $value Valor en formulario
     *
     * @return Valor para BD
     */
    function setpassword($value)
    {
        if ($value == '') {
            $this->password = null;
        } else {
            $this->password = sha1($value);
        }
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
        $e = HTML_QuickForm::createElement(
            'password', 'password', _('Clave')
        );
        $this->fb_preDefElements = array('password' => $e);
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
        $e =& $form->getElement('password');
        $e->setValue('');
    }

}

?>
