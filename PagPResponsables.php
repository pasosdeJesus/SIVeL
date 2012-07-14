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
 * Pestaña Presuntos Responsables de la ficha de captura de caso
 */
require_once 'PagBaseMultiple.php';
require_once 'ResConsulta.php';
require_once 'DataObjects/Presuntos_responsables.php';
require_once $_SESSION['dirsitio'] . '/conf.php';

/**
 * Página presuntos responsables.
 * Ver documentación de funciones en clase base.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      PagBaseMultiple
*/
class PagPResponsables extends PagBaseMultiple
{
    /** Relaciona caso con presuntos responsables */
    var $bpresuntos_responsables_caso;
    /** Categorias de cada presunto responsable */
    var $bcategoria;

    var $titulo = 'Presuntos Responsables';

    var $tcorto = 'P. Resp.';

    var $pref = "fpr";

    var $nuevaCopia = false;

    var $clase_modelo = 'presuntos_responsables_caso';

    /**
     * Pone en null variables asociadas a tablas de la pestaña.
     *
     * @return null
     */
    function nullVar()
    {
        $this->bpresuntos_responsables_caso = null;
        $this->bcategoria = null;
    }

    /**
     * Retorna una identificación del registro actual.
     *
     * @return string Identifación
     */
    function copiaId()
    {
        return $this->bpresuntos_responsables_caso->_do->id_p_responsable .
            ":" . $this->bpresuntos_responsables_caso->_do->id;
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
        if ($valores['id'] != null) {
            $do =& objeto_tabla('presuntos_responsables_caso');
            $db =& $do->getDatabaseConnection();
            $do->id_caso = $_SESSION['basicos_id'];
            $do->id_p_responsable
                = (int)var_escapa($valores['id_p_responsable'], $db);
            $do->id = (int)var_escapa($valores['id'], $db);
            $ir = PagPResponsables::integridadRef(
                $db, $do->id_caso,
                $do->id_p_responsable, 'eliminar', $valores
            );
            if ($ir && $do->find()==1) {
                $q = "DELETE FROM categoria_p_responsable_caso " .
                    "WHERE id_caso='" . (int)$do->id_caso . "' " .
                    " AND id='" . (int)var_escapa($do->id, $db) . "' " .
                    " AND id_p_responsable='" .
                    (int)var_escapa($do->id_p_responsable, $db) . "';";
                hace_consulta($db, $q);
                $do->delete();
                $_SESSION['fpr_total']--;
            }

        }
    }




