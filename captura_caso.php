<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Ficha para capturar casos (también utilizable para buscar).
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2005 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 * @link      http://www.21st.de/downloads/rapidprototyping.pdf
 */

/**
 * Ficha para capturar casos (también utilizable para buscar).
 */
require_once "aut.php";
require_once "confv.php";
require_once $_SESSION['dirsitio'] . "/conf.php";

global $dsn;
if (!isset($dsn)) {
    die("Se esperaba que dsn estuviera definido");
}
$aut_usuario = "";
$accno = "";
autentica_usuario($dsn, $aut_usuario, 31);

require_once $_SESSION['dirsitio'] . "/conf_int.php";

require_once 'HTML/QuickForm/Controller.php';
require_once 'HTML/QuickForm/Action/Direct.php';
require_once 'HTML/QuickForm/Action/Display.php';
require_once 'HTML/QuickForm/Action/Next.php';
require_once 'HTML/QuickForm/Action/Back.php';
require_once 'HTML/QuickForm/Action/Jump.php';
require_once 'HTML/QuickForm/header.php';
require_once 'HTML/QuickForm/date.php';
require_once 'HTML/QuickForm/text.php';
require_once 'HTML/QuickForm.php';
require_once 'HTML/QuickForm/Page.php';
require_once 'HTML/CSS.php';

foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
    list($n, $c, $o) = $tab;
    // @codingStandardsIgnoreStart
    require_once "$c.php";
    // @codingStandardsIgnoreEnd
}
require_once 'PagPresentaRes.php';
if (isset($GLOBALS['PagPresentaRes'])) {
    include_once $GLOBALS['PagPresentaRes'] . '.php';
}


/**
 * Presenta formulario.
 * Referencia: pear/lib/HTML/Progress/generator/default.php
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
*/
class PresentaFormulario extends HTML_QuickForm_Action_Display
{

    /**
     * Presenta formulario
     *
     * @param object &$pag Página
     *
     * @return void
     */
    function _renderForm(&$pag)
    {
        $nomPag = $pag->getAttribute('name');
        $css = new HTML_CSS();
        $css->setStyle('body', 'background-color', '#FFFFFF');
        $css->setStyle('body', 'font-family', 'Arial');
        $css->setStyle('body', 'font-size', '10pt');
        $css->setStyle('h1', 'color', '#000FFC');
        $css->setStyle('h1', 'text-align', 'center');
        $css->setStyle('.maintable', 'width', '100%');
        $css->setStyle('.maintable', 'border-width', '0');
        $css->setStyle('.maintable', 'border-color', '#D0D0D0');
        $colfon = isset($GLOBALS['ficha_color_fondo']) ?
            $GLOBALS['ficha_color_fondo'] : '#EEE';
        $css->setStyle('.maintable', 'background-color', $colfon);
        $css->setStyle('th', 'text-align', 'center');
        $css->setStyle('th', 'color', '#FFC');
        $css->setStyle('th', 'background-color', '#AAA');
        $css->setStyle('th', 'white-space', 'nowrap');
        $css->setStyle('input', 'font-family', 'Arial');
        $css->setStyle('input.flat', 'font-size', '10pt');
        $css->setStyle('input.flat', 'border-width', '2px 2px 0px 2px');
        $css->setStyle('input.flat', 'border-color', '#996');
        // http://www.w3.org/TR/html5-diff/
        $enc = '<!doctype html>
<html>
<head>
<meta charset = "UTF-8">
<title>Ficha caso</title>
<link rel="stylesheet" 
    href="lib/jqueryui-1.10.3/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="estilo.css" />
<style type="text/css">
{%style%}
.ui-autocomplete-loading {
    background: white url(\'imagen/ajax-loader.gif\') right center no-repeat;
}
.ui-autocomplete {
    max-height: 100px;
    overflow-y: auto;
    overflow-x: hidden;
}
/* IE 6 no soporta max-height */
* html .ui-autocomplete {
    height: 100px;
}
</style>
<script src="lib/jquery-2.0.3.min.js"></script>
<script src="lib/jqueryui-1.10.3/jquery-ui.min.js"></script>
<script src="lib/jquery.watermark.min.js"></script>
<script type="text/javascript" src="sivel.js" type="text/javascript"></script>
<script type="text/javascript">
<!--
{%javascript%}
-->
</script>
<body>';
        $enc= str_replace('{%style%}', $css->toString(), $enc);
        $js = "";
        foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
            list($n, $c, $o) = $tab;
            //echo "OJO n=$n, c=$c, o=$o<br>\n";
            if (($d = strrpos($c, "/"))>0) {
                $c = substr($c, $d+1);
            }
            if (is_callable(array($c, 'encJavascript'))) {
                call_user_func_array(array($c, 'encJavascript'), array(&$js));
            } else {
                echo_enc("Falta encJavascript en $n, $c");
            }
        }
        $js .= 'function envia(que) ' . "{\n " .
            " document.forms[0]._qf_default.value = que;\n" .
            " document.forms[0].submit(); \n}";
        $enc = str_replace('{%javascript%}', $js, $enc);

        $renderer =& $pag->defaultRenderer();

        $renderer->setFormTemplate(
            $enc . '<table class="maintable" ' .
            'align = "left">' .
            '<colgroup><col width = "150" style = "colprin1"/>' .
            '<col/></colgroup>' .
            '<form{attributes}>{content}</form></table>'
        );
        $renderer->setHeaderTemplate('<tr><th colspan = "2">{header}</th></tr>');
        $renderer->setGroupTemplate('<table><tr>{content}</tr></table>', 'name');
        $renderer->setGroupElementTemplate(
            '<td>{element}<br />' .
            '<span class="qfLabel">{label}</span></td>', 'name'
        );
        $renderer->setElementTemplate(
            "\n\t<tr>\n\t\t<td valign=\"top\" " .
            "align=\"left\" colspan=\"2\"><!-- BEGIN error -->" .
            "<span style=\"color: #ff0000\">{error}</span><br />" .
            "<!-- END error -->\t{element}</td>\n\t</tr>", 'tabs'
        );
        $renderer->setElementTemplate(
            "\n\t<tr>\n\t\t<td valign=\"top\" " .
            "align=\"center\" colspan=\"2\"><!-- BEGIN error -->" .
            "<span style=\"color: #ff0000\">{error}</span><br />" .
            "<!-- END error -->\t{element}</td>\n\t</tr>", 'buttons'
        );
        $pag->accept($renderer);

        echo $renderer->toHtml();

        echo "</body></html>";
    }
}


