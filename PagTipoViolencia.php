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
 * Pestaña Tipo de Violencia de la ficha de captura de un caso
 */
require_once 'PagBaseSimple.php';
require_once 'ResConsulta.php';

/**
 * Facilita cambio de supracategorias cuando el usuario cambia tipo de
 * violencia al menos en estadistica.php y estadistica_comb.php
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
*/
class CamTipoViolencia extends HTML_QuickForm_Action
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
        $_SESSION['camTipoViolencia']
            = (int)$page->_submitValues['id_tviolencia'];
        $pageName =  $page->getAttribute('id');
        $data     =& $page->controller->container();
        $data['values'][$pageName] = $page->exportValues();
        $data['valid'][$pageName]  = $page->validate();

        $page->handle('display');
    }

}


/**
 * Tipo de Violencia.
 * Ver documentación de funciones en clase base.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      PagBaseSimple
*/
class PagTipoViolencia extends PagBaseSimple
{

    /* Variables DB_DataObject_FormBuilder */

    /** Contextos del caso */
    var $bcaso_contexto;

    /** Antecedentes del caso */
    var $bantecedente_caso;

    var $titulo = 'Contexto';

    var $clase_modelo = 'caso_contexto';

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
        list($db, $dcaso, $idcaso) = parent::iniVar(array(true, true));

        $dcaso_contexto =& objeto_tabla('caso_contexto');
        $dantecedente_caso =& objeto_tabla('antecedente_caso');

        $dcaso->id =  $dcaso_contexto->id_caso
            = $dantecedente_caso->id_caso = $idcaso;
        $result = hace_consulta($db, "SELECT  id FROM caso WHERE id='$idcaso'");
        $row = array();
        if (!isset($result) || PEAR::isError($result)
            || (!$result->fetchInto($row) && $idcaso != $GLOBALS['idbus'])
        ) {
                die("No pudo consultarse caso " . $idcaso);
        }
        $dcaso_contexto->find();
        $dantecedente_caso->find();

