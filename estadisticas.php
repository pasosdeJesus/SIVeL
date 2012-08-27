<?php
//  vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Estadísticas victimas individuales/casos
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
 * Estadísticas victimas individuales/casos
 */
require_once "aut.php";
require_once $_SESSION['dirsitio'] . "/conf.php";

$aut_usuario = "";
autenticaUsuario($dsn, $aut_usuario, 21);

require_once $_SESSION['dirsitio'] . "/conf_int.php";
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
require_once 'misc.php';

/**
 * Responde a botón consulta
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class AccionEstadisticasInd extends HTML_QuickForm_Action
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
        $d = objeto_tabla('departamento');
        if (PEAR::isError($d)) {
            die($d->getMessage());
        }

        $fb =& DB_DataObject_FormBuilder::create($d);
        $db =& $d->getDatabaseConnection();

        $pFini      = var_post_escapa('fini');
        $pFfin      = var_post_escapa('ffin');
        $pTipo      = var_post_escapa('id_tviolencia');
        $pSupra     = (int)var_post_escapa('id_supracategoria');
        $pSegun     = var_post_escapa('segun');
        //$pQue       = var_post_escapa('que');
        $pMuestra   = var_post_escapa('muestra');
        $pMunicipio = (int)var_post_escapa('municipio');
        $pDepartamento = (int)var_post_escapa('departamento');
        $pSinCatRepetidas = var_post_escapa('sin_cat_repetidas');

        //verifica_sin_CSRF($page->_submitValues);

        $tGeo = '';
        if ($pMunicipio != '') {
            $tGeo = 'departamento, municipio, ';
        } elseif ($pDepartamento != '') {
            $tGeo = 'departamento, ';
        }
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

        $tablaSegun = $titSegun = "";
        $tCat = "";
        $tQue = "";
        $condSegun = "";
        $distinct = 'DISTINCT';
        if ($pSegun == 'id_rangoedad') {
            consulta_and_sinap(
                $where, "victima.id_rangoedad", "rangoedad.id"
            );
            $campoSegun = "id_rangoedad";
            $cfSegun = "rangoedad.rango";
            $tablaSegun = "rangoedad, ";
            $condSegun = "AND rangoedad.id=$cons2.id_rangoedad";
            $titSegun = 'Rango de Edad';
        } elseif ($pSegun == 'sexo') {
            $campoSegun = "sexo";
            $cfSegun = "sexo";
            $tablaSegun = "";
            $condSegun = "";
            $titSegun = 'Sexo';
        } elseif ($pSegun == 'id_presponsable') {
            $distinct = '';
            $cfSegun = 'presponsable.nombre';
            $tablaSegun = "presponsable, ";
            $condSegun = "AND presponsable.id=$cons2.id_presponsable";
            $titSegun = 'P. Responsable';
            consulta_and_sinap(
                $where, "acto.id_presponsable",
                "presponsable.id"
            );
            consulta_and_sinap(
                $where, "victima.id_persona", "acto.id_persona"
            );
            consulta_and_sinap(
                $where, "victima.id_caso",
                "acto.id_caso"
            );
            $tQue .= "";
            $campoSegun = "acto.id_presponsable";
        } elseif ($pSegun == 'meses') {
            $campoSegun = "extract(year from fecha) || '-' " .
                "|| lpad(cast(extract(month from fecha) as text), 2, " .
                "cast('0' as text))";
            $cfSegun = "meses";
            $tablaSegun= "";
            $titSegun = 'Mes';
        } elseif ($pSegun != '') {
            $campoSegun = $pSegun;
            $ant = explode("_", $pSegun);
            $tablaSegun = "";
            $tsep = "";
            for ($i = 1; $i < count($ant); $i++) {
                $tablaSegun .= $tsep . $ant[$i];
                $tsep = "_";
            }
            $cfSegun= $tablaSegun . ".nombre";
            consulta_and_sinap(
                $where, "victima.$campoSegun",
                "$tablaSegun.id"
            );
            $condSegun = "AND $tablaSegun.id=$cons2.$campoSegun";
            $titSegun = $GLOBALS['etiqueta'][$tablaSegun];
            $tablaSegun .= ",";
        }

        $cab = array();
        if ($titSegun != "") {
            $cab[] = $titSegun;
        }
        $tDep = $gDep = "";
        if ($pDepartamento == "1") {
            $tDep = "departamento.id, trim(departamento.nombre), ";
            $gDep = "departamento.id, departamento.nombre, ";
            $cab[] = _('C. Dep.');
            $cab[] = _('Departamento');
        }
        $tMun = $gMun = "";
        if ($pMunicipio == "1") {
            $tMun = "municipio.id, trim(municipio.nombre), ";
            $gMun = "municipio.id, municipio.nombre, ";
            $cab[] = _('C. Mun.');
            $cab[] = _('Municipio');
        }
        $cab[] = _('Tipo de Violencia');
        $cab[] = _('Supracategoria');
        $cab[] = _('Categoria');
        if ($pSegun == 'id_presponsable') {
            $cab[] = _('N. Actos');
            // Un acto es un hecho de violencia cometido por un actor
            // contra una víctima
        } else {
            $cab[] = _('N. Víctimizaciones');
            // Una victimización o violación es un tipo de violencia
            // sufrido por una víctima (sin examinar el o los responsables)
            // Para casos con un sólo presunto responsable actos y
            // violaciones/victimizaciones coincide.
            // Un hecho contra una victima con N presuntos responsables,
            // cuenta como una victimización pero como N actos
        }
        $tCat .= 'acto';
        $tQue .= "victima, persona, $tCat, ";
        consulta_and_sinap($where, "victima.id_caso", "caso.id");
        consulta_and_sinap($where, "victima.id_persona", "persona.id");
        consulta_and_sinap($where, "persona.id", "acto.id_persona");
        consulta_and_sinap($where, "acto.id_caso", "caso.id");
        consulta_and_sinap($where, "acto.id_categoria", "categoria.id");
        $cCons = 'id_persona';
        $pQ1 = 'id_persona, ';
        $pQ1sel = 'victima.id_persona, ';

        $tablas = $tablaSegun . "$tQue caso, categoria";
        $campos = array('caso_id' => _('Cód.'));


        if ($pTipo != '') {
            consulta_and($db, $where, "categoria.id_tviolencia", $pTipo);
        }

        if ($pSupra != '') {
            consulta_and($db, $where, "categoria.id_supracategoria", $pSupra);
        }

        foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
            list($n, $c, $o) = $tab;
            if (($d = strrpos($c, "/"))>0) {
                $c = substr($c, $d+1);
            }
            if (is_callable(array($c, 'estadisticasIndCreaConsulta'))) {
                call_user_func_array(
                    array($c, 'estadisticasIndCreaConsulta'),
                    array(&$db, &$where, &$tablas)
                );
            } else {
                echo_esc("Falta estadisticasIndCreaConsulta en $n, $c");
            }
        }
        if (isset($GLOBALS['gancho_ei_creaconsulta'])) {
            foreach ($GLOBALS['gancho_ei_creaconsulta'] as $k => $f) {
                if (is_callable($f)) {
                    call_user_func_array(
                        $f,
                        array($pMostrar, $this->opciones, $this, &$ae, &$t)
                    );
                } else {
                    echo_esc(
                        _("Falta") . " $f " . _("de")
                        . " gancho_ei_creaconsulta[$k]"
                    );
                }
            }
        }

        $campoSegun2=$cfSegun2=$cfSegun3=$pSegun2="";
        if ($pSegun != "") {
            $pSegun2=", ".$pSegun;
            $cfSegun2=$cfSegun . ", ";
            $cfSegun3="trim(" . $cfSegun . "), ";
            $campoSegun2=", ".$campoSegun;
        }
        $q1="CREATE VIEW $cons ($pQ1 id_caso, id_tviolencia, " .
            "id_supracategoria, id_categoria".$pSegun2 .") AS " .
            "SELECT $distinct $pQ1sel caso.id, categoria.id_tviolencia, " .
            "categoria.id_supracategoria, $tCat.id_categoria $campoSegun2 " .
            " FROM " .  $tablas .
            " WHERE categoria.id=$tCat.id_categoria AND " .
            " caso.id<>'".$GLOBALS['idbus'] . "'" ;
        if ($pSinCatRepetidas != "") {
            $q1 .= " AND id_categoria IN (SELECT id FROM categoria " .
                " WHERE contadaen IS NULL)";
        }

        if ($where != "") {
            $q1 .= " AND ".$where;
        }
        hace_consulta($db, "DROP VIEW $cons2", false, false);
        hace_consulta($db, "DROP VIEW $cons", false, false);
        //echo "q1=$q1<br>";
        $result = hace_consulta($db, $q1);

        $q2="CREATE VIEW $cons2 ($cCons, id_tviolencia, " .
            "id_supracategoria, id_categoria" . $pSegun2 .
            ", id_departamento, id_municipio) ";
        $q2 .= "AS SELECT $cons.$cCons, id_tviolencia, " .
            "id_supracategoria, id_categoria" . $pSegun2 .
            ", ubicacion.id_departamento, ubicacion.id_municipio FROM " .
            "ubicacion, $cons " .
            "WHERE $cons.id_caso = ubicacion.id_caso "
            ;
        //echo "q2=$q2<br>";
        $result = hace_consulta($db, $q2);
        $q3="SELECT $cfSegun3 $tDep $tMun trim(tviolencia.nombre), " .
            "trim(supracategoria.nombre), trim(categoria.nombre), " .
            "count($cons2.$cCons) FROM
        $tGeo $tablaSegun tviolencia,
        supracategoria, categoria , $cons2
        WHERE $cons2 . id_tviolencia = tviolencia.id
        AND $cons2 . id_tviolencia = supracategoria.id_tviolencia
        AND $cons2 . id_supracategoria = supracategoria.id
        AND $cons2 . id_tviolencia = categoria.id_tviolencia
        AND $cons2 . id_supracategoria = categoria.id_supracategoria
        AND $cons2 . id_categoria = categoria.id ";
        if ($pDepartamento == "1"  || $pMunicipio == "1") {
            $q3 .= " AND departamento.id=$cons2.id_departamento ";
        }
        if ($pMunicipio == "1") {
            $q3 .= " AND municipio.id=$cons2.id_municipio ";
            $q3 .= " AND municipio.id_departamento=$cons2.id_departamento ";
        }
        $q3 .= " $condSegun
            GROUP BY $cfSegun2 $gDep $gMun tviolencia.nombre,
            supracategoria.nombre, categoria.nombre
            ORDER BY $cfSegun2 $gDep $gMun tviolencia.nombre,
            supracategoria.nombre, categoria.nombre
            ";

        //echo "q3 es $q3<hr>";
        $result = hace_consulta($db, $q3);

        if ($pMuestra == 'tabla') {
            encabezado_envia();
            echo "<table border=\"1\"><tr>";
            foreach ($cab as $k => $t) {
                echo "<th>" . htmlentities($t, ENT_COMPAT, 'UTF-8') . "</th>";
            }
            echo "</tr>\n";
            $row = array();
            $nf = 0;
            while ($result->fetchInto($row)) {
                echo "<tr>";
                foreach ($cab as $k => $t) {
                    echo "<td>";
                    echo htmlentities($row[$k], ENT_COMPAT, 'UTF-8');
                    echo "</td>";
                }
                echo "</tr>\n";
                $nf++;
            }
            echo "</table>";
            if ($nf > 0) {
                echo '<div align = "right"><a href = "index.php">' .
                    '<b>' . _('Men&uacute; Principal') . '</b></a></div>';
            }
            pie_envia();
        } else { // CSV
            // http://www.creativyst.com/Doc/Articles/CSV/CSV01.htm
            header("Content-type: text/csv");
            $sep = '';
            foreach ($cab as $k => $t) {
                $adjunto_t = $sep . '"' . str_replace('"', '""', $t). '"';
                echo $adjunto_t;
                $sep = ', ';
            }
            echo "\n";
            $row = array();
            while ($result->fetchInto($row)) {
                $sep = '';
                foreach ($cab as $k => $t) {
                    $adjunto_t = $sep . $row[$k];
                    echo $adjunto_t;
                    $sep = ', ';
                }
                echo "\n";
            }
        }
    }
}


/**
 * Formulario de Estadísticas Individuales
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class PagEstadisticasInd extends HTML_QuickForm_Page
{

    /**
     * Constructora.
     *
     * @return void
     */
    function PagEstadisticasInd()
    {
        $this->HTML_QuickForm_Page('estadisticas', 'post', '_self', null);

        $this->addAction('id_tviolencia', new CamTipoViolencia());

        $this->addAction('consulta', new AccionEstadisticasInd());
    }


    /**
     * Id tipo violencia
     *
     * @return string Id tipo violencia
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
     * Id supracategoria
     *
     * @return string id
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
     * Construye formulario
     *
     * @return void
     */
    function buildForm()
    {
        encabezado_envia();
        $this->_formBuilt = true;
        $x =&  objeto_tabla('departamento');
        $db = $x->getDatabaseConnection();

        $e =& $this->addElement(
            'header', null,
            _('Conteos Victimizacione Individuales')
        );
        $slang = 'es';
        if (isset($_SESSION['LANG'])) {
            $slang = substr($_SESSION['LANG'], 0, 2);
        }

        $cy = date('Y');
        if ($cy < 2005) {
            $cy = 2005;
        }
        $e =& $this->addElement(
            'date', 'fini', _('Desde') .': ',
            array(
                'language' => $slang, 'addEmptyOption' => true,
            'minYear' => $GLOBALS['anio_min'], 'maxYear' => $cy
            )
        );
        $e =& $this->addElement(
            'date', 'ffin', _('Hasta'),
            array(
                'language' => $slang, 'addEmptyOption' => true,
                'minYear' => $GLOBALS['anio_min'], 'maxYear' => $cy
            )
        );


        $tipo =& $this->addElement(
            'select', 'id_tviolencia',
            _('Tipo de violencia') . ': ', array()
        );
        $options= array('' => '') + htmlentities_array(
            $db->getAssoc(
                "SELECT  id, nombre FROM tviolencia " .
                "ORDER BY id"
            )
        );
        $tipo->loadArray($options);
        $tipo->updateAttributes(
            array(
                'onchange' =>
                'envia(\'estadisticas:id_tviolencia\')'
            )
        );

        $supra =& $this->addElement(
            'select', 'id_supracategoria',
            _('Supracategoria') . ': ', array()
        );

        $ntipoviolencia = $this->idTipoViolencia();
        if ($ntipoviolencia != null) {
            $tipo->setValue($ntipoviolencia);
            $options= array('' => '') + htmlentities_array(
                $db->getAssoc(
                    "SELECT  id, nombre FROM supracategoria " .
                    "WHERE id_tviolencia='$ntipoviolencia' ORDER BY id"
                )
            );
            $supra->loadArray($options);
        }
        $nsupra = $this->idSupracategoria();

        $sel =& $this->addElement(
            'select', 'segun', _('Según')
        );
        $sel->loadArray(
            array(
                '' => '',
            'id_presponsable' =>
                'ACTOS ' .
                strtoupper($GLOBALS['etiqueta']['p_responsable']),
            'id_rangoedad' => strtoupper($GLOBALS['etiqueta']['rangoedad']),
            'sexo' => strtoupper($GLOBALS['etiqueta']['sexo']),
            'id_filiacion' => strtoupper($GLOBALS['etiqueta']['filiacion']),
            'id_profesion' => strtoupper($GLOBALS['etiqueta']['profesion']),
            'id_sectorsocial' =>
                strtoupper($GLOBALS['etiqueta']['sectorsocial']),
            'id_organizacion' =>
                strtoupper($GLOBALS['etiqueta']['organizacion']),
            'meses' => _('MESES'),
            'id_profesion' => _('PROFESION'),
        )
        );

        foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
            list($n, $c, $o) = $tab;
            if (($d = strrpos($c, "/"))>0) {
                $c = substr($c, $d+1);
            }
            if (is_callable(array($c, 'estadisticasIndFiltro'))) {
                call_user_func_array(
                    array($c, 'estadisticasIndFiltro'),
                    array(&$db, &$this)
                );
            } else {
                echo_esc("Falta estadisticasIndFiltro en $n, $c");
            }
        }
        if (isset($GLOBALS['gancho_ei_filtro'])) {
            foreach ($GLOBALS['gancho_ei_filtro'] as $k => $f) {
                if (is_callable($f)) {
                    call_user_func_array(
                        $f,
                        array($pMostrar, $this->opciones, $this, &$ae, &$t)
                    );
                } else {
                    echo_esc(
                        _("Falta") . " $f " .  _("de")
                        . " estadisticasIndFiltro[$k]"
                    );
                }
            }
        }

        $ae = array();
        $sel =& $this->createElement(
            'checkbox', 'departamento', 'Departamento', _('Departamento')
        );
        $sel->setValue(true);
        $ae[] =& $sel;

        $sel =& $this->createElement(
            'checkbox', 'municipio', 'Municipio', _('Municipio')
        );
        $sel->setValue(true);
        $ae[] =& $sel;
        $this->addGroup($ae, null, _('Ubicación'), '&nbsp;', false);

        $sel =& $this->addElement(
            'checkbox',
            'sin_cat_repetidas', _('Categorias Repetidas'),
            _('Excluir')
        );

        $ae = array();
        $t =& $this->createElement(
            'radio', 'muestra', 'tabla',
            _('Tabla HTML'), 'tabla'
        );
        $ae[] =&  $t;

        $ae[] =&  $this->createElement(
            'radio', 'muestra', 'csv',
            _('Formato CSV (hoja de cálculo)'), 'csv'
        );
        $this->addGroup($ae, null, _('Forma de presentación'), '&nbsp;', false);
        $t->setChecked(true);


        $prevnext = array();
        $sel =& $this->createElement(
            'submit',
            $this->getButtonName('consulta'), _('Consulta')
        );
        $prevnext[] =& $sel;

        $this->addGroup($prevnext, null, '', '&nbsp;', false);

        $tpie = "<div align=right><a href=\"index.php\">" .
            _("Men&uacute; Principal") . "</a></div>";
        $e =& $this->addElement('header', null, $tpie);


        agrega_control_CSRF($this);

        $this->setDefaultAction('consulta');

    }

}

encabezado_envia(_('Conteos Victimizaciones Individuales'));
$wizard = new HTML_QuickForm_Controller('EstadisticasInd', false);
$consweb = new PagEstadisticasInd($mreq);

$wizard->addPage($consweb);


$wizard->addAction('display', new HTML_QuickForm_Action_Display());
$wizard->addAction('jump', new HTML_QuickForm_Action_Jump());

$wizard->addAction('process', new AccionEstadisticasInd());

$wizard->run();
pie_envia();
?>