/**
 * Controlador.
 * Con base en pear/lib/HTML/Progress/generator.php
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
*/
class CapturaCaso extends HTML_QuickForm_Controller
{

    var $_botonAnterior  = '<< Anterior';
    var $_botonSiguiente= 'Siguiente >>';
    var $_botonElimina  = 'Elimina caso';
    var $_botonReporte  = 'Val. y Rep. Gen.';
    var $_botonMenu     = 'Menú';
    var $_botonCasoNuevo = 'Caso nuevo';
    var $_botonBusqueda = 'Buscar';
    var $_attrBoton     = array('style'=>'width:85px; padding:0; ');

    var $_tabs;
    var $opciones;

    /**
     * Constructora
     *
     * @param array $opciones Opciones
     *
     * @return void
     */
    function CapturaCaso($opciones)
    {
        $this->_botonAnterior   = _('<< Anterior');
        $this->_botonSiguiente = _('Siguiente >>');
        $this->_botonElimina   = _('Elimina caso');
        $this->_botonReporte   = _('Val. y Rep. Gen.');
        $this->_botonMenu      = _('Menú');
        $this->_botonCasoNuevo  = _('Caso Nuevo');
        $this->_botonBusqueda  = _('Buscar');

        $this->opciones = $opciones;
        $this->_modal = false;
        $this->_tabs = $GLOBALS['ficha_tabuladores'];
        if (isset($_SESSION['forma_modo'])
            && $_SESSION['forma_modo'] == 'busqueda'
        ) {
            $ppr = isset($GLOBALS['PagPresentaRes']) ?
                $GLOBALS['PagPresentaRes'] : 'PagPresentaRes';
            $this->_tabs[] = array('presentacion', $ppr,
                'Forma Resultados'
            );
        }

        $nobus = !isset($_SESSION['forma_modo'])
            || $_SESSION['forma_modo'] != 'busqueda' ;
        $mreq = '<span style = "font-size:80%; color:#ff0000;">*</span>' .
            '<span style = "font-size:80%;"> '
            . _('marca un campo requerido') . '</span>';
        foreach ($this->_tabs as $tab) {
            list($pag, $cl) = $tab;
            if (($d = strrpos($cl, "/"))>0) {
                $cl = substr($cl, $d+1);
            }
            if ($pag == "presentacion") {
                $clpag =& new $cl($pag, $opciones);
            } else {
                $clpag =& new $cl($pag);
            }
            if (!isset($GLOBALS['etiqueta'][$cl])) {
                $GLOBALS['etiqueta'][$cl] = $clpag->titulo;
            }
            $this->addPage($clpag);
            $this->addAction($pag, new Salta());
        }

        $this->addAction('reporte', new ReporteGeneral());
        $this->addAction('menu', new Terminar());
        $this->addAction('elimina_caso', new EliminaCaso());
        $this->addAction('casonuevo', new CasoNuevo());
        $this->addAction('display', new PresentaFormulario());
        $this->addAction('next', new HTML_QuickForm_Action_Next());
        $this->addAction('back', new HTML_QuickForm_Action_Back());
        $this->addAction('jump', new HTML_QuickForm_Action_Jump());

        $this->addAction('process', new Terminar());

        $this->addAction('buscar', new BuscarId());

        // set ProgressBar default values on first run
        $sess = $this->container();
        $defaults = $sess['defaults'];

        if (count($sess['defaults']) == 0) {
            $this->setDefaults(
                array(
                'borderpainted' => false,
                'borderclass'   => 'progressBarBorder',
                'borderstyle'   => array('style' => 'solid',
                    'width' => 0, 'color' => '#000000'
                ),
                'cellid'        => 'progressCell%01s',
                'cellclass'     => 'cell',
                'cellvalue'     => array('min' => 0, 'max' => 100, 'inc' => 1),
                'cellsize'      => array('width' => 15, 'height' => 20,
                    'spacing' => 2, 'count' => 10
                ),
                'cellcolor'     => array('active' => '#006600',
                    'inactive' => '#CCCCCC'
                ),
                'cellfont'      => array('family' => 'Courier, Verdana',
                    'size' => 8, 'color' => '#000000'
                ),
                'stringpainted' => false,
                'stringid'      => 'installationProgress',
                'stringsize'    => array('width' => 50, 'height' => '',
                    'bgcolor' => '#FFFFFF'
                ),
                'stringvalign'  => 'right',
                'stringalign'   => 'right',
                'stringfont'    => array('family' =>
                    'Verdana, Arial, Helvetica, sans-serif',
                    'size' => 10, 'color' => '#000000'
                ),
                'phpcss'        => array('P'=>true)
            )
            );
        }
    }


