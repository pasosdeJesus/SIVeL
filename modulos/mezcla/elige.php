<?php
//  vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Detecta víctimas repetidas
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2011 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
*/

/**
 * Detecta víctimas repetidas
 */

require_once "aut.php";
require_once $_SESSION['dirsitio'] . "/conf.php";
require_once 'HTML/QuickForm/Controller.php';
require_once 'HTML/QuickForm/Action/Display.php';
require_once 'HTML/QuickForm/Action/Next.php';
require_once 'HTML/QuickForm/Action/Back.php';
require_once 'HTML/QuickForm/Action/Jump.php';
require_once 'HTML/QuickForm/header.php';
require_once 'HTML/QuickForm/date.php';
require_once 'HTML/QuickForm/text.php';
require_once 'PagTipoViolencia.php';
require_once 'PagUbicacion.php';
require_once 'ResConsulta.php';
require_once 'misc.php';

foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
    list($n, $c, $o) = $tab;
    if (($d = strrpos($c, "/"))>0) {
        $c = substr($c, $d+1);
    }
    // @codingStandardsIgnoreStart
    require_once "$c.php";
    // @codingStandardsIgnoreEnd
}



/**
 * Acción que responde al boton Comparar dos caso por numero
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  http://creativecommons.org/licenses/publicdomain/ Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class AccionComparaDos extends HTML_QuickForm_Action
{

    /**
     * Busca similares
     *
     * @return void
     */
    static function busca() 
    {
        $pIds = "";
        $do = objeto_tabla("caso");
        $db =& $do->getDatabaseConnection();
        $figuales = isset($_POST['figuales']) && $_POST['figuales'] == '1';
        $diguales = isset($_POST['diguales']) && $_POST['diguales'] == '1';
        // Fechas iguales, departamentos iguales, nombres parecidos pero
        // no en homonimos
        hace_consulta(
            $db, 'REFRESH MATERIALIZED VIEW vvictimasoundexesp'
        );
        $cons = "SELECT DISTINCT v1.id_caso, v2.id_caso AS id_caso
            FROM vvictimasoundexesp AS v1, vvictimasoundexesp AS v2, 
            caso AS c1, caso AS c2, ubicacion AS u1, ubicacion AS u2
            WHERE v1.id_caso = c1.id AND v2.id_caso = c2.id
            AND u1.id_caso = c1.id AND u2.id_caso = c2.id ";
        if ($diguales) {
            $cons .= " AND u1.id_departamento = u2.id_departamento ";
        }
        if ($figuales) {
            $cons .= " AND c1.fecha = c2.fecha";
        }
        $cons .=" AND c1.id<c2.id
            AND (POSITION(v1.nomsoundexesp IN v2.nomsoundexesp)>0 OR
                POSITION(v2.nomsoundexesp IN v1.nomsoundexesp)>0) 
            AND (v1.id_persona, v2.id_persona) NOT IN 
                (SELECT * FROM homonimia) 
            AND UPPER(v1.nomap) <> 
                'PERSONA SIN IDENTIFICAR'
            AND UPPER(regexp_replace(v1.nomap, '[ .,]', '', 'g')) <> 'N'
            AND UPPER(regexp_replace(v1.nomap, '[ .,]', '', 'g')) <> 'NN'
            AND UPPER(regexp_replace(v1.nomap, '[ .,]', '', 'g')) <> 'NNN'
            AND UPPER(regexp_replace(v1.nomap, '[ .,]', '', 'g')) <> 'NNNN'
            ORDER BY 2 DESC, 1
            ";
        $r = hace_consulta($db, $cons);
        $mezcladoen = array();
        $row = array();
        while ($r->fetchInto($row)) {
            $nr = 0;
            $id1 = $row[0];
            $id2 = $row[1];
            if (isset($mezcladoen[$id2])) {
                continue;
            }
            $mezcladoen[$id2] = $id1;
        }
        $sep = "";
        $pIds = "";
        foreach ($mezcladoen as $id2 => $id1) {
            $pIds .= $sep . $id1 . " " . $id2;
            $sep = " ";
        }
        return $pIds;
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
        $pIds = "";
        if (!isset($_REQUEST['ids']) || $_REQUEST['ids'] == '') {

            if (isset($_POST['_mezclaFiltro'])) {
                
                $pIds = AccionComparaDos::busca();
            }
            foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
                list($n, $c, $o) = $tab;
                //echo "OJO $n, $c, $o<br>";
                if (($d = strrpos($c, "/"))>0) {
                    $c = substr($c, $d+1);
                }
                if (is_callable(array($c, 'mezclaAccionFiltro'))) {
                    $pIds = call_user_func_array(
                        array($c, 'mezclaAccionFiltro'),
                        array(&$this)
                    );
                    if ($pIds != "") {
                        break;
                    }
                }
            }
        } else {
            $pIds   = var_post_escapa('ids');
        }
        $a = explode(" ", $pIds);
        if (count($a) == 0) {
            error_valida("No se encontraron parejas de casos");
            return false;
        } else if (count($a) < 2 || count($a) % 2 != 0) {
            error_valida(
                "Debe ingresar parejas de códigos separados por espacio "
                . "(cuenta=" . count($a) . ")", null
            );
            return false;
        }
        foreach ($a as $nc) {
            if ($nc != (int)$nc) {
                error_valida(
                    "Debe ingresar parejas de códigos separados por un espacio "
                    . " nc=$nc", null
                );
                return false;
            }
        }

        $_SESSION['mezcla_ids'] = $pIds;
        header("Location: opcion.php?num=1004&ids=sesion");

        die("compara");
    }
}

