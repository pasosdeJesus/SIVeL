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
 * @copyright 2004 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

/**
 * Pestaña Víctima Individual de la ficha de captura de caso
 */

require_once 'PagBaseMultiple.php';
require_once 'ResConsulta.php';
require_once 'PagUbicacion.php';

require_once 'DataObjects/Persona.php';
require_once 'DataObjects/Rango_edad.php';
require_once 'DataObjects/Sector_social.php';
require_once 'DataObjects/Vinculo_estado.php';
require_once 'DataObjects/Filiacion.php';
require_once 'DataObjects/Organizacion.php';
require_once 'DataObjects/Profesion.php';
require_once 'DataObjects/Presuntos_responsables.php';
require_once 'DataObjects/Resultado_agresion.php';
require_once 'DataObjects/Relacion_personas.php';


/**
 * Responde a eliminación de una relación
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class EliminaRel extends HTML_QuickForm_Action
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
        assert($_REQUEST['eliminarel'] != null);

        $dvictima=& objeto_tabla('relacion_personas');
        list($dvictima->id_persona1, $dvictima->id_persona2,
            $dvictima->id_tipo
        ) = explode(':', var_escapa($_REQUEST['eliminarel']));
        $dvictima->delete();
        $page->_submitValues = array();
        $page->_defaultValues = array();
        $page->nullVar();
        $page->handle('display');
    }
}

/**
 * Victima Individual.
 * Ver documentación de funciones en clase base.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      PagBaseMultiple
 */
class PagVictimaIndividual extends PagBaseMultiple
{

    var $bpersona;

    var $brelacion_personas;

    /** Víctima */
    var $bvictima;

    /** Antecedentes */
    var $bantecedente_victima;

    var $titulo = 'Victimas Individuales';

    var $tcorto = 'Victima';

    var $pref = "fvi";

    var $clase_modelo = 'victima';

    /*var $bt;  Benchmark_Timer */

    /**
     * Pone en null variables asociadas a tablas de la pestaña.
     *
     * @return null
     */
    function nullVar()
    {
        $this->bvictima = null;
        $this->bpersona = null;
        $this->relacion_personas = null;
        $this->bantecedente_victima = null;
        PagUbicacion::nullVarUbicacion();
    }

    /**
     * Responde a eventos
     *
     * @param string $accion Acción solicitada
     *
     * @return void
     */
    function handle($accion)
    {
        parent::handle($accion);
    }

    /**
     * Retorna una identificación del registro actual.
     *
     * @return string Identifación
     */
    function copiaId()
    {
        return $this->bvictima->_do->id_persona;
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
        //echo "OJO VictimaIndividual elimina($valores)<br>";
        $this->iniVar();
        if (isset($this->bvictima->_do->id_persona)) {
            //echo "OJO VictimaIndividual elimina 2<br>";
            $this->eliminaVic($this->bvictima->_do, true);
            $_SESSION['fvi_total']--;
        }
    }


