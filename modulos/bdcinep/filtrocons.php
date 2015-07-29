<?php
//  vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
/**
 * Estadísticas victimas individuales por rótulos
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2015 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $$
 * @link      http://sivel.sf.net
*/

/**
 * Cifras  consolidado general de víctimas
 */
require_once "aut.php";
require_once $_SESSION['dirsitio'] . "/conf.php";
require_once "misc.php";
require_once "DataObjects/Filiacion.php";

require_once 'HTML/QuickForm/Controller.php';

require_once 'HTML/QuickForm/Action/Display.php';
require_once 'HTML/QuickForm/Action/Next.php';
require_once 'HTML/QuickForm/Action/Back.php';
require_once 'HTML/QuickForm/Action/Jump.php';
require_once 'HTML/QuickForm/header.php';
require_once 'HTML/QuickForm/date.php';
require_once 'HTML/QuickForm/text.php';

require_once "PagTipoViolencia.php";
require_once "ResConsulta.php";

/**
 * Responde a botón consulta
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class AccionCifrasCons extends HTML_QuickForm_Action
{

    /**
     * Cuenta actos con una categorio que cumplan condición
     *
     * @param object  &$db Conexión a base
     * @param integer $cat Categoria de violencia (
     * @param string  $w   where por añadir a consultas
     *
     * @return integer Cantidad
     */
    function cuenta_actos(&$db, $cat, $w)
    {
        consulta_and($db, $w, "acto.id_categoria", (int)$cat);
        $q = "SELECT count(*) FROM caso, acto WHERE caso.id=acto.id_caso AND "
            . $w;
        //echo "OJO q=$q<br>";
        $r = $db->getOne($q);
        sin_error_pear($r);
        return (int)$r;
    }
 
    /**
     * Genera y presenta tabla de resultados
     *
     * @param object &$db         Conexión a base
     * @param string $titulotabla Título
     * @param array  $filas       (tit=>titulo, cat=array cat)
     * @param string $totales     Mensaje totales
     * @param string $w           where por añadir a consultas
     *
     * @return void 
     */
    function gen_tabla(&$db, $titulotabla, $filas, $totales, $w)
    {
        #echo "OJO gen_tabla db, $titulotabla, filas, totales, $w)<br>";
        #echo "OJO filas: "; print_r($filas);
        #echo "OJO totales: ";print_r($totales);
        echo "<h3>";
        echo_esc($titulotabla);
        echo "</h3>";
        echo "<table border='1'>";
        $tot = 0;
        foreach($filas as $f) {
            echo "<tr><td>";
            echo_esc($f['titulo']);
            echo "</td><td>";
            $s = 0;
            foreach($f['cat'] as $c) {
                $mult = 1;
                if ($c < 0) {
                    $mult = -1;
                    $c = -$c;
                }
                $s += $mult * $this->cuenta_actos($db, $c, $w);
            }
            echo_esc($s);
            $tot += $s;
            echo "</td></tr>";
        }
        echo "<tr><td><b>";
        echo_esc($totales);
        echo "</b></td><td>";
        echo_esc($tot);
        echo "</td></tr>";

        echo "</table>";


    }
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
        encabezado_envia();
        $d = objeto_tabla('departamento');
        if (PEAR::isError($d)) {
            die($d->getMessage());
        }
        $fb =& DB_DataObject_FormBuilder::create($d);
        $db =& $d->getDatabaseConnection();


        $pFini = var_post_escapa('fini');
        $pFfin = var_post_escapa('ffin');

        $cons = 'cons';
        $cons2="cons2";
        $where = "";

        $tfini = $GLOBALS['consulta_web_fecha_min'];
        consulta_and($db, $where, "caso.fecha", $tfini, ">=");
        $tffin = $GLOBALS['consulta_web_fecha_max'];
        consulta_and($db, $where, "caso.fecha", $tffin, "<=");

        if ($pFini['Y'] != '') {
            $tfini = arr_a_fecha($pFini, true);
            consulta_and($db, $where, "caso.fecha", $tfini, ">=");
        }
        if ($pFfin['Y'] != '') {
            $tffin = arr_a_fecha($pFfin, false);
            consulta_and($db, $where, "caso.fecha", $tffin, "<=");
        }

        echo "<center>";
        echo "<h1>Consolidado General de Víctimas</h1>";
        echo "<h2>";
        echo_esc($tfini . " - " . $tffin);
        echo "</h2>";

        $filas = array();
        $filas[0] = array(
            "cat" => array( 0 => 20, 1 => 30),
            "titulo" => "Víctimas de Ejecución Extrajudicial por Abuso de Autoridad e Intolerancia Social por agentes directos o indirectos del Estado (Violaciones a los Derechos Humanos)"
        );
        $filas[1] = array(
            "cat" => array( 0 => 10),
            "titulo" => "Víctimas registradas simultáneamente como Ejecuciones Extrajudiciales perpetradas por agentes directos o indirectos del Estado por móviles de Persecución Política (Violaciones a los Derechos Humanos) y como Homicidios Intencionales de personas protegidas (Infracciones al Derecho Internacional Humanitario)."
        );
        $filas[2] = array(
            "cat" => array( 0 => 701, 1 => -10, 2 => 97, 3 => 703, 4 => 87),
            "titulo" => "Víctimas de Homicidio Intencional de Persona Protegida (excepto casos de Violaciones a Derechos Humanos) o Civiles Muertos por uso de Métodos y Medios Ilícitos de guerra o Civiles Muertos en Acciones Bélicas o en Ataques a Bienes Civiles."
        );
        $filas[3] = array(
            "cat" => array( 0 => 40, 1 => 50),
            "titulo" => "Víctimas de Asesinatos por Móviles Político-Sociales sin autor determinado"
        );

        $this->gen_tabla(
            $db,
            "DERECHO A LA VIDA", 
            $filas, 
            "Total víctimas que perdieron la vida",
            $where

        );

        echo "<h3>DERECHO A LA INTEGRIDAD</h3>";
        $filas = array();
        $filas[0] = array(
            "cat" => array( 0 => 23, 1 => 33),
            "titulo" => "Víctimas de Ejecución Extrajudicial por Abuso de Autoridad e Intolerancia Social por agentes directos o indirectos del Estado (Violaciones a los Derechos Humanos)"
        );
        $filas[1] = array(
            "cat" => array( 0 => 13),
            "titulo" => "Víctimas registradas simultáneamente como Heridas por agentes directos o indirectos del Estado por móviles de Persecución Política (Violaciones a los Derechos Humanos) y como Heridas Intencionales de personas protegidas (Infracciones al Derecho Internacional Humanitario)."
        );
        $filas[2] = array(
            "cat" => array( 0=> 702, 1 => -13, 2 => 98, 3=> 704, 4 => 88),
            "titulo" => "Víctimas de Herida Intencional de Persona Protegida (excepto casos de Violación a Derechos Humanos) o Civiles Heridos por uso de Métodos y Medios Ilícitos de guerra o Civiles Heridos en Acciones Bélicas o en Ataques a Bienes Civiles."
        );
         $this->gen_tabla(
            $db,
            "HERIDOS", 
            $filas, 
            "Total víctimas heridas",
            $where

        );

        $filas = array();
        $filas[0] = array(
            "cat" => array( 0 => 25, 1 => 35),
            "titulo" => "Víctimas de Amenaza por Abuso de Autoridad e Intolerancia Social por agentes directos o indirectos del Estado (Violaciones a los Derechos Humanos)."
        );
        $filas[1] = array(
            "cat" => array( 0 => 15),
            "titulo" => "Víctimas registradas simultáneamente como Amenazadas por agentes directos o indirectos del Estado por móviles de Persecución Política (Violaciones a los Derechos Humanos) y como víctimas de Amenazas que constituyen Infracciones al Derecho Internacional Humanitario por parte de agentes directos o indirectos del Estado."
        );
        $filas[2] = array(
            "cat" => array( 0 => 73, 1 => -15),
            "titulo" => "Víctimas de Amenaza como Infracciones al Derecho Internacional Humanitario por parte de la insurgencia o combatientes."
        );
         $this->gen_tabla(
            $db,
            "AMENAZAS", 
            $filas, 
            "Total víctimas de amenazas",
            $where
        );

        $filas = array();
        $filas[0] = array(
            "cat" => array( 0 => 22, 1 => 32),
            "titulo" => "Víctimas de Tortura por Abuso de Autoridad e Intolerancia Social por agentes directos o indirectos del Estado (Violaciones a los Derechos Humanos)."
        );
        $filas[1] = array(
            "cat" => array( 0 => 12),
            "titulo" => "Víctimas registradas simultáneamente como Torturadas por agentes directos o indirectos del Estado por móviles de Persecución Política (Violaciones a los Derechos Humanos) y como víctimas de Tortura que constituye Infracción al Derecho Internacional Humanitario por parte de agentes directos o indirectos del Estado."
        );
        $filas[2] = array(
            "cat" => array( 0 => 72, 1 => -12),
            "titulo" => "Víctimas de Tortura como Infracciones al Derecho Internacional Humanitario por parte de la insurgencia o combatientes."
        );
         $this->gen_tabla(
            $db,
            "TORTURA", 
            $filas, 
            "Total víctimas de tortura",
            $where
        );

        $filas = array();
        $filas[0] = array(
            "cat" => array( 0 => 16, 1 => 26, 2 => 37),
            "titulo" => "Víctimas de Atentados por Persecución Política, Abuso de Autoridad o Intolerancia Social por agentes directos o indirectos del Estado (Violaciones a los Derechos Humanos)."
        );
         $this->gen_tabla(
            $db,
            "ATENTADOS", 
            $filas, 
            "Total víctimas de atentados",
            $where
        );

        $filas = array();
        $filas[0] = array(
            "cat" => array( 0 => 296, 1 => 396),
            "titulo" => "Víctimas de Violencia Sexual por móvil de Abuso de Autoridad o Intolerancia Social, perpetrada por agentes directos o indirectos del Estado (Violaciones a los Derechos Humanos)."
        );
        $filas[1] = array(
            "cat" => array( 0 => 196),
            "titulo" => "Casos registrados simultáneamente como víctimas de Violencia Sexual por agentes directos o indirectos del Estado por móviles de Persecución Política (Violaciones a los Derechos Humanos) y como casos que constituyen al mismo tiempo infracciones al Derecho Internacional Humanitario."
        );
        $filas[2] = array(
            "cat" => array( 0 => 77, 1 => -196),
            "titulo" => "Casos de Violencia Sexual que constituyen infracciones al Derecho Internacional Humanitario por parte de la insurgencia o combatientes."
        );
         $this->gen_tabla(
            $db,
            "VIOLENCIA SEXUAL", 
            $filas, 
            "Total víctimas de violencia sexual",
            $where
        );


        $filas = array();
        $filas[0] = array(
            "cat" => array( 0 => 11),
            "titulo" => "Víctimas de Desaparición por móviles de Persecución Política por parte de agentes directos o indirectos del Estado (Violaciones a los Derechos Humanos)."

        );
        $filas[1] = array(
            "cat" => array( 0 => 14, 1 => 24),
            "titulo" => "Víctimas de Detención Arbitraria por móviles de Persecución Política o Abuso de Autoridad por parte de agentes directos e indirectos del Estado (Violaciones a los Derechos Humanos)."
        );
         $this->gen_tabla(
            $db,
            "DERECHO A LA LIBERTAD", 
            $filas, 
            "Total víctimas de violación del derecho a la libertad",
            $where
        );


        pie_envia();
    }
}


