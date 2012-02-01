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
 * @version   CVS: $Id: PagUbicacion.php,v 1.85.2.5 2011/10/13 13:41:06 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

/**
 * Pestaña Ubicación de la ficha de captura de un caso
 */
require_once 'PagBaseMultiple.php';
require_once 'DataObjects/Caso.php';
require_once 'misc_importa.php';
require_once 'DB/DataObject/Cast.php';

/**
 * Responde a evento cambio de departamento
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class CamDepartamento extends HTML_QuickForm_Action
{
    /** * Ejecuta acción
     *
     * @param object &$page      Página
     * @param string $actionName Acción
     *
     * @return void
     */
    function perform(&$page, $actionName)
    {
        $_SESSION['camDepartamento']
            = (int)$page->_submitValues['id_departamento'];
        $_SESSION['camMunicipio'] = '';
        $pageName =  $page->getAttribute('id');
        $data     =& $page->controller->container();
        $data['values'][$pageName] = $page->exportValues();
        $data['valid'][$pageName]  = $page->validate();
        $page->handle('display');
    }
}

/**
 * Responde a evento cambio de municipio
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class CamMunicipio extends HTML_QuickForm_Action
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

        $_SESSION['camDepartamento']
            = (int)$page->_submitValues['id_departamento'];
        $_SESSION['camMunicipio']
            = (int)$page->_submitValues['id_municipio'];
        $pageName =  $page->getAttribute('id');
        $data     =& $page->controller->container();
        $data['values'][$pageName] = $page->exportValues();
        $data['valid'][$pageName]  = $page->validate();

        $page->handle('display');
    }
}



/**
 * Ubicación.
 * Ver documentación de funciones en clase base.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      PagBaseMultiple
 */
class PagUbicacion extends PagBaseMultiple
{
    /** Ubicación independiente del caso */
    var $bubicacion;

    var $titulo = 'Ubicación';

    var $tcorto = 'Ubicación';

    var $pref = "fub";

    var $nuevaCopia = false;

    var $clase_modelo = 'ubicacion';

    /**
     * Deja en blanco variables de este formulario
     *
     * @return void
     */
    static function nullVarUbicacion()
    {
        unset($_SESSION['camDepartamento']);
        unset($_SESSION['camMunicipio']);
    }


    /**
     * Pone en null variables asociadas a tablas de la pestaña.
     *
     * @return null
     */
    function nullVar()
    {
        $this->bubicacion = null;
        PagUbicacion::nullVarUbicacion();
    }

