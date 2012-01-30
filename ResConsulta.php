<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
/**
 * Resultados de una consulta
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: ResConsulta.php,v 1.167.2.4 2011/10/13 13:41:06 vtamara Exp $
 * @link      http://sivel.sf.net
 */

/**
 * Resultados de una consulta
 */

require_once 'HTML/QuickForm/Action.php';
require_once 'aut.php';
require_once 'misc.php';
require_once $_SESSION['dirsitio'] . '/conf.php';
require_once 'DataObjects/Escrito_caso.php';
require_once 'DataObjects/Prensa.php';
require_once 'DataObjects/Caso.php';
require_once 'DataObjects/Region_caso.php';
require_once 'DataObjects/Frontera_caso.php';
require_once 'DataObjects/Fuente_directa_caso.php';
require_once 'DataObjects/Categoria_caso.php';
require_once 'DataObjects/Supracategoria.php';
require_once 'DataObjects/Categoria.php';
require_once 'DataObjects/Presuntos_responsables.php';
require_once 'DataObjects/Presuntos_responsables_caso.php';
require_once 'DataObjects/Categoria_p_responsable_caso.php';
require_once 'DataObjects/Victima.php';
require_once 'DataObjects/Victima_colectiva.php';
require_once 'DataObjects/Sector_social.php';
require_once 'DataObjects/Sector_social_comunidad.php';
require_once 'DataObjects/Profesion_comunidad.php';
require_once 'DataObjects/Funcionario_caso.php';
require_once 'DataObjects/Resultado_agresion.php';
require_once 'DataObjects/Filiacion.php';
require_once 'DataObjects/Sector_social.php';
require_once 'DataObjects/Profesion.php';
require_once 'DataObjects/Presuntos_responsables.php';
require_once 'DataObjects/Etiquetacaso.php';

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
 * Responde al boton buscar
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
*/
class ResConsulta
{

    /**
     * Campos
     * @var    array
     */
    var $campos; // Ver parámetros de constructora


    /**
     * Conexión a base de datos
     * @var    object
     */
    var $db;

    /**
     * Resultado
     * @var    string
     */
    var $resultado;

    /**
     * Conv
     * @var    array
     */
    var $conv;

    /**
     * Mostrar
     * @var    string
     */
    var $mostrar;

    /**
     * Memo en varias líneas
     * @var    bool
     */
    var $varlin;

    /**
     * Ordenar
     * @var    bool
     */
    var $ordenar;

    /**
     * Traducción TeX
     * @var    bool
     */
    var $tex;

    /**
     * ordCod
     * @var    array
     */
    var $ordCod;

    /**
     * Vector con opciones para presentar resultado
     * @var    array
     */
    var $busca_pr; //

    /**
     * Constructora
     *
     * @param array  &$campos      Campos por mostrar id => nombre
     * @param handle &$db          Conexión a base de datos
     * @param string &$resultado   Resultado de consulta tiene caso.id
     * @param array  &$conv        Convertir id de campos a base de datos
     * @param string $mostrar      Forma de presentacion (rev., gen., tabla)
     * @param array  $detallesform Partir  memo en varias lineas
     * @param array  $ordCasos     Orden de los casos por mostrar
     * @param array  $busca_pr     Opciones de mostrar info.
     * @param array  $ordenar      Ordenar
     *
     * @return void
    */
    function ResConsulta(&$campos, &$db, &$resultado, &$conv, $mostrar,
        $detallesform = array(), $ordCasos = array(), $busca_pr = null,
        $ordenar = null
    ) {
        $this->campos =& $campos;
        $this->db=& $db;
        if (is_array($resultado)) {
            $this->resultado =& $resultado;
        } else {
            $this->resultado = array(&$resultado);
        }
        $this->conv =& $conv;
        $this->mostrar = $mostrar;
        $this->ordCasos = $ordCasos;
        $this->busca_pr = $busca_pr;
        $this->ordenar = $ordenar;
        $this->varlin = isset($detallesform['varlineas']) ?
            $detallesform['varlineas'] : true;
        $this->tex = isset($detallesform['tex']) ?
            $detallesform['tex'] : false;
    }


    /**
     * Extrae ubicaciones asociadas a un caso.
     *
     * @param integer $idcaso Código del caso
     * @param handle  &$db    Conexión a base de datos
     * @param array   &$idd   Ids. de departamentos  retornados
     * @param array   &$ndd   Nombres de departamentos  retornados
     * @param array   &$idm   Ids. de municipios  retornados
     * @param array   &$ndm   Nombres de municipios  retornados
     * @param array   &$idc   Ids. de clases  retornados
     * @param array   &$ndc   Nombres de clases  retornados
     * @param array   &$tdu   Tipo de ubicacion
     *
     * @return integer Cantidad de ubicaciones encontradas
     */
    function extraeUbicacionCaso($idcaso, &$db,
        &$idd, &$ndd, &$idm, &$ndm, &$idc, &$ndc, &$tdu
    ) {

        $tot = 0;
        $q = "SELECT ubicacion.id_departamento, ubicacion.id_municipio, " .
            " ubicacion.id_clase, " .
            " departamento.nombre, municipio.nombre, clase.nombre, " .
            " tipo_sitio.nombre " .
            " FROM ubicacion, tipo_sitio, departamento, municipio, clase " .
            " WHERE ubicacion.id_caso='$idcaso' " .
            " AND ubicacion.id_tipo_sitio=tipo_sitio.id " .
            " AND ubicacion.id_departamento=departamento.id " .
            " AND ubicacion.id_municipio=municipio.id " .
            " AND ubicacion.id_clase=clase.id " .
            " AND municipio.id_departamento=departamento.id " .
            " AND clase.id_departamento=departamento.id " .
            " AND clase.id_municipio=municipio.id " .
            " ORDER BY departamento.nombre, municipio.nombre, clase.nombre;";
        $result = hace_consulta($db, $q);
        while (isset($result) && $result->fetchInto($row)) {
            $idd[] = $row[0];
            $idm[] = $row[1];
            $idc[] = $row[2];
            $ndd[] = $row[3];
            $ndm[] = $row[4];
            $ndc[] = $row[5];
            $tdu[] = $row[6];
            $tot++;
        }
        $q = "SELECT ubicacion.id_departamento, ubicacion.id_municipio, " .
            " departamento.nombre, municipio.nombre, " .
            " tipo_sitio.nombre " .
            " FROM ubicacion, tipo_sitio, departamento, municipio " .
            " WHERE ubicacion.id_caso='$idcaso' " .
            " AND ubicacion.id_tipo_sitio=tipo_sitio.id " .
            " AND ubicacion.id_departamento=departamento.id " .
            " AND municipio.id_departamento=departamento.id " .
            " AND ubicacion.id_municipio=municipio.id " .
            " AND ubicacion.id_clase IS NULL " .
            " ORDER BY departamento.nombre, municipio.nombre;";
        $result = hace_consulta($db, $q);
        while (isset($result) && $result->fetchInto($row)) {
            $idd[] = $row[0];
            $idm[] = $row[1];
            $idc[] = null;
            $ndd[] = $row[2];
            $ndm[] = $row[3];
            $ndc[] = '';
            $tdu[] = $row[4];
            $tot++;
        }
        $q = "SELECT ubicacion.id_departamento, departamento.nombre, " .
            " tipo_sitio.nombre " .
            " FROM ubicacion, tipo_sitio, departamento " .
            " WHERE ubicacion.id_caso='$idcaso' " .
            " AND ubicacion.id_tipo_sitio=tipo_sitio.id " .
            " AND ubicacion.id_departamento=departamento.id " .
            " AND ubicacion.id_municipio IS NULL " .
            " AND ubicacion.id_clase IS NULL " .
            " ORDER BY departamento.nombre;";
        $result = hace_consulta($db, $q);
        while (isset($result) && $result->fetchInto($row)) {
            $idd[] = $row[0];
            $idm[] = null;
            $idc[] = null;
            $ndd[] = $row[1];
            $ndm[] = '';
            $ndc[] = '';
            $tdu[] = $row[2];
            $tot++;
        }
        return $tot;
    }


    /**
     * Retorna cadena con ubicaciones
     *
     * @param handel  &$db    Conexión a BD
     * @param integer $idcaso Id. del caso
     *
     * @return string ubicaciones
     */
    function ubicacion(&$db, $idcaso)
    {
        $idd = array(); // Identificaciones
        $idm = array();
        $idc = array();
        $ndd = array(); // Nombres
        $ndm = array();
        $ndc = array();
        $tdu = array();
        ResConsulta::extraeUbicacionCaso(
            $idcaso,
            $db, $idd, $ndd, $idm, $ndm, $idc, $ndc, $tdu
        );
        $seploc = "";
        $vr = "";
        foreach ($ndd as $k => $nd) {
            $vr .= $seploc . trim($nd);
            if ($ndm[$k] != '') {
                $vr .= " / " . trim($ndm[$k]);
            }
            if ($ndc[$k] != '') {
                $vr .= " / " . trim($ndc[$k]);
            }
            $seploc = "; ";
        }
        return $vr;
    }


    /**
     * Extrae víctimas de un caso y retorna su información en varios
     *   vectores
     *
     * @param integer $idcaso     Id. del caso
     * @param handle  &$db        Conexión
     * @param array   &$idp       Vector de identificaciones
     * @param array   &$ndp       Vector de nombres
     * @param integer $id_persona Id.
     * @param integer &$indid     Indid
     * @param object  &$edp       edp
     *
     * @return Total de víctimas
     */
    function extraeVictimas($idcaso, &$db, &$idp, &$ndp,
        $id_persona, &$indid, &$edp
    ) {
        $q = "SELECT  id_persona, nombres, apellidos, anionac " .
            " FROM victima, persona " .
            " WHERE id_caso='$idcaso' AND victima.id_persona=persona.id " .
            " ORDER BY id;";
        $result = hace_consulta($db, $q);
        $row = array();
        $tot = 0;
        while ($result->fetchInto($row)) {
            $idp[] = $row[0];
            $ndp[] = $row[1] . " " . $row[2];
            $edp[] = $row[3];
            if (isset($id_persona) && $id_persona== $row[0]) {
                $indid = $tot;
            }
            $tot++;
        }
        return $tot;
    }



    /**
     * Extrae víctimas de un caso y retorna su información en varios
     * vectores
     *
     * @param integer $idcaso         Id. del caso
     * @param handle  &$db            Conexión
     * @param array   &$idp           Vector de identificaciones
     * @param array   &$ndp           Vector de nombres
     * @param integer $id_combatiente Id.
     * @param integer &$indid         Indid
     *
     * @return Total de víctimas

     */
    function extraeCombatientes($idcaso, &$db, &$idp, &$ndp,
        $id_combatiente, &$indid
    ) {

        $result = hace_consulta(
            $db, "SELECT  id, nombre FROM combatiente " .
            " WHERE id_caso='$idcaso' ORDER BY id;"
        );
        $row = array();
        $tot = 0;
        while ($result->fetchInto($row)) {
            $idp[] = $row[0];
            $ndp[] = $row[1];
            if (isset($id_combatiente) && $id_combatiente == $row[0]) {
                $indid = $tot;
            }
            $tot++;
        }
        return $tot;
    }


    /**
     * Extrae presuntos responsables
     *
     * @param int    $idcaso Id. del caso
     * @param object &$db    Conexión a BD
     * @param array  &$idp   Vector de identificaciones
     * @param array  &$idp2  Vector de identificaciones 2
     * @param array  &$ndp   Vector de nombres
     *
     * @return integer Return description (if any) ...
     */
    function extraePResponsables($idcaso, &$db, &$idp, &$idp2, &$ndp)
    {
        $q = "SELECT  id_p_responsable, presuntos_responsables_caso.id, " .
            " presuntos_responsables.nombre " .
            " FROM presuntos_responsables_caso, presuntos_responsables " .
            " WHERE id_caso='$idcaso' " .
            " AND id_p_responsable=presuntos_responsables.id " .
            " ORDER BY id;";
        $result = hace_consulta($db, $q);
        $row = array();
        $tot = 0;
        while ($result->fetchInto($row)) {
            $idp[] = $row[0];
            $idp2[] = $row[1];
            $ndp[] = $row[2];
            $tot++;
        }

        return $tot;
    }


