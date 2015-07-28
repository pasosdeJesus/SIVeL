<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Base para página simple del multi-formulario para capturar caso
 * (captura_caso.php).
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2005 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

/**
 * Base para página simple del multi-formulario para capturar caso
 */

require_once 'HTML/QuickForm/Page.php';
require_once 'aut.php';
require_once 'misc.php';
require_once $_SESSION['dirsitio'] . '/conf.php';

/**
 * Clase base para subformularios de una sóla página
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
abstract class PagBaseSimple extends HTML_QuickForm_Page
{

    /** Titulo que aparecerá en formulario */
    var $titulo = '';

    /** Nombre de una clase DataObject característica de este formulario */
    var $clase_modelo = 'caso';

    /**
     * Definimos variables para subformularios (a su vez descendientes de
     * DB_DataObject_FormBuilder).
     * Convención sugerida: que comienzan con la letra b
     */
    var $bcaso = null;

    /**
     * Mensaje de campos requeridos
     **/
    var $mreq = '<span style="font-size:80%; color:#ff0000;">*</span>
        <span style = "font-size:80%;"> marca un campo requerido</span>';


    /**
     * Inicializa variables de la clase extrayendo datos de la base.
     * Puede mejorarse manteniendo información en var. de sesión
     * (actualizada con operaciones) para que no tener que consultar
     * la base de datos siempre.
     *
     * @param array $apar Arreglo de parametros. Consta de
     *   0->bool $cargaCaso  decide si se carga o no el caso de la B.D
     *   1->bool $retArreglo indica si debe retornar un arreglo con
     *      base de datos, objeto dcaso e identificación o sólo B.D
     *
     * @return mixed Puede ser bien conexión a base de daotos o bien
     * un arreglo con base, objeto dcaso e identificación de caso (depende
     * del parámetro $retArreglo
     */
    function iniVar($apar = null)
    {
        $cargaCaso = true;
        $retArreglo = true;
        if (isset($apar) && count($apar) == 2) {
            $cargaCaso = $apar[0];
            $retArreglo = $apar[1];
        } else if (isset($apar) && count($apar) == 1) {
            list($cargaCaso) = each($apar);
        }
        $dcaso = objeto_tabla('caso');
        sin_error_pear($dcaso);
        $db =& $dcaso->getDatabaseConnection();

        $idcaso = null;
        if ($cargaCaso && isset($_SESSION['basicos_id'])) {
            $idcaso =& $_SESSION['basicos_id'];
            if (!isset($idcaso) || $idcaso == null) {
                die(_("Bug: idcaso no debería ser null"));
            }
            $dcaso->id = $idcaso;
            if (($e = $dcaso->find()) != 1
                && $idcaso != $GLOBALS['idbus']
            ) {
                die(sprintf(
                    _("Se esperaba un sólo registro, pero se encontraron %s."),
                    $e
                ));
            }
            $dcaso->fetch();
        }

        $this->bcaso =& DB_DataObject_FormBuilder::create(
            $dcaso,
            array(
                'requiredRuleMessage' => $GLOBALS['mreglareq'],
                'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );

        if ($retArreglo) {
            return array($db, $dcaso, $idcaso);
        } else {
            return $db;
        }
    }


    /** Constructora
     *
     * @param string $nomForma es nombre del formulario
     *
     * @return void
     */
    function PagBaseSimple($nomForma)
    {
        $this->HTML_QuickForm_Page($nomForma, 'post', '_self', null);
        $this->setRequiredNote($this->mreq);

        if (isset($_SESSION['forma_modo'])
            && $_SESSION['forma_modo'] == 'busqueda'
        ) {
            $this->addAction('busqueda', new Busqueda());
        }
    }


    /**
     * Agrega elementos particulares del formulario
     *
     * @param handle  &$db    Conexión a base de datos
     * @param integer $idcaso Identificación del caso
     *
     * @return void
     */
    abstract function formularioAgrega(&$db, $idcaso);


    /**
    * Establece valores por defecto cuando se requiere para presentar
    * en el formulario.
    *
    * @param handle  &$db    Conexión a base de datos
    * @param integer $idcaso Identificación del caso
    *
    * @return void
    */
    abstract function formularioValores(&$db, $idcaso);


    /**
     * Construye elementos del formulario
     *
     * @return object Formulario
     */
    function buildForm()
    {
        $this->_formBuilt = true;
        $this->_submitValues = array();
        $this->_defaultValues = array();

        $cm = "b" . $this->clase_modelo;
        if (!isset($cm) || $cm == null || !isset($this->$cm)
            || $this->$cm == null
        ) {
            $db = $this->iniVar();
        } else {
            $db = $this->$cm->_do->getDatabaseConnection();
            if (PEAR::isError($db)) {
                die($cm . " - " . $db->getMessage());
            }
        }
        $idcaso = $_SESSION['basicos_id'];
        $this->controller->crea_tabuladores($this, array('class' => 'flat'));

        $comp = isset($_SESSION['forma_modo'])
            && $_SESSION['forma_modo'] == 'busqueda'
            && $idcaso == $GLOBALS['idbus'] ?
            _('Consulta') : _('Caso') . ' ' . $idcaso;

        $e =& $this->addElement(
            'header', null, '<table width = "100%">' .
            '<th align = "left">' . $this->titulo .
            '</th><th algin = "right">' .
            $comp . "</th></table>"
        );

        $this->formularioAgrega($db, $idcaso);

        $this->controller->creaBotonesEstandar($this);

        $this->setDefaultAction('siguiente');

        // OJO Mejor cambiar valores después de crear formulario, si
        // se hace antes molesta.
        $this->formularioValores($db, $idcaso);
    }


    /**
     * Elimina de la base, datos asociados a un caso y presentados por este
     * formulario.
     *
     * @param handle  &$db    conexión a base de datos
     * @param integer $idcaso Número de caso
     *
     * @return void
     */
    static function eliminaDep(&$db, $idcaso)
    {
    }



    /**
     * Verifica y salva datos.
     * Típicamente debe validar datos, preprocesar de requerirse,
     * procesar con función process y finalmente registrar evento con función
     * caso_usuario
     *
     * @param array &$valores Valores enviados por el formulario.
     *
     * @return void
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
        // Verifica si es vacio

        if (!$this->validate()) {
            return false;
        }
        verifica_sin_CSRF($valores);
        if (in_array(31, $_SESSION['opciones'])
            && !in_array(21, $_SESSION['opciones'])
        ) {
            return true;
        }


        // Reglas de integridad referencial

        $db = $this->iniVar();

        // Preprocesamiento

        // Procesamiento
        $ret = $this->process(array(&$this->bcaso, 'processForm'), false);
        if (PEAR::isError($ret)) {
            die($ret->getMessage());
        }

        // Otros

        caso_usuario($_SESSION['basicos_id']);
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
    abstract function datosBusqueda(&$w, &$t, &$db, $idcaso, &$subcons);


    /**
     * Llamada cuando se inicia captura de ficha
     *
     * @return void
     */
    static function iniCaptura()
    {
    }


    /**
     * Llamada en cada inicio de una consulta ResConsulta.
     * Hace posible nuevos tipos de consulta.
     *
     * @param string  $mostrar  Forma de mostrar consulta
     * @param string  &$renglon Llena como iniciar consulta
     * @param string  &$rtexto  Llena texto inicial de consula
     * @param integer $tot      Total de casos en consulta
     *
     * @return void
     */
    static function resConsultaInicio($mostrar, &$renglon, &$rtexto, $tot = 0)
    {
    }


    /**
     * Llamada para mostrar un registro en ResConsulta.
     * Hace posible nuevos tipos de consulta.
     *
     * @param object  &$db       Conexión a B.D
     * @param string  $mostrar   Forma de mostrar consulta
     * @param int     $idcaso    Código de caso
     * @param array   $campos    Campos por mostrar
     * @param array   $conv      Conversiones
     * @param array   $sal       Para conversiones con $conv
     * @param boolean $retroalim Con boton de retroalimentación
     *
     * @return string Fila en HTML
     */
    static function resConsultaRegistro(&$db, $mostrar, $idcaso, $campos,
        $conv, $sal, $retroalim
    ) {
    }

    /**
     * Llamada al final de una consulta ResConsulta.
     * Hace posible nuevos tipos de consulta.
     *
     * @param string $mostrar Forma de mostrar consulta
     *
     * @return void
     */
    static function resConsultaFinal($mostrar)
    {
    }


    /**
     * Llamada cuando se inicia presentación en formato de tabla.
     * Da oportunidad por ejemplo de inicializar variables.
     *
     * @param string $cc Campo que se muestra
     *
     * @return void
     */
    static function resConsultaInicioTabla($cc)
    {
    }

    /**
     * Llamada desde la función que muestra cada fila de la tabla en
     * ResConsulta.
     * Hace posible modificar la tabla.
     *
     * @param object &$db    Base de datos
     * @param string $cc     Campo que se procesa
     * @param int    $idcaso Número de caso
     *
     * @return Cadena por presentar
     */
    static function resConsultaFilaTabla(&$db, $cc, $idcaso)
    {
    }

    /**
     * Llamada desde consulta web en formato de tabla al terminar tabla.
     *
     * @param string $cc Campo que se procesa
     *
     * @return Cadena por presentar
     */
    static function resConsultaFinaltablaHtml($cc)
    {
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
     * @param array  &$oconv    Otros campos por incluir en consulta
     *
     * @return void
     */
    static function consultaWebCreaConsulta(&$db, $mostrar, &$where,
        &$tablas, &$pOrdenar, &$campos, &$oconv
    ) {
    }


    /**
     * Llamada desde consulta_web al generar formulario en porción
     * `Forma de presentación'
     *
     * @param string $mostrar  Forma de mostrar consulta
     * @param array  $opciones Opciones de menu del usuario
     * @param object &$forma   Formulario
     * @param array  &$ae      Grupo de elementos que conforman Forma de pres.
     * @param array  &$t       Si está marcado lo pone en el elemento creado
     *
     * @return void
     */
    static function consultaWebFormaPresentacion($mostrar, $opciones,
        &$forma, &$ae, &$t
    ) {
    }


    /**
     * Llamada desde consulta_web para generar formulario en porción
     * 'Detalles de la presentación'
     *
     * @param string $mostrar  Forma de mostrar consulta
     * @param array  $opciones Opciones de menu del usuario
     * @param object &$forma   Formulario
     * @param array  &$opch    Grupo de elementos que conforman Forma de pres.
     *
     * @return void
     */
    static function consultaWebDetalle($mostrar, $opciones,
        &$forma, &$opch
    ) {
    }


    /**
     * Llamada desde consulta_web para completar consulta poniendo una
     * política de ordenamiento
     *
     * @param object &$q       Consulta por modificar
     * @param string $pOrdenar Criterio de ordenamiento
     *
     * @return void
     */
    static function consultaWebOrden(&$q, $pOrdenar)
    {
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
    }

    /**
     * Llamada desde consolidado para completar consulta SQL en caso
     *
     * @param object &$db     Conexión a B.D
     * @param string &$where  Consulta SQL por completar
     * @param string &$tablas Tablas incluidas en consulta
     *
     * @return void
     */
    static function consolidadoCreaConsulta(&$db, &$where, &$tablas)
    {
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
    }


    /**
     * Llamada para completar registro por mostrar en Reporte Revista.
     *
     * @param object &$db    Conexión a B.D
     * @param array  $campos Campos por mostrar
     * @param int    $idcaso Código de caso
     *
     * @return void
     */
    static function reporteRevistaRegistroHtml(&$db, $campos, $idcaso)
    {
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
    }

    /**
     * Llamada para inicializar variables globales como cw_ncampos
     *
     * @return void
     */
    static function act_globales()
    {
    }

    /**
     * Llamada para crear encabezado en Javascript
     *
     * @param string &$js colchon de funciones en javascript
     *
     * @return void
     */
    static function encJavascript(&$js)
    {
    }

    /**
     * Importa de un relato SINCODH lo relacionado con esta pestaña,
     * creando registros en la base de datos para el caso $idcaso
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
    }

    /**
     * Exporta a relato lo relacionado con esta pestaña, en el caso
     * de módulos como observaciones al final del caso.
     *
     * @param object &$db   Conexión a base de datos
     * @param int    $dcaso Objeto Dataobject con el caso que se exporta.
     * @param string &$r    XML generado al que debe concatenarse al final
     *                      lo de esta pestaña.
     *
     * @return void
     */
    static function aRelato(&$db, $dcaso, &$r)
    {
    }
}

?>
