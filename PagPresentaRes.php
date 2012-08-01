<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Página del multi-formulario para consulta externa
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2005 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

/**
 * Pestaña Presentación de Resultados de la consulta externa
 */
require_once $_SESSION['dirsitio'] . "/conf.php";
require_once 'PagBaseSimple.php';
require_once 'HTML/QuickForm/Renderer/Default.php';

/**
 * Renderer para este formulario
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
*/
class Doble_Renderer extends HTML_QuickForm_Renderer_Default
{
    var $_headerTemplate = "";
}

/**
 * Presentación de resultados.
 * Ver documentación de funciones en clase base.
 * A diferencia de las demás páginas del formulario de captura, esta sólo
 * se presenta para búsquedas y no mantiene la información en la base de
 * datos sino en variables de sesión.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      PagBaseSimple
 */
class PagPresentaRes extends PagBaseSimple
{

    var $clase_modelo = '';

    /* Objetos DB_DataObject_FormBuilder */

    /** Opciones de presentación */
    var $opciones;


    /**
     * Constructora.
     * Ver documentación completa en clase base.
     *
     * @param string $nomForma Nombre
     * @param string $opciones Opciones
     *
     * @return void
     */
    function PagPresentaRes($nomForma, $opciones)
    {
        parent::PagBaseSimple($nomForma);
        $this->titulo = _('Forma de Presentar Resultados');

        $this->opciones = $opciones;
        if (!isset($_SESSION['busca_presenta'])) {
            $_SESSION['busca_presenta']['ordenar'] = 'fecha';
            $_SESSION['busca_presenta']['mostrar'] = 'tabla';
            foreach ($GLOBALS['cw_ncampos'] as $idc => $dc) {
                $_SESSION['busca_presenta'][$idc]=1;
            }
            $_SESSION['busca_presenta']['m_fuentes']=0;
            $_SESSION['busca_presenta']['m_varlineas']=1;
        }
        $this->addAction('anterior', new Anterior());
    }