    /**
     * Inicializa variables y datos de la pestaña.
     * Ver documentación completa en clase base.
     *
     * @return handle Conexión a base de datos
     */
    function iniVar()
    {
        $drespCaso =& objeto_tabla('presuntos_responsables_caso');
        $dcategoria =& objeto_tabla('categoria_p_responsable_caso');

        $db =& $drespCaso->getDatabaseConnection();
        $idcaso =& $_SESSION['basicos_id'];
        if (!isset($idcaso) || $idcaso == null) {
            die("Bug: idcaso no debería ser null");
        }

        $idp = array();
        $idp2 = array();
        $ndp = array();
        $tot = ResConsulta::extraePResponsables(
            $idcaso, $db, $idp,
            $idp2, $ndp
        );

        $_SESSION['fpr_total'] = $tot;
        $drespCaso->id_caso = $idcaso;
        $dcategoria->id_caso = $idcaso;
        if ($_SESSION['fpr_pag'] < 0 || $_SESSION['fpr_pag'] >= $tot) {
            $drespCaso->id_p_responsable = null;
            $q = "SELECT (max(id)) FROM " .
                    "presuntos_responsables_caso WHERE " .
                    "id_caso='" . $idcaso . "'";
            $id = (int)($db->getOne($q)) + 1;
            $drespCaso->id = $id;
            $dcategoria->id_p_responsable = null;
            $dcategoria->id = null;
            $dcategoria->id_tipo_violencia = null;
            $dcategoria->id_supracategoria = null;
            $dcategoria->id_categoria = null;
        } else {
            $drespCaso->id_p_responsable = $idp[$_SESSION['fpr_pag']];
            $drespCaso->id = $idp2[$_SESSION['fpr_pag']];
            $drespCaso->find();
            $drespCaso->fetch();
            $dcategoria->id_p_responsable = $idp[$_SESSION['fpr_pag']];
            $dcategoria->id = $idp2[$_SESSION['fpr_pag']];
        }

        $this->bpresuntos_responsables_caso =& DB_DataObject_FormBuilder::create(
            $drespCaso,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
                'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bcategoria =& DB_DataObject_FormBuilder::create(
            $dcategoria,
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
     */
    function PagPResponsables($nomForma)
    {
        parent::PagBaseMultiple($nomForma);

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
        if (isset($this->bpresuntos_responsables_caso->_do->id)) {
            $vv = $this->bpresuntos_responsables_caso->_do->id;
        } else {
            $vv = '';
        }
        $this->addElement('hidden', 'id', $vv);

        $this->bpresuntos_responsables_caso->createSubmit = 0;
        $q = "SELECT DISTINCT tipo FROM presuntos_responsables_caso " .
            " WHERE id_caso='" . (int)$_SESSION['basicos_id'] . "' " .
            " ORDER BY tipo;";
        $result = hace_consulta($db, $q);
        if (PEAR::isError($result)) {
            die($result->getMessage());
        }
        $row = array();
        $l = array('0' => 'A', '1' => 'B', '2'=> 'C');
        $op = array();
        reset($l);
        while ($result->fetchInto($row)) {
            list($llave, $op[$row[0]]) = each($l);
        }
        while (count($op)<3) {
            list($llave, $op[]) = each($l);
        }
        $this->bpresuntos_responsables_caso->_do->es_enumOptions['tipo'] = $op;
        if (isset($_SESSION['forma_modo'])
            && $_SESSION['forma_modo'] == 'busqueda'
        ) {
            $this->bpresuntos_responsables_caso->_do->es_enumOptions['tipo']
                = array('' => '') + $op;
        }

        $this->bpresuntos_responsables_caso->useForm($this);
        $this->bpresuntos_responsables_caso->getForm();

        $pr =& $this->getElement('id_p_responsable');
        sort($pr->_options);

        $sel =& $this->addElement(
            'select', 'clasificacion',
            'Otras Agresiones'
        );
        $this->addRule(
            'clasificacion', 'requerido',
            'Otras Agresiones', 'required', '', 'client'
        );
        $sel->setMultiple(true);
        ResConsulta::llenaSelCategoria(
            $db,
            "SELECT id_tipo_violencia, id_supracategoria, " .
            "id FROM categoria " .
            "WHERE tipocat='O' ORDER BY id_tipo_violencia, id;", $sel
        );

        if (strpos($GLOBALS['modulos'], 'modulos/belicas') === false) {
            $this->removeElement('tipo');
            $this->addElement('hidden', 'tipo', 0);
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
        $d =& objeto_tabla('presuntos_responsables_caso');
        $campos = array_merge(
            array('id_p_responsable', 'clasificacion'),
            $d->fb_fieldsToRender
        );

        if (isset($_SESSION['recuperaErrorValida'])) {
            $v = $_SESSION['recuperaErrorValida'];
        } else {
            $cpr = $this->bpresuntos_responsables_caso->_do->id_p_responsable;
            $v['id_p_responsable'] = $cpr;
            $pr=& $this->getElement('id_p_responsable');
            $pr->setValue($cpr);
            $vscc = array();
            if (isset($_SESSION['nuevo_copia_id'])
                && strstr($_SESSION['nuevo_copia_id'], ':') != false
            ) {
                list($idpr, $id) = explode(':', $_SESSION['nuevo_copia_id']);
                unset($_SESSION['nuevo_copia_id']);
                $d->id_caso = $idcaso;
                $d->id_p_responsable = $idpr;
                $d->id = $id;
                $d->find();
                $d->fetch();
                foreach ($d->fb_fieldsToRender as $c) {
                    $cq = $this->getElement($c);
                    $v[$c] = $d->$c;
                    //$cq->setValue($d->$c);
                }
                $dc =& objeto_tabla('categoria_p_responsable_caso');
                $dc->id_caso = $idcaso;
                $dc->id_p_responsable = $idpr;
                $dc->id = $id;
                $dc->find();
                while ($dc->fetch()) {
                    $vscc[] = $dc->id_tipo_violencia . ":" .
                    $dc->id_supracategoria . ":" .
                    $dc->id_categoria;
                }
                $v['clasificacion'] = $vscc;
                // $scc->setValue($vscc);
            }
            if (isset($this->bcategoria->_do->id)
                && isset($this->bcategoria->_do->id_p_responsable)
            ) {
                $this->bcategoria->_do->find();
                while ($this->bcategoria->_do->fetch()) {
                    $vscc[] = $this->bcategoria->_do->id_tipo_violencia .
                        ":" . $this->bcategoria->_do->id_supracategoria .
                        ":" . $this->bcategoria->_do->id_categoria;
                }
                $v['clasificacion'] = $vscc;
                //$scc->setValue($vscc);
            }
        }

        establece_valores_form($this, $campos, $v);

        if (isset($_SESSION['recuperaErrorValida'])) {
            unset($_SESSION['recuperaErrorValida']);
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
        $q = "DELETE FROM categoria_p_responsable_caso WHERE id_caso='"
            . (int)$idcaso . "'";
        hace_consulta($db, $q);

        $q = "DELETE FROM presuntos_responsables_caso WHERE id_caso='"
            . (int)$idcaso . "'";
        hace_consulta($db, $q);
    }

     /** Verifica integridad referencial antes de eliminar o modificar
     *
     * @param handle  &$db     Base de datos
     * @param integer $idcaso  Id. del caso
     * @param integer $idpres  Id del presunto responsable
     * @param integer $accion  Acción
     * @param integer $valores Valores enviados por formulario
     *
     * @return bool Verdaderdo si y solo si hay integridad referencial
     */
    static function integridadRef(&$db, $idcaso, $idpres, $accion, $valores)
    {
        $q = "SELECT COUNT(tipocat) FROM " .
        "categoria_p_responsable_caso, categoria WHERE " .
        "id_categoria=categoria.id AND " .
        "id_caso='" . $idcaso . "' AND " .
        "id_p_responsable='" . $idpres . "' AND "  .
        "categoria.tipocat<>'O'"
        ;
        $nr = $db->getOne($q);
        if ($nr > 0) {
            error_valida(
                'Hay ' . $nr . ' categorias que no son de tipo Otras',
                $valores
            );
            return false;
        }
        return true;
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
        $es_vacio = ($valores['id_p_responsable'] == '');

        if ($es_vacio) {
            return true;
        }
        if (!$this->validate() ) {
            return false;
        }
        verifica_sin_CSRF($valores);

        $db = $this->iniVar();
        $this->bpresuntos_responsables_caso->forceQueryType(
            DB_DATAOBJECT_FORMBUILDER_QUERY_FORCEINSERT
        );
        if (isset($this->bpresuntos_responsables_caso->_do->id)
            && isset($this->bpresuntos_responsables_caso->_do->id_p_responsable)
        ) {
            $id = (int)var_escapa($valores['id'], $db);
            $idcaso = $this->bpresuntos_responsables_caso->_do->id_caso;
            $idpres = $this->bpresuntos_responsables_caso->_do->id_p_responsable;
            if (isset($idpres) && $idpres != ''
                && $valores['id_p_responsable'] != $idpres
            ) {
                $ir =$this->integridadRef(
                    $db, $idcaso, $idpres, 'modificar', $valores
                );
                if (!$ir) {
                    return false;
                }
            }
            $q = "DELETE FROM categoria_p_responsable_caso " .
                " WHERE id_caso='" . (int)$idcaso . "' " .
                " AND id='" . (int)$id . "' " .
                " AND id_p_responsable='" . (int)$idpres . "'";
            $result = hace_consulta($db, $q);
            $this->bpresuntos_responsables_caso->_do->delete();
            $this->bpresuntos_responsables_caso->_do->id = $id;
            $this->bpresuntos_responsables_caso->_do->id_caso = $idcaso;
        } else {
            $_SESSION['fpr_total']++;
        }

        $ret = $this->process(
            array(&$this->bpresuntos_responsables_caso,
            'processForm'
            ), false
        );
        if (PEAR::isError($ret)) {
            die($ret->getMessage());
        }
        if (isset($valores['clasificacion'])) {
            foreach (var_escapa($valores['clasificacion']) as $k => $v) {
                $t = explode(":", var_escapa($v, $db));
                $this->bcategoria->_do->id
                    = $this->bpresuntos_responsables_caso->_do->id;
                $this->bcategoria->_do->id_caso
                    = $this->bpresuntos_responsables_caso->_do->id_caso;
                $this->bcategoria->_do->id_p_responsable
                    = $this->bpresuntos_responsables_caso->_do->id_p_responsable;
                $this->bcategoria->_do->id_tipo_violencia = $t[0];
                $this->bcategoria->_do->id_supracategoria = $t[1];
                $this->bcategoria->_do->id_categoria = $t[2];
                $this->bcategoria->_do->insert();
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
        $duc=& objeto_tabla('presuntos_responsables_caso');
        $duc->id_caso = (int)$idcaso;
        if ($duc->find()>0) {
            $t .= ", presuntos_responsables_caso";
            consulta_and_sinap(
                $w, "presuntos_responsables_caso.id_caso",
                "caso.id", "=", "AND"
            );
            $w3="";
            while ($duc->fetch()) {
                $w2="";
                consulta_and(
                    $db, $w2, "presuntos_responsables_caso.id_p_responsable",
                    (int)($duc->id_p_responsable), "=", "AND"
                );
                if (isset($duc->tipo)) {
                    consulta_and(
                        $db, $w2, "presuntos_responsables_caso.tipo",
                        $duc->tipo, "=", "AND"
                    );
                }
                foreach (
                    array(
                        'bloque', 'frente', 'brigada', 'batallon',
                        'division', 'otro'
                    ) as $ncampo
                ) {
                    if (isset($duc->$ncampo) && trim($duc->$ncampo) != '') {
                        consulta_and(
                            $db, $w2,
                            "presuntos_responsables_caso.$ncampo",
                            "%" . trim($duc->$ncampo) . "%",
                            " ILIKE ", "AND"
                        );
                    }
                }

                $du=& objeto_tabla('Categoria_p_responsable_caso');
                $du->id_caso = (int)$idcaso;
                if ($du->find()>0) {
                    $t .= ", categoria_p_responsable_caso";
                    consulta_and_sinap(
                        $w, "categoria_p_responsable_caso.id_caso",
                        "caso.id", "=", "AND"
                    );
                    consulta_and_sinap(
                        $w,
                        "categoria_p_responsable_caso.id_p_responsable",
                        "presuntos_responsables_caso.id_p_responsable", "=",
                        "AND"
                    );
                    while ($du->fetch()) {
                        consulta_and(
                            $db, $w2,
                            "categoria_p_responsable_caso.id_tipo_violencia",
                            $du->id_tipo_violencia, '=', 'AND'
                        );
                        consulta_and(
                            $db, $w2,
                            "categoria_p_responsable_caso.id_supracategoria",
                            $du->id_supracategoria, '=', 'AND'
                        );
                        consulta_and(
                            $db, $w2,
                            "categoria_p_responsable_caso.id_categoria",
                            $du->id_categoria, '=', 'AND'
                        );
                    }
                }
                if ($w2!="") {
                    $w3 = $w3 == "" ? "($w2)" : "$w3 OR ($w2)";
                }
            }
            if ($w3!="") {
                $w .= " AND ($w3)";
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
        parent::compara(
            $db, $r, $id1, $id2,
            array('Presuntos Responsables'
            => array('presuntos_responsables_caso', 'id_p_responsable,id'))
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
            array('Presuntos Responsables'
            => array('presuntos_responsables_caso', 'id_p_responsable,id'))
        );
    }


}

?>
