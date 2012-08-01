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
    var $bfuente_directa;
    /** Relación entre fuente directa y caso */
    var $bfuente_directa_caso;


    var $titulo = 'Otras Fuentes';

    var $pref = "fd";

    var $nuevaCopia = false;

    var $clase_modelo = 'fuente_directa_caso';



    /**
     * Pone en null variables asociadas a tablas de la pestaña.
     *
     * @return null
     */
    function nullVar()
    {
        $this->bfuente_directa = null;
        $this->bfuente_directa_caso = null;
    }


    /**
     * Retorna una identificación del registro actual.
     *
     * @return string Identifación
     */
    function copiaId()
    {
        return $this->bfuente_directa->_do->id;
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
        if ($this->bfuente_directa_caso->_do->id_fuente_directa != null
            && $this->bfuente_directa_caso->_do->fecha != null
        ) {
            $this->bfuente_directa_caso->_do->delete();
            $_SESSION['fd_total']--;
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
        $do =& objeto_tabla('fuente_directa_caso');
        $do_fd =& objeto_tabla('fuente_directa');
        $db =& $do->getDatabaseConnection();
        $idcaso =& $_SESSION['basicos_id'];
        if (!isset($idcaso) || $idcaso == null) {
            die("Bug: idcaso no debería ser null");
        }
        $do->id_caso = $idcaso;

        $result = hace_consulta(
            $db, "SELECT  id_fuente_directa, fecha " .
            " FROM fuente_directa_caso, fuente_directa " .
            " WHERE id_caso='$idcaso' AND id_fuente_directa=id " .
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
            $do->id_fuente_directa = null;
        } else {
            $do->id_fuente_directa = $idp[$_SESSION['fd_pag']];
            $do->fecha = $idp2[$_SESSION['fd_pag']];
            $do->find();
            $do->fetch();
            $do_fd->id = $do->id_fuente_directa;
            $do_fd->find();
            $do_fd->fetch();
        }

        $this->bfuente_directa_caso =& DB_DataObject_FormBuilder::create(
            $do,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
                  'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        $this->bfuente_directa =& DB_DataObject_FormBuilder::create(
            $do_fd,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
                  'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );

        if ($this->bfuente_directa_caso == null) {
            die("bfuente_directa_caso es null");
        }
        if ($this->bfuente_directa == null) {
            die("bfuente_directa es null");
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

        $this->bfuente_directa->createSubmit = 0;
        $this->bfuente_directa->useForm($this);
        $this->bfuente_directa->getForm();

        $this->bfuente_directa_caso->createSubmit = 0;
        $this->bfuente_directa_caso->useForm($this);
        $this->bfuente_directa_caso->getForm();

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
            foreach ($this->bfuente_directa->_do->fb_fieldsToRender as $c) {
                $cq = $this->getElement($c);
                if (isset($this->bfuente_directa->_do->$c)) {
                    $v[$c] = $this->bfuente_directa->_do->$c;
                }
            }
            foreach ($this->bfuente_directa_caso->_do->fb_fieldsToRender as $c) {
                $cq = $this->getElement($c);
                if (isset($this->bfuente_directa_caso->_do->$c)) {
                    $v[$c] =$this->bfuente_directa_caso->_do->$c;
                }
            }
            if (isset($_SESSION['nuevo_copia_id'])) {
                $id = $_SESSION['nuevo_copia_id'];
                unset($_SESSION['nuevo_copia_id']);

                foreach (array('fuente_directa' => 'id',
                    'fuente_directa_caso' => 'id_fuente_directa'
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
            $this->bfuente_directa->_do->fb_fieldsToRender,
            $this->bfuente_directa_caso->_do->fb_fieldsToRender
        );
        establece_valores_form($this, $campos, $v);

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
            $db, "DELETE FROM fuente_directa_caso " .
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
            && ($valores['ubicacion_fisica'] == null
                || $valores['ubicacion_fisica'] == ''
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
        $db = $this->iniVar();

        $do =& objeto_tabla('caso');
        $do->id = $this->bfuente_directa_caso->_do->id_caso;
        $do->find();
        $do->fetch();
        $df= call_user_func(
            $this->bfuente_directa_caso->dateToDatabaseCallback,
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

        if ($this->bfuente_directa_caso->_do->id_fuente_directa != null
            && $this->bfuente_directa_caso->_do->fecha != null
        ) {
            $this->bfuente_directa_caso->_do->delete();
            $_SESSION['fd_total']--;
        }
        $this->bfuente_directa_caso->forceQueryType(
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
        $q = "SELECT id FROM fuente_directa WHERE " .
            "nombre='" . var_escapa($valores['nombre'], $db) . "';";
        $result = hace_consulta($db, $q);
        $row = array();
        if (isset($result) && !PEAR::isError($result)
            && $result->fetchInto($row)
        ) {
            $this->bfuente_directa->_do->get('id', $row[0]);
        } else {
            $this->bfuente_directa->_do->id = $nuevoidf;
            $this->bfuente_directa->_do->nombre
                = var_escapa($valores['nombre'], $db);
            $this->bfuente_directa->_do->insert();
        }

        $this->bfuente_directa_caso->_do->id_fuente_directa
            = $this->bfuente_directa->_do->id;
        $ret = @$this->process(
            array(&$this->bfuente_directa_caso,
            'processForm'
            ), false
        );
        $_SESSION['fd_total']++;

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
            $w, $t, $idcaso, 'fuente_directa_caso',
            'fuente_directa', 'id_fuente_directa', true
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

        $dfdc = objeto_tabla('fuente_directa_caso');
        $dfdc->id_caso = $idcaso;
        $dfdc->fecha = $fecha;
        if (!empty($ubif)) {
            $dfdc->ubicacion_fisica = $ubif;
        }
        $dfdc->anotacion = $anota;
        if ($tipof != null) {
            $op = $dfdc->fb_enumOptions['tipo_fuente'];
            if (in_array($tipof, $op)) {
                $dfdc->tipo_fuente = array_search($tipof, $op);
            }
        }
        $rp = hace_consulta(
            $db, "SELECT id FROM fuente_directa " .
            " WHERE nombre ILIKE '$nomf'"
        );
        $rows = array();
        $nr = $rp->numRows();
        $dfd = objeto_tabla('fuente_directa');
        if ($nr == 0) {
            $dfd->nombre = $nomf;
            $dfd->insert();
            $dfdc->id_fuente_directa = $dfd->id;
        } else {
            $row = array();
            $rp->fetchInto($row);
            $dfdc->id_fuente_directa = $row[0];
            if ($rp->fetchInto($row)) {
                repObs(
                    _("Hay ") . $nr . 
                    _("fuentes no frecuentes con nombre como ") 
                    .  $fuente->nombre_fuente 
                    .  _(", escogida la primera") . "\n", $obs
                );
            }
        }
        $dfdc->insert();

        return $dfdc->id_fuente_directa;
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
            $nomf = $fuente->nombre_fuente;
            if (empty($fuente->fecha_fuente)) {
                repObs(
                    _("No se incluyó fuente sin fecha: ") .
                    $fuente->asXML()
                );
            } else if (empty($fuente->nombre_fuente)) {
                repObs(
                    _("No se incluyó fuente sin nombre: ") .
                    $fuente->asXML()
                );
            } else {
                $fecha = conv_fecha($fuente->fecha_fuente, $obs);
                PagOtrasFuentes::busca_inserta(
                    $db, $idcaso, utf8_decode($nomf), $fecha,
                    utf8_decode((string)$fuente->ubicacion_fuente),
                    dato_en_obs($fuente, 'anotacion'),
                    dato_en_obs($fuente, 'tipo_fuente'),
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
    static function compara(&$db, &$r, $id1, $id2, $cls)
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
        //echo "OJO PagOtrasFuentes::mezcla(db, ";
        //print_r($sol); echo ", $id1, $id2, $idn, $t)<br> ";
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
            $d1->find();
            while ($d1->fetch()) {
                $dd = objeto_tabla($t);
                $dd->id_caso = $idn;
                foreach (array_merge(array($pl), $dd->fb_fieldLabels)
                    as $c => $cf
                ) {
                    //echo "OJO 1 c=$c, d1->c=" . $d1->$c . "<br>";
                    $dd->$c = $d1->$c;
                }
                $dd->insert();
                //echo "OJO 1 insertado dd"; print_r($dd);
            }
            $d2->find();
            while ($d2->fetch()) {
                //echo "copiando de nuevo d1<br>";
                $dd = objeto_tabla($t);
                $dd->id_caso = $idn;
                foreach (array_merge(array($pl), $dd->fb_fieldLabels)
                    as $c => $cf
                ) {
                    //echo "OJO 2 c=$c, d2->c=" . $d2->$c . "<br>";
                    $dd->$c = $d2->$c;
                }
                $dd->insert();
                //echo "OJO 2 insertado dd"; print_r($dd); //$dd->insert();
            }
            //print_r($dd);
        }
    }

}

?>
