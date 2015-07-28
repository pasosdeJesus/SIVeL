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
require_once 'DataObjects/Rangoedad.php';
require_once 'DataObjects/Sectorsocial.php';
require_once 'DataObjects/Vinculoestado.php';
require_once 'DataObjects/Filiacion.php';
require_once 'DataObjects/Organizacion.php';
require_once 'DataObjects/Profesion.php';
require_once 'DataObjects/Presponsable.php';
require_once 'DataObjects/Resagresion.php';
require_once 'DataObjects/Persona_trelacion.php';


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

        $dvictima=& objeto_tabla('persona_trelacion');
        list($dvictima->persona1, $dvictima->persona2,
            $dvictima->id_trelacion
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

    var $bpersona_trelacion;

    /** Víctima */
    var $bvictima;

    var $titulo = 'Víctimas Individuales';

    /** Antecedentes */
    var $bantecedente_victima;

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
        $this->persona_trelacion = null;
        $this->bantecedente_victima = null;
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
     * @param array $apar Arreglo de parametros. Consta de
     *  0=>$id_persona Id  de víctima
     *
     * @return handle Conexión a base de datos
     */
    function iniVar($apar = null)
    {
        $id_persona = null;
        if (isset($apar) && count($apar) == 1) {
            $id_persoea = $apar[0];
        }
        $dvictima=& objeto_tabla('victima');
        $dpersona=& objeto_tabla('persona');
        $drelacion=& objeto_tabla('persona_trelacion');
        $dantecedente_victima =& objeto_tabla('antecedente_victima');

        $db =& $dvictima->getDatabaseConnection();

        $idcaso =& $_SESSION['basicos_id'];
        if (!isset($idcaso) || $idcaso == null) {
            die(_("Bug: idcaso no debería ser null"));
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
            $drelacion->persona1 = $dvictima->id_persona;
            $drelacion->fetch();
        }

        $dantecedente_victima->id_persona = $dvictima->id_persona;
        $dantecedente_victima->id_caso = $dvictima->id_caso;

        $this->bvictima =& DB_DataObject_FormBuilder::create(
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
        $this->bpersona->fb_useMutators = true;
        $this->bpersona_trelacion=& DB_DataObject_FormBuilder::create(
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
        $this->titulo = _('Víctimas Individuales');
        $this->tcorto = _('Víctima');
        if (isset($GLOBALS['etiqueta']['Victimas Individuales'])) {
            $this->titulo = $GLOBALS['etiqueta']['Victimas Individuales'];
            $this->tcorto = $GLOBALS['etiqueta']['Victimas Individuales'];
        }

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

        $this->bpersona->createSubmit = 0;
        $this->bpersona->useForm($this);
        $this->bpersona->getForm($this);

        list($dep, $mun, $cla) = PagUbicacion::creaCampos(
            $this, 'id_departamento', 'id_municipio', 'id_clase'
        );
        $gr = array();
        $gr[] =& $dep;
        $gr[] =& $mun;
        $gr[] =& $cla;

        $this->addGroup(
            $gr, 'procedencia', _('Lugar de Nacimiento'),
            '&nbsp;', false
        );
        PagUbicacion::modCampos(
            $db, $this, 'id_departamento', 'id_municipio', 'id_clase',
            $this->bpersona->_do->id_departamento,
            $this->bpersona->_do->id_municipio,
            $this->bpersona->_do->id_clase
        );

        if (isset($this->bvictima->_do->id_persona)) {
            $comovic = "";
            $comofam = "";
            enlaces_casos_persona_html(
                $db, $idcaso, $this->bpersona->_do->id, $comovic, $comofam
            );
            if ($comovic != '') {
                $this->addElement(
                    'static', 'tambien', _('Cómo víctima en casos'), $comovic
                );
            }
            if ($comofam != '') {
                $this->addElement(
                    'static', 'tambien', _('Cómo familiar en casos'), $comofam
                );
            }
        }

        if (!isset($GLOBALS['familiaresvictima'])
            || $GLOBALS['familiaresvictima']
        ) {
            $this->bpersona_trelacion->createSubmit = 0;
            $this->bpersona_trelacion->useForm($this);
            $f =& $this->bpersona_trelacion->getForm($this);
        }

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
        $idcaso =& $_SESSION['basicos_id'];
        $dcaso = objeto_tabla('caso');

        if ($vv != '') {
            $dcaso->get($idcaso);

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
        if (isset($GLOBALS['no_permite_editar']) && $GLOBALS['no_permite_editar']) {
            $htmljs = new HTML_Javascript();
            echo $htmljs->startScript();
            echo $htmljs->alert( 'Edición deshabilitada.');
            echo $htmljs->endScript();
            return true;
        }
        $es_vacio = (!isset($valores['nombres']) || $valores['nombres'] == '');
        $es_vacio = $es_vacio
            && (!isset($valores['apellidos']) || $valores['apellidos'] == '');
        $es_vacio = $es_vacio
            && (!isset($valores['hijos']) || $valores['hijos'] == '');
        $es_vacio = $es_vacio && (!isset($valores['id_profesion'])
            || $valores['id_profesion'] == DataObjects_Profesion::idSinInfo()
        );
        $es_vacio = $es_vacio && (!isset($valores['id_rangoedad'])
            || $valores['id_rangoedad'] == DataObjects_Rangoedad::idSinInfo()
        );
        $es_vacio = $es_vacio && (!isset($valores['id_filiacion'])
            || $valores['id_filiacion'] == DataObjects_Filiacion::idSinInfo()
        );
        $sssin = DataObjects_Sectorsocial::idSinInfo();
        $es_vacio = $es_vacio && (!isset($valores['id_sectorsocial'])
            || $valores['id_sectorsocial'] == $sssin
        );
        $es_vacio = $es_vacio && (!isset($valores['id_organizacion'])
            || $valores['id_organizacion']==
            DataObjects_Organizacion::idSinInfo()
        );
        $es_vacio = $es_vacio && (!isset($valores['id_vinculoestado'])
            || $valores['id_vinculoestado']==
            DataObjects_Vinculoestado::idSinInfo()
        );
        $es_vacio = $es_vacio && (!isset($valores['organizacionarmada'])
            || $valores['organizacionarmada']==
            DataObjects_Presponsable::idSinInfo()
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
            error_valida(_('Falta nombre de víctima'), $valores);
            return false;
        }

        if (isset($valores['hijos'])
            && ((int)$valores['hijos'] < 0 || (int)$valores['hijos'] > 100)
        ) {
            error_valida(_('Cantidad de hijos fuera de rango'), $valores);
            return false;
        }
        if (in_array(31, $_SESSION['opciones'])
            && !in_array(21, $_SESSION['opciones'])
        ) {
            return true;
        }

        if (!isset($valores['id']) || $valores['id'] == '') {
            $valores['id'] = null;
            $db = $this->iniVar(null);
        } else {
            $db = $this->iniVar(array((int)$valores['id']));
        }

        $idcaso =& $_SESSION['basicos_id'];
        $dcaso = objeto_tabla('caso');
        $dcaso->get('id', $idcaso);

        $merr = "";
        if (!DataObjects_Persona::valida($dcaso->fecha, true, $valores, $merr)) {
            error_valida($merr, $valores);
            return false;
        }

        if (isset($valores['numerodocumento'])
            && (int)$valores['numerodocumento'] > 0
        ) {
            $q = "SELECT id, nombres, apellidos, id_caso
                FROM victima, persona
                WHERE persona.id = victima.id_persona
                AND numerodocumento = '"
                . (int)$valores['numerodocumento'] . "'";
            $r = hace_consulta($db, $q); $row = array();
            if ($r->fetchInto($row)) {
                if (!isset($valores['id'])
                    || $row[0] != (int)$valores['id']
                ) {
                    error_valida(
                        _(
                            'Numero de documento repetido en víctima '
                            . $row[1] . " " . $row[2] . " de caso " . $row[3]
                        ), $valores
                    );
                    return false;
                }
            }
        }
        if ($procFam
            && (!isset($valores['fnombres']) || $valores['fnombres'] == '')
            && (!isset($valores['fapellidos']) || $valores['fapellidos'] == '')
        ) {
                error_valida(_('Faltó nombre y/o apellido de familiar'), $valores);
                return false;
        }

        if (isset($GLOBALS['estilo_nombres'])
            && $GLOBALS['estilo_nombres'] == 'MAYUSCULAS'
        ) {
            $valores['nombres'] = a_mayusculas(trim($valores['nombres']));
            $valores['apellidos'] = a_mayusculas(trim($valores['apellidos']));
        } else if (isset($GLOBALS['estilo_nombres'])
            && $GLOBALS['estilo_nombres'] == 'a_minusculas'
        ) {
            $valores['nombres'] = prim_may(trim($valores['nombres']));
            $valores['apellidos'] = prim_may(trim($valores['apellidos']));
        } else {
            $valores['nombres'] = trim($valores['nombres']);
            $valores['apellidos'] = trim($valores['apellidos']);
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
            if (isset($valores['persona2'])
                && (int)$valores['persona2'] > 0
            ) {
                $nper->get((int)$valores['persona2']);
                $nper->nombres = var_escapa($valores['fnombres'], $db);
                $nper->apellidos = var_escapa($valores['fapellidos'], $db);
                $nper->update();
            } else {
                $nper->nombres = var_escapa($valores['fnombres'], $db);
                $nper->apellidos = var_escapa($valores['fapellidos'], $db);
                $nper->sexo = 'S';
                $nper->insert();
            }
            $this->bpersona_trelacion->_do->persona1
                = $this->bpersona->_do->id;
            $this->bpersona_trelacion->_do->persona2
                = $nper->id;
            $this->bpersona_trelacion->_do->id_trelacion
                = var_escapa($valores['ftipo'], $db, 5);
            $this->bpersona_trelacion->_do->observaciones
                = var_escapa($valores['fobservaciones'], $db);
            $this->bpersona_trelacion->_do->insert();
            $tr = $this->bpersona_trelacion->_do->getLink('id_trelacion');
            if ($tr->inverso != null) {
                $npr = objeto_tabla('persona_trelacion');
                $npr->persona1 = $this->bpersona_trelacion->_do->persona2;
                $npr->persona2 = $this->bpersona_trelacion->_do->persona1;
                $npr->id_trelacion = $tr->inverso;
                $npr->insert();
            }
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

        //$bt->setMarker("procesa: antes de caso_usuario");
        caso_usuario($_SESSION['basicos_id']);
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
            if (is_array($subcons)) {
                foreach ($subcons as $subc) {
                    $ds =& objeto_tabla($subc['tabla']);
                    sin_error_pear($ds);
                    $ds->id_caso = (int)$idcaso;
                    $ds->id_persona = $duc->id_persona;
                    if (@$ds->find(1) == 1) {
                        $w5 = prepara_consulta_con_tabla(
                            $ds, $subc['tabla'], '', '', false,
                            array(), 'id_persona',
                            array(), 'id_persona', array(), $tab
                        );
                        if ($w5 != "") {
                            agrega_tabla($t, $subc['tabla']);
                            consulta_and_sinap(
                                $w, "victima.id_persona",
                                $subc['tabla'] . ".id_persona", "=", "AND"
                            );
                            consulta_and_sinap(
                                $w, "victima.id_caso",
                                $subc['tabla'] . ".id_caso", "=", "AND"
                            );
                            $w5 .= ' AND victima.id_caso = '
                                . $subc['tabla'] . '.id_caso AND '
                                . ' victima.id_persona = '
                                . $subc['tabla'] . '.id_persona ';
                            $hayper = true;
                            if ($w2 != "") {
                                $w2 = $w5 . ' AND ' . $w2;
                            } else {
                                $w2 = $w5;
                            }
                        }
                    }
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

}

?>
