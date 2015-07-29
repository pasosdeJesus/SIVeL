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
require_once 'DataObjects/Presponsable.php';
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
    var $bcaso_presponsable;
    /** Categorias de cada presunto responsable */
    var $bcategoria;

    var $titulo = 'Presuntos Responsables';

    var $pref = "fpr";

    var $nuevaCopia = false;

    var $clase_modelo = 'caso_presponsable';

    /**
     * Pone en null variables asociadas a tablas de la pestaña.
     *
     * @return null
     */
    function nullVar()
    {
        $this->bcaso_presponsable = null;
        $this->bcategoria = null;
    }

    /**
     * Retorna una identificación del registro actual.
     *
     * @return string Identifación
     */
    function copiaId()
    {
        return $this->bcaso_presponsable->_do->id_presponsable .
            ":" . $this->bcaso_presponsable->_do->id;
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
            $do =& objeto_tabla('caso_presponsable');
            $db =& $do->getDatabaseConnection();
            $do->id_caso = $_SESSION['basicos_id'];
            $do->id_presponsable
                = (int)var_escapa($valores['id_presponsable'], $db);
            $do->id = (int)var_escapa($valores['id'], $db);
            $ir = PagPResponsables::integridadRef(
                $db, $do->id_caso,
                $do->id_presponsable, 'eliminar', $valores
            );
            if ($ir && $do->find()==1) {
                $q = "DELETE FROM caso_categoria_presponsable " .
                    "WHERE id_caso='" . (int)$do->id_caso . "' " .
                    " AND id='" . (int)var_escapa($do->id, $db) . "' " .
                    " AND id_presponsable='" .
                    (int)var_escapa($do->id_presponsable, $db) . "';";
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
     * @param array $apar Arreglo de parametros. Vacio aqui.
     *
     * @return handle Conexión a base de datos
     */
    function iniVar($apar = null)
    {
        $drespCaso =& objeto_tabla('caso_presponsable');
        $dcategoria =& objeto_tabla('caso_categoria_presponsable');

        $db =& $drespCaso->getDatabaseConnection();
        $idcaso =& $_SESSION['basicos_id'];
        if (!isset($idcaso) || $idcaso == null) {
            die(_("Bug: idcaso no debería ser null"));
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
            $drespCaso->id_presponsable = null;
            $q = "SELECT (max(id)) FROM " .
                    "caso_presponsable WHERE " .
                    "id_caso='" . $idcaso . "'";
            $id = (int)($db->getOne($q)) + 1;
            $drespCaso->id = $id;
            $dcategoria->id_presponsable = null;
            $dcategoria->id = null;
            $dcategoria->id_tviolencia = null;
            $dcategoria->id_supracategoria = null;
            $dcategoria->id_categoria = null;
        } else {
            $drespCaso->id_presponsable = $idp[$_SESSION['fpr_pag']];
            $drespCaso->id = $idp2[$_SESSION['fpr_pag']];
            $drespCaso->find();
            $drespCaso->fetch();
            $dcategoria->id_presponsable = $idp[$_SESSION['fpr_pag']];
            $dcategoria->id = $idp2[$_SESSION['fpr_pag']];
        }

        $this->bcaso_presponsable =& DB_DataObject_FormBuilder::create(
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
        $this->PagBaseMultiple($nomForma);
        $this->titulo = _('Presuntos Responsables');
        $this->tcorto = _('P. Resp.');
        if (isset($GLOBALS['etiqueta']['Presuntos Responsables'])) {
            $this->titulo = $GLOBALS['etiqueta']['Presuntos Responsables'];
            $this->tcorto = $GLOBALS['etiqueta']['Presuntos Responsables'];
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
     *
     * @see PagBaseSimple
     */
    function formularioAgrega(&$db, $idcaso)
    {
        if (isset($this->bcaso_presponsable->_do->id)) {
            $vv = $this->bcaso_presponsable->_do->id;
        } else {
            $vv = '';
        }
        $this->addElement('hidden', 'id', $vv);

        $this->bcaso_presponsable->createSubmit = 0;
        $q = "SELECT DISTINCT tipo FROM caso_presponsable " .
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
        $this->bcaso_presponsable->_do->es_enumOptions['tipo'] = $op;
        if (isset($_SESSION['forma_modo'])
            && $_SESSION['forma_modo'] == 'busqueda'
        ) {
            $this->bcaso_presponsable->_do->es_enumOptions['tipo']
                = array('' => '') + $op;
        }

        $this->bcaso_presponsable->useForm($this);
        $this->bcaso_presponsable->getForm();

        $pr =& $this->getElement('id_presponsable');
        sort($pr->_options);

        $sel =& $this->addElement(
            'select', 'clasificacion',
            _('Otras Agresiones')
        );
        $this->addRule(
            'clasificacion', 'requerido',
            _('Otras Agresiones'), 'required', '', 'client'
        );
        $sel->setMultiple(true);
        ResConsulta::llenaSelCategoria(
            $db,
            "SELECT id_tviolencia, id_supracategoria, " .
            "id FROM categoria " .
            "WHERE tipocat='O' ORDER BY id_tviolencia, id;", $sel
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
        $d =& objeto_tabla('caso_presponsable');
        $campos = array_merge(
            array('id_presponsable', 'clasificacion'),
            $d->fb_fieldsToRender
        );

        if (isset($_SESSION['recuperaErrorValida'])) {
            $v = $_SESSION['recuperaErrorValida'];
        } else {
            $cpr = $this->bcaso_presponsable->_do->id_presponsable;
            $v['id_presponsable'] = $cpr;
            $pr=& $this->getElement('id_presponsable');
            $pr->setValue($cpr);
            $vscc = array();
            if (isset($_SESSION['nuevo_copia_id'])
                && strstr($_SESSION['nuevo_copia_id'], ':') != false
            ) {
                list($idpr, $id) = explode(':', $_SESSION['nuevo_copia_id']);
                unset($_SESSION['nuevo_copia_id']);
                $d->id_caso = $idcaso;
                $d->id_presponsable = $idpr;
                $d->id = $id;
                $d->find();
                $d->fetch();
                foreach ($d->fb_fieldsToRender as $c) {
                    $cq = $this->getElement($c);
                    $v[$c] = $d->$c;
                    //$cq->setValue($d->$c);
                }
                $dc =& objeto_tabla('caso_categoria_presponsable');
                $dc->id_caso = $idcaso;
                $dc->id_presponsable = $idpr;
                $dc->id = $id;
                $dc->find();
                while ($dc->fetch()) {
                    $vscc[] = $dc->id_tviolencia . ":" .
                    $dc->id_supracategoria . ":" .
                    $dc->id_categoria;
                }
                $v['clasificacion'] = $vscc;
                // $scc->setValue($vscc);
            }
            if (isset($this->bcategoria->_do->id)
                && isset($this->bcategoria->_do->id_presponsable)
            ) {
                $this->bcategoria->_do->find();
                while ($this->bcategoria->_do->fetch()) {
                    $vscc[] = $this->bcategoria->_do->id_tviolencia .
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
        $q = "DELETE FROM caso_categoria_presponsable WHERE id_caso='"
            . (int)$idcaso . "'";
        hace_consulta($db, $q);

        $q = "DELETE FROM caso_presponsable WHERE id_caso='"
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
        "caso_categoria_presponsable, categoria WHERE " .
        "id_categoria=categoria.id AND " .
        "id_caso='" . $idcaso . "' AND " .
        "id_presponsable='" . $idpres . "' AND "  .
        "categoria.tipocat<>'O'"
        ;
        $nr = $db->getOne($q);
        if ($nr > 0) {
            error_valida(
                _('Hay ') . $nr .
                (' categorias que no son de tipo Otras'),
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
        if (isset($GLOBALS['no_permite_editar']) && $GLOBALS['no_permite_editar']) {
            $htmljs = new HTML_Javascript();
            echo $htmljs->startScript();
            echo $htmljs->alert( 'Edición deshabilitada.');
            echo $htmljs->endScript();
            return true;
        }
        $es_vacio = ($valores['id_presponsable'] == '');

        if ($es_vacio) {
            return true;
        }
        if (!$this->validate() ) {
            return false;
        }
        verifica_sin_CSRF($valores);
        if (in_array(31, $_SESSION['opciones'])
            && !in_array(21, $_SESSION['opciones'])
        ) {
            return true;
        }


        $db = $this->iniVar();
        $this->bcaso_presponsable->forceQueryType(
            DB_DATAOBJECT_FORMBUILDER_QUERY_FORCEINSERT
        );
        if (isset($this->bcaso_presponsable->_do->id)
            && isset($this->bcaso_presponsable->_do->id_presponsable)
        ) {
            $id = (int)var_escapa($valores['id'], $db);
            $idcaso = $this->bcaso_presponsable->_do->id_caso;
            $idpres = $this->bcaso_presponsable->_do->id_presponsable;
            if (isset($idpres) && $idpres != ''
                && $valores['id_presponsable'] != $idpres
            ) {
                $ir =$this->integridadRef(
                    $db, $idcaso, $idpres, 'modificar', $valores
                );
                if (!$ir) {
                    return false;
                }
            }
            $q = "DELETE FROM caso_categoria_presponsable " .
                " WHERE id_caso='" . (int)$idcaso . "' " .
                " AND id='" . (int)$id . "' " .
                " AND id_presponsable='" . (int)$idpres . "'";
            $result = hace_consulta($db, $q);
            $this->bcaso_presponsable->_do->delete();
            $this->bcaso_presponsable->_do->id = $id;
            $this->bcaso_presponsable->_do->id_caso = $idcaso;
        } else {
            $_SESSION['fpr_total']++;
        }

        $ret = $this->process(
            array(&$this->bcaso_presponsable,
            'processForm'
            ), false
        );
        if (PEAR::isError($ret)) {
            die($ret->getMessage());
        }
        if (isset($valores['clasificacion']) && isset($this->bcategoria->_do)) {
            foreach (var_escapa($valores['clasificacion']) as $k => $v) {
                $t = explode(":", var_escapa($v, $db));
                $this->bcategoria->_do->id
                    = $this->bcaso_presponsable->_do->id;
                $this->bcategoria->_do->id_caso
                    = $this->bcaso_presponsable->_do->id_caso;
                $this->bcategoria->_do->id_presponsable
                    = $this->bcaso_presponsable->_do->id_presponsable;
                $this->bcategoria->_do->id_tviolencia = $t[0];
                $this->bcategoria->_do->id_supracategoria = $t[1];
                $this->bcategoria->_do->id_categoria = $t[2];
                $this->bcategoria->_do->insert();
            }
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
     * @param string &$db      Conexión a base de datos
     * @param object $idcaso   Identificación de caso
     * @param string &$subcons Subconsulta
     *
     * @return void
     * @see PagBaseSimple
     */
    function datosBusqueda(&$w, &$t, &$db, $idcaso, &$subcons)
    {
        $duc=& objeto_tabla('caso_presponsable');
        $duc->id_caso = (int)$idcaso;
        if ($duc->find()>0) {
            $t .= ", caso_presponsable";
            consulta_and_sinap(
                $w, "caso_presponsable.id_caso",
                "caso.id", "=", "AND"
            );
            $w3="";
            while ($duc->fetch()) {
                $w2="";
                consulta_and(
                    $db, $w2, "caso_presponsable.id_presponsable",
                    (int)($duc->id_presponsable), "=", "AND"
                );
                if (isset($duc->tipo)) {
                    consulta_and(
                        $db, $w2, "caso_presponsable.tipo",
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
                            "caso_presponsable.$ncampo",
                            "%" . trim($duc->$ncampo) . "%",
                            " ILIKE ", "AND"
                        );
                    }
                }

                $du=& objeto_tabla('Caso_categoria_presponsable');
                $du->id_caso = (int)$idcaso;
                if ($du->find()>0) {
                    $t .= ", caso_categoria_presponsable";
                    consulta_and_sinap(
                        $w, "caso_categoria_presponsable.id_caso",
                        "caso.id", "=", "AND"
                    );
                    consulta_and_sinap(
                        $w,
                        "caso_categoria_presponsable.id_presponsable",
                        "caso_presponsable.id_presponsable", "=",
                        "AND"
                    );
                    while ($du->fetch()) {
                        consulta_and(
                            $db, $w2,
                            "caso_categoria_presponsable.id_tviolencia",
                            $du->id_tviolencia, '=', 'AND'
                        );
                        consulta_and(
                            $db, $w2,
                            "caso_categoria_presponsable.id_supracategoria",
                            $du->id_supracategoria, '=', 'AND'
                        );
                        consulta_and(
                            $db, $w2,
                            "caso_categoria_presponsable.id_categoria",
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


}

?>
