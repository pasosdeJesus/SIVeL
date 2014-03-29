<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Pestaña con siguiente/anterior y partes del multi-formulario para
 * capturar caso (captura_caso.php)
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
require_once 'PagUbicacion.php';
require_once 'HTML/QuickForm/Action.php';

// Incluya DataObjects que son partes

/**
 * Pestaña BaseMultiplePartes
 * Ver documentación de funciones en clase base.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  Dominio público.
 * @link     http://sivel.sf.net/tec
 */
class PagBaseMultiplePartes extends PagBaseMultiple
{

    /** Nombre de una clase DataObject característica de este formulario */
    const CLASEMODELO = 'basemultiplepartes';

    // La llave sería id_caso junto con esta, por ahora una sola
    const LLAVECOMP = 'id_otratabla';

    /**
     * Definimos variables para subformularios (a su vez descendientes de
     * DB_DataObject_FormBuilder).
     * Convención sugerida: que comienzan con la letra b
     */
    var $btablaprincipal = null;
    // Agregar uno por cada parte

    /** Partes del formulario */
    const PARTES = 'tablaprincipal'; // agregar cada parte

    /** Tablas básicas creadas para este formulario */
    const BASICAS = 'basica1 basica2';

    /** De las partes del formulario aquellas que sean de multiple seleccion
     * (y por tanto no se procesen con process sino borrando e insertando)
     */
    var $partesmulti = array();

    var $pref = "tablaprincipal";

    /** Titulo que aparecerá en formulario */
    var $titulo = 'Tabla Principal';

    /** Titulo que aparecerá en Tablas Básicas*/
    const TITULO = 'Tabla Principal';

    var $nuevaCopia = false;

    /**
     * Pone en null variables asociadas a tablas de la pestaña.
     *
     * @return null
     */
    function nullVar()
    {
        $cll = get_called_class();
        $cpartes = $cll::PARTES;
        foreach (explode(' ', $cpartes) as $t) {
            $nb = 'b' . $t;
            $this->$nb = null;
        }
        //$this->bbasemultiplepartes = null;
    }

    /**
     * Retorna una identificación del registro actual.
     *
     * @return string Identifación
     */
    function copiaId()
    {
        $cll = get_called_class();
        $bcm = "b" . $cll::CLASEMODELO;
        $ll = $cll::LLAVECOMP;
        $a = array(
            $this->$bcm->_do->id_caso,
            $this->$bcm->_do->$ll
        );

        return $a;
    }

    /**
     * Elimina de base de datos el registro actual.
     *
     * @param array &$valores Valores enviados por formulario.
     *
     * @return null
     */
    function elimina(&$valores)
    {
        $this->iniVar();
        $cll = get_called_class();
        $bcm = "b" . $cll::CLASEMODELO;
        $ll = $cll::LLAVECOMP;
        $cpartes = $cll::PARTES;
        if (isset($this->$bcm->_do->$ll)) {
            $partes = array_diff(
                explode(' ', $cpartes), array($cll::CLASEMODELO)
            );
            for ($i = 0; $i < 2; $i++) {
                foreach ($partes as $t) {
                    $nb = 'b' . $t;
                    if (isset($this->$nb->_do->id_caso)
                        && isset($this->$nb->_do->$ll)
                    ) {
                        $this->$nb->_do->delete();
                    }
                }
            }
            $this->eliminaClaseModelo(
                $this->$bcm->_do, true
            );
            $_SESSION[$this->pref.'_total']--;
        }
    }


