<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 *Página del multi-formulario para capturar caso (captura_caso.php).
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2007 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

require_once 'PagBaseMultiple.php';
require_once 'ResConsulta.php';
require_once 'HTML/QuickForm/Action.php';

require_once 'DataObjects/Accion.php';
require_once 'DataObjects/Rangoedad.php';
require_once 'DataObjects/Sectorsocial.php';
require_once 'DataObjects/Vinculoestado.php';
require_once 'DataObjects/Filiacion.php';
require_once 'DataObjects/Organizacion.php';
require_once 'DataObjects/Profesion.php';
require_once 'DataObjects/Presponsable.php';
require_once 'DataObjects/Resagresion.php';
require_once 'DataObjects/Etapa.php';
require_once 'DataObjects/Tproceso.php';



/**
 * Acción que responde al botor Agregar Acción
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class AgregarAccionJ extends HTML_QuickForm_Action
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
        if ($page->procesa($page->_submitValues, true)) {
            $page->_submitValues = array();
            $page->_defaultValues = array();
            $page->nullVar();
        }
        $page->handle('display');
    }
}


/**
* Responde a eliminación de una acción
 * @package SIVeL
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  Dominio público.
 * @link     http://sivel.sf.net/tec
*/
class EliminaAccionJ extends HTML_QuickForm_Action
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
        assert($_REQUEST['eliminaaccionj'] != null);
        $dac=& objeto_tabla('accion');
        $dac->id = (int)$_REQUEST['eliminaaccionj'];
        $dac->delete();
        $page->_submitValues = array();
        $page->_defaultValues = array();
        $page->nullVar();
        $page->handle('display');
    }
}


/**
* Página Proceso Judicial
* Ver documentación de funciones en clase base.
 * @package SIVeL
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  Dominio público.
 * @link     http://sivel.sf.net/tec
*/
class PagSegJudicial extends PagBaseMultiple
{

    var $bproceso;
    var $baccion;

    var $pref = "fju";

    var $nuevaCopia = false;

    var $clase_modelo = 'proceso';

    var $titulo = 'Seguimiento Judicial';

    /**
     * Pone en null variables asociadas a tablas de la pestaña.
     *
     * @return null
     */
    function nullVar()
    {
        $this->bproceso = null;
        $this->baccion = null;
    }

    /**
     * Retorna una identificación del registro actual.
     *
     * @return string Identifación
     */
    function copiaId()
    {
        return $this->bproceso->_do->id;
    }

    function elimina(&$values)
    {
        $this->iniVar();
        if (isset($this->bproceso->_do->id)) {
            $this->eliminaProceso($this->bproceso->_do, true);
            $_SESSION[$this->pref.'_total']--;
        }
    }