    /**
     * Agrega elementos al formulario.
     * Ver documentación completa en clase base.
     *
     * @param handle &$db    Conexión a base de datos
     * @param string $idcaso Id del caso
     *
     * @return void
     *
     * @see PagBaseSimple
     */
    function formularioAgrega(&$db, $idcaso)
    {
        $ult = $_SESSION['busca_presenta'];

        $ae = array();
        $x =& $this->createElement('radio', 'ordenar', 'fecha', 'Fecha', 'fecha');
        $ae[] =&  $x;
        if ($ult['ordenar'] == '' || $ult['ordenar'] == 'fecha') {
            $t =& $x;
        }
        $x =& $this->createElement(
            'radio', 'ordenar', 'ubicacion',
            _('Ubicación'), 'ubicacion'
        );
        $ae[] =& $x;
        if ($ult['ordenar'] == 'ubicacion') {
            $t =& $x;
        }
        $x =& $this->createElement(
            'radio', 'ordenar', 'codigo',
            _('Código'), 'codigo'
        );
        $ae[] =& $x;
        if ($ult['ordenar'] == 'codigo') {
            $t =& $x;
        }

        $this->addGroup($ae, null, _('Ordenar por'), '&nbsp;', false);
        $t->setChecked(true);

        $ae = array();
        $t =& $this->createElement(
            'radio', 'mostrar', 'tabla', _('Tabla'), 'tabla'
        );
        $ae[] =&  $t;

        $x =& $this->createElement(
            'radio', 'mostrar', 'csv', _('CSV'), 'csv'
        );
        $ae[] =&  $x;
        if (isset($ult['mostrar']) && $ult['mostrar'] == 'revista') {
            $t =& $x;
        }

        if (isset($this->opciones)) {
            if (in_array(41, $this->opciones)) {
                $x =&  $this->createElement(
                    'radio', 'mostrar', 'revista',
                    'Reporte Revista', 'revista'
                );
                $ae[] =& $x;
                if (isset($ult['mostrar']) && $ult['mostrar'] == 'revista') {
                    $t =& $x;
                }
            }
            if (in_array(42, $this->opciones)) {
                $x =&  $this->createElement(
                    'radio', 'mostrar',
                    'general', _('Reporte General'), 'general'
                );
                $ae[] =& $x;
                if (isset($ult['mostrar'])
                    && $ult['mostrar'] == 'general'
                ) {
                    $t =& $x;
                }
                $x =&  $this->createElement(
                    'radio', 'mostrar',
                    'actos', _('Actos'), 'actos'
                );
                $ae[] =& $x;
                if (isset($ult['mostrar'])
                    && $ult['mostrar'] == 'actos'
                ) {
                    $t =& $x;
                }

                $x =&  $this->createElement(
                    'radio', 'mostrar',
                    'relato', _('Relato XML'), 'relato'
                );
                $ae[] =& $x;
                if (isset($ult['mostrar'])
                    && $ult['mostrar'] == 'relato'
                ) {
                    $t =& $x;
                }

                $x =&  $this->createElement(
                    'radio', 'mostrar',
                    'relatoslocal', _('Relatos XML en disco local'),
                    'relatoslocal'
                );
                $ae[] =& $x;
                if (isset($ult['mostrar'])
                    && $ult['mostrar'] == 'relatoslocal'
                ) {
                    $t =& $x;
                }

            }
        }
        $this->addGroup($ae, null, _('Forma'), '&nbsp;', false);
        $t->setChecked(true);

        $asinc = array();
        if (isset($pSinCampos) && $pSinCampos != '') {
            $asinc = explode(',', $pSinCampos);
        }
        $prevnext = array();
        foreach ($GLOBALS['cw_ncampos'] as $idc => $dc) {
            $sel =& $this->createElement(
                'checkbox',
                $idc, $dc, $dc
            );
            if (!in_array($idc, $ult) && isset($ult[$idc]) && $ult[$idc] == 1) {
                $sel->setValue(true);
            }
            $prevnext[] =& $sel;
        };

        if (in_array(42, $this->opciones)) { // Podría ver rep. gen?
            $sel =& $this->createElement(
                'checkbox',
                'm_fuentes', _('Fuentes'), _('Fuentes')
            );
            if (!in_array('m_fuentes', $ult) && isset($ult['m_fuentes'])
                && $ult['m_fuentes'] == 1
            ) {
                $sel->setValue(true);
            }
            $prevnext[] =& $sel;

        }

        $this->addGroup($prevnext, null, _('Mostrar'), '&nbsp;', false);

        $prevnext = array();
        $sel =& $this->createElement(
            'checkbox',
            'm_varlineas', _('Memo en varias líneas'), 
            _('Memo en varias líneas')
        );
        if (!in_array('m_varlineas', $ult) && isset($ult['m_varlineas'])
            && $ult['m_varlineas'] == 1
        ) {
            $sel->setValue(true);
        }
        $prevnext[] =& $sel;
        $this->addGroup($prevnext, null, _('Detalles'), '&nbsp;', false);

        $cy = date('Y');
        if ($cy < 2005) {
            $cy = 2005;
        }
        $ay = explode('-', $GLOBALS['consulta_web_fecha_min']);
        $e =& $this->addElement(
            'date', 'fiini', _('Ingreso Desde: '),
            array(
                'language' => 'es',
                'addEmptyOption' => true,
                'minYear' => $ay[0],
                'maxYear' => $cy
            )
        );

        $e =& $this->addElement(
            'date', 'fifin', _('Ingreso Hasta:'),
            array(
                'language' => 'es', 'addEmptyOption' => true,
                'minYear' => $ay[0], 'maxYear' => $cy
            )
        );

        agrega_control_CSRF($this);

        $d = new Doble_Renderer();

        $this->accept($d);
    }


    /**
     * Llena valores del formulario.
     * Ver documentación completa en clase base.
     *
     * @param handle  &$db    Conexión a base de datos
     * @param integer $idcaso Id del caso
     *
     * @return void
     * @see PagBaseSimple
     */
    function formularioValores(&$db, $idcaso)
    {
    }

    /**
     * Elimina registros de tablas relacionadas con caso de este formulario.
     * Ver documentación completa en clase base.
     *
     * @param handle  &$db    Conexión a base de datos
     * @param integer $idcaso Id del caso
     *
     * @return void
     * @see PagBaseSimple
     */
    static function eliminaDep(&$db, $idcaso)
    {
    }

    /**
    * Suponemos que un formulario vació es válido pero no agrega
    * ni actualiza info en b.d
    *
    * @param array &$valores Valores enviados por usuario
    *
    * @return bool Verdadero si y solo logra procesar
    */
    function procesa(&$valores)
    {
        $_SESSION['busca_presenta'] = $valores;
        verifica_sin_CSRF($valores);

        return  true;
    }

    /**
     * Prepara consulta SQL para buscar datos de este formulario.
     * Ver documentación completa en clase base.
     *
     * @param string &$w       Consulta que se construye
     * @param string &$t       Tablas
     * @param string &$db      Conexión a base de datos
     * @param object $idcaso   Identificación de caso
     * @param string &$subcons Subconsulta
     *
     * @return void
     * @see PagBaseSimple
     */
    function datosBusqueda(&$w, &$t, &$db, $idcaso, &$subcons)
    {
    }

}

?>
