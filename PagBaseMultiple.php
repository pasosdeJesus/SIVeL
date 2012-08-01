<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Base para página con multiples subpáginas al capturar caso
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
 * Base para página con multiples subpáginas al capturar caso
 */
require_once 'aut.php';
require_once $_SESSION['dirsitio'] . "/conf.php";
require_once 'PagBaseSimple.php';
require_once 'HTML/QuickForm/Action.php';

/**
 * Acción que responde al botón eliminar.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      BuscarId
 */
class EliminarMultiple extends HTML_QuickForm_Action
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
        $page->elimina($page->_submitValues);
        if ($_SESSION[$page->pref.'_pag'] >= $_SESSION[$page->pref.'_total']) {
            $_SESSION[$page->pref.'_pag']
                = max($_SESSION[$page->pref.'_total']-1, 0);
        }
        $page->nullVar();
        $page->_submitValues = array();
        $page->_defaultValues = array();
        $page->handle('display');
    }
}

/**
 * Acción que responde al botón nuevo
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      BuscarId
 */
class NuevoMultiple extends HTML_QuickForm_Action
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
        if ($page->procesa($page->_submitValues)) {
            $page->_submitValues = array();
            $page->_defaultValues = array();
            $page->nullVar();
            $_SESSION[$page->pref.'_pag'] = $_SESSION[$page->pref.'_total'];
        }
        $page->handle('display');
    }
}

/**
 * Acción que responde al botón nuevo como copia
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      BuscarId
 */
class NuevoCopiaMultiple extends HTML_QuickForm_Action
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
        if ($page->procesa($page->_submitValues)) {
            $page->_submitValues = array();
            $page->_defaultValues = array();
            $page->iniVar();
            $_SESSION['nuevo_copia_id'] = $page->copiaId();
            $page->nullVar();
            $_SESSION[$page->pref.'_pag'] = $_SESSION[$page->pref.'_total'];
        }
        $page->handle('display');
    }
}


/**
 * Acción que responde al botón anterior
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      BuscarId
 */
class AnteriorMultiple extends HTML_QuickForm_Action
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
        if ($page->procesa($page->_submitValues)) {
            $page->_submitValues = array();
            $page->_defaultValues = array();
            if ($_SESSION[$page->pref.'_pag'] > 0) {
                $_SESSION[$page->pref.'_pag']--;
                $page->nullVar();
            }
        }
        $page->handle('display');
    }
}

/**
 * Acción que responde al botón siguiente
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      BuscarId
 */
class SiguienteMultiple extends HTML_QuickForm_Action
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
        if ($page->procesa($page->_submitValues)) {
            $page->_submitValues = array();
            $page->_defaultValues = array();
            if ($_SESSION[$page->pref.'_pag'] < $_SESSION[$page->pref.'_total']
            ) {
                $_SESSION[$page->pref.'_pag']++;
            }
        }
        $page->nullVar();
        $page->handle('display');
    }
}


/**
 * Clase base para página con multiples subpáginas al capturar caso.
 *
 * La ídea es identificar con un número las posibles subpáginas, para
 * poder avanzar, retroceder, eliminar y agregar nuevos.
 * La información de la subpágina en la que está se mantiene en variables
 * de sesión que tienen un prefijo común.
 *
 * Ver también documentación de clase base.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      PagBaseSimple
 */
abstract class PagBaseMultiple extends PagBaseSimple
{

    /** Titulo de la pestaña cuando se le de foco*/
    var $titulo= '';

    /** Titulo corto que aparece en botones */
    var $tcorto = '';

    /** Prefijo común para variables de sesión de la clase */
    var $pref = '';

    /** Habilitar boton Nueva Copia */
    var $nuevoCopia = true;

    /**
     * Pone en null variables asociadas a tablas de la pestaña.
     *
     * @return null
     */
    abstract function nullVar();

    /**
     * Elimina de base de datos el registro actual.
     *
     * @param array &$valores Valores enviados por formulario.
     *
     * @return null
     */
    abstract function elimina(&$valores) ;

    /**
     * Retorna una identificación del registro actual.
     *
     * @return string Identifación
     */
    abstract function copiaId();

    /**
     * Constructora
     *
     * @param string $nomForma Nombre del formulario
     *
     * @return null
     */
    function PagBaseMultiple($nomForma)
    {
        parent::PagBaseSimple($nomForma);

        $this->addAction('eliminar', new EliminarMultiple());
        $this->addAction('nuevo', new NuevoMultiple());
        if ($this->nuevoCopia) {
            $this->addAction('nuevoCopia', new NuevoCopiaMultiple());
        }
        $this->addAction('anteriorMultiple', new AnteriorMultiple());
        $this->addAction('siguienteMultiple', new SiguienteMultiple());

        if (!isset($_SESSION[$this->pref . '_pag'])) {
            $_SESSION[$this->pref . '_pag'] = 0;
        }
    }