    /**
     * Agrega victimas colectivas del caso $idcaso a los arreglos
     *        $idp (identificacion), $ndp (nombre) y $cpd (cantidad de personas)
     *
     * @param int    $idcaso      Id. del caso
     * @param object &$db         Conexión a BD
     * @param array  &$idp        Vector de identificaciones
     * @param array  &$ndp        Vector de nombres
     * @param array  &$cdp        Vector
     * @param int    $id_grupoper Si no es null y hay un indice en idp
     * que corresponda a este valor, retorna tal indice en indid
     * @param int    &$indid      indid
     * @param int    &$totelem    Total de elementos agregados a cada arreglo
     *
     * @return Suma de victimas.
     **/
    function extraeColectivas($idcaso, &$db, &$idp, &$ndp, &$cdp,
        $id_grupoper, &$indid, &$totelem
    ) {
        $q = "SELECT  id_grupoper, nombre, personas_aprox " .
            " FROM victima_colectiva, grupoper " .
            " WHERE victima_colectiva.id_grupoper=grupoper.id " .
            " AND id_caso='$idcaso' ORDER BY id_grupoper;";
        $result = hace_consulta($db, $q);
        $row = array();
        $tot = 0;
        $totelem = 0;
        while ($result->fetchInto($row)) {
            $idp[] = $row[0];
            $ndp[] = $row[1];
            $cdp[] = $row[2];
            if (isset($id_grupoper) && $id_grupoper == $row[0]) {
                $indid = $totelem;
            }
            $tot += $row[2];
            $totelem++;
        }
        return $tot;
    }



    /**
     * Reporte de Actos
     *
     * @param object &$db        Conexión a BD
     * @param array  $tablas     Tablas
     * @param string $donde      Donde
     * @param string $pFinchasta Caja de selección
     * @param string $pMuestra   Muestra
     *
     * @return void
     */
    function actosHtml(&$db, $tablas, $donde, $pFinchasta, $pMuestra)
    {
        $etablas = array();
        if (is_array($tablas)) {
            $etablas = $tablas;
        } else if ($tablas != '') {
            $etablas = explode(',', $tablas);
        }
        $etablas = array_merge(
            $etablas, array('caso',
            'victima', 'persona', 'presuntos_responsables',
            'acto',
            'sector_social', 'organizacion'
        )
        );
        $etablas = implode(", ", array_unique($etablas));
        $q = " SELECT caso.id, persona.id, " .
            " persona.nombres || ' ' || persona.apellidos, caso.fecha, " .
            " acto.id_categoria, presuntos_responsables.nombre, " .
            " sector_social.nombre, organizacion.nombre  " .
            " FROM  $etablas WHERE " .
            " presuntos_responsables.id=acto.id_p_responsable " .
            " AND acto.id_persona=persona.id " .
            " AND persona.id=victima.id_persona " .
            " AND caso.id=victima.id_caso " .
            " AND caso.id=acto.id_caso " .
            " AND sector_social.id=victima.id_sector_social" .
            " AND organizacion.id=victima.id_organizacion" .
            " AND $donde ORDER BY caso.fecha" ;
        //echo "q es $q<br>";
        //die("x");
        $result = hace_consulta($db, $q);

        $suma = array();
        if ($pMuestra == "csv") {
            header("Content-type: text/csv");
            echo '"Fecha", "Caso", "Víctima", "Categoria", "P. Responsable", ""\n';
        } elseif ($pMuestra == 'latex') {
            //header("Content-type: application/x-latex");
            echo "<pre>";
            echo '\\textbf{Fecha} & \\textbf{Caso} & \\textbf{Víctima} & '
                . ' \\textbf{Categoria} & \\textbf{P. Responsable} \\\\ \n '
                . '\hline\n';
        } else { // tabla o consolidado

            echo "<table border='1'>\n";
            echo "<tr><th>Fecha</th><th>Caso</th><th>Víctima</th>" .
                "<th>Sector Social</th><th>Organización Social</th>" .
                "<th>Categoria</th><th>P. Responsable</th></tr>";
        }


        $tv = 0;
        while ($result->fetchInto($row)) {
            //print_r($row);
            $fecha = $row[3];
            $cat = $row[4];
            $nom = $row[2];
            $ss = $row[6];
            $os = $row[7];
            $idvic = $row[1];
            $idcaso = $row[0];
            $presp = $row[5];

            if ($pMuestra == "tabla" || $pMuestra == 'actos') {
                $html_il = "<tr><td>" . htmlentities($fecha) . "</td>" .
                    "<td>" . trim(htmlentities($idcaso)) . "</td>" .
                    "<td>" . trim(htmlentities($nom)) . "</td>" .
                    "<td>" . trim(htmlentities($ss)) . "</td>" .
                    "<td>" . trim(htmlentities($os)) . "</td>" .
                    "<td>" . htmlentities($cat) . "</td>" .
                    "<td>" . htmlentities($presp) . "</td>";
            } elseif ($pMuestra == 'csv') {
                $html_il = $fecha . ", ".trim($nom).
                    ", " . $cat . ", " . $presp . ", ";
            } elseif ($pMuestra == 'latex') {
                $html_il = txt2latex($fecha).
                    " & ".txt2latex(trim($idcaso)).
                    " & ".txt2latex(trim($nom)).
                    " & ".txt2latex($cat).
                    " & ".txt2latex($presp)." ";
            }

            echo $html_il;

            if ($pMuestra == "tabla" || $pMuestra == 'consolidado') {
                echo "</tr>\n";
            } elseif ($pMuestra == 'csv') {
                echo " \n";
            } elseif ($pMuestra == 'latex') {
                echo " \\\\\n \hline\n";
            }

        }
        if ($pMuestra == "tabla"  || $pMuestra == 'consolidado') {
            echo "</table>";
        } elseif ($pMuestra == 'csv') {
        } elseif ($pMuestra == 'latex') {
        }
    }


    /** Llena un select o un arreglo con ids y nombres de categorias de
     * violencia  extraidos de la tabla con una consulta que recibe
     *  (retorna primero tipo_violencia, id_supracategoria e id_categoria)
     *
     * @param object &$db   Conexión a BD
     * @param string $q     Q
     * @param string &$sel  Caja de selección
     * @param string $orden Orden
     *
     * @return void
     */
    function llenaSelCategoria(&$db, $q, &$sel, $orden = array(-1, 0, 1, 2))
    {
        $result = hace_consulta($db, $q);
        $row = array();
        while ($result->fetchInto($row)) {
            $qtvio = "SELECT id, nomcorto "
                .  " FROM tipo_violencia "
                .  " WHERE id='" . $row[0] . "';";
            $tvio = htmlentities_array($db->getAssoc($qtvio));
            $scat = htmlentities_array(
                $db->getAssoc(
                    "SELECT id, nombre " .
                    " FROM supracategoria " .
                    " WHERE id_tipo_violencia='" . $row[0] . "' " .
                    " AND id='" . $row[1] . "';"
                )
            );
            $cat = htmlentities_array(
                $db->getAssoc(
                    "SELECT id, nombre " .
                    "FROM categoria WHERE " .
                    "id_tipo_violencia='".$row[0] . "' AND " .
                    "id_supracategoria='".$row[1] . "' AND " .
                    "id='".$row[2] . "';"
                )
            );
            if (isset($orden) && is_array($orden)) {
                $sepnord = "";
                $n = "";
                foreach ($orden as $nord) {
                    switch ($nord) {
                    case -1:
                        $r = $row[0] . $row[2];
                        break;
                    case 0:
                        $r = $tvio[$row[0]];
                        break;
                    case 1:
                        $r = $scat[$row[1]];
                        break;
                    default:
                        $r = $cat[$row[2]];
                        break;
                    }
                    $n .= $sepnord . trim($r);
                    $sepnord = ":";
                }
            } else {
                $n = trim($tvio[$row[0]]).":".trim($scat[$row[1]]).
                    ":".trim($cat[$row[2]]);
            }
            $i = PagTipoViolencia::cadenaDeCodcat($row[0], $row[1], $row[2]);
            if (is_array($sel)) {
                $sel[$i] = $n;
            } else {
                $sel->addOption($n, $i);
            }
        }
    }

    /**
     * Convierte reporte a HTML
     *
     * @param boolean $retroalim    Retroalimentación
     * @param string  $html_enlace1 Enlace por agregar al final
     *
     * @return void
     */
    function aHtml($retroalim = true,
        $html_enlace1='<a href = "consulta_web.php">Consulta Web</a>, '
    ) {
        $html_erelato =  "<" ."?xml version=\"1.0\" encoding=\"ISO-8859-1\"?".">\n"
            . "<!DOCTYPE relatos PUBLIC \"-//SINCODH/DTD relatos 0.96\" "
            . "\"relatos.dtd\">\n"
            . '<'.'?xml-stylesheet type="text/xsl" href="xrlat-a-html.xsl"?'
            . ">\n<relatos>";

        $j = 0;
        $tot = 0;
        foreach ($this->resultado as $resultado) {
            $tot += $resultado->numRows();
            //print_r($resultado);
            //echo "tot=$tot";
            //die("z");
        }
        $esadmin = false;
        if (isset($_SESSION['id_funcionario'])) {
            include $_SESSION['dirsitio'] . "/conf.php";

            $aut_usuario = "";
            autenticaUsuario($dsn, $accno, $aut_usuario, 0);
            if (in_array(42, $_SESSION['opciones'])) {
                $esadmin = true;
            }
        }

        if (!$esadmin && isset($GLOBALS['consulta_web_max'])
            && $GLOBALS['consulta_web_max'] > 0
            && $tot > $GLOBALS['consulta_web_max']
        ) {
            echo "Consulta de " . (int)$tot . " casos. <br>";
            die("Por favor refine su consulta para que sean menos de " .
            $GLOBALS['consulta_web_max']
            );
        }
        switch ($this->mostrar) {
        case 'general':
        case 'revista':
            encabezado_envia('Consulta Web', $GLOBALS['cabezote_consulta_web']);
            echo "<html><head><title>Reporte Revista</title></head>";
            echo "<body>";
            echo "<pre>";
            break;
        case 'sql':
            header("Content-type: text/plain");
            header('Content-Disposition: attachment; filename = "consulta.sql"');
            break;
        case 'relato':
            header("Content-type: text/plain");
            header('Content-Disposition: attachment; filename = "consulta.xrlt"');
            echo $html_erelato;
            break;

        case 'csv':
            header("Content-type: text/csv");
            header('Content-Disposition: attachment; filename = "consulta.csv"');
            $adjunto_renglon = "";
            $sep = "";
            foreach ($this->campos as $cc => $nc) {
                $nc = str_replace('"', '""', $nc);
                $renglon .= $sep . '"' . $nc . '"';
                $sep = ', ';
            }
            echo "$adjunto_renglon\n";
            break;
        case 'tabla':
            encabezado_envia('Consulta Web', $GLOBALS['cabezote_consulta_web']);
            echo "<html><head><title>Tabla</title></head>";
            echo "<body>";
            echo "Consulta de " . (int)$tot . " casos. ";
            echo "<p><table border=1 cellspacing=0 cellpadding=5>";
            $html_renglon = "<tr>";
            $rtexto = "";
            foreach ($this->campos as $cc => $nc) {
                $html_renglon = "$html_renglon<th valign=top>$nc</th>";
                $rtexto = "$rtexto\n$nc";
                foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
                    list($n, $c, $o) = $tab;
                    if (($d = strrpos($c, "/"))>0) {
                        $c = substr($c, $d+1);
                    }
                    if (is_callable(array($c, 'resConsultaInicioTabla'))) {
                        call_user_func_array(
                            array($c, 'resConsultaInicioTabla'),
                            array($cc)
                        );
                    } else {
                        echo_esc("Falta resConsultaInicioTabla en $n, $c");
                    }
                }
            }
            echo "$html_renglon";
            if ($retroalim) {
                echo "<th valign=top>Retroalimentacion</th>";
            }
            echo "</tr>\n";
            break;
        case 'relatoslocal':
            if (!isset($GLOBALS['DIR_RELATOS'])
                || $GLOBALS['DIR_RELATOS'] == ''
            ) {
                echo "Falta definir directorio destino en variable " .
                    "\$GLOBALS['DIR_RELATOS'] del archivo " .
                    htmlentities("$dirserv/$dirsitio/conf.php") . "<br>";
                die("");
            } else if (!is_writable($GLOBALS['DIR_RELATOS'])) {
                echo "No puede escribirse en directorio " .
                    $GLOBALS['DIR_RELATOS'] .
                    "<br>Ajuste o cambie permisos temporalmente con:<br>" .
                    "<tt>sudo chmod a+w ${GLOBALS['dirchroot']}" .
                    "${GLOBALS['DIR_RELATOS']}</tt><br>";
            } else {
                echo "<font color='red'>El directorio  " .
                    $GLOBALS['DIR_RELATOS'] .
                    " puede ser escrito</font><br>" .
                    "Tras generar, retire permiso de escritura con :<br>" .
                    "<tt>sudo chmod a-w ${GLOBALS['dirchroot']}" .
                    "${GLOBALS['DIR_RELATOS']}</tt><br>";
            }
            echo "Generando relatos:<br>";
            break;
        default:
            foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
                list($n, $c, $o) = $tab;
                if (($d = strrpos($c, "/"))>0) {
                    $c = substr($c, $d+1);
                }
                if (is_callable(array($c, 'resConsultaInicio'))) {
                    call_user_func_array(
                        array($c, 'resConsultaInicio'),
                        array($this->mostrar, &$renglon, &$rtexto, $tot)
                    );
                } else {
                    echo_esc("Falta resConsultaInicio en $n, $c");
                }
            }
            if (isset($GLOBALS['gancho_rc_inicio'])) {
                foreach ($GLOBALS['gancho_rc_inicio'] as $k => $f) {
                    if ($this->mostrar == $k) {
                        if (is_callable($f)) {
                            call_user_func_array(
                                $f,
                                array($this->mostrar, &$renglon, &$rtexto, $tot)
                            );
                        } else {
                            echo_esc("Falta $f de gancho_rc_inicio[$k]");
                        }
                    }
                }
            }