     /**
     * Inicializa variables y datos de la pestaña.
     * Ver documentación completa en clase base.
     *
     * @param array $apar Arreglo de parametros. Vacio aqui.
     *
     * @return handle Conexión a base de datos
     */
    function iniVar($aper = null)
    {
        $id_persona = null;
        if (isset($aper) && count($aper) == 1) {
            $id_persona = $aper[0];
        }
        $dproceso=& objeto_tabla('proceso');
        $daccion=& objeto_tabla('accion');

        $db =& $dproceso->getDatabaseConnection();

        $idcaso =& $_SESSION['basicos_id'];
        if (!isset($idcaso) || $idcaso == null) {
            die("Bug: idcaso no debería ser null");
        }

        $idp = array();
        $ndp = array();
        $edp = array();
        $indid = -1;
        $tot = PagSegjudicial::extrae_procesos(
            $idcaso, $db, $idp, $ndp,
            $id_persona, $indid, $edp
        );
        $_SESSION[$this->pref.'_total'] = $tot;
        if ($indid >= 0) {
            $_SESSION[$this->pref.'_pag'] = $indid;
        }
        $dproceso->id_caso= $idcaso;
        if ($_SESSION[$this->pref.'_pag'] < 0
            || $_SESSION[$this->pref.'_pag'] >= $tot
        ) {
            $dproceso->id = null;
        } else {
            $dproceso->id = $idp[$_SESSION[$this->pref.'_pag']];
            $dproceso->find();
            $dproceso->fetch();
            $daccion->id_proceso = $dproceso->id;
            $daccion->fetch();
        }

        $this->bproceso =& DB_DataObject_FormBuilder::create(
            $dproceso,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
                'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->baccion=& DB_DataObject_FormBuilder::create(
            $daccion,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
                'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );

        return $db;
    }


    /**
     * Constructora.
     * Ver documentación completa en clase base.
     *
     * @param string $nomForma Nombre
     * @param string $mreq     Mensaje de dato requerido
     *
     * @return void
     */
    function PagSegJudicial($nomForma)
    {
        parent::PagBaseMultiple($nomForma);
        $this->titulo  = _('Seguimiento Judicial');
        $this->tcorto  = _('Seg. Jud.');

        $this->addAction('siguiente', new Siguiente());
        $this->addAction('anterior', new Anterior());
        $this->addAction('eliminaaccionj', new EliminaAccionJ());
        $this->addAction('agregaraccionj', new AgregarAccionJ());
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
        $vv = isset($this->bproceso->_do->id)
            ? $this->bproceso->_do->id : '';
        $this->addElement('hidden', 'id', $vv);
        $this->addElement('');

        $_SESSION['pagJudicial_id'] = $vv;

        $this->bproceso->createSubmit = 0;
        $this->bproceso->useForm($this);
        $this->bproceso->getForm($this);

        $this->baccion->createSubmit = 0;
        $this->baccion->useForm($this);
        $f =& $this->baccion->getForm($this);


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
        $vv = isset($this->bproceso->_do->id) ?
            $this->bproceso->_do->id: '';

        if ($vv != '') {
            $d =& objeto_tabla('proceso');
            $d->get($vv);
            foreach ($d->fb_fieldsToRender as $c) {
                $cq = $this->getElement($c);
                if (!PEAR::isError($cq)) {
                    $cq->setValue($d->$c);
                }
            }
            $cq = $this->getElement('id_tproceso');
            $id_tproceso= $cq->_elements[0];
            $id_etapa = $cq->_elements[1];
            $id_tproceso->setValue($d->id_tproceso);
            $id_etapa->setValue($d->id_etapa);
            //die("x");
        }

    }

    function eliminaProceso($dproceso, $elimProc = false)
    {
        assert($dproceso != null);
        assert($dproceso->id != null);
        $db =& $dproceso->getDatabaseConnection();
        $q = "DELETE FROM accion WHERE id_proceso='{$dproceso->id}'";
        $result = hace_consulta($db, $q);
        if ($elimProc) {
            $q = "DELETE FROM proceso WHERE id='{$dproceso->id}'";
            $result = hace_consulta($db, $q);
        }
    }

    /** eliminaDep($db, $idcaso) elimina victimas de la base $db presentados
            en este formulario, que dependen del caso $idcaso */
    static function eliminaDep(&$db, $idcaso)
    {
        assert($db != null);
        assert(isset($idcaso));
        $dproceso =& objeto_tabla('proceso');
        sin_error_pear($dproceso);
        $dproceso->id_caso = $idcaso;
        $dproceso->find();
        while ($dproceso->fetch()) {
            PagSegJudicial::eliminaProceso($dproceso);
            $dproceso->delete();
        }
    }

    /**
    * @param procAc es true si y solo si debe añadirse Acción
    */
    function procesa(&$valores, $procAc = false)
    {
        $valores['id_tproceso'] = (int)$valores['tipoetapa'][0];
        $valores['id_etapa'] = (int)$valores['tipoetapa'][1];
        $es_vacio = (
            (!isset($valores['id_tproceso'])
            || $valores['id_tproces'] === ''
            || $valores['id_tproces'] == DataObjects_Tproceso::idSinInfo()
            )
            || (!isset($valores['id_etapa'])
                || $valores['id_etapa']==
                DataObjects_Etapa::idSinInfo()
            )
        );

        if ($es_vacio) {
            return true;
        }

        if (!$this->validate() ) {
            return false;
        }
        if (in_array(31, $_SESSION['opciones']) &&
            !in_array(21, $_SESSION['opciones'])
        ) {
            return true;
        }


        if (!isset($valores['id']) || $valores['id'] == '') {
            $valores['id'] = null;
            $db = $this->iniVar();
        } else {
            $db = $this->iniVar(array((int)$valores['id']));
        }
        $dcaso = objeto_tabla('caso');
        if (PEAR::isError($dcaso)) {
            die($dcaso->getMessage());
        }

        $ret = $this->process(array(&$this->bproceso, 'processForm'), false);
        if (PEAR::isError($ret)) {
            die($ret->getMessage());
        }
        if ($procAc) {
            $nacc =& objeto_tabla('accion');
            $nacc->fb_useMutators = true;
            $nacc->id_proceso = $this->bproceso->_do->id;
            $nacc->id_taccion = (int)$valores['id_taccion'];
            $nacc->id_despacho = (int)$valores['id_despacho'];
            $nacc->fecha = arr_a_fecha(var_escapa($valores['fecha'], $db, 20));
            $nacc->numeroradicado =
                var_escapa($valores['numeroradicado'], $db);
            $nacc->observacionesaccion =
                var_escapa($valores['observacionesaccion'], $db);
            $nacc->insert();
            $nacc->respondido= isset($valores['respondido'])
                && $valores['respondido'] == 1 ? 't' : 'f';
            $q = "UPDATE accion SET respondido='".$nacc->respondido."' " .
                " WHERE id='".$nacc->id."'";
            hace_consulta($db, $q);
            $procAc = false;
        }

        caso_funcionario($_SESSION['basicos_id']);
        return  $ret;
    }

    function datosBusqueda(&$w, &$t, &$db, $idcaso, &$subcons)
    {

    }

    function handle($action)
    {
//        echo "handle($action)";
//        print_r($this->_actions);
//        die("s");
        parent::handle($action);
    }

    /** Extrae procesos de un caso y retorna su información en varios
     *  vectores
     *
     *  @param integer $idcaso  Id. del Caso
     *  @param object  &$db     Conexión a BD
     *  @param array   &$idp    Para retornar identificación de procesos
     *
     *  @return integer Cantidad de procesos retornados
     **/
    function extrae_procesos($idcaso, &$db, &$idp)
    {
        $q = "SELECT  id FROM proceso WHERE " .
            "proceso.id_caso='" . (int)$idcaso . "' ORDER BY id";
        $result = hace_consulta($db, $q);
        $row = array();
        $tot = 0;
        while ($result->fetchInto($row)) {
            $idp[] = $row[0];
            $tot++;
        }
        return $tot;
    }

    static function iniCaptura()
    {
        if (isset($_REQUEST['eliminaaccionj'])) {
            $_REQUEST['_qf_segjudicial_eliminaaccionj'] = true;
        }
    }

    static function resConsultaInicio($mostrar, &$renglon, &$rtexto, $tot = 0)
    {
        if ($mostrar == "judicial") {
            echo "<html><head><title>Tabla</title></head>";
            echo "<body>";
            echo "Consulta de " . (int)$tot . " casos. ";
            echo "<p><table border=1 cellspacing=0 cellpadding=5>";
            $renglon = "<tr>";
            $rtexto = "";
        }
    }

    static function resConsultaRegistro(&$db, $mostrar, $idcaso, $campos,
        $conv, &$sal, &$retroalim)
    {
        if ($mostrar == "judicial") {
            ResConsulta::filaTabla(
                $db, $idcaso, $campos, $conv, $sal,
                $retroalim
            );
        }
    }

    static function resConsultaFinal($mostrar)
    {
        if ($mostrar == "judicial") {
            echo "</table>";
        }
    }

    static function consultaWebCreaConsulta(&$db, $mostrar, &$where, &$tablas,
        &$pOrdenar, &$campos)
    {
        if ($mostrar == "judicial") {
            consulta_and_sinap($where, "proceso.id_caso", "caso.id");
            $tablas .= ", proceso";
            $oconv = array('proceso_id', 'proceso_proximafecha');
            $pOrdenar = "fechajudicial";
            $campos['proceso_proximafecha'] = 'Próxima fecha';
        }
    }

    static function consultaWebFormaPresentacion($mostrar, $opciones,
        &$forma, &$ae, &$t)
    {
        if (isset($opciones) && in_array(42, $opciones)) {
            $x =&  $forma->createElement(
                'radio', 'mostrar',
                'judicial', 'Tabla Judicial', 'judicial'
            );
            $ae[] =& $x;
            if ($mostrar == 'judicial') {
                $t =& $x;
            }
        }
    }

    static function consultaWebOrden(&$q, $pOrdenar)
    {
        if ($pOrdenar == 'fechajudicial') {
            $q .= ' ORDER by proceso.proximafecha';
        }
    }

    /**
     * Llamada para inicializar variables globales
     */
    static function act_globales()
    {
        html_menu_agrega_submenu(
            $GLOBALS['menu_tablas_basicas'],
            null, 'Información Judicial',
            '', null
        );
        html_menu_agrega_submenu(
            $GLOBALS['menu_tablas_basicas'],
            'Información Judicial', 'Tipos de acciones judiciales',
            'taccion', null
        );
        html_menu_agrega_submenu(
            $GLOBALS['menu_tablas_basicas'],
            'Información Judicial', 'Tipos de proceso',
            'tproceso', null
        );
        html_menu_agrega_submenu(
            $GLOBALS['menu_tablas_basicas'],
            'Información Judicial', 'Despacho',
            'despacho', null
        );
        html_menu_agrega_submenu(
            $GLOBALS['menu_tablas_basicas'],
            'Información Judicial', 'Etapa',
            'etapa', null
        );
    }


    /**
     * Compara datos relacionados con esta pestaña de los casos
     * con identificación id1 e id2.
     *
     * @param object  &$db Conexión a base de datos
     * @param array   &$r  Para llenar resultados de comparación, cada
     *   entrada es de la forma
     *      id_unica => ('etiqueta', 'valor1', 'valor2', pref)
     *   donde valor1 es valor en primer caso, valor2 es valor en segundo
     *   caso y pref es 1 o 2 para indicar cual de los valores será por defecto
     * @param integer $id1 Código de primer caso
     * @param integer $id2 Código de segundo caso
     * @param array   $cls Especificación de las tablas por revisar.
     *
     * @return void Añade a $r datos de comparación
     * @see PagBaseSimple
     */
    static function compara(&$db, &$r, $id1, $id2, $cls)
    {
        PagBaseMultiple::compara(
            $db, $r, $id1, $id2,
            array('Seguimiento Judicial' => array('proceso', 'id_tproceso'))
        );
    }


    /**
     * Mezcla valores de los casos $id1 e $id2 en el caso $idn de
     * acuerdo a las preferencias especificadas en $sol.
     *
     * @param object  &$db Conexión a base de datos
     * @param array   $sol Arreglo con solicitudes de cambios de la forma
     *   id_unica => (pref)
     *   donde pref es 1 si el valor relacionado con id_unica debe
     *   tomarse del caso $id1 o 2 si debe tomarse de $id2.  Las
     *   identificaciones id_unica son las empleadas por la función
     *   compara.
     * @param integer $id1 Código de primer caso
     * @param integer $id2 Código de segundo caso
     * @param integer $idn Código del caso en el que aplicará los cambios
     * @param array   $cls Especificación de tablas por mezclar
     *
     * @return Mezcla valores de los casos $id1 e $id2 en el caso $idn de
     * acuerdo a las preferencias especificadas en $sol.
     * @see PagBaseSimple
     */
    static function mezcla(&$db, $sol, $id1, $id2, $idn, $cls)
    {
        //echo "OJO PagSegJudicial::mezcla(db, sol, $id1, $id2, $idn, $cls)";
        $e1 = isset($sol['proceso']['id_tproceso'])
            && $sol['proceso']['id_tproceso'] == 1;
        if (($e1 && $idn != $id1) || (!$e1 && $idn != $id2)) {
            PagSegJudicial::eliminaDep($db, $idn);
            PagBaseMultiple::mezcla(
                $db, $sol, $id1, $id2, $idn,
                array('Seguimiento Judicial' => array('proceso', 'id_tproceso'))
            );
            PagBaseMultiple::mezcla(
                $db, $sol, $id1, $id2, $idn,
                array('Seguimiento Judicial'
                => array('accion', 'id_tproceso', 'proceso'))
            );
            echo "Falta completar copia de Acciones en Seguimiento Judicial<br>";
        }
    }

}

?>
