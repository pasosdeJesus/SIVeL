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
require_once 'DataObjects/Ffrecuente.php';

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
    var $bcaso_ffrecuente;

    var $titulo = 'Fuentes Frecuentes';

    var $pref = "ff";

    var $nuevaCopia = false;

    var $clase_modelo = 'caso_ffrecuente';

    /**
     * Pone en null variables asociadas a tablas de la pestaña.
     *
     * @return null
     */
    function nullVar()
    {
        $this->bcaso_ffrecuente = null;
    }

    /**
     * Retorna una identificación del registro actual.
     *
     * @return string Identifación
     */
    function copiaId()
    {
        $r = $this->bcaso_ffrecuente->_do->id_caso.":" .
            $this->bcaso_ffrecuente->_do->id_ffrecuente.":" .
            $this->bcaso_ffrecuente->_do->fecha;
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
        if ($this->bcaso_ffrecuente->_do->id_ffrecuente != null) {
            $this->bcaso_ffrecuente->_do->delete();
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
        $do =& objeto_tabla('caso_ffrecuente');
        $db =& $do->getDatabaseConnection();
        $idcaso =& $_SESSION['basicos_id'];
        if (!isset($idcaso) || $idcaso == null) {
            die("Bug: idcaso no debería ser null");
        }
        $do->id_caso = $idcaso;
        $result = hace_consulta(
            $db, "SELECT id_ffrecuente " .
            " FROM caso_ffrecuente, ffrecuente " .
            " WHERE id_caso='$idcaso' AND id_ffrecuente=id " .
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
            $do->id_ffrecuente = null;
        } else {
            $do->id_ffrecuente = $idp[$_SESSION['ff_pag']];
            $do->find();
            $do->fetch();
        }

        $this->bcaso_ffrecuente =& DB_DataObject_FormBuilder::create(
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
        if (isset($GLOBALS['etiqueta']['Fuentes Frecuentes'])) {
            $this->titulo = $GLOBALS['etiqueta']['Fuentes Frecuentes'];
            $this->tcorto = $GLOBALS['etiqueta']['Fuentes Frecuentes'];
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
        $GLOBALS['fechaPuedeSerVacia'] = isset($_SESSION['forma_modo'])
            && $_SESSION['forma_modo'] == 'busqueda';

        $this->bcaso_ffrecuente->createSubmit = 0;
        $this->bcaso_ffrecuente->useForm($this);
        $this->bcaso_ffrecuente->getForm();

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
                $this->bcaso_ffrecuente->_do->fb_fieldsToRender,
                $_SESSION['recuperaErrorValida']
            );
            unset($_SESSION['recuperaErrorValida']);
        } else {
            foreach ($this->bcaso_ffrecuente->_do->fb_fieldsToRender as $c) {
                $cq = $this->getElement($c);
                if (isset($this->bcaso_ffrecuente->_do->$c)) {
                    $cq->setValue($this->bcaso_ffrecuente->_do->$c);
                }
            }
        }
        if (isset($_SESSION['forma_modo'])
            && $_SESSION['forma_modo'] == 'busqueda'
        ) {
            $f = $this->getElement('fecha');
            $da = $f->getValue();
            $y = @date('Y');
            $m = @date('m');
            $d = @date('d');
            $lm = isset($da['m']) ? 'm' : 'M';
            if ($da['Y'][0] == ($GLOBALS['anio_min'] - 1)
                || ($y == $da['Y'][0] && $d == $da['d'][0]
                && $m == $da[$lm][0])
            ) {
                $f->setValue(
                    array('d' => '', // array('0' => ''),
                    $lm => '',
                    'Y' => ''
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
            $d =& objeto_tabla('caso_ffrecuente');
            $d->id_caso = $idc;
            $d->id_ffrecuente = $idp;
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
        hace_consulta($db, "DELETE FROM caso_ffrecuente WHERE id_caso='$idcaso'");
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
        $es_vacio = ($valores['id_ffrecuente'] == null
                || $valores['id_ffrecuente'] == ''
        )
            && ($valores['ubicacion'] == null || $valores['ubicacion'] == '')
            && ($valores['clasificacion'] == null
                || $valores['clasificacion'] == ''
            )
            && ($valores['ubicacionfisica'] == null
                || $valores['ubicacionfisica'] == ''
                )
        ;

        if ($es_vacio) {
            return true;
        }
        if (!$this->validate()) {
            return false;
        }
        verifica_sin_CSRF($valores);
        if (in_array(31, $_SESSION['opciones'])
            && !in_array(21, $_SESSION['opciones'])
        ) {
            return true;
        }


        $db = $this->iniVar();
        $do =& objeto_tabla('caso');
        $do->id = $this->bcaso_ffrecuente->_do->id_caso;
        $do->find();
        $do->fetch();
        $df = call_user_func(
            $this->bcaso_ffrecuente->dateToDatabaseCallback,
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

        if ($this->bcaso_ffrecuente->_do->id_ffrecuente != null) {
            $this->bcaso_ffrecuente->_do->delete();
            $_SESSION['ff_total']--;
        }
        $this->bcaso_ffrecuente->forceQueryType(
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
            if ($valores['id_ffrecuente'] == '') {
                $valores['id_ffrecuente'] = DataObjects_Ffrecuente::id_sinInfo();
            }
        }

        $ret = $this->process(
            array(&$this->bcaso_ffrecuente, 'processForm'),
            false
        );
        $_SESSION['ff_total']++;

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
        prepara_consulta_gen(
            $w, $t, $idcaso,
            'caso_ffrecuente', 'ffrecuente', 'id_ffrecuente', false
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
     * @return integer Id de ffrecuente insertada o -1 si no pudo
     */
    static function busca_inserta(&$db, $idcaso, $nomf, $fecha,
        $ubif, $ubi, $cla, &$obs
    ) {
        $rp = hace_consulta(
            $db, "SELECT id FROM ffrecuente WHERE " .
            "nombre ILIKE '$nomf'"
        );
        $rows = array();
        $nr = $rp->numRows();
        if ($rp->fetchInto($row)) {
            $idffrecuente = $row[0];
            if ($rp->fetchInto($row)) {
                rep_obs(
                    "Hay $nr fuentes frecuentes con nombre como " .
                    $fuente->nombre_fuente .
                    ", escogido el primero\n", $obs
                );
            }
            if (!empty($fecha)) {
                $escritocaso = objeto_tabla('caso_ffrecuente');
                $escritocaso->id_caso = $idcaso;
                $escritocaso->id_ffrecuente = $idffrecuente;
                $escritocaso->fecha = $fecha;
                if (!empty($ubif)) {
                    $escritocaso->ubicacionfisica = $ubif;
                }
                $escritocaso->ubicacion = $ubi;
                $escritocaso->clasificacion = $cla;
                $escritocaso->insert();
                return $escritocaso->id_ffrecuente;
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
            $idffrecuente = null;
            $nomf = utf8_decode($fuente->nombre_fuente);
            if (empty($fuente->fecha_fuente)) {
                rep_obs(
                    _("No se incluyó fuente sin fecha: ") .
                    $fuente->asXML(), $obs
                );
            } else if (empty($fuente->nombre_fuente)) {
                rep_obs(
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

}

?>
