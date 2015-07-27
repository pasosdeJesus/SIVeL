<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Página del multi-formulario para capturar caso (captura_caso.php).
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
 * Datos básicos del multi-formulario capturar caso
 */
require_once "aut.php";
require_once $_SESSION['dirsitio'] . "/conf.php";
//$aut_usuario = "";
//autentica_usuario($GLOBALS['dsn'], $aut_usuario, 31);
require_once $_SESSION['dirsitio'] . "/conf_int.php";
require_once 'HTML/QuickForm/Action.php';
require_once 'PagBaseSimple.php';
require_once 'DataObjects/Intervalo.php';
require_once 'DataObjects/Caso.php';
require_once 'HTML/QuickForm/Page.php';
require_once 'HTML/QuickForm/Action/Display.php';
require_once 'HTML/QuickForm/Action/Next.php';
require_once 'HTML/QuickForm/Action/Back.php';
require_once 'HTML/QuickForm/Action/Jump.php';
require_once 'HTML/QuickForm/header.php';
require_once 'HTML/QuickForm/date.php';
require_once 'HTML/QuickForm/text.php';
require_once 'HTML/Javascript.php';
require_once 'ResConsulta.php';


/**
 * Acción para avanzar a siguienete.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      BuscarId
 */
class Siguiente extends HTML_QuickForm_Action
{

    /**
     * Ejecuta acción
     *
     * @param object &$page      Página
     * @param string $actionName Acción
     *
     * @return void
     */
    function perform(&$page, $actionName)
    {
        if ($page->procesa($page->_submitValues)) {
            $pageName =  $page->getAttribute('id');
            $data     =& $page->controller->container();

            $nextName = $page->controller->getNextName($pageName);
            if (null !== $nextName) {
                $next =& $page->controller->getPage($nextName);
                $next->handle('jump');
            }
        } else {
            $page->handle('display');
        }
    }
}


/**
 * Acción para retroceder.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      BuscarId
 */
class Anterior extends HTML_QuickForm_Action
{
    /**
     * Ejecuta acción
     *
     * @param object &$page      Página
     * @param string $actionName Acción
     *
     * @return void
     */
    function perform(&$page, $actionName)
    {
        if ($page->procesa($page->_submitValues)) {
            $pageName =  $page->getAttribute('id');

            // get the previous page and go to it
            // we don't check validation status here, 'jump' handler should */
            $prevName = $page->controller->getPrevName($pageName);
            if (null === $prevName) {
                $page->handle('jump');
            } else {
                $prev =& $page->controller->getPage($prevName);
                $prev->handle('jump');
            }
        } else {
            $page->handle('display');
        }
    }
}


/**
 * Acción para iniciar caso nuevo.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      BuscarId
 */
class CasoNuevo extends HTML_QuickForm_Action
{

    /**
     * Ejecuta acción
     *
     * @param object &$page      Página
     * @param string $actionName Acción
     *
     * @return void
     */
    function perform(&$page, $actionName)
    {
        if ($page->procesa($page->_submitValues)) {
            unset_var_session();
            header('Location: captura_caso.php');
        } else {
            $page->handle('display');
        }
    }
}


/**
 * Acción para terminar salvando (Menú)
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      BuscarId
 */
class Terminar extends HTML_QuickForm_Action
{

    /**
     * Ejecuta acción
     *
     * @param object &$page      Página
     * @param string $actionName Acción
     *
     * @return void
     */
    function perform(&$page, $actionName)
    {
        $v = $page->_submitValues;
        $es_vacio = (!isset($v['titulo']) || $v['titulo'] == '')
                && (!isset($v['fecha']['d']) || $v['fecha']['d'] == '')
                && (!isset($v['fecha']['M']) || $v['fecha']['M'] == '')
                && (!isset($v['fecha']['Y']) || $v['fecha']['Y'] == '')
                && (!isset($v['hora']) || $v['hora'] == '')
                && (!isset($v['duracion']) || $v['duracion'] == '')
                && $_SESSION['basicos_id'] == '' ;
        if ($es_vacio) {
                unset_var_session();
                header('Location: index.php');
        } elseif ($page->procesa($page->_submitValues)) {
            unset_var_session();
            header('Location: index.php');
        } else {
            $page->handle('display');
        }
    }
}



