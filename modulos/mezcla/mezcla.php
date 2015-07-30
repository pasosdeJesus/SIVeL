<?php
//  vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Mezcla dos casos
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
 * Mezcla dos casos
 */
require_once "aut.php";
require_once $_SESSION['dirsitio'] . "/conf.php";
require_once 'misc.php';

$aut_usuario = "";
$db = autentica_usuario($dsn, $accno, $aut_usuario, 31);

require_once $_SESSION['dirsitio'] . "/conf_int.php";
require_once 'misc_caso.php';

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
 * Inserta un registro de la tabla $t en la base de datos $db con
 * estructura $estbd a partir de un DataObject $objd y una especificación
 * de sus campos por cambiar $camposcamb.
 *
 * @param object $db         Conexión a base de datos
 * @param array  $estbd      Estructura de base de datos 
 *                     ($tabla => ($campo1 => $tipo1, ... $campon => $tipon))
 * @param string $t          Tabla
 * @param array  $camposcamb Arreglo de campos por cambiar ($campo => $valor)
 * @param array  $camposelim Arreglo de campos por eliminar ($campo => 'E')
 * @param object $objd       Objeto/registro del cual copiar
 *
 * @return void
 */
function inserta_con_plantilla($db, $estbd, $t, $camposcamb, $camposelim, $objd)
{
    //echo "OJO inserta_con_plantilla(db, estbd, $t, camposcamb, 
    //camposelim, objd)<br>"; print_r($camposelim);
    $sep = $nc = $vc = "";
    foreach ($estbd[$t] as $c => $tc) {
        if (!isset($camposelim[$c])) {
            //echo "OJO c=$c, tc=$tc<br>";
            $nc .= "$sep$c";
            if (isset($camposcamb[$c])) {
                $ve = "'" . var_escapa($camposcamb[$c], $db) . "'";
            } elseif ($objd->$c != null) {
                $ve = "'" . var_escapa($objd->$c, $db) . "'";
            } else {
                $ve = "NULL";
            }
            $vc .= "$sep$ve";
            $sep = ", ";
        }
    }
    $q = "INSERT INTO $t ($nc) VALUES ($vc)";
    //echo "OJO <hr>q=$q";
    $r = hace_consulta($db, $q);
    sin_error_pear($r);
}

/**
 * Encuentra referencia inicial de una referencia $r de la tabla $t
 *
 * @param array  $enl Enlaces
 * @param string $t   Tabla
 * @param string $r   Referencia
 *
 * @return string Referencia Inicial
 */
function ref_inicial($enl, $t, $r)
{
    //#echo "OJO ref_inicial(enl, $t, $r)";
    if (!isset($enl[$t])) {
        //echo "OJO caso 1";
        return $r;
    }
    foreach ($enl[$t] as $ct => $rotra) {
        //echo "OJO ct=$ct, rotra=$rotra";
        if (strpos($ct, ",")) {
            $mct = explode(",", $ct);
            list($nt, $lc) = explode(":", $rotra);
            $mrotra = explode(",", $lc);
            //echo "OJO nt=$nt ";
            for ($i = 0; $i < count($mct); $i++) {
                //echo "OJO i=$i, mct[i]={$mct[$i]}, 
                //mrotra[i]={$mrotra[$i]}<br>";
                if ($mrotra[$i] == $r) {
                    return ref_inicial($enl, $nt, $mrotra[$i]);
                }
            }
        } else {
            //echo "OJO r=$r, ct=$ct, ";
            list($nt, $nc) = explode(":", $rotra);
            //echo "OJO nt=$nt, nc=$nc, ";
            if ($nc == $ct) {
                if (isset($enl[$nt][$nc])) {
                    $ri = ref_inicial($enl, $nt, $enl[$nt][$nc]);
                    if ($ri == "") {
                        $ri = $rotra;
                    }
                } else {
                    $ri = $rotra;
                }
                //echo "OJO ri=$ri<br>";
                return $ri;
            } else {
                return $r;
            }
            //echo "OJO <br>";
        }
    }
}

/**
 * Retorna una subcadena de $s desde la izquierda hasta la primera
 * ocurrencia de $c.
 *
 * @param string $s Cadena en la cual buscar
 * @param string $c Catacter buscado
 *
 * @return string Subcadena de $s
 */
function subizq_hasta_car($s, $c)
{
    $p = strpos($s, $c);
    $r = $s;
    if ($p !== false) {
        $r = substr($s, 0, $p);
    }
    return $r;
}

