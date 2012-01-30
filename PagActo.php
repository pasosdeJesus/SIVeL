<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
/**
 *Página del multi-formulario para capturar caso (captura_caso.php).
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: PagActo.php,v 1.23.2.4 2011/10/11 16:33:37 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

/**
 * Pestaña Actos de la ficha de captura de caso
 */

require_once 'PagBaseSimple.php';
require_once 'ResConsulta.php';


/**
 * Acción que responde al boton Agregar agresión individual
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class AgregarActo extends HTML_QuickForm_Action
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
        if ($page->procesa($page->_submitValues, true, false)) {
            $page->_submitValues = array();
            $page->_defaultValues = array();
            $page->nullVar();
        }
        $page->handle('display');
    }
}

/**
 * Acción que responde al boton Agregar Agresion colectiva
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class AgregarActocolectivo extends HTML_QuickForm_Action
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
        if ($page->procesa($page->_submitValues, false, true)) {
            $page->_submitValues = array();
            $page->_defaultValues = array();
            $page->nullVar();
        }
        $page->handle('display');
    }
}

/**
 * Acción que responde al enlace Eliminar acto individual
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class EliminaActo extends HTML_QuickForm_Action
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
        assert($_REQUEST['eliminaacto'] != null);

        $dacto=& objeto_tabla('acto');
        list($dacto->id_p_responsable, $dacto->id_categoria,
            $dacto->id_persona
        ) = explode(':', var_escapa($_REQUEST['eliminaacto']));
        $dacto->id_caso = $_SESSION['basicos_id'];
        $dacto->delete();
        $page->_submitValues = array();
        $page->_defaultValues = array();
        $page->nullVar();
        $page->handle('display');
    }
}

/**
 * Acción que responde al enlace Eliminar acto colectivo
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class EliminaActocolectivo extends HTML_QuickForm_Action
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
        assert($_REQUEST['eliminaactocolectivo'] != null);

        $dactocolectivo = & objeto_tabla('actocolectivo');
        list($dactocolectivo->id_p_responsable, $dactocolectivo->id_categoria,
            $dactocolectivo->id_grupoper
        ) = explode(':', var_escapa($_REQUEST['eliminaactocolectivo']));
        $dactocolectivo->id_caso = $_SESSION['basicos_id'];
        $dactocolectivo->delete();
        $page->_submitValues = array();
        $page->_defaultValues = array();
        $page->nullVar();
        $page->handle('display');
    }
}


/**
 * Actos
 * Ver documentación de funciones en clase base.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      PagBaseSimple
 */
class PagActo extends PagBaseSimple
{

    var $bacto;

    var $bactocolectivo;

    var $titulo = 'Actos';

    var $tcorto = 'Actos';

    var $clase_modelo = 'acto';

    /*var $bt;  Benchmark_Timer */

    /**
     * Pone en null variables asociadas a tablas de la pestaña.
     *
     * @return null
     */
    function nullVar()
    {
        $this->bacto = null;
        $this->bactocolectivo = null;
    }