/**
 * Acción para terminar sin salvar.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      BuscarId
 */
class TerminarSinSalvar extends HTML_QuickForm_Action
{

    /**
     * Ejecuta acción
     *
     * @param object &$page      Página
     * @param string $actionName Acción
     *
     * @return void
     */
    function perform(&$page, $actionName)
    {
        unset_var_session();
        header('Location: index.php');
    }
}

/**
 * Acción para eliminar caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      BuscarId
 */
class EliminaCaso extends HTML_QuickForm_Action
{

    /**
     * Ejecuta acción
     *
     * @param object &$page      Página
     * @param string $actionName Acción
     *
     * @return void
     */
    function perform(&$page, $actionName)
    {
        $htmljs = new HTML_Javascript();
        echo $htmljs->startScript();
        echo $htmljs->confirm(
            sprintf(
                _('¿Confirma eliminación del caso %s?'),
                (int)$_SESSION['basicos_id']
            ), 'eliminar'
        );
        echo $htmljs->write('eliminar', true);
        echo $htmljs->_out(
            "if (eliminar == true) { " .
            "window.location='eliminar_caso.php'; }"
        );
        echo $htmljs->endScript();
        $page->handle('display');
    }
}


/**
 * Acción para hacer una busqueda.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      BuscarId
 */
class Busqueda extends HTML_QuickForm_Action
{
    /**
     * Prepara consulta
     *
     * @param object &$page  Página
     * @param object &$dCaso Registro caso DataObject
     *
     * @return array consulta, conexión a base de datos, partes de consulta
     */
    static function prepConsulta(&$page, &$dCaso)
    {

        $pFiini      = var_req_escapa('fiini');
        $pFifin      = var_req_escapa('fifin');
        $dCaso = objeto_tabla('caso');
        if (PEAR::isError($dCaso)) {
            die($dCaso->getMessage());
        }

        $db =& $dCaso->getDatabaseConnection();

        $dCaso->id = $GLOBALS['idbus'];
        if ($dCaso->find() == 0) {
            die(sprintf(
                "Problema debería haber un registro en caso con id = %s",
                $GLOBALS['idbus']
            ));
        }
        $dCaso->fetch();
        $w = "";
        $w2 = "";
        $t = "caso";
        foreach ($page->controller->_pages as $pn => $p) {
            //echo "OJO pn=$pn<br>"; print_r($dCaso); echo "<hr>";
            $p->datosBusqueda($w, $t, $db, $dCaso->id, $w2);
        }

        if ((isset($pFiini['Y']) && $pFiini['Y'] != '')
            || (isset($pFifin['Y']) && $pFifin['Y'] != '')
        ) {
                $t .= ", caso_usuario";
                consulta_and_sinap($w, "caso_usuario.id_caso", "caso.id");
        }
        if (isset($pFiini['Y'])
            && $pFiini['Y'] != ''
        ) {
                consulta_and(
                    $db, $w, "caso_usuario.fechainicio",
                    arr_a_fecha($pFiini, true), ">="
                );
        }
        if (isset($pFifin['Y'])
            && $pFifin['Y'] != ''
        ) {
                consulta_and(
                    $db, $w, "caso_usuario.fechainicio",
                    arr_a_fecha($pFifin, false), "<="
                );
        }

        $wc = "caso.id<>'" . $GLOBALS['idbus'] . "'";
        if ($w != "") {
            $wc .= " AND " . $w;
        }
        if ($w2!="") {
            $wc .= " AND caso.id IN (" . $w2 . ")";
        }
        $q = "SELECT DISTINCT caso.id, caso.fecha, caso.memo FROM ". $t .
        "  WHERE $wc";
        consulta_orden($q, $_SESSION['busca_presenta']['ordenar']);

        //echo "q es $q"; die("x");
        return array($q, $db, $wc, $t);
    }


