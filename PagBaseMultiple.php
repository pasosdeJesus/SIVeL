<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
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
 * @version   CVS: $Id: PagBaseMultiple.php,v 1.34.2.1 2011/09/14 14:56:18 vtamara Exp $
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

        $comp = $idcaso == $GLOBALS['idbus'] ? 'Consulta' : 'Caso ' . $idcaso;
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
        $e =& $this->createElement('submit', $n, 'Eliminar');
        $ed[] =& $e;

        $nac = 'nuevo';
        $n = $this->getButtonName($nac);
        $e =& $this->createElement('submit', $n, 'Nueva');
        $ed[] =& $e;

        $nac = 'nuevoCopia';
        $n = $this->getButtonName($nac);
        $e =& $this->createElement('submit', $n, 'Nueva Copia');
        if (!$this->nuevoCopia) {
            $e->updateAttributes(array('disabled' => 'true'));
        }
        $ed[] =& $e;

        $nac = 'anteriorMultiple';
        $n = $this->getButtonName($nac);
        $e =& $this->createElement('submit', $n, $this->tcorto.' anterior');
        $ed[] =& $e;

        $nac = 'siguienteMultiple';
        $n = $this->getButtonName($nac);
        $e =& $this->createElement('submit', $n, $this->tcorto.' siguiente');
        $ed[] =& $e;

        $this->addGroup($ed, null, '', '&nbsp;', false);

        $this->formularioAgrega($db, $idcaso);

        $this->controller->creaBotonesEstandar($this);

        $this->setDefaultAction('siguiente');

        $this->formularioValores($db, $idcaso);
    }

}

?>
