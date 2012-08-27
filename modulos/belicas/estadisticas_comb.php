<?php
//  vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
* Estadísticas victimas combatientes
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
*/

/**
 * Estadísticas victimas combatientes
 */
require_once "aut.php";
require_once $_SESSION['dirsitio'] . "/conf.php";
require_once 'HTML/QuickForm/Page.php';
require_once 'HTML/QuickForm/Action.php';
require_once 'HTML/QuickForm/Controller.php';
require_once 'HTML/QuickForm/Action/Display.php';
require_once 'HTML/QuickForm/Action/Next.php';
require_once 'HTML/QuickForm/Action/Back.php';
require_once 'HTML/QuickForm/Action/Jump.php';
require_once 'HTML/QuickForm/header.php';
require_once 'HTML/QuickForm/date.php';
require_once 'HTML/QuickForm/text.php';
require_once 'PagTipoViolencia.php';
require_once 'ResConsulta.php';

/**
 * Responde a botón Consulta
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class AccionEstadisticasComb extends HTML_QuickForm_Action
{

    /**
     * Muestra un dato
     *
     * @param string  $t    Selector
     * @param unknown $np   Nombre de organización
     * @param unknown $ndep Departamento
     * @param unknown $nmun Municipio
     * @param array   $res  Vector con otros
     *
     * @return void
     * @access public
     */
    function muestraUno($t, $np, $cdep, $html_nomdep, $cmun, $nommun, $res)
    {
        if ($t == 'Organización') {
            echo "<td>" . htmlentities($np, ENT_COMPAT, 'UTF-8') . "</td>";
        } elseif ($t == 'C. Dep.') {
           echo "<td>" . htmlentities($cdep, ENT_COMPAT, 'UTF-8') . "</td>";
        } elseif ($t == 'Dep.') {
            // Escapado tras consulta
           echo "<td>" . $html_nomdep . "</td>";
        } elseif ($t == 'C. Mun.') {
           echo "<td>" . htmlentities($cmun, ENT_COMPAT, 'UTF-8') . "</td>";
        } elseif ($t == 'Mun.') {
           echo "<td>" . htmlentities($nommun, ENT_COMPAT, 'UTF-8') . "</td>";
        } else {
            echo "<td>" .
                (isset($res[$t]) ? (int)$res[$t] : 0) ."</td>";
        }
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
        $d = objeto_tabla('departamento');
        if (PEAR::isError($d)) {
            die($d->getMessage());
        }
        $fb =& DB_DataObject_FormBuilder::create($d);
        $db =& $d->getDatabaseConnection();

        $pFini = var_post_escapa('fini');
        $pFfin = var_post_escapa('ffin');
        $pMunicipio = var_post_escapa('municipio');
        $pDepartamento = var_post_escapa('departamento');

        verifica_sin_CSRF($page->_submitValues);

        $cons = 'cons';
        $cons2="cons2";
        $where = "";

        consulta_and(
            $db, $where, "caso.fecha",
            $GLOBALS['consulta_web_fecha_min'], ">="
        );
        consulta_and(
            $db, $where, "caso.fecha",
            $GLOBALS['consulta_web_fecha_max'], "<="
        );

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

        $wgeo = '';
        $sgeo = '';
        $tgeo = '';
        $ggeo = '';
        $ogeo = '';
        $cab = array('Organización');
        if ($pDepartamento != '') {
            $cab[] = 'C. Dep.';
            $cab[] = 'Dep.';
            $sgeo .= 'iddep, ';
        }
        if ($pMunicipio != '') {
            $cab[] = 'C. Mun.';
            $cab[] = 'Mun.';
            $sgeo = 'iddep, idmun, ';
        }
        $dr = objeto_tabla('resagresion');
        $dr->find();
        $dr->orderBy('id');
        while ($dr->fetch()) {
            $cab[] = $dr->nombre;
        }
        $cab[] = 'Total';

        $nomdep = htmlentities_array(
            $db->getAssoc('SELECT id, nombre FROM departamento')
        );
        $rmun = hace_consulta(
            $db,
            'SELECT id_departamento, id, nombre FROM municipio'
        );
        $row = array();
        $nommun = array();
        while ($rmun->fetchInto($row)) {
            $nommun[$row[0]][$row[1]] = $row[2];
        }
        hace_consulta($db, "DROP VIEW vestcomb", false, false);
        $q = "CREATE VIEW vestcomb (presp, iddep, idmun, nomres, cid) AS
            (SELECT presponsable.nombre,
            ubicacion.id_departamento, ubicacion.id_municipio,
            resagresion.nombre,
            combatiente.id FROM resagresion, caso, ubicacion,
            combatiente, presponsable
            WHERE $where AND
            ubicacion.id_caso = caso.id AND
            resagresion.id = id_resultado_agresion AND
            caso.id = combatiente.id_caso AND
            presponsable.id = organizacionarmada";
/*        foreach (array("municipio", "clase") as $t) {
            $q .= " UNION SELECT presponsable.nombre,
                {$t}_caso.id_departamento, {$t}_caso.id_municipio,
                resagresion.nombre, combatiente.id
                FROM resagresion, caso, {$t}_caso, combatiente,
                presponsable
                WHERE $where AND {$t}_caso.id_caso = caso.id AND
                resagresion.id = id_resultado_agresion AND
                caso.id = combatiente.id_caso AND
                presponsable.id = organizacionarmada";
} */
        $q .= " )";
        //echo "q= $q";
        hace_consulta($db, "$q");

        $q = "SELECT presp, $sgeo nomres, count(cid) FROM vestcomb
            GROUP BY presp, $sgeo nomres ORDER BY presp, $sgeo nomres";
        //echo "<hr>q=$q";
        $result = hace_consulta($db, $q);
        if (PEAR::isError($result)) {
            die("Problema ejecutando consulta preliminar: '$q', " .
            $result->getMessage()."'"
            );
        }
        $n = array();
        $row = array();
        while ($result->fetchInto($row)) {
            if ($pMunicipio != '') {
                $n[$row[0]][$row[1]][$row[2]][$row[3]]=(int)$row[4];
                if (!isset($n[$row[0]][$row[1]][$row[2]]['Total'])) {
                    $n[$row[0]][$row[1]][$row[2]]['Total']=0;
                }
                $n[$row[0]][$row[1]][$row[2]]['Total']+=(int)$row[4];
            } else if ($pDepartamento != '') {
                $n[$row[0]][$row[1]][$row[2]]=(int)$row[3];
                if (!isset($n[$row[0]][$row[1]]['Total'])) {
                    $n[$row[0]][$row[1]]['Total']=0;
                }
                $n[$row[0]][$row[1]]['Total']+=(int)$row[3];
            } else {
                $n[$row[0]][$row[1]]=(int)$row[2];
                if (!isset($n[$row[0]]['Total'])) {
                    $n[$row[0]]['Total']=0;
                }
                $n[$row[0]]['Total']+=(int)$row[2];
            }

        }
        encabezado_envia();
        echo "<table border=\"1\"><tr>";
        foreach ($cab as $k => $t) {
            echo "<th>" . htmlentities($t, ENT_COMPAT, 'UTF-8') . "</th>";
        }
        echo "</tr>\n";
        foreach ($n as $np => $dep) {
            if ($pDepartamento == '' && $pMunicipio == '') {
                echo "<tr>";
                foreach ($cab as $k => $t) {
                    $this->muestraUno($t, $np, null, null, null, null, $dep);
                }
                echo "</tr>\n";
            } else {
                foreach ($dep as $ndep => $mun) {
                    if ($pMunicipio != '') {
                        foreach ($mun as $nmun => $res) {
                            echo "<tr>";
                            foreach ($cab as $k => $t) {
                                $this->muestraUno(
                                    $t, $np, $ndep,
                                    $nomdep[$ndep], $nmun,
                                    $nommun[$ndep][$nmun],
                                    $res
                                );
                            }
                            echo "</tr>\n";
                        }
                    } else {
                        echo "<tr>";
                        foreach ($cab as $k => $t) {
                            $this->muestraUno(
                                $t, $np, $ndep, $nomdep[$ndep],
                                null, null, $mun
                            );
                        }
                        echo "</tr>\n";
                    }
                }
            }
        }
        echo "</table>";
        pie_envia();
    }
}