    /**
     * Ejecuta acción
     *
     * @param object &$page      Página
     * @param string $actionName Acción
     *
     * @return void
     */
    function perform(&$page, $actionName)
    {
        if ($page->procesa($page->_submitValues)) {
            list($q, $db, $w, $tablas) = Busqueda::prepConsulta($page, $dCaso);

            if (isset($_SESSION['busca_presenta']['ensql'])
                && $_SESSION['busca_presenta']['ensql'] != ''
            ) {
                $q = $_SESSION['busca_presenta']['ensql'];
            }
            if ($_SESSION['busca_presenta']['mostrar'] == 'actos') {
                ResConsulta::actosHtml(
                    $db, $tablas, $w,
                    $_SESSION['bus_fecha_final'],
                    var_escapa(
                        $_SESSION['busca_presenta']['mostrar'],
                        $db
                    )
                );
            } else {
                $result = hace_consulta($db, $q);
                $conv = array('caso_id' => 0, 'caso_fecha' => 1, 'caso_memo' =>2);
                $campos = array();
                foreach ($GLOBALS['cw_ncampos']+array('m_fuentes'=>'Fuentes') as
                    $idc => $dc
                ) {
                    if (isset($_SESSION['busca_presenta'][$idc])
                        && $_SESSION['busca_presenta'][$idc] == 1
                    ) {
                            $campos[$idc] = $dc;
                    }
                }

                $mvl = isset($_SESSION['busca_presenta']['m_varlineas']) ?
                    $_SESSION['busca_presenta']['m_varlineas'] : false;

                $r = new ResConsulta(
                    $campos, $db, $result,
                    $conv,
                    var_escapa(
                        $_SESSION['busca_presenta']['mostrar'], $db
                    ), array('varlineas' => $mvl),
                    array(), $_SESSION['busca_presenta']
                );
                //$_SESSION['forma_modo'] = 'consulta';
                $r->aHtml(
                    false,
                    '<a href = "captura_caso.php">Consulta Detallada</a>, '
                );
            }
        } else {
            $page->handle('display');
        }
    }
}

/**
 * Acción para presentar reporte general.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      BuscarId
 */
class ReporteGeneral extends HTML_QuickForm_Action
{

    /**
     * Presenta reporte general de un caso
     *
     * @param integer $idcaso Número de caso
     *
     * @return void
     */
    static function reporte($idcaso)
    {
        encabezado_envia(
            sprintf(
                _("Reporte General del caso %s"), (int)$idcaso
            )
        );
        $buf_html = array();
        $html_bufort= "";
        $r = valida_caso($idcaso, $buf_html, $html_bufort);
        if (!$r || count($buf_html) > 0) {
            $msg_html = implode($buf_html, "\n");
            if (trim($msg_html) != "") {
                error_valida($msg_html, null, '', true);
            }
        }

        $campos = array_merge(
            $GLOBALS['cw_ncampos'],
            array('m_fuentes'=>'Fuentes')
        );
        $html_rep = ResConsulta::reporteGeneralHtml($idcaso, null, $campos);
        echo $html_bufort;
        echo "<pre>";
        echo $html_rep;
        echo "</pre>";
        echo "<hr>";
        echo '<a href = "captura_caso.php">' . _('Volver al Caso') . '</a> | ';
        echo '<a href = "captura_caso.php?limpia=1">' . _('Caso Nuevo')
            . '</a> | ';
        echo '<a href = "index.php">' . _('Menú') . '</a>';
        pie_envia();
    }

    /**
     * Ejecuta acción
     *
     * @param object &$page      Página
     * @param string $actionName Acción
     *
     * @return void
     */
    function perform(&$page, $actionName)
    {
        if (($pdest = $page->controller->getPage($actionName)) == null) {
            die(_("No existe en el controlador la página") . $actionName);
        }
        if ($page->procesa($page->_submitValues)) {
            $this->reporte($_SESSION['basicos_id']);
        } else {
            $page->handle('display');
        }
    }
}


/**
 * Acción para saltar.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      BuscarId
 */
