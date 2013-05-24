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
 * @copyright 2013 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

require_once 'PagBaseMultiple.php';
require_once 'ResConsulta.php';
require_once 'HTML/QuickForm/Action.php';

require_once 'DataObjects/Acreditacion.php';
require_once 'DataObjects/Causadesp.php';
require_once 'DataObjects/Clasifdesp.php';
require_once 'DataObjects/Declaroante.php';
require_once 'DataObjects/Desplazamiento.php';
require_once 'DataObjects/Inclusion.php';
require_once 'DataObjects/Modalidadtierra.php';
require_once 'DataObjects/Tipodesp.php';


/**
 * Página Desplazamiento
 * Ver documentación de funciones en clase base.
 * @package SIVeL
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  Dominio público.
 * @link     http://sivel.sf.net/tec
*/
class PagDesplazamiento extends PagBaseMultiple
{

    var $bdesplazamiento;

    var $pref = "des";

    var $nuevaCopia = false;

    var $clase_modelo = 'desplazamiento';

    var $titulo = 'Desplazamiento';

    /**
     * Pone en null variables asociadas a tablas de la pestaña.
     *
     * @return null
     */
    function nullVar()
    {
        $this->bdesplazamiento= null;
    }

    /**
     * Retorna una identificación del registro actual.
     *
     * @return string Identifación
     */
    function copiaId()
    {
        return array(
            $this->bdesplazamiento->_do->id_caso,
            $this->bdesplazamiento->_do->fechaexpulsion
        );
    }

