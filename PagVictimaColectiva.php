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
 * Pestaña Víctima Colectiva de la ficha de captura de caso
 */

require_once 'PagBaseMultiple.php';
require_once 'ResConsulta.php';
require_once 'aut.php';
require_once $_SESSION['dirsitio'] . "/conf.php";

/**
 * Víctima Colectiva.
 * Ver documentación de funciones en clase base.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      PagBaseMultiple
 */
class PagVictimaColectiva extends PagBaseMultiple
{

    /** Grupo de personas */
    var $bgrupoper;

    /** Víctima colectiva (independiente de caso) */
    var $bvictimacolectiva ;

    /** Antecedentes */
    var $bantecedente_comunidad;

    /** Rangos de edad */
    var $bcomunidad_rangoedad;

    /** Sectores sociales */
    var $bcomunidad_sectorsocial;

    /** Vínculos con estado */
    var $bcomunidad_vinculoestado;

    /** Filiaciones */
    var $bcomunidad_filiacion;

    /** Profesiones */
    var $bcomunidad_profesion;

    /** Organizaciones */
    var $bcomunidad_organizacion;

    /** Prefijo para variables de sesión */
    var $pref = "fvc";

    /** Nueva Copia */
    var $nuevaCopia = false;

    /** Clase modelo para pestaña */
    var $clase_modelo = 'victimacolectiva';

    var $titulo = 'Víctimas Colectivas';

    var $tcorto = 'Víc. Colectivas';

    /**
     * Pone en null variables asociadas a tablas de la pestaña.
     *
     * @return null
     */
    function nullVar()
    {
        $this->bgrupoper= null;
        $this->bvictimacolectiva = null;
        $this->bantecedente_comunidad = null;
        $this->bcomunidad_rangoedad = null;
        $this->bcomunidad_sectorsocial = null;
        $this->bcomunidad_vinculoestado = null;
        $this->bcomunidad_filiacion = null;
        $this->bcomunidad_profesion = null;
        $this->bcomunidad_organizacion = null;
    }

    /**
     * Retorna una identificación del registro actual.
     *
     * @return string Identifación
     */
    function copiaId()
    {
        return $this->bvictimacolectiva->_do->id_grupoper;
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
        if (isset($this->bvictimacolectiva->_do->id_grupoper)) {
            $this->eliminaVic($this->bvictimacolectiva->_do, true);
            $_SESSION['fvc_total']--;
        }
    }

    var $tablasrel = array(
        'grupoper',
        'victimacolectiva',
        'antecedente_comunidad',
        'comunidad_rangoedad',
        'comunidad_sectorsocial',
        'comunidad_vinculoestado',
        'comunidad_filiacion',
        'comunidad_profesion',
        'comunidad_organizacion',
    );