    /**
     * Construye elementos del formulario incluyendo botones
     * (anterior/siguiente/eliminar/nuevo/nueva copia)
     *
     * @return Formulario
     */
    function buildForm()
    {
        $this->_formBuilt = true;
        $this->_submitValues = array();
        $this->_defaultValues = array();

        $cm = "b" . $this->clase_modelo;
        if (!isset($this->$cm) || $this->$cm == null) {
            $db = $this->iniVar();
        } else {
            $db = $this->$cm->_do->getDatabaseConnection();
        }
        $this->controller->creaTabuladores($this, array('class' => 'flat'));
        $idcaso =& $_SESSION['basicos_id'];
        if (!isset($idcaso) || $idcaso == null) {
            die("Bug: idcaso no debería ser null");
        }

        $comp = $idcaso == $GLOBALS['idbus'] ? 
            _('Consulta') : _('Caso') . ' ' . $idcaso;
        $nf = $_SESSION[$this->pref.'_pag'] >= $_SESSION[$this->pref.'_total'] ?
            '-' : $_SESSION[$this->pref . '_pag'] + 1;
        $e =& $this->addElement(
            'header', null, '<table width = "100%">' .
            '<th align = "left">' . $this->titulo . ' (' .
            $nf .'/' . $_SESSION[$this->pref . '_total'] .
            ')</th><th algin = "right">' .
            $comp . "</th></table>"
        );


        $nac = 'eliminar';
        $n = $this->getButtonName($nac);
        $e =& $this->createElement('submit', $n, _('Eliminar'));
        $ed[] =& $e;

        $nac = 'nuevo';
        $n = $this->getButtonName($nac);
        $e =& $this->createElement('submit', $n, _('Nueva'));
        $ed[] =& $e;

        $nac = 'nuevoCopia';
        $n = $this->getButtonName($nac);
        $e =& $this->createElement('submit', $n, _('Nueva Copia'));
        if (!$this->nuevoCopia) {
            $e->updateAttributes(array('disabled' => 'true'));
        }
        $ed[] =& $e;

        $nac = 'anteriorMultiple';
        $n = $this->getButtonName($nac);
        $nb = sprintf(_("%s anterior"), $this->tcorto);
        $e =& $this->createElement('submit', $n, $nb);
        $ed[] =& $e;

        $nac = 'siguienteMultiple';
        $n = $this->getButtonName($nac);
        $nb = sprintf(_("%s siguiente"), $this->tcorto);
        $e =& $this->createElement('submit', $n, $nb);
        $ed[] =& $e;

        $this->addGroup($ed, null, '', '&nbsp;', false);

        $this->formularioAgrega($db, $idcaso);

        $this->controller->creaBotonesEstandar($this);

        $this->setDefaultAction('siguiente');

        $this->formularioValores($db, $idcaso);
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
     * @param array   $cls Especificación de las tablas por revisar. Cada
     * elemento es de la forma etiqueta  => array(tabla, campo_por_mostrar)
     *
     * @return void Añade a $r datos de comparación
     * @see PagBaseSimple
     */
    static function compara(&$db, &$r, $id1, $id2, $cls)
    {
        //echo "OJO PagBaseMultiple::compara(db, r, $id1, $id2, {";
        //print_r($a); echo "})<br>";
        if ($cls == null || (count($cls) == 1 && $cls[0] == 'caso_contexto')) {
            $cls = array('Contextos' => array('caso_contexto', 'id_contexto'),
                'Antecedentes' => array('antecedente_caso', 'id_antecedente'));
        }
        foreach ($cls as $eti => $clm) {
            list($cl, $ck) = $clm;
            //echo "OJO cl=$cl, ck=$ck<br> ";
            $v1 = $v2 = "";
            for ($nd = 1; $nd <= 2; $nd++) {
                $nomid = "id$nd";
                $nomv = "v$nd";
                //echo "OJO nomid=$nomid, nomv=$nomv<br>";
                $d = objeto_tabla($cl);
                $d->id_caso = $$nomid;
                $d->find();
                $sep = "";
                while ($d->fetch()) {
                    //echo "OJO d fetched <br>";
                    foreach (explode(',', $ck) as $c) {
                        $dr = $d->getLink($c);
                        //echo "OJO c=$c<br>"; print_r($dr);
                        if (isset($dr->fb_linkDisplayFields)
                            && count($dr->fb_linkDisplayFields) > 0
                        ) {
                            $ac = $dr->fb_linkDisplayFields;
                        } else if (isset($dr->nombres)) {
                            $ac = array('nombres');
                        } else {
                            $ac = array('nombre');
                        }
                        $$nomv .= $sep;
                        foreach ($ac as $n) {
                            //echo "  OJO n=$n<br>";
                            $$nomv .= " " . $dr->$n;
                        }
                    }
                    $sep = ", ";
                }
                //echo "OJO $nomv=" . $$nomv . "<br>";
            }
            $vp = 1;
            if (strlen($v2) > strlen($v1)) {
                $vp =2;
            }
            if ($v1 != $v2) {
                $r[$cl . "-" . $c] = array(
                    $eti, $v1, $v2, $vp
                );
            }
        }

        //echo "OJO saliendo de PagBaseMultiple::compara, r=" ;
        //print_r($r); echo "<br>";
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
     * @param array   $cls Especificación de las tablas por revisar. Cada
     *   elemento es de la forma etiqueta  => array(tabla, campo_por_mostrar)
     *   o bien => array(tabla, campo_por_mostrar, tabla_ref_en_sol)
     *
     * @return Mezcla valores de los casos $id1 e $id2 en el caso $idn de
     * acuerdo a las preferencias especificadas en $sol.
     * @see PagBaseSimple
     */
    static function mezcla(&$db, $sol, $id1, $id2, $idn, $cls)
    {
        //echo "PagBaseMultiple::mezcla(db, {";
        //print_r($sol); echo "}, $id1, $id2, $idn, {" ;
        //print_r($cls) ; echo "})<br>";
        /* No sacamos llaves primarias de aqui porque la "granularidad"
           de lo que se copia debe especificarse

           $tab = parse_ini_file(
            $_SESSION['dirsitio'] . "/DataObjects/" .
            $GLOBALS['dbnombre'] . ".ini",
            true
            );
        */
        //print_r($tab); die("x");
        if ($cls == 'caso_contexto') {
            $cls = array('Contextos' => array('caso_contexto', 'id_contexto'),
            'Antecedentes' => array('antecedente_caso', 'id_antecedente'));
            // 'presuntos_responsables_caso' => array(
            // 'presuntos_responsables_caso', 'id_caso,id_p_responsable,id'));
        }
        foreach ($cls as $eti => $clm) {
            if (count($clm) == 2) {
                list($cl, $ck) = $clm;
                $clsol = $cl;
            } else {
                list($cl, $ck, $clsol) = $clm;
            }
            //echo "OJO cl=$cl, ck=$ck,clsol=$clsol<br> ";
            $de = objeto_tabla($cl);
            $eti = $de->nom_tabla;
            if ($sol[$clsol][$ck] == 1) {
                //echo "OJO caso 1";
                $de->id_caso = $id1;
            } else {
                //echo "OJO caso 2";
                $de->id_caso = $id2;
            }
            // aqui tocaría por las llaves primarias que no esten en ck
            // y no solo id_caso
            $de->find();
            $lc = array();
            while ($de->fetch()) {
                $k = ""; $sep = "";
                $nk = explode(',', "id_caso," . $ck);
                foreach ($nk as $c) {
                    $k .= $sep;
                    if ($c == "id_caso") {
                        $k .= $idn;
                    } else {
                        $k .= $de->$c;
                    }
                    $sep = ',';
                }
                foreach ($de->fb_fieldLabels as $ftr => $nf) {
                    $lc[$k][$ftr] = $de->$ftr;
                }
            }
            //Borrar las del nuevo le correspondia a llamadora
            //hace_consulta($db, "DELETE FROM $cl WHERE id_caso='$idn'");
            // Insertar las que se guardaron en $lc
            foreach ($lc as $k => $sv) {
                $d = objeto_tabla($cl);
                $d->id_caso = $idn;
                $vk = explode(',', $k);
                $nk = explode(',', "id_caso," . $ck);
                assert(count($vk) == count($nk));
                for ($i = 0; $i < count($nk); $i++) {
                    $nc = $nk[$i];
                    //echo "OJO llave $i, nc=$nc, vk[i]=" . $vk[$i] . "<br>";
                    $d->$nc = $vk[$i];
                }
                foreach ($d->fb_fieldLabels as $ftr => $nf) {
                    //echo "OJO campo ftr=$ftr, sv[ftr]=" . $sv[$ftr] . "<br>";
                    if (!in_array($ftr, $nk)) {
                        $d->$ftr = $sv[$ftr];
                    }
                }
                $d->insert();
                //echo "insertado"; print_r($d); //die("x");
            }
        }
    }

}

?>
