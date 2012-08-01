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
 * Pestaña Víctima Combatiente de la ficha de captura de caso
 */
require_once 'PagBaseMultiple.php';
require_once 'ResConsulta.php';

require_once 'DataObjects/Rango_edad.php';
require_once 'DataObjects/Sector_social.php';
require_once 'DataObjects/Vinculo_estado.php';
require_once 'DataObjects/Filiacion.php';
require_once 'DataObjects/Organizacion.php';
require_once 'DataObjects/Profesion.php';
require_once 'DataObjects/Presuntos_responsables.php';
require_once 'DataObjects/Resultado_agresion.php';

/**
 * Víctima Combatiente.
 * Ver documentación de funciones en clase base.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see PagBaseMultiple
 */
class PagVictimaCombatiente extends PagBaseMultiple
{

    /** Combatiente */
    var $bcombatiente;
    /** Antecedentes */
    var $bantecedente_combatiente;

    var $pref = "fvm";

    var $nuevaCopia = false;

    var $clase_modelo = 'combatiente';

    /**
     * Pone en null variables asociadas a tablas de la pestaña.
     *
     * @return null
     */
    function nullVar()
    {
        $this->bcombatiente = null;
        $this->bantecedente_combatiente = null;
    }


    /**
     * Retorna una identificación del registro actual.
     *
     * @return string Identifación
     */
    function copiaId()
    {
        return $this->bcombatiente->_do->id;
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
        if (isset($this->bcombatiente->_do->id)) {
            $this->eliminaVic($this->bcombatiente->_do, true);
            $_SESSION['fvm_total']--;
        }
    }