    function elimina(&$values)
    {
        $this->iniVar();
        if (isset($this->bdesplazamiento->_do->fechaexpulsion)) {
            $this->eliminaDesplazamiento($this->bdesplazamiento->_do, true);
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
        $ddesplazamiento =& objeto_tabla('desplazamiento');

        $db =& $ddesplazamiento->getDatabaseConnection();

        $idcaso =& $_SESSION['basicos_id'];
        if (!isset($idcaso) || $idcaso == null) {
            die("Bug: idcaso no debería ser null");
        }

        $idp = array();
        $ndp = array();
        $edp = array();
        $indid = -1;
        $tot = PagDesplazamiento::extrae_desplazamientos($idcaso, $db, $idf);

        $_SESSION[$this->pref.'_total'] = $tot;
        $ddesplazamiento->id_caso= $idcaso;
        if ($_SESSION[$this->pref.'_pag'] < 0
            || $_SESSION[$this->pref.'_pag'] >= $tot
        ) {
            $ddesplazamiento->fechaexpulsion = null;
        } else {
            $ddesplazamiento->fechaexpulsion = 
                $idf[$_SESSION[$this->pref.'_pag']];
            $ddesplazamiento->id_caso = $idcaso;
            $ddesplazamiento->find();
            $ddesplazamiento->fetch();
        }

        $this->bdesplazamiento =& DB_DataObject_FormBuilder::create(
            $ddesplazamiento,
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
    function PagDesplazamiento($nomForma)
    {
        parent::PagBaseMultiple($nomForma);
        $this->titulo  = _('Desplazamiento');
        $this->tcorto  = _('Desplazamiento');

        $this->addAction('siguiente', new Siguiente());
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
        $vv = isset($this->bdesplazamiento->_do->fechaexpulsion)
            ? $this->bdesplazamiento->_do->fechaexpulsion : '';
        $this->addElement('');

        $_SESSION['pagDesplazamiento_id'] = $vv;

        $this->bdesplazamiento->createSubmit = 0;
        $this->bdesplazamiento->useForm($this);
        $this->bdesplazamiento->getForm($this);
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
        $vv = isset($this->bdesplazamiento->_do->fechaexpulsion) ?
            $this->bdesplazamiento->_do->fechaexpulsion : '';
    }

    function eliminaDesplazamiento($ddesplazamiento, $elimProc = false)
    {
        assert($ddesplazamiento != null);
        assert($ddesplazamiento->fechaexpulsion != null);
        $db =& $ddesplazamiento->getDatabaseConnection();
        $q = "DELETE FROM desplazamiento WHERE fechaexpulsion='{$ddesplazamiento->fechaexpulsion}' AND id_caso={$_SESSION['id_basico']}";
        $result = hace_consulta($db, $q);
        if ($elimProc) {
        }
    }

    /** eliminaDep($db, $idcaso) elimina victimas de la base $db presentados
            en este formulario, que dependen del caso $idcaso */
    static function eliminaDep(&$db, $idcaso)
    {
        assert($db != null);
        assert(isset($idcaso));
        $ddesplazamiento =& objeto_tabla('desplazamiento');
        sin_error_pear($ddesplazamiento);
        $ddesplazamiento->id_caso = $idcaso;
        $ddesplazamiento->find();
        while ($ddesplazamiento->fetch()) {
            PagDesplazamiento::eliminaProceso($ddesplazamiento);
            $ddesplazamiento->delete();
        }
    }

    /**
    * @param procAc es true si y solo si debe añadirse Acción
    */
    function procesa(&$valores)
    {
        $valores['fechaexpulsion'] = 
            arr_a_fecha($valores['fechaexpulsion'], true);
        $es_vacio = (!isset($valores['expulsion'])
                || $valores['expulsion'] === ''
            );

        if ($es_vacio) {
            return true;
        }

        if (!$this->validate() ) {
            return false;
        }
        if (in_array(31, $_SESSION['opciones'])
            && !in_array(21, $_SESSION['opciones'])
        ) {
            return true;
        }


        $db = $this->iniVar(array((int)$valores['fechaexpulsion']));
        $dcaso = objeto_tabla('caso');
        if (PEAR::isError($dcaso)) {
            die($dcaso->getMessage());
        }

        $ret = $this->process(
            array(&$this->bdesplazamiento, 'processForm'), false
        );
        if (PEAR::isError($ret)) {
            die($ret->getMessage());
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

    /** Extrae desplazamientos de un caso y retorna su información en 
     *  vectores
     *
     *  @param integer $idcaso  Id. del Caso
     *  @param object  &$db     Conexión a BD
     *  @param array   &$idf    Para retornar fechas
     *
     *  @return integer Cantidad de desplazamientos retornados
     **/
    function extrae_desplazamientos($idcaso, &$db, &$idf)
    {
        $q = "SELECT fechaexpulsion FROM desplazamiento WHERE " 
            . "desplazamiento.id_caso='" . (int)$idcaso 
            . "' ORDER BY fechaexpulsion";
        $result = hace_consulta($db, $q);
        $row = array();
        $tot = 0;
        while ($result->fetchInto($row)) {
            $idf[] = $row[0];
            $tot++;
        }
        return $tot;
    }

    /**
     * Llamada para inicializar variables globales
     */
    static function act_globales()
    {
        html_menu_agrega_submenu(
            $GLOBALS['menu_tablas_basicas'],
            null, 'Desplazamiento',
            '', null
        );
        html_menu_agrega_submenu(
            $GLOBALS['menu_tablas_basicas'],
            'Desplazamiento', 'Clasificación',
            'clasifdesp', null
        );
        html_menu_agrega_submenu(
            $GLOBALS['menu_tablas_basicas'],
            'Desplazamiento', 'Tipos',
            'tipodesp', null
        );
        html_menu_agrega_submenu(
            $GLOBALS['menu_tablas_basicas'],
            'Desplazamiento', 'Causas',
            'acreditacion', null
        );
        html_menu_agrega_submenu(
            $GLOBALS['menu_tablas_basicas'],
            'Desplazamiento', 'Entidades para declarar',
            'declaroante', null
        );
        html_menu_agrega_submenu(
            $GLOBALS['menu_tablas_basicas'],
            'Desplazamiento', 'Inclusión',
            'inclusion', null
        );
        html_menu_agrega_submenu(
            $GLOBALS['menu_tablas_basicas'],
            'Desplazamiento', 'Modalidad Tenencia de Tierra',
            'modalidadtierra', null
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
            array('Desplazamiento' => array('desplazamiento', 'fechaexpulsion'))
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
        //echo "OJO PagDesplazamiento::mezcla(db, sol, $id1, $id2, $idn, $cls)";
        $e1 = isset($sol['desplazamiento']['fechaexpulsion'])
            && $sol['desplazamiento']['fechaexpulsion'] == 1;
        if (($e1 && $idn != $id1) || (!$e1 && $idn != $id2)) {
            PagDesplazamiento::eliminaDep($db, $idn);
            PagBaseMultiple::mezcla(
                $db, $sol, $id1, $id2, $idn,
                array('Desplazamiento' => array(
                    'desplazamiento', 'id_fechaexpulsion'
                ))
            );
            echo "Falta completar copia de Desplazamiento<br>";
        }
    }

}

?>