    /**
     * Inicializa variables.
     *
     * @param array $apar Arreglo de parametros. Consta de
     *   0=>$id_grupoper Id  de grupo de personas
     *
     * @return handle  Conexión a base de datos
     */
    function iniVar($apar = null)
    {
        $id_grupoper = null;
        if (isset($apar) && count($apar) == 1) {
            $id_grupoper = $apar[0];
        }
        $dgrupoper =& objeto_tabla('grupoper');
        $dvictimacolectiva =& objeto_tabla('victimacolectiva');
        $dantecedente_comunidad =&
            objeto_tabla('antecedente_comunidad');
        $dcomunidad_rangoedad =&
            objeto_tabla('comunidad_rangoedad');
        $dcomunidad_sectorsocial =&
            objeto_tabla('comunidad_sectorsocial');
        $dcomunidad_vinculoestado =&
            objeto_tabla('comunidad_vinculoestado');
        $dcomunidad_filiacion =& objeto_tabla('comunidad_filiacion');
        $dcomunidad_profesion =& objeto_tabla('comunidad_profesion');
        $dcomunidad_organizacion =&
            objeto_tabla('comunidad_organizacion');
        $db =& $dvictimacolectiva->getDatabaseConnection();

        $idcaso =& $_SESSION['basicos_id'];
        if (!isset($idcaso) || $idcaso == null) {
            die(_("Bug: idcaso no debería ser null"));
        }

        $idp = $ndp = $cdp = array();
        $indid = -1;
        $tot = 0;
        ResConsulta::extraeColectivas(
            $idcaso, $db, $idp, $ndp, $cdp,
            $id_grupoper, $indid, $tot
        );
        $_SESSION['fvc_total'] = $tot;
        if ($indid >= 0) {
            $_SESSION['fvc_pag'] = $indid;
        }
        $dvictimacolectiva->id_caso= $idcaso;
        if ($_SESSION['fvc_pag'] < 0 || $_SESSION['fvc_pag'] >= $tot) {
            $dvictimacolectiva->id_grupoper = null;
        } else {
            $dvictimacolectiva->id_grupoper = $idp[$_SESSION['fvc_pag']];
            $dvictimacolectiva->find();
            $dvictimacolectiva->fetch();
            $dgrupoper->id = $idp[$_SESSION['fvc_pag']];
            $dgrupoper->find();
            $dgrupoper->fetch();
        }
        $idgrupoper = $dvictimacolectiva->id_grupoper;
        $dantecedente_comunidad->id_grupoper = $idgrupoper;
        $dantecedente_comunidad->id_caso= $idcaso;
        $dcomunidad_rangoedad->id_grupoper= $idgrupoper;
        $dcomunidad_rangoedad->id_caso= $idcaso;
        $dcomunidad_sectorsocial->id_grupoper = $idgrupoper;
        $dcomunidad_sectorsocial->id_caso = $idcaso;
        $dcomunidad_vinculoestado->id_grupoper = $idgrupoper;
        $dcomunidad_vinculoestado->id_caso = $idcaso;
        $dcomunidad_filiacion->id_grupoper = $idgrupoper;
        $dcomunidad_filiacion->id_caso= $idcaso;
        $dcomunidad_profesion->id_grupoper= $idgrupoper;
        $dcomunidad_profesion->id_caso = $idcaso;
        $dcomunidad_organizacion->id_grupoper = $idgrupoper;
        $dcomunidad_organizacion->id_caso = $idcaso;

        $this->bgrupoper =& DB_DataObject_FormBuilder::create(
            $dgrupoper,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
            'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bvictimacolectiva  =& DB_DataObject_FormBuilder::create(
            $dvictimacolectiva,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
            'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bvictimacolectiva->useMutators = true;
        $this->bantecedente_comunidad =& DB_DataObject_FormBuilder::create(
            $dantecedente_comunidad,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
            'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bcomunidad_rangoedad =& DB_DataObject_FormBuilder::create(
            $dcomunidad_rangoedad,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
            'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bcomunidad_sectorsocial =& DB_DataObject_FormBuilder::create(
            $dcomunidad_sectorsocial,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
            'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bcomunidad_vinculoestado =& DB_DataObject_FormBuilder::create(
            $dcomunidad_vinculoestado,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
            'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bcomunidad_filiacion =& DB_DataObject_FormBuilder::create(
            $dcomunidad_filiacion,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
            'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bcomunidad_profesion =& DB_DataObject_FormBuilder::create(
            $dcomunidad_profesion,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
            'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bcomunidad_organizacion =& DB_DataObject_FormBuilder::create(
            $dcomunidad_organizacion,
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
     *
     * @return void
     * @see    PagBaseMultiple
     */
    function PagVictimaColectiva($nomForma)
    {
        $this->PagBaseMultiple($nomForma);
        $this->titulo = _('Víctimas Colectivas');
        $this->tcorto = _('Vic. colectiva');
        if (isset($GLOBALS['etiqueta']['Victimas Colectivas'])) {
            $this->titulo = $GLOBALS['etiqueta']['Victimas Colectivas'];
            $this->tcorto = $GLOBALS['etiqueta']['Victimas Colectivas'];
        }
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
     * @see    PagBaseSimple
     */
    function formularioAgrega(&$db, $idcaso)
    {
        $e = $this->getElement(null);
        #$vv= isset($this->bvictimacolectiva->_do->id_grupoper) ?
        #    $this->bvictimacolectiva->_do->id_grupoper : '';
        //$this->addElement('hidden', 'id_grupoper', $vv);

        $this->bgrupoper->createSubmit = 0;
        $this->bgrupoper->useForm($this);
        $this->bgrupoper->getForm();

        $this->bvictimacolectiva->createSubmit = 0;
        $this->bvictimacolectiva->useForm($this);
        $this->bvictimacolectiva->getForm();

        $this->bantecedente_comunidad->createSubmit = 0;
        $this->bantecedente_comunidad->useForm($this);
        $this->bantecedente_comunidad->getForm();

        $this->bcomunidad_rangoedad->createSubmit = 0;
        $this->bcomunidad_rangoedad->useForm($this);
        $this->bcomunidad_rangoedad->getForm();

        $this->bcomunidad_sectorsocial->createSubmit = 0;
        $this->bcomunidad_sectorsocial->useForm($this);
        $this->bcomunidad_sectorsocial->getForm();

        $this->bcomunidad_vinculoestado->createSubmit = 0;
        $this->bcomunidad_vinculoestado->useForm($this);
        $this->bcomunidad_vinculoestado->getForm();

        $this->bcomunidad_filiacion->createSubmit = 0;
        $this->bcomunidad_filiacion->useForm($this);
        $this->bcomunidad_filiacion->getForm();

        $e =& $this->getElement('id_filiacion');
        $this->bcomunidad_profesion->createSubmit = 0;
        $this->bcomunidad_profesion->useForm($this);
        $this->bcomunidad_profesion->getForm();

        $this->bcomunidad_organizacion->createSubmit = 0;
        $this->bcomunidad_organizacion->useForm($this);
        $this->bcomunidad_organizacion->getForm();

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
     * @see    PagBaseSimple
     */
    function formularioValores(&$db, $idcaso)
    {
        $campos = array_merge(
            array('anotaciones'),
            $this->bvictimacolectiva->_do->fb_fieldsToRender,
            array(
            'id_antecedente', 'id_rangoedad',
            'id_sectorsocial', 'id_vinculoestado', 'id_filiacion',
            'id_profesion', 'id_organizacion'
            )
        );

        $vv= isset($this->bvictimacolectiva->_do->id_grupoper) ?
            $this->bvictimacolectiva->_do->id_grupoper : '';
        $v = array();
        if (isset($_SESSION['recuperaErrorValida'])) {
            $v = $_SESSION['recuperaErrorValida'];
        } else if (isset($_SESSION['nuevo_copia_id'])
            && $_SESSION['nuevo_copia_id'] != ''
        ) {
            $id = $_SESSION['nuevo_copia_id'];
            $_SESSION['nuevo_copia_id'] = '';
            unset($_SESSION['nuevo_copia_id']);

            $dg =& objeto_tabla('grupoper');
            $dg->id = $id;
            $dg->find(1);
            $cq = $this->getElement('gid');
            $p = $cq->_elements[1];
            $p->setValue($dg->nombre);

            $d =& objeto_tabla('victimacolectiva');
            $d->id_grupoper = $id;
            $d->id_caso = $_SESSION['basicos_id'];
            $d->find();
            $d->fetch(1);
            foreach ($d->fb_fieldsToRender as $c) {
                $v[$c] = $d->$c;
            }
            $r = array('antecedente_comunidad' => 'id_antecedente',
                'comunidad_rangoedad' =>  'id_rangoedad',
                'comunidad_sectorsocial' =>  'id_sectorsocial',
                'comunidad_vinculoestado' =>  'id_vinculoestado',
                'comunidad_filiacion' =>  'id_filiacion',
                'comunidad_profesion' =>  'id_profesion',
                'comunidad_organizacion' =>  'id_organizacion'
            );
            foreach ($r as $nt => $ca) {
                $valel = array();
                $d =& objeto_tabla($nt);
                $d->id_grupoper = $id;
                $d->find();
                while ($d->fetch()) {
                    $valel[] = $d->$ca;
                }
                //   $el->setValue($valel);
                $v[$ca] = $valel;
            }

            $this->removeElement('id_grupoper');
        } else if ($vv != '') {
            $valsca = array();
            $valscre = array();
            $valscss = array();
            $valscve = array();
            $valscfc = array();
            $valscpro = array();
            $valscoc = array();

            $this->bantecedente_comunidad->_do->find();
            while ($this->bantecedente_comunidad->_do->fetch()) {
                $valsca[] = $this->bantecedente_comunidad->_do->id_antecedente;
            }

            $this->bcomunidad_rangoedad->_do->find();
            while ($this->bcomunidad_rangoedad->_do->fetch()) {
                $valscre[] = $this->bcomunidad_rangoedad->_do->id_rangoedad;
            }

            $this->bcomunidad_sectorsocial->_do->find();
            while ($this->bcomunidad_sectorsocial->_do->fetch()) {
                $valscss[] = $this->bcomunidad_sectorsocial->_do->id_sectorsocial;
            }

            $this->bcomunidad_vinculoestado->_do->find();
            while ($this->bcomunidad_vinculoestado->_do->fetch()) {
                $valscve[]
                    = $this->bcomunidad_vinculoestado->_do->id_vinculoestado;
            }

            $this->bcomunidad_filiacion->_do->find();
            while ($this->bcomunidad_filiacion->_do->fetch()) {
                $valscfc[] = $this->bcomunidad_filiacion->_do->id_filiacion;
            }

            $this->bcomunidad_profesion->_do->find();
            while ($this->bcomunidad_profesion->_do->fetch()) {
                $valscpro[] = $this->bcomunidad_profesion->_do->id_profesion;
            }

            $this->bcomunidad_organizacion->_do->find();
            while ($this->bcomunidad_organizacion->_do->fetch()) {
                $valscoc[] = $this->bcomunidad_organizacion->_do->id_organizacion;
            }
            $v['id_antecedente'] = $valsca;
            $v['id_rangoedad'] = $valscre;
            $v['id_sectorsocial'] = $valscss;
            $v['id_vinculoestado'] = $valscve;
            $v['id_filiacion'] = $valscfc;
            $v['id_profesion'] = $valscpro;
            $v['id_organizacion'] = $valscoc;
            $v['organizacionarmada']
                = $this->bvictimacolectiva->_do->organizacionarmada;
            $v['personasaprox']
                = isset($this->bvictimacolectiva->_do->personasaprox)
                && $this->bvictimacolectiva->_do->personasaprox != 'null' ?
                $this->bvictimacolectiva->_do->personasaprox :
                '';
        }
        establece_valores_form($this, $campos, $v);

        if (isset($_SESSION['recuperaErrorValida'])) {
            unset($_SESSION['recuperaErrorValida']);
        }

    }


    /**
     * Elimina una víctima
     *
     * @param object $dvcolectiva Objeto con vic. col
     * @param bool   $este        Verdadero sii se elimina objeto dvcolectiva
     *
     * @return void
     */
    function eliminaVic($dvcolectiva, $este)
    {
        if ($dvcolectiva->id_grupoper != null) {
            $db =& $dvcolectiva->getDatabaseConnection();
            $idgrupoper = $dvcolectiva->id_grupoper;
            $idcaso = $dvcolectiva->id_caso;
            hace_consulta(
                $db, "DELETE FROM antecedente_comunidad " .
                " WHERE id_caso=$idcaso " .
                " AND id_grupoper='$idgrupoper'"
            );
            hace_consulta(
                $db, "DELETE FROM comunidad_rangoedad " .
                " WHERE id_caso=$idcaso " .
                " AND id_grupoper='$idgrupoper'"
            );
            hace_consulta(
                $db, "DELETE FROM comunidad_sectorsocial " .
                " WHERE id_caso=$idcaso " .
                " AND id_grupoper='$idgrupoper'"
            );
            hace_consulta(
                $db, "DELETE " .
                " FROM comunidad_vinculoestado " .
                " WHERE id_caso=$idcaso " .
                " AND id_grupoper='$idgrupoper'"
            );
            hace_consulta(
                $db, "DELETE FROM comunidad_filiacion " .
                " WHERE id_caso=$idcaso " .
                " AND id_grupoper='$idgrupoper'"
            );
            hace_consulta(
                $db, "DELETE FROM comunidad_profesion " .
                " WHERE id_caso=$idcaso " .
                " AND id_grupoper='$idgrupoper'"
            );
            hace_consulta(
                $db, "DELETE FROM comunidad_organizacion " .
                " WHERE id_caso=$idcaso " .
                " AND id_grupoper='$idgrupoper'"
            );
            if ($este) {
                $dvcolectiva->delete();
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
     * @see    PagBaseSimple
     */
    static function eliminaDep(&$db, $idcaso)
    {
        assert($db != null);
        assert(isset($idcaso));
        $dvcolectiva =& objeto_tabla('victimacolectiva');
        $dvcolectiva->id_caso = $idcaso;
        $dvcolectiva->find();
        $cp = array();
        while ($dvcolectiva->fetch()) {
            $cp[] = $dvcolectiva->id_grupoper;
        }
        foreach ($cp as $idg) {
            $dvcolectiva =& objeto_tabla('victimacolectiva');
            $dvcolectiva->id_caso = $idcaso;
            $dvcolectiva->id_grupoper= $idg;
            $dvcolectiva->find();
            $dvcolectiva->fetch();
            PagVictimaColectiva::eliminaVic($dvcolectiva, true);
        }
    }


    /**
     * Procesa valores del formulario enviados por el usuario.
     * Ver documentación completa en clase base.
     *
     * @param handle &$valores Valores ingresados por usuario
     *
     * @return bool   Verdadero si y solo si puede completarlo con éxito
     * @see    PagBaseSimple
     */
    function procesa(&$valores)
    {
        if (isset($GLOBALS['no_permite_editar']) && $GLOBALS['no_permite_editar']) {
            $htmljs = new HTML_Javascript();
            echo $htmljs->startScript();
            echo $htmljs->alert( 'Edición deshabilitada.');
            echo $htmljs->endScript();
            return true;
        }
        $es_vacio = (!isset($valores['nombre']) || $valores['nombre'] == '')
            && (!isset($valores['anotacion']) || $valores['anotacion'] == '')
            && (!isset($valores['personasaprox'])
            || $valores['personasaprox'] == ''
            )
            && (!isset($valores['id_antecedente'])
            || $valores['id_antecedente'] == array()
            )
            && (!isset($valores['id_rangoedad'])
            || $valores['id_rangoedad'] == array()
            )
            && (!isset($valores['id_sectorsocial'])
            || $valores['id_sectorsocial'] == array()
            )
        ;

        if ($es_vacio) {
            return true;
        }
        print_r($valores);
        if (!$this->validate() ) {
            return false;
        }

        verifica_sin_CSRF($valores);

        $nobusca = !isset($_SESSION['forma_modo'])
            || $_SESSION['forma_modo'] != 'busqueda';
        if ($nobusca
            && (!isset($valores['nombre']) || trim($valores['nombre'])=='')
        ) {
            error_valida('Falta nombre de víctima colectiva', $valores);
            return false;
        }
        if (in_array(31, $_SESSION['opciones'])
            && !in_array(21, $_SESSION['opciones'])
        ) {
            return true;
        }


        if (!isset($valores['id_grupoper']) || $valores['id_grupoper'] == '') {
            $valores['id_grupoper'] = null;
            $db = $this->iniVar();
        } else {
            $db = $this->iniVar(array((int)$valores['id_grupoper']));
        }


        $idcaso = $_SESSION['basicos_id'];
        $ret = $this->process(
            array(&$this->bgrupoper, 'processForm'),
            false
        );
        if (PEAR::isError($ret)) {
            die($ret->getMessage());
        }
        $idgrupoper = $this->bgrupoper->_do->id;
        $nuevo = $this->bvictimacolectiva->_do->id_grupoper == null
            || $this->bvictimacolectiva->_do->id_grupoper != $idgrupoper;

        $this->bvictimacolectiva->_do->id_grupoper = $idgrupoper;
        $valores['id_grupoper'] = $idgrupoper;
        if ($nuevo) {
            $this->bvictimacolectiva->forceQueryType(
                DB_DATAOBJECT_FORMBUILDER_QUERY_FORCEINSERT
            );
        }
        $ret = $this->process(
            array(&$this->bvictimacolectiva,
            'processForm'
            ), false
        );
        if (PEAR::isError($ret)) {
            die($ret->getMessage());
        }

        if ($nuevo) {
            $_SESSION['fvc_total']++;
        } else {
            $this->eliminaVic($this->bvictimacolectiva->_do, false);
        }

        foreach (var_escapa_arreglo($valores['id_antecedente']) as $k => $v) {
            $this->bantecedente_comunidad->_do->id_grupoper = $idgrupoper;
            $this->bantecedente_comunidad->_do->id_caso = $idcaso;
            $this->bantecedente_comunidad->_do->id_antecedente
                = (int)var_escapa($v, $db);
            $this->bantecedente_comunidad->_do->insert();
        }

        foreach (var_escapa_arreglo($valores['id_rangoedad']) as $k => $v) {
            $this->bcomunidad_rangoedad->_do->id_grupoper = $idgrupoper;
            $this->bcomunidad_rangoedad->_do->id_caso = $idcaso;
            $this->bcomunidad_rangoedad->_do->id_rangoedad
                = (int)var_escapa($v, $db);
            $this->bcomunidad_rangoedad->_do->insert();
        }

        foreach (var_escapa_arreglo($valores['id_sectorsocial']) as $k => $v) {
            $this->bcomunidad_sectorsocial->_do->id_grupoper = $idgrupoper;
            $this->bcomunidad_sectorsocial->_do->id_caso = $idcaso;
            $this->bcomunidad_sectorsocial->_do->id_sectorsocial
                = (int)var_escapa($v, $db);
            $this->bcomunidad_sectorsocial->_do->insert();
        }

        foreach (var_escapa_arreglo($valores['id_vinculoestado']) as $k => $v) {
            $this->bcomunidad_vinculoestado->_do->id_grupoper
                = $idgrupoper;
            $this->bcomunidad_vinculoestado->_do->id_caso = $idcaso;
            $this->bcomunidad_vinculoestado->_do->id_vinculoestado
                = (int)var_escapa($v, $db);
            $this->bcomunidad_vinculoestado->_do->insert();
        }

        foreach (var_escapa_arreglo($valores['id_filiacion']) as $k => $v) {
            $this->bcomunidad_filiacion->_do->id_grupoper = $idgrupoper;
            $this->bcomunidad_filiacion->_do->id_caso = $idcaso;
            $this->bcomunidad_filiacion->_do->id_filiacion
                = (int)var_escapa($v, $db);
            $this->bcomunidad_filiacion->_do->insert();
        }

        foreach (var_escapa_arreglo($valores['id_profesion']) as $k => $v) {
            $this->bcomunidad_profesion->_do->id_grupoper = $idgrupoper;
            $this->bcomunidad_profesion->_do->id_caso= $idcaso;
            $this->bcomunidad_profesion->_do->id_profesion
                = (int)var_escapa($v, $db);
            $this->bcomunidad_profesion->_do->insert();
        }

        foreach (var_escapa_arreglo($valores['id_organizacion']) as $k => $v) {
            $this->bcomunidad_organizacion->_do->id_grupoper = $idgrupoper;
            $this->bcomunidad_organizacion->_do->id_caso = $idcaso;
            $this->bcomunidad_organizacion->_do->id_organizacion
                = (int)var_escapa($v, $db);
            $this->bcomunidad_organizacion->_do->insert();
        }

        caso_usuario($_SESSION['basicos_id']);
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
     * @see    PagBaseSimple
     */
    function datosBusqueda(&$w, &$t, &$db, $idcaso, &$subcons)
    {
        prepara_consulta_gen(
            $w, $t, $idcaso, 'victimacolectiva',
            '', '', false, array('antecedente_comunidad',
                    'comunidad_rangoedad', 'comunidad_sectorsocial',
                    'comunidad_vinculoestado', 'comunidad_filiacion',
                    'comunidad_profesion', 'comunidad_organizacion'
            ),
            'id_grupoper', array('personasaprox'), 'id_grupoper'
        );
    }

}

?>