    /**
     * Crea Tabuladores en Ficha.
     * Adaptada de Progress/generator.php
     *
     * @param object &$page      Página
     * @param array  $attributes Atributos
     *
     * @return void
     */
    function crea_tabuladores(&$page, $attributes = null)
    {
        $here = $attributes = HTML_Common::_parseAttributes($attributes);
        $here['disabled'] = 'disabled';
        $pageName = $page->getAttribute('name');
        $jump = array();

        foreach ($this->_tabs as $tab) {
            list($event, $cls) = $tab;
            if (($d = strrpos($cls, "/"))>0) {
                $cls = substr($cls, $d+1);
            }
            $ocls = new $cls("");
            $titulo = isset($GLOBALS['etiqueta'][$cls]) ?
                $GLOBALS['etiqueta'][$cls] :
                isset($ocls->titulo) ? $ocls->titulo : "Título";
            //echo "OJO cls=$cls, ocls->titulo= {$ocls->titulo}, titulo=$titulo<br>";
            //var_dump($varc);
            $attrs = ($pageName == $event) ? $here : $attributes;
            $jump[] =& $page->createElement(
                'submit',
                $page->getButtonName($event), $titulo,
                HTML_Common::_getAttrString($attrs)
            );
        }
        $page->addGroup($jump, 'tabs', '', '&nbsp;', false);
    }


    /**
     * Agrega botones a una página.
     * Adaptada de Progress/generator.php
     *
     * @param object &$page      HTML_QuicForm_Page página por cambiar
     * @param array  $buttons    Botones por agregar
     * @param array  $attributes Atributos
     *
     * @return void
     */
    function creaBotones(&$page, $buttons, $attributes = null)
    {
        $confirm = $attributes = HTML_Common::_parseAttributes($attributes);
        $confirm['onClick'] = "return(confirm('Are you sure ?'));";

        $prevnext = array();

        foreach ($buttons as $event => $label) {
            if ($event == 'cancel') {
                $type = 'submit';
                $attrs = $confirm;
            } elseif ($event == 'reset') {
                $type = 'reset';
                $attrs = $confirm;
            } else {
                $type = 'submit';
                $attrs = $attributes;
            }
            $prevnext[] =& $page->createElement(
                $type,
                $page->getButtonName($event), $label,
                HTML_Common::_getAttrString($attrs)
            );
        }
        $page->addGroup($prevnext, 'buttons', '', '&nbsp;', false);
    }


