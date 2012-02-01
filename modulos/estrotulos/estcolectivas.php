<?php
//  vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Estadísticas victimas colectivas por rótulos
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2009 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: estcolectivas.php,v 1.16.2.6 2011/11/15 12:03:19 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Estadísticas de victimas individuales/casos por rótulos
 */
require_once "aut.php";
require_once $_SESSION['dirsitio'] . "/conf.php";
require_once "misc.php";

require_once 'HTML/QuickForm/Controller.php';
require_once 'HTML/QuickForm/Page.php';
require_once 'HTML/QuickForm/Action.php';
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
class AccionEstadisticasCol extends HTML_QuickForm_Action
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

        $pFini = var_post_escapa('fini');
        $pFfin = var_post_escapa('ffin');
        $pTipo = var_post_escapa('id_tipo_violencia');
        $pSupra = var_post_escapa('id_supracategoria');
        $pSegun = var_post_escapa('segun');
        $pMuestra = var_post_escapa('muestra');

        $pMunicipio = var_post_escapa('municipio');
        $pDepartamento = var_post_escapa('departamento');
        $pSinFiliacion = var_post_escapa('sinfiliacion');


        $cons = 'cons';
        $cons2="cons2";
/*        $cons21="cons21";
        $cons22="cons22"; */
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
            consulta_and($bd, $where, "caso.fecha", arr_a_fecha($pFini, true), ">=");
        }
        if ($pFfin['Y'] != '') {
           consulta_and($bd, $where, "caso.fecha", arr_a_fecha($pFfin, false), "<=");
        }

        $tablaSegun = $titSegun = "";
        $tCat = "";
        $tQue = "";
        $condSegun = "";
        $distinct = "DISTINCT ";
        if ($pSinFiliacion != 1) {
            $tablaFil = "filiacion_comunidad, ";
            consulta_and(
                $db, $where, "filiacion_comunidad.id_filiacion",
                DataObjects_Filiacion::idSinInfo(), "<>"
            );
            consulta_and_sinap(
                $where, "filiacion_comunidad.id_caso",
                "caso.id"
            );
            consulta_and_sinap(
                $where, "filiacion_comunidad.id_grupoper",
                "grupoper.id"
            );
        }

        if ($pSegun == 'id_rango_edad') {
/*            consulta_and_sinap($where, "victima.id_rango_edad","rango_edad.id");
            $tablas .= ", rango_edad"; */
            $campoSegun = "id_rango_edad";
            $cfSegun = "rango_edad.rango";
            $tablaSegun = "rango_edad, ";
            $condSegun = "AND rango_edad.id=$cons2.id_rango_edad";
            $titSegun = 'Edad';
        }  else if ($pSegun == 'sexo') {
            $campoSegun = "sexo";
            $cfSegun = "sexo";
            $tablaSegun = "";
            $condSegun = "";
            $titSegun = 'Sexo';
        } else if ($pSegun == 'id_p_responsable') {
            $distinct = "DISTINCT ";
            $cfSegun = 'presuntos_responsables.nombre';
            $tablaSegun = "presuntos_responsables, ";
            $condSegun = "AND presuntos_responsables.id=$cons2.id_p_responsable";
            $titSegun = 'P. Responsable';
            consulta_and_sinap(
                $where, "victima_colectiva.id_grupoper",
                "grupoper.id"
            );
            consulta_and_sinap(
                $where, "victima_colectiva.id_grupoper",
                "actocolectivo.id_grupoper"
            );
            consulta_and_sinap(
                $where, "grupoper.id",
                "actocolectivo.id_grupoper"
            );
            consulta_and_sinap(
                $where, "presuntos_responsables.id",
                "actocolectivo.id_p_responsable"
            );
            $tQue .= "";
            $campoSegun = "actocolectivo.id_p_responsable";
        } else if ($pSegun == 'id_filiacion') {
            $campoSegun = "id_filiacion";
            $cfSegun = "filiacion.nombre";
            $tablaSegun = "filiacion, ";
            $condSegun = "AND filiacion.id=$cons2.id_filiacion";
            $titSegun = 'Filiación';
        } else if ($pSegun == 'id_sector_social') {
            $campoSegun = "id_sector_social";
            $cfSegun = "sector_social.nombre";
            $tablaSegun = "sector_social, ";
            $condSegun = "AND sector_social.id=$cons2.id_sector_social";
            $titSegun = 'Sector Social';
        } else if ($pSegun == 'meses') {
            $campoSegun = "extract(year from fecha) || '-' || lpad(cast(extract(month from fecha) as text), 2, cast('0' as text))";

            $cfSegun = "meses";
            $tablaSegun= "";
            $titSegun = 'Mes';
        }

        $colenc = 0;
        $cab = array();
        if ($titSegun != "") {
            $cab[] = $titSegun;
            $colenc++;
        }
        $tDep = $gDep = "";
        if ($pDepartamento == "1") {
            $tDep = "trim(departamento.nombre), ";
            $gDep = "departamento.nombre, ";
            $cab[] = 'Departamento';
            $colenc++;
        }
        $tMun = $gMun = "";
        if ($pMunicipio == "1") {
            $tMun = "trim(municipio.nombre), ";
            $gMun = "municipio.nombre, ";
            $cab[] = 'Municipio';
            $colenc++;
        }
        $cab[] = 'Rotulo';
        if ($pSegun == 'id_p_responsable') {
            $cab[] = 'N. Actos';
        } else {
            $cab[] = 'N. Víctimizaciones';
        }
        $tCat .= '';
        $tQue .= "victima_colectiva, grupoper $tCat, ";
        consulta_and_sinap($where, "actocolectivo.id_caso","caso.id");
        consulta_and_sinap(
            $where, "actocolectivo.id_grupoper",
            "grupoper.id"
        );
        consulta_and_sinap(
            $where, "victima_colectiva.id_grupoper",
            "grupoper.id"
        );
        consulta_and_sinap(
            $where, "victima_colectiva.id_caso",
            "caso.id"
        );
        consulta_and_sinap(
            $where, "actocolectivo.id_categoria",
            "categoria.id"
        );
        $cCons = 'id_grupoper';
        $pQ1='id_grupoper, ';
        $pQ1sel = 'victima_colectiva.id_grupoper, ';
        $tablas = $tablaFil . $tablaSegun . "$tQue actocolectivo, caso, categoria ";
        $campos = array('caso_id' => 'Cód.');


        if ($pTipo != '') {
            consulta_and($db, $where, "categoria.id_tipo_violencia", $pTipo);
        }
        if ($pSupra != '') {
            consulta_and($db, $where, "categoria.id_supracategoria", $pSupra);
        }

        $campoSegun2=$cfSegun2=$cfSegun3=$pSegun2="";
        if ($pSegun != "") {
            $pSegun2=", ".$pSegun;
            $cfSegun2=$cfSegun . ", ";
            $cfSegun3="trim(" . $cfSegun . "), ";
            $campoSegun2=", ".$campoSegun;
        }
        $q1 = "CREATE VIEW $cons ($pQ1 personas_aprox, id_caso, " .
        "id_tipo_violencia, " .
        "id_supracategoria, id_categoria, col_rep_consolidado" .
        $pSegun2 .") AS SELECT $distinct $pQ1sel victima_colectiva.personas_aprox, " .
        "caso.id, " .
        "categoria.id_tipo_violencia, categoria.id_supracategoria, " .
        "actocolectivo.id_categoria, categoria.col_rep_consolidado " .
        "$campoSegun2 FROM " . $tablas .
        " WHERE caso.id<>'" . $GLOBALS['idbus'] . "'" ;
        // Evitamos sobreconteos de duplicados en DIH y DH excluyendo los de DH
        $q1 .=  " AND categoria.contada_en IS NULL ";
        if ($where != "") {
            $q1 .= " AND ".$where;
        }
        hace_consulta($db, "DROP VIEW $cons22", false, false);
        hace_consulta($db, "DROP VIEW $cons21", false, false);
        hace_consulta($db, "DROP VIEW $cons2", false, false);
        hace_consulta($db, "DROP VIEW $cons", false, false);
        //echo "q1 es $q1<hr>";
        $result = hace_consulta($db, $q1);
        if (PEAR::isError($result)) {
            die("Problema ejecutando consulta preliminar: '$q1', " .
            $result->getMessage()."'"
            );
        }

        $q2="CREATE VIEW $cons2 ($cCons, personas_aprox, id_tipo_violencia, " .
        "id_supracategoria, id_categoria, col_rep_consolidado" .
        $pSegun2 . ", id_departamento, id_municipio) ";
        $q2 .= "AS SELECT $cons.$cCons, $cons.personas_aprox, id_tipo_violencia, " .
        "id_supracategoria, id_categoria, col_rep_consolidado" .
        $pSegun2 . ", ubicacion.id_departamento, ubicacion.id_municipio " .
        "FROM ubicacion, $cons " .
        "WHERE $cons.id_caso=ubicacion.id_caso";

        //echo "q2 es $q2<hr>";
        $result = hace_consulta($db, $q2);
        if (PEAR::isError($result)) {
            die("Problema ejecutando consulta: '$q2', " .
            $result->getMessage()."'"
            );
        }