/**
 * Formulario de Estadísticas por Combatiente
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class PagEstadisticasComb extends HTML_QuickForm_Page
{

    /**
     * Constructora.
     *
     * @return void
     */
    function PagEstadisticasComb()
    {
        $this->HTML_QuickForm_Page('estadisticas', 'post', '_self', null);

        $this->addAction('id_tviolencia', new CamTipoViolencia());

        $this->addAction('consulta', new AccionEstadisticasComb());
    }


    /**
     * Id del tipo de violencia
     *
     * @return string Id. tipo de violencia
     * @access public
     */
    function idTipoViolencia()
    {
        $ntipoviolencia= null;
        if (isset($this->_submitValues['id_tviolencia'])) {
            $ntipoviolencia = (int)$this->_submitValues['id_tviolencia'] ;
        } else if (isset($_SESSION['id_tviolencia'])) {
            $ntipoviolencia = $_SESSION['id_tviolencia'] ;
        }
        return $ntipoviolencia;
    }


    /**
     * Id. supracategoria
     *
     * @return string Supracategoria
     * @access public
     */
    function idSupracategoria()
    {
        $nclase = null;
        if (isset($this->_submitValues['id_supracategoria'])) {
            return  (int)$this->_submitValues['id_supracategoria'] ;
        }
        return null;
    }


    /**
     * Cosntruye formulario
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
            'header', null,
            'Conteos Victimas Combatientes'
        );

        //    $e =& $this->addElement('static', 'fini', 'Victimas ');

        $cy = date('Y');
        if ($cy < 2005) {
            $cy = 2005;
        }
    $slan = isset($_SESSION['LANG']) ? $_SESSION['LANG'] : 'es';

        $e =& $this->addElement(
            'date', 'fini', 'Desde: ',
            array('language' => $slan, 'addEmptyOption' => true,
            'minYear' => $GLOBALS['anio_min'], 'maxYear' => $cy
            )
        );
        $e =& $this->addElement(
            'date', 'ffin', 'Hasta',
            array('language' => $slan, 'addEmptyOption' => true,
            'minYear' => $GLOBALS['anio_min'], 'maxYear' => $cy
            )
        );

        $ae = array();
        $sel =& $this->createElement(
            'checkbox',
            'departamento', 'Departamento', 'Departamento'
        );
        $sel->setValue(true);
        $ae[] =& $sel;

        $sel =& $this->createElement(
            'checkbox',
            'municipio', 'Municipio', 'Municipio'
        );
        $sel->setValue(true);
        $ae[] =& $sel;
        $this->addGroup($ae, null, 'Ubicación', '&nbsp;', false);

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

    $num = (int)$_REQUEST['num'];
    $this->addElement('hidden', 'num', $num);


        if (!isset($_POST['evita_csrf'])) {
            agrega_control_CSRF($this);
        }

        $this->setDefaultAction('consulta');

    }

}

function muestra($dsn, $accno)
{
    $aut_usuario = "";
    autenticaUsuario($dsn, $aut_usuario, 21);

    $wizard =& new HTML_QuickForm_Controller('EstadisticasComb', false);
    $consweb = new PagEstadisticasComb($mreq);

    $wizard->addPage($consweb);


    $wizard->addAction('display', new HTML_QuickForm_Action_Display());
    $wizard->addAction('jump', new HTML_QuickForm_Action_Jump());

    $wizard->addAction('process', new AccionEstadisticasComb());

    $wizard->run();
}


?>