    /**
     * Crea botones de la parte inferior de la ficha.
     *
     * @param object &$page HTML_QuickForm_Page Página con ficha.
     *
     * @return void
     */
    function creaBotonesEstandar(&$page)
    {
        $page->addElement('header', null, '&nbsp; ');
        $botones = array('anterior'   => $this->_botonAnterior,
        'reporte'  => $this->_botonReporte,
        'busqueda'=> $this->_botonBusqueda,
        'menu'=> $this->_botonMenu,
        'elimina_caso' => $this->_botonElimina,
        'casonuevo'=> $this->_botonCasoNuevo,
        'siguiente'   => $this->_botonSiguiente
        );
        $this->creaBotones(
            $page, $botones,
            $this->_attrBoton
        );
        if (!isset($_SESSION['forma_modo'])
            || $_SESSION['forma_modo'] != 'busqueda'
        ) {
                $this->deshabilita_botones($page, array('busqueda'));
        } else {
                $this->deshabilita_botones($page, array('reporte'));
                $this->deshabilita_botones($page, array('elimina_caso'));
                $this->deshabilita_botones($page, array('casonuevo'));
        }
    }



    /**
     * Habilita botones estándar de la página.
     * Adaptada de Progress/generator.php
     *
     * @param object   &$page  HTML_QuickForm_Page Página con ficha.
     * @param string[] $events Eventos
     *
     * @return void
     */
    function habilita_botones(&$page, $events = array())
    {
        if (!is_a($page, 'HTML_QuickForm_Page')) {
            die("page no es HTML_QuickForm_page");
        } elseif (!is_array($events)) {
            die("events no es arreglo");
        }
        static $all;
        if (!isset($all)) {
            $all = array('back','next','cancel','reset','apply','help');
        }
        $buttons = (count($events) == 0) ? $all : $events;

        foreach ($buttons as $event) {
            $group    =& $page->getElement('buttons');
            $elements =& $group->getElements();
            foreach (array_keys($elements) as $key) {
                if ($group->getElementName($key) == $page->getButtonName($event)) {
                    $elements[$key]->updateAttributes(array('disabled'=>'false'));
                }
            }
        }
    }


    /**
     * Deshabilita los botones estándar de una página.
     * Adaptada de Progress/generator.php
     *
     * @param object &$page  HTML_QuickForm_Page Página con ficha.
     * @param array  $events Eventos
     *
     * @return void
     */
    function deshabilita_botones(&$page, $events = array())
    {
        if (!is_a($page, 'HTML_QuickForm_Page')) {
            die("page no es HTML_QuickForm_page");
        } elseif (!is_array($events)) {
            die("events no es arreglo");
        }
        static $all;
        if (!isset($all)) {
            $all = array('back','next','cancel','reset','apply','help');
        }
        $buttons = (count($events) == 0) ? $all : $events;

        foreach ($buttons as $event) {
            $group    =& $page->getElement('buttons');
            $elements =& $group->getElements();
            foreach (array_keys($elements) as $key) {
                if ($group->getElementName($key) == $page->getButtonName($event)) {
                    $elements[$key]->updateAttributes(
                        array('disabled'=>'true')
                    );
                }
            }
        }
    }


}

if (isset($_GET['limpia']) && $_GET['limpia'] == 1) {
    unset_var_session();
}


$opciones = array();
$nv = "_auth_".nom_sesion();
if (isset($_SESSION[$nv]['username']) || $opciones == array()) {
    $d = objeto_tabla('caso');
    if (PEAR::isError($d)) {
        die($d->getMessage());
    }
    $db =& $d->getDatabaseConnection();
    $rol = "";
    saca_opciones($_SESSION[$nv]['username'], $db, $opciones, $rol);
}

$captura = new CapturaCaso($opciones);

if (isset($_REQUEST['modo'])) {
    $_SESSION['forma_modo'] = 'consulta';
}

$GLOBALS['ya_captura_caso'] =& $captura;

foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
    list($n, $c, $o) = $tab;
    //echo "OJO n=$n, c=$c, o=$o<br>\n";
    if (($d = strrpos($c, "/"))>0) {
        $c = substr($c, $d+1);
    }
    if (is_callable(array($c, 'iniCaptura'))) {
        call_user_func(array($c, 'iniCaptura'), $opciones);
    } else {
        echo_enc("Falta iniCaptura en $n, $c");
    }
}

$captura->run();
?>
