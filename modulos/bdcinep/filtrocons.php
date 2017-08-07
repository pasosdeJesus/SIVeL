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
     * Cuenta victimas en un caso con una categorio, y posiblemente con otra
     * o posiblemente sin otra
     * que ademas cumplan condición
     *
     * @param object  &$db Conexión a base
     * @param integer $cat Categoria de violencia que debe tener
     * @param integer $concat Otra categoria de violencia que debe tener
     * @param integer $sincat Categoria de violencia que no debe tener
     * @param string  $w   where por añadir a consultas
     *
     * @return integer Cantidad
     */
    function cuenta_actos(&$db, $cat, $concat, $sincat, $w)
    {
        consulta_and($db, $w, "acto.id_categoria", (int)$cat);
        if ((int)$concat > 0) {
          $w = "$w AND (acto.id_caso, acto.id_persona) IN 
                (SELECT id_caso, id_persona FROM acto 
                WHERE acto.id_categoria='" . (int)$concat . "') ";
        }
        if ((int)$sincat > 0) {
          $w = "$w AND (acto.id_caso, acto.id_persona) NOT IN 
                (SELECT id_caso, id_persona FROM acto 
                WHERE acto.id_categoria='" . (int)$sincat . "')";
        }
        $q = "SELECT count(*) FROM (SELECT DISTINCT acto.id_caso, 
            acto.id_persona, acto.id_categoria FROM acto JOIN caso 
            ON caso.id=acto.id_caso WHERE $w) AS subcuentaactos";
        #echo "OJO q=$q<br>";
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
        if ($titulotabla != '') {
            echo "<h4>";
            echo_esc($titulotabla);
            echo "</h4>";
        }
        echo "<table border='1'>";
        $tot = 0;
        foreach($filas as $f) {
            echo "<tr><td>";
            echo_esc($f['titulo']);
            echo "</td><td>";
            $s = 0;
            foreach($f['cat'] as $c) {
                $pc = split('&', $c);
                $concat = 0;
                $sincat = 0;
                if (count($pc) > 2) {
                    die("Implementar primero");
                } elseif (count($pc) == 2) {
                    if ($pc[1][0] == '!') {
                        $sincat = substr($pc[1],1);
                    } else {
                        $concat = $pc[1];
                    }
                }
                #echo "OJO pc[0]=".$pc[0].", concat=$concat, sincat=$sincat, w=$w<br>";
                $s += $this->cuenta_actos($db, $pc[0], $concat, $sincat, $w);
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

        echo "<h3>DERECHO A LA INTEGRIDAD</h3>";
        # DERECHO A LA VIDA
        $filas = array();
        $filas[0] = array(
            "cat" => array( 0 => 20, 1 => 30),
            "titulo" => "Víctimas de 'Ejecución Extrajudicial' por Abuso de Autoridad e Intolerancia Social por agentes directos o indirectos del Estado (Violaciones a los Derechos Humanos)"
        );
        $filas[1] = array(
            "cat" => array( 0 => "10&!701"),
            "titulo" => "Víctimas de 'Ejecución Extrajudicial' por Persecución Política (Violaciones a los DH) que no representan infracciones al DIHC"
        );
        $filas[2] = array(
            "cat" => array( 0 => "10&701"),
            "titulo" => "Víctimas simultáneamente de 'Ejecución Extrajudicial' perpetradas por agentes directos o indirectos del Estado por móviles de Persecución Política (Violaciones a los Derechos Humanos) y de 'Homicidio Intencional de Persona Protegida' (Infracciones al Derecho Internacional Humanitario Consuetudinario)."
        );
        $filas[3] = array(
            "cat" => array( 0 => "701&!10", 2 => 97, 3 => 703, 4 => 87),
            "titulo" => "Víctimas de 'Homicidio Intencional de Persona Protegida' (excepto casos de Violaciones a Derechos Humanos) o 'Muerte Causad por Empleo de Métodos y Medios Ilícitos de Guerra' o 'Muerte de Civil en Acción Bélica' o 'Muerte Causada por Ataque a Bienes Civiles'."
        );
        $filas[4] = array(
            "cat" => array( 0 => 40),
            "titulo" => "Víctimas de 'Asesinato' por Persecución Política con Móviles Político-Sociales"
        );
        $filas[5] = array(
            "cat" => array( 0 => 50),
            "titulo" => "Víctimas de 'Asesinato' por Intolerancia Social con Móviles Político-Sociales"
        );


        $this->gen_tabla(
            $db,
            "", 
            $filas, 
            "Total víctimas que perdieron la vida",
            $where

        ); 

        echo "<h3>DERECHO A LA INTEGRIDAD</h3>";

        # LESIONES FÍSICA
        $filas = array();
        $filas[0] = array(
            "cat" => array( 0 => 23, 1 => 33),
            "titulo" => "Víctimas de 'Lesión Física' por Abuso de Autoridad e Intolerancia Social por agentes directos o indirectos del Estado (Violaciones a los Derechos Humanos)"
        );
        $filas[1] = array(
            "cat" => array( 0 => "13&!702"),
            "titulo" => "Víctimas de 'Lesión Física' por Persecución Política (Violaciones a los DH) que no representan infracciones al DIHC"
        );

        $filas[2] = array(
            "cat" => array( 0 => "13&702"),
            "titulo" => "Víctimas de 'Lesión Física' por agentes directos o indirectos del Estado por móviles de Persecución Política (Violaciones a los Derechos Humanos) y de 'Lesión Intencional a la Integridad Personal de Personas Protegidas' (Infracciones al Derecho Internacional Humanitario Consuetudinario)."
        );
        $filas[3] = array(
            "cat" => array( 0=> "702&!13", 2 => 98, 3=> 704, 4 => 88),
            "titulo" => "Víctimas de 'Lesión Intencional a la Integridad de Persona Protegida' (excepto casos de Violación a Derechos Humanos) o 'Lesiones a la Integridad Personal de Persona Protegida por Empleo de Métodos o Medios Ilícitos de Guerra' o 'Lesiones a la Integridad Personal de Persona Protegida como Consecuencia de una Acción Bélica' o 'Lesiones a la Integridad Personal de Persona Protegida como Consecuencia de Ataques a Bienes de Cáracter Civil'"
        );
        $filas[4] = array(
            "cat" => array( 0 => 43),
            "titulo" => "Víctimas de 'Lesión Física' por Presecución Política con Móviles Político-Sociales sin autor determinado"
        );
        $filas[5] = array(
            "cat" => array( 0 => 53),
            "titulo" => "Víctimas de 'Lesión Física' por Intolerancia Social con Móviles Político-Sociales sin autor determinado"
        );

         $this->gen_tabla(
            $db,
            "LESIONES FÍSICAS", 
            $filas, 
            "Total víctimas lesionadas",
            $where

        );

        # AMENAZAS
        $filas = array();
        $filas[0] = array(
            "cat" => array( 0 => 25, 1 => 35),
            "titulo" => "Víctimas de 'Amenaza' por Abuso de Autoridad e Intolerancia Social por agentes directos o indirectos del Estado (Violaciones a los Derechos Humanos)."
        );
        $filas[1] = array(
            "cat" => array( 0 => "15&!73"),
            "titulo" => "Víctimas de 'Amenaza' por agentes directos o indirectos del Estado por móviles de Persecución Política (Violaciones a los Derechos Humanos) que no constituyen Infracciones al Derecho Internacional Humanitario Consuetudinario."
        );
        $filas[2] = array(
            "cat" => array( 0 => "15&73"),
            "titulo" => "Víctimas simultaneamente de 'Amenaza' por agentes directos o indirectos del Estado por móviles de Persecución Política (Violaciones a los Derechos Humanos) y de 'Amenaza' que constituyen Infracciones al Derecho Internacional Humanitario Consuetudinario por parte de agentes directos o indirectos del Estado."
        );
        $filas[3] = array(
            "cat" => array( 0 => "73&!15"),
            "titulo" => "Víctimas de 'Amenaza' como Infracciones al Derecho Internacional Humanitario Consuetudinario por parte de la insurgencia o combatientes."
        );
        $filas[4] = array(
            "cat" => array( 0 => 45),
            "titulo" => "Víctimas de 'Amenaza' por Presecución Política con Móviles Político-Sociales sin autor determinado"
        );
        $filas[5] = array(
            "cat" => array( 0 => 55),
            "titulo" => "Víctimas de 'Amenaza por Intolerancia Social' con Móviles Político-Sociales sin autor determinado"
        );
         $this->gen_tabla(
            $db,
            "AMENAZAS", 
            $filas, 
            "Total víctimas de amenazas",
            $where
        );

        # TORTURA
        $filas = array();
        $filas[0] = array(
            "cat" => array( 0 => 22, 1 => 36),
            "titulo" => "Víctimas de 'Tortura' por Abuso de Autoridad e Intolerancia Social por agentes directos o indirectos del Estado (Violaciones a los Derechos Humanos)."
        );
        $filas[1] = array(
            "cat" => array( 0 => "12&!72"),
            "titulo" => "Víctimas de 'Tortura' por agentes directos o indirectos del Estado por móviles de Persecución Política (Violaciones a los Derechos Humanos) que no constituye Infracción al Derecho Internacional Humanitario Consuetudinario."
        );
        $filas[2] = array(
            "cat" => array( 0 => "12&72"),
            "titulo" => "Víctimas simultáneamente 'Tortura' por agentes directos o indirectos del Estado por móviles de Persecución Política (Violaciones a los Derechos Humanos) y de 'Tortura y Tratos Crueles e Inhumanos, Atentados contra la Dignidad Personal, Tratos Humillantes y Degradantes y Castigos Corporales, como Instrumentos de Guerra' que constituye Infracción al Derecho Internacional Humanitario Consuetudinario por parte de agentes directos o indirectos del Estado."
        );

        $filas[3] = array(
            "cat" => array( 0 => "72&!12"),
            "titulo" => "Víctimas de 'Tortura y Tratos Crueles e Inhumanos, Atentados contra la Dignidad Personal, Tratos Humillantes y Degradantes y Castigos Corporales, como Instrumentos de Guerra' como Infracciones al Derecho Internacional Humanitario Consuetudinario por parte de la insurgencia o combatientes."
        );
        $filas[4] = array(
            "cat" => array( 0 => 47),
            "titulo" => "Víctimas de 'Tortura' por Presecución Política con Móviles Político-Sociales sin autor determinado"
        );
        $filas[5] = array(
            "cat" => array( 0 => 56),
            "titulo" => "Víctimas de 'Tortura por Intolerancia Social' con Móviles Político-Sociales sin autor determinado"
        );
         $this->gen_tabla(
            $db,
            "TORTURA", 
            $filas, 
            "Total víctimas de tortura",
            $where
        );

        # ATENTADOS
        $filas = array();
        $filas[0] = array(
            "cat" => array( 0 => 26, 1 => 37),
            "titulo" => "Víctimas de 'Atentado' por Abuso de Autoridad o Intolerancia Social por agentes directos o indirectos del Estado (Violaciones a los Derechos Humanos)."
        );
        $filas[1] = array(
            "cat" => array( 0 => 16),
            "titulo" => "Víctimas de 'Atentado' por Persecución Política por agentes directos o indirectos del Estado (Violaciones a los Derechos Humanos)."
        );
        $filas[2] = array(
            "cat" => array( 0 => 46),
            "titulo" => "Víctimas de 'Atentado' por Presecución Política con Móviles Político-Sociales sin autor determinado"
        );
        $filas[3] = array(
            "cat" => array( 0 => 57),
            "titulo" => "Víctimas de 'Atentando por Intolerancia Social' con Móviles Político-Sociales sin autor determinado"
        );
         $this->gen_tabla(
            $db,
            "ATENTADOS", 
            $filas, 
            "Total víctimas de atentados",
            $where
        );

        # VIOLENCIA SEXUAL
        $filas = array();
        $filas[0] = array(
            "cat" => array( 0 => 29, 1 => 39),
            "titulo" => "Víctimas de 'Violencia Sexual' por móvil de Abuso de Autoridad o Intolerancia Social, perpetrada por agentes directos o indirectos del Estado (Violaciones a los Derechos Humanos)."
        );
        $filas[1] = array(
            "cat" => array( 0 => "19&!77"),
            "titulo" => "Víctimas de 'Violencia Sexual' por agentes directos o indirectos del Estado por móviles de Persecución Política (Violaciones a los Derechos Humanos) que no representan infracciones al Derecho Internacional Humanitario Consuetudinario."
        );
        $filas[2] = array(
            "cat" => array( 0 => "19&77"),
            "titulo" => "Víctimas de 'Violencia Sexual' por agentes directos o indirectos del Estado por móviles de Persecución Política (Violaciones a los Derechos Humanos) y que constituyen al mismo tiempo infracciones al Derecho Internacional Humanitario Consuetudinario."
        );
        $filas[3] = array(
            "cat" => array( 0 => "77&!19"),
            "titulo" => "Víctimas de 'Violencia Sexual' que constituyen infracciones al Derecho Internacional Humanitario Consuetudinario por parte de la insurgencia o combatientes."
        );
        $filas[4] = array(
            "cat" => array( 0 => 420),
            "titulo" => "Víctimas de 'Violencia Sexual' por Presecución Política con Móviles Político-Sociales sin autor determinado"
        );
        $filas[5] = array(
            "cat" => array( 0 => 520),
            "titulo" => "Víctimas de 'Violencia Sexual por Intolerancia Social' con Móviles Político-Sociales sin autor determinado"
        );
         $this->gen_tabla(
            $db,
            "VIOLENCIA SEXUAL", 
            $filas, 
            "Total víctimas de violencia sexual",
            $where
        );

        echo "<h3>DERECHO A LA LIBERTAD</h3>";
        # DERECHO A LA LIBERTAD
        $filas = array();
        $filas[0] = array(
            "cat" => array( 0 => 21, 1 => 24, 2=>241, 3=>302, 4=>301, 5=>341),
            "titulo" => "Víctimas de 'Desaparición Forzada e Involuntaria', 'Detención Arbitraria' y 'Judicialización Arbitraria' por móvil de Abuso de Autoridad o Intolerancia Social, perpetrada por agentes directos o indirectos del Estado (Violaciones a los Derechos Humanos)."
        );
        $filas[1] = array(
            "cat" => array( 0 => 14, 1 => 141, 2=>101),
            "titulo" => "Víctimas de 'Detención Arbitraria',  'Judicialización Arbitraria' y 'Deportación' por móviles de Persecución Política (Violaciones a los Derechos Humanos)."
        );
        $filas[2] = array(
            "cat" => array( 0 => "11&!76"),
            "titulo" => "Víctimas de 'Desaparicion Forzada e Involuntaria' por agentes directos o indirectos del Estado por móviles de Persecución Política (Violaciones a los Derechos Humanos) que no representan infracciones al Derecho Internacional Humanitario Consuetudinario."
        );
        $filas[3] = array(
            "cat" => array( 0 => "11&76"),
            "titulo" => "Víctimas de 'Desaparicion Forzada e Involuntaria' por agentes directos o indirectos del Estado por móviles de Persecución Política (Violaciones a los Derechos Humanos) y que constituyen al mismo tiempo infracciones al Derecho Internacional Humanitario Consuetudinario."
        );
        $filas[4] = array(
            "cat" => array( 0 => "76&!11"),
            "titulo" => "Víctimas de 'Desaparición Forzada como Instrumento de Guerra' que constituyen infracciones al Derecho Internacional Humanitario Consuetudinario pero no violación a los Derechos Humanos."
        );
        $filas[5] = array(
            "cat" => array( 0 => 48, 1 => 41),
            "titulo" => "Víctimas de 'Rapto por Móvies Politicos' y 'Secuestro perpetrado por organizaciones insurgentes'."
        );
        $filas[7] = array(
            "cat" => array( 0 => 58),
            "titulo" => "Víctimas de 'Rapto' por móviles de Intolerancia Social sin autor determinado."
        );
         $this->gen_tabla(
            $db,
            "", 
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

        $e =& $this->addElement('header', null, 
            'Cifras del Consolidado General de Víctimas'
        );
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