/**
 * Mezcla información del caso id2 dentro de id1
 * o dejando en observaciones cuando no puede
 *
 * @param integer $id1    Primer caso
 * @param integer $id2    Segundo caso
 * @param bool    $elim2  Elimina segundo tras mezclar?
 * @param string  $obs   Colchon de observaciones
 * @param string  $rvic  Retorna aqui víctimas tras mezclas
 * @param string  $fecha Retorna aqui fecha tras mezcla
 * @param string  $rdep  Retorna aqui departamento tras mezcla
 *
 * @return bool Si logra completar mezcla
 */
function mezclaen($id1, $id2, $elim2, &$obs, &$rvic, &$fecha, &$rdep)
{
    $dc = objeto_tabla('caso');
    $db = $dc->getDatabaseConnection();
    $estbd = parse_ini_file(
        $_SESSION['dirsitio'] . "/DataObjects/" .
        $GLOBALS['dbnombre'] . ".ini",
        true
    );
    $enl = parse_ini_file(
        $_SESSION['dirsitio'] . "/DataObjects/" .
        $GLOBALS['dbnombre'] . ".links.ini",
        true
    );
    act_globales();
    $basicas = html_menu_toma_url($GLOBALS['menu_tablas_basicas']);

    // Ordenamos tablas que referencian caso:id por su número de
    // llaves primarias, para comenzar por las que tienen menos.
    $ref = array();
    foreach ($enl as $t => $ct) {
        foreach ($ct as $c => $rl) {
            list($tab, $cam) = explode(":", $rl);
            //echo " OJO <br> t=$t, c=$c, tab=$tab, cam=$cam ";
            //echo " OJO estbd[tab__keys]= ";
            //print_r($estbd["{$t}__keys"]);
            if (strpos($c, ",")) {
                // pasar
            } elseif (in_array($tab, $basicas)) {
                // pasar
            } elseif (isset($estbd["{$t}__keys"][$c])) {
                $ref[$rl][$t] = count($estbd["{$t}__keys"]);
            } else if ($t != $tab) {
                $ref[$rl][$t] = 1;
            }
        }
    }
    $ref['caso:id']['caso'] = 0;

    asort($ref['caso:id']);
    //echo "<hr>ref=";print_r($ref['persona:id']); die("x");
    //echo "<hr>ref=";print_r($ref); die("x");
    //echo "OJO Caso<br>";
    $do1 = objeto_tabla('caso');
    $do2 = objeto_tabla('caso');
    $do1->id = $id1;
    $do2->id = $id2;
    if (!$do1->find(1)) {
        $obs .= " No se encontró primer caso $id1";
        return false;
    }
    if (!$do2->find(1)) {
        $obs .= " No se encontró segundo caso $id2";
        return false;
    }
    $do1->mezclaAutom($do2, $obs);
    $do1->update();
    sin_error_pear($do1);
    $fecha = $do1->fecha;
    unset($ref['caso:id']['caso']);

    //echo "OJO Víctima<br>";
    $mapk = array();
    $do1 = objeto_tabla('victima');
    $dp1 = objeto_tabla('persona');
    $do2 = objeto_tabla('victima');
    $dp2 = objeto_tabla('persona');
    $do1->id_caso = $id1;
    $do2->id_caso = $id2;
    $do1->find(1);
    sin_error_pear($do1);
    $do2->find(1);
    sin_error_pear($do2);
    $mapk['persona'][$do2->id_persona] = $do1->id_persona;
    $idp1 = $dp1->id = $do1->id_persona;
    $dp1->find(1);
    sin_error_pear($dp1);
    $idp2 = $dp2->id = $do2->id_persona;
    $dp2->find(1);
    sin_error_pear($dp2);
    $do1->mezclaAutom($do2, $obs);
    $do1->update();
    sin_error_pear($do1);
    $dp1->mezclaAutom($dp2, $obs);
    $dp1->update();
    sin_error_pear($dp1);
    $rvic = $dp1->nombres . " " . $dp1->apellidos;
    unset($ref['caso:id']['victima']);
    unset($ref['presponsable:id']['victima']);
    unset($ref['persona:id']['victima']);

    //echo "OJO Relacionados con persona<br>";
    $do2 = objeto_tabla('persona_trelacion');
    $do2->persona1 = $idp2;
    $do2->find();
    while ($do2->fetch()) {
        //echo "OJO Relacionada persona {$do2->persona2}";
        $do1 = objeto_tabla('persona_trelacion');
        $do1->persona1 = $idp1;
        $do1->persona2 = $do2->persona2;
        $do1->find();
        if ($do1->fetch()) {
            //echo "OJO Mezcla persona_trelacion";
            $do1->mezclaAutom($do2, $obs);
            $do1->update();
            sin_error_pear($do1);
        } else {
            //echo "OJO Inserta persona_trelacion";
            inserta_con_plantilla(
                $db, $estbd, 'persona_trelacion',
                array(
                    'persona1' => $idp1,
                    'persona2' => $do2->persona2,
                ), array(), $do2
            );
            $obs .= " Asociada(p) persona_trelacion({$idp1}, {$do2->persona2})";
        }
    }
    $do2 = objeto_tabla('persona_trelacion');
    $do2->persona2 = $idp2;
    $do2->find();
    while ($do2->fetch()) {
        //echo "OJO Relacionada persona {$do2->persona1}";
        $do1 = objeto_tabla('persona_trelacion');
        $do1->persona1 = $do2->persona1;
        $do1->persona2 = $idp1;
        $do1->find();
        if ($do1->fetch()) {
            //echo "OJO Mezcla persona_trelacion";
            $do1->mezclaAutom($do2, $obs);
            $do1->update();
            sin_error_pear($do1);
        } else {
            //echo "OJO Inserta persona_trelacion";
            inserta_con_plantilla(
                $db, $estbd, 'persona_trelacion',
                array(
                    'persona1' => $do2->persona1,
                    'persona2' => $idp1,
                ), array(), $do2
            );
            $obs .= " Asociada(p) persona_trelacion({$do2->persona1}, {$idp1})";
        }
    }
    unset($ref['persona:id']['persona_trelacion']);

    //echo "OJO Caso_presponsable<br>";
    // caso_presponsable
    $do2 = objeto_tabla('caso_presponsable');
    $do2->id_caso = $id2;
    $do2->find();
    sin_error_pear($do2);
    while ($do2->fetch()) {
        $do1 = objeto_tabla('caso_presponsable');
        $do1->id_caso = $id1;
        $do1->id_presponsable = $do2->id_presponsable;
        $do1->find();
        if (!$do1->fetch()) {
            $m = $db->getOne(
                "SELECT MAX(id)+1 FROM caso_presponsable WHERE id_caso='$id1'"
            );
            if (PEAR::isError($m)) {
                $m = 1;
            } else {
                $m = (int)$m;
            }
            inserta_con_plantilla(
                $db, $estbd, 'caso_presponsable',
                array(
                    'id_caso' => $id1,
                    'id' => $m
                ), array(), $do2
            );
            $obs .= " Asociado caso_presponsable" 
                . "(id_presponsable:{$do2->id_presponsable})";
            $mapk['caso_presponsable'][$do2->id] = $m;
        }
    }
    unset($ref['caso:id']['caso_presponsable']);
    unset($ref['presponsable:id']['caso_presponsable']);

    //echo "OJO Caso_categoria_presponsable<br>";
    // caso_categoria_presponable
    $do2 = objeto_tabla('caso_categoria_presponsable');
    $do2->id_caso = $id2;
    $do2->find();
    sin_error_pear($do2);
    while ($do2->fetch()) {
        $do1 = objeto_tabla('caso_categoria_presponsable');
        $do1->id_caso = $id1;
        $do1->id_presponsable = $do2->id_presponsable;
        $do1->id_categoria = $do2->id_categoria;
        $do1->find();
        if (!$do1->fetch()) {
            /*$do2->id_caso = $id1;
            $do2->id = null;
            $do2->insert(); */
            inserta_con_plantilla(
                $db, $estbd, 'caso_categoria_presponsable',
                array(
                    'id_caso' => $id1,
                    'id' => $mapk['caso_presponsable'][$do2->id],
                ), array(),
                $do2
            );
            $obs .= " Asociado caso_categoria_presponsable"
                . "(id_presponsable:{$do2->id_presponsable})";
        }
    }
    unset($ref['caso:id']['caso_categoria_presponsable']);
    unset($ref['presponsable:id']['caso_categoria_presponsable']);
    unset($ref['categoria:id']['caso_categoria_presponsable']);

    // acto 
    $do2 = objeto_tabla('acto');
    $do2->id_caso = $id2;
    $do2->find();
    $dot1 = null;
    sin_error_pear($do2);
    while ($do2->fetch()) {
        if (isset($mapk['persona'][$do2->id_persona])) {
            $do1 = objeto_tabla('acto');
            if ($GLOBALS['actoreiniciar']) {
                $dot1 = objeto_tabla('actoreiniciar');
                $dot1->id_caso = $do1->id_caso = $id1;
                $dot1->id_presponsable = $do1->id_presponsable 
                    = $do2->id_presponsable;
                $dot1->id_categoria = $do1->id_categoria = $do2->id_categoria;
                $dot1->id_persona = $do1->id_persona 
                    = $mapk['persona'][$do2->id_persona];
            }
            $do1->find();
            if (!$do1->fetch()) {
                //$do1->insert();
                inserta_con_plantilla(
                    $db, $estbd, 'acto',
                    array(
                        'id_caso' => $id1,
                        'id_persona' => $mapk['persona'][$do2->id_persona],
                    ), array(),
                    $do2
                );
                $obs .= " Asociado acto(id_persona:"
                    . "{$do1->id_persona},id_categoria:{$do2->id_categoria})";
            }
            if ($dot1 != null) {
                $dot1->find();
                if (!$dot1->fetch()) {
                    $dot1->fecha = $fecha;
                    $dot1->insert();
                    sin_error_pear($dot1);
                    $obs .= " Asociado actoreiniciar(id_persona:" +
                        "{$dot1->id_persona}," +
                        "id_categoria:{$dot1->id_categoria}," +
                        "fecha:{$dot1->fecha})";
                }
            }
        }
    }
    foreach (array('presponsable', 'caso', 'persona', 'categoria') as $t) {
        unset($ref["$t:id"]['acto']);
        unset($ref["$t:id"]['actoreiniciar']);
    }

    // Otras tablas relacionadas con caso y persona
    foreach ($ref['persona:id'] as $t => $v) {
        if (isset($ref['caso:id'][$t])) {
            unset($ref['persona:id'][$t]);
            //echo "OJO Con caso y persona $t<br>";
            $do2 = objeto_tabla($t);
            $vcla = get_object_vars($do2);
            if (!array_key_exists('id_persona', $vcla)) {
                continue;
            }
            //echo "OJO $t tiene persona<br>"; print_r($vcla);
            $do2->id_caso = $id2;
            $do2->find();
            unset($ref['caso:id'][$t]);
            while ($do2->fetch()) {
                $do1 = objeto_tabla($t);
                $do1->id_caso = $id1;
                $do1->id_persona = isset($mapk['persona'][$do2->id_persona]) ?
                    $mapk['persona'][$do2->id_persona] : $do2->id_persona;
                $ck = $estbd["{$t}__keys"];
                $llavmos = $sep = "";
                foreach ($ck as $k => $kb) {
                    if ($k != 'id_caso' && $k != 'id_persona') {
                        $do1->$k= $do2->$k;
                        $llavmos .= "$sep{$k}:{$do2->$k}";
                        $sep =", ";
                    }
                }
                $do1->find();
                if ($do1->fetch()) {
                    $do1->mezclaAutom($do2, $obs);
                    $do1->update();
                    sin_error_pear($do1);
                } else {
                    inserta_con_plantilla(
                        $db, $estbd, $t,
                        array(
                            'id_caso' => $id1,
                            'id_persona' =>
                            isset($mapk['persona'][$do2->id_persona]) ?
                            $mapk['persona'][$do2->id_persona] :
                            $do2->id_persona,

                        ), array(), $do2
                    );
                    $obs .= " Asociada(p) $t({$llavmos})";
                }
            }
        }
    }


    //Otras tablas relacionadas con caso
    foreach ($ref['caso:id'] as $t => $n) {
        //echo "OJO Otro t=$t<br>";
        $do2 = objeto_tabla($t);
        $do2->id_caso = $id2;
        $do2->find();
        while ($do2->fetch()) {
            $do1 = objeto_tabla($t);
            $do1->id_caso = $id1;
            $ck = $estbd["{$t}__keys"];
            $llavmos = $sep = "";
            foreach ($ck as $k => $kb) {
                if ($k != 'id_caso' && $k != 'id') {
                    $do1->$k= $do2->$k;
                    $llavmos .= "$sep{$k}:{$do2->$k}";
                    $sep =", ";
                }
            }
            $do1->find();
            if ($do1->fetch()) {
                $do1->mezclaAutom($do2, $obs);
                $do1->update();
                sin_error_pear($do1);
            } else {
                $pe = array();
                if (isset($do2->id)) {
                    $pe['id'] = 'E';
                    //echo "OJO no teniendo en cuenta id en $t";
                }
                inserta_con_plantilla(
                    $db, $estbd, $t,
                    array(
                        'id_caso' => $id1,
                    ), $pe, $do2
                );
                $obs .= " Asociada(g) $t({$llavmos})";
                $do1->find(1);
                if (isset($do2->id)) {
                    $mapk[$t][$do2->id] = $do1->id;
                }
            }
            if ($t == "ubicacion" && $do1->id_departamento != null) {
                $ddep = $do1->getLink('id_departamento');
                $rdep = $ddep->nombre;
            }
        }
        foreach ($estbd[$t] as $c => $tip) {
            if (isset($enl[$t][$c])) {
                unset($ref[$enl[$t][$c]][$t]);
            }
        }
        unset($ref['caso:id'][$t]);
    }
    //echo "OJO Proceso<br>";
    foreach ($ref['proceso:id'] as $t => $n) {
        //echo "OJO Proceso t=$t<br>";
        if (isset($mapk['proceso'])) {
            foreach ($mapk['proceso'] as $la => $ln) {
                //echo "OJO la=$la, ln=$ln<br>";
                $do2 = objeto_tabla($t);
                $do2->id_proceso = $la;
                $do2->find();
                while ($do2->fetch()) {
                    //echo "Insertando con plantilla ln=$ln<br>";
                    inserta_con_plantilla(
                        $db, $estbd, $t,
                        array(
                            'id_proceso' => $ln,
                        ), array('id' => 'E'), $do2
                    );
                }
            }
        }
        unset($ref['proceso:id'][$t]);
    }
    if ($elim2) {
        if ($id1 != $id2) {
            elimina_caso($db, $id2);
        }
        if (isset($idp2) && $idp1 != $idp2) {
            $q = "DELETE FROM persona_trelacion WHERE persona1='$idp2'";
            hace_consulta($db, $q);
            $q = "DELETE FROM persona_trelacion WHERE persona2='$idp2'";
            hace_consulta($db, $q);
            $q = "DELETE FROM persona WHERE id='$idp2'";
            hace_consulta($db, $q);
        }
    }
    return true;
}