class Salta extends HTML_QuickForm_Action
{
    /**
     * Ejecuta acción
     *
     * @param object &$page      Página
     * @param string $actionName Acción
     *
     * @return void
     */
    function perform(&$page, $actionName)
    {
        if (($pdest = $page->controller->getPage($actionName)) == null) {
            die("No existe en el controlador la página $actionName");
        }
        if ($page->procesa($page->_submitValues)) {
            $pdest->handle('jump');
        } else {
            $page->handle('display');
        }
    }
}


/**
 * Responde al boton buscar caso en tabulador Básicos de la ficha de ingreso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
*/
class BuscarId extends HTML_QuickForm_Action
{
    /**
     * Ejecuta acción.
     *
     * @param object &$page      HTML_QuickForm página que produjo la acción
     * @param string $actionName Nombre de la acción
     *
     * @return void
     */
    function perform(&$page, $actionName)
    {
        unset_var_session();
        $_SESSION['basicos_id'] = (int)var_escapa($_POST['busid']);
        $page->handle('display');
    }
}


/**
 * Página de datos básicos.
 * Ver documentación de funciones en clase base.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      PagBaseSimple
 */
class PagBasicos extends PagBaseSimple
{
    /* Variables DB_DataObject_FormBuilder de caso */
    var $bcaso_frontera;
    var $bcaso_region;

    var $titulo = 'Datos Básicos';

    var $clase_modelo = 'caso';