            break;
        }
        $rCod = array();
        foreach ($this->resultado as $resultado) {
            $sal = array();
            while ($resultado->fetchInto($sal)) {
                $idc = $sal[$this->conv['caso_id']];
                if (is_array($this->ordCasos) && count($this->ordCasos)>0) {
                    $rCod[array_search($idc, $this->ordCasos)] = $sal;
                } else {
                    $rCod[] = $sal;
                }
            }
        }
        unset($this->resultado);
        ksort($rCod);
        $ultpeso = -1;
        $reportado = array();
        $numcaso = 0;
        foreach ($rCod as $sal) {
            $idcaso = (int)$sal[$this->conv['caso_id']];
            if (!isset($reportado[$idcaso])) {
                $reportado[$idcaso] = true;
                $numcaso++;
                switch ($this->mostrar) {
                case 'general':
                    $html_rep = $this->reporteGeneralHtml(
                        $idcaso, $this->db, $this->campos, $this->varlin
                    );
                    echo $html_rep . "\n<hr>\n";
                    break;
                case 'revista':
                    $nc = null;
                    if ($this->ordenar == 'rotulo') {
                        $nc = $numcaso;
                    }
                    list($html_r, $peso, $rotulo)
                        = $this->reporteRevistaHtml(
                            $idcaso, $this->db,
                            $this->campos, $this->varlin, $this->tex,
                            $nc
                        );
                    if ($this->ordenar == 'rotulo'
                        && array_key_exists('m_tipificacion', $this->campos)
                    ) {
                        if ($peso > $ultpeso) {
                            echo "<font size=+2>" . htmlentities($rotulo) .
                                "</font>\n";
                        }
                        echo $html_r . "\n";
                        if ($peso >= $ultpeso) {
                            $ultpeso = $peso;
                        } else {
                            echo "<br/><font color='red'>Peso " . (int)$peso 
                               . " de caso " . (int)$idcaso 
                               ." fuera de secuencia</font><br/>";
                        }
                    } else {
                        echo $html_r . "\n";
                    }
                    break;
                case 'relato':
                    echo $this->reporteRelato(
                        $idcaso, null,
                        $this->campos, $this->varlin
                    );
                    break;
                case 'csv':
                    echo $this->reporteCsvAdjunto(
                        $this->db, $idcaso,
                        $this->campos, $this->conv, $sal
                    );
                    echo "\n";
                    break;
                case 'tabla':
                    $this->filaTabla(
                        $this->db, $idcaso, $this->campos,
                        $this->conv, $sal, $retroalim
                    );
                    break;
                case 'relatoslocal':
                    echo_esc(memory_get_usage());
                    echo "<br>";
                    $nar = $GLOBALS['DIR_RELATOS'] .
                        $GLOBALS['PREF_RELATOS'] . $idcaso . '.xrlat';
                    echo "&nbsp;&nbsp;" . htmlentities($nar);
                    if (!file_exists($nar)) {
                        $r = $html_erelato;
                        $r .= ResConsulta::reporteRelato(
                            $idcaso, $this->db,
                            $this->campos, $this->varlin
                        );
                        $r .= "</relatos>\n";
                        if (!file_put_contents($nar, $r)) {
                            echo " ... Falló<br>";
                        } else {
                            echo "<br>\n";
                        }
                    }
                    unset($r);
                    unset($nar);
                    break;

                default:
                    foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
                        list($n, $c, $o) = $tab;
                        if (($d = strrpos($c, "/"))>0) {
                            $c = substr($c, $d+1);
                        }
                        if (is_callable(array($c, 'resConsultaRegistro'))) {
                            call_user_func_array(
                                array($c, 'resConsultaRegistro'),
                                array(&$this->db, $this->mostrar,
                                $idcaso, $this->campos,
                                $this->conv, $sal, $retroalim)
                            );
                        } else {
                            echo_esc("Falta resConsultaRegistro en $n, $c");
                        }
                    }

                    if (isset($GLOBALS['gancho_rc_registro'])) {
                        foreach (
                            $GLOBALS['gancho_rc_registro'] as $k => $f
                        ) {
                            if ($this->mostrar == $k) {
                                if (is_callable($f)) {
                                    call_user_func_array(
                                        $f,
                                        array(&$this->db,
                                        $idcaso, $this->campos,
                                        $this->conv, $sal, $retroalim)
                                    );
                                } else {
                                    muestra_escapado(
                                        "Falta $f de resConsultaRegistro[$k]"
                                    );
                                }
                            }
                        }
                    }
                    break;
                }
            }
        }
        switch ($this->mostrar) {
        case 'general':
        case 'revista':
            echo "</pre>";
            break;
        case 'sql':
        case 'csv':
            break;
        case 'relato':
            echo "</relatos>\n";
            break;
        case 'tabla':
            if (array_key_exists('m_desembolsos', $this->campos)) {
                echo "<tr>";
                $html_renglon = "";
                foreach ($this->campos as $cc => $nc) {
                    $html_renglon .= "<td>";
                    foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
                        list($n, $c, $o) = $tab;
                        if (($d = strrpos($c, "/"))>0) {
                            $c = substr($c, $d+1);
                        }
                        if (is_callable(array($c, 'resConsultaFinaltabla'))) {
                            $html_renglon .= call_user_func_array(
                                array($c, 'resConsultaFinaltablaHtml'),
                                array($cc)
                            );
                        } else {
                            echo_esc("Falta resConsultaFinaltablaHtml en $n, $c");
                        }
                    }
                    $html_renglon .= "</td>";
                }
                echo "$html_renglon";
                if ($retroalim) {
                    echo "<td/>";
                }
                echo "</tr>\n";
            }
            echo "</table>";
            break;
        default:
            foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
                list($n, $c, $o) = $tab;
                if (($d = strrpos($c, "/"))>0) {
                    $c = substr($c, $d+1);
                }
                if (is_callable(array($c, 'resConsultaFinal'))) {
                    call_user_func_array(
                        array($c, 'resConsultaFinal'),
                        array($this->mostrar)
                    );
                } else {
                    echo_esc("Falta resConsultaFinal en $n, $c");
                }
            }
            if (isset($GLOBALS['gancho_rc_final'])) {
                foreach ($GLOBALS['gancho_rc_final'] as $k => $f) {
                    if ($this->mostrar == $k) {
                        if (is_callable($f)) {
                            call_user_func_array(
                                $f,
                                array($this->mostrar)
                            );
                        } else {
                            echo_esc("Falta $f de resConsultaFinal[$k]");
                        }
                    }
                }
            }
            break;
        }
        $sinpie = array('csv', 'sql', 'relato');
        if (isset($GLOBALS['resconsulta_sinpie'])) {
            $sinpie = array_merge($sinpie, $GLOBALS['resconsulta_sinpie']);
        }
        if (!in_array($this->mostrar, $sinpie)) {
            echo $html_enlace1;
            echo '<div align = "right"><a href = "index.php">' .
                '<b>Menú principal</b></a></div>';
            pie_envia($GLOBALS['pie_consulta_web']);
        }
    }


    /**
     * Retorna una fila de la tabla
     *
     * @param object  $db        Conexión a B.D
     * @param int     $idcaso    Código de caso
     * @param array   $campos    Campos por mostrar
     * @param array   $conv      Conversiones
     * @param array   $sal       Para conversiones con $conv
     * @param boolean $retroalim Con boton de retroalimentación
     *
     * @return string Fila en HTML
     */
    static function
    filaTabla($db, $idcaso, $campos, $conv, $sal, $retroalim = true)
    {
        //echo "OJO filaTabal(db, $idcaso, campos, conv, sal, retroalim);<br>";
        $col = "#FFFFFF";
        $dec = objeto_tabla('etiquetacaso');
        if (!PEAR::isError($dec)) {
            $dec->id_caso = $idcaso;
            $dec->find();
            $seploc = "";
            while ($dec->fetch()) {
                $det = $dec->getLink('id_etiqueta');
                if (strtolower(substr($det->observaciones, 0, 7)) == 'color #'
                ) {
                    $col = substr($det->observaciones, 6, 7);
                }
            }
        }
        $html_renglon = "<tr style='background-color: " . htmlentities($col) 
            . "'>";
        foreach ($campos as $cc => $nc) {
            $html_renglon .= "<td valign='top'>";
            $sep = "";
            $vr = $vrescon = $vrpre = $vrpost = "";
            // No se sacaron responsables y demás directamente en
            // la consulta por dificultad en el caso de ubicación
            // pues la información puede provenir de diversas tablas
            if ($cc == 'm_ubicacion') {
                $vr .= ResConsulta::ubicacion($db, $idcaso);
            } else if ($cc == 'm_presponsables') {
                $idp = array(); // Identificaciones
                $idp2=array();
                $ndp = array();
                ResConsulta::extraePResponsables(
                    $idcaso,
                    $db, $idp, $idp2, $ndp
                );
                $seploc = "";
                foreach ($ndp as $k => $np) {
                    $vr .= $seploc . trim($np);
                    $seploc = ", ";
                }
            } else if ($cc == 'm_fuentes') {
                $idp = array(); // Identificaciones
                $idp2=array();
                $ndp = array();
                $dff = objeto_tabla('escrito_caso');
                if (PEAR::isError($dff)) {
                    die($dff->getMessage());
                }
                $dff->id_caso = $idcaso;
                $dff->find();
                $seploc = "";
                while ($dff->fetch()) {
                    $des = $dff->getLink('id_prensa');
                    $vr .= $seploc . trim($des->nombre)." " .
                        $dff->fecha;
                    $seploc = ", ";
                }
            } else if ($cc == 'm_victimas') {
                $idp = array(); // Identificaciones
                $ndp = array();
                $edp = array();
                $indid = -1;
                $totv = ResConsulta::extraeVictimas(
                    $idcaso,
                    $db, $idp, $ndp, null, $indid, $edp
                );
                $k = 0;
                $seploc = "";
                for ($k = 0; $k < count($ndp); $k++) {
                    $q = "SELECT id_tipo_violencia, id_supracategoria, " .
                    "id_categoria " .
                    " FROM acto, categoria " .
                    " WHERE id_persona='". (int)$idp[$k] . "' " .
                    " AND id_caso='". (int)$idcaso . "' " .
                    " AND acto.id_categoria=categoria.id"
                    ;
                    $result = hace_consulta($db, $q);
                    $row = array();
                    $septip = " "; $tip = "";
                    $vrescon .= $seploc . trim($ndp[$k]);
                    while ($result->fetchInto($row)) {
                        $tip .= $septip . "<a href='consulta_web_cat.php?t = " .
                        $row[0] . "&s = ".$row[1] . "&c = ".$row[2] . "'>" .
                        $row[0] . $row[2] . "</a>";
                        $vrescon .= $septip . $row[0] . $row[2];
                        $septip = ", ";
                    }
                    $med = "";
                    if (isset($edp[$k]) && $edp[$k] > 0) {
                        $med = " (".$edp[$k] . ")";
                    }
                    $vr .= $seploc . $ndp[$k] . $med . $tip;
                    $seploc = ", ";
                }
                $indid = -1;
                $idind = -1;
                $nind = -1; $totelem = 0;
                $totv+=ResConsulta::extraeColectivas(
                    $idcaso,
                    $db, $idp, $ndp, $cdp, null, $ind, $totelem
                );
                $bk = $k;
                for (; $k < count($ndp); $k++) {
                    $q = "SELECT id_tipo_violencia, id_supracategoria, " .
                    " id_categoria " .
                    " FROM actocolectivo, categoria " .
                    " WHERE id_grupoper='". (int)$idp[$k] . "' " .
                    " AND id_caso='". (int)$idcaso . "' " .
                    " AND actocolectivo.id_categoria=categoria.id"
                    ;
                    $result = hace_consulta($db, $q);
                    $row = array();
                    $septip = " "; $tip = "";
                    $vrescon .= $seploc . trim($ndp[$k]);
                    while ($result->fetchInto($row)) {
                        $tip .= $septip . "<a href='consulta_web_cat.php?t = " .
                        $row[0] . "&s = ".$row[1] . "&c = ".$row[2] . "'>" .
                        $row[0] . $row[2] . "</a>";
                        $vrescon .= $septip . $row[0] . $row[2];
                        $septip = ", ";
                    }
                    $vr .= $seploc . $ndp[$k] . $tip;
                    if ((int)$cdp[$k - $bk] > 0) {
                        $vr .= " (".$cdp[$k - $bk] . ")";
                        $vrescon .= " (".(int)$cdp[$k - $bk] . ")";
                    }
                    $seploc = ", ";
                }

                $vrpost = " | Víctimas:".$totv;

            } else if ($cc == 'm_tipificacion') {
                $idp = array(); // Identificaciones
                $ndp = array();
                $ncat = array();
                ResConsulta::llenaSelCategoria(
                    $db,
                    "(SELECT id_tipo_violencia, id_supracategoria, " .
                    "id_categoria FROM categoria_p_responsable_caso " .
                    "WHERE id_caso='$idcaso') UNION " .
                    "(SELECT id_tipo_violencia, id_supracategoria, " .
                    "id_categoria FROM categoria, acto " .
                    "WHERE id_caso='$idcaso' AND " .
                    "categoria.id=acto.id_categoria) UNION " .
                    "(SELECT id_tipo_violencia, id_supracategoria, " .
                    "id_categoria FROM categoria, actocolectivo " .
                    "WHERE id_caso='$idcaso' AND " .
                    "categoria.id=actocolectivo.id_categoria) " .
                    "ORDER BY id_tipo_violencia," .
                    "id_supracategoria, id_categoria;", $ncat, array(1, 2)
                );
                $vr = $seploc = "";
                foreach ($ncat as $k => $nc) {
                    $vr .= $seploc . $k . " ".$nc;
                    $seploc = ",  ";
                }
            } else if ($cc == 'caso_id') {
                $vrpre = "<a href='captura_caso.php?modo=edita&id=" .
                $sal[$conv[$cc]] . "'>";
                $vr = $sal[$conv[$cc]];
                $vrpost = "</a>";
            } else if (isset($conv[$cc]) && isset($sal[$conv[$cc]])) {
                $vr = trim($sal[$conv[$cc]]);
            } else {
                $vr = '';
                foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
                    list($n, $c, $o) = $tab;
                    if (($d = strrpos($c, "/"))>0) {
                        $c = substr($c, $d+1);
                    }
                    if (is_callable(array($c, 'resConsultaFilaTabla'))) {
                        $vr .= call_user_func_array(
                            array($c, 'resConsultaFilaTabla'),
                            array(&$db, $cc, $idcaso)
                        );
                    } else {
                        echo_esc("Falta resConsultaFilaTabla en $n, $c");
                    }
                }
            }
            $escon[$cc] = $vrescon == '' ? $vr : $vrescon;
            $html_renglon .= $vrpre . strip_tags($vr) . $vrpost . "</td>";
        }
        echo "$html_renglon\n";
        if ($retroalim) {
            echo "<td valign=top><form method=\"POST\" " .
                "action=\"consulta_web_correo.php\">\n";
            foreach ($escon as $l => $v) {
                echo "<input type=\"hidden\" name=\"" 
                    . htmlentities($l) . "\" value=\""
                    . htmlentities($v) . "\">\n";
            }
            echo "<p>\n<input TYPE=\"submit\" NAME=\"Request\" " .
                "VALUE=\"Comente caso\">\n";
            echo "</form></td>\n";
        }
    }


    /**
     * Un relato es un arreglo ar, cuyos elementos son de la forma
     * elemento => valor donde
     * elemento puede ser un elemento solo o un elemento con atributo
     * de la forma elemento{atributo->valor}.
     * La representación en XML de estos relatos es la definida en el
     * documento de diseño del SINCODH.
     *
     * Ideas:
     * - autocontenido, poner códigos previamente introducidos.
     *
     * @param integer $idcaso Identificación del caso
     * @param object  $db     conexión a base de datos
     * @param array   $campos Campos por mostrar
     * @param boolean $varlin Varías lineas?
     *
     * @return string Reporte
     */
    static function reporteRelato($idcaso, $db = null,
        $campos = array(), $varlin = true
    ) {
        //echo "OJO Entrando a reporteRelato: " . memory_get_usage() ."<br>";
        $arotros = array(); // Para poner observaciones al final
        $dcaso = objeto_tabla('caso');
        $dcaso->get('id', $idcaso);
        $arcaso = array();
        $formacomp = 'privado';
        $locdb = false;
        if ($db == null) {
            $locdb = true; 
            $db = $dcaso->getDatabaseConnection();
        }
        $nom = $db->getOne(
            "SELECT nombre FROM etiqueta, etiquetacaso "
            . " WHERE etiquetacaso.id_caso='$idcaso' " .
            " AND etiquetacaso.id_etiqueta=etiqueta.id " .
            " AND nombre LIKE 'SINCODH%'"
        );
        if (!PEAR::isError($nom) && $nom == 'SINCODH:PUBLICO') {
               $formacomp = 'publico';
        } else if (!isset($campos['m_fuentes'])  ) {
            // Si no van fuentes se considera público
            $formacomp = 'publico';
        } else {
            $formacomp = 'privado';
        }
        unset($nom);
        $dcaso->fb_fieldsToRender = array('id', 'titulo', 'fecha',
            'hora', 'duracion', 'memo', 'gr_confiabilidad',
            'gr_esclarecimiento', 'gr_impunidad', 'gr_informacion',
            'bienes', 'id_intervalo'
        );
        $dcaso->aRelato(
            $arcaso,
            array('forma_compartir' => $formacomp,
            'memo' => 'hechos',
            'gr_confiabilidad' => 'observaciones{tipo->gr_confiabilidad}',
            'gr_esclarecimiento' => 'observaciones{tipo->gr_esclarecimiento}',
            'gr_impunidad' => 'observaciones{tipo->gr_impunidad}',
            'gr_informacion' => 'observaciones{tipo->gr_informacion}',
            'id_intervalo' => 'observaciones{tipo->id_intervalo}',
            'bienes' => 'observaciones{tipo->bienes}'
            )
        );

        //print_r($arcaso); die("x");
        $r = "<relato>\n";
        $r .= a_elementos_xml(
            $r, 2,
            array('organizacion_responsable' =>
            $GLOBALS['organizacion_responsable'],
            'derechos' =>
            $GLOBALS['derechos']
        )
        );
        $r .= a_elementos_xml(
            $r, 2,
            array('id_relato' => $dcaso->id,
            'forma_compartir' => $formacomp
            )
        );
        //echo "OJO Antes de agregar titulo: " . memory_get_usage() ."<br>";
        $r .= a_elementos_xml($r, 2, array('titulo' => $arcaso['titulo']));
        if (isset($campos['caso_memo'])) {
            $r .= a_elementos_xml(
                $r, 2,
                array('hechos' => $arcaso['hechos'])
            );
        }

        $afecha = explode("-", $dcaso->fecha);

        $max_id_grupo = 0; // Máxima identificación de grupos en víctimas
        //colectivas para sumarlo a id. de presuntos responsables
        if (isset($campos['m_victimas'])) {
            $r .= "  <!-- Victimas personas individuales -->\n";
            $dvictima = objeto_tabla('victima');
            $dvictima->id_caso = $idcaso;
            $dvictima->orderBy('id_persona');
            $dvictima->find();
            while ($dvictima->fetch()) {
                $arvictima = array();
                $dvictima->aRelato(
                    $arvictima,
                    array('fecha_caso' => $afecha,
                    'nombres' => 'nombre',
                    'apellidos' => 'apellido',
                    'id_departamento' => 'REL;observaciones{tipo->departamento}',
                    'id_municipio' => 'REL;observaciones{tipo->municipio}',
                    'id_clase' => 'REL;observaciones{tipo->clase}',
                )
                );
                $drelp = objeto_tabla('relacion_personas');
                $drelp->id_persona1 = $dvictima->id_persona;
                $drelp->orderBy('id_persona2');
                $drelp->find();
                $sep = $relp = "";
                while ($drelp->fetch()) {
                    $op = $drelp->getLink('id_persona2');
                    $tr = $drelp->getLink('id_tipo');
                    $relp .= $sep . $tr->nombre . " " . $op->nombres .
                        ", " .  $op->apellidos . ". " . $op->observaciones;
                    $sep = "; ";
                }
                $drelp->free();
                $arvictima['observaciones{tipo->relacionados}'] = $relp;

                //print_r($arvictima); die("x");
                $r .= "  <persona>\n";
                a_elementos_xml(
                    $r, 4,
                    subarreglo(
                        $arvictima, array('id_persona', 'nombre',
                    'nombre2', 'apellido', 'apellido2', 'docid',
                    'fecha_nacimiento', 'sexo',
                    'observaciones{tipo->departamento}',
                    'observaciones{tipo->municipio}',
                    'observaciones{tipo->clase}',
                    'observaciones{tipo->relacionados}',)
                    )
                );
                unset($arvictima);
                $r .= "  </persona>\n";
            }
            $dvictima->free();
            unset($dvictima);

            $r .= "  <!-- Grupos victimizados -->\n";
            $dvictimacol = objeto_tabla('victima_colectiva');
            $dvictimacol->id_caso = $idcaso;
            $dvictimacol->orderBy('id_grupoper');
            $dvictimacol->find();
            while ($dvictimacol->fetch()) {
                $dgrupoper = $dvictimacol->getLink('id_grupoper');
                $max_id_grupo = max($dgrupoper->id, $max_id_grupo);
                $argrupo = array();
                $dgrupoper->aRelato(
                    $argrupo, array('nombre' => 'nombre_grupo',
                    'id' => 'id_grupo',
                    'anotaciones' => 'observaciones{tipo->anotaciones}')
                );
                $dvictimacol->aRelato(
                    $argrupo, array(
                        'id_organizacion_armada' =>
                        'REL;observaciones{tipo->organizacion_armada}',
                        'personas_aprox'
                        => 'observaciones{tipo->personas_aprox}')
                );

                $r .= "  <grupo>\n";

                $atradrel = DataObjects_Victima_colectiva::tradRelato();
                foreach ($atradrel as $t => $vt) {
                    $cx = $vt[0];
                    $idt = $vt[1]; 
                    $lr = lista_relacionados(
                        $t,
                        array('id_grupoper' => $dvictimacol->id_grupoper,
                        'id_caso' => $dvictimacol->id_caso),
                        $idt
                    );
                    $argrupo["observaciones{tipo->$cx}"] = $lr;
                }
                a_elementos_xml($r, 4, $argrupo);

                unset($argrupo);
                $r .= "  </grupo>\n";
                $dgrupoper->free();
                unset($dgrupoper);
            }
            $dvictimacol->free();
            unset($dvictimacol);
        }

        if (isset($campos['m_presponsables'])) {
            $r .= "  <!-- Presuntos responsables -->\n";
            $dprespcaso = objeto_tabla('presuntos_responsables_caso');
            $dprespcaso->id_caso = $idcaso;
            $dprespcaso->orderBy('id_p_responsable');
            $dprespcaso->find();
            while ($dprespcaso->fetch()) {
                $argrupo = array();
                $dprespcaso->aRelato($argrupo);
                $r .= "  <grupo>\n";
                $argrupo['id_p_responsable'] += $max_id_grupo;
                a_elementos_xml(
                    $r, 4, subarreglo(
                        $argrupo,
                        array('id_p_responsable', 'nombre',
                        'sigla', 'subgrupo_de')
                    ),
                    array('id_p_responsable' => 'id_grupo',
                    'nombre' => 'nombre_grupo',)
                );
                $dcp = objeto_tabla('categoria_p_responsable_caso');
                $dcp->id_caso = $dprespcaso->id_caso;
                $dcp->id_p_responsable = $dprespcaso->id_p_responsable;
                if ($dcp->find()>0) {
                    while ($dcp->fetch()) {
                        $dcat = $dcp->getLink('id_categoria');
                        a_elementos_xml(
                            $r, 6,
                            array('agresion_sin_vicd' => $dcat->nombre . " (" .
                            $dcat->id . ")")
                        );
                        $dcat->free();
                    }
                }
                $dcp->free();
                unset($dcp);
                a_elementos_xml(
                    $r, 4, subarreglo(
                        $argrupo,
                        array('observaciones')
                    )
                );
                unset($argrupo);
                a_elementos_xml(
                    $r, 4, array(
                        'observaciones{tipo->bloque}' => $dprespcaso->bloque,
                        'observaciones{tipo->frente}' => $dprespcaso->frente,
                        'observaciones{tipo->brigada}' => $dprespcaso->brigada,
                        'observaciones{tipo->batallon}' => $dprespcaso->batallon,
                        'observaciones{tipo->division}' => $dprespcaso->division,
                        'observaciones{tipo->otro}' => $dprespcaso->otro,
                    )
                );
                $r .= "  </grupo>\n";
            }
            $dprespcaso->free();
            unset($dprespcaso);
        }

        if (isset($campos['m_victimas'])) {
            $r .= "  <!-- Victima Individual -->\n";
            $dvictima = objeto_tabla('victima');
            $dvictima->id_caso = $idcaso;
            $dvictima->orderBy('id_persona');
            $dvictima->find();
            while ($dvictima->fetch()) {
                $arvictima = array();
                $dvictima->aRelato(
                    $arvictima,
                    array('fecha_caso' => $afecha,
                    'id_profesion' => 'REL;ocupacion',
                    'id_sector_social' => 'REL;sector_condicion',
                    'id_organizacion' => 'REL;organizacion',
                    'id_filiacion' => 'REL;observaciones{tipo->filiacion}',
                    'hijos' => 'observaciones{tipo->hijos}',
                    'id_vinculo_estado' =>
                    'REL;observaciones{tipo->vinculo_estado}',
                    'id_organizacion_armada' =>
                    'REL;observaciones{tipo->organizacion_armada}',
                    'anotaciones' => 'observaciones{tipo->anotaciones}',)
                );
                //print_r($arvictima); die("x");
                $r .= "  <victima>\n";
                a_elementos_xml(
                    $r, 4,
                    subarreglo(
                        $arvictima, array('id_persona',
                        'ocupacion', 'sector_condicion', 'iglesia',
                        'organizacion', 'id_grupo',
                        'estado_tras_hecho', 'danio_directo',
                        'danio_indirecto', 'personas_dependientes',
                        'observaciones{tipo->hijos}',
                        'observaciones{tipo->filiacion}',
                        'observaciones{tipo->vinculo_estado}',
                        'observaciones{tipo->organizacion_armada}',
                        'observaciones{tipo->anotaciones}',)
                    )
                );
                unset($arvictima);

                $tan = lista_relacionados(
                    'antecedente_victima',
                    array('id_persona' => $dvictima->id_persona),
                    'id_antecedente'
                );
                $drango = $dvictima->getLink('id_rango_edad');
                a_elementos_xml(
                    $r, 4, array(
                        'observaciones{tipo->rango_edad}' => $drango->rango,
                        'observaciones{tipo->antecedentes}' => $tan,
                    )
                );
                $drango->free();
                unset($drango);
                $r .= "  </victima>\n";
            }
            $dvictima->free();
            unset($dvictima);

            $r .= "  <!-- Presuntos responsables individuales -->\n";
        }
        unset($afecha);

        $ubisitio = "";
        $ubilugar = "";
        $ubitipositio = "";
        if (isset($campos['m_ubicacion'])) {
            $r .= "  <!-- Ubicacion -->\n";
            // Tomamos ubicación (una sola)
            $dubicacion= objeto_tabla('ubicacion');
            $dubicacion->id_caso = $idcaso;
            $dubicacion->find();
            $nubi = 0; $pobs = "";
            while ($dubicacion->fetch()) {
                $nubi++;
                $uobs = $pobs;
                $pobs .= " - dep: " . $dubicacion->id_departamento .
                    " mun: " . $dubicacion->id_municipio .
                    " cla: " . $dubicacion->id_clase .
                    " longitud: " . $dubicacion->longitud .
                    " latitud: " . $dubicacion->latitud .
                    " tipositio: " . $dubicacion->id_tipo_sitio;
            }
            if ($nubi > 1) {
                $arotros['observaciones{tipo->etiqueta:IMPORTA_RELATO}']
                    = date('Y-m-d') . " Tiene más de una ubicacion: $uobs";
            }
            $arubicacion = array();
            $dubicacion->aRelato(
                $arubicacion,
                array('forma_compartir' => $formacomp)
            );
            $r .= a_elementos_xml(
                $r, 2,
                subarreglo(
                    $arcaso, array('fecha', 'hora',
                'duracion'
                    )
                )
            );

            $r .= a_elementos_xml(
                $r, 2,
                subarreglo(
                    $arubicacion, array('departamento', 'municipio',
                    'centro_poblado', 'longitud', 'latitud')
                )
            );
            $ts = $dubicacion->getLink('id_tipo_sitio');
            $ubisitio = $dubicacion->sitio;
            $ubilugar = $dubicacion->lugar;
            $ubitipositio = isset($ts->nombre) ? $ts->nombre : '';
            if (!PEAR::isError($ts) && method_exists($ts, 'free')) {
                $ts->free();
                unset($ts);
            }
            $dubicacion->free();
            unset($dubicacion);
        }

        if (isset($campos['m_tipificacion'])) {
            $r .= "  <!-- Actos con Victimas Individuales -->\n";
            $dacto = objeto_tabla('acto');
            $dacto->id_caso = $idcaso;
            $dacto->orderBy('id_persona');
            $dacto->find();
            while ($dacto->fetch()) {
                $dcat = $dacto->getLink('id_categoria');
                $dper = $dacto->getLink('id_persona');
                $dpres = $dacto->getLink('id_p_responsable');
                $dvictima = objeto_tabla('victima');
                $dvictima->id_caso = $idcaso;
                $dvictima->id_persona = $dacto->id_persona;
                $dvictima->find();
                $dvictima->fetch(1);
                $q = "SELECT clasificacion " .
                    " FROM parametros_reporte_consolidado, categoria " .
                    " WHERE col_rep_consolidado=" .
                    " parametros_reporte_consolidado.no_columna " .
                    " AND categoria.id='" . $dacto->id_categoria . "'";
                $result = hace_consulta($db, $q);
                $row = array();
                $general = "";
                if ($result->fetchInto($row)) {
                    $general = $row[0];
                }
                if ($general == "") {
                    $general = $dcat->nombre;
                }
                $r .= "  <acto>\n";
                $r .= a_elementos_xml(
                    $r, 4,
                    array('agresion' => $general,
                    'agresion_particular' => $dcat->nombre . " (" .
                    $dcat->id . ")",
                    'id_victima_individual' => $dvictima->valorRelato(),
                    'id_presunto_grupo_responsable' =>
                    $dpres->valorRelato() + $max_id_grupo)
                );
                $r .= "  </acto>\n";
                $dvictima->free();
                unset($dvictima);
            }
            $r .= "\n";
            $dacto->free();
            unset($dacto);

            $r .= "  <!-- Actos con Victimas Colectivas -->\n";
            $dactocol =objeto_tabla('actocolectivo');
            $dactocol->id_caso = $idcaso;
            $dactocol->orderBy('id_grupoper');
            $dactocol->find();
            while ($dactocol->fetch()) {
                $dccom = $dactocol->getLink('id_categoria');
                $dpper = $dactocol->getLink('id_p_responsable');
                $dvictimacol = objeto_tabla('victima_colectiva');
                $dvictimacol->id_caso = $idcaso;
                $dvictimacol->id_grupoper= $dactocol->id_grupoper;
                $dvictimacol->find();
                $dvictimacol->fetch(1);
                $q = "SELECT clasificacion " .
                    " FROM parametros_reporte_consolidado, categoria " .
                    " WHERE col_rep_consolidado =" .
                    " parametros_reporte_consolidado.no_columna " .
                    " AND categoria.id='" . $dccom->id. "'";
                $result = hace_consulta($db, $q);
                unset($q);
                $row = array();
                $general = "";
                if ($result->fetchInto($row)) {
                    $general = $row[0];
                }
                if ($general == "") {
                    $general = $dccom->nombre;
                }
                $r .= "  <acto>\n";
                $idgv = $dvictimacol->valorRelato();
                //echo "idgv=$idgv<br>";
                $r .= a_elementos_xml(
                    $r, 4,
                    array('agresion' => $general,
                    'agresion_particular' => $dccom->nombre . " (" .
                    $dccom->id . ")",
                    'id_grupo_victima' => $idgv,
                    'id_presunto_grupo_responsable' =>
                    $dpper->id + $max_id_grupo)
                );
                unset($general);
                unset($idgv);
                $dccom->free();
                unset($dccom);
                $dpper->free();
                unset($dpper);
                $r .= "  </acto>\n";
                //print_r($r); die("x");
                $dvictimacol->free();
                unset($dvictimacol);
            }
            $dactocol->free();
            unset($dactocol);
        }
        $r .= "\n";


        //        $r .= "<contexto>"
        if (isset($campos['m_fuentes'])
            && isset($_SESSION['id_funcionario'])
        ) {
            include $_SESSION['dirsitio'] . "/conf.php";
            $aut_usuario = "";
            autenticaUsuario($dsn, $accno, $aut_usuario, 0);
            if (!in_array(42, $_SESSION['opciones'])) {
                die('No autorizado');
            }

            //Quien opera debe ser bien conciente, encomendamos a Dios
            //a las personas que dan información para que el proteja su
            //identidad en el caso de quienes no quieren que sea revelada
            //y para que proteja la vida de quienes optan por visibilización,
            //en particular moviendo corazones en nuestra sociedad.
            $r .= "  <!-- Fuente frecuente -->\n";
            $descritocaso = objeto_tabla('escrito_caso');
            $descritocaso->id_caso = $idcaso;
            $descritocaso->orderBy('fecha, id_prensa');
            $descritocaso->find();
            while ($descritocaso->fetch()) {
                $arfuente = array();
                $dprensa = $descritocaso->getLink('id_prensa');
                $arfuente['nombre_fuente'] = $dprensa->nombre;
                $dprensa->free();
                unset($dprensa);
                $descritocaso->aRelato(
                    $arfuente,
                    array('fecha' => 'fecha_fuente',
                    'ubicacion_fisica' => 'ubicacion_fuente',
                    'ubicacion' => 'observaciones{tipo->ubicacion}',
                    'clasificacion' => 'observaciones{tipo->clasificacion}')
                );
                unset($arfuente['id_prensa']);
                $r .= "  <fuente>\n";
                a_elementos_xml($r, 4, $arfuente);
                $r .= "  </fuente>\n";
                unset($arfuente);
            }
            $descritocaso->free();
            unset($descritocaso);

            $r .= "  <!-- Fuente no frecuente -->\n";
            $dfuentedirectacaso = objeto_tabla('fuente_directa_caso');
            $dfuentedirectacaso->id_caso = $idcaso;
            $dfuentedirectacaso->orderBy('fecha, id_fuente_directa');
            $dfuentedirectacaso->find();
            while ($dfuentedirectacaso->fetch()) {
                $dfd = $dfuentedirectacaso->getLink('id_fuente_directa');
                $arfuente = array();
                $arfuente['nombre_fuente'] = $dfd->nombre;
                $dfuentedirectacaso->aRelato(
                    $arfuente,
                    array('anotacion' => 'observaciones{tipo->anotacion}',
                    'fecha' => 'fecha_fuente',
                    'ubicacion_fisica' => 'ubicacion_fuente',
                    'tipo_fuente' => 'observaciones{tipo->tipofuente}')
                );
                $ia2 = $dfuentedirectacaso->tipo_fuente;
                $arfuente['observaciones{tipo->tipofuente}']
                    = $dfuentedirectacaso->fb_enumOptions['tipo_fuente'][$ia2];
                $r .= "  <fuente>\n";
                a_elementos_xml($r, 4, $arfuente);
                $r .= "  </fuente>\n";
                unset($ia2);
                unset($arfuente);
                $dfd->free();
                unset($dfd);
            }
            $dfuentedirectacaso->free();
            unset($dfuentedirectacaso);
        }
        if (isset($campos['caso_memo'])) {
            //        $r .= "<acciones_juridicas>"
            //        $r .= "<otras_acciones>"
            //        $r .= "<fecha_publicacion>"
            //        $r .= "<anexo>"


            $r .= "  <!-- Otros -->\n";
            $dinter = $dcaso->getLink('id_intervalo');
            $arotros['observaciones{tipo->intervalo}'] = $dinter->nombre;
            $dinter->free();
            unset($dinter);

            $fr = lista_relacionados(
                'frontera_caso',
                array('id_caso' => $idcaso), 'id_frontera'
            );
            $arotros['observaciones{tipo->frontera}'] = $fr;
            $reg = lista_relacionados(
                'region_caso',
                array('id_caso' => $idcaso), 'id_region'
            );
            $arotros['observaciones{tipo->region}'] = $reg;

            $r .= a_elementos_xml($r, 2, $arotros);
            unset($arotros);
            unset($reg);
            unset($fr);
            /*
             * Se decide no poner analista
             *
             */
            $r .= a_elementos_xml(
                $r, 2,
                subarreglo(
                    $arcaso, array(
                        'observaciones{tipo->gr_confiabilidad}',
                        'observaciones{tipo->gr_esclarecimiento}',
                        'observaciones{tipo->gr_impunidad}',
                        'observaciones{tipo->gr_informacion}',
                        'observaciones{tipo->bienes}',
                    )
                )
            );
            $tcont = lista_relacionados(
                'caso_contexto',
                array('id_caso' => $idcaso),
                'id_contexto'
            );
            $tan = lista_relacionados(
                'antecedente_caso',
                array('id_caso' => $idcaso), 'id_antecedente'
            );
            $r .= a_elementos_xml(
                $r, 2,
                array('observaciones{tipo->sitio}' => $ubisitio,
                'observaciones{tipo->lugar}' => $ubilugar,
                'observaciones{tipo->tipo_sitio}' => $ubitipositio,
                'observaciones{tipo->contexto}' => $tcont,
                'observaciones{tipo->antecedente}' => $tan,)
            );
            unset($tan); 
            unset($tcon);
            unset($ubitipositipo);
            unset($ubilugar);
            unset($ubisitio);
        }
        $dcaso->free();
        unset($dcaso);
        // Modulos, van como observaciones
        unset($arcaso);
        if ($locdb) {
            unset($db);
        }

        $r .= "</relato>";

        //echo "OJO Saliendo de reporteRelato: " . memory_get_usage() ."<br>";
        return $r;
    }

    /**
     * Retorna un registro del reporte general.
     *
     * @param integer $idcaso id. del caso
     * @param handle  $db     Conexión a BD
     * @param array   $campos Campos por mostrar
     * @param boolean $varlin Varias líneas
     *
     * @return string  Registro como texto
     */
    static function reporteGeneralHtml($idcaso, $db = null,
        $campos = array(), $varlin = true
    ) {
        $dcaso = objeto_tabla('caso');
        if ($db == null) {
            $db = $dcaso->getDatabaseConnection();
        }
        if (PEAR::isError($dcaso)) {
            die($dcaso->getMessage());
        }
        $dcaso->get('id', $idcaso);
        $r = ""; $rcaso = "";
        if (array_key_exists('caso_id', $campos)) {
            $rcaso .= "CASO NO. " 
                . "<a href='captura_caso.php?modo=edita&id={$dcaso->id}'>" 
                . "{$dcaso->id}</a>\n";
        }
        if (array_key_exists('caso_fecha', $campos)) {
            $m = explode("-", $dcaso->fecha);
            $r .= a_mayusculas($GLOBALS['etiqueta']['fecha']).": " .
                $m[2] . "-".$GLOBALS['mes'][(int)$m[1]] . "-".$m[0] . " " .
                trim($dcaso->hora)." ";
            if ($dcaso->id_intervalo != null) {
                $dintervalo = $dcaso->getLink('id_intervalo');
                $r .= trim($dintervalo->nombre);
            }
        }
        if (array_key_exists('m_ubicacion', $campos)) {
            $idd = array(); // Identificaciones
            $idm = array();
            $idc = array();
            $ndd = array(); // Nombres
            $ndm = array();
            $ndc = array();
            $tdu = array();
            ResConsulta::extraeUbicacionCaso(
                $idcaso,
                $db, $idd, $ndd, $idm, $ndm, $idc, $ndc, $tdu
            );
            $seploc = "";
            $vr = "";
            $idu = ":";
            $cadub = "";
            $arr_ubica_listo = array();
            foreach ($ndd as $k => $nd) {
                $vr .= $seploc . trim($nd);
                $idu .= $idd[$k];
                if ($ndm[$k] != '') {
                    $vr .= " / ".trim($ndm[$k]);
                    $idu .= ":".$idm[$k];
                }
                if ($ndc[$k] != '') {
                    $vr .= " / ".trim($ndc[$k]);
                    $idu .= ":".$idc[$k];
                }
                if (isset($arr_ubica[$idu])) {
                    $sepu = " : ";
                    $arr_ubica_listo[$idu]=1;
                    foreach ($arr_ubica[$idu] as $i => $nu) {
                        $vr .= $sepu . $nu;
                        $sepu = ", ";
                    }
                }
                $seploc = "\n ";
                $cadub .= " " . ucfirst(strtolower($tdu[$k]));
            }

            if (isset($arr_ubica_listo)) {
                foreach ($arr_ubica_listo as $idu => $v) {
                    $sepu = "\n";
                    if ($v == 0) {
                        foreach ($arr_ubica_divipol[$idu] as $idd => $ddiv) {
                            $vr .= $sepu . $arr_ubica_divipol[$idu][$idd]
                                . "/" . $arr_ubica[$idu][$idd];
                        }
                    }
                }
            }

            $r .= "  Tip. Ub: ";
            $r .= trim($cadub);
            $r .= "\n\n";

            $dregioncaso = objeto_tabla('region_caso');
            $dregioncaso->id_caso = $idcaso;
            $dregioncaso->orderBy('id_region');
            $dregioncaso->find();
            $fl = "";
            $sep = $GLOBALS['etiqueta']['region'] . ": ";
            while ($dregioncaso->fetch()) {
                $dregion = $dregioncaso->getLink('id_region');
                $r .= $sep . trim($dregion->nombre);
                $fl = "\n";
                $sep = ", ";
            }
            $r .= $fl;

            $fl = "";
            $dfronteracaso = objeto_tabla('frontera_caso');
            if (PEAR::isError($dfronteracaso)) {
                die($dfronteracaso->getMessage());
            }
            $dfronteracaso->id_caso = $idcaso;
            $dfronteracaso->orderBy('id_frontera');
            $dfronteracaso->find();
            $sep = $GLOBALS['etiqueta']['frontera'] . ": ";
            while ($dfronteracaso->fetch()) {
                $dfrontera = $dfronteracaso->getLink('id_frontera');
                $r .= $sep . trim($dfrontera->nombre);
                $fl = "\n";
                $sep = ", ";
            }
            $r .= $fl;
            $r .= $vr;
            $r .= "\n";
        }
        if (array_key_exists('m_fuentes', $campos)) {
            $fl = "";
            $sep = $GLOBALS['etiqueta']['id_prensa'] . ": ";
            $descritocaso = objeto_tabla('escrito_caso');
            if (PEAR::isError($descritocaso)) {
                die($descritocaso->getMessage());
            }
            $descritocaso->id_caso = $idcaso;
            $descritocaso->orderBy('fecha, id_prensa');
            $descritocaso->find();
            while ($descritocaso->fetch()) {
                $dprensa = $descritocaso->getLink('id_prensa');
                $r .= $sep . trim($dprensa->nombre);
                $r .= " - ".trim($descritocaso->ubicacion);
                $r .= " - ";
                $m = explode("-", $descritocaso->fecha);
                $r .= $m[2] . "-".$GLOBALS['mes'][(int)$m[1]] . "-".$m[0];
                $fl = "\n";
                $sep = "\n ";
            }
            $r .= $fl;

            $fl = "";
            $sep = "";
            $dfuentedirectacaso = objeto_tabla('fuente_directa_caso');
            if (PEAR::isError($dfuentedirectacaso)) {
                die($dfuentedirectacaso->getMessage());
            }
            $dfuentedirectacaso->id_caso = $idcaso;
            $dfuentedirectacaso->orderBy('fecha, id_fuente_directa');
            $dfuentedirectacaso->find();
            while ($dfuentedirectacaso->fetch()) {
                $dfuentedirecta
                    = $dfuentedirectacaso->getLink('id_fuente_directa');
                $r .= $sep . trim($dfuentedirecta->nombre);
                $r .= " - ".trim($dfuentedirectacaso->ubicacion_fisica);
                $r .= " - ";
                $m = explode("-", $dfuentedirectacaso->fecha);
                $r .= $m[2] . "-".$GLOBALS['mes'][(int)$m[1]] . "-".$m[0];

                $fl = "\n";
                $sep = "\n ";
            }
            $r .= $fl;
        }

        $rantesmemo = $r;

        $r = "";
        if (array_key_exists('m_tipificacion', $campos)) {
            $lcat = array();

            $dacto = objeto_tabla('acto');
            $dacto->id_caso = $idcaso;
            $dacto->find();
            while ($dacto->fetch()) {
                $lcat[$dacto->id_categoria] = $dacto->id_categoria;
            }
            $dactocolectivo = objeto_tabla('actocolectivo');
            $dactocolectivo->id_caso = $idcaso;
            $dactocolectivo->find();
            while ($dactocolectivo->fetch()) {
                $lcat[$dactocolectivo->id_categoria]
                    = $dactocolectivo->id_categoria;
            }
            $dcatpr= objeto_tabla('categoria_p_responsable_caso');
            $dcatpr->id_caso = $idcaso;
            $dcatpr->find();
            while ($dcatpr->fetch()) {
                $lcat[$dcatpr->id_categoria] = $dcatpr->id_categoria;
            }
            sort($lcat);
            foreach ($lcat as $idcat) {
                $dcategoria = objeto_tabla('categoria');
                $dcategoria->id = $idcat;
                $dcategoria->find(1);

                $r .= $dcategoria->id_tipo_violencia .
                    $dcategoria->id. ". ";
                $dtipoviolencia = $dcategoria->getLink('id_tipo_violencia');
                $r .= trim($dtipoviolencia->nombre)." - ";
                $dsupracategoria = objeto_tabla('supracategoria');
                $dsupracategoria->id = $dcategoria->id_supracategoria;
                $dsupracategoria->id_tipo_violencia
                    = $dcategoria->id_tipo_violencia;
                $dsupracategoria->find(1);
                $r .= trim($dsupracategoria->nombre)." - ";
                $r .= trim($dcategoria->nombre)."\n";
            }
            $r .= "\n";
        }

        if (array_key_exists('m_victimas', $campos)
            || array_key_exists('m_presponsables', $campos)
        ) {
            ResConsulta::listaPrCatVictima($idcaso, $campos, $r, true);
        }
        $r .= "\n";

        foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
            list($n, $c, $o) = $tab;
            if (($d = strrpos($c, "/"))>0) {
                $c = substr($c, $d+1);
            }
            if (is_callable(array($c, 'reporteGeneralRegistroHtml'))) {
                $r .= call_user_func_array(
                    array($c, 'reporteGeneralRegistroHtml'),
                    array(&$db, $campos, $idcaso)
                );
            } else {
                echo_esc("Falta reporteGeneralRegistroHtml en $n, $c");
            }
        }

        if (array_key_exists('m_fuentes', $campos)) {
            $sep = "\n\n" . a_mayusculas($GLOBALS['etiqueta']['analista']) .
                "(s):\n    ";
            $dfuncaso = objeto_tabla('funcionario_caso');
            if (PEAR::isError($dfuncaso)) {
                die($dfuncaso->getMessage());
            }
            $dfuncaso->id_caso = $idcaso;
            $dfuncaso->find();
            while ($dfuncaso->fetch()) {
                $dfuncionario = $dfuncaso->getLink('id_funcionario');
                $r .= $sep . trim($dfuncionario->nombre);
                $r .= "  ";
                $m = explode("-", $dfuncaso->fecha_inicio);
                $r .= $m[2] . "-".$GLOBALS['mes'][(int)$m[1]] . "-".$m[0];
                $sep = "\n    ";
            }
        }

        $r .= "\n\n";
        $rdespuesmemo = $r;

        $r = $rcaso . strip_tags($rantesmemo);
        if (array_key_exists('caso_memo', $campos)
            && $dcaso->memo != null && trim($dcaso->memo)!=''
        ) {
            $r .= "\n".$GLOBALS['etiqueta']['memo'] . ":\n";
            if ($varlin) {
                $r .= "</pre><table width='" .
                    $GLOBALS['ancho-tabla'] . "'><tr><td><tt>";
                $r .= strip_tags($dcaso->memo) . "\n\n";
                $r .= "</tt></td></tr></table><pre>";
            } else {
                $r .= str_replace("\n", "", strip_tags($dcaso->memo))."\n\n";
            }
        }

        return $r . strip_tags($rdespuesmemo);
    }

    /**
     * Nombre de una tipificación
     *
     * @param string $c Tipificación
     *
     * @return string Hace pequeños cambios a tipificación que recibe
     */
    static function nomTipificacion($c)
    {
        $r = ucwords(a_minusculas($c));
        $r = str_replace(" De ", " de ", $r);
        $r = str_replace(" Y ", " y ", $r);
        $r = str_replace(" Contra ", " contra ", $r);
        $r = str_replace(" El ", " el ", $r);
        $r = str_replace(" La ", " la ", $r);
        $r = str_replace(" A ", " a ", $r);
        $r = str_replace(" Que ", " que ", $r);
        $r = str_replace(" En ", " en ", $r);

        return $r;
    }

    /**
     * Presenta listado de presuntos responsables, categorias y víctimas
     *
     * Hay varias formas de agrupar, por ejemplo el siguiente arreglo
     * indexado por presunto responsable y categoria con códigos de víctimas
     * como valor:
     *
     *    Array
        (
            [15] => Array
            (
                [10] => i6
                [12] => i9
                [14] => i6,i9
                [19] => i6,i9
            )

            [16] => Array
            (
                [10] => i6
                [12] => i6,i9
                [16] => i6
            )

        )

    * Agrupando más por categorias:
    * 15 => 10 => i6
    *       14,19 => i6,i9
    *       12 => i9
    * 16 => 10,16 => i6
    *       12 => i6,i9

    * Agrupando más por presuntos responsables:
    * 15,16 => 10 => i6
    *          12 => i9
    * 15 => 14,19 => i6,i9
    * 16 => 12 => i9
    *       16 => i6
    * Depende del orden de agrupamiento de cada indice.  Esta
    * función emplea el siguiente orden:
    * 1. Agrupar presuntos responsables
    * 2. Agrupar víctimas
    * 3. Agrupar categorias
    *
    * @param integer $idcaso Id. del caso
    * @param array   $campos Campos por mostrar
    * @param strgin  &$r     Colchon para dejar respuesta
    * @param boolena $repgen Para reporte general
    *
    * @return void Agrega al colchon r
    */
    static function listaPrCatVictima($idcaso,  $campos, &$r,
        $repgen= false
    ) {
        $indenta = false;
        if (isset($GLOBALS['reporte_indenta_victimas'])
            && $GLOBALS['reporte_indenta_victimas'] === true
        ) {
            $indenta = true;
        }
        $dvictima = objeto_tabla('victima');
        $dvictima->id_caso = $idcaso;
        $dvictima->orderBy('id_persona');
        $dvictima->find();
        $sep = "";
        $porVic = array();
        $lvic = array();
        while ($dvictima->fetch()) {
            $dpersona = $dvictima->getLink('id_persona');
            $dacto = objeto_tabla('acto');
            $dacto->id_persona = $dvictima->id_persona;
            $dacto->id_caso = $dvictima->id_caso;
            $dacto->orderBy('id_p_responsable, id_categoria');
            $dacto->find();
            $sep2="";
            $icat = "";
            $presp = "";
            while ($dacto->fetch()) {
                $sep2 = ",";
                $ia1 = $dacto->id_p_responsable;
                $ia2 = $dacto->id_categoria;
                $ia3 = 'i' . $dacto->id_persona;
                $porVic[$ia1][$ia2][$ia3] = 'i' . $dacto->id_persona;
            }
            $nvc = strip_tags($dpersona->nombres) . " " .
                strip_tags($dpersona->apellidos);
            $idp = DataObjects_Sector_social::id_profesional();
            if ($dvictima->id_profesion != DataObjects_Profesion::idSinInfo()
                && $dvictima->id_sector_social == $idp
            ) {
                    $dprofesion = $dvictima->getLink('id_profesion');
                    $nvc .= " - " . strip_tags($dprofesion->nombre);
            } else {
                $ids = DataObjects_Sector_social::idSinInfo();
                if ($dvictima->id_sector_social != $ids) {
                        $dsector = $dvictima->
                            getLink('id_sector_social');
                        $nvc .= " - " . strip_tags($dsector->nombre);
                }
                $ids = DataObjects_Profesion::idSinInfo();
                if ($dvictima->id_profesion != $ids) {
                        $dprofesion = $dvictima->
                            getLink('id_profesion');
                        $nvc .= " - " . strip_tags($dprofesion->nombre);
                }
            }
            $dper = $dvictima->getLink('id_persona');
            if ($repgen && $dvictima->hijos != null
                && $dvictima->hijos != null
            ) {
                $nvc .= " " . $dvictima->hijos. " hijos.";
            }
            $ids = DataObjects_Filiacion::idSinInfo();
            if ($repgen && $dvictima->id_filiacion != $ids) {
                    $nvc .= " " . $GLOBALS['etiqueta']['filiacion'] . ": ";
                    $dfiliacion = $dvictima->getLink('id_filiacion');
                    $nvc .= $dfiliacion->nombre . ". ";
            }
            if (isset($campos['m_fuentes'])
                && $repgen && trim($dvictima->anotaciones) != ''
            ) {
                $nvc .= " " . $GLOBALS['etiqueta']['anotaciones_victima']
                    . ": " . $dvictima->anotaciones;
            }
            $ids = DataObjects_Organizacion::idSinInfo();
            if ($repgen &&  isset($dvictima->id_organizacion)
                && $dvictima->id_organizacion != $ids
            ) {
                $nvc .= "  ".$GLOBALS['etiqueta']['organizacion'] .
                    ": ";
                $dorganizacion = $dvictima->getLink('id_organizacion');
                $nvc .= $dorganizacion->nombre;
            }

            $lvic['i' . $dvictima->id_persona] = $nvc;
        }

        $dvictimacol = objeto_tabla('victima_colectiva');
        $dvictimacol->id_caso = $idcaso;
        $dvictimacol->orderBy('id_grupoper');
        $dvictimacol->find();
        $sep = "";
        while ($dvictimacol->fetch()) {
            $dgrupoper = $dvictimacol->getLink('id_grupoper');
            $dactoc = objeto_tabla('actocolectivo');
            $sep2="";
            $dactoc->id_grupoper = $dvictimacol->id_grupoper;
            $dactoc->id_caso = $dvictimacol->id_caso;
            $dactoc->orderBy('id_p_responsable, id_categoria');
            $dactoc->find();
            while ($dactoc->fetch()) {
                $ia1 = $dactoc->id_p_responsable;
                $ia2 = $dactoc->id_categoria;
                $ia3 = 'c' . $dactoc->id_grupoper;
                $porVic[$ia1][$ia2][$ia3] = $ia3;
                unset($ia1);
                unset($ia2);
                unset($ia3);
            }
            $nvc = strip_tags($dgrupoper->nombre);
            if ($repgen) {
                if ($dvictimacol->personas_aprox != null
                    && $dvictimacol->personas_aprox > 0
                ) {
                    $nvc .= " (".trim($dvictimacol->personas_aprox).") ";
                }
                $nvc .= lista_relacionados(
                    'sector_social_comunidad',
                    array('id_caso' => $dvictimacol->id_caso,
                    'id_grupoper' => $dvictimacol->id_grupoper),
                    'id_sector', ', ',
                    "   " . $GLOBALS['etiqueta']['sector_social'] . ": "
                );
                $nvc .= lista_relacionados(
                    'profesion_comunidad',
                    array('id_caso' => $dvictimacol->id_caso,
                    'id_grupoper' => $dvictimacol->id_grupoper),
                    'id_profesion', ', ',
                    "   ".$GLOBALS['etiqueta']['profesion'] . ": "
                );
                $nvc .= lista_relacionados(
                    'filiacion_comunidad',
                    array('id_caso' => $dvictimacol->id_caso,
                    'id_grupoper' => $dvictimacol->id_grupoper),
                    'id_filiacion', ', ',
                    "   ".$GLOBALS['etiqueta']['filiacion'] . ": "
                );

            }
            $lvic['c' . $dactoc->id_grupoper] = $nvc;
            $dgrupoper->free();
            unset($dgrupoper);
            $dactoc->free();
            unset($dactoc);
        }

        /** Agrupamos presuntos responsables */
        $agPr = array();
        foreach ($porVic as $idpr => $pc) {
            foreach ($pc as $idc => $lv) {
                foreach ($lv as $idv => $ridv) {
                    if (!isset($agPr[$idc][$idv])) {
                        $agPr[$idc][$idv] = $idpr;
                    } else {
                        $agPr[$idc][$idv] .= "," . $idpr;
                    }
                }
            }
        }

        /* Agrupamos víctimas */
        $agV = array();
        foreach ($agPr as $idc => $lvp) {
            foreach ($lvp as $idv => $lp) {
                if (!isset($agV[$lp][$idc])) {
                    $agV[$lp][$idc] = $idv;
                } else {
                    $agV[$lp][$idc] .= "," . $idv;
                }
            }
        }

        /* Agrupamos categorias */
        $agC = array();
        foreach ($agV as $lpr => $pc) {
            $art = array();
            foreach ($pc as $idc => $lv) {
                if (!isset($art[$lv])) {
                    $art[$lv] = $idc;
                } else {
                    $art[$lv] .= "," . $idc;
                }
            }
            $agC[$lpr] = array();
            foreach ($art as $lv => $lc) {
                $agC[$lpr][$lc] = $lv;
            }
        }

        /* Categorias que no tiene víctimas */
        $dcat = objeto_tabla('categoria_p_responsable_caso');
        if (PEAR::isError($dcat)) {
            die($dcat->getMessage());
        }
        $dcat->id_caso = $idcaso;
        $dcat->find();
        $sep = "";
        $asinv = array();
        while ($dcat->fetch()) {
            $esta = 0;
            foreach ($agC as  $pr => $r1) {
                $pids = explode(",", $pr);
                foreach ($r1 as $ids => $vc) {
                    $arids = explode(",", $ids);
                    if (in_array($dcat->id_p_responsable, $pids)
                        && in_array($dcat->id_categoria, $arids)
                    ) {
                        $esta = 1;
                        break 2;
                    }
                }
            }
            if ($esta == 0) {  // No tiene victimas
                if (isset($asinv[$dcat->id_categoria])) {
                    $asinv[$dcat->id_categoria] .= ","
                        . $dcat->id_p_responsable;
                } else {
                    $asinv[$dcat->id_categoria]
                        = $dcat->id_p_responsable;
                }
            }
        }

        $aspr = array();
        foreach ($asinv as $imc => $ipr) {
            if (!isset($aspr[$ipr])) {
                $aspr[$ipr] = $imc;
            } else {
                $aspr[$ipr] .= "," . $imc;
            }
        }

        foreach ($aspr as $ipr => $imcs) {
            $agC[$ipr][$imcs] = -1; // Convención para Categorias sin víctima
        }

        foreach ($agC as $pr => $r1) {
            if (array_key_exists('m_presponsables', $campos)) {
                $pids = explode(",", $pr);
                $ra = "";  $rant = ""; $sep2="";
                //                $plistos = array();
                foreach ($pids as $idp) {
                    //                    if (!in_array($idp, $plistos)) {
                    //                        $plistos[] = $idp;
                    $dpr = objeto_tabla('presuntos_responsables');
                    $dpr->get('id', $idp);
                    if ($ra != "") {
                        $sep2=", ";
                    }
                    $ra .= $sep2 . $rant;
                    $rant = trim(strip_tags($dpr->nombre));
                    $dprc = objeto_tabla('presuntos_responsables_caso');
                    $dprc->id_caso = $idcaso;
                    $dprc->id_p_responsable = $idp;
                    $dprc->fetch(1);
                    if ($repgen && $dprc->bloque != null) {
                        $rant .= " ".$GLOBALS['etiqueta']['bloque'] . ": " .
                            trim($dprc->bloque)." ";
                    }
                    if ($repgen && $dprc->frente != null) {
                        $rant .= " ".$GLOBALS['etiqueta']['frente'] . ": " .
                            trim($dprc->frente)." ";
                    }
                    if ($repgen && $dprc->brigada != null) {
                        $rant .= " ".$GLOBALS['etiqueta']['brigada'] . ": " .
                            trim($dprc->brigada)." ";
                    }
                    if ($repgen && $dprc->batallon != null) {
                        $rant .= " ".$GLOBALS['etiqueta']['batallon'] . ": " .
                            trim($dprc->batallon)." ";
                    }
                    if ($repgen && $dprc->division != null) {
                        $rant .= " ".$GLOBALS['etiqueta']['division'] . ": " .
                            trim($dprc->division)." ";
                    }
                    if ($repgen && $dprc->otro != null) {
                        $rant .= " ".$GLOBALS['etiqueta']['otro'] . ": " .
                            trim($dprc->otro)." ";
                    }
                }
                if ($ra != "") {
                    if ($indena) {
                        $r .= "\n";
                    } else {
                        $r .= "\n\n";
                    }
                    $r .= $GLOBALS['etiqueta']['p_responsables'] .
                        ": " . $ra . " Y " . $rant;
                } elseif ($ra == "" && $rant != "") {
                    $r .= $GLOBALS['etiqueta']['p_responsable'] .
                        ": " .  $rant;
                }
                if ($repgen) {
                    $r .= ": ";
                } else {
                    $r .= "\n";
                }
            }
            foreach ($r1 as $ids => $vc) {
                $arids = explode(",", $ids);
                $cat = array();
                if ($indenta) {
                    $sepc = "&nbsp;&nbsp;";
                } else {
                    $r .= "\n";
                }
                foreach ($arids as $idc) {
                    $dpr = objeto_tabla('categoria');
                    $dpr->id = $idc;
                    $dpr->find();
                    $dpr->fetch();
                    if ($repgen) {
                        $r .= $sepc . $dpr->id_tipo_violencia . $idc;
                        $sepc = " / ";
                        continue;
                    }
                    $ds = objeto_tabla('supracategoria');
                    $ds->id_tipo_violencia
                        = $dpr->id_tipo_violencia;
                    $ds->id = $dpr->id_supracategoria;
                    $ds->find(1);
                    $dt = $dpr->getLink('id_tipo_violencia');
                    $cat[trim($dt->nombre)][trim($ds->nombre)]
                        [trim($dpr->nombre)] = trim($dpr->nombre);
                }
                foreach ($cat as $t => $rs) {
                    if ($indenta) {
                        $r .= "&nbsp;";
                    }
                    $r .= "$t\n";
                    foreach ($rs as $s => $rs2) {
                        foreach ($rs2 as $c) {
                            if ($indenta) {
                                $r .= "&nbsp;&nbsp;";
                            }
                            if ($t == 'INFRACCIONES AL DIH') {
                                $r .= ResConsulta::nomTipificacion($c) .
                                    "\n";
                            } else {
                                $r .= ResConsulta::nomTipificacion($c) .
                                    " por " .
                                    ResConsulta::nomTipificacion($s) .
                                    "\n";
                            }
                        }
                    }
                }
                if ($repgen) {
                    $r .= "\n";
                }
                $nns = 0;
                if (array_key_exists('m_victimas', $campos)) {
                    if (!$indenta) {
                        $r .= "\n";
                    }
                    $lvc = explode(',', $vc);
                    foreach ($lvc as $idv) {
                        if ($idv == -1) {
                            continue;
                        }
                        $nv = $lvic[$idv];
                        if (trim($nv)=="NN") {
                            $nns++;
                        } else {
                            if ($indenta) {
                                $r .= "&nbsp;&nbsp;&nbsp;&nbsp;";
                            }
                            $r .= trim(strip_tags($nv));
                            $r .= "\n";
                        }
                    }
                    if ($nns >= 1 && $indenta) {
                        $r .= "&nbsp;&nbsp;&nbsp;&nbsp;";
                    }
                    if ($nns == 1) {
                        $r .= "PERSONA SIN IDENTIFICAR\n";
                    } else if ($nns > 1) {
                        $r .= $nns . " PERSONAS SIN IDENTIFICAR\n";
                    }
                }
            }
        }
        if (!$indenta) {
            $r .= "\n";
        }
    }

    /**
     * Retorna un registro del reporte revista
     *
     * @param integer $idcaso  Id. del caso
     * @param handle  $db      Conexión a BD
     * @param array   $campos  Campos por mostrar
     * @param boolean $varlin  Varias líneas
     * @param boolean $tex     Generar TeX ?
     * @param boolean $numcaso Número de caso para orden por rótulo
     *
     * @return string  Registro
     */
    function reporteRevistaHtml($idcaso, $db = null, $campos = array(),
        $varlin = true, $tex = false, $numcaso = null
    ) {
        $dcaso = objeto_tabla('caso');
        if ($db == null) {
            $db = $dcaso->getDatabaseConnection();
        }
        if (PEAR::isError($dcaso)) {
            die($dcaso->getMessage());
        }
        $dcaso->id = $idcaso;
        $dcaso->find();
        $dcaso->fetch();
        $r = "";

        if (array_key_exists('caso_id', $campos)) {
            $r .= "(<a href='captura_caso.php?modo=edita&id=" .
                (int)$dcaso->id . "'>" . (int)$dcaso->id . "</a>) ";
        }
        if (isset($GLOBALS['gancho_rc_reginicial'])) {
            foreach ($GLOBALS['gancho_rc_reginicial'] as $k => $f) {
                if (is_callable($f)) {
                    $r .= call_user_func_array(
                        $f,
                        array(&$db, $campos, $idcaso, $numcaso)
                    );
                } else {
                    echo_esc("Falta $f indicada en gancho_rc_reginicial[$k]");
                }
            }
        }

        if (array_key_exists('caso_fecha', $campos)) {
            $a = explode("-", $dcaso->fecha);
            /*$ts=mktime(0, 0, 0, $a[1], $a[2], $a[0]);
            setlocale(LC_TIME, "es");
            strftime ... Se intentó pero no soportó locale es */
            if ($tex) {
                $fecha = $a[2] . "/" . $GLOBALS['mes_corto'][(int)$a[1]] .
                    "/" . substr($a[0], 2);
                $r .= "\\item[";
                $r .= $fecha;
                $r .= "]";
            } else {
                $fecha = $GLOBALS['mes'][(int)$a[1]] . " " .
                    (int)$a[2] . "/" . (int)$a[0] . " ";
                $r .= $fecha;
            }
        }

        $parche = "";
        if (array_key_exists('m_ubicacion', $campos)) {
            $idd = array(); // Identificaciones
            $idm = array();
            $idc = array();
            $ndd = array(); // Nombres
            $ndm = array();
            $ndc = array();
            $tdu = array();
            ResConsulta::extraeUbicacionCaso(
                $idcaso,
                $db, $idd, $ndd, $idm, $ndm, $idc, $ndc, $tdu
            );
            $locs = "";
            foreach ($ndd as $k => $nd) {
                $cnd = trim(strip_tags($nd));
                $cndm = trim(strip_tags($ndm[$k]));
                if ($numcaso != null) {
                    $locs .= " - " . prim_may($cnd) . " / " .
                        prim_may($cndm) . " ";
                } elseif (array_key_exists('m_tipificacion', $campos)) {
                    $locs= "\n\n" .
                        a_mayusculas($GLOBALS['etiqueta']['departamento']) .
                        ": " . $cnd . "\n";
                    $locs .= a_mayusculas($GLOBALS['etiqueta']['municipio']) .
                        ": " .  $cndm;
                } else {
                    $locs .= $cnd . " - " . $cndm . "  ";
                }
            }
            $r .= $locs . "\n";
        }

        $rantmemo = $r;


        $r = "";
        $mvicopr = array_key_exists('m_victimas', $campos)
            || array_key_exists('m_presponsables', $campos);
        if ($numcaso == null && $mvicopr) {
            ResConsulta::listaPrCatVictima($idcaso, $campos, $r);
        }

        $rdespuesmemo = $r;

        $r = "";
        if (array_key_exists('caso_memo', $campos)) {
            if ($dcaso->memo != null && trim($dcaso->memo) != '') {
                $r .= "\n";
                $memo = $dcaso->memo;
                if ($tex) {
                    $memo = formato_texto_tex($dcaso->memo);
                }
                if ($varlin) {
                    $r .= "</pre><table width='" . $GLOBALS['ancho-tabla'] .
                        "'><tr><td><tt>";
                    $r .= strip_tags($memo)."\n";
                    $r .= "</tt></td></tr></table><pre>";
                } else {
                    $r .= str_replace("\n", "", strip_tags($memo))."\n";
                }
            }
        }
        foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
            list($n, $c, $o) = $tab;
            if (($d = strrpos($c, "/"))>0) {
                $c = substr($c, $d+1);
            }
            if (is_callable(array($c, 'reporteRevistaRegistroHtml'))) {
                $r .= call_user_func_array(
                    array($c, 'reporteRevistaRegistroHtml'),
                    array(&$db, $campos, $idcaso)
                );
            } else {
                echo_esc("Falta reporteRevistaRegistroHtml en $n, $c");
            }
        }

        $peso = 0;
        $rotulo = "";
        if (isset($GLOBALS['gancho_rc_regfinal'])) {
            foreach ($GLOBALS['gancho_rc_regfinal'] as $k => $f) {
                if (is_callable($f)) {
                    list($rr, $peso, $rotulo) = call_user_func_array(
                        $f,
                        array(&$db, $campos, $idcaso, $numcaso)
                    );
                    $r .= $rr;
                } else {
                    echo_esc("Falta $f indicada en gancho_rc_regfinal[$k]");
                }
            }
        }

        //echo "OJO reg reporteRevisa con r=$r, peso=$peso y rotulo=$rotulo";
        return array($rantmemo . $r . $rdespuesmemo,
            $peso, $rotulo
        );
    }


    /**
     * Renglon de reporte CSV
     *
     * @param object  $db     Conexión
     * @param unknown $idcaso Id. caso
     * @param array   $campos Campos por mostrar
     * @param array   $conv   Para conversión de ids.
     * @param array   $sal    registro por generar
     *
     * @return void
     */
    function reporteCsvAdjnto($db, $idcaso, $campos, $conv, $sal)
    {
        $adjunto_renglon = "";
        $vrpre = '"';
        foreach ($campos as $cc => $nc) {
            $adjunto_renglon .= "";
            $sep = "";
            $vr = $vrescon = "";
            $vrpost = '"';
            // No se sacaron responsables y demás directamente en
            // la consulta por dificultad en el caso de ubicación
            // pues la información puede provenir de diversas tablas
            if ($cc == 'm_ubicacion') {
                $vr .= ResConsulta::ubicacion($db, $idcaso);
            } else if ($cc == 'm_presponsables') {
                $idp = array(); // Identificaciones
                $idp2=array();
                $ndp = array();
                ResConsulta::extraePResponsables(
                    $idcaso,
                    $db, $idp, $idp2, $ndp
                );
                $seploc = "";
                foreach ($ndp as $k => $np) {
                    $vr .= $seploc . trim($np);
                    $seploc = ". ";
                }
            } else if ($cc == 'm_fuentes') {
                $idp = array(); // Identificaciones
                $idp2=array();
                $ndp = array();
                $dff = objeto_tabla('escrito_caso');
                if (PEAR::isError($dff)) {
                    die($dff->getMessage());
                }
                $dff->id_caso = $idcaso;
                $dff->find();
                $seploc = "";
                while ($dff->fetch()) {
                    $des = $dff->getLink('id_prensa');
                    $vr .= $seploc . trim($des->nombre)." " .
                        $dff->fecha;
                    $seploc = "; ";
                }
            } else if ($cc == 'm_victimas') {
                $idp = array(); // Identificaciones
                $ndp = array();
                $edp = array();
                $indid = -1;
                $totv = ResConsulta::extraeVictimas(
                    $idcaso,
                    $db, $idp, $ndp, null, $indid, $edp
                );
                $k = 0;
                $seploc = "";
                for ($k = 0; $k < count($ndp); $k++) {
                    $q = "SELECT id_tipo_violencia, id_categoria " .
                        " FROM acto, categoria " .
                        " WHERE id_persona='". (int)$idp[$k] . "' " .
                        " AND id_caso='" . (int)$idcaso ."' " .
                        " AND acto.id_categoria=categoria.id "
                    ;
                    $result = hace_consulta($db, $q);
                    $row = array();
                    $septip = " "; $tip = "";
                    $vrescon .= $seploc . trim($ndp[$k]);
                    while ($result->fetchInto($row)) {
                        $tip .= $septip . $row[0] . $row[1];
                        $vrescon .= $septip . $row[0] . $row[1];
                        $septip = "; ";
                    }
                    $med = "";
                    if (isset($edp[$k]) && $edp[$k] > 0) {
                        $med = " (".$edp[$k] . ")";
                    }
                    $vr .= $seploc . $ndp[$k] . $med . $tip;
                    $seploc = "; ";
                }
                $indid = -1;
                $idind = -1;
                /* $totv+=ResConsulta::extraeCombatientes($idcaso,
                    $db, $idp, $ndp, null, $indid
                );
                for (; $k < count($ndp); $k++) {
                    $vr .= $seploc . $ndp[$k];
                    $vrescon .= $seploc . trim($ndp[$k]);
                    $seploc = "; ";
                } */

                $nind = -1; $totelem = 0;
                $totv+=ResConsulta::extraeColectivas(
                    $idcaso,
                    $db, $idp, $ndp, $cdp, null, $ind, $totelem
                );
                $bk = $k;
                for (; $k < count($ndp); $k++) {
                    $vr .= $seploc . $ndp[$k] . " (".$cdp[$k-$bk] . ")";
                    $vrescon .= $seploc . trim($ndp[$k])." (".$cdp[$k-$bk] . ")";
                    $seploc = "; ";
                }

                $vrpost = " ".$GLOBALS['etiqueta']['victimas'] . ":" . $totv . '"';

            } else if ($cc == 'm_tipificacion') {
                $idp = array(); // Identificaciones
                $ndp = array();
                $ncat = array();
                ResConsulta::llenaSelCategoria(
                    $db,
                    "SELECT id_tipo_violencia, id_supracategoria, " .
                    "id_categoria FROM categoria_caso " .
                    "WHERE id_caso='$idcaso' " .
                    "ORDER BY id_tipo_violencia," .
                    "id_supracategoria, id_categoria;",
                    $ncat, array(1, 2)
                );
                $vr = $seploc = "";
                foreach ($ncat as $k => $nc) {
                    $vr .= $seploc . $k . " ".$nc;
                    $seploc = ";  ";
                }
            } else if ($cc == 'caso_id') {
                //$vrpre = "<a href='captura_caso.php?modo=edita&id=" .
                $sal[$conv[$cc]] . "'>";
                $vr = $sal[$conv[$cc]];
                //$vrpost = "</a>";
            } else if (isset($conv[$cc]) && isset($sal[$conv[$cc]])) {
                $vr = trim($sal[$conv[$cc]]);
            } else {
                $vr = '';
            }
            $vr = str_replace('"', '""', $vr);
            $escon[$cc] = $vrescon == '' ? $vr : $vrescon;
            $adjunto_renglon .= $vrpre . $vr . $vrpost;
            $vrpre = ', "';
        }
        echo $adjunto_renglon;
    }


}



?>