/**
 * Punto de entrada a formulario
 *
 * @param string $dsn URL a base de datos
 *
 * @return void
 */
function muestra($dsn)
{
    global $db;

    $html_t = "Mezcla "
        . date("Y-m-d H:m");
    encabezado_envia($html_t);
    echo '<table width="100%"><td style="white-space: ' .
        ' nowrap; background-color: #CCCCCC;" align="left" ' .
        ' valign="top" colspan="2"><b><div align=center>' ;
    echo $html_t;
    echo '</div></b></td></table>';

    $par = array();
    $hom = array();
    foreach ($_POST as $cid1 => $on) {
        $pd = explode("_", $cid1);
        if (isset($pd[1]) && isset($pd[2])) {
            if ((int)$pd[1] <= 0) {
                error_valida(
                    "Códigos deben ser positivos (problema con {$pd[1]})", null
                );
                return false;
            }
            if ((int)$pd[2] <= 0) {
                error_valida(
                    "Códigos deben ser positivos (problema con {$pd[2]})", null
                );
                return false;
            }
            $id1 = (int)$pd[1];
            $id2 = (int)$pd[2];
            if ($id1 == $id2) {
                error_valida(
                    "Códigos deben ser diferentes (problema con $id1)", null
                );
                return false;
            }
            if ($pd[0] == 'm') {
                $par[] = array($id1, $id2);
            } else if ($pd[0] == 'h') {
                $hom[] = array($id1, $id2);
            }
        }
    }

    $tmez = 0;
    $mezcladoen = array();
    echo "<p>Mezclando " . count($par) . " parejas de casos</p><p>";
    echo "<center><table border='1'>";
    echo "<tr><th>Caso que queda</th><th>Caso eliminado</th>
        <th>Observaciones</th></tr>";
    foreach ($par as $p) {
        $id1 = $p[0];
        $id2 = $p[1];
        if (isset($mezcladoen[$id2])) {
            continue;
        }
        $dec = objeto_tabla('caso_etiqueta');
        $obs = "";
        $idet = (int)conv_basica(
            $db, 'etiqueta', 'MEZCLA_CASOS', $obs, false, 'nombre'
        );
        if ($idet == -1) {
            die_act("No se encontró etiqueta MEZCLA_CASOS");
        }
        $dec->id_caso = $id1;
        $dec->id_etiqueta = $idet;
        $dec->id_usuario = $_SESSION['id_usuario'];
        $dec->fecha = @date('Y-m-d');
        $r1 = ResConsulta::reporteRelato(
            $id1, $db,
            $GLOBALS['cw_ncampos'] + array('m_fuentes' => 'Fuentes')
        );
        $r2 = ResConsulta::reporteRelato(
            $id2, $db,
            $GLOBALS['cw_ncampos'] + array('m_fuentes' => 'Fuentes')
        );
        $obs1 = "Caso original ($id1): " . strip_tags($r1)
            . "\nCaso mezclado ($id2): " . strip_tags($r2) ."\n";

        echo "<tr>";
        echo "<td><a href='captura_caso.php?modo=edita&id=" . (int)$id1 . "'>"
            . (int)$id1 . "</a></td>";
        echo "<td>" . (int)$id2 . "</td>";
        $html_obs2 = "";
        $fecha = ""; $dep = ""; $vic = "";
        if (mezclaen($id1, $id2, true, $html_obs2, $vic, $fecha, $dep)) {
            $tmez++;
            $mezcladoen[$id2] = array($id1, $vic, $fecha, $dep);
        }
        echo "<td>$html_obs2</td>";
        echo "</tr>\n";
        $dec->observaciones = var_escapa($html_obs2 . "\n" . $obs1, $db, 5000);
        $dec->insert();
        //if ($tmez == 1) { break; die("y"); }
    }
    echo "</table></center>";
    echo "</p>";
    echo "<p>Casos mezclados: " . (int)$tmez . "</p>";
    echo "<p>Referencia de mezclados:</p>";
    echo "<center><table border='1'>";
    echo "<tr><th>Código inicial</th><th>Mezclado en</th>
        <th>Víctima(s)</th><th>Fecha</th><th>Ubicación</th></tr>";
    ksort($mezcladoen);
    foreach ($mezcladoen as $id2 => $l) {
        $id1 = $l[0];
        $rvic = $l[1];
        $rfec = $l[2];
        $rdep = $l[3];
        echo "<tr>";
        echo "<td>" . (int)$id2 . "</td><td>" . (int)$id1 . "</td>";
        echo "<td>" . htmlentities($rvic, ENT_COMPAT, 'UTF-8') . "</td>"
            . "<td>" . htmlentities($rfec, ENT_COMPAT, 'UTF-8') . "</td>"
            . "<td>" . htmlentities($rdep, ENT_COMPAT, 'UTF-8') . "</td>";
        echo "</tr>";
    }
    echo "</table></center>";

    echo "<p>Agregando " . count($hom) 
        . " parejas de homonimos o similares (pero diferentes)</p><p>";
    echo "<center><table border='1'>";
    echo "<tr><th>Caso 1</th><th>Persona 1</th><th>Caso 2</th>
        <th>Persona 2</th2><td>Observaciones</td></tr>";
    foreach ($hom as $par) {
        $v1 = objeto_tabla('victima');
        $v1->id_caso = $par[0];
        $v1->find(1);
        $v2 = objeto_tabla('victima');
        $v2->id_caso = $par[1];
        $v2->find(1);
        $id1 = $id2 = -1;
        if ($v1->id_persona < $v2->id_persona) {
            $id1 = $v1->id_persona;
            $id2 = $v2->id_persona;
        } else if ($v1->id_persona > $v2->id_persona) {
            $id2 = $v1->id_persona;
            $id1 = $v2->id_persona;
        }
        if ($id1 != $id2) {
            $h = objeto_tabla('homonimosim');
            $h->id_persona1 = $id1;
            $h->id_persona2 = $id2;
            $h->insert();
            sin_error_pear($h);
        }
        echo "<tr>";
        echo "<td>" . (int)$par[0] . "</td><td>" . (int)$v1->id_persona 
            . "</td><td>" . (int)$par[1] . "</td><td>" . (int)$v2->id_persona
            . "</td>";
        if ($id1 == $id2) {
            echo "<td>Misma persona --no es homonimo</td>";
        } else {
            echo "<td></td>";
        }
        echo "</tr>";
    }

    echo "</table></center>";

    echo '<table width="100%">
        <td style = "white-space: nowrap; background-color: #CCCCCC;"
        align = "left" valign="top" colspan="2"><b><div align=right>
        <a href = "index.php">Menú Principal</a></div></b></td></table>';


    pie_envia();

}

?>