     /**
     * Inicializa variables y datos de la pestaña.
     * Ver documentación completa en clase base.
     *
     * @param array $aper Arreglo de parametros. Vacio aqui.
     *
     * @return handle Conexión a base de datos
     */
    function iniVar($aper = null)
    {
        $cll = get_called_class();
        $cm = $cll::CLASEMODELO;
        $bcm = "b" . $cm;
        $dcm =& objeto_tabla($cm);
        $ll = $cll::LLAVECOMP;
        $cpartes = $cll::PARTES;
        $db =& $dcm->getDatabaseConnection();

        $idcaso =& $_SESSION['basicos_id'];
        if (!isset($idcaso) || $idcaso == null) {
            die("Bug: idcaso no debería ser null");
        }

        $ida = array();
        $indid = -1;
        $tot = self::extrae_clasemodelo($idcaso, $db, $ida);

        $_SESSION[$this->pref.'_total'] = $tot;
        $dcm->id_caso = $idcaso;
        //print_r($_SESSION);
        if ($_SESSION[$this->pref.'_pag'] < 0
            || $_SESSION[$this->pref.'_pag'] >= $tot
        ) {
            $dcm->$ll = '';
        } else {
            $dcm->$ll = $ida[$_SESSION[$this->pref.'_pag']];
            $dcm->id_caso = $idcaso;
            $dcm->find();
            $dcm->fetch();
        }

        $partes = array_diff(
            explode(' ', $cpartes), array($cm)
        );
        foreach ($partes as $t) {
            $nb = 'b' . $t;
            $ndo = 'd' . $t;
            $$ndo = objeto_tabla($t);
            sin_error_pear($$ndo);

            $$ndo->id_caso = $idcaso;
            if ($_SESSION[$this->pref.'_pag'] < 0
                || $_SESSION[$this->pref.'_pag'] >= $tot
            ) {
                    $$ndo->$ll= null;
            } else {
                $$ndo->$ll = $ida[$_SESSION[$this->pref.'_pag']];
                $$ndo->find();
                if (!isset($this->partesmulti[$ndo])) {
                    $$ndo->fetch();
                }
            }

            $this->$nb = DB_DataObject_FormBuilder::create(
                $$ndo, array('requiredRuleMessage' => $GLOBALS['mreglareq'],
                'ruleViolationMessage' => $GLOBALS['mreglavio']
                )
            );
            sin_error_pear($this->$nb);
            $this->$nb->_do->useMutators = true;
        }
        $this->$bcm =& DB_DataObject_FormBuilder::create(
            $dcm, array(
                'requiredRuleMessage' => $GLOBALS['mreglareq'],
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
     *
     * @return void
     */
    function PagBaseMultiplePartes($nomForma)
    {
        parent::PagBaseMultiple($nomForma);
        $this->titulo  = 'Pag. Base Múltiple';
        $this->tcorto  = 'Pag. Base Múltiple';
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
        $this->addElement('');
        $this->addElement('hidden', 'id_caso', $idcaso);

        $cll = get_called_class();
        $cpartes = $cll::PARTES;
        foreach (explode(' ', $cpartes) as $t) {
            $nb = 'b' . $t;
            $nf = 'f' . $t;
            $eti = isset($GLOBALS['etiqueta'][$t]) ?
                $GLOBALS['etiqueta'][$t] : '';
            if ($eti != '') {
                $this->addElement('header', $t, $eti);
            }
            $this->$nb->createSubmit = 0;
            $this->$nb->useForm($this);
            $$nf =& $this->$nb->getForm($this);
        }
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
        $cll = get_called_class();
        $cpartes = $cll::PARTES;
        $bcm = "b" . $cll::CLASEMODELO;
        valores_pordefecto_form($this->$bcm->_do, $this);
        foreach (explode(' ', $cpartes) as $t) {
            $nb = 'b' . $t;
            valores_pordefecto_form($this->$nb->_do, $this, false);
        }
    }


    /**
     * Elimina clasemodelo
     *
     * @param object $dcm DataObject de la clase modelo
     *
     * @return void
     */
    function eliminaClasemodelo($dcm, $idcaso = null)
    {
        assert($dcm != null);
        assert($dcm->id_caso != null);
        if ($idcaso == null) {
            $idcaso = $_SESSION['basicos_id'];
        }
        //echo "OJO eliminaClasemodelo({$dcm->__table}, $idcaso)<br>";
        $db =& $dcm->getDatabaseConnection();
        $cll = get_called_class();
        $cm = $cll::CLASEMODELO;
        $q = "DELETE FROM $cm WHERE id_caso={$idcaso}";
        $result = hace_consulta($db, $q);
    }

    /**
     * eliminaDep($db, $idcaso) elimina datos de la base $db presentados
     * en este formulario, que dependen del caso $idcaso
     *
     * @param object  &$db    Conexión a base
     * @param integer $idcaso Identificación del caso
     *
     * @return void
     */
    static function eliminaDep(&$db, $idcaso)
    {
        assert($db != null);
        assert(isset($idcaso));

        $cll = get_called_class();
        $cpartes = $cll::PARTES;
        $partes = array_reverse(
            array_diff(explode(' ', $cpartes), array($cll::CLASEMODELO))
        );
        $ll = $cll::LLAVECOMP;
        foreach ($partes as $t) {
            //echo "OJO t=$t<br>";
            $do =& objeto_tabla($t);
            sin_error_pear($do);
            $do->id_caso = $idcaso;
            $do->find();
            $cp = array();
            while ($do->fetch()) {
                $cp[] = $do->$ll;
            }
            foreach ($cp as $num) {
                //echo "OJO num=$num<br>";
                $do =& objeto_tabla($t);
                $do->id_caso = $idcaso;
                $do->$ll = $num;
                $do->fetch(1);
                $do->delete();
            }
        }
        //echo "OJO $cll::CLASEMODELO<br>";
        $dcm =& objeto_tabla($cll::CLASEMODELO);
        sin_error_pear($dcm);
        $dcm->id_caso = $idcaso;
        $dcm->find();
        while ($dcm->fetch()) {
            //echo "OJO llama eliminaClaseModelo($dcm->__table)<br>";
            //print_r($dcm);
            self::eliminaClasemodelo($dcm, $idcaso);
            $dcm->delete();
        }
    }

    /**
     * Procesa datos del formulario insertando o actualizando
     *
     * @param array  &$valores  Recibios de formulario
     * @param string $otratabla Otra tabla
     *
     * @return void
     */
    function procesa(&$valores, $otratabla = '')
    {

        //echo "OJO PagAyudahumanitaria::procesa(" ; print_r($valores); echo ")<br>";
        $cll = get_called_class();
        $ll = $cll::LLAVECOMP;
        $cm = $cll::CLASEMODELO;
        $cpartes = $cll::PARTES;
        $bcm = "b" . $cm;
        $es_vacio = (!isset($valores[$ll])
            || $valores[$ll] === ''
        );

        if ($es_vacio) {
            return true;
        }

                    
        $vll = var_escapa($valores[$ll]);
        if (!$this->validate() ) {
            return false;
        }

        $db = $this->iniVar();
        $dcaso = objeto_tabla('caso');
        if (PEAR::isError($dcaso)) {
            die($dcaso->getMessage());
        }
        $idcaso = $_SESSION['basicos_id'];
        $this->$bcm->_do->useMutators = true;
        $q = "SELECT COUNT(*) FROM $cm WHERE id_caso='$idcaso' "
            ." AND $ll='$vll'";
        $nr = (int)$db->getOne($q);
        //echo "<hr>OJO q=$q, nr=$nr<br>";
        if ($this->$bcm->_do->$ll == null
            || $this->$bcm->_do->$ll == ''
        ) {
            if ($nr > 0) {
                error_valida(
                    _('Ya había una') . " $cm " . _('con la') . " $ll "
                    . _('dada'),
                    var_escapa($valores)
                );
                return false;
            }
        }
        if ($nr == 0) {
            $this->$bcm->forceQueryType(
                DB_DATAOBJECT_FORMBUILDER_QUERY_FORCEINSERT
            );
        } else {
            $this->$bcm->forceQueryType(
                DB_DATAOBJECT_FORMBUILDER_QUERY_FORCEUPDATE
            );
        }

        $ret = $this->process(
            array(&$this->$bcm, 'processForm'), false
        );
        if (PEAR::isError($ret)) {
            die($ret->getMessage());
        }

        //echo "OJO PagAyudahumanitaria::procesa bayudahumanitaria procesado<br>";
        $fa = null;
        $tab = null;
        $partes = array_diff(
            explode(' ', $cpartes), array($cm)
        );
        foreach ($partes as $t) {
            $nb = 'b' . $t;
            $keys = $this->$nb->_do->keys();
            $vk = array();
            foreach ($keys as $k) {
                $vk[$k] = null;
                if (isset($valores[$k])) {
                    //echo "OJO 2 k=$k, valores[k]=" . $valores[$k] . "<br>";
                    $vk[$k] = valor_fb2do(
                        var_escapa($valores[$k]), 
                        $this->$nb->_do->__table, $k, $tab
                    );
                }
            }
            if (!isset($this->partesmulti[$t])) {
                $this->$nb->forceQueryType(
                    DB_DATAOBJECT_FORMBUILDER_QUERY_FORCEINSERT
                );
                $q = "SELECT COUNT(*) FROM $t WHERE ";
                $sep = " ";
                $haynull = false;
                foreach ($vk as $k => $v) {
                    // echo "OJO k=$k, v=$v<br>";
                    if ($v == null) {
                        $haynull = true;
                    }
                    $q .= $sep . $k . "='" . $v . "'";
                    $sep = " AND ";
                }
                if (!$haynull) {
                    $nr = (int)$db->getOne($q);
                    if ($nr > 0) {
                        //echo "OJO 4 update, q=$q, nr=$nr<br>";
                        $this->$nb->forceQueryType(
                            DB_DATAOBJECT_FORMBUILDER_QUERY_FORCEUPDATE
                        );
                    }
                    //echo "OJO 3 procesando<br>";
                    if (!isset($otratabla) || $otratabla == $t) {
                        $ret = $this->process(
                            array(&$this->$nb, 'processForm'),
                            false
                        );
                    }
                }
            } else {
                $result = hace_consulta(
                    $db, "DELETE FROM $t " .
                    " WHERE id_caso='$idcaso' AND $ll='$fa'"
                );
                $c = $this->partesmulti[$t];
                if (isset($valores[$c])) {
                    foreach (var_escapa($valores[$c]) as $k => $v) {
                        $do = objeto_tabla($t);
                        $do->id_caso = $idcaso;
                        $do->$ll = $fa;
                        $do->$c = $v;
                        $ret = $do->insert();
                        $do->free();
                    }
                }
            }
            if (PEAR::isError($ret)) {
                die($ret->getMessage());
            }
        }
        caso_usuario($idcaso);
        return  $ret;
    }

    /**
     * Para consulta detallada
     *
     * @param string  &$w       consulta que se arma
     * @param string  &$t       Tablas involucradas
     * @param object  &$db      Conexión a base de datos
     * @param integer $idcaso   Identificación del caso
     * @param string  &$subcons Subconsulta$idcaso  Identificación del caso
     *
     * @return void Modifica parametros
     */
    function datosBusqueda(&$w, &$t, &$db, $idcaso, &$subcons)
    {

    }

    /**
     * Interrumpe controlador
     *
     * @param string $action Accion solicitada
     *
     * @return void
     */
    function handle($action)
    {
        //print_r($this->_actions);
        parent::handle($action);
    }


    /**
     * Extrae tablaprincipal de un caso y retorna su información en
     * vectores
     *
     *  @param integer $idcaso Id. del Caso
     *  @param object  &$db    Conexión a BD
     *  @param array   &$ida   Para retornar llaves primarias
     *
     *  @return integer Cantidad de tablaprincipal retornados
     **/
    function extrae_clasemodelo($idcaso, &$db, &$ida)
    {
        $cll = get_called_class();
        $ll = $cll::LLAVECOMP;
        $cm = $cll::CLASEMODELO;
        $q = "SELECT $ll FROM $cm WHERE "
            . "$cm.id_caso='" . (int)$idcaso
            . "' ORDER BY $ll DESC";
        $result = hace_consulta($db, $q);
        $row = array();
        $tot = 0;
        while ($result->fetchInto($row)) {
            $ida[] = $row[0];
            $tot++;
        }
        return $tot;
    }

    /**
     * Llamada para inicializar variables globales
     *
     * @return void
     */
    static function act_globales()
    {
        parent::act_globales();
        $cll = get_called_class();
        $cbasicas = $cll::BASICAS;
        $nid = _($cll::TITULO);
        if (isset($cbasicas) && $cbasicas != '') {
            html_menu_agrega_submenu(
                $GLOBALS['menu_tablas_basicas'],
                null, $nid,
                '', null
            );
            foreach (explode(' ', $cbasicas) as $b) {
                $do = objeto_tabla($b);
                html_menu_agrega_submenu(
                    $GLOBALS['menu_tablas_basicas'],
                    $nid, $do->nom_tabla,
                    "$b", null
                );
            }
        }
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
            array(
                'BaseMultiplePartes' =>
                array('basemultiplepartes', 'fechaatencion')
            )
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
        //echo "OJO PagBaseMultiplePartes::mezcla(db, sol, $id1, $id2, $idn, $cls)";
        $e1 = isset($sol['basemultiplepartes']['fechaatencion'])
            && $sol['basemultiplepartes']['fechaatencion'] == 1;
        if (($e1 && $idn != $id1) || (!$e1 && $idn != $id2)) {
            PagBaseMultiplePartes::eliminaDep($db, $idn);
            PagBaseMultiple::mezcla(
                $db, $sol, $id1, $id2, $idn,
                array('BaseMultiplePartes' => array(
                    'basemultiplepartes', 'fechaatencion'
                ))
            );
            echo "Falta completar copia de BaseMultiplePartes<br>";
        }
    }

}

?>
