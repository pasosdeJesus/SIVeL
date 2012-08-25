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
 * @copyright 2006 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

require_once 'PagBaseSimple.php';
require_once 'Etiquetacaso.php';
require_once 'misc.php';


/**
 * Etiquetas
 * Ver documentación de funciones en clase base.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see PagBaseSimple
 */
class PagEtiquetas extends PagBaseSimple
{

    var $betiquetacaso;

    var $clase_modelo = 'etiquetacaso';

    var $titulo = 'Etiquetas';

    /**
     * Pone en null variables asociadas a tablas de la pestaña.
     *
     * @return null
     */
    function nullVar()
    {
        $this->betiquetacaso = null;
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
        list($db, $dcaso, $idcaso) = parent::iniVar(array(true, true));

        $detiquetacaso =& objeto_tabla('etiquetacaso');
        $detiquetacaso->id_caso = $idcaso;
        $detiquetacaso->find();
        $this->betiquetacaso=& DB_DataObject_FormBuilder::create(
            $detiquetacaso,
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
    function PagEtiquetas($nomForma)
    {
        parent::PagBaseSimple($nomForma, $this->titulo);
        $this->titulo  = _('Etiquetas');
        $this->tcorto  = _('Etiquetas');

        $this->addAction('process', new Terminar());
/*        if (isset($_SESSION['forma_modo'])
            && $_SESSION['forma_modo'] == 'busqueda') {
        } */
        $this->addAction('siguiente', new Siguiente());
        $this->addAction('anterior', new Anterior());
        $this->addAction('agregarEtiqueta', new AgregarEtiqueta());
        $this->addAction('eliminaest', new EliminaEst ());
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
        $this->betiquetacaso->createSubmit = 0;
        $this->betiquetacaso->useForm($this);
        $f =& $this->betiquetacaso->getForm($this);

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
        /*if (!isset($_SESSION['forma_modo'])
        || $_SESSION['forma_modo'] != 'busqueda') {
            $this->controller->deshabilitaBotones($this, array('siguiente'));
        } */
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
             $db, "DELETE FROM etiquetacaso WHERE " .
            "id_caso='$idcaso'"
        );
     }

     function procesa(&$valores, $agregaEtiqueta = false)
     {
         $ret = true;
         if (!$this->validate() ) {
             return false;
         }
         if ($agregaEtiqueta
         && (!isset($valores['fetiqueta']) || $valores['fetiqueta'] == '')
         ) {
             error_valida(_('Faltó fecha y/o etiqueta'), $valores);
             return false;
         }

         $db = $this->iniVar();

         $idcaso = $_SESSION['basicos_id'];
         // Procesamiento
         if ($agregaEtiqueta) {
             $this->betiquetacaso->_do->id_caso = (int)$idcaso;
             $this->betiquetacaso->_do->id_etiqueta =
                 (int)$valores['fetiqueta'];
             $this->betiquetacaso->_do->id_funcionario =
                 (int)$_SESSION['id_funcionario'];
             $this->betiquetacaso->_do->fecha = date('Y-m-d');
             $this->betiquetacaso->_do->observaciones =
                 var_escapa($valores['fobservaciones'], $db);
             //print_r($this->betiquetacaso->_do);
             $r = $this->betiquetacaso->_do->insert();
             sin_error_pear($r, _('No pudo insertar en base.'));
             $agregaEtiqueta = false;
         }

         // Actualizamos observaciones
         foreach ($valores as $i => $v) {
             if (substr($i, 0, 5)=='fobs_') {
                 $po = explode('_', $i);
                 $dec =& objeto_tabla('etiquetacaso');
                 $dec->id_caso = $idcaso;
                 $dec->id_etiqueta = $po[2];
                 $dec->id_funcionario = $po[3];
                 $dec->fecha = $po[4];
                 $dec->observaciones = $v;
                 $dec->update();
             }
         }

         funcionario_caso($_SESSION['basicos_id']);
         return  $ret;
     }


     /**
      * Llena una consulta de acuerdo a datos del formulario cuando
      * está en modo busqueda.
      * <b>SELECT caso.id FROM $t WHERE $w</b>
      *
      * @param string &$w       Condiciones de consulta exterior
      * @param string &$t       Tablas de consulta exterior
      * @param object &$db      Conexión a base de datos
      * @param object $idcaso   Identificación de caso
      * @param string &$subcons Consulta interior (si no es vacía hacer UNION)
      *
      * @return void
      */
     function datosBusqueda(&$w, &$t, &$db, $idcaso, &$subcons)
     {
         $duc=& objeto_tabla('etiquetacaso');
         $duc->id_caso = $idcaso;
         if ($duc->find()>0) {
             $t .= ", etiquetacaso, etiqueta";
             consulta_and_sinap(
                 $w, "etiquetacaso.id_caso", "caso.id", "=", "AND"
             );
             consulta_and_sinap(
                 $w, "etiquetacaso.id_etiqueta",
                 "etiqueta.id", "=", "AND"
             );
             $w3="";
             while ($duc->fetch()) {
                 $w2="";
                 if (isset($duc->anotacion) && $duc->anotacion != '') {
                     consulta_and(
                         $db, $w2, "etiquetacaso.id_funcionario",
                         $duc->id_anotacion, "=", "AND"
                     );
                 }
                 if (isset($duc->ubicacion_fisica)
                     && $duc->ubicacion_fisica != ''
                     )
                 {
                     consulta_and(
                         $db, $w2, "etiquetacaso.fecha",
                         $duc->fecha, "=", "AND"
                     );
                 }
                 if (isset($duc->observaciones) && $duc->observaciones != '') {
                     consulta_and(
                         $db, $w2, "etiquetacaso.observaciones",
                         $duc->observaciones, "=", "AND"
                     );
                 }

                 $du=& objeto_tabla('etiqueta');
                 $du->get($duc->id_etiqueta);
                 consulta_and(
                     $db, $w2, "etiqueta.nombre",
                     $du->nombre, '=', 'AND'
                 );
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
      * Llamada cuando se inicia captura de ficha
      *
      * @return void
      */
     static function iniCaptura()
     {
         if (isset($_REQUEST['eliminaest'])) {
             assert($_REQUEST['eliminaest'] != null);
             $de=& objeto_tabla('etiquetacaso');
             list($de->id_caso, $de->id_etiqueta, $de->id_funcionario,
                 $de->fecha
             ) = explode(':', var_escapa($_REQUEST['eliminaest']));
             $de->delete();
         }
     }


     /**
      * Llamada para inicializar variables globales como cw_ncampos
      *
      * @return void
      */
     static function actGlobales()
     {
         html_menu_agrega_submenu(
             $GLOBALS['menu_tablas_basicas'],
             _('Información caso'), _('Etiquetas para un caso'),
             'etiqueta', null
         );
     }


     /**
      * Llamada desde formulario de estadísticas individuales para
      * dar la posibilidad de añadir elementos.
      *
      * @param object &$db   Conexión a B.D
      * @param object &$form Formulario
      *
      * @return Cadena por presentar
      */
     static function estadisticasIndFiltro(&$db, &$form)
     {
         sin_error_pear($db);
         $gr = array();

         $sel =& $form->createElement(
             'select',
             'critetiqueta', _('Criterio Etiqueta')
         );
         $sel->loadArray(array('0' => _('tiene'), '1' => _('no tiene')));
         $gr[] = $sel;

         $sel =& $form->createElement(
             'select',
             'poretiqueta', _('Etiqueta')
         );
         if (!PEAR::isError($sel)) {
             $options = array('' => '') +
                 htmlentities_array(
                     $db->getAssoc(
                         "SELECT id, nombre FROM etiqueta
                         ORDER BY nombre"
                     )
                 );
             $sel->loadArray($options);
         }
         $gr[] = $sel;
         $form->addGroup($gr, null, _('Etiqueta'), '&nbsp;', false);
     }


     /**
      * Llamada desde consulta web durante construcción de formulario para
      * dar la posibilidad de añadir elementos.
      *
      * @param object &$db   Conexión a B.D
      * @param object &$form Formulario
      *
      * @return Cadena por presentar
      */
     static function consultaWebFiltro(&$db, &$form)
     {
         PagEtiquetas::estadisticasIndFiltro($db, $form);
     }

     /**
      * Llamada desde estadisticas.php para completar primera consulta SQL
      * que genera estadísticas
      *
      * @param object &$db     Conexión a B.D
      * @param string &$where  Consulta SQL que se completa
      * @param string &$tablas Tablas incluidas en consulta
      *
      * @return void Modifica $tablas y $where
      */
     static function estadisticasIndCreaConsulta(&$db, &$where, &$tablas)
     {
         $pEtiqueta  = var_req_escapa('poretiqueta', $db, 32);
         $pCon = (int)var_req_escapa('critetiqueta', $db, 32);
         if ($pEtiqueta != "") {
             if ($pCon === 0) {
                 agrega_tabla($tablas, 'etiquetacaso');
                 consulta_and_sinap($where, "etiquetacaso.id_caso", "caso.id");
                 consulta_and(
                     $db, $where, "etiquetacaso.id_etiqueta", $pEtiqueta, '='
                 );
             } else {

                 consulta_and_sinap(
                     $where, "caso.id",
                     "(SELECT id_caso FROM etiquetacaso
                     WHERE id_etiqueta = '$pEtiqueta')",
                     ' NOT IN '
                 );
                 //var_dump($where); die("x");
             }
         }
     }


     /**
      * Llamada desde consulta_web para completar consulta SQL en caso
      *
      * @param object &$db       Conexión a B.D
      * @param string $mostrar   Forma de mostrar consulta
      * @param string &$where    Consulta SQL por completar
      * @param string &$tablas   Tablas incluidas en consulta
      * @param array  &$pOrdenar Forma de ordenamiento
      * @param array  &$campos   Campos por mostrar
      *
      * @return void
      */
     static function consultaWebCreaConsulta(&$db, $mostrar, &$where, &$tablas,
         &$pOrdenar, &$campos)
     {
         PagEtiquetas::estadisticasIndCreaConsulta($db, $where, $tablas);
     }



    /**
     * Llamada desde consolidado durante construcción de formulario para
     * dar la posibilidad de añadir elementos.
     *
     * @param object &$db   Conexión a B.D
     * @param object &$form Formulario
     *
     * @return Cadena por presentar
     */
    static function consolidadoFiltro(&$db, &$form)
    {
         PagEtiquetas::estadisticasIndFiltro($db, $form);
    }

    /**
     * Llamada desde consolidado para completar consulta SQL en caso
     *
     * @param object &$db       Conexión a B.D
     * @param string &$where    Consulta SQL por completar
     * @param string &$tablas   Tablas incluidas en consulta
     *
     * @return void
     */
    static function consolidadoCreaConsulta(&$db, &$where, &$tablas)
    {
         PagEtiquetas::estadisticasIndCreaConsulta($db, $where, $tablas);
    }

     /**
      * Llamada para completar registro por mostrar en Reporte General.
      *
      * @param object &$db    Conexión a B.D
      * @param array  $campos Campos por mostrar
      * @param int    $idcaso Código de caso
      *
      * @return void
      */
     static function reporteGeneralRegistroHtml(&$db, $campos, $idcaso)
     {
         $idcaso = (int)$idcaso;
         $r = "";
         if (isset($campos['m_fuentes'])) {
             $c = hace_consulta($db, "SELECT nombre, etiquetacaso.observaciones
                 FROM etiqueta, etiquetacaso
                 WHERE etiqueta.id = etiquetacaso.id_etiqueta
                 AND etiquetacaso.id_caso = '$idcaso'"
             );
             $reg = array();
             $sep = _("Etiquetas") . ": \n   ";
             while ($c->fetchInto($reg)) {
                 $r .= $sep . trim($reg[0]);
                 if (trim($reg[1]) != "") {
                     $r .= ": " . $reg[1];
                 }
                 $sep = "\n   ";
             }
         }
         return $r;
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
        PagBaseMultiple::compara(
            $db, $r, $id1, $id2,
            array('Etiquetas' => array('etiquetacaso', 'id_etiqueta'))
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
        $e1 = isset($sol['etiquetacaso']['id_etiqueta'])
            && $sol['etiquetacaso']['id_etiqueta'] == 1;
        if (($e1 && $idn != $id1) || (!$e1 && $idn != $id2)) {
            PagEtiquetas::eliminaDep($db, $idn);
            PagBaseMultiple::mezcla(
                $db, $sol, $id1, $id2, $idn,
                array('Etiquetas' => array('etiquetacaso', 'id_etiqueta'))
            );
        }
    }


}
?>