    /**
     * Inicializa variables y datos de la pestaña.
     * Ver documentación completa en clase base.
     *
     * @param array $apar Arreglo de parametros. Vacio aqui.
     *
     * @return handle Conexión a base de datos
     */
    function iniVar($apar = null)
    {
        $r = parent::iniVar(array(false, true));
        if (count($r) != 3) {
            die_esc(_('Se esperaban 3 parametros en iniVar'));
        }
        $db = $r[0];
        $dcaso = $r[1];
        $idcaso = $r[2];
        $dcaso_frontera =& objeto_tabla('caso_frontera');
        $dcaso_region =& objeto_tabla('caso_region');

        // Modo insercion: id en null indica que se está insertado
        // uno nuevo.
        if (isset($_REQUEST['id'])) {
            $dcaso->id = (int)var_escapa($_REQUEST['id'], $db);
        } else if (isset($_SESSION['basicos_id'])) {
            $dcaso->id = $_SESSION['basicos_id'];
        } else {
            $dcaso->id = null;
        }
        $_SESSION['basicos_id'] = $dcaso->id;
        $dcaso_frontera->id_caso = $dcaso->id;
        $dcaso_region->id_caso = $dcaso->id;

        // Si ya existía lo carga
        if (isset($dcaso->id)) {
            if (($e = $dcaso->find()) != 1 && $dcaso->id != $GLOBALS['idbus']) {
                die(
                    _("Se esperaba un sólo registro, pero se encontraron")
                   . " $e (" .  $dcaso->id . ")"
                );
            } else if ($e != 0 || $dcaso->id != $GLOBALS['idbus']) {
                $dcaso->fetch();
            }
            $dcaso_frontera->find();
            $dcaso_region->find();
        }

        $this->bcaso_frontera =& DB_DataObject_FormBuilder::create(
            $dcaso_frontera,
            array('requiredRuleMessage' =>
            $GLOBALS['mreglareq'],
            'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bcaso_region =& DB_DataObject_FormBuilder::create(
            $dcaso_region,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
            'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );

        return $db;
    }


    /**
     * Constructora
     *
     * @param string $nomForma Nombre
     *
     * @return void
     */
    function PagBasicos($nomForma)
    {
        $aut_usuario = "";
        autentica_usuario($GLOBALS['dsn'], $aut_usuario, 31);
        parent::PagBaseSimple($nomForma);
        $this->titulo = _('Datos Básicos');
        $this->tcorto = _('Básicos');

        if (isset($GLOBALS['etiqueta']['Basicos'])) {
            $this->titulo = $GLOBALS['etiqueta']['Basicos'];
            $this->tcorto = $GLOBALS['etiqueta']['Basicos'];
        }

        $this->addAction('buscar', new BuscarId());
        $this->addAction('siguiente', new Siguiente());
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
        if (isset($_SESSION['forma_modo'])
            && $_SESSION['forma_modo'] == 'busqueda'
        ) {  //Busqueda
            $this->addElement('hidden', 'id', $GLOBALS['idbus']);

            $slan = isset($_SESSION['LANG']) ?
                substr($_SESSION['LANG'], 0, 2) : 'es';
            $e =& $this->addElement(
                'date', 'fini', _('Fecha inicial'),
                array('language' => $slan, 'addEmptyOption' => true,
                'minYear' => $GLOBALS['anio_min']
                )
            );
            $v = array();
            if (isset($_SESSION['bus_fecha_inicial'])) {
                $v = call_user_func(
                    $this->bcaso->dateFromDatabaseCallback,
                    $_SESSION['bus_fecha_inicial']
                );
            }
            $this->_defaultValues['fini'] = $v;
            $e =& $this->addElement(
                'date', 'ffin', _('Fecha final'),
                array('language' => $slan, 'addEmptyOption' => true,
                'minYear' => $GLOBALS['anio_min']
                )
            );
            $v = array();
            if (isset($_SESSION['bus_fecha_inicial'])) {
                $v = call_user_func(
                    $this->bcaso->dateFromDatabaseCallback,
                    $_SESSION['bus_fecha_final']
                );
            }
            $this->_defaultValues['ffin'] = $v;
            $this->bcaso->_do->id_intervalo
                = DataObjects_Intervalo::idSinInfo();
            $this->bcaso->_do->defSinInf = false;
        } else { // Nuevo o actualización
            $ed = array();
            $tid =& $this->createElement('static', 'id', _('No. Caso') . ': ');
            $ed[] =& $tid;
            $tid =& $this->createElement(
                'text', 'busid',
                _('No. Caso por buscar: '), array("align"=>"right")
            );
            $tid->setSize(7);
            $ed[] =& $tid;
            $botBuscar =& $this->createElement(
                'submit',
                $this->getButtonName('buscar'), _('Buscar'),
                array("align" => "right")
            );
            $ed[] =& $botBuscar;
            $ed[] =& $this->createElement(
                'static', null,
                _('Deje en blanco si es nuevo')
            );
            $this->addGroup($ed, null, _('No. Caso'), '&nbsp;', false);
        }


        $this->bcaso->createSubmit = 0;
        $this->bcaso->useForm($this);
        $f =& $this->bcaso->getForm();

        if (isset($_SESSION['forma_modo'])
            && $_SESSION['forma_modo'] == 'busqueda'
        ) {
            $e =& $this->removeElement('fecha', true);

        }

        $e =& $this->addElement('header', 'ubicacion', _('Ubicación'));

        $this->bcaso_region->createSubmit = 0;
        $this->bcaso_region->useForm($this);
        $this->bcaso_region->getForm();
        unset($this->_rules['id_region[]']);
        unset($this->_rules['id_region']);

        $this->bcaso_frontera->createSubmit = 0;
        $this->bcaso_frontera->useForm($this);
        $bff = $this->bcaso_frontera->getForm();
        unset($bff->_rules['id_frontera[]']);
        unset($this->_rules['id_frontera']);

        agrega_control_CSRF($this);
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
        $this->controller->deshabilita_botones(
            $this,
            array('anterior','elimina')
        );

        if (isset($_SESSION['recuperaErrorValida'])) {
            establece_valores_form(
                $this,
                array('titulo', 'hora', 'duracion', 'id_intervalo',
                'fecha', 'id_region', 'id_frontera'
                ),
                $_SESSION['recuperaErrorValida']
            );
            unset($_SESSION['recuperaErrorValida']);
        } elseif ($this->bcaso->_do->id != null) {
            $scf =& $this->getElement('id_frontera');
            if (!PEAR::isError($scf)) {
                $valscf = array();
                $this->bcaso_frontera->_do->find();
                while ($this->bcaso_frontera->_do->fetch()) {
                    $valscf[] = $this->bcaso_frontera->_do->id_frontera;
                }
                $scf->setValue($valscf);
            }
            $scr =& $this->getElement('id_region');
            if (!PEAR::isError($scr)) {
                $valscr = array();
                $t = $this->bcaso_region->_do->find();
                while ($this->bcaso_region->_do->fetch()) {
                    $valscr[] = $this->bcaso_region->_do->id_region;
                }
                $scr->setValue($valscr);
            }
            $sci =& $this->getElement('id_intervalo');
            if (!PEAR::isError($sci)) {
                $v = $this->bcaso->_do->id_intervalo;
                $sci->setValue($v);
            }
        } 
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
        assert($db != null);
        assert(isset($idcaso));
        $result = hace_consulta(
            $db, "DELETE FROM caso_frontera " .
            "WHERE id_caso='" . $idcaso . "'"
        );
        $result = hace_consulta(
            $db, "DELETE FROM caso_region " .
            "WHERE id_caso='" . $idcaso . "'"
        );
    }

    /**
     * Procesa valores del formulario enviados por el usuario.
     * Ver documentación completa en clase base.
     *
     * @param handle &$valores Valores ingresados por usuario
     *
     * @return bool Verdadero si y solo si puede completarlo con éxito
     * @see PagBaseSimple
     */
    function procesa(&$valores)
    {
        if (isset($GLOBALS['no_permite_editar']) && $GLOBALS['no_permite_editar']) {
            $htmljs = new HTML_Javascript();
            echo $htmljs->startScript();
            echo $htmljs->alert( 'Edición deshabilitada.');
            echo $htmljs->endScript();
            if (!isset($valores['id_caso']) || $valores['id_caso'] == ''
                || $valores['id_caso'] == null
            ) {
                return false;
            } else { 
                return true;
            }
        }
        if (!$this->validate()) {
            return false;
        }
        verifica_sin_CSRF($valores);
        if (in_array(31, $_SESSION['opciones'])
            && !in_array(21, $_SESSION['opciones'])
        ) {
            return true;
        }


        $db = $this->iniVar();
        if (!isset($this->bcaso->_do->memo)) {
            $this->bcaso->_do->memo = '';
        }

        if (isset($_SESSION['forma_modo'])
            && $_SESSION['forma_modo'] == 'busqueda'
            && isset($valores['id_caso'])
            && $valores['id_caso'] == $GLOBALS['idbus']
        ) {
            $oc = $this->bcaso->_do;
            $oc->id_caso = $GLOBALS['idbus'] ;
            $oc->find(1);
            if ($oc->fecha == null || $oc->fecha == '') {
                $q = "INSERT INTO caso (id, fecha, memo, " .
                    " id_intervalo) VALUES ('" .
                    $GLOBALS['idbus'] . "', '2005-1-1', '', '5');";
                //die("procesa $q");
                hace_consulta($db, $q);
            }
            $this->bcaso->_do->id = $GLOBALS['idbus'];
            $this->bcaso->forceQueryType(
                DB_DATAOBJECT_FORMBUILDER_QUERY_FORCEUPDATE
            );
        } else {
            if (!isset($valores['id_caso']) || $valores['id_caso'] == ''
                || $valores['id_caso'] == null
            ) {
                $this->bcaso->forceQueryType(
                    DB_DATAOBJECT_FORMBUILDER_QUERY_FORCEINSERT
                );
            }
            if ($valores['fecha']['d'] == ''
                || $valores['fecha']['M'] == ''
                || $valores['fecha']['Y'] == ''
            ) {
                error_valida(_('Falta fecha del caso'), $valores);
                return false;
            }
        }
        $ret = $this->process(array(&$this->bcaso, 'processForm'), false);
        if (PEAR::isError($ret)) {
            die($ret->getMessage());
        }
        $idcaso = $this->bcaso->_do->id;

        if (isset($_SESSION['forma_modo'])
            && $_SESSION['forma_modo'] == 'busqueda'
            && isset($idcaso) && $idcaso == $GLOBALS['idbus']
        ) {
            $v= call_user_func(
                $this->bcaso->dateToDatabaseCallback,
                var_escapa($_REQUEST['ffin'], $db)
            );
            if ($v == '--') {
                $v = '';
            }
            $_SESSION['bus_fecha_final'] = $v;
            $v = call_user_func(
                $this->bcaso->dateToDatabaseCallback,
                var_escapa($_REQUEST['fini'], $db)
            );
            if ($v == '--') {
                $v = '';
            }
            $_SESSION['bus_fecha_inicial'] = $v;
        }

        // No llamar con $this->eliminaDep porque al extender llama
        // al eliminaDep de la clase extendida que borrará más de lo que
        // espera esta funcion.
        PagBasicos::eliminaDep($db, $idcaso);
        if (isset($valores['id_frontera'])) {
            foreach (var_escapa($valores['id_frontera'], $db) as $k => $v) {
                $this->bcaso_frontera->_do->id_caso = $idcaso;
                $this->bcaso_frontera->_do->id_frontera = $v;
                $this->bcaso_frontera->_do->insert();
            }
        }
        if (isset($valores['id_region'])) {
            foreach (var_escapa($valores['id_region'], $db) as $k => $v) {
                $this->bcaso_region->_do->id_caso = $idcaso;
                $this->bcaso_region->_do->id_region = $v;
                $this->bcaso_region->_do->insert();
            }
        }

        $_SESSION['basicos_id'] = $idcaso;
        caso_usuario($idcaso);
        return  $ret;
    }

    /**
     * Prepara consulta SQL para buscar datos de este formulario.
     * Ver documentación completa en clase base.
     *
     * @param string &$w       Consulta que se construye
     * @param string &$t       Tablas
     * @param object &$db      Conexión a base de datos
     * @param int    $idcaso   Identificación del caso
     * @param string &$subcons Subconsulta
     *
     * @return void
     * @see PagBaseSimple
     */
    function datosBusqueda(&$w, &$t, &$db, $idcaso, &$subcons)
    {
        //echo "OJO PagBasicos->datosBusqueda($w, $t, $db, $idcaso, $subcons)";
        assert(isset($db) && $db != null);
        $dCaso = objeto_tabla('caso');
        $dCaso->id = $idcaso;
        assert($dCaso->find() != 0);
        $dCaso->fetch();


        if (trim($dCaso->titulo) != '') {
            consulta_and(
                $db, $w, "caso.titulo", "%" . trim($dCaso->titulo) . "%",
                ' ILIKE ', 'AND'
            );
        }
        list($faini, $fmini, $fdini) = explode(
            '-',
            $_SESSION['bus_fecha_inicial']
        );
        list($fafin, $fmfin, $fdfin) = explode(
            '-',
            $_SESSION['bus_fecha_final']
        );
        if ((int)$faini != 0) {
                consulta_and(
                    $db, $w, "caso.fecha",
                    arr_a_fecha(
                        array('Y' => $faini,
                    'm' => $fmini, 'd' => $fdini
                        ), true
                    ), ">="
                );
        }
        if ((int)$fafin != 0) {
                consulta_and(
                    $db, $w, "caso.fecha",
                    arr_a_fecha(
                        array('Y' => $fafin,
                    'm' => $fmfin, 'd' => $fdfin
                        ), false
                    ), "<="
                );
        }
        if (trim($dCaso->hora) != '') {
            consulta_and(
                $db, $w, "caso.hora", "%" . trim($dCaso->hora) . "%",
                ' ILIKE ', 'AND'
            );
        }
        if (trim($dCaso->duracion) != '') {
            consulta_and(
                $db, $w, "caso.duracion", "%" . trim($dCaso->duracion) . "%",
                ' ILIKE ', 'AND'
            );
        }
        if ($dCaso->id_intervalo != DataObjects_Intervalo::idSinInfo()) {
            consulta_and($db, $w, "caso.id_intervalo", $dCaso->id_intervalo);
        }

        $t = "caso";
        consulta_or_muchos($w, $t, 'caso_frontera');

        consulta_or_muchos($w, $t, 'caso_region');
    }

    //function handle($action)
    //{
    //    parent::handle($action);
    //    echo "PagBasicos::handle($action)";
    //}
}
?>