/**
 * Formulario de Estadísticas Individuales por rótulos
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class PagCifrasCons extends HTML_QuickForm_Page
{

    /**
     * Constructora.
     * Ver documentación completa en clase base.
     *
     * @return void
     */
    function PagCifrasCons()
    {
        $this->HTML_QuickForm_Page(
            'cifrascons', 'post', '_self',
            null
        );

        $this->addAction('consulta', new AccionCifrasCons());
    }


    function buildForm()
    {
        encabezado_envia();
        $this->_formBuilt = true;
        $x =&  objeto_tabla('departamento');
        $db = $x->getDatabaseConnection();

        $e =& $this->addElement('header', null, 'Cifras del Consolidado General de Víctimas');
        $e =& $this->addElement('hidden', 'num', (int)$_REQUEST['num']);

        //    $e =& $this->addElement('static', 'fini', 'Victimas ');

        $cy = date('Y');
        if ($cy < 2005) {
            $cy = 2005;
        }
        $e =& $this->addElement(
            'date', 'fini', 'Desde: ',
            array('language' => 'es', 'addEmptyOption' => true,
            'minYear' => '1990', 'maxYear' => $cy
        )
    );
        $e->setValue(($cy - 1) . "-01-01");

        $e =& $this->addElement(
            'date', 'ffin', 'Hasta',
            array('language' => 'es', 'addEmptyOption' => true,
            'minYear' => '1990', 'maxYear' => $cy
        )
    );
        $e->setValue(($cy  - 1) . "-12-31");


        $prevnext = array();
        $sel =& $this->createElement(
            'submit',
            $this->getButtonName('consulta'), 'Consulta'
        );
        $prevnext[] =& $sel;

        $this->addGroup($prevnext, null, '', '&nbsp;', false);


        $tpie = "<div align=right><a href=\"index.php\">" .
            "Menú Principal</a></div>";
        $e =& $this->addElement('header', null, $tpie);

        $this->setDefaultAction('consulta');
    }

}


/**
 * Presenta formulario filtro o estadística
 */
function muestra($dsn)
{
    $aut_usuario = "";
    autentica_usuario($dsn, $aut_usuario, 21);

    $wizard =& new HTML_QuickForm_Controller('CifrasCons', false);
    $consweb = new PagCifrasCons();

    $wizard->addPage($consweb);


    $wizard->addAction('display', new HTML_QuickForm_Action_Display());
    $wizard->addAction('jump', new HTML_QuickForm_Action_Jump());

    $wizard->addAction('process', new AccionCifrasCons());

    $wizard->run();
}

?>