/**
 * Responde a botón Consulta
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class AccionVictimasrep extends HTML_QuickForm_Action
{

    /**
     * Presenta categorias que conformaran cada columna de la tabla.
     * Depuración
     *
     * @param handle  &$db    Conexión a BD
     * @param array   $cataux Cat
     * @param unknown $pResto pResto
     * @param unknown $ncol   Número de columnas
     *
     * @return void
     */
    function depTabla(&$db, $cataux, $pResto, $ncol)
    {
        $n = 1;
        $sep = "";
        foreach ($cataux as $l => $lc) {
            $p = objeto_tabla('parametros_reporte_consolidado');
            if (PEAR::isError($p)) {
                die($p->getMessage());
            }
            if ($n==($ncol+1)) {
                $rot = '(Resto)';
            } else if ($p->get($n)==1) {
                $rot = "(" . $p->rotulo.")";
            } else {
                $rot = '';
            }
            if ($n<($ncol+1) || ($pResto && $n==($ncol+1))) {
                $html_l = $sep . "<b>" . htmlentities($l, ENT_COMPAT, 'UTF-8')
                    . " " . htmlentities($rot, ENT_COMPAT, 'UTF-8') . ":</b>";
                echo $html_l;
                foreach ($lc as $cc) {
                    echo htmlentities($cc, ENT_COMPAT, 'UTF-8') . " ";
                }
                $sep = "; ";
            }
            $n++;
        }
    }




    /**
     * Realiza consulta
     *
     * @param unknown &$page      Página
     * @param unknown $actionName nombre de la acción
     *
     * @return void
     * @access public
     */
    function perform(&$page, $actionName)
    {
        $d = objeto_tabla('categoria');
        if (PEAR::isError($d)) {
            die($d->getMessage());
        }
        //verifica_sin_CSRF($page->_submitValues);

        $fb =& DB_DataObject_FormBuilder::create($d);
        $db =& $d->getDatabaseConnection();

        $pFini      = var_post_escapa('fini', $db);
        $pFfin      = var_post_escapa('ffin', $db);
        assert($pFini['Y'] == '' || ($pFini['Y'] >= 1900));
        assert($pFfin['Y'] == '' || ($pFfin['Y'] >= 1900));
        $pIdClase   = (int)var_post_escapa('id_clase');
        $pIdMunicipio = (int)var_post_escapa('id_municipio', $db);
        $pIdDepartamento = (int)var_post_escapa('id_departamento', $db);

        $campos = array('caso_id' => 'Cód.');
        $tablas = "victima, caso";
        $where = "";

        consulta_and_sinap($where, "victima.id_caso", "caso.id");
        consulta_and(
            $db, $where, "caso.fecha",
            $GLOBALS['consulta_web_fecha_min'], ">="
        );
        consulta_and(
            $db, $where, "caso.fecha",
            $GLOBALS['consulta_web_fecha_max'], "<="
        );

        $tgeo = "";
        if ($pIdClase != '') {
            $tgeo = "ubicacion, ";
            consulta_and_sinap($where, "ubicacion.id_caso", "caso.id");
            consulta_and_sinap(
                $where, "ubicacion.id_departamento", $pIdDepartamento
            );
            consulta_and_sinap($where, "ubicacion.id_municipio", $pIdMunicipio);
            consulta_and_sinap($where, "ubicacion.id_clase", $pIdClase);
        } else if ($pIdMunicipio != '') {
            $tgeo = "ubicacion, ";
            consulta_and_sinap($where, "ubicacion.id_caso", "caso.id");
            consulta_and_sinap(
                $where, "ubicacion.id_departamento", $pIdDepartamento
            );
            consulta_and_sinap($where, "ubicacion.id_municipio", $pIdMunicipio);
        } else if ($pIdDepartamento != '') {
            $tgeo = "ubicacion, ";
            consulta_and_sinap($where, "ubicacion.id_caso", "caso.id");
            $where .= " AND ubicacion.id_departamento IN "
                . "('$pIdDepartamento', '1000') ";
        }

        if ($pFini['Y'] != '') {
            consulta_and(
                $db, $where, "caso.fecha",
                arr_a_fecha($pFini, true), ">="
            );
        }
        if ($pFfin['Y'] != '') {
            consulta_and(
                $db, $where, "caso.fecha",
                arr_a_fecha($pFfin, false), "<="
            );
        }

        $q = " SELECT caso.id, persona.id, " .
            " persona.nombres || ' ' || persona.apellidos, caso.fecha " .
            " FROM $tgeo persona, victima, caso " .
            " WHERE persona.id=victima.id_persona " .
            " AND $where " ;
        $q = "(" . $q . ") ORDER BY 3";
        //echo "q es $q<br>";
        $result = hace_consulta($db, $q);

        $datv = array();
        $dn = array();
        $tv = 0;
        while ($result->fetchInto($row)) {
            $datv[$tv] = array($row[0], $row[1], $row[2], $row[3]);
            $tv++;
        }

        echo "<p>Total de casos con v&iacute;ctima: " . (int)$tv . "</p>";
        $suma = array();
        echo "<form  action='opcion.php?num=1004' method='post' target='_blank'>";
        echo "<input name='Comparar' type='submit' class='form' id='Comparar' "
            . " value='Comparar'>";
        echo "<table border='1'>\n";
        echo "<tr>";
        echo "<td>IdCaso</td><td>IdVic</td>";
        echo "<th>Fecha</th><th>Ubicaci&oacute;n</th><th>V&iacute;ctimas</th>";

        for ($v = 0;$v < $tv; $v++) {
            $idcaso = $datv[$v][0];
            $idvic = $datv[$v][1];
            $nom = $datv[$v][2];
            $fecha = $datv[$v][3];
            $ubi = "";
            $u =&  objeto_tabla('ubicacion');
            $u->id_caso = $idcaso;
            if ($u->find() == 0) {
                $ubi = "";
            } else {
                $u->fetch();
                $d = $u->getLink('id_departamento');
                $ubi = trim($d->nombre);
                if (isset($u->id_municipio)) {
                    $m =&  objeto_tabla('municipio');
                    $m->id = $u->id_municipio;
                    $m->id_departamento = $u->id_departamento;
                    if ($m->find()==0) {
                        die("Caso " . $idcaso .
                            " referencia municipio inexistente " .
                            $m->id.", " . $m->id_departamento
                        );
                    }
                    $m->fetch();
                    $ubi .= " - ".trim($m->nombre);
                }
            }

            echo "<tr>";
            echo "<td><a href='captura_caso.php?modo=edita&id=" .
                (int)$idcaso . "'>" . (int)$idcaso . "</a></td>";
            echo "<td>" . (int)$idvic . "</td>";
            echo "<td>" . htmlentities($fecha, ENT_COMPAT, 'UTF-8') . "</td><td>" .
                    htmlentities($ubi, ENT_COMPAT, 'UTF-8') . "</td><td>" .
                    trim(htmlentities($nom, ENT_COMPAT, 'UTF-8')) . "</td>";
            echo "<td><input name='id" . (int)$idcaso .  "' "
                . " type=\"checkbox\"/></td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</form>";
    }
}