// El método para departamento y municipio es machete porque cuando
// no hay asigna santa-marta y así saldría e.g PUTUMAYO    SANTA MARTA
        $q3 = "SELECT $cfSegun3 $tDep $tMun
            TRIM(parametros_reporte_consolidado.rotulo),
            SUM($cons2.personas_aprox)
            FROM $tablaSegun departamento, municipio,
            parametros_reporte_consolidado, $cons2
            WHERE (($cons2.id_departamento IS NULL AND departamento.id = 47) OR
            departamento.id = $cons2.id_departamento)
            AND (($cons2.id_municipio IS NULL AND municipio.id = 1
            AND municipio.id_departamento = 47)  OR
            (municipio.id = $cons2.id_municipio
            AND municipio.id_departamento = $cons2.id_departamento))
            AND col_rep_consolidado = parametros_reporte_consolidado.no_columna
            $condSegun
            GROUP BY $cfSegun2 $gDep $gMun parametros_reporte_consolidado.rotulo
            ORDER BY $cfSegun2 $gDep $gMun parametros_reporte_consolidado.rotulo
        ";
        //echo "q3 es $q3<hr>";
        $result = hace_consulta($db, $q3);
        if (PEAR::isError($result)) {
            die("Problema ejecutando consulta: '$q3', " .
            $result->getMessage()."'"
            );
        }


        $tcol = array();

        $tfil = array();
        $celda = array();
        for ($i = 0; $i < count($cab)-2; $i++) {
            $tcol[$cab[$i]]=1;
        }
        $q4='SELECT no_columna, rotulo FROM parametros_reporte_consolidado " .
        "ORDER BY 1';
        $rcon = hace_consulta($db, $q4);
        if (PEAR::isError($rcon)) {
            die("Problema ejecutando consulta: '$q4', " .
            $rcon->getMessage()."'"
            );
        }
        $row = array();
        while ($rcon->fetchInto($row)) {
            $tcol[$row[1]]=1;
        }

        if ($pMuestra == 'tabla') {
            while ($result->fetchInto($row)) {
                $nc = count($row);
                $nf = "";
                for ($i = 0; $i < $nc-2; $i++) {
                    $nf .= $row[$i];
                }
                for ($i = 0; $i < $nc-2; $i++) {
                    $celda[$nf][$cab[$i]] = $row[$i];
                }
            //    echo $nf . "<br>";
                $tfil[$nf]=1;
                //$tcol[$row[$nc-2]]=1;
                $celda[$nf][$row[$nc-2]] = $row[$nc-1];
            }
            ksort($tfil);

            encabezado_envia();
            echo "<table border=\"1\">";

            echo "<tr>";
            $scol = array();
            foreach ($tcol as $k => $t) {
                echo "<th>" . htmlentities($k) . "</th>";
                $scol[$k] = 0;
            }
            echo "<th>Total</th>";
            echo "</tr>\n";

            $row = array();
            foreach ($tfil as $f => $t1) {
                echo "<tr>";
                $sfil = 0;
                $ncol = 0;
                foreach ($tcol as $c => $t2) {
                    echo "<td>";
                    if (isset($celda[$f][$c])) {
                        echo htmlentities($celda[$f][$c]); # . " " . $sfil;
                        if ($ncol >= $colenc) {
                            $scol[$c] += (int)$celda[$f][$c];
                            $sfil += (int)$celda[$f][$c];
                        }
                    } else {
                        echo "0";
                    }
                    echo "</td>";
                    $ncol++;
                }
                echo "<td><b>$sfil</b></td>";
                echo "</tr>\n";
            }

            if (count($tfil) > 1) {
                echo "<tr>";
                $sfil = 0;
                $ncol = 0;
                foreach ($tcol as $k => $t) {
                    echo "<td>";
                    if ($ncol < $colenc) {
                        if ($ncol == 0) {
                            echo "<b>Total</b>";
                        }
                    } else {
                        echo "<b>" . $scol[$k] . "</b>";
                        $sfil += $scol[$k];
                    }
                    echo "</td>";
                    $ncol++;
                }
                echo "<td><b>" . $sfil . "</b></td>";
                echo "</tr>";
            }
            echo "</table>";
            pie_envia();
        } else { // CSV
            // http://www.creativyst.com/Doc/Articles/CSV/CSV01.htm
            header("Content-type: text/csv");
            $sep = '';
            foreach ($cab as $k => $t) {
                $adjunto_t = $sep . '"' . str_replace('"', '""', $t). '"';
                echo  $adjunto_t;
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

/*        $r=new ExportaResultados($db, $result); $r->aHtml(); */

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
class PagEstadisticasCol extends HTML_QuickForm_Page
{

    /**
     * Constructora.
     * Ver documentación completa en clase base.
     *
     * @param string $nomForma Nombre
     * @param string $mreq     Mensaje de dato requerido
     *
     * @return void
     */
    function PagEstadisticasCol()
    {
        $this->HTML_QuickForm_Page('estadisticascol', 'post', '_self', null);

        $this->addAction('id_tipo_violencia', new CamTipoViolencia());

        $this->addAction('consulta', new AccionEstadisticasCol());
    }


    function idTipoViolencia()
{
        $ntipoviolencia= null;
        if (isset($this->_submitValues['id_tipo_violencia'])) {
            $ntipoviolencia = (int)$this->_submitValues['id_tipo_violencia'] ;
        } else if (isset($_SESSION['id_tipo_violencia'])) {
            $ntipoviolencia = $_SESSION['id_tipo_violencia'] ;
        }
        return $ntipoviolencia;
    }


    function idSupracategoria()
    {
        $nclase = null;
        if (isset($this->_submitValues['id_supracategoria'])) {
            return  (int)$this->_submitValues['id_supracategoria'] ;
        }
        return null;
    }


    function buildForm()
    {
        encabezado_envia();
        $this->_formBuilt = true;
        $x =&  objeto_tabla('departamento');
        $db = $x->getDatabaseConnection();

        $e =& $this->addElement(
            'header', null,
            'Estadísticas Victimas Colectivas'
        );

        $e =& $this->addElement('hidden', 'num', (int)$_REQUEST['num']);

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
        $e->setValue(($cy - 1) . "-12-31");


        $tipo =& $this->addElement(
            'select', 'id_tipo_violencia',
            'Tipo de violencia: ', array()
        );
        $options= array('' => '') + htmlentities_array(
            $db->getAssoc(
                "SELECT  id, nombre FROM tipo_violencia " .
                "ORDER BY id"
            )
        );
        $tipo->loadArray($options);
        $tipo->updateAttributes(
            array('onchange' =>
            'envia(\'estadisticascol:id_tipo_violencia\')'
            )
        );

        $supra =& $this->addElement(
            'select', 'id_supracategoria',
            'Supracategoria: ', array()
        );

        $ntipoviolencia = $this->idTipoViolencia();
        if ($ntipoviolencia != null) {
            $tipo->setValue($ntipoviolencia);
            $options= array('' => '') +  htmlentities_array(
                $db->getAssoc(
                    "SELECT  id, nombre FROM supracategoria " .
                    "WHERE id_tipo_violencia='$ntipoviolencia' ORDER BY id"
                )
            );
            $supra->loadArray($options);
        }
        $nsupra = $this->idSupracategoria();

        $sel =& $this->addElement(
            'select',
            'segun', 'Según'
        );
        $sel->loadArray(
            array('' => '',
            'id_p_responsable' => 'ACTOS '
                . strtoupper($GLOBALS['etiqueta']['p_responsable']),
            'meses' => 'MESES',
        )
        );

        $ae = array();
        $sel =& $this->createElement(
            'checkbox',
            'departamento', 'Departamento', 'Departamento'
        );
        //$sel->setValue(true);
        $ae[] =& $sel;

        $sel =& $this->createElement(
            'checkbox',
            'municipio', 'Municipio', 'Municipio'
        );
        //       $sel->setValue(true);
        $ae[] =& $sel;
        $this->addGroup($ae, null, 'Localización', '&nbsp;', false);

        $ae = array();
        $sel =& $this->createElement(
            'checkbox',
            'sinfiliacion', 'Incluir', 'Incluir'
        );
        //        $sel->setValue(true);
        $ae[] =& $sel;
        $this->addGroup(
            $ae, null, "Víctimas colectivas sin " .
            $GLOBALS['etiqueta']['filiacion'],
            '&nbsp;', false
        );

        $ae = array();
        $t =& $this->createElement(
            'radio', 'muestra', 'tabla',
            'Tabla HTML', 'tabla'
        );
        $ae[] =&  $t;

        $ae[] =&  $this->createElement(
            'radio', 'muestra', 'csv',
            'Formato CSV (hoja de cálculo)', 'csv'
        );
        $this->addGroup($ae, null, 'Forma de presentación', '&nbsp;', false);
        $t->setChecked(true);


        $prevnext = array();
        $sel =& $this->createElement(
            'submit',
            $this->getButtonName('consulta'), 'Consulta'
        );
        $prevnext[] =& $sel;

        $this->addGroup($prevnext, null, '', '&nbsp;', false);

        $this->setDefaultAction('consulta');

    }

}


/**
 * Presenta formulario filtro o estadística
 */
function muestra($dsn, $accno)
{
    $aut_usuario = "";
    autenticaUsuario($dsn, $accno, $aut_usuario, 21);

    $wizard =& new HTML_QuickForm_Controller('EstadisticasCol', false);
    $consweb = new PagEstadisticasCol();

    $wizard->addPage($consweb);


    $wizard->addAction('display', new HTML_QuickForm_Action_Display());
    $wizard->addAction('jump', new HTML_QuickForm_Action_Jump());

    $wizard->addAction('process', new AccionEstadisticasCol());

    $wizard->run();
}
?>
