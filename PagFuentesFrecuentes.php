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
 * Pestaña Fuentes Frecuentes del multi-formulario capturar caso
 */

require_once 'PagBaseMultiple.php';
require_once 'DataObjects/Prensa.php';

/**
 * Página fuentes frecuentes.
 * Ver documentación de funciones en clase base.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      PagBaseMultiple
 */
class PagFuentesFrecuentes extends PagBaseMultiple
{

    /** Fuente frecuente asociada al caso, que se está consultando */
    var $bescrito_caso;

    var $titulo = 'Fuentes Frecuentes';

    var $pref = "ff";

    var $nuevaCopia = false;

    var $clase_modelo = 'escrito_caso';

    /**
     * Pone en null variables asociadas a tablas de la pestaña.
     *
     * @return null
     */
    function nullVar()
    {
        $this->bescrito_caso = null;
    }

    /**
     * Retorna una identificación del registro actual.
     *
     * @return string Identifación
     */
    function copiaId()
    {
        $r = $this->bescrito_caso->_do->id_caso.":" .
            $this->bescrito_caso->_do->id_prensa.":" .
            $this->bescrito_caso->_do->fecha;
        return  $r;
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
        if ($this->bescrito_caso->_do->id_prensa != null) {
            $this->bescrito_caso->_do->delete();
            $_SESSION['ff_total']--;
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
        $do =& objeto_tabla('escrito_caso');
        $db =& $do->getDatabaseConnection();
        $idcaso =& $_SESSION['basicos_id'];
        if (!isset($idcaso) || $idcaso == null) {
            die("Bug: idcaso no debería ser null");
        }
        $do->id_caso = $idcaso;
        $result = hace_consulta(
            $db, "SELECT id_prensa " .
            " FROM escrito_caso, prensa " .
            " WHERE id_caso='$idcaso' AND id_prensa=id " .
            " ORDER BY nombre;"
        );
        $row = array();
        $idp = array();
        $tot = 0;
        while ($result->fetchInto($row)) {
            $idp[] = $row[0];
            $tot++;
        }
        $_SESSION['ff_total'] = $tot;
        if ($_SESSION['ff_pag'] < 0 || $_SESSION['ff_pag'] >= $tot) {
            $do->id_prensa = null;
        } else {
            $do->id_prensa = $idp[$_SESSION['ff_pag']];
            $do->find();
            $do->fetch();
        }

        $this->bescrito_caso =& DB_DataObject_FormBuilder::create(
            $do,
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
    function PagFuentesFrecuentes($nomForma)
    {
        parent::PagBaseMultiple($nomForma);

        $this->titulo = _('Fuentes Frecuentes');
        $this->tcorto = _('Fuente');

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
        $GLOBALS['fechaPuedeSerVacia'] = isset($_SESSION['forma_modo'])
            && $_SESSION['forma_modo'] == 'busqueda';

        $this->bescrito_caso->createSubmit = 0;
        $this->bescrito_caso->useForm($this);
        $this->bescrito_caso->getForm();

        $this->registerRule(
            'frecuenteposterior', 'function', 'frecposterior',
            'PagFuentesFrecuentes'
        );
        $this->addRule(
            'fecha',
            _('La fecha de la fuente debe ser posterior a la del caso'),
            'frecuenteposterior', null, 'client'
        );

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
            establece_valores_form(
                $this,
                $this->bescrito_caso->_do->fb_fieldsToRender,
                $_SESSION['recuperaErrorValida']
            );
            unset($_SESSION['recuperaErrorValida']);
        } else {
            foreach ($this->bescrito_caso->_do->fb_fieldsToRender as $c) {
                $cq = $this->getElement($c);
                if (isset($this->bescrito_caso->_do->$c)) {
                    $cq->setValue($this->bescrito_caso->_do->$c);
                }
            }
        }
        if (isset($_SESSION['forma_modo'])
            && $_SESSION['forma_modo'] == 'busqueda'
        ) {
            $f = $this->getElement('fecha');
            $da = $f->getValue();
            $y = date('Y');
            $m = date('m');
            $d = date('d');
            if ($da['Y'][0] == ($GLOBALS['anio_min'] - 1)
                || ($y == $da['Y'][0] && $d == $da['d'][0] && $m == $da['m'][0])
            ) {
                $f->setValue(
                    array('d' => array('0' => ''),
                    'm' => array('0' => ''),
                    'Y' => array('0' => '')
                    )
                );
            }
        }


        if (isset($_SESSION['nuevo_copia_id'])
            && strstr($_SESSION['nuevo_copia_id'], ":") != false
        ) {
            list($idc, $idp, $fecha)
                = explode(':', $_SESSION['nuevo_copia_id']);
            unset($_SESSION['nuevo_copia_id']);
            $d =& objeto_tabla('escrito_caso');
            $d->id_caso = $idc;
            $d->id_prensa = $idp;
            $d->fecha = $fecha;
            $d->find();
            $d->fetch();
            foreach ($d->fb_fieldsToRender as $c) {
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
        hace_consulta($db, "DELETE FROM escrito_caso WHERE id_caso='$idcaso'");
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
        $es_vacio = ($valores['id_prensa'] == null
                || $valores['id_prensa'] == ''
        )
            && ($valores['ubicacion'] == null || $valores['ubicacion'] == '')
            && ($valores['clasificacion'] == null
                || $valores['clasificacion'] == ''
            )
            && ($valores['ubicacion_fisica'] == null
                || $valores['ubicacion_fisica'] == ''
                )
        ;

        if ($es_vacio) {
            return true;
        }
        if (!$this->validate()) {
            return false;
        }
        verifica_sin_CSRF($valores);

        $db = $this->iniVar();
        $do =& objeto_tabla('caso');
        $do->id = $this->bescrito_caso->_do->id_caso;
        $do->find();
        $do->fetch();
        $df = call_user_func(
            $this->bescrito_caso->dateToDatabaseCallback,
            var_escapa($valores['fecha'], $db, 20)
        );
        $nobusca = !isset($_SESSION['forma_modo'])
            || $_SESSION['forma_modo'] != 'busqueda';
        /* No funcionan reglas de validación de QuickForm
           porque no está construido el formulario cuando pasa
           por esta función.  $this->validate encuentra que
           $this->_rules es vacio */
        if ($nobusca && strtotime($df) < strtotime($do->fecha)) {
            error_valida(
                _('Fecha de fuente no puede ser anterior a la del caso'),
                $valores
            );
            return false;
        }

        if ($this->bescrito_caso->_do->id_prensa != null) {
            $this->bescrito_caso->_do->delete();
            $_SESSION['ff_total']--;
        }
        $this->bescrito_caso->forceQueryType(
            DB_DATAOBJECT_FORMBUILDER_QUERY_FORCEINSERT
        );

        if (isset($_SESSION['forma_modo'])
            && $_SESSION['forma_modo'] == 'busqueda'
            && isset($valores['id_caso'])
            && $valores['id_caso'] == $GLOBALS['idbus']
        ) {
            if ($df == "0000-00-00") {
                $valores['fecha']['d'] = 1;
                $valores['fecha']['m'] = 1;
                $valores['fecha']['Y'] = $GLOBALS['anio_min'] - 1;
            }
            if ($valores['id_prensa'] == '') {
                $valores['id_prensa'] = DataObjects_Prensa::id_sinInfo();
            }
        }

        $ret = $this->process(
            array(&$this->bescrito_caso, 'processForm'),
            false
        );
        $_SESSION['ff_total']++;

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
        prepara_consulta_gen(
            $w, $t, $idcaso,
            'escrito_caso', _('Prensa'), 'id_prensa', false
        );
        // echo "OJO w=$w";
    }

    /**
     * Busca una fuente frecuente por nombre y la inserta en un caso
     * con los datos que esta función recibe.
     *
     * @param object &$db    Conexión a base de datos
     * @param intger $idcaso Número de caso al que se añade fuente
     * @param string $nomf   Nombre de la fuente
     * @param string $fecha  Fecha de fuente
     * @param string $ubif   Ubicación física
     * @param string $ubi    Ubicación
     * @param string $cla    Clasificación
     * @param string &$obs   Colchon para agregar notas de conversion
     *
     * @return integer Id de prensa insertada o -1 si no pudo
     */
    static function busca_inserta(&$db, $idcaso, $nomf, $fecha,
        $ubif, $ubi, $cla, &$obs
    ) {
        $rp = hace_consulta(
            $db, "SELECT id FROM prensa WHERE " .
            "nombre ILIKE '$nomf'"
        );
        $rows = array();
        $nr = $rp->numRows();
        if ($rp->fetchInto($row)) {
            $idprensa = $row[0];
            if ($rp->fetchInto($row)) {
                repObs(
                    "Hay $nr fuentes frecuentes con nombre como " .
                    $fuente->nombre_fuente .
                    ", escogido el primero\n", $obs
                );
            }
            if (!empty($fecha)) {
                $escritocaso = objeto_tabla('escrito_caso');
                $escritocaso->id_caso = $idcaso;
                $escritocaso->id_prensa = $idprensa;
                $escritocaso->fecha = $fecha;
                if (!empty($ubif)) {
                    $escritocaso->ubicacion_fisica = $ubif;
                }
                $escritocaso->ubicacion = $ubi;
                $escritocaso->clasificacion = $cla;
                $escritocaso->insert();
                return $escritocaso->id_prensa;
            }
        }

        return -1;
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
        foreach ($r->fuente as $fuente) {
            $idprensa = null;
            $nomf = utf8_decode($fuente->nombre_fuente);
            if (empty($fuente->fecha_fuente)) {
                repObs(
                    _("No se incluyó fuente sin fecha: ") .
                    $fuente->asXML(), $obs
                );
            } else if (empty($fuente->nombre_fuente)) {
                repObs(
                    _("No se incluyó fuente sin nombre: ") .
                    $fuente->asXML(), $obs
                );
            } else {
                $fecha = conv_fecha($fuente->fecha_fuente, $obs);
                PagFuentesFrecuentes::busca_inserta(
                    $db, $idcaso, $nomf, $fecha,
                    utf8_decode((string)$fuente->ubicacion_fuente),
                    dato_en_obs($fuente, 'ubicacion'),
                    dato_en_obs($fuente, 'clasificacion'),
                    $ubif, $ubi, $cla, $obs
                );
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
    static function compara(&$db, &$r, $id1, $id2, $cls = array('caso'))
    {
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
        PagOtrasFuentes::mezcla(
            $db, $sol, $id1, $id2, $idn, array('escrito_caso')
        );
    }

}

?>
