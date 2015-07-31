<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
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
 * @link      http://sivel.sf.net
 */

/**
 * Resultados de una consulta
 */

require_once 'HTML/QuickForm/Action.php';
require_once 'aut.php';
require_once 'misc.php';
require_once $_SESSION['dirsitio'] . '/conf.php';
require_once 'DataObjects/Caso_ffrecuente.php';
require_once 'DataObjects/Ffrecuente.php';
require_once 'DataObjects/Caso.php';
require_once 'DataObjects/Caso_region.php';
require_once 'DataObjects/Caso_frontera.php';
require_once 'DataObjects/Caso_fotra.php';
require_once 'DataObjects/Categoria_caso.php';
require_once 'DataObjects/Supracategoria.php';
require_once 'DataObjects/Categoria.php';
require_once 'DataObjects/Presponsable.php';
require_once 'DataObjects/Caso_presponsable.php';
require_once 'DataObjects/Caso_categoria_presponsable.php';
require_once 'DataObjects/Victima.php';
require_once 'DataObjects/Victimacolectiva.php';
require_once 'DataObjects/Sectorsocial.php';
require_once 'DataObjects/Comunidad_sectorsocial.php';
require_once 'DataObjects/Comunidad_profesion.php';
require_once 'DataObjects/Caso_usuario.php';
require_once 'DataObjects/Resagresion.php';
require_once 'DataObjects/Filiacion.php';
require_once 'DataObjects/Sectorsocial.php';
require_once 'DataObjects/Profesion.php';
require_once 'DataObjects/Presponsable.php';
require_once 'DataObjects/Caso_etiqueta.php';
require_once 'PagTipoViolencia.php';

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
     * @var    array
     */
    var $resultado;

    /**
     * Conv
     * @var    array
     */
    var $conv;

    /**
     * Indica si primero se presenta el nombre, si es falso primero apellido
     * @var     bool
     */
    var $primnom;

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
     * @var string
     */
    var $ordenar;

    /**
     * Traducción TeX
     * @var    bool
     */
    var $tex;

    /**
     * ordCasos
     * @var    array
     */
    var $ordCasos;


    /**
     * Vector con opciones para presentar resultado
     * @var    array
     */
    var $busca_pr; 

    /**
     * Constructora
     *
     * @param array        &$campos      Campos por mostrar id => nombre
     * @param handle       &$db          Conexión a base de datos
     * @param array|object &$resultado   Resultado de consulta tiene caso.id
     * @param array        &$conv        Convertir id de campos a base de datos
     * @param string       $mostrar      Forma de presentacion 
     * @param array        $detallesform Partir  memo en varias lineas
     * @param array        $ordCasos     Orden de los casos por mostrar
     * @param array        $busca_pr     Opciones de mostrar info.
     * @param string       $ordenar      Ordenar
     * @param boolean      $primnom      Nombre y apellido
     *
     * @return void
     */
    function ResConsulta(&$campos, &$db, &$resultado, &$conv, $mostrar,
        $detallesform = array(), $ordCasos = array(), $busca_pr = array(),
        $ordenar = '', $primnom = true
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
        $this->primnom = $primnom;
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
            " tsitio.nombre " .
            " FROM ubicacion, tsitio, departamento, municipio, clase " .
            " WHERE ubicacion.id_caso='$idcaso' " .
            " AND ubicacion.id_tsitio=tsitio.id " .
            " AND ubicacion.id_departamento=departamento.id " .
            " AND ubicacion.id_municipio=municipio.id " .
            " AND ubicacion.id_clase=clase.id " .
            " AND municipio.id_departamento=departamento.id " .
            " AND clase.id_departamento=departamento.id " .
            " AND clase.id_municipio=municipio.id " .
            " ORDER BY departamento.nombre, municipio.nombre, clase.nombre;";
        $result = hace_consulta($db, $q);
        $row = array();
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
            " tsitio.nombre " .
            " FROM ubicacion, tsitio, departamento, municipio " .
            " WHERE ubicacion.id_caso='$idcaso' " .
            " AND ubicacion.id_tsitio=tsitio.id " .
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
            " tsitio.nombre " .
            " FROM ubicacion, tsitio, departamento " .
            " WHERE ubicacion.id_caso='$idcaso' " .
            " AND ubicacion.id_tsitio=tsitio.id " .
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
     * Retorna cadena con ubicaciones como 2 columnas en HTML
     *
     * @param handel  &$db    Conexión a BD
     * @param integer $idcaso Id. del caso
     *
     * @return string ubicaciones
     */
    function ubicacion_separada_html(&$db, $idcaso)
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
        $cdep = "";
        $cmun = "";
        $sep = "";
        foreach ($ndd as $k => $nd) {
            $cdep = $sep . htmlentities(trim($nd), ENT_COMPAT, 'UTF-8');
            $cmun = $sep . htmlentities(trim($ndm[$k]), ENT_COMPAT, 'UTF-8');
            $sep = "<br/>";
        }
        $vr = "$cmun</td><td valign='top'>$cdep";
        return $vr;
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
     * @param integer $indid     Indid
     * @param object  &$edp       edp
     * @param boolean $primnom    Nombre y apellido
     * @param boolean $septd      Emplear como separador </td><td> y quitar tags
     *
     * @return integer Total de víctimas
     */
    function extraeVictimas($idcaso, &$db, &$idp, &$ndp,
        $id_persona, &$indid, &$edp, $primnom = true, $septd = false
    ) {
        $q = "SELECT  id_persona, nombres, apellidos, anionac " .
            " FROM victima, persona " .
            " WHERE id_caso='$idcaso' AND victima.id_persona=persona.id " .
            " ORDER BY id;";
        $result = hace_consulta($db, $q);
        $row = array();
        $tot = 0;

        if ($septd) {
            $sepm ="</td><td valign='top'>";
        } else {
            $sepm =" ";
        }
        while ($result->fetchInto($row)) {
            $idp[] = $row[0];
            if ($septd) {
                $nom = strip_tags($row[1]);
                $ap = strip_tags($row[2]);
            } else {
                $nom = $row[1];
                $ap = $row[2];
            }
            if ($primnom) {
                $ndp[] = $nom . $sepm . $ap;
            } else {
                $ndp[] = $ap . $sepm . $nom;
            }
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
     * @param integer $indid         Indid
     *
     * @return integer Total de víctimas

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
        $q = "SELECT  id_presponsable, caso_presponsable.id, " .
            " presponsable.nombre " .
            " FROM caso_presponsable, presponsable " .
            " WHERE id_caso='$idcaso' " .
            " AND id_presponsable=presponsable.id " .
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
     * @param int    $totelem    Total de elementos agregados a cada arreglo
     *
     * @return integer Suma de victimas.
     **/
    function extraeColectivas($idcaso, &$db, &$idp, &$ndp, &$cdp,
        $id_grupoper, &$indid, &$totelem
    ) {
        $q = "SELECT id_grupoper, nombre, personasaprox " .
            " FROM victimacolectiva, grupoper " .
            " WHERE victimacolectiva.id_grupoper=grupoper.id " .
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
     * @param string $pMuestra   Muestra
     *
     * @return void
     */
    function actosHtml(&$db, $tablas, $donde, $pMuestra)
    {
        $etablas = array();
        if (is_array($tablas)) {
            $etablas = $tablas;
        } else if ($tablas != '') {
            $etablas = explode(',', $tablas);
        }
        $etablas = array_merge(
            $etablas, array('caso',
            'victima', 'departamento', 'municipio',
            'persona', 'presponsable', 'acto',
            'sectorsocial', 'organizacion', 'rangoedad',
            'ubicacion')
        );
        $etablas = array_map("trim", $etablas);
        $etablas = implode(", ", array_unique($etablas));
        $q = " SELECT caso.id, persona.id, " .
            " persona.nombres, persona.apellidos, caso.fecha, " .
            " acto.id_categoria, presponsable.nombre, " .
            " sectorsocial.nombre, organizacion.nombre, " .
            " persona.sexo, rangoedad.rango, " .
            " ubicacion.id_departamento,  ubicacion.id_municipio, " .
            " departamento.nombre, municipio.nombre " .
            " FROM  $etablas WHERE " .
            " presponsable.id=acto.id_presponsable " .
            " AND acto.id_persona=persona.id " .
            " AND rangoedad.id=victima.id_rangoedad " .
            " AND persona.id=victima.id_persona " .
            " AND caso.id=victima.id_caso " .
            " AND caso.id=acto.id_caso " .
            " AND sectorsocial.id=victima.id_sectorsocial" .
            " AND organizacion.id=victima.id_organizacion" .
            " AND ubicacion.id_caso=caso.id " .
            " AND departamento.id=ubicacion.id_departamento " .
            " AND municipio.id=ubicacion.id_municipio" .
            " AND municipio.id_departamento=ubicacion.id_departamento" .
            " AND $donde ORDER BY caso.fecha" ;
        //echo "q es $q<br>";
        //die("x");
        $result = hace_consulta($db, $q);

        $ac = array(
            _("Fecha"), _("Caso"), _("Nombres Víctima"),
             _("Apellidos Víctima"),
            _("Sector Social"), _("Organización Social"),
            _("Sexo"), _("Rango de Edad"),
            _("Categoria"), _("P. Responsable"),
            _("Departamento"), _("Municipio"),
            _("Nom. Departamento"), _("Nom. Municipio"),
        );

        if ($pMuestra == "csv") {
            header("Content-type: text/csv");
            $st = ""; $cpm_ne = "";
            foreach ($ac as $c) {
                $cpm_ne .= $st . "\"$c\"";
                $st = ", ";
            }
            echo $cpm_ne . ', ""\n'; 
        } elseif ($pMuestra == 'latex') {
            //header("Content-type: application/x-latex");
            echo "<pre>";
            $st = ""; $cpm_ne = "";
            foreach ($ac as $c) {
                $cpm_ne .= $st . "\\textbf{$c}";
                $st = " & ";
            }
            echo $cpm_ne . ' \\\\ \n' . '\hline\n'; 
        } else { // tabla o consolidado

            encabezado_envia("Actos");
            echo "<table border='1'>\n";
            $st = ""; $html_cpm = "<tr>";
            foreach ($ac as $c) {
                $html_cpm .= $st . "<th>" . 
                    htmlentities($c, ENT_COMPAT, 'UTF-8') . "</th>";
            }
            echo $html_cpm . '</tr>'; 
        }

        $row = array();
        while ($result->fetchInto($row)) {
            //print_r($row);
            $fecha = $row[4];
            $cat = $row[5];
            $nom = $row[2];
            $ap = $row[3];
            $ss = $row[7];
            $os = $row[8];
            //$idvic = $row[1];
            $idcaso = $row[0];
            $presp = $row[6];
            $sexo = $row[9];
            $rangoedad = $row[10];
            $dep = $row[11];
            $mun = $row[12];
            $ndep = $row[13];
            $nmun = $row[14];

            $html_il = "";
            if ($pMuestra == "tabla" || $pMuestra == 'actos') {
                $html_il = "<tr><td>" .
                    htmlentities($fecha, ENT_COMPAT, 'UTF-8') . "</td>" .
                    "<td>" . trim(htmlentities($idcaso, ENT_COMPAT, 'UTF-8')) .
                    "</td><td>" . trim(htmlentities($nom, ENT_COMPAT, 'UTF-8')).
                    "</td><td>" . trim(htmlentities($ap, ENT_COMPAT, 'UTF-8')).
                    "</td><td>" . trim(htmlentities($ss, ENT_COMPAT, 'UTF-8')) .
                    "</td><td>" . trim(htmlentities($os, ENT_COMPAT, 'UTF-8')) .
                    "</td><td>" . htmlentities($sexo, ENT_COMPAT, 'UTF-8') .
                    "</td><td>" . htmlentities($rangoedad, ENT_COMPAT, 'UTF-8') .
                    "</td><td>" . htmlentities($cat, ENT_COMPAT, 'UTF-8') .
                    "</td><td>" . htmlentities($presp, ENT_COMPAT, 'UTF-8') .
                    "</td><td>" . htmlentities($dep, ENT_COMPAT, 'UTF-8') .
                    "</td><td>" . htmlentities($mun, ENT_COMPAT, 'UTF-8') .
                    "</td><td>" . htmlentities($ndep, ENT_COMPAT, 'UTF-8') .
                    "</td><td>" . htmlentities($nmun, ENT_COMPAT, 'UTF-8') .
                    "</td>";
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

            if ($pMuestra == "tabla" || $pMuestra == 'actos') {
                echo "</tr>\n";
            } elseif ($pMuestra == 'csv') {
                echo " \n";
            } elseif ($pMuestra == 'latex') {
                echo " \\\\\n \hline\n";
            }

        }
        if ($pMuestra == "tabla"  || $pMuestra == 'actos') {
            echo "</table>";
            pie_envia();
        } elseif ($pMuestra == 'csv') {
        } elseif ($pMuestra == 'latex') {
        }
    }


    /** Llena un select o un arreglo con ids y nombres de categorias de
     * violencia  extraidos de la tabla con una consulta que recibe
     *  (retorna primero tviolencia, id_supracategoria e id_categoria)
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
                .  " FROM tviolencia "
                .  " WHERE id='" . $row[0] . "';";
            $tvio = htmlentities_array($db->getAssoc($qtvio));
            $scat = htmlentities_array(
                $db->getAssoc(
                    "SELECT id, nombre " .
                    " FROM supracategoria " .
                    " WHERE id_tviolencia='" . $row[0] . "' " .
                    " AND id='" . $row[1] . "';"
                )
            );
            $cat = htmlentities_array(
                $db->getAssoc(
                    "SELECT id, nombre " .
                    "FROM categoria WHERE " .
                    "id_tviolencia='".$row[0] . "' AND " .
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
        $html_enlace1=null
    ) {
        //echo "OJO aHtml";
        if ($html_enlace1 === null || strlen($html_enlace1) == 0) {
            $html_enlace1 = '<a href = "consulta_web.php">'
                . _('Consulta Web') . '</a>, ';
        }
        $html_erelato =  $GLOBALS['enc_relato']
            . "<relatos>";

        $renglon = "";
        $j = 0;
        $tot = 0;
        foreach ($this->resultado as $resultado) {
            $tot += $resultado->numRows();
            //print_r($resultado);
            //echo "tot=$tot";
            //die("z");
        }
        $esadmin = false;
        if (isset($_SESSION['id_usuario'])) {
            include $_SESSION['dirsitio'] . "/conf.php";
            global $dsn;
            $aut_usuario = "";
            autentica_usuario($dsn, $aut_usuario, 0);
            if (in_array(42, $_SESSION['opciones'])) {
                $esadmin = true;
            }
        }

        if (!$esadmin && isset($GLOBALS['consulta_web_max'])
            && $GLOBALS['consulta_web_max'] > 0
            && $tot > $GLOBALS['consulta_web_max']
        ) {
            echo _("Consulta de") . " " . (int)$tot . " " . _("casos") .".<br>";
            die(
                _("Por favor refine su consulta para que sean menos de")
                . " " .  $GLOBALS['consulta_web_max']
            );
        }
        switch ($this->mostrar) {
        case 'general':
        case 'revista':
            encabezado_envia(
                _('Consulta Web'), $GLOBALS['cabezote_consulta_web']
            );
            echo "<html><head><title>" . _("Reporte Revista")
                . "</title></head>";
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
            $sep = "";
            foreach ($this->campos as $cc => $nc) {
                $nc = str_replace('"', '""', $nc);
                $renglon .= $sep . '"' . $nc . '"';
                $sep = ', ';
            }
            echo "$renglon\n";
            break;
        case 'tabla':
            encabezado_envia(
                _('Consulta Web'),
                $GLOBALS['cabezote_consulta_web']
            );
            echo "<html><head><title>Tabla</title></head>";
            echo "<body>";
            echo _("Consulta de") . " " . (int)$tot . " " . _("casos") . ". ";
            echo "<p><table border=1 cellspacing=0 cellpadding=5>";
            $html_renglon = "<tr>";
            $rtexto = "";
            foreach ($this->campos as $cc => $nc) {
                if ($cc == 'm_ubicacion'
                    && isset($GLOBALS['reptabla_separa_ubicacion'])
                    && $GLOBALS['reptabla_separa_ubicacion']
                ) {
                    $html_renglon = "$html_renglon<th colspan='2' valign='top'>"
                        . "$nc</th>";
                } elseif ($cc == 'm_victimas'
                    && isset($GLOBALS['reptabla_separa_nomap'])
                    && $GLOBALS['reptabla_separa_nomap']
                ) {
                    $html_renglon = "$html_renglon<th colspan='2' valign='top'>"
                        . "$nc</th>";
                } else {
                    $html_renglon = "$html_renglon<th valign='top'>$nc</th>";
                }
                $rtexto = "$rtexto\n$nc";
                foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
                    list($n, $c, ) = $tab;
                    if (($d = strrpos($c, "/"))>0) {
                        $c = substr($c, $d+1);
                    }
                    if (is_callable(array($c, 'resConsultaInicioTabla'))) {
                        call_user_func_array(
                            array($c, 'resConsultaInicioTabla'),
                            array($cc)
                        );
                    } else {
                        echo_esc(
                            _("Falta") ." resConsultaInicioTabla "
                            . _("en") . " $n, $c"
                        );
                    }
                }
            }
            echo "$html_renglon";
            if ($retroalim) {
                echo "<th valign=top>" . _("Retroalimentación") . "</th>";
            }
            echo "</tr>\n";
            break;
        case 'relatoslocal':
            if (!isset($GLOBALS['DIR_RELATOS'])
                || $GLOBALS['DIR_RELATOS'] == ''
            ) {
                global $dirserv, $dirsitio;
                $na = "$dirserv/$dirsitio/conf.php";
                echo _("Falta definir directorio destino en variable") ." " .
                    "\$GLOBALS['DIR_RELATOS'] " . _("del archivo") ." " .
                    htmlentities($na, ENT_COMPAT, 'UTF-8') 
                    . "<br>";
                die("");
            } else if (!is_writable($GLOBALS['DIR_RELATOS'])) {
                echo _("No puede escribirse en directorio") . " " .
                    $GLOBALS['DIR_RELATOS'] .
                    "<br>" . _("Ajuste o cambie permisos temporalmente con")
                    . ":<br><tt>sudo chmod a+w ${GLOBALS['dirchroot']}" .
                    "${GLOBALS['DIR_RELATOS']}</tt><br>";
            } else {
                echo "<font color='red'>" . _("El directorio") . "  "
                    .  $GLOBALS['DIR_RELATOS']
                    .  " " . _("puede ser escrito") . "</font><br>"
                    .  _("Tras generar, retire permiso de escritura con")
                    . " :<br>" .
                    "<tt>sudo chmod a-w ${GLOBALS['dirchroot']}" .
                    "${GLOBALS['DIR_RELATOS']}</tt><br>";
            }
            echo _("Generando relatos") . ":<br>";
            break;
        default:
            $rtexto = "";
            foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
                list($n, $c, ) = $tab;
                if (($d = strrpos($c, "/"))>0) {
                    $c = substr($c, $d+1);
                }
                if (is_callable(array($c, 'resConsultaInicio'))) {
                    call_user_func_array(
                        array($c, 'resConsultaInicio'),
                        array($this->mostrar, &$renglon, &$rtexto, $tot)
                    );
                } else {
                    echo_esc(
                        _("Falta") ." resConsultaInicio " . _("en") . " $n, $c"
                    );
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
                            echo_esc(
                                _("Falta") . " $f " . _("de")
                                . " gancho_rc_inicio[$k]"
                            );
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
                            echo "<font size=+2>" .
                                htmlentities($rotulo, ENT_COMPAT, 'UTF-8') .
                                "</font>\n";
                        }
                        echo $html_r . "\n";
                        if ($peso >= $ultpeso) {
                            $ultpeso = $peso;
                        } else {
                            echo "<br/><font color='red'>Peso " . (int)$peso
                               . " " . _("de caso") ." " . (int)$idcaso
                               ." " . _("fuera de secuencia") . "</font><br/>";
                        }
                    } else {
                        echo $html_r . "\n";
                    }
                    break;
                case 'relato':
                    $html_relato = $this->reporteRelato(
                        $idcaso, null,
                        $this->campos
                    );
                    echo $html_relato;
                    break;
                case 'csv':
                    $r_ne = $this->reporteCsvAdjunto(
                        $this->db, $idcaso,
                        $this->campos, $this->conv, $sal
                    );
                    echo $r_ne;
                    echo "\n";
                    break;
                case 'tabla':
                    $this->filaTabla(
                        $this->db, $idcaso, $this->campos,
                        $this->conv, $sal, $retroalim, $this->primnom
                    );
                    break;
                case 'relatoslocal':
                    echo_esc(memory_get_usage());
                    echo "<br>";
                    $nar = $GLOBALS['DIR_RELATOS'] .
                        $GLOBALS['PREF_RELATOS'] . $idcaso . '.xrlat';
                    echo "&nbsp;&nbsp;" .
                        htmlentities($nar, ENT_COMPAT, 'UTF-8');
                    if (!file_exists($nar)) {
                        $r = $html_erelato;
                        $r .= ResConsulta::reporteRelato(
                            $idcaso, $this->db,
                            $this->campos
                        );
                        $r .= "</relatos>\n";
                        if (!file_put_contents($nar, $r)) {
                            echo " ... " . _("Falló") . "<br>";
                        } else {
                            echo "<br>\n";
                        }
                    }
                    unset($r);
                    unset($nar);
                    break;

                default:
                    foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
                        list($n, $c, ) = $tab;
                        if (($d = strrpos($c, "/"))>0) {
                            $c = substr($c, $d+1);
                        }
                        if (is_callable(array($c, 'resConsultaRegistro'))) {
                            call_user_func_array(
                                array($c, 'resConsultaRegistro'),
                                array(&$this->db, $this->mostrar,
                                $idcaso, $this->campos,
                                $this->conv, &$sal, &$retroalim)
                            );
                        } else {
                            echo_esc(
                                _("Falta") . " resConsultaRegistro "
                                . _("en") . " $n, $c"
                            );
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
                                        _("Falta") . " $f " . _("de")
                                        . " resConsultaRegistro[$k]"
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
            if (isset($GLOBALS['reporte_tabla_fila_totales'])
                && $GLOBALS['reporte_tabla_fila_totales'] == true
            ) {
                echo "<tr>";
                $html_renglon = "";
                foreach ($this->campos as $cc => $nc) {
                    $html_renglon .= "<td>";
                    foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
                        list($n, $c, ) = $tab;
                        if (($d = strrpos($c, "/"))>0) {
                            $c = substr($c, $d+1);
                        }
                        if (is_callable(array($c, 'resConsultaFinaltablaHtml'))) {
                            $html_renglon .= call_user_func_array(
                                array($c, 'resConsultaFinaltablaHtml'),
                                array($cc)
                            );
                        } else {
                            echo_esc(
                                _("Falta") . " resConsultaFinaltablaHtml "
                                . _("en") . " $n, $c"
                            );
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
                list($n, $c, ) = $tab;
                if (($d = strrpos($c, "/"))>0) {
                    $c = substr($c, $d+1);
                }
                if (is_callable(array($c, 'resConsultaFinal'))) {
                    call_user_func_array(
                        array($c, 'resConsultaFinal'),
                        array($this->mostrar)
                    );
                } else {
                    echo_esc(
                        _("Falta") . " resConsultaFinal " . _("en") . " $n, $c"
                    );
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
                            echo_esc(
                                _("Falta") .  " $f " . _("de")
                                . " resConsultaFinal[$k]"
                            );
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
                '<b>' . _('Men&uacute; Principal') . '</b></a></div>';
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
     * @param boolean $primnom   Nombre y apellido
     *
     * @return string Fila en HTML
     */
    static function filaTabla($db, $idcaso, $campos, $conv, $sal,
        $retroalim = true, $primnom = true
    ) {
        //echo "OJO filaTabla(db, $idcaso, campos, conv, sal, retroalim);<br>";
        $col = "#FFFFFF";
        $dec = objeto_tabla('caso_etiqueta');
        if (!PEAR::isError($dec)) {
            $dec->id_caso = $idcaso;
            $dec->find();
            while ($dec->fetch()) {
                $det = $dec->getLink('id_etiqueta');
                if (strtolower(substr($det->observaciones, 0, 7)) == 'color #'
                ) {
                    $col = substr($det->observaciones, 6, 7);
                }
            }
        }
        $html_renglon = "<tr>";
        $escon = array();
        foreach ($campos as $cc => $nc) {
            $html_renglon .= "<td valign='top'";
            if ($cc == "caso_id") {
                $html_renglon .= "style='background-color: " 
                    . htmlentities($col, ENT_COMPAT, 'UTF-8') .  "'";
            }
            $html_renglon .= ">";
            $vr_html = $vrescon = $vrpre = $vrpost = "";
            // No se sacaron responsables y demás directamente en
            // la consulta por dificultad en el caso de ubicación
            // pues la información puede provenir de diversas tablas
            if ($cc == 'm_ubicacion') {
                if ($GLOBALS['reptabla_separa_ubicacion']) {
                    $vr_html .= ResConsulta::ubicacion_separada_html(
                        $db, $idcaso
                    );
                } else {
                    $vr_html .= strip_tags(
                        ResConsulta::ubicacion($db, $idcaso)
                    );
                }

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
                    $vr_html .= $seploc . strip_tags(trim($np));
                    $seploc = ", ";
                }
            } else if ($cc == 'm_fuentes') {
                $ndp = array();
                $dff = objeto_tabla('caso_ffrecuente');
                if (PEAR::isError($dff)) {
                    die($dff->getMessage());
                }
                $dff->id_caso = $idcaso;
                $dff->find();
                $seploc = "";
                while ($dff->fetch()) {
                    $des = $dff->getLink('id_ffrecuente');
                    $vr_html .= $seploc . strip_tags(trim($des->nombre))
                        . " " . $dff->fecha;
                    $seploc = ", ";
                }
            } else if ($cc == 'm_victimas') {
                $idp = array(); // Identificaciones
                $ndp_html = array();
                $edp = array();
                $indid = -1;
                $totv = ResConsulta::extraeVictimas(
                    $idcaso,
                    $db, $idp, $ndp_html, null, $indid, $edp, $primnom,
                    isset($GLOBALS['reptabla_separa_nomap'])
                    && $GLOBALS['reptabla_separa_nomap']
                );
                $seploc = "";
                for ($k = 0; $k < count($ndp_html); $k++) {
                    $q = "SELECT id_tviolencia, id_supracategoria, " .
                    "id_categoria " .
                    " FROM acto, categoria " .
                    " WHERE id_persona='". (int)$idp[$k] . "' " .
                    " AND id_caso='". (int)$idcaso . "' " .
                    " AND acto.id_categoria=categoria.id"
                    ;
                    $result = hace_consulta($db, $q);
                    $row = array();
                    $septip = " "; $tip = "";
                    if (!isset($GLOBALS['reptabla_noagresion'])
                        || !$GLOBALS['reptabla_noagresion']
                    ) {
                        $vrescon .= $seploc . trim($ndp_html[$k]);
                        while ($result->fetchInto($row)) {
                            $tip .= $septip
                                . "<a href='consulta_web_cat.php?t = "
                                .  $row[0] . "&s = ".$row[1] . "&c = "
                                . $row[2] . "'>" . $row[0]
                                . $row[2] . "</a>";
                            $vrescon .= $septip . $row[0] . $row[2];
                            $septip = ", ";
                        }
                    }
                    $med = "";
                    if (!isset($GLOBALS['reptabla_nonacimiento'])
                        || !$GLOBALS['reptabla_nonacimiento']
                    ) {
                        if (isset($edp[$k]) && $edp[$k] > 0) {
                            $med = " (".$edp[$k] . ")";
                        }
                    }
                    $vr_html .= $seploc . $ndp_html[$k] . $med . $tip;
                    $seploc = ", ";
                }
                $totelem = 0;
                if (!isset($GLOBALS['actoscolectivos'])
                    || $GLOBALS['actoscolectivos'] == true
                ) {
                    $totv+=ResConsulta::extraeColectivas(
                        $idcaso,
                        $db, $idp, $ndp, $cdp, null, $ind, $totelem
                    );
                    $bk = $k;
                    for (; $k < count($ndp); $k++) {
                        $q = "SELECT id_tviolencia, id_supracategoria, " .
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
                            $tip .= $septip
                                . "<a href='consulta_web_cat.php?t = "
                                .  $row[0] . "&s = ".$row[1] . "&c = ".$row[2]
                                . "'>" .  $row[0] . $row[2] . "</a>";
                            $vrescon .= $septip . $row[0] . $row[2];
                            $septip = ", ";
                        }
                        $vr_html .= $seploc . strip_tags($ndp[$k]) . $tip;
                        if ((int)$cdp[$k - $bk] > 0) {
                            $vr_html .= " (". strip_tags($cdp[$k - $bk]) . ")";
                            $vrescon .= " (".(int)$cdp[$k - $bk] . ")";
                        }
                        $seploc = ", ";
                    }

                    $vrpost = " | " . _("Víctimas") . ":".$totv;
                }
            } else if ($cc == 'm_tipificacion') {
                $ndp = array();
                $ncat = array();
                ResConsulta::llenaSelCategoria(
                    $db,
                    "(SELECT id_tviolencia, id_supracategoria, " .
                    "id_categoria FROM caso_categoria_presponsable " .
                    "WHERE id_caso='$idcaso') UNION " .
                    "(SELECT id_tviolencia, id_supracategoria, " .
                    "id_categoria FROM categoria, acto " .
                    "WHERE id_caso='$idcaso' AND " .
                    "categoria.id=acto.id_categoria) UNION " .
                    "(SELECT id_tviolencia, id_supracategoria, " .
                    "id_categoria FROM categoria, actocolectivo " .
                    "WHERE id_caso='$idcaso' AND " .
                    "categoria.id=actocolectivo.id_categoria) " .
                    "ORDER BY id_tviolencia," .
                    "id_supracategoria, id_categoria;", $ncat, array(1, 2)
                );
                $vr_html = $seploc = "";
                foreach ($ncat as $k => $nc) {
                    if (isset($GLOBALS['reptabla_tipificacion_breve'])
                        && $GLOBALS['reptabla_tipificacion_breve'] === true
                    ) {
                        $nc = substr($nc, strpos($nc, ":") + 1);
                        $vr_html .= $seploc . strip_tags($nc);
                    } else {
                        $vr_html .= $seploc . strip_tags($k)
                            . " ".strip_tags($nc);
                    }
                    $seploc = ",  ";
                }
            } else if ($cc == 'caso_id') {
                $vrpre = "<a href='captura_caso.php?modo=edita&id=" .
                $sal[$conv[$cc]] . "'>";
                $vr_html = strip_tags($sal[$conv[$cc]]);
                $vrpost = "</a>";
                //echo "OJO 1 cc=$cc,  vr = $vr <br>";
            } else if (isset($conv[$cc]) && isset($sal[$conv[$cc]])) {
                $vr_html = strip_tags(trim($sal[$conv[$cc]]));
            } else {
                $vr_html = '';
                //echo "<hr>"; var_dump($GLOBALS['ficha_tabuladores']);
                foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
                    list($n, $c, ) = $tab;
                    if (($d = strrpos($c, "/"))>0) {
                        $c = substr($c, $d+1);
                    }
                    //echo $c;
                    if (is_callable(array($c, 'resConsultaFilaTabla'))) {
                        $vr_html .= call_user_func_array(
                            array($c, 'resConsultaFilaTabla'),
                            array(&$db, $cc, $idcaso)
                        );
                    } else {
                        echo_esc(
                            _("Falta") . " resConsultaFilaTabla " . _("en")
                            . " $n, $c"
                        );
                    }
                }
            }
            //echo "OJO 1 cc=$cc,  vr = $vr <br>";
            $escon[$cc] = $vrescon == '' ? $vr_html : $vrescon;
            $html_renglon .= $vrpre . $vr_html . $vrpost . "</td>";
        }
        echo "$html_renglon\n";
        if ($retroalim) {
            echo "<td valign=top><form method=\"POST\" " .
                "action=\"consulta_web_correo.php\">\n";
            foreach ($escon as $l => $v) {
                echo "<input type=\"hidden\" name=\""
                    . htmlentities($l, ENT_COMPAT, 'UTF-8') . "\" value=\""
                    . htmlentities($v, ENT_COMPAT, 'UTF-8') . "\">\n";
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
     *
     * @return string Reporte
     */
    static function reporteRelato($idcaso, $db = null, $campos = array()) {
        $arotros = array(); // Para poner observaciones al final
        $dcaso = objeto_tabla('caso');
        $dcaso->get('id', $idcaso);
        $arcaso = array();
        $locdb = false;
        if ($db == null) {
            $locdb = true;
            $db = $dcaso->getDatabaseConnection();
        }
        $nom = $db->getOne(
            "SELECT nombre FROM etiqueta, caso_etiqueta "
            . " WHERE caso_etiqueta.id_caso='$idcaso' " .
            " AND caso_etiqueta.id_etiqueta=etiqueta.id " .
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
            'hora', 'duracion', 'memo', 'grconfiabilidad',
            'gresclarecimiento', 'grimpunidad', 'grinformacion',
            'bienes', 'id_intervalo'
        );
        $dcaso->aRelato(
            $arcaso,
            array('forma_compartir' => $formacomp,
            'memo' => 'hechos',
            'grconfiabilidad' => 'observaciones{tipo->grconfiabilidad}',
            'gresclarecimiento' => 'observaciones{tipo->gresclarecimiento}',
            'grimpunidad' => 'observaciones{tipo->grimpunidad}',
            'grinformacion' => 'observaciones{tipo->grinformacion}',
            'id_intervalo' => 'observaciones{tipo->id_intervalo}',
            'bienes' => 'observaciones{tipo->bienes}'
            )
        );

        //print_r($arcaso); die("x");
        $r = "<relato>\n";
        a_elementos_xml(
            $r, 2,
            array('organizacion_responsable' =>
            $GLOBALS['organizacion_responsable'],
            'derechos' =>
            $GLOBALS['derechos']
        )
        );
        a_elementos_xml(
            $r, 2,
            array('id_relato' => $dcaso->id,
            'forma_compartir' => $formacomp
            )
        );
        //echo "OJO Antes de agregar titulo: " . memory_get_usage() ."<br>";
        a_elementos_xml($r, 2, array('titulo' => $arcaso['titulo']));
        if (isset($campos['caso_memo'])) {
            a_elementos_xml(
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
                $drelp = objeto_tabla('persona_trelacion');
                $drelp->persona1 = $dvictima->id_persona;
                $drelp->orderBy('persona2');
                $drelp->find();
                $sep = $relp = "";
                while ($drelp->fetch()) {
                    $op = $drelp->getLink('persona2');
                    $tr = $drelp->getLink('id_trelacion');
                    $relp .= $sep . $tr->nombre . " " . $op->nombres .
                        ", " .  $op->apellidos ;
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
            $dvictimacol = objeto_tabla('victimacolectiva');
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
                        'organizacionarmada' =>
                        'REL;observaciones{tipo->organizacion_armada}',
                        'personasaprox'
                        => 'observaciones{tipo->personasaprox}')
                );

                $r .= "  <grupo>\n";

                $atradrel = DataObjects_Victimacolectiva::tradRelato();
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
            $dprespcaso = objeto_tabla('caso_presponsable');
            $dprespcaso->id_caso = $idcaso;
            $dprespcaso->orderBy('id_presponsable');
            $dprespcaso->find();
            while ($dprespcaso->fetch()) {
                $argrupo = array();
                $dprespcaso->aRelato($argrupo);
                $r .= "  <grupo>\n";
                $argrupo['id_presponsable'] += $max_id_grupo;
                a_elementos_xml(
                    $r, 4, subarreglo(
                        $argrupo,
                        array('id_presponsable', 'nombre',
                        'sigla', 'subgrupo_de')
                    ),
                    array('id_presponsable' => 'id_grupo',
                    'nombre' => 'nombre_grupo',)
                );
                $dcp = objeto_tabla('caso_categoria_presponsable');
                $dcp->id_caso = $dprespcaso->id_caso;
                $dcp->id_presponsable = $dprespcaso->id_presponsable;
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
                    'id_sectorsocial' => 'REL;sector_condicion',
                    'id_organizacion' => 'REL;organizacion',
                    'id_filiacion' => 'REL;observaciones{tipo->filiacion}',
                    'hijos' => 'observaciones{tipo->hijos}',
                    'id_vinculoestado' =>
                    'REL;observaciones{tipo->vinculoestado}',
                    'organizacionarmada' =>
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
                        'observaciones{tipo->vinculoestado}',
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
                $drango = $dvictima->getLink('id_rangoedad');
                a_elementos_xml(
                    $r, 4, array(
                        'observaciones{tipo->rangoedad}' => $drango->rango,
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
            $uobs = "";
            while ($dubicacion->fetch()) {
                $nubi++;
                $uobs = $pobs;
                $pobs .= " - dep: " . $dubicacion->id_departamento .
                    " mun: " . $dubicacion->id_municipio .
                    " cla: " . $dubicacion->id_clase .
                    " longitud: " . $dubicacion->longitud .
                    " latitud: " . $dubicacion->latitud .
                    " tipositio: " . $dubicacion->id_tsitio;
            }
            if ($nubi > 1) {
                $arotros['observaciones{tipo->etiqueta:IMPORTA_RELATO}']
                    = @date('Y-m-d') . " " .
                    _("Tiene más de una ubicación") . ": $uobs";
            }
            $arubicacion = array();
            $dubicacion->aRelato(
                $arubicacion,
                array('forma_compartir' => $formacomp)
            );
            a_elementos_xml(
                $r, 2,
                subarreglo(
                    $arcaso, array('fecha', 'hora',
                'duracion'
                    )
                )
            );

            a_elementos_xml(
                $r, 2,
                subarreglo(
                    $arubicacion, array('departamento', 'municipio',
                    'centro_poblado', 'longitud', 'latitud')
                )
            );
            $ts = $dubicacion->getLink('id_tsitio');
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
                #$dper = $dacto->getLink('id_persona');
                $dpres = $dacto->getLink('id_presponsable');
                $dvictima = objeto_tabla('victima');
                $dvictima->id_caso = $idcaso;
                $dvictima->id_persona = $dacto->id_persona;
                $dvictima->find();
                $dvictima->fetch(1);
                $q = "SELECT clasificacion " .
                    " FROM pconsolidado, categoria " .
                    " WHERE id_pconsolidado=" .
                    " pconsolidado.id " .
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
                a_elementos_xml(
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
                $dpper = $dactocol->getLink('id_presponsable');
                $dvictimacol = objeto_tabla('victimacolectiva');
                $dvictimacol->id_caso = $idcaso;
                $dvictimacol->id_grupoper= $dactocol->id_grupoper;
                $dvictimacol->find();
                $dvictimacol->fetch(1);
                $q = "SELECT clasificacion " .
                    " FROM pconsolidado, categoria " .
                    " WHERE id_pconsolidado =" .
                    " pconsolidado.id " .
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
                a_elementos_xml(
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
            && isset($_SESSION['id_usuario'])
        ) {
            //            include $_SESSION['dirsitio'] . "/conf.php";
            global $dsn;
            $aut_usuario = "";
            autentica_usuario($dsn, $aut_usuario, 0);
            if (!in_array(42, $_SESSION['opciones'])) {
                die('No autorizado');
            }

            //Quien opera debe ser bien conciente, encomendamos a Dios
            //a las personas que dan información para que el proteja su
            //identidad en el caso de quienes no quieren que sea revelada
            //y para que proteja la vida de quienes optan por visibilización,
            //en particular moviendo corazones en nuestra sociedad.
            $r .= "  <!-- Fuente frecuente -->\n";
            $descritocaso = objeto_tabla('caso_ffrecuente');
            $descritocaso->id_caso = $idcaso;
            $descritocaso->orderBy('fecha, id_ffrecuente');
            $descritocaso->find();
            while ($descritocaso->fetch()) {
                $arfuente = array();
                $dffrecuente = $descritocaso->getLink('id_ffrecuente');
                if (isset($dffrecuente) && !PEAR::isError($dffrecuente)
                    && isset($dffrecuente->nombre)
                ) {
                    $arfuente['nombre_fuente'] = $dffrecuente->nombre;
                    $dffrecuente->free();
                }
                unset($dffrecuente);
                $descritocaso->aRelato(
                    $arfuente,
                    array('fecha' => 'fecha_fuente',
                    'ubicacionfisica' => 'ubicacion_fuente',
                    'ubicacion' => 'observaciones{tipo->ubicacion}',
                    'clasificacion' => 'observaciones{tipo->clasificacion}')
                );
                unset($arfuente['id_ffrecuente']);
                $r .= "  <fuente>\n";
                a_elementos_xml($r, 4, $arfuente);
                $r .= "  </fuente>\n";
                unset($arfuente);
            }
            $descritocaso->free();
            unset($descritocaso);

            $r .= "  <!-- Fuente no frecuente -->\n";
            $dfuentedirectacaso = objeto_tabla('caso_fotra');
            $dfuentedirectacaso->id_caso = $idcaso;
            $dfuentedirectacaso->orderBy('fecha, id_fotra');
            $dfuentedirectacaso->find();
            while ($dfuentedirectacaso->fetch()) {
                $dfd = $dfuentedirectacaso->getLink('id_fotra');
                $arfuente = array();
                if (isset($dfd) && !PEAR::isError($dfd)) {
                    $arfuente['nombre_fuente'] = $dfd->nombre;
                }

                $dfuentedirectacaso->aRelato(
                    $arfuente,
                    array('anotacion' => 'observaciones{tipo->anotacion}',
                    'fecha' => 'fecha_fuente',
                    'ubicacionfisica' => 'ubicacion_fuente',
                    'tfuente' => 'observaciones{tipo->tipofuente}')
                );
                $arfuente['observaciones{tipo->tipofuente}']
                    = $dfuentedirectacaso->tfuente;
                    //$dfuentedirectacaso->fb_enumOptions['tfuente'][$ia2];
                $r .= "  <fuente>\n";
                a_elementos_xml($r, 4, $arfuente);
                $r .= "  </fuente>\n";
                unset($ia2);
                unset($arfuente);
                if (isset($dfd) && !PEAR::isError($dfd)) {
                    $dfd->free();
                }
                unset($dfd);
            }
            if (isset($dfuentedirectacaso) && !PEAR::isError($dfuentedirectacaso)) {
                $dfuentedirectacaso->free();
            }
            unset($dfuentedirectacaso);
        }

        if (isset($campos['caso_memo'])) {
            //        $r .= "<acciones_juridicas>"
            //        $r .= "<otras_acciones>"
            //        $r .= "<fecha_publicacion>"
            //        $r .= "<anexo>"


            $r .= "  <!-- Otros -->\n";
            if (isset($dcaso->id_intervalo)) {
                $dinter = $dcaso->getLink('id_intervalo');
                $arotros['observaciones{tipo->intervalo}'] = $dinter->nombre;
                $dinter->free();
                unset($dinter);
            }

            $fr = lista_relacionados(
                'caso_frontera',
                array('id_caso' => $idcaso), 'id_frontera'
            );
            $arotros['observaciones{tipo->frontera}'] = $fr;
            $reg = lista_relacionados(
                'caso_region',
                array('id_caso' => $idcaso), 'id_region'
            );
            $arotros['observaciones{tipo->region}'] = $reg;

            a_elementos_xml($r, 2, $arotros);
            unset($arotros);
            unset($reg);
            unset($fr);
            /*
             * Se decide no poner analista
             *
             */
            a_elementos_xml(
                $r, 2,
                subarreglo(
                    $arcaso, array(
                        'observaciones{tipo->grconfiabilidad}',
                        'observaciones{tipo->gresclarecimiento}',
                        'observaciones{tipo->grimpunidad}',
                        'observaciones{tipo->grinformacion}',
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
            a_elementos_xml(
                $r, 2,
                array('observaciones{tipo->sitio}' => $ubisitio,
                'observaciones{tipo->lugar}' => $ubilugar,
                'observaciones{tipo->tsitio}' => $ubitipositio,
                'observaciones{tipo->contexto}' => $tcont,
                'observaciones{tipo->antecedente}' => $tan,)
            );
            unset($tan);
            unset($tcon);
            unset($ubitipositipo);
            unset($ubilugar);
            unset($ubisitio);
        }
        // Módulos, van como observaciones
        foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
            list($n, $c, ) = $tab;
            if (($d = strrpos($c, "/"))>0) {
                $c = substr($c, $d+1);
            }
            //echo $c;
            if (is_callable(array($c, 'aRelato'))) {
                call_user_func_array(
                    array($c, 'aRelato'),
                    array(&$db, $dcaso, &$r)
                );
            } else {
                echo_esc(
                    _("Falta") . " aRelato " . _("en")
                    . " $n, $c"
                );
            }
        }
   
        $dcaso->free();
        unset($dcaso);
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
            $rcaso .= _("CASO No.") . " "
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
            $arr_ubica = array();
            foreach ($ndd as $k => $nd) {
                $vr .= $seploc . trim($nd);
                $idu .= $idd[$k];
                if ($ndm[$k] != '') {
                    $vr .= " / " . trim($ndm[$k]);
                    $idu .= ":" . $idm[$k];
                }
                if ($ndc[$k] != '') {
                    $vr .= " / " . trim($ndc[$k]);
                    $idu .= ":" . $idc[$k];
                }
                if (isset($arr_ubica[$idu])) {
                    $sepu = " : ";
                    $arr_ubica_listo[$idu] = 1;
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
                        foreach ($arr_ubica[$idu] as $idd => $ddiv) {
                            $vr .= $sepu . "/" . $arr_ubica[$idu][$idd];
                        }
                    }
                }
            }

            $r .= "  " . _("Tip. Ub") . ": ";
            $r .= trim($cadub);
            $r .= "\n\n";

            $dregioncaso = objeto_tabla('caso_region');
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
            $dfronteracaso = objeto_tabla('caso_frontera');
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
            $sep = $GLOBALS['etiqueta']['id_ffrecuente'] . ": ";
            $descritocaso = objeto_tabla('caso_ffrecuente');
            if (PEAR::isError($descritocaso)) {
                die($descritocaso->getMessage());
            }
            $descritocaso->id_caso = $idcaso;
            $descritocaso->orderBy('fecha, id_ffrecuente');
            $descritocaso->find();
            while ($descritocaso->fetch()) {
                $dffrecuente = $descritocaso->getLink('id_ffrecuente');
                $r .= $sep . trim($dffrecuente->nombre);
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
            $dfuentedirectacaso = objeto_tabla('caso_fotra');
            if (PEAR::isError($dfuentedirectacaso)) {
                die($dfuentedirectacaso->getMessage());
            }
            $dfuentedirectacaso->id_caso = $idcaso;
            $dfuentedirectacaso->orderBy('fecha, id_fotra');
            $dfuentedirectacaso->find();
            while ($dfuentedirectacaso->fetch()) {
                $dfuentedirecta
                    = $dfuentedirectacaso->getLink('id_fotra');
                $r .= $sep . trim($dfuentedirecta->nombre);
                $r .= " - ".trim($dfuentedirectacaso->ubicacionfisica);
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
            $dcontexto = objeto_tabla('caso_contexto');
            $dcontexto->id_caso = $idcaso;
            $dcontexto->find();
            $pref = _("Contexto") . ": ";
            $post = "";
            while ($dcontexto->fetch()) {
                $dc = $dcontexto->getLink('id_contexto');
                $r .= $pref . $dc->nombre;
                $pref = ", ";
                $post = "\n";
            }
            $r .= $post;

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
            $dcatpr= objeto_tabla('caso_categoria_presponsable');
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

                $r .= $dcategoria->id_tviolencia .
                    $dcategoria->id. ". ";
                $dtipoviolencia = $dcategoria->getLink('id_tviolencia');
                $r .= trim($dtipoviolencia->nombre)." - ";
                $dsupracategoria = objeto_tabla('supracategoria');
                $dsupracategoria->id = $dcategoria->id_supracategoria;
                $dsupracategoria->id_tviolencia
                    = $dcategoria->id_tviolencia;
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
            list($n, $c, ) = $tab;
            if (($d = strrpos($c, "/"))>0) {
                $c = substr($c, $d+1);
            }
            if (is_callable(array($c, 'reporteGeneralRegistroHtml'))) {
                $r .= call_user_func_array(
                    array($c, 'reporteGeneralRegistroHtml'),
                    array(&$db, $campos, $idcaso)
                );
            } else {
                echo_esc(
                    _("Falta") . " reporteGeneralRegistroHtml "
                    . _("en") . " $n, $c"
                );
            }
        }

        if (array_key_exists('m_fuentes', $campos)) {
            $sep = "\n\n" . a_mayusculas($GLOBALS['etiqueta']['analista']) .
                "(s):\n    ";
            $dcasousuario = objeto_tabla('caso_usuario');
            if (PEAR::isError($dcasousuario)) {
                die($dcasousuario->getMessage());
            }
            $dcasousuario->id_caso = $idcaso;
            $dcasousuario->find();
            while ($dcasousuario->fetch()) {
                $du= $dcasousuario->getLink('id_usuario');
                $r .= $sep . trim($du->nusuario);
                $r .= "  ";
                $m = explode("-", $dcasousuario->fechainicio);
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
        //echo "OJO nomTipificacion($c)<br>";
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
     * Representacion de un grupo de victimas
     *
     * @param string &$r      Cadena resultante
     * @param array  $lvc     Arreglo con códigos de personas por presentar
     * @param array  $lvic    Arreglo de datos de victimas indexado por código
     * @param bool   $indenta Identacion?
     * @param bool   $corto   Formato corto?
     *
     * @return void
     */
    static function representa_victimas(&$r, $lvc, $lvic,
        $indenta, $corto = false
    ) {
        $nns = 0;
        $sep = $corto ? _("Víctimas") . ": " : "";
        $fin = "";
        foreach ($lvc as $idv) {
            if ($idv == -1) {
                return ;
            }
            $nv = $lvic[$idv];
            if (trim($nv)=="NN") {
                $nns++;
            } else {
                if ($indenta) {
                    $r .= "&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                $r .= $sep . trim(strip_tags($nv));
                if (!$corto) {
                    $r .= "\n";
                } else {
                    $sep = ", ";
                    $fin = "\n";
                }
            }
        }
        $r .= $fin;
        if ($nns >= 1 && $indenta) {
            $r .= "&nbsp;&nbsp;&nbsp;&nbsp;";
        }
        if ($nns == 1) {
            $r .= _("PERSONA SIN IDENTIFICAR") . "\n";
        } else if ($nns > 1) {
            $r .= $nns . " " . _("PERSONAS SIN IDENTIFICAR") . "\n";
        }
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
    * @param string  $r      Colchon para dejar respuesta
    * @param boolena $repgen Para reporte general
    *
    * @return void Agrega al colchon r
    */
    static function listaPrCatVictima($idcaso,  $campos, &$r,
        $repgen= false
    ) {
        $corto = !array_key_exists('m_presponsables', $campos)
            && !array_key_exists('m_tipificacion', $campos)
                && array_key_exists('m_victimas', $campos);

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
        $porVic = array();
        $lvic = array();
        while ($dvictima->fetch()) {
            $dpersona = $dvictima->getLink('id_persona');
            $dacto = objeto_tabla('acto');
            $dacto->id_persona = $dvictima->id_persona;
            $dacto->id_caso = $dvictima->id_caso;
            $dacto->orderBy('id_presponsable, id_categoria');
            $dacto->find();
            while ($dacto->fetch()) {
                $ia1 = $dacto->id_presponsable;
                $ia2 = $dacto->id_categoria;
                $ia3 = 'i' . $dacto->id_persona;
                $porVic[$ia1][$ia2][$ia3] = 'i' . $dacto->id_persona;
            }
            $nvc = strip_tags($dpersona->nombres) . " " .
                strip_tags($dpersona->apellidos);
            $idp = DataObjects_Sectorsocial::id_profesional();
            if ($dvictima->id_profesion != DataObjects_Profesion::idSinInfo()
                && $dvictima->id_sectorsocial == $idp
                && !$corto
            ) {
                $dprofesion = $dvictima->getLink('id_profesion');
                $nvc .= " - " . strip_tags($dprofesion->nombre);
            } else {
                $ids = DataObjects_Sectorsocial::idSinInfo();
                if ($dvictima->id_sectorsocial != $ids && !$corto) {
                    $dsector = $dvictima->
                        getLink('id_sectorsocial');
                    $nvc .= " - " . strip_tags($dsector->nombre);
                }
                $ids = DataObjects_Profesion::idSinInfo();
                if ($dvictima->id_profesion != $ids && !$corto) {
                    $dprofesion = $dvictima->
                        getLink('id_profesion');
                    $nvc .= " - " . strip_tags($dprofesion->nombre);
                }
            }
            #$dper = $dvictima->getLink('id_persona');
            if ($repgen && $dvictima->hijos != null
                && $dvictima->hijos != null && !$corto
            ) {
                $nvc .= " " . $dvictima->hijos. " hijos.";
            }
            $ids = DataObjects_Filiacion::idSinInfo();
            if ($repgen && $dvictima->id_filiacion != $ids && !$corto) {
                $nvc .= " " . $GLOBALS['etiqueta']['filiacion'] . ": ";
                $dfiliacion = $dvictima->getLink('id_filiacion');
                $nvc .= $dfiliacion->nombre . ". ";
            }
            if (isset($campos['m_fuentes']) && !$corto
                && $repgen && trim($dvictima->anotaciones) != ''
            ) {
                $nvc .= " " . $GLOBALS['etiqueta']['anotaciones_victima']
                    . ": " . $dvictima->anotaciones;
            }
            $ids = DataObjects_Organizacion::idSinInfo();
            if ($repgen &&  isset($dvictima->id_organizacion)
                && $dvictima->id_organizacion != $ids && !$corto
            ) {
                $nvc .= "  ".$GLOBALS['etiqueta']['organizacion'] .
                    ": ";
                $dorganizacion = $dvictima->getLink('id_organizacion');
                $nvc .= $dorganizacion->nombre;
            }

            $lvic['i' . $dvictima->id_persona] = $nvc;
        }

        $dvictimacol = objeto_tabla('victimacolectiva');
        $dvictimacol->id_caso = $idcaso;
        $dvictimacol->orderBy('id_grupoper');
        $dvictimacol->find();
        while ($dvictimacol->fetch()) {
            $dgrupoper = $dvictimacol->getLink('id_grupoper');
            $dactoc = objeto_tabla('actocolectivo');
            $dactoc->id_grupoper = $dvictimacol->id_grupoper;
            $dactoc->id_caso = $dvictimacol->id_caso;
            $dactoc->orderBy('id_presponsable, id_categoria');
            $dactoc->find();
            while ($dactoc->fetch()) {
                $ia1 = $dactoc->id_presponsable;
                $ia2 = $dactoc->id_categoria;
                $ia3 = 'c' . $dactoc->id_grupoper;
                $porVic[$ia1][$ia2][$ia3] = $ia3;
                unset($ia1);
                unset($ia2);
                unset($ia3);
            }
            $nvc = strip_tags($dgrupoper->nombre);
            if ($repgen) {
                if ($dvictimacol->personasaprox != null
                    && $dvictimacol->personasaprox > 0
                ) {
                    $nvc .= " (".trim($dvictimacol->personasaprox).") ";
                }
                $nvc .= lista_relacionados(
                    'comunidad_sectorsocial',
                    array('id_caso' => $dvictimacol->id_caso,
                    'id_grupoper' => $dvictimacol->id_grupoper),
                    'id_sector', ', ',
                    "   " . $GLOBALS['etiqueta']['sectorsocial'] . ": "
                );
                $nvc .= lista_relacionados(
                    'comunidad_profesion',
                    array('id_caso' => $dvictimacol->id_caso,
                    'id_grupoper' => $dvictimacol->id_grupoper),
                    'id_profesion', ', ',
                    "   ".$GLOBALS['etiqueta']['profesion'] . ": "
                );
                $nvc .= lista_relacionados(
                    'comunidad_filiacion',
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
        if (!array_key_exists('m_presponsables', $campos)
            && !array_key_exists('m_tipificacion', $campos)
            && array_key_exists('m_victimas', $campos)
        ) {
            $lvc = array_keys($lvic);
            ResConsulta::representa_victimas(
                $r, $lvc, $lvic, $indenta, true
            );
            return;
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
        $dcat = objeto_tabla('caso_categoria_presponsable');
        if (PEAR::isError($dcat)) {
            die($dcat->getMessage());
        }
        $dcat->id_caso = $idcaso;
        $dcat->find();
        $asinv = array();
        while ($dcat->fetch()) {
            $esta = 0;
            foreach ($agC as  $pr => $r1) {
                $pids = explode(",", $pr);
                foreach ($r1 as $ids => $vc) {
                    $arids = explode(",", $ids);
                    if (in_array($dcat->id_presponsable, $pids)
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
                        . $dcat->id_presponsable;
                } else {
                    $asinv[$dcat->id_categoria]
                        = $dcat->id_presponsable;
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
                    $dpr = objeto_tabla('presponsable');
                    $dpr->get('id', $idp);
                    if ($ra != "") {
                        $sep2=", ";
                    }
                    $ra .= $sep2 . $rant;
                    $rant = trim(strip_tags($dpr->nombre));
                    $dprc = objeto_tabla('caso_presponsable');
                    $dprc->id_caso = $idcaso;
                    $dprc->id_presponsable = $idp;
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
                    if ($indenta) {
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
                    $sepc = "";
                }
                foreach ($arids as $idc) {
                    $dpr = objeto_tabla('categoria');
                    $dpr->id = $idc;
                    $dpr->find();
                    $dpr->fetch();
                    if ($repgen) {
                        $r .= $sepc . $dpr->id_tviolencia . $idc;
                        $sepc = " / ";
                        continue;
                    }
                    $ds = objeto_tabla('supracategoria');
                    $ds->id_tviolencia
                        = $dpr->id_tviolencia;
                    $ds->id = $dpr->id_supracategoria;
                    $ds->find(1);
                    $dt = $dpr->getLink('id_tviolencia');
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
                if (array_key_exists('m_victimas', $campos)) {
                    if (!$indenta) {
                        $r .= "\n";
                    }
                    $lvc = explode(',', $vc);
                    ResConsulta::representa_victimas(
                        $r, $lvc, $lvic, $indenta
                    );
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
     * @param integer      $idcaso  Id. del caso
     * @param handle       $db      Conexión a BD
     * @param array        $campos  Campos por mostrar
     * @param boolean      $varlin  Varias líneas
     * @param boolean      $tex     Generar TeX ?
     * @param integer|null $numcaso Número de caso para orden por rótulo
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
                    echo_esc(
                        _("Falta") ." $f " . _("indicada en")
                        . " gancho_rc_reginicial[$k]"
                    );
                }
            }
        }

        if (array_key_exists('caso_fecha', $campos)) {
            $a = explode("-", $dcaso->fecha);
            /*$ts=mktime(0, 0, 0, $a[1], $a[2], $a[0]);
            setlocale(LC_TIME, "es");
            strftime ... Se intentó pero OpenBSD 5.0 no soportó locale es */
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
                if ($numcaso !== null) {
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
        if ($numcaso === null && $mvicopr) {
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
            list($n, $c, ) = $tab;
            if (($d = strrpos($c, "/"))>0) {
                $c = substr($c, $d+1);
            }
            if (is_callable(array($c, 'reporteRevistaRegistroHtml'))) {
                $r .= call_user_func_array(
                    array($c, 'reporteRevistaRegistroHtml'),
                    array(&$db, $campos, $idcaso)
                );
            } else {
                echo_esc(
                    _("Falta") . " reporteRevistaRegistroHtml "
                    . _("en") . " $n, $c"
                );
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
                    echo_esc(
                        _("Falta") . " $f ". _("indicada en")
                        . "gancho_rc_regfinal[$k]"
                    );
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
     * @param integer $idcaso Id. caso
     * @param array   $campos Campos por mostrar
     * @param array   $conv   Para conversión de ids.
     * @param array   $sal    registro por generar
     *
     * @return void
     */
    function reporteCsvAdjunto($db, $idcaso, $campos, $conv, $sal)
    {
        $adjunto_renglon = "";
        $vrpre = '"';
        $escon = array();
        foreach ($campos as $cc => $nc) {
            $adjunto_renglon .= "";
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
                $dff = objeto_tabla('caso_ffrecuente');
                if (PEAR::isError($dff)) {
                    die($dff->getMessage());
                }
                $dff->id_caso = $idcaso;
                $dff->find();
                $seploc = "";
                while ($dff->fetch()) {
                    $des = $dff->getLink('id_ffrecuente');
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
                $seploc = "";
                for ($k = 0; $k < count($ndp); $k++) {
                    $q = "SELECT id_tviolencia, id_categoria " .
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
                #$indid = -1;
                /* $totv+=ResConsulta::extraeCombatientes($idcaso,
                    $db, $idp, $ndp, null, $indid
                );
                for (; $k < count($ndp); $k++) {
                    $vr .= $seploc . $ndp[$k];
                    $vrescon .= $seploc . trim($ndp[$k]);
                    $seploc = "; ";
                } */

                $totelem = 0;
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
                $ncat = array();
                ResConsulta::llenaSelCategoria(
                    $db,
                    "SELECT id_tviolencia, id_supracategoria, " .
                    "id_categoria FROM categoria_caso " .
                    "WHERE id_caso='$idcaso' " .
                    "ORDER BY id_tviolencia," .
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
        return $adjunto_renglon;
    }

}



?>