        $this->bcaso_contexto =& DB_DataObject_FormBuilder::create(
            $dcaso_contexto,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
                'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bantecedente_caso =& DB_DataObject_FormBuilder::create(
            $dantecedente_caso,
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
    function PagTipoViolencia($nomForma)
    {
        parent::PagBaseSimple($nomForma);

        $this->titulo = _('Contexto');

        $this->addAction('siguiente', new Siguiente('salvaTipoViolencia'));
        $this->addAction('anterior', new Anterior('salvaTipoViolencia'));

        if (isset($GLOBALS['etiqueta']['clasificacion'])) {
            $this->titulo = $this->tcorto
                = $GLOBALS['etiqueta']['clasificacion'];
        }
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
        $this->bcaso_contexto->createSubmit = 0;
        $this->bcaso_contexto->useForm($this);
        $this->bcaso_contexto->getForm();

        $cont = $this->getElement('id_contexto');

        $this->bantecedente_caso->createSubmit = 0;
        $this->bantecedente_caso->useForm($this);
        $this->bantecedente_caso->getForm();

        $this->bcaso->_do->fb_fieldsToRender
            = $this->bcaso->_do->fb_preDefOrder = array('bienes');
        $this->bcaso->createSubmit = 0;
        $this->bcaso->useForm($this);
        $this->bcaso->getForm();

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
        if (isset($_SESSION['recuperaErrorValida'])) {
            $v = $_SESSION['recuperaErrorValida'];
        } else {
            //$scc->setValue($valscc);

            $scd =& $this->getElement('id_contexto');
            $valscd = array();
            $ndo = $this->bcaso_contexto->_do;
            $ndo->find();
            while ($ndo->fetch()) {
                $valscd[] = $ndo->id_contexto;
            }
            //$scd->setValue($valscd);
            $v['id_contexto'] = $valscd;

            $sca =& $this->getElement('id_antecedente');
            $valsca = array();
            $ndo = $this->bantecedente_caso->_do;
            $ndo->find();
            while ($ndo->fetch()) {
                $valsca[] = $ndo->id_antecedente;
            }
            $v['id_antecedente'] = $valsca;
            //$sca->setValue($valsca);

            $v['bienes'] = $this->bcaso->_do->bienes;
            //$b =& $this->getElement('bienes');
            //$b->setValue($this->bcaso->_do->bienes);
        }

        establece_valores_form(
            $this, array('clasificacion',
            'id_contexto', 'id_antecedente', 'bienes'
            ), $v
        );

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
        $result = hace_consulta(
            $db, "DELETE FROM caso_contexto " .
            " WHERE id_caso='$idcaso'"
        );
        $result = hace_consulta(
            $db, "DELETE FROM antecedente_caso " .
            " WHERE id_caso='$idcaso'"
        );
    }

    /** Verifica si se violaría integridad referencial al cambiar
    * una categoria
    *
    * @param handle  &$db     Base de datos
    * @param integer $idcaso  Número de caso
    * @param string  $tv      Tipo de Violencia
    * @param integer $s       Supracategoria
    * @param integer $c       Categoria
    * @param string  $fc      Categoria por mostrar al usuario
    * @param integer $valores Valores enviados por formulario
    *
    * @return bool Verdadero si y solo cumple integridad ref.
    */
    static function integridadRef(&$db, $idcaso, $tv, $s, $c, $fc, $valores)
    {
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
        if (!$this->validate() ) {
            return false;
        }

        verifica_sin_CSRF($valores);
        if (in_array(31, $_SESSION['opciones']) &&
            !in_array(21, $_SESSION['opciones'])
        ) {
            return true;
        }


        $db = $this->iniVar();

        $idcaso = $this->bcaso->_do->id;

        // Verificamos no ir a violar integridad referencial
        // en caso de modificación

        $result = hace_consulta(
            $db, "DELETE FROM caso_contexto " .
            " WHERE id_caso='$idcaso'"
        );
        $result = hace_consulta(
            $db, "DELETE FROM antecedente_caso " .
            " WHERE id_caso='$idcaso'"
        );
        if (isset($valores['id_contexto'])) {
            foreach (var_escapa($valores['id_contexto']) as $k => $v) {
                $this->bcaso_contexto->_do->id_caso = $idcaso;
                $this->bcaso_contexto->_do->id_contexto = $v;
                $this->bcaso_contexto->_do->insert();
            }
        }

        if (isset($valores['id_antecedente'])) {
            foreach (var_escapa($valores['id_antecedente']) as $k => $v) {
                $this->bantecedente_caso->_do->id_caso = $idcaso;
                $this->bantecedente_caso->_do->id_antecedente = $v;
                $this->bantecedente_caso->_do->insert();
            }
        }
        $this->bcaso->forceQueryType(DB_DATAOBJECT_FORMBUILDER_QUERY_FORCEUPDATE);

        $ret = $this->process(
            array(&$this->bcaso, 'processForm'),
            false
        );
        caso_funcionario($_SESSION['basicos_id']);
        return  $ret;
    }

    /**
    * Retorna una cadena que identifica una categoria (con tipo de violencia
    * y supracategoria).
    *
    * @param string  $tv Tipo de violencia
    * @param integer $s  Supracategoria
    * @param integer $c  Categoria
    *
    * @return string Identificación
    */
    function cadenaDeCodcat($tv, $s, $c)
    {
        return $tv . ":" . $s . ":" . $c;
    }

    /**
    * Dada una cadena creada con cadenaDeCodcat retorna el
    * código de la categoria llenando las referencias.
    *
    * @param string  $cadena identificación retornada por cadenaDeCodcat
    * @param string  &$tv    Referencia donde llena tipo de violencia
    * @param integer &$s     Referencia para poner supracategoria
    * @param integer &$c     Referencia para poner categoria
    *
    * @return void
    */
    function codcatDeCadena($cadena, &$tv, &$s, &$c)
    {
        $t = explode(":", $cadena);
        $tv = $t[0];
        $s = $t[1];
        $c = $t[2];
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
        consulta_or_muchos($w, $t, 'caso_contexto');
        consulta_or_muchos($w, $t, 'antecedente_caso');
        $dCaso = objeto_tabla('caso');
        $dCaso->id = $idcaso;
        assert($dCaso->find() != 0);
        $dCaso->fetch();

        if (trim($dCaso->bienes) != '') {
            consulta_and(
                $db, $w, "caso.bienes", "%" . trim($dCaso->bienes) . "%",
                " ILIKE "
            );
        }
    }


    /**
     * Importa de un relato SINCODH lo relacionado con esta pestaña,
     * creando registros en la base de datos para el caso $idcaso
     *
     * @param object &$db    Conexión a base de datos
     * @param object $r      Relato en XML
     * @param int    $idcaso Número de caso que se inserta
     * @param string &$obs   Para agregar notas de conversion
     *
     * @return void
     * @see PagBaseSimple
     */
    static function importaRelato(&$db, $r, $idcaso, &$obs)
    {
        $reg = dato_basico_en_obs(
            $db, $obs, $r,
            'contexto', 'contexto', 'caso_contexto', $idcaso, '; ',
            'id_contexto'
        );
        $reg = dato_basico_en_obs(
            $db, $obs, $r,
            'antecedente', 'antecedente', 'antecedente_caso', $idcaso, '; ',
            'id_antecedente'
        );
        // bienes fue ingresado con caso
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
    static function compara(&$db, &$r, $id1, $id2, $cls = array('caso'))
    {
        //echo "PagTipoViolencia::compara(db, r, $id1, $id2, a)";
        //print_r($cls); echo "<br>";
        if ($cls == null || (count($cls) == 1 && $cls[0] == 'caso_contexto')) {
            $cls = array('Contextos' => array('caso_contexto', 'id_contexto'),
                'Antecedentes' => array('antecedente_caso', 'id_antecedente'));
        }
        //print_r($cls);
        //PagBaseSimple::compara($db, $r, $id1, $id2, array('caso'));
        foreach ($cls as $eti => $cls) {
            list($cl, $c) = $cls;
            //echo "OJO cl=$cl, c=$c<br> ";
            $v1 = $v2 = "";
            $d1 = objeto_tabla($cl);
            $d1->id_caso = $id1;
            $d1->find();
            $sep = "";
            while ($d1->fetch()) {
                //echo "d1 fetched " . $d1->$c . "<br>";
                $d = $d1->getLink($c);
                $v1 .= $sep . $d->nombre;
                $sep = ", ";
            }
            //echo "v1=$v1<br>";
            $d2 = objeto_tabla($cl);
            $d2->id_caso = $id2;
            $d2->find();
            $sep = "";
            while ($d2->fetch()) {
                $d = $d2->getLink($c);
                $v2 .= $sep . $d->nombre;
                $sep = ", ";
            }
            $vp = 1;
            if (strlen($v2) > strlen($v1)) {
                $vp =2;
            }
            if ($v1 != $v2) {
                $r[$cl . '-' . $c] = array(
                    $eti, $v1, $v2, $vp
                );
            }
        }

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
        //echo "PagTipoViolencia::mezcla(db, ["; print_r($sol);
        //echo "], $id1, $id2, $idn, [" ; print_r($a) ; echo "])<br>";

        if ($cls == 'caso_contexto') {
            $cls = array('Contextos' => array('caso_contexto', 'id_contexto'),
                'Antecedentes' => array('antecedente_caso', 'id_antecedente'));
        }
        foreach ($cls as $eti => $cls) {
            list($cl, $c) = $cls;
            //echo "OJO cl=$cl, c=$c<br> ";
            $de = objeto_tabla($cl);
            //$eti = $de->nom_tabla
            if (isset($sol[$cl][$c]) && $sol[$cl][$c] == 1) {
                //echo "OJO caso 1";
                $de->id_caso = $id1;
            } else {
                //echo "OJO caso 2";
                $de->id_caso = $id2;
            }
            $de->find();
            $lc = array();
            while ($de->fetch()) {
                //echo "OJO de->$c=" . $de->$c . "<br>";
                $lc[] = $de->$c;
            }
            foreach ($lc as $v) {
                $d = objeto_tabla($cl);
                $d->id_caso = $idn;
                $d->$c = $v;
                //echo "por insertar"; print_r($d); die("x");
                $d->insert();
            }
        }
    }

}

?>