/**
 * Formulario de reporte consolidado
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class PagVictimasrep extends HTML_QuickForm_Page
{

    /**
     * Constructora.
     *
     * @return void
     */
    function PagVictimasrep()
    {
        $this->HTML_QuickForm_Page('victimiasrep', 'post', '_self', null);

        $this->addAction('id_departamento', new CamDepartamento());
        $this->addAction('id_municipio', new CamMunicipio());


        $this->addAction('compara', new AccionComparaDos());
        $this->addAction('consulta', new AccionVictimasrep());
    }


    /**
     * Construye formulario
     *
     * @return void
     * @access public
     */
    function buildForm()
    {
        encabezado_envia();
        $this->_formBuilt = true;
        $x =&  objeto_tabla('departamento');
        $db = $x->getDatabaseConnection();

        $e =& $this->addElement(
            'header', null, 'Compara pares de casos por código'
        );
        $e =& $this->addElement('text', 'ids');
        $e->setSize(80);

        $e =& $this->addElement(
            'submit',
            $this->getButtonName('compara'), 'Comparar'
        );

        $e =& $this->addElement(
            'header', null,
            'Reporte para identificar v&iacute;ctimas repetidas'
        );

        list($dep, $mun, $cla) = PagUbicacion::creaCampos(
            $this, 'id_departamento', 'id_municipio', 'id_clase'
        );
        $this->addElement($dep);
        $this->addElement($mun);
        $this->addElement($cla);
        PagUbicacion::modCampos(
            $db, $this, 'id_departamento', 'id_municipio', 'id_clase',
            null, null, null
        );


        $cy = @date('Y');
        if ($cy < 2005) {
            $cy = 2005;
        }
        $e =& $this->addElement(
            'date', 'fini', 'Desde: ',
            array('language' => 'es', 'addEmptyOption' => true,
            'minYear' => $GLOBALS['anio_min'], 'maxYear' => $cy
            )
        );
        $e =& $this->addElement(
            'date', 'ffin', 'Hasta',
            array('language' => 'es', 'addEmptyOption' => true,
            'minYear' => $GLOBALS['anio_min'], 'maxYear' => $cy
            )
        );
        $e =& $this->addElement('hidden', 'num', (int)$_REQUEST['num']);

        $prevnext = array();
        $sel =& $this->createElement(
            'submit',
            $this->getButtonName('consulta'), 'Consulta'
        );
        $prevnext[] =& $sel;
        $this->addGroup($prevnext, null, '', '&nbsp;', false);

        $e =& $this->addElement('header', null, 'Buscar similares');
        $e =& $this->addElement('hidden', '_mezclaFiltro', 'si');

        $e =& $this->addElement(
            'checkbox',
            'figuales', _('Fechas iguales'), ''
        );
        $e =& $this->addElement(
            'checkbox',
            'diguales', _('Departamentos iguales'), ''
        );

        $e =& $this->addElement(
            'submit',
            $this->getButtonName('compara'), 'Comparar'
        );

        foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
            list($n, $c, $o) = $tab;
            if (($d = strrpos($c, "/"))>0) {
                $c = substr($c, $d+1);
            }
            if (is_callable(array($c, 'mezclaFiltro'))) {
                call_user_func_array(
                    array($c, 'mezclaFiltro'),
                    array(&$db, &$this)
                );
            }
        }

        $tpie = "<div align=right><a href=\"index.php\">" .
            "Men&uacute; Principal</a></div>";
        $e =& $this->addElement('header', null, $tpie);

        if (!isset($_POST['evita_csrf'])) {
            $_SESSION['sin_csrf'] = mt_rand(0, 1000);
        }
        $this->addElement('hidden', 'evita_csrf', $_SESSION['sin_csrf']);

        $this->setDefaultAction('consulta');

    }

}

/**
 * Punto de entrada al formulario
 *
 * @param string $dsn URL a base de datos
 *
 * @return void
 */
function muestra($dsn)
{
    $aut_usuario = "";
    autentica_usuario($dsn, $accno, $aut_usuario, 31);
    encabezado_envia('Elegir');

    $wizard =& new HTML_QuickForm_Controller('Victimasrep', false);
    $consweb = new PagVictimasrep();

    $wizard->addPage($consweb);


    $wizard->addAction('display', new HTML_QuickForm_Action_Display());
    $wizard->addAction('jump', new HTML_QuickForm_Action_Jump());

    $wizard->addAction('process', new AccionVictimasrep());

    $wizard->run();
}

?>