    /**
     * Inicializa variables.
     *
     * @return handle Conexión a base de datos
     */
    function iniVar()
    {
        /*if (!isset($bt)) {
            $bt = new Benchmark_Timer(true);
        }
        $bt->setMarker("iniVar");*/
        $dacto =& objeto_tabla('acto');
        if (PEAR::isError($dacto)) {
            die($dacto->getMessage() . " - " . $dacto->getUserInfo());
        }
        $db =& $dacto->getDatabaseConnection();
        $dactocolectivo =& objeto_tabla('actocolectivo');


        $idcaso =& $_SESSION['basicos_id'];
        if (!isset($idcaso) || $idcaso == null) {
            die("Bug: idcaso no debería ser null");
        }

        $dacto->id_caso = $idcaso;
        $dactocolectivo->id_caso = $idcaso;
        $dacto->orderBy('id_p_responsable, id_categoria, id_persona');
        $dactocolectivo->orderBy('id_p_responsable, id_categoria, id_grupoper');

        $this->bacto =& DB_DataObject_FormBuilder::create(
            $dacto,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
            'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bacto->useMutators = true;
        $this->bactocolectivo =& DB_DataObject_FormBuilder::create(
            $dactocolectivo,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
            'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bactocolectivo->useMutators = true;

        return $db;
    }

    /**
     * Constructora.
     * Ver documentación completa en clase base.
     *
     * @param string $nomForma Nombre
     *
     * @return void
     */
    function PagActo($nomForma)
    {
        parent::PagBaseSimple($nomForma);

        $this->addAction('siguiente', new Siguiente());
        $this->addAction('anterior', new Anterior());

        $this->addAction('agregarActo', new AgregarActo());
        $this->addAction('agregarActocolectivo', new AgregarActocolectivo());

        $this->addAction('eliminaacto', new EliminaActo());
        $this->addAction('eliminaactocolectivo', new EliminaActocolectivo());

        if (isset($GLOBALS['etiqueta']['actos'])) {
            $this->titulo = $GLOBALS['etiqueta']['actos'];
            $this->tcorto = $GLOBALS['etiqueta']['actos'];
        }
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
        $vv = isset($this->bvictima->_do->id_persona)
            ? $this->bvictima->_do->id_persona : '';
        $this->addElement('hidden', 'id_persona', $vv);

        $idcaso = $_SESSION['basicos_id'];

        $gacto = array();

        $sel =& $this->createElement(
            'select', 'presponsables', 'presponsables'
        );
        $sel->setMultiple(true);
        $sel->setSize(5);
        $op = htmlentities_array(
            $db->getAssoc(
                "SELECT id_p_responsable, nombre
                FROM presuntos_responsables_caso, presuntos_responsables 
                WHERE id_caso=$idcaso AND 
                presuntos_responsables.id=id_p_responsable 
                ORDER BY nombre"
            )
        );
        $sel->loadArray($op);
        $gacto[] =& $sel;

        $sel =& $this->createElement('select', 'categorias', 'categorias');
        $sel->setMultiple(true);
        $sel->setSize(5);
        $op = htmlentities_array(
            $db->getAssoc(
                "SELECT id, id_tipo_violencia || id || ' ' || nombre
                FROM categoria
                WHERE tipocat='I'  AND fechadeshabilitacion IS NULL
                ORDER BY id_tipo_violencia, id"
            )
        );
        $sel->loadArray($op);
        $gacto[] =& $sel;

        $sel =& $this->createElement('select', 'victimas', 'victimas');
        $sel->setMultiple(true);
        $sel->setSize(5);
        $op = htmlentities_array(
            $db->getAssoc(
                "SELECT id_persona, nombres || ' ' || apellidos
                FROM victima, persona 
                WHERE id_caso=$idcaso AND 
                victima.id_persona=persona.id 
                ORDER BY nombres, apellidos "
            )
        );
        $sel->loadArray($op);
        $gacto[] =& $sel;

        $bn = $this->getButtonName('agregarActo');
        $sel =& $this->createElement('submit', $bn, 'Añadir');
        $gacto[] =& $sel;

        $this->addGroup($gacto, 'nuevoacto', 'Individuales', '&nbsp;', false);

        $this->bacto->createSubmit = 0;
        $this->bacto->useForm($this);
        $f =& $this->bacto->getForm($this);

        if (!isset($GLOBALS['actoscolectivos'])
            || $GLOBALS['actoscolectivos']
        ) {
            $gactocol = array();
            $sel =& $this->createElement(
                'select', 'presponsablescol', 'presponsablescol'
            );
            $sel->setMultiple(true);
            $sel->setSize(5);
            $op = htmlentities_array(
                $db->getAssoc(
                    "SELECT id_p_responsable, nombre " .
                    " FROM presuntos_responsables_caso, presuntos_responsables " .
                    " WHERE id_caso=$idcaso AND " .
                    "presuntos_responsables.id=id_p_responsable " .
                    "ORDER BY nombre "
                )
            );
            $sel->loadArray($op);
            $gactocol[] = $sel;

            $sel =& $this->createElement(
                'select', 'categoriascol', 'categoriascol'
            );
            $sel->setMultiple(true);
            $sel->setSize(5);
            $op = htmlentities_array(
                $db->getAssoc(
                    "SELECT id, id_tipo_violencia || id || ' ' || nombre
                    FROM categoria
                    WHERE tipocat = 'C' AND fechadeshabilitacion IS NULL
                    ORDER BY id_tipo_violencia, id"
                )
            );
            $sel->loadArray($op);
            $gactocol[] = $sel;


            $sel =& $this->createElement('select', 'victimascol', 'victimascol');
            $sel->setMultiple(true);
            $sel->setSize(5);
            $op = htmlentities_array(
                $db->getAssoc(
                    "SELECT id_grupoper, nombre
                    FROM victima_colectiva, grupoper
                    WHERE id_caso=$idcaso AND 
                    victima_colectiva.id_grupoper=grupoper.id 
                    ORDER BY nombre"
                )
            );
            $sel->loadArray($op);
            $gactocol[] =& $sel;

            $sel =& $this->createElement(
                'submit', $this->getButtonName('agregarActocolectivo'),
                'Añadir'
            );
            $gactocol[] =& $sel;

            $this->addGroup(
                $gactocol, 'nuevoactocol', 'Colectivos', '&nbsp;', false
            );

            $this->bactocolectivo->createSubmit = 0;
            $this->bactocolectivo->useForm($this);
            $f =& $this->bactocolectivo->getForm($this);
        }

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
        foreach ($this->bacto->_do->fb_fieldsToRender as $c) {
            $cq = $this->getElement($c);
            if (!PEAR::isError($cq) && isset($this->bacto->_do->$c)) {
                $cq->setValue($this->bacto->_do->$c);
            }
        }
        foreach ($this->bactocolectivo->_do->fb_fieldsToRender as $c) {
            $cq = $this->getElement($c);
            if (!PEAR::isError($cq) && isset($this->bactocolectivo->_do->$c)) {
                $cq->setValue($this->bactocolectivo->_do->$c);
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
        assert(isset($idcaso) && $idcaso != null);

        $q = "DELETE FROM acto " .
            " WHERE id_caso='$idcaso'";
        $result = hace_consulta($db, $q, false);
        $q = "DELETE FROM actocolectivo " .
            " WHERE id_caso='$idcaso'";
        $result = hace_consulta($db, $q, false);
    }

    /**
     * Procesa valores del formulario enviados por el usuario.
     * Ver documentación completa en clase base.
     *
     * @param handle &$valores    Valores ingresados por usuario
     * @param bool   $procActo    True si y solo si debe añadirse acto
     * @param bool   $procActocol True si y solo si debe añadirse acto colec.
     *
     * @return bool Verdadero si y solo si puede completarlo con éxito
     * @see PagBaseSimple
     */
    function procesa(&$valores, $procActo = false, $procActocol = false)
    {
        //$bt=new Benchmark_Timer(true);
        if (!$this->validate() ) {
            return false;
        }

        verifica_sin_CSRF($valores);

        $db = $this->iniVar();
        $idcaso = $_SESSION['basicos_id'];
        $ret = true;
        $da = objeto_tabla('acto');
        if ($procActo  && isset($valores['presponsables'])
            && isset($valores['categorias']) && isset($valores['victimas'])
        ) {
            foreach (var_escapa($valores['presponsables']) as $pr) {
                foreach (var_escapa($valores['categorias']) as $ca) {
                    foreach (var_escapa($valores['victimas']) as $vi) {
                        $da->id_caso = $idcaso;
                        $da->id_p_responsable = $pr;
                        $da->id_categoria = $ca;
                        $da->id_persona = $vi;
                        if ($da->find() == 0) {
                            $da->insert();
                        }
                    }
                }
            }
        }
        $dactocol = objeto_tabla('actocolectivo');
        if ($procActocol  && isset($valores['presponsablescol'])
            && isset($valores['categoriascol'])
            && isset($valores['victimascol'])
        ) {
            foreach (var_escapa($valores['presponsablescol']) as $pr) {
                foreach (var_escapa($valores['categoriascol']) as $ca) {
                    foreach (var_escapa($valores['victimascol']) as $vi) {
                        $dactocol->id_caso = $idcaso;
                        $dactocol->id_p_responsable = $pr;
                        $dactocol->id_categoria = $ca;
                        $dactocol->id_grupoper = $vi;
                        if ($dactocol->find() == 0) {
                            $dactocol->insert();
                        }
                    }
                }
            }
        }
        funcionario_caso($_SESSION['basicos_id']);
        return  $ret;
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
        prepara_consulta_gen(
            $w, $t, $idcaso, 'acto', '', '', false, array(),
            'id_categoria', array()
        );
    }

    /**
     * Ver documentación en clase base.
     *
     * @see PagBaseSimple
     *
     * @return void
     */
    static function iniCaptura()
    {
        if (isset($_REQUEST['eliminaacto'])) {
            $_REQUEST['_qf_acto_eliminaacto'] = true;
        }
        if (isset($_REQUEST['eliminaactocolectivo'])) {
            $_REQUEST['_qf_acto_eliminaactocolectivo'] = true;
        }
    }


}

?>
