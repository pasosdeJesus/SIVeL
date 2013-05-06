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
 * Pestaña Otras Fuentes de la ficha de captura de caso
 */
require_once 'PagBaseMultiple.php';

/**
 * Página otras fuentes.
 * Ver documentación de funciones en clase base.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      PagBaseMultiple
*/
class PagOtrasFuentes extends PagBaseMultiple
{

    /* Objetos DB_DataObject_FormBuilder */
    /** Fuente directa independiente de caso */
    var $bfotra;
    /** Relación entre fuente directa y caso */
    var $bcaso_fotra;


    var $titulo = 'Otras Fuentes';

    var $pref = "fd";

    var $nuevaCopia = false;

    var $clase_modelo = 'caso_fotra';



    /**
     * Pone en null variables asociadas a tablas de la pestaña.
     *
     * @return null
     */
    function nullVar()
    {
        $this->bfotra = null;
        $this->bcaso_fotra = null;
    }


    /**
     * Retorna una identificación del registro actual.
     *
     * @return string Identifación
     */
    function copiaId()
    {
        return $this->bfotra->_do->id;
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
        if ($this->bcaso_fotra->_do->id_fotra != null
            && $this->bcaso_fotra->_do->fecha != null
        ) {
            $this->bcaso_fotra->_do->delete();
            $_SESSION['fd_total']--;
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
        $do =& objeto_tabla('caso_fotra');
        $do_fd =& objeto_tabla('fotra');
        $db =& $do->getDatabaseConnection();
        $idcaso =& $_SESSION['basicos_id'];
        if (!isset($idcaso) || $idcaso == null) {
            die(_("Bug: idcaso no debería ser null"));
        }
        $do->id_caso = $idcaso;

        $result = hace_consulta(
            $db, "SELECT  id_fotra, fecha " .
            " FROM caso_fotra, fotra " .
            " WHERE id_caso='$idcaso' AND id_fotra=id " .
            " ORDER BY nombre, fecha;"
        );
        $row = array();
        $idp = array();
        $idp2=array();
        $tot = 0;
        while ($result->fetchInto($row)) {
            $idp[] = $row[0];
            $idp2[] = $row[1];
            $tot++;
        }
        $_SESSION['fd_total'] = $tot;
        if ($_SESSION['fd_pag'] < 0 || $_SESSION['fd_pag'] >= $tot) {
            $do->id_fotra = null;
        } else {
            $do->id_fotra = $idp[$_SESSION['fd_pag']];
            $do->fecha = $idp2[$_SESSION['fd_pag']];
            $do->find();
            $do->fetch();
            $do_fd->id = $do->id_fotra;
            $do_fd->find();
            $do_fd->fetch();
        }

        $this->bcaso_fotra =& DB_DataObject_FormBuilder::create(
            $do,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
                  'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bfotra =& DB_DataObject_FormBuilder::create(
            $do_fd,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
                  'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );

        if ($this->bcaso_fotra == null) {
            die("bcaso_fotra " . _("es null"));
        }
        if ($this->bfotra == null) {
            die("bfotra " . _("es null"));
        }

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
    function PagOtrasFuentes($nomForma)
    {
        parent::PagBaseMultiple($nomForma);
        $this->titulo = _('Otras Fuentes');
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

        $this->bfotra->createSubmit = 0;
        $this->bfotra->useForm($this);
        $this->bfotra->getForm();

        $this->bcaso_fotra->createSubmit = 0;
        $this->bcaso_fotra->useForm($this);
        $this->bcaso_fotra->getForm();

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
            $v = array();
            foreach ($this->bfotra->_do->fb_fieldsToRender as $c) {
                $cq = $this->getElement($c);
                if (isset($this->bfotra->_do->$c)) {
                    $v[$c] = $this->bfotra->_do->$c;
                }
            }
            foreach ($this->bcaso_fotra->_do->fb_fieldsToRender as $c) {
                $cq = $this->getElement($c);
                if (isset($this->bcaso_fotra->_do->$c)) {
                    $v[$c] =$this->bcaso_fotra->_do->$c;
                }
            }
            if (isset($_SESSION['nuevo_copia_id'])) {
                $id = $_SESSION['nuevo_copia_id'];
                unset($_SESSION['nuevo_copia_id']);

                foreach (array('fotra' => 'id',
                    'caso_fotra' => 'id_fotra'
                ) as $n => $k
                ) {
                    $d =& objeto_tabla($n);
                    $d->get($k, $id);
                    foreach ($d->fb_fieldsToRender as $c) {
                        $cq = $this->getElement($c);
                        $v[$c] = $d->$c;
                    }
                }
            }
        }

        $campos = array_merge(
            $this->bfotra->_do->fb_fieldsToRender,
            $this->bcaso_fotra->_do->fb_fieldsToRender
        );
        establece_valores_form($this, $campos, $v);

        if (isset($_SESSION['forma_modo'])
            && $_SESSION['forma_modo'] == 'busqueda'
        ) {
            $f = $this->getElement('fecha');
            $da = $f->getValue();
            $y = @date('Y');
            $m = @date('m');
            $d = @date('d');
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
        hace_consulta(
            $db, "DELETE FROM caso_fotra " .
            " WHERE id_caso='$idcaso'"
        );
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
        $es_vacio = ($valores['nombre'] == null || $valores['nombre'] == '')
            && ($valores['anotacion'] == null || $valores['anotacion'] == '')
            && ($valores['ubicacionfisica'] == null
                || $valores['ubicacionfisica'] == ''
            ) ;

        if ($es_vacio) {
            return true;
        }
        ;
        if (!isset($_SESSION['forma_modo'])
            || $_SESSION['forma_modo'] != 'busqueda'
        ) {
            if (!$this->validate() || $valores['nombre'] == null
                || $valores['fecha'] == null
            ) {
                return false;
            }
        }
        verifica_sin_CSRF($valores);
        if (in_array(31, $_SESSION['opciones'])
            && !in_array(21, $_SESSION['opciones'])
        ) {
            return true;
        }


        $db = $this->iniVar();

        $do =& objeto_tabla('caso');
        $do->id = $this->bcaso_fotra->_do->id_caso;
        $do->find();
        $do->fetch();
        $df= call_user_func(
            $this->bcaso_fotra->dateToDatabaseCallback,
            var_escapa($valores['fecha'], $db)
        );

        if (!isset($_SESSION['forma_modo'])
            || $_SESSION['forma_modo'] != 'busqueda'
        ) {
            if (strtotime($df)<strtotime($do->fecha)) {
                error_valida(
                    _('Fecha de fuente no puede ser anterior a la del caso'),
                    $valores
                );
                return false;
            }
        }

        if ($this->bcaso_fotra->_do->id_fotra != null
            && $this->bcaso_fotra->_do->fecha != null
        ) {
            $this->bcaso_fotra->_do->delete();
            $_SESSION['fd_total']--;
        }
        $this->bcaso_fotra->forceQueryType(
            DB_DATAOBJECT_FORMBUILDER_QUERY_FORCEINSERT
        );

        $nuevoidf = null;
        if (isset($_SESSION['forma_modo'])
            && $_SESSION['forma_modo'] == 'busqueda'
            && isset($valores['id_caso'])
            && $valores['id_caso'] == $GLOBALS['idbus']
        ) {
            $nuevoidf = -1;
            if ($df == "0000-00-00") {
                $valores['fecha']['d'] = 1;
                $valores['fecha']['m'] = 1;
                $valores['fecha']['Y'] = $GLOBALS['anio_min'] - 1;
            }
        }
        $q = "SELECT id FROM fotra WHERE " .
            "nombre='" . var_escapa($valores['nombre'], $db) . "';";
        $result = hace_consulta($db, $q);
        $row = array();
        if (isset($result) && !PEAR::isError($result)
            && $result->fetchInto($row)
        ) {
            $this->bfotra->_do->get('id', $row[0]);
        } else {
            $this->bfotra->_do->id = $nuevoidf;
            $this->bfotra->_do->nombre
                = var_escapa($valores['nombre'], $db);
            $this->bfotra->_do->insert();
        }

        $this->bcaso_fotra->_do->id_fotra
            = $this->bfotra->_do->id;
        $ret = @$this->process(
            array(&$this->bcaso_fotra,
            'processForm'
            ), false
        );
        $_SESSION['fd_total']++;

        caso_funcionario($_SESSION['basicos_id']);
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
            $w, $t, $idcaso, 'caso_fotra',
            'fotra', 'id_fotra', true
        );
    }

    /**
     * Busca una fuente no frecuente por nombre y la inserta en un caso
     * con los datos que esta función recibe.
     *
     * @param object &$db    Conexión a base de datos
     * @param intger $idcaso Número de caso al que se añade fuente
     * @param string $nomf   Nombre de la fuente
     * @param string $fecha  Fecha de fuente
     * @param string $ubif   Ubicación física
     * @param string $anota  Anotación
     * @param string $tipof  Tipo de fuente
     * @param string &$obs   Colchon para agregar notas de conversion
     *
     * @return integer Id de la fuente insertad o -1 si no puede
     */
    static function busca_inserta(&$db, $idcaso, $nomf, $fecha,
        $ubif, $anota, $tipof, &$obs
    ) {
        assert($idcaso != null);
        assert($nomf != null);
        assert($fecha != null);

        $dfdc = objeto_tabla('caso_fotra');
        $dfdc->id_caso = $idcaso;
        $dfdc->fecha = $fecha;
        if (!empty($ubif)) {
            $dfdc->ubicacionfisica = $ubif;
        }
        $dfdc->anotacion = $anota;
        if ($tipof != null) {
            $op = $dfdc->fb_enumOptions['tfuente'];
            if (in_array($tipof, $op)) {
                $dfdc->tfuente = array_search($tipof, $op);
            }
        }
        $rp = hace_consulta(
            $db, "SELECT id FROM fotra " .
            " WHERE nombre ILIKE '$nomf'"
        );
        $rows = array();
        $nr = $rp->numRows();
        $dfd = objeto_tabla('fotra');
        if ($nr == 0) {
            $dfd->nombre = $nomf;
            $dfd->insert();
            $dfdc->id_fotra = $dfd->id;
        } else {
            $row = array();
            $rp->fetchInto($row);
            $dfdc->id_fotra = $row[0];
            if ($rp->fetchInto($row)) {
                rep_obs(
                    _("Hay ") . $nr .
                    _("fuentes no frecuentes con nombre como ")
                    .  $fuente->nombre_fuente
                    .  _(", escogida la primera") . "\n", $obs
                );
            }
        }
        $dfdc->insert();

        return $dfdc->id_fotra;
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
            $nomf = $fuente->nombre_fuente;
            if (empty($fuente->fecha_fuente)) {
                rep_obs(
                    _("No se incluyó fuente sin fecha: ") .
                    $fuente->asXML()
                );
            } else if (empty($fuente->nombre_fuente)) {
                rep_obs(
                    _("No se incluyó fuente sin nombre: ") .
                    $fuente->asXML()
                );
            } else {
                $fecha = conv_fecha($fuente->fecha_fuente, $obs);
                PagOtrasFuentes::busca_inserta(
                    $db, $idcaso, utf8_decode($nomf), $fecha,
                    utf8_decode((string)$fuente->ubicacion_fuente),
                    dato_en_obs($fuente, 'anotacion'),
                    dato_en_obs($fuente, 'tfuente'),
                    $obs
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
    static function mezcla(&$db, $sol, $id1, $id2, $idn, 
        $cls=array('caso_fotra')
    ) {
        //echo "OJO PagOtrasFuentes::mezcla(db, ";
        //print_r($sol); echo ", $id1, $id2, $idn, ";print_r($cls);echo ")<br> ";
        assert($id1 != $id2);
        assert($id2 != $idn);
        if (!is_array($cls)) {
            $cls = array($cls);
        }
        foreach ($cls as $t) {
            if ($t == 'anexo') {
                $pl = 'id';
            } else {
                $pl = "id_" . str_replace("_caso", "", $t);
            }
            $d1 = objeto_tabla($t);
            $d2 = objeto_tabla($t);
            $d1->id_caso = $id1;
            $d2->id_caso = $id2;
            if ($id1 != $idn) {
                $d1->find();
                while ($d1->fetch()) {
                    $dd = objeto_tabla($t);
                    $dd->id_caso = $idn;
                    foreach (array_merge(array($pl), $dd->fb_fieldLabels)
                        as $c => $cf
                    ) {
                        //echo "OJO 1 c=$c, d1->c=" . $d1->$c . "<br>";
                        if (isset($d1->$c)) {
                            $dd->$c = $d1->$c;
                        }
                    }
                    //echo "OJO 1 insertado dd"; print_r($dd);
                    $dd->insert();
                }
            }
            $d2->find();
            while ($d2->fetch()) {
                //echo "OJO copiando de nuevo d1<br>";
                $dd = objeto_tabla($t);
                $dd->id_caso = $idn;
                foreach (array_merge(array($pl), $dd->fb_fieldLabels)
                    as $c => $cf
                ) {
                    //echo "OJO 2 c=$c, d2->c=" . $d2->$c . "<br>";
                    if (isset($d2->$c)) {
                        $dd->$c = $d2->$c;
                    }
                }
                //echo "OJO 2 insertado dd"; print_r($dd); 
                $dd->insert();
            }
            //print_r($dd);
        }
    }

}

?>