    /**
     * Retorna una identificación del registro actual.
     *
     * @return string Identifación
     */
    function copiaId()
    {
        return $this->bubicacion->_do->id;
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

            $du =& objeto_tabla('ubicacion');
            $du->id = var_escapa($valores['id'], $db);
            $du->id_caso = $_SESSION['basicos_id'];

            if ($du->find()==1) {
                $du->delete();
                $_SESSION['fub_total']--;
            }
            return true;
        }
        return false;
    }

    /**
     * Inicializa variables y datos de la pestaña.
     * Ver documentación completa en clase base.
     *
     * @return handle Conexión a base de datos
     */
    function iniVar()
    {
        $dubicacion =& objeto_tabla('ubicacion');

        $db =& $dubicacion->getDatabaseConnection();
        $idcaso =& $_SESSION['basicos_id'];
        if (!isset($idcaso) || $idcaso == null) {
            die("Bug: idcaso no debería ser null");
        }
        $result = hace_consulta($db, "SELECT  id FROM caso WHERE id='$idcaso'");
        $row = array();
        if (!isset($result) || PEAR::isError($result)
            || (!$result->fetchInto($row) && $idcaso != $GLOBALS['idbus'])
        ) {
            die("No pudo consultarse caso " . $idcaso);
        }

        $idu = array();
        $row = array();
        $tot = 0;
        $q = "SELECT id FROM ubicacion " .
            "WHERE id_caso='$idcaso' " .
            "ORDER BY id";
        $result = hace_consulta($db, $q);
        while (isset($result) && !PEAR::isError($result)
            && $result->fetchInto($row)
        ) {
            $idu[] = $row[0];
            $tot++;
        }

        $_SESSION['fub_total'] = $tot;
        $ni = $_SESSION['fub_pag'];
        if ($ni >= 0 && $ni < $tot) {
            $dubicacion->id = $idu[$ni];
            if ($dubicacion->find()>0) {
                $dubicacion->fetch();
            }
        }

        $this->bubicacion =& DB_DataObject_FormBuilder::create(
            $dubicacion,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
            'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bubicacion->useMutators = true;

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
    function PagUbicacion($nomForma)
    {
        parent::PagBaseMultiple($nomForma);

        PagUbicacion::nullVarUbicacion();
        $this->addAction('id_departamento', new CamDepartamento());
        $this->addAction('id_municipio', new CamMunicipio());

        $this->addAction('siguiente', new Siguiente());
        $this->addAction('anterior', new Anterior());
    }


    /**
     * Identificación de departamento elegido por usuario.
     *
     * @param object &$form Formulario
     * @param object $def   Valor por defecto
     *
     * @return string id de departamento
     */
    static function retIdDepartamento(&$form, $def = null)
    {
        $ndepartamento = null;
        if (isset($form->_submitValues['id_departamento'])) {
            $ndepartamento = (int)$form->_submitValues['id_departamento'] ;
        } else if (isset($_SESSION['camDepartamento'])
            && $_SESSION['camDepartamento'] != ''
        ) {
            $ndepartamento = $_SESSION['camDepartamento'] ;
        } else if (isset($def) && $def != null
            && $def != DB_DataObject_Cast::sql('NULL')
        ) {
            $ndepartamento = $def;
        }
        return $ndepartamento;
    }

    /**
     * Identificación del municpio elegido por usuario.
     *
     * @param object &$form Formulario
     * @param object $def   Valor por defecto
     *
     * @return string id de municipio
     */
    static function retIdMunicipio(&$form, $def = null)
    {
        $nmunicipio = null;
        if (isset($form->_submitValues['id_municipio'])) {
            $nmunicipio = (int)$form->_submitValues['id_municipio'] ;
        } else if (isset($_SESSION['camMunicipio'])) {
            $nmunicipio = $_SESSION['camMunicipio'] ;
        } else if (isset($def) && $def != null
            && $def != DB_DataObject_Cast::sql('NULL')
        ) {
            $nmunicipio = $def;
        }
        return $nmunicipio;
    }

    /**
     * Identificación de la clase geográfica elegida por usuario
     *
     * @param object &$form Formulario
     * @param object $def   Valor por defecto
     *
     * @return string id de clase
     */
    function retIdClase(&$form, $def = null)
    {
        $nclase = null;
        if (isset($form->_submitValues['id_clase'])) {
            $nclase= (int)$form->_submitValues['id_clase'] ;
        } else if (isset($def) && $def != null) {
            $nclase = $def;
        }
        return $nclase;
    }


    /**
     * Crea campos interdependientes Departamento/Muncipio/Clase
     *
     * @param object &$db    Base de datos
     * @param object &$form  Formulario
     * @param object $idpest Identificación de la pestaña
     * @param object $depdef Departamento por defecto
     * @param object $mundef Municipio por defecto
     *
     * @return array Vector con 3 objetos para añadir al formulario:
     *  departamento, municipio y clase
     */
    static function creaCamposUbicacion(&$db, &$form,
        $idpest, $depdef, $mundef
    ) {
        if (PEAR::isError($db)) {
            die($db->getMessage()." - ".$db->getUserInfo());
        }
        $dep =& $form->createElement(
            'select', 'id_departamento',
            $GLOBALS['etiqueta']['departamento'],
            array()
        );
        $options = array('' => '') + htmlentities_array(
            $db->getAssoc(
                "SELECT  id, nombre FROM departamento ORDER BY nombre"
            )
        );
        $dep->loadArray($options);
        $dep->updateAttributes(
            array('onchange' => "envia('$idpest:id_departamento')")
        );

        $mun =& $form->createElement(
            'select', 'id_municipio',
            $GLOBALS['etiqueta']['municipio'],
            array()
        );
        $mun->updateAttributes(
            array('onchange' => "envia('$idpest:id_municipio')")
        );

        $cla =& $form->createElement(
            'select', 'id_clase',
            $GLOBALS['etiqueta']['clase'],
            array()
        );

        $ndepartamento= PagUbicacion::retIdDepartamento($form, $depdef);
        if ($ndepartamento != null) {
            $dep->setValue($ndepartamento);
            $options = array('' => '') + htmlentities_array(
                $db->getAssoc(
                    "SELECT  id, nombre FROM municipio " .
                    " WHERE id_departamento='$ndepartamento' ORDER BY nombre"
                )
            );
            $mun->loadArray($options);
            $cla->loadArray(array());
        }
        $nmunicipio = PagUbicacion::retIdMunicipio($form, $mundef);
        if ($nmunicipio != null) {
            $mun->setValue($nmunicipio);
            $options = array('' => '') + htmlentities_array(
                $db->getAssoc(
                    "SELECT id, nombre || ' (' || id_tipo_clase || ')'
                    FROM clase
                    WHERE id_departamento = '$ndepartamento'
                    AND id_municipio = '$nmunicipio' ORDER BY nombre"
                )
            );
            $cla->loadArray($options);
        }

        return array($dep, $mun, $cla);
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
        $vv = isset($this->bubicacion->id) ? $this->bubicacion->id : '';
        $this->addElement('hidden', 'id', $vv);

        list($dep, $mun, $cla) = PagUbicacion::creaCamposUbicacion(
            $db, $this, 'ubicacion',
            $this->bubicacion->_do->id_departamento,
            $this->bubicacion->_do->id_municipio
        );

        $this->addElement($dep);
        $this->addElement($mun);
        $this->addElement($cla);

        $this->bubicacion->createSubmit = 0;
        $this->bubicacion->useForm($this);
        $this->bubicacion->getForm($this);

        if (isset($this->bubicacion->_do->latitud)
            && isset($this->bubicacion->_do->longitud)
        ) {
            $this->addElement(
                'static', 'Ver mapa', 'Ver',
                '<a href="http://www.openstreetmap.org/?lat=' .
                $this->bubicacion->_do->latitud .  '&lon=' .
                $this->bubicacion->_do->longitud .
                '&zoom=14&layers=B000FTFT" target="_mapa">Mapa</a>'
            );
        }

        agrega_control_CSRF($this);
    }

    /**
     * Llena valores de ubicación en formulario.
     *
     * @param handle  &$form  Formulario
     * @param integer $depdef Departamento por defecto
     * @param integer $mundef Municipio por defecto
     * @param integer $cladef Clase por defecto
     * @param integer $dep    Objeto departamento en formulario
     * @param integer $mun    Objeto municipio en formulario
     * @param integer $cla    Objeto clase en formulario
     *
     * @return void
     * @see PagBaseSimple
     */
    static function valoresUbicacion(&$form, $depdef, $mundef, $cladef,
        $dep, $mun, $cla
    ) {
        $ndepartamento = PagUbicacion::retIdDepartamento($form, $depdef);
        if ($ndepartamento != null) {
            $dep->setValue($ndepartamento);
        }
        $nmunicipio = PagUbicacion::retIdMunicipio($form, $mundef);
        if ($nmunicipio != null) {
            $mun->setValue($nmunicipio);
        }
        $nclase = PagUbicacion::retIdClase($form, $cladef);
        if ($nclase != null) {
            $cla->setValue($nclase);
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

        $dep =& $this->getElement('id_departamento');
        $mun =& $this->getElement('id_municipio');
        $cla =& $this->getElement('id_clase');
        PagUbicacion::valoresUbicacion(
            $this, $this->bubicacion->_do->id_departamento,
            $this->bubicacion->_do->id_municipio,
            $this->bubicacion->_do->id_clase,
            $dep, $mun, $cla
        );

        $ubi =& $this->getElement('id');
        if (isset($_SESSION['nuevo_copia_id'])) {
            $id = $_SESSION['nuevo_copia_id'];
            unset($_SESSION['nuevo_copia_id']);
            $d =& objeto_tabla('ubicacion');
            $d->get($id);
            foreach (array_merge(
                $d->fb_fieldsToRender,
                array('id_departamento', 'id_municipio', 'id_clase')
            ) as $c
            ) {
                $cq = $this->getElement($c);
                $cq->setValue($d->$c);
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
        hace_consulta($db, "DELETE FROM ubicacion WHERE id_caso='$idcaso'");
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
        $es_vacio = (!isset($valores['lugar']) || $valores['lugar'] == '')
            && (!isset($valores['sitio']) || $valores['sitio'] == '')
            && (!isset($valores['latitud']) || $valores['latitud'] == '')
            && (!isset($valores['longitud']) || $valores['longitud'] == '')
            && ($valores['id_departamento'] == null
            || $valores['id_departamento'] == ''
            )
            && (!isset($valores['id_municipio'])
            || $valores['id_municipio'] == ''
            )
            && (!isset($valores['id_clase']) || $valores['id_clase'] == '' )
        ;

        if ($es_vacio) {
            return true;
        }
        if (!$this->validate() ) {
            return false;
        }
        verifica_sin_CSRF($valores);
        $db = $this->iniVar();

        $idcaso = $_SESSION['basicos_id'];

        $this->bubicacion->_do->id_caso = $idcaso;
        $this->bubicacion->_do->id = var_escapa($valores['id'], $db);

        $this->bubicacion->_do->useMutators = true;
        if ($this->bubicacion->_do->id == null
            || $this->bubicacion->_do->id == ''
        ) {
            $this->bubicacion->forceQueryType(
                DB_DATAOBJECT_FORMBUILDER_QUERY_FORCEINSERT
            );
            $_SESSION['fub_total']++;
        } else {
            if ($this->bubicacion->_do->id_municipio == '') {
                $this->bubicacion->_do->id_municipio = 'null';
            }
            if ($this->bubicacion->_do->id_clase == '') {
                $this->bubicacion->_do->id_clase = 'null';
            }
            $this->bubicacion->forceQueryType(
                DB_DATAOBJECT_FORMBUILDER_QUERY_FORCEUPDATE
            );
        }
        $ret = $this->process(array(&$this->bubicacion, 'processForm'), false);
        if (PEAR::isError($ret)) {
            die($ret->getMessage());
        }

        unset($_SESSION['camDepartamento']);
        unset($_SESSION['camMunicipio']);
        funcionario_caso($_SESSION['basicos_id']);
        return  true;
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
        //prepara_consulta_gen($w, $t, $dCaso->id, 'ubicacion', null, null, false);

        $du=& objeto_tabla('ubicacion');
        $du->id_caso = (int)$idcaso;
        if ($du->find()>0) {
            $t .= ", ubicacion";
            consulta_and_sinap($w, "ubicacion.id_caso", "caso.id", "=", "AND");
            $w3="";
            while ($du->fetch()) {
                $w2="";
                if ($du->id_departamento != null) {
                    consulta_and(
                        $db, $w2, "ubicacion.id_departamento",
                        (int)($du->id_departamento), '=', 'AND'
                    );
                }
                if ($du->id_municipio != null) {
                    consulta_and(
                        $db, $w2, "ubicacion.id_municipio",
                        (int)($du->id_municipio), '=', 'AND'
                    );
                }
                if ($du->id_clase != null) {
                    consulta_and(
                        $db, $w2, "ubicacion.id_clase",
                        (int)($du->id_clase), '=', 'AND'
                    );
                }
                if (trim($du->lugar) != '') {
                    consulta_and(
                        $db, $w2, "ubicacion.lugar",
                        "%" .  trim($du->lugar) . "%", ' ILIKE ', 'AND'
                    );
                }
                if (trim($du->sitio) != '') {
                    consulta_and(
                        $db, $w2, "ubicacion.sitio",
                        "%" .  trim($du->sitio) . "%", ' ILIKE ', 'AND'
                    );
                }
                if (trim($du->latitud) != '') {
                    consulta_and(
                        $db, $w2, "ubicacion.latitud",
                        (double)($du->latitud), '=', 'AND'
                    );
                }
                if (trim($du->longitud) != '') {
                    consulta_and(
                        $db, $w2, "ubicacion.longitud",
                        (double)($du->longitud), '=', 'AND'
                    );
                }
                if ($du->id_tipo_sitio != 1) {
                    consulta_and(
                        $db, $w2, "ubicacion.id_tipo_sitio",
                        (int)($du->id_tipo_sitio), '=', 'AND'
                    );
                }

                if ($w2!="") {
                    $w3 = $w3=="" ? "($w2)" : "$w3 OR ($w2)";
                }
            }
            if ($w3!="") {
                $w .= " AND ($w3)";
            }
        }
    }

    /**
     * Importa de un relato SINCODH lo relacionado con Ubicación
     * creando registros en la base de datos relacionados con el caso $idcaso
     *
     * @param object &$db    Conexión a base de datos
     * @param object $r      Relato en XML
     * @param int    $idcaso Número de caso que se inserta
     * @param string &$obs   Colchon para agregar notas de conversion
     *
     * @return void
     * @see PagBaseSimple
     */
    static function importaRelato(&$db, $r, $idcaso, &$obs)
    {
        assert($db != null);
        assert($r != null);
        assert($idcaso > 0);

        //echo "OJO importaRelato(db, r, idcaso=$idcaso, observaciones=$obs)<br>";
        $reg = dato_basico_en_obs(
            $db, $obs, $r,
            'region', 'region', 'region_caso', $idcaso, '; ', 'id_region'
        );
        $fro = dato_basico_en_obs(
            $db, $obs, $r,
            'frontera', 'frontera', 'frontera_caso', $idcaso, '; ',
            'id_frontera'
        );
        $dubicacion = objeto_tabla('ubicacion');
        $dubicacion->id_caso = $idcaso;
        $departamento = ereg_replace(
            "  *", " ",
            trim(utf8_decode((string)$r->departamento))
        );
        if (isset($r->municipio)) {
            $munic = ereg_replace(
                "  *", " ",
                trim(utf8_decode((string)$r->municipio))
            );
        } else {
            $munic = null;
        }
        if (isset($r->centro_poblado)) {
            $cenp = ereg_replace(
                "  *", " ",
                trim(utf8_decode((string)$r->centro_poblado))
            );
        } else {
            $cenp = null;
        }

        list($id_departamento, $id_municipio, $id_clase)
            = conv_localizacion($db, $departamento, $munic, $cenp, $obs);
        if ($id_municipio != 1000) {
            $dubicacion->id_municipio = $id_municipio;
        }
        if ($id_departamento != 1000) {
            $dubicacion->id_departamento = $id_departamento;
        }
        if ($id_clase != 1000) {
            $dubicacion->id_clase = $id_clase;
        }
        $idtipositio = dato_basico_en_obs(
            $db, $obs, $r, 'tipo_sitio', 'tipo_sitio', '', ''
        );
        if (isset($idtipositio) && $idtipositio != null) {
            $dubicacion->id_tipo_sitio = $idtipositio;
        } else {
            $dubicacion->id_tipo_sitio
                = DataObjects_Tipo_sitio::idSinInfo();
        }
        if (isset($r->latitud)) {
            $dubicacion->latitud = (string)$r->latitud;
        }
        if (isset($r->longitud)) {
            $dubicacion->longitud = (string)$r->longitud;
        }
        foreach (array('lugar', 'sitio') as $c) {
            $d = dato_en_obs($r, $c);
            if ($d != null) {
                $dubicacion->$c = $d;
            }
        }
        $riu = $dubicacion->insert();
        if (PEAR::isError($riu)) {
            die($riu->getMessage() . " - " . $riu->getUserInfo());
        }
        $idubicacion = $dubicacion->id;
        if ($idubicacion == 0) {
            die("idubicacion es 0");
        }
    }
}

?>