    /**
     * Inicializa variables.
     *
     * @param integer $id_persona Id  de víctima
     *
     * @return handle Conexión a base de datos
     */
    function iniVar($id_persona = null)
    {
        $dvictima=& objeto_tabla('victima');
        $dpersona=& objeto_tabla('persona');
        $drelacion=& objeto_tabla('relacion_personas');
        $dantecedente_victima =& objeto_tabla('antecedente_victima');

        $db =& $dvictima->getDatabaseConnection();

        $idcaso =& $_SESSION['basicos_id'];
        if (!isset($idcaso) || $idcaso == null) {
            die("Bug: idcaso no debería ser null");
        }

        $idp = array();
        $ndp = array();
        $edp = array();
        $indid = -1;
        //$bt->setMarker("iniVar: antes de extrae víctimas");
        $tot = ResConsulta::extraeVictimas(
            $idcaso, $db, $idp, $ndp,
            $id_persona, $indid, $edp
        );
        //$bt->setMarker("iniVar: después de extrae víctimas");
        $_SESSION['fvi_total'] = $tot;
        if ($indid >= 0) {
            $_SESSION['fvi_pag'] = $indid;
        }
        $dvictima->id_caso= $idcaso;
        if ($_SESSION['fvi_pag'] < 0) {
            $dvictima->id = null;
            $dvictima->id_persona = null;
            $_SESSION['fvi_pag'] = 0;
        } else if ($_SESSION['fvi_pag'] >= $tot) {
            $dvictima->id = null;
            $dvictima->id_persona = null;
            $_SESSION['fvi_pag'] = $tot;
        } else {
            $dvictima->id_persona = $idp[$_SESSION['fvi_pag']];
            $dvictima->find();
            $dvictima->fetch();
            $dpersona->id = $dvictima->id_persona;
            $dpersona->find();
            $dpersona->fetch();
            $drelacion->id_persona1 = $dvictima->id_persona;
            $drelacion->fetch();
        }

        $dantecedente_victima->id_persona = $dvictima->id_persona;
        $dantecedente_victima->id_caso = $dvictima->id_caso;

        $this->bvictima=& DB_DataObject_FormBuilder::create(
            $dvictima,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
                'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bvictima->useMutators = true;
        $this->bpersona=& DB_DataObject_FormBuilder::create(
            $dpersona,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
            'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bpersona->useMutators = true;
        $this->brelacion_personas=& DB_DataObject_FormBuilder::create(
            $drelacion,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
            'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bantecedente_victima =& DB_DataObject_FormBuilder::create(
            $dantecedente_victima,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
            'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );

        //$bt->setMarker("iniVar: fin");
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
    function PagVictimaIndividual($nomForma)
    {
        parent::PagBaseMultiple($nomForma);

        PagUbicacion::nullVarUbicacion();
        $this->addAction('id_departamento', new CamDepartamento());
        $this->addAction('id_municipio', new CamMunicipio());

        $this->addAction('siguiente', new Siguiente());
        $this->addAction('anterior', new Anterior());
        $this->addAction('eliminarel', new EliminaRel());
        $this->addAction('agregarFamiliar', new AgregarFamiliar());

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

        $_SESSION['pagVictimaIndividual_id_persona'] = $vv;

        list($dep, $mun, $cla) = PagUbicacion::creaCamposUbicacion(
            $db, $this, 'victimaIndividual',
            $this->bpersona->_do->id_departamento,
            $this->bpersona->_do->id_municipio
        );

        $gr = array();
        $gr[] =& $dep;
        $gr[] =& $mun;
        $gr[] =& $cla;

        $this->addGroup($gr, 'procedencia', 'Procedencia', '&nbsp;', false);

        $this->bpersona->createSubmit = 0;
        $this->bpersona->useForm($this);
        $this->bpersona->getForm($this);

        if (isset($this->bvictima->_do->id_persona)) {
            $comovic = "";
            $comofam = "";
            enlaces_casos_persona_html(
                $db, $idcaso, $this->bpersona->_do->id, $comovic, $comofam
            );
            if ($comovic != '') {
                $this->addElement(
                    'static', 'tambien', 'Cómo víctima en casos', $comovic
                );
            }
            if ($comofam != '') {
                $this->addElement(
                    'static', 'tambien', 'Cómo familiar en casos', $comofam
                );
            }
        }

        $this->brelacion_personas->createSubmit = 0;
        $this->brelacion_personas->useForm($this);
        $f =& $this->brelacion_personas->getForm($this);

        if (isset($GLOBALS['iglesias_cristianas'])
            && $GLOBALS['iglesias_cristianas']
        ) {
            $this->bvictima->_do->fb_fieldsToRender = array_merge(
                $this->bvictima->_do->fb_fieldsToRender,
                array('id_iglesia')
            );
        }
        $this->bvictima->createSubmit = 0;
        $this->bvictima->useForm($this);
        $this->bvictima->getForm($this);

        $this->bantecedente_victima->createSubmit = 0;
        $this->bantecedente_victima->useForm($this);
        $this->bantecedente_victima->getForm($this);
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
        $vv = isset($this->bvictima->_do->id_persona) ?
            $this->bvictima->_do->id_persona : '';
        $valsca = array();
        if ($vv != '') {
            $e =& $this->getElement('procedencia');
            $dep =& $e->_elements[0];
            $mun =& $e->_elements[1];
            $cla =& $e->_elements[2];
            PagUbicacion::valoresUbicacion(
                $this, $this->bpersona->_do->id_departamento,
                $this->bpersona->_do->id_municipio,
                $this->bpersona->_do->id_clase,
                $dep, $mun, $cla
            );

            $fmes = $this->bpersona->_do->mesnac;
            $fdia = $this->bpersona->_do->dianac;
            $fanio = $this->bpersona->_do->anionac;
            $fsexo = $this->bpersona->_do->sexo;

            $g =& $this->getElement('nacimiento');
            $sanio =& $g->_elements[0];
            $sanio->setValue($fanio);
            $smes =& $g->_elements[1];
            $smes->setValue($fmes);
            $sdia =& $g->_elements[2];
            $sdia->setValue($fdia);
            $ssexo =& $g->_elements[3];
            $ssexo->setValue($fsexo);

            $idcaso =& $_SESSION['basicos_id'];
            $dcaso = objeto_tabla('caso');
            $dcaso->get($idcaso);
            $pf = fecha_a_arr($dcaso->fecha);

            $ht =& $this->getElement('aniocaso');
            $ht->setValue($pf['Y']);
            $ht =& $this->getElement('mescaso');
            $ht->setValue($pf['M']);
            $ht =& $this->getElement('diacaso');
            $ht->setValue($pf['d']);

            $sedad =& $g->_elements[5];
            if ($fanio > 0) {
                $na = edad_de_fechanac(
                    $fanio, $pf['Y'], $fmes,
                    $pf['M'], $fdia, $pf['d']
                );
                $sedad->setValue($na);
            }


            foreach ($this->bvictima->_do->fb_fieldsToRender as $c) {
                $cq = $this->getElement($c);
                if (!PEAR::isError($cq) && isset($this->bvictima->_do->$c)) {
                    if ($c == 'hijos' && $this->bvictima->_do->$c == 'null') {
                        $cq->setValue('');
                    } else {
                        $cq->setValue($this->bvictima->_do->$c);
                    }
                }
            }

            $this->bantecedente_victima->_do->find();
            while ($this->bantecedente_victima->_do->fetch()) {
                $valsca[] = $this->bantecedente_victima->_do->id_antecedente;
            }
        } else if (isset($_SESSION['nuevo_copia_id'])
            && $_SESSION['nuevo_copia_id'] != ''
        ) {
            $id = $_SESSION['nuevo_copia_id'];
            $_SESSION['nuevo_copia_id'] = '';
            unset($_SESSION['nuevo_copia_id ']);

            $dp =& objeto_tabla('persona');
            $dp->id = $id;
            $dp->find(1);
            $cq = $this->getElement('nom');
            $p = $cq->_elements[0];
            $p->setValue($dp->nombres);
            //var_dump($p); die("x");
            $p = $cq->_elements[1];
            $p->setValue($dp->apellidos);

            $g =& $this->getElement('nacimiento');
            $p =& $g->_elements[3];
            $p->setValue($dp->sexo);

            $dv =& objeto_tabla('victima');
            $dv->id_persona = $id;
            $dv->id_caso= $idcaso;
            $dv->find(1);

            $csn = DataObjects_Victima::camposSinInfo();
            foreach ($dv->fb_fieldsToRender as $c) {
                $cq = $this->getElement($c);
                if (isset($dv->$c)) {
                    $cq->setValue($dv->$c);
                } else {
                    $cq->setValue('');
                }
            }
        } else { //Nuevo
            $d =& objeto_tabla('victima');
            $csn = DataObjects_Victima::camposSinInfo();
            foreach ($d->fb_fieldsToRender as $c) {
                $cq = $this->getElement($c);
                if (PEAR::isError($cq)) {
                    continue;
                }
                if (isset($csn[$c])) {
                    $cq->setValue($csn[$c]);
                } else {
                    $cq->setValue('');
                }
            }
        }

        if ($vv == '' && isset($_SESSION['fvi_error_valida'])) {
            unset($_SESSION['fvi_error_valida']);
            $d =& objeto_tabla('victima');
            foreach ($d->fb_fieldsToRender as $c) {
                if (isset($_POST[$c])) {
                    $cq = $this->getElement($c);
                    $cq->setValue(var_escapa($_POST[$c], $db));
                }
            }
            if (isset($_POST['id_antecedente'])) {
                foreach (var_escapa($_POST['id_antecedente'], $db) as $r) {
                    $valsca[] = $r;
                }
            }
        }

        $sca =& $this->getElement('id_antecedente');
        if (!PEAR::isError($sca)) {
            $sca->setValue($valsca);
        }
    }

    /**
    * Elimina datos relacionados con la víctima que se ven en esta
    * pestaña y opcionalmente datos de la víctima y de otras pestañas
    * relacionados con víctima.
    *
    * @param object $dvictima DB_DataObject con datos de víctima
    * @param bool   $elimVic  Si es <b>true</b> elimina datos de víctima también
    *
    * @return void
    */
    function eliminaVic($dvictima, $elimVic = false)
    {
        assert(isset($dvictima) && $dvictima != null);
        assert(isset($dvictima->id_persona));
        assert($dvictima->id_persona != null);
        assert($dvictima->id_persona != '');


        $db =& $dvictima->getDatabaseConnection();
        $idpersona = $dvictima->id_persona;
        $idcaso = $dvictima->id_caso;

        $q = "DELETE FROM antecedente_victima WHERE id_persona='$idpersona' " .
            " AND id_caso='$idcaso'";
        $result = hace_consulta($db, $q);
        if ($elimVic) {
            $q = "DELETE FROM acto WHERE id_persona='$idpersona' " .
                " AND id_caso='$idcaso'";
            hace_consulta($db, $q);

            $q = "DELETE FROM victima WHERE id_persona='$idpersona' " .
                " AND id_caso='$idcaso'";
            hace_consulta($db, $q);
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
        $dvictima =& objeto_tabla('victima');
        $dvictima->id_caso = $idcaso;
        $dvictima->find();
        $cvic = array();
        while ($dvictima->fetch()) {
            $cvic[] = $dvictima->id_persona;
        }
        foreach ($cvic as $idv) {
            $dvictima =& objeto_tabla('victima');
            $dvictima->id_caso = $idcaso;
            $dvictima->id_persona = $idv;
            $dvictima->find();
            $dvictima->fetch();
            PagVictimaIndividual::eliminaVic($dvictima, true);
        }
    }

    /**
     * Procesa valores del formulario enviados por el usuario.
     * Ver documentación completa en clase base.
     *
     * @param handle &$valores Valores ingresados por usuario
     * @param bool   $procFam  True si y solo si debe añadirse familiar
     *
     * @return bool Verdadero si y solo si puede completarlo con éxito
     * @see PagBaseSimple
     */
    function procesa(&$valores, $procFam = false)
    {
        $es_vacio = (!isset($valores['nombres']) || $valores['nombres'] == '');
        $es_vacio = $es_vacio
            && (!isset($valores['apellidos']) || $valores['apellidos'] == '');
        $es_vacio = $es_vacio
            && (!isset($valores['hijos']) || $valores['hijos'] == '');
        $es_vacio = $es_vacio && (!isset($valores['id_profesion'])
            || $valores['id_profesion'] == DataObjects_Profesion::idSinInfo()
        );
        $es_vacio = $es_vacio && (!isset($valores['id_rango_edad'])
            || $valores['id_rango_edad'] == DataObjects_Rango_edad::idSinInfo()
        );
        $es_vacio = $es_vacio && (!isset($valores['id_filiacion'])
            || $valores['id_filiacion'] == DataObjects_Filiacion::idSinInfo()
        );
        $sssin = DataObjects_Sector_social::idSinInfo();
        $es_vacio = $es_vacio && (!isset($valores['id_sector_social'])
            || $valores['id_sector_social'] == $sssin
        );
        $es_vacio = $es_vacio && (!isset($valores['id_organizacion'])
            || $valores['id_organizacion']==
            DataObjects_Organizacion::idSinInfo()
        );
        $es_vacio = $es_vacio && (!isset($valores['id_vinculo_estado'])
            || $valores['id_vinculo_estado']==
            DataObjects_Vinculo_estado::idSinInfo()
        );
        $es_vacio = $es_vacio && (!isset($valores['id_organizacion_armada'])
            || $valores['id_organizacion_armada']==
            DataObjects_Presuntos_responsables::idSinInfo()
        );
        $es_vacio = $es_vacio && (!isset($valores['id_antecedente'])
                || $valores['id_antecedente'] == array()
        );

        if ($es_vacio) {
            return true;
        }

        if (!$this->validate() ) {
            return false;
        }

        $nobus = !isset($_SESSION['forma_modo'])
            || $_SESSION['forma_modo'] != 'busqueda';
        if ($nobus
            && (!isset($valores['nombres']) || trim($valores['nombres'])=='')
        ) {
            error_valida('Falta nombre de víctima', $valores);
            return false;
        }

        if (isset($valores['hijos'])
            && ((int)$valores['hijos'] < 0 || (int)$valores['hijos'] > 100)
        ) {
            error_valida('Cantidad de hijos fuera de rango', $valores);
            return false;
        }

        if (!isset($valores['id_persona']) || $valores['id_persona'] == '') {
            $valores['id_persona'] = null;
            $db = $this->iniVar(null);
        } else {
            $db = $this->iniVar((int)$valores['id_persona']);
        }

        $idcaso =& $_SESSION['basicos_id'];
        $dcaso = objeto_tabla('caso');
        $dcaso->get('id', $idcaso);

        $merr = "";
        if (!DataObjects_Persona::valida($dcaso->fecha, true, $valores, $merr)) {
            error_valida($merr, $valores);
            return false;
        }

        if ($procFam
            && (!isset($valores['fnombres']) || $valores['fnombres'] == '')
            && (!isset($valores['fapellidos']) || $valores['fapellidos'] == '')
        ) {
                error_valida('Faltó nombre y/o apellido de familiar', $valores);
                return false;
        }

        $ret = $this->process(array(&$this->bpersona, 'processForm'), false);
        sin_error_pear($ret);
        $nuevo = $this->bvictima->_do->id_persona == null
            || $this->bvictima->_do->id_persona != $this->bpersona->_do->id;
        $this->bvictima->_do->id_persona = $this->bpersona->_do->id;
        $valores['id_persona'] = $this->bpersona->_do->id;
        if ($nuevo) {
            $this->bvictima->forceQueryType(
                DB_DATAOBJECT_FORMBUILDER_QUERY_FORCEINSERT
            );
        }
        $ret = $this->process(array(&$this->bvictima, 'processForm'), false);
        if (PEAR::isError($ret)) {
            die($ret->getMessage());
        }
        if ($procFam) {
            $nper =& objeto_tabla('persona');
            if (isset($valores['id_persona2'])
                && (int)$valores['id_persona2'] > 0
            ) {
                $nper->get((int)$valores['id_persona2']);
                $nper->nombres = var_escapa($valores['fnombres'], $db);
                $nper->apellidos = var_escapa($valores['fapellidos'], $db);
                $nper->update();
            } else {
                $nper->nombres = var_escapa($valores['fnombres'], $db);
                $nper->apellidos = var_escapa($valores['fapellidos'], $db);
                $nper->sexo = 'S';
                $nper->insert();
            }
            $this->brelacion_personas->_do->id_persona1
                = $this->bpersona->_do->id;
            $this->brelacion_personas->_do->id_persona2
                = $nper->id;
            $this->brelacion_personas->_do->id_tipo
                = var_escapa($valores['ftipo'], $db, 5);
            $this->brelacion_personas->_do->observaciones
                = var_escapa($valores['fobservaciones'], $db);
            $this->brelacion_personas->_do->insert();
            $procFam = false;
        }


        if (isset($this->bpersona->_do->id)) {
            $idpersona = $this->bpersona->_do->id;
            $idcaso = $_SESSION['basicos_id'];
            if ($nuevo) {
                $_SESSION['fvi_total']++;
            } else {
                $this->eliminaVic($this->bvictima->_do, false);
            }
            if (isset($valores['id_antecedente'])) {
                foreach (var_escapa($valores['id_antecedente']) as $k => $v) {
                    $this->bantecedente_victima->_do->id_persona = $idpersona;
                    $this->bantecedente_victima->_do->id_caso = $idcaso;
                    $this->bantecedente_victima->_do->id_antecedente = $v;
                    $this->bantecedente_victima->_do->insert();
                }
            }
        }
        //$bt->setMarker("procesa: antes de funcionario_caso");
        funcionario_caso($_SESSION['basicos_id']);
        //$bt->_Benchmark_Timer();
        return  $ret;
    }

    /**
     * Prepara consulta SQL para buscar datos de este formulario.
     * Ver documentación completa en clase base.
     *
     * @param string &$w       Consulta que se construye
     * @param string &$t       Tablas
     * @param object &$db      Conexión a base de datos
     * @param object $idcaso   Identificación del caso
     * @param string &$subcons Subconsulta
     *
     * @return void
     * @see PagBaseSimple
     */
    function datosBusqueda(&$w, &$t, &$db, $idcaso, &$subcons)
    {
        $tab = parse_ini_file(
            $_SESSION['dirsitio'] . "/DataObjects/" .
            $GLOBALS['dbnombre'] . ".ini", true
        );
        $duc =& objeto_tabla('victima');
        sin_error_pear($duc);
        $duc->id_caso = (int)$idcaso;
        if (@$duc->find() == 0) {
            return;
        }
        $hayper = false;
        $w3="";
        while ($duc->fetch()) {
            $dper = $duc->getLink('id_persona');
            $w4 = prepara_consulta_con_tabla(
                $dper, 'persona', '', '', false,
                array(), '',
                array(), 'id', array(), $tab
            );
            $w2 = prepara_consulta_con_tabla(
                $duc, 'victima', '', '', false,
                array('antecedente_victima'), 'id_persona',
                array('edad', 'hijos'), 'id_persona', array(), $tab
            );

            if ($w4 != "") {
                $hayper = true;
                if ($w2 != "") {
                    $w2 = $w4 . ' AND ' . $w2;
                } else {
                    $w2 = $w4;
                }
            }
            //echo "<hr>".$w2;
            if ($w2!="") {
                $w3 = $w3=="" ? "($w2)" : "$w3 OR ($w2)";
            }
        }
        agrega_tabla($t, 'victima');
        consulta_and_sinap($w, "victima.id_caso", "caso.id", "=", "AND");
        if ($hayper) {
            agrega_tabla($t, 'persona');
            consulta_and_sinap(
                $w, "persona.id", "victima.id_persona", "=", "AND"
            );
        }

        if ($w3!="") {
            $w = $w == "" ? "($w3)" : "$w AND ($w3)";
        }

    }

    /**
     * Llamada cuando se inicia captura de ficha
     *
     * @return void
     * @see PagBaseSimple
     */
    static function iniCaptura()
    {
        if (isset($_REQUEST['eliminarel'])) {
            $_REQUEST['_qf_victimaIndividual_eliminarel'] = true;
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
        parent::compara(
            $db, $r, $id1, $id2,
            array('Victimas Individuales' =>
            array('victima', 'id_persona'))
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
        parent::mezcla(
            $db, $sol, $id1, $id2, $idn,
            array('Victimas Individuales'
            => array('victima', 'id_persona'))
        );
    }

}

?>