     /**
     * Inicializa variables.
     *
     * @param integer $id_combatiente Id  de víctima combatiente
     *
     * @return handle Conexión a base de datos
     */
    function iniVar($id_combatiente = null)
    {
        $dcombatiente =& objeto_tabla('combatiente');
        $dantecedente_combatiente =&
            objeto_tabla('antecedente_combatiente');
        $db =& $dcombatiente->getDatabaseConnection();

        $idcaso =& $_SESSION['basicos_id'];
        if (!isset($idcaso) || $idcaso == null) {
            die("Bug: idcaso no debería ser null");
        }

        $idp = array();
        $ndp = array();
        $indid = -1;
        $tot = ResConsulta::extraeCombatientes(
            $idcaso, $db, $idp,
            $ndp, $id_combatiente, $indid
        );
        $_SESSION['fvm_total'] = $tot;
        if ($indid >= 0) {
            $_SESSION['fvm_pag'] = $indid;
        }
        $dcombatiente->id_caso = $idcaso;
        if ($_SESSION['fvm_pag'] < 0 || $_SESSION['fvm_pag'] >= $tot) {
            $dcombatiente->id = null;
        } else {
            $dcombatiente->id = $idp[$_SESSION['fvm_pag']];
            $dcombatiente->find();
            $dcombatiente->fetch();
        }
        $dantecedente_combatiente->id_combatiente = $dcombatiente->id;

        $this->bcombatiente =& DB_DataObject_FormBuilder::create(
            $dcombatiente,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
                'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bcombatiente->useMutators = true;
        $this->bantecedente_combatiente =&
            DB_DataObject_FormBuilder::create(
                $dantecedente_combatiente,
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
    function PagVictimaCombatiente($nomForma)
    {
        parent::PagBaseMultiple($nomForma);
        $this->titulo  = _('Víctima Combatiente');
        $this->tcorto  = _('Comb.');
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
        $vv = isset($this->bcombatiente->_do->id) ?
            $this->bcombatiente->_do->id : '';
        $this->addElement('hidden', 'id', $vv);

        $this->bcombatiente->createSubmit = 0;
        $this->bcombatiente->useForm($this);
        $this->bcombatiente->getForm();

        $this->bantecedente_combatiente->createSubmit = 0;
        $this->bantecedente_combatiente->useForm($this);
        $this->bantecedente_combatiente->getForm();

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
        $vv = isset($this->bcombatiente->_do->id) ?
            $this->bcombatiente->_do->id : '';

        $campos = array_merge(
            $this->bcombatiente->_do->fb_fieldsToRender,
            array('id_antecedente')
        );
        //$sca =& $this->getElement('id_antecedente');
            $valsca = array();

        if (isset($_SESSION['recuperaErrorValida'])) {
            $v = $_SESSION['recuperaErrorValida'];
        } else if (isset($_SESSION['nuevo_copia_id'])) {
            $id = $_SESSION['nuevo_copia_id'];
            unset($_SESSION['nuevo_copia_id']);

            $d =& objeto_tabla('combatiente');
            $d->get('id', $id);
            foreach ($d->fb_fieldsToRender as $c) {
                /*$cq = $this->getElement($c);
                $cq->setValue($d->$c);*/
                $v[$c] = $d->$c;
            }

            $d =& objeto_tabla('antecedente_combatiente');
            $d->id_combatiente = $id;
            $d->find();
            while ($d->fetch()) {
                $valsca[] = $d->id_antecedente;
            }
            $v['id_antecedente'] = $valsca;
        } else {
            foreach ($this->bcombatiente->_do->fb_fieldsToRender as $c) {
                $cq = $this->getElement($c);
                if (isset($this->bcombatiente->_do->$c)) {
                    $v[$c] = $this->bcombatiente->_do->$c;
                    //$cq->setValue($this->bcombatiente->_do->$c);
                }
            }


            if ($vv != '') {
                $this->bantecedente_combatiente->_do->find();
                while ($this->bantecedente_combatiente->_do->fetch()) {
                    $valsca[] =
                        $this->bantecedente_combatiente->_do->id_antecedente;
                }
            }
            $v['id_antecedente'] = $valsca;
        }
        establece_valores_form($this, $campos, $v);

        if (isset($_SESSION['recuperaErrorValida'])) {
            unset($_SESSION['recuperaErrorValida']);
        }
    }


    /**
     * Elimina de la base antecedentes de un combatiente y opcionalmente
     * datos del combatiente también.
     *
     * @param object $dcombatiente    objeto con campos ya llenos identifican
     * datos de combatiente por eliminar
     * @param bool   $elimcombatiente elimina también el combatiente.
     *
     * @return void
     */
    function eliminaVic($dcombatiente, $elimcombatiente = false)
    {
        assert($dcombatiente != null);
        assert($dcombatiente->id != null);

        if ($dcombatiente->id != null) {
            $db =& $dcombatiente->getDatabaseConnection();
            $idcombatiente = $dcombatiente->id;
            $result = hace_consulta(
                $db, "DELETE FROM antecedente_combatiente " .
                    "WHERE id_combatiente='$idcombatiente'"
                );
            if ($elimcombatiente) {
                $dcombatiente->delete();
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

        $dcombatiente =& objeto_tabla('combatiente');
        $dcombatiente->id_caso = $idcaso;
        $dcombatiente->find();

        $cp = array();
        while ($dcombatiente->fetch()) {
            $cp[] = $dcombatiente->id;
        }
        foreach ($cp as $idv) {
            $dcombatiente->get('id', $idv);
            PagVictimaCombatiente::eliminaVic($dcombatiente, true);
        }
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
        $es_vacio = (!isset($valores['nombre']) || $valores['nombre'] == '')
            && (!isset($valores['alias']) || $valores['alias'] == '')
            && (!isset($valores['edad']) || $valores['edad'] == '')
            && (!isset($valores['sexo']) || $valores['sexo'] == 'S')
            && (!isset($valores['id_rango_edad'])
            || $valores['id_rango_edad']==DataObjects_Rango_edad::idSinInfo()
            )
            && (!isset($valores['id_sector_social'])
            || $valores['id_sector_social']==
                DataObjects_Sector_social::idSinInfo()
            )
            && (!isset($valores['id_vinculo_estado'])
            || $valores['id_vinculo_estado']==
                DataObjects_Vinculo_estado::idSinInfo()
                )
            && (!isset($valores['id_filiacion'])
            || $valores['id_filiacion']==DataObjects_Filiacion::idSinInfo()
                )
            && (!isset($valores['id_profesion'])
            || $valores['id_profesion']==DataObjects_Profesion::idSinInfo()
            )
            && (!isset($valores['id_organizacion'])
            || $valores['id_organizacion']==
                DataObjects_Organizacion::idSinInfo()
            )
            && (!isset($valores['id_organizacion_armada'])
            || $valores['id_organizacion_armada']==
                DataObjects_Presuntos_responsables::idSinInfo()
                )
            ;
        if ($es_vacio) {
            return true;
        }
        if (!$this->validate() ) {
            return false;
        }
        verifica_sin_CSRF($valores);
        if ($valores['id_rango_edad']!=DataObjects_Rango_edad::idSinInfo()
            && $valores['edad'] != ''
        ) {
            $r = (int)var_escapa($valores['id_rango_edad'], $db);
            $e = var_escapa($valores['edad'], $db);
            if (!verifica_edad_y_rango($e, $r)) {
                error_valida(
                    'La edad debe corresponder al rango de edad',
                    $valores, 'fvm_error_valida'
                );
                return false;
            }
        } else if ($valores['edad'] != '') { //Autocompleta
            $re = rango_de_edad(var_escapa($valores['edad'], $db));
            if ($re == 0) {
                error_valida(
                    'La edad ingresada no corresponde a rango alguno',
                    $valores, 'fvm_error_valida'
                );
                return false;
            }
            $valores['id_rango_edad'] = $re;
        }

        if ((!isset($_SESSION['forma_modo'])
            || $_SESSION['forma_modo'] != 'busqueda'
        )
        ) {
            if (!isset($valores['nombre'])
                || trim($valores['nombre']) == ''
            ) {
                error_valida('Falta nombre de víctima', $valores);
                return false;
            }
            if (!isset($valores['id_resultado_agresion'])
                    || $valores['id_resultado_agresion'] == ''
            ) {
                error_valida('Falta resultado de agresión', $valores);
                return false;
            }
        }

        if (!isset($valores['id']) || $valores['id'] == '') {
            $valores['id'] = null;
            $db = $this->iniVar();
        } else {
            $db = $this->iniVar((int)$valores['id']);
        }

        $idcaso = $this->bcombatiente->_do->id_caso;
        $nuevo = $this->bcombatiente->_do->id == null;
        $ret = $this->process(
            array(&$this->bcombatiente, 'processForm'),
            false
        );
        if (PEAR::isError($ret)) {
            die($ret->getMessage());
        }
        if (isset($this->bcombatiente->_do->id)) {
            $idcombatiente = $this->bcombatiente->_do->id;
            if ($nuevo) {
                $_SESSION['fvm_total']++;
            } else {
                $this->eliminaVic($this->bcombatiente->_do, false);
            }
/*            $q = "DELETE FROM antecedente_combatiente " .
                "WHERE id_combatiente='$idcombatiente'";
            $result = hace_consulta($db, $q); */
            if (isset($valores['id_antecedente'])) {
                foreach (var_escapa($valores['id_antecedente']) as $k => $v) {
                    $this->bantecedente_combatiente->_do->id_combatiente =
                        $idcombatiente;
                    $this->bantecedente_combatiente->_do->id_antecedente =
                        (int)var_escapa($v, $db);
                    $this->bantecedente_combatiente->_do->insert();
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
     * @param object &$dCaso   Objeto con caso
     * @param string &$subcons Subconsulta
     *
     * @return void
     * @see PagBaseSimple
     */
    function datosBusqueda(&$w, &$t, &$db, $idcaso, &$subcons)
    {
        prepara_consulta_gen(
            $w, $t, $idcaso, 'combatiente',
            '', '', false, array('antecedente_combatiente'),
            'id_combatiente',
            array('edad', 'id_resultado_agresion')
        );

    }


    static function reporteGeneralRegistroHtml(&$db, $campos, $idcaso)
    {
        // Victimas combatientes

        $sep = "\n\n".$GLOBALS['etiqueta']['victimas_combatientes'] . ":\n    ";
        $dcombatiente = objeto_tabla('combatiente');
        $dcombatiente->id_caso = $idcaso;
        $dcombatiente->find();
        $r = "";
        while ($dcombatiente->fetch()) {
            $r .= $sep . trim($dcombatiente->nombre);
            $r .= "\n    ".$GLOBALS['etiqueta']['resultado_agresion'] . ": ";
            $dresultado = $dcombatiente->getLink('id_resultado_agresion');
            $r .= $dresultado->nombre;
            $dresultado = $dcombatiente->getLink('id_resultado_agresion');
            $r .= " (".trim($dresultado->nombre).")";
            if ($dcombatiente->id_sector_social!=
                DataObjects_Sector_social::idSinInfo()
            ) {
                    $r .= "\n    ".$GLOBALS['etiqueta']['sector_social'] . ": ";
                    $dsectorsocial = $dcombatiente->
                        getLink('id_sector_social');
                    $r .= $dsectorsocial->nombre;
                }
            if ($dcombatiente->id_profesion!=
                DataObjects_Profesion::idSinInfo()
            ) {
                    $r .= "\n    ".$GLOBALS['etiqueta']['profesion'] . ": ";
                    $dprofesion = $dcombatiente->getLink('id_profesion');
                    $r .= $dprofesion->nombre;
                }
            if ($dcombatiente->id_organizacion_armada!=
                DataObjects_Presuntos_Responsables::idSinInfo()
            ) {
                    $r .= "\n    " .
                        $GLOBALS['etiqueta']['organizacion_armada'] . ": ";
                    $dorgarmada = $dcombatiente->
                        getLink('id_organizacion_armada');
                    $r .= $dorgarmada->nombre;
                }
            $sep = "\n\n    ";
        }

        return $r;
    }

    static function reporteRevistaRegistroHtml(&$db, $campos, $idcaso)
    {
        $dcombatiente = objeto_tabla('combatiente');
        if (PEAR::isError($dcombatiente)) {
            die($dcombatiente->getMessage());
        }
        $dcombatiente->id_caso = $idcaso;
        $dcombatiente->find();

        $r ="";
        while ($dcombatiente->fetch()) {
            $r .= trim($dcombatiente->nombre);
            if ($dcombatiente->id_organizacion_armada!=
                DataObjects_Presuntos_responsables::idSinInfo()
            ) {
                    $dorg = $dcombatiente->
                        getLink('id_organizacion_armada');
                    $r .= " / ".trim($dorg->nombre);
                }
            if (isset($dcombatiente->id_resultado_agresion)) {
                $dresultado = $dcombatiente->
                    getLink('id_resultado_agresion');
                $r .= " ".trim($dresultado->nombre);
            }
            $r .= "\n";
        }
        return $r;
    }


    function integridad_ref_tipoviolencia()
    {
        $q = "SELECT COUNT(id_combatiente) FROM " .
        "p_responsable_agrede_combatiente, combatiente WHERE " .
        "combatiente.id_caso='" . $idcaso . "' AND " .
        "id_p_responsable='" . $idpres . "' AND " .
        "combatiente.id=id_combatiente";
        $nr = $db->getOne($q);
        if ($nr > 0) {
            error_valida(
                'Hay ' . $nr . ' victima(s) ' .
            'combatiente(s) con el presunto responsable que ' .
            ' quiere ' . $accion . '.<br>  ' .
            ' Por favor cambiela(s) antes', $valores
            );
            return false;
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
            array('Belicas' => array('combatiente', 'nombre'))
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
        //echo "OJO PagEtiquetas::mezcla(db, sol, $id1, $id2, $idn, $t)";
        $e1 = isset($sol['combatiente']['nombre'])
            && $sol['combatiente']['nombre'] == 1;
        if (($e1 && $idn != $id1) || (!$e1 && $idn != $id2)) {
            PagVictimaCombatiente::eliminaDep($db, $idn);
            PagBaseMultiple::mezcla(
                $db, $sol, $id1, $id2, $idn,
                array('Belicas' => array('combatiente', 'nombre'))
            );
        }
    }


}

?>
