<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Funciones diversas útiles en varias fuentes PHP.
 * Créditos: Se ha empleado porciones cortas de código y documentación
 * disponible en: http://structio.sourceforge.net/seguidor
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

/**
 * Funciones diversas útiles en varias fuentes PHP.
 */

require_once "Auth.php";
require_once "HTML/QuickForm.php";
require_once "HTML/Common.php";
require_once "DB_DataObject_SIVeL.php";

/**
 * Encabezado de un relato
 * @global string $GLOBALS['enc_relato']
 * @name enc_relato
 */
$GLOBALS['enc_relato']
    = "<" ."?xml version=\"1.0\" encoding=\"UTF-8\"?".">\n"
    . "<!DOCTYPE relatos PUBLIC \"-//SINCODH/DTD relatos 0.97\" "
    . "\"relatos.dtd\">\n"
    . '<'.'?xml-stylesheet type="text/xsl" href="xrlat-a-html.xsl"?'
    . ">\n";

/**
 * Número de caso usado en búsquedas --no puede usarse en casos.
 * @global unknown $GLOBALS['idbus']
 * @name   $idbus
 */
$GLOBALS['idbus']=-1;

/* -------- OPERACIONES CON CADENAS */

/**
 * Convierte a minúsculas textos en español
 *
 * @param string $s Cadena
 *
 * @return string Convertida a minúsculas
 */
function a_minusculas($s)
{
    $r = mb_strtolower($s, 'UTF8');
    $r = str_replace(
        array('Á', 'É', 'Í', 'Ó', 'Ú'),
        array('á', 'é', 'í', 'ó', 'ú'), $r
    );
    return $r;
}


/**
 * Convierta mayúsculas textos en español
 *
 * @param string $s Cadena
 *
 * @return string Convertida a mayúscula
     */
function a_mayusculas($s)
{
    $r = str_replace(
        array('á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü'),
        array('Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ', 'Ü'),
        $s
    );
    $r = mb_strtoupper($r, 'UTF8');
    return $r;
}


/**
 * Convierte a mayúscula la primera letra de cada palabra de $s y el resto a
 * minúsculas.
 *
 * @param string $s Cadena
 *
 * @return string Convertida primera letra de cada palabra a mayúscula
 *         y resto a minúsculas
     */
function prim_may($s)
{
    $rs = a_minusculas($s);
    $ant = 1; // Próximo debe ser mayúscula
    for ($i = 0; $i < strlen($rs); $i++) {
        if ($ant == 1) {
            $rs[$i] = a_mayusculas($rs[$i]);
            $ant = 0;
        }
        if ($rs[$i] == ' ' || $rs[$i] == '('
            || $rs[$i] == '\t' || $rs[$i] == '\n'
        ) {
            $ant = 1;
        }
    }

    return $rs;
}


/**
 * Caracteres escapados en LaTeX
 *
 * @param char $c caracter
 *
 * @return string  Representación laTeX
     */
function car2latex($c)
{
    switch ($c) {
    case '$':
        $r = '\\$';
        break ;
    case '\\':
        $r = '\\textbackslash';
        break;
    case '{':
        $r = '$\\{$';
        break;
    case '}':
        $r = '$\\}$';
        break;
    case '%':
        $r = '\\%';
        break;
    case '_':
        $r = '\\_';
        break;
    case '&':
        $r = '\\&';
        break;
    case '#':
        $r = '\\#';
        break;
    case '^':
        $r = '\\^{}';
        break;
    case '~':
        $r = '\\~{}';
        break;
    case '¿':
        $r = '?`';
        break;
    case '¡':
        $r = '!`';
        break;
    case '|':
        $r = '\\textbar`';
        break;
    default:
        $r = $c;
        break;
    }
    return $r;
}


/**
 * Convierte de texto a laTeX
 *
 * @param string $s Texto
 *
 * @return string Latex
     */
function txt2latex($s)
{
    $r = "";
    $nc = 0; // Número de comillas encontradas
    $nc = 0; // Número de apostrofes encontradas
    for ($i = 0; $i < strlen($s); $i++) {
        switch ($s{$i}) {
        case '"':
            $nc++;
            if (($nc % 2)==1) {
                $r .= "``";
            } else {
                $r .= "''";
            }
            break;
        case '\'':
            $na++;
            if (($na % 2)==1) {
                $r .= "`";
            } else {
                $r .= "'";
            }
            break;
        default:
            $r .= car2latex($s{$i});
            break;
        }
    }
    return $r;
}

/**
 * Convierto de texto a tex
 *
 * @param string $t texto
 *
 * @return string Tex
     */
function formato_texto_tex($t)
{
    $num_com = 0;  // Número de comillas
    $num_apo = 0;  // Número de apostrofes
    $r = "";
    for ($i = 0; $i < strlen($t); $i++) {
        $c = substr($t, $i, 1);
        switch ($c) {
        case '$':
            $r .= '\$';
            break;
        case '"':
            if ($num_com % 2 == 0) {
                $r .= "``";
            } else {
                $r .= "''";
            }
            $num_com++;
            break;
        case '\'':
            if ($num_apo % 2 == 0) {
                $r .= "`";
            } else {
                $r .= "'";
            }
            $num_apo++;
            break;
        default:
            $r .= $c;
            break;
        }
    }
    //$t=str_replace('$', '\$', $t);
    return $r;
}


/* -------- ARREGLOS */

/**
 * Retorna el subarreglo de $ar que tiene llaves de $ind
 *
 * @param array $ar  Arreglo
 * @param array $ind Arreglo de llaves
 *
 * @return array Subarreglo de $arr cuyas llaves están en $ind
 **/
function subarreglo($ar, $ind)
{
    $res = array();
    foreach ($ind as $llave) {
        if (isset($ar[$llave])) {
            $res[$llave] = $ar[$llave];
        }
    }
    return $res;
}


/* -------- OPERACIONES SOBRE estructuras para HTML_Menu */

/**
 * Agrega un submenú a un menu como los requeridos por
 * HTML_Menu
 *
 * @param object &$menu      Menu por modificar
 * @param string $titulo     Titulo por buscar
 * @param string $nsubtitulo Subtitulo por agregar al titulo buscado
 * @param string $nurl       Url por asociar al subtitulo agregado
 * @param array  $nsub       Subarbol por asociar al subtitulo agregado
 *
 * @return true si y solo si encuentra el titulo y puede añadir subtitulo nuevo
     */
function html_menu_agrega_submenu(&$menu, $titulo, $nsubtitulo, $nurl,
    $nsub = null
) {
    if ($titulo == null) {
        assert($nsubtitulo != null && strlen($nsubtitulo) > 0);
        foreach ($menu as $l => $d) {
            if ($d['title'] == $nsubtitulo) {
                return false;
            }
        }
        $n = array('title' => $nsubtitulo,
            'url' => $nurl, 'sub' => $nsub
        );
        $menu[] = $n;
        return true;
    }


    foreach ($menu as $l => $d) {
        if ($d['title'] == $titulo) {
            $n = array('title' => $nsubtitulo,
                'url' => $nurl, 'sub' => $nsub
            );
            if ($d['sub'] == null) {
                $menu[$l]['sub'] = array($n);
            } else {
                foreach ($d['sub'] as $sd) {
                    if ($sd['title'] == $nsubtitulo) {
                        return false;
                    }
                }
                $menu[$l]['sub'][] = $n;
            }
            return true;
        }
        if ($d['sub'] != null) {
            $rhm = html_menu_agrega_submenu(
                $menu[$l]['sub'], $titulo,
                $nsubtitulo, $nurl, $nsub
            );
            if ($rhm) {
                return true;
            }
        }
    }
    return false;
}

/**
 * Retorna arreglo con URLs de un arreglo apropiado para HTML_Menu
 *
 * @param array $m Arreglo para HTML_Menu
 *
 * @return array Arreglo de URLs
     */
function html_menu_toma_url($m)
{
    $r = array();
    if (!is_array($m)) {
        return $r;
    }
    foreach ($m as $ent) {
        if (isset($ent['url']) && $ent['url'] != null) {
            $r[] = $ent['url'];
        }
        if (isset($ent['sub']) && $ent['sub'] != null) {
            $r = array_merge($r, html_menu_toma_url($ent['sub']));
        }
    }
    return $r;
}



/* -------- ARCHIVOS */

/**
 * Envía a salida estándar contenido del archivo noma
 *
 * @param string $noma Nombre del archivo
 * @param string $esc  Escapar contenido antes de presentarlo
 *
 * @return void
 * @see  http://www.php.net/manual/en/function.fopen.php
 **/
function muestra_archivo($noma, $esc = false)
{
    $rh = fopen($noma, "rb");
    while ($rh != false && !feof($rh)) {
        if ($esc) {
            echo_esc(fread($rh, 1024));
        } else {
            $html_l = fread($rh, 1024);
            echo $html_l;
        }
    }
    fclose($rh);
}


/* -------- FORMULARIOS  Y SESIÓN */

/**
 * Agregar tabla a formulario
 *
 * @param string $nom    Nombre de formulario
 * @param object &$f     Formulario
 * @param int    $idcaso Id. del caso
 * @param bool   $nuevo  Si es nuevo
 * @param object &$da    Dataobject
 *
 * @return object Formulario
     */
function agregar_tabla($nom, &$f, $idcaso, $nuevo, &$da)
{
    if (!isset($da) || $da == null) {
        $da =& objeto_tabla($nom);
        $da->id_caso = $idcaso;
    }

    if (!$nuevo) {
        $da->find();
        $da->fetch();
    }

    $ba =& DB_DataObject_FormBuilder::create(
        $da,
        array(
            'requiredRuleMessage' => _('El campo %s es indispensable.'),
            'ruleViolationMessage' =>
            _('%s: El valor que ha ingresado no es válido.')
        )
    );

    $ba->createSubmit = 0;
    $ba->useForm($f);
    $fa = $ba->getForm();

    return $ba;
}


/**
 * Preparación de información en acciones que responden a
 *    eventos de HTML_QuickForm_Controller
 *
 * @param mixed &$page Página
 *
 * @return boolean Validado
     */
function valida(&$page)
{

    $pageName =  $page->getAttribute('id');
    $data     =& $page->controller->container();
    $data['values'][$pageName] = $page->exportValues();
    $data['valid'][$pageName]  = $page->validate();

    if (!$data['valid'][$pageName]) {
        $page->handle('display');
        return false;
    }
    return true;
}


/**
 * Presenta un error de validación no fatal.
 *
 * @param string $msg     Mensaje de error
 * @param array  $valores Valores del formulario por recuperar
 * @param string $iderr   Si es no nulo variable de sesión donde ponerlo
 * @param string $enhtml  Mensaje en HTML
 *
 * @return void
     */
function error_valida($msg, $valores, $iderr = '', $enhtml = false)
{
    if (!headers_sent()) {
        encabezado_envia();
    }
    if (isset($valores) && is_array($valores) && count($valores) > 0) {
        $_SESSION['recuperaErrorValida'] = $valores;
    }
    echo "<div class='regla'>";
    if ($enhtml) {
        echo $msg;
    } else {
        echo htmlentities($msg, ENT_COMPAT, 'UTF-8');
    }
    echo "</div>";
    if ($iderr != '') {
        $_SESSION[$iderr] = $msg;
    }
}

/**
 * Presenta resultado de una validación.
 * La primera columna de la consulta $cons debe ser una identificación
 * de caso
 * Las funciones SQL son tomadas de:
 * http://www.postgresonline.com/journal/archives/
 * 68-More-Aggregate-Fun-Whos-on-First-and-Whos-on-Last.html
 *
 * @param object &$db     Conexión a base de datos
 * @param string $mens    Mensaje por mostrar
 * @param string $cons    Consulta pr realizar
 * @param bool   $confunc Incluir primer funcionario que trabajo caso, en este
 *                        caso columna con id del caso se llama id_caso
 *
 * @return void
 */
function res_valida(&$db, $mens, $cons, $confunc = false)
{
    if ($confunc) {
        hace_consulta(
            $db,
            "CREATE OR REPLACE FUNCTION
            first_element_state(anyarray, anyelement) RETURNS anyarray AS
            $$
            SELECT CASE WHEN array_upper($1,1) IS NULL
                THEN array_append($1,$2)
                ELSE $1
            END;
            $$
            LANGUAGE 'sql' IMMUTABLE;", false, false
        );
        hace_consulta(
            $db,
            "CREATE OR REPLACE FUNCTION first_element(anyarray)
            RETURNS anyelement AS
            $$
            SELECT ($1)[1] ;
            $$
            LANGUAGE 'sql' IMMUTABLE;",
            false, false
        );
        hace_consulta(
            $db,
            "CREATE AGGREGATE first(anyelement) (
                SFUNC = first_element_state,
                STYPE = anyarray,
                FINALFUNC = first_element
            );", false, false
        );
        hace_consulta(
            $db,
            "CREATE VIEW primerfuncionario AS
            SELECT id_caso, MIN(fechainicio) AS fechainicio,
            FIRST(id_funcionario) AS id_funcionario
            FROM caso_funcionario
            GROUP BY id_caso ORDER BY id_caso;", false, false
        );

    }
    echo "<p>" . htmlentities($mens, ENT_COMPAT, 'UTF-8') . ": ";

    if ($confunc) {
        $r = hace_consulta(
            $db,
            "SELECT primerfuncionario.id_caso,
            funcionario.nombre, sub.*
            FROM primerfuncionario, funcionario, ($cons) AS sub
            WHERE primerfuncionario.id_funcionario = funcionario.id
            AND primerfuncionario.id_caso = sub.id_caso"
        );
    } else {
        $r = hace_consulta($db, $cons);
    }
    $nr = $r->numRows();
    echo (int)$nr;
    if ($nr > 0) {
        echo "<center><table border='1'>";
        while ($r->fetchInto($row)) {
            echo "<tr>";
            $nr = 0;
            foreach ($row as $dat) {
                if ($nr == 0) {
                    $n = (int)$dat;
                    $html_l = "<a href='captura_caso.php?modo=edita&id=$n'>"
                        . "$n</a>";
                } else {
                    $html_l = $dat;
                }
                $nr++;
                echo "<td>$html_l</td>";
            }
            echo "</tr>\n";
        }
        echo "</table></center>";
    }
    echo "</p>";
}



/**
 * Retira variables de sesión
 *
 * @return void
     */
function unset_var_session()
{
    unset($_SESSION['basicos_id']);
    unset($_SESSION['bus_fecha_final']);
    unset($_SESSION['bus_fecha_inicial']);
    unset($_SESSION['camDepartamento']);
    unset($_SESSION['camMunicipio']);

    foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
        list($pag, $cl) = $tab;
        $vars = get_class_vars($cl);
        if (isset($vars['pref'])) {
            unset($_SESSION[$vars['pref'] . '_pag']);
            unset($_SESSION[$vars['pref'] . '_total']);
            unset($_SESSION[$vars['pref'] . '_error_valida']);
        }
    }

    unset($_SESSION['fvm_nuevo_copia_id_combatiente']);
    unset($_SESSION['fvm_error_valida']);
    unset($_SESSION['fvi_error_valida']);
    unset($_SESSION['fvc_error_valida']);
    unset($_SESSION['fvc_nuevo_copia_id_grupoper']);
    unset($_SESSION['fvi_nuevo_copia_id_persona']);
    unset($_SESSION['fvm_pag']);
    unset($_SESSION['fvm_total']);
    unset($_SESSION['id_Municipio']);
    unset($_SESSION['id_departamento']);
    unset($_SESSION['id_municipio']);
    unset($_SESSION['_Caso_container']);
}


/**
 * Pone en campos de un formulario los valores del arreglo valores
 *
 * @param object &$pag    Formulario
 * @param array  $campos  Campos por establecer
 * @param array  $valores Valores indexados por campos
 *
 * @return void
     */
function establece_valores_form(&$pag, $campos, $valores)
{
    foreach ($campos as $c) {
        $e =& $pag->getElement($c);
        if (!PEAR::isError($e) && isset($valores[$c])) {
            $e->setValue(var_escapa($valores[$c]));
        }
    }

}


/**
 * Retorna un elemento de un formulario HTML_QuickForm buscando de
 * requerirse dentro de grupos.
 *
 * @param object $form         HTML_QuickForm
 * @param array  $nom          Nombre del elemento buscado
 * @param array  $yaanalizados No revisar elementos/grupos con estos nombres
 *
 * @return object o null si no lo encuentra
     */
function toma_elemento_recc($form, $nom, $yaanalizados = array())
{
    assert(is_array($yaanalizados));

    $le = $form->_elements; // elementIndex no funciona en group
    foreach ($le as $key => $el) {
        $nomel = $el->getName();
        if ($nom == $nomel) {
            return $el;
        }
        if (!in_array($nomel, $yaanalizados)) {
            $yaanalizados[] = $nomel;
            if ($el->_type == 'group') {
                $group  =& $form->getElement($nomel);
                $r =& toma_elemento_recc($group, $nom, $yaanalizados);
                if ($r != null) {
                    return $r;
                }
            }
        }
    }

    return null;
}


/**
 * Pone valores por defecto en una pestaña, para ser llamado desde
 * formularioValores
 *
 * PORHACER: Que no use el booleanFields sino que examine tipos de
 * variable global
 *
 * @param object $d    DB_DataObject
 * @param object $form HTML_QuickForm
 * @param bool   $merr Si debe mostrar errores
 *
 * @return void
 */
function valores_pordefecto_form($d, $form, $merr = true)
{
    //echo "OJO valores_pordefecto_form(d, {$d->__table}, form)<br>";
    foreach ($d->fb_fieldsToRender as $c) {
        //echo "<hr>OJO c=$c<br>";
        $cq = toma_elemento_recc($form, $c);
        if (($cq == null || PEAR::isError($cq)) && $merr) {
            echo_esc(
                sprintf(
                    _("Error: No se encontró elemento %s en el formulario %s")
                    . "<br>", $c, $d->__table
                )
            );
        } else if ($cq != null && is_callable(array($cq, 'setValue'))) {
            //echo "OJO setValue callable<br>";
            if (isset($d->fb_booleanFields)
                && in_array($c, $d->fb_booleanFields)
            ) {
                //echo "OJO booleano<br>";
                if ((!isset($d->$c) || $d->$c===0 || $d->$c==='f')) {
                    $cq->setValue(0);
                } else {
                    $cq->setValue(1);
                }
            } else {
                //echo "OJO poniendo valor {$d->$c}<br>";
                $cq->setValue($d->$c);
            }
        }
    }
}


/**
 * Identificación de departamento elegido por usuario.
 *
 * @param object $form Formulario
 *
 * @return string id de departamento
     */
function ret_id_departamento($form)
{
    $ndepartamento = null;
    if (isset($form->_submitValues['id_departamento'])) {
        $ndepartamento = (int)$form->_submitValues['id_departamento'] ;
    } else if (isset($_SESSION['id_departamento'])) {
        $ndepartamento = $_SESSION['id_departamento'] ;
    }
    return $ndepartamento;
}


/**
 * Identificación del municpio elegido por usuario.
 *
 * @param object $form Formulario
 *
 * @return string id de municipio
     */
function ret_id_municipio($form)
{

    $nmunicipio = null;
    if (isset($form->_submitValues['id_municipio'])) {
        $nmunicipio = (int)$form->_submitValues['id_municipio'] ;
    } else if (isset($_SESSION['id_municipio'])) {
        $nmunicipio = $_SESSION['id_municipio'] ;
    }
    return $nmunicipio;
}


/**
 * Identificación de la clase geográfica elegida por usuario
 *
 * @param object $form Formulario
 *
 * @return string id de clase
     */
function ret_id_clase($form)
{
    $nclase = null;
    if (isset($form->_submitValues['id_clase'])) {
        $nclase= (int)$form->_submitValues['id_clase'] ;
    }
    return $nclase;
}


/* -------- HTML */

/**
 * Presenta encabezado
 *
 * @param string $titulo   Título
 * @param string $cabezote Imagen de Cabezote
 *
 * @return void
     */
function encabezado_envia($titulo = null, $cabezote = '')
{
    // http://www.w3.org/TR/html5-diff/
    echo '<' . '!doctype html>
<html>
<head>
<meta charset = "UTF-8">
';
    if (isset($titulo)) {
        echo '  <title>' . htmlentities($titulo, ENT_COMPAT, 'UTF-8') . '</title>';
    }
    echo '<link rel = "stylesheet" type = "text/css" href = "estilo.css" />
<!--Fuentes de dominio publico. Sin garantias. 2004-->
<!-- http://sivel.sf.net -->
<script language = "JavaScript">
<!--
function envia(que){
    document.forms[0]._qf_default.value = que;
    document.forms[0].submit();
}
// -->
<!-- Contador por: Nannette Thacker -->
<!-- http://www.shiningstar.net -->
<!-- Original by :  Ronnie T. Moore -->
<!-- Web Site:  The JavaScript Source -->
<!-- Use one function for multiple text areas on a page -->
<!-- Limit the number of characters per textarea -->
<!-- Begin
function textCounter(field, cntfield, maxlimit)
{
    if (field.value.length > maxlimit) // if too long...trim it!
        field.value = field.value.substring(0, maxlimit);
    // otherwise, update \'characters left\' counter
    else
        cntfield.value = maxlimit - field.value.length;
}
//  End -->
<' . '/script>';

    if ($cabezote != '' && file_exists($cabezote)) {
        // http://www.php.net/manual/en/function.fopen.php
        $rh = fopen($cabezote, "rb");
        while ($rh != false && !feof($rh)) {
            $html_l = fread($rh, 1024);
            echo $html_l;
        }
        fclose($rh);
    } else {
        $f = isset($GLOBALS['fondo']) ? $GLOBALS['fondo'] : '';
        echo '</' . 'head><' . 'body background = "' . $f . '">';
    }
}


/**
 * Presenta pie de página general en captura
 *
 * @param string $pie Archivo con pie de página por mostrar
 *
 * @return void
     */
function pie_envia($pie = '')
{
    if ($pie != '' && file_exists($pie)) {
        $rh = fopen($pie, "rb");
        while ($rh != false && !feof($rh)) {
            $html_l = fread($rh, 1024);
            echo $html_l;
        }
        fclose($rh);
    } else {
        echo '</' . 'body></' . 'html>';
    }
}



/**
 * Genera enlace a un caso (reporte general por abrir en otra ventana)
 *
 * @param integer $id Identificación del caso
 *
 * @return string Cadena HTML con enlace a caso
     */
function enlace_caso_html($id)
{
    return "<a target='_otro' href='consulta_web.php?" .
        "_qf_consultaWeb_consulta=Consulta" .
        "&mostrar=general&id_casos=$id" .
        "&caso_memo=1&caso_fecha=1&m_ubicacion=1" .
        "&m_victimas=1&m_presponsables=1&m_tipificacion=1" .
        "'>$id</a>";
}

/**
 * Retorna enlaces a casos donde se referencie a una persona
 *
 * @param object  &$db      Conexión a base de datos
 * @param integer $idcaso   Identificación del caso (por excluir)
 * @param integer $idp      Identificación de la persona
 * @param string  &$comovic Colchon para retornar URLs como víctima
 * @param string  &$comofam Colchon para retornar URLs como familiar
 *
 * @return void Llena $comovic y $comofan con enlaces a casos donde
 * se referencia idp como víctima y familiar respectivamente
 * (excepto idcaso)
     */
function enlaces_casos_persona_html(
    &$db, $idcaso, $idp, &$comovic, &$comofam
) {
    $q = "SELECT id_caso FROM victima WHERE id_persona = '$idp'";
    $r = hace_consulta($db, $q);
    $campos = array();
    $sep = "";
    while ($r->fetchInto($campos)) {
        $idv = $campos[0];
        if ($idv != $idcaso) {
            $comovic .= $sep . enlace_caso_html($idv);
            $sep = ", ";
        }
    }

    $q = "SELECT id_caso FROM persona_trelacion, victima
        WHERE persona1 = id_persona AND persona2 = '$idp'";
    $r = hace_consulta($db, $q);
    $campos = array();
    $sep = "";
    while ($r->fetchInto($campos)) {
        $idv = $campos[0];
        if ($idv != $idcaso) {
            $comofam .= $sep . enlace_caso_html($idv);
            $sep = ", ";
        }
    }
}


/**
 * Retorna enlaces a casos donde se referencie a una víctima colectiva
 *
 * @param object  &$db      Conexión a base de datos
 * @param integer $idcaso   Identificación del caso (por excluir)
 * @param integer $idc      Identificación del grupo de personas
 * @param string  &$comovic Colchon para retornar URLs como víctima
 *
 * @return void Llena $comovic con enlaces a casos donde se referencia
 *   idc como víctima y familiar respectivamente (excepto idcaso)
     */
function enlaces_casos_grupoper_html(&$db, $idcaso, $idc, &$comovic)
{
    $q = "SELECT id_caso FROM victimacolectiva WHERE id_grupoper = '$idc'";
    $r = hace_consulta($db, $q);
    $campos = array();
    $sep = "";
    while ($r->fetchInto($campos)) {
        $idv = $campos[0];
        if ($idv != $idcaso) {
            $comovic .= $sep . enlace_caso_html($idv);
            $sep = ", ";
        }
    }
}

/**
 * Convierte valores de un arreglo a entidades HTML aptas para mostrar en
 * web
 *
 * @param array $ar  Arreglo por convertir
 * @param array $enc Codificación
 *
 * @return array Arreglo convertido
 */
function htmlentities_array($ar, $enc = 'UTF-8')
{
    $ars = array();
    foreach ($ar as $l => $v) {
        if (is_array($v)) {
            $ars[$l] = htmlentities_array($v, $enc);
        } else {
            $ars[$l] = htmlentities($v, ENT_COMPAT, $enc);
        }
    }
    return $ars;
}


/* -------- BASE DE DATOS Y CONSULTAS*/

/**
 * Muestra mensaje escapandolo antes para presentar en navegador y termina
 *
 * @param string $mens Mensaje por mostrar
 *
 * @return void
     */
function die_esc($mens)
{
    die(htmlentities($mens, ENT_COMPAT, 'UTF-8'));
}

/**
 * Retorna HTML con enlace para editar un caso
 *
 * @param int $idc Código del caso
 *
 * @return string cadena HTML con enlace para editar caso $idc
 */
function enlace_edita($idc) 
{
    $idn = (int)$idc;
    return "<a href='captura_caso.php?modo=edita&id=$idn'>$idn</a>";
}

/**
 * Muestra mensaje escapandolo antes para presentar en navegador
 *
 * @param string $mens Mensaje por mostrar
 * @param bool   $nl   Nueva linea tras mensaje
 *
 * @return void
 */
function echo_esc($mens, $nl = true)
{
    echo htmlentities($mens, ENT_COMPAT, 'UTF-8');
    if ($nl) {
        echo "<br>\n";
    }
}

/**
 * Muestra mensaje escapandolo y después mensaje recordando actualizar
 *
 * @param string $mens Mensaje por mostrar
 *
 * @return void
 */
function die_act($mens)
{
    echo_esc($mens);
    echo "<br>" . _("&iquest;Ya") . " <a href='actualiza.php'>"
            . _("actualiz&oacute;") . "</a> " . _("y regener&oacute; esquema?");

    exit(1);
}

/**
 * Si el objeto es error, presenta mensaje y termina.
 *
 * @param object $do  Objeto por examinar
 * @param string $msg Mensaje adicional por mostrar en caso de error
 *
 * @return void
 */
function sin_error_pear($do, $msg = "")
{
    if (PEAR::isError($do)) {
        debug_print_backtrace();
        die_act(
            "Error " . trim($msg . " ") . $do->getMessage() . 
            " - " . $do->getUserInfo()
        );
    }
}

/**
 * Ejecuta consulta $q
 *
 * @param handle &$db          Conexióna BD
 * @param string $q            Consulta
 * @param bool   $finenerror   Indica si termina en caso de error o no
 * @param bool   $muestraerror Indica si debe mostrar mensaje de error
 *
 * @return resultado de la consulta. si hay errores los presenta.
     */
function hace_consulta(&$db, $q, $finenerror = true, $muestraerror = true)
{
    $res = $db->query($q);
    if (PEAR::isError($res)) {
        if ($muestraerror) {
            echo_esc(
                _("Error") . ": " .
                $res->getMessage() . " - " .  $res->getUserInfo()
            );
            echo_esc($q);
        }
        if ($finenerror) {
            echo _("&iquest;Ya") . " <a href='actualiza.php'>"
                . _("actualiz&oacute;") . "</a> "
                . _("y regener&oacute; esquema?");
            exit(1);
        }
    }
    return $res;
}

/**
 * Ejecuta consulta $q que debe retornar exactamente un resultado
 *
 * @param handle &$db Conexióna BD
 * @param string $q   Consulta
 * @param bool   $t   Termina?
 *
 * @return primer campo del resultado de la consulta. 
 *      Si no hay uno retorna -1 o termina
 */
function consulta_uno(&$db, $q, $t = true)
{
    $res = hace_consulta($db, $q);
    if (($nr = $res->numRows()) != 1) {
        if ($t) { 
            die_esc(
                sprintf(
                    _("Se esperaba un resultado y no %s de consulta \"%s\""),
                    $nr, $q
                )
            );
        }
        return -1;
    }
    $reg = array();
    $res->fetchInto($reg);

    return $reg[0];
}


/**
 * Agrega condición a WHERE en un SELECT de SQL
 *
 * @param unknown &$db   Conexión a base de datos
 * @param string  &$w    cadena con WHERE que se completa
 * @param string  $n     nombre de campo
 * @param string  $v     valor esperado
 * @param string  $opcmp operador de comparación por usar.
 * @param string  $con   con
 *
 * @return void
     */
function consulta_and(&$db, &$w, $n, $v, $opcmp = '=', $con='AND')
{
    if (!isset($v) || $v === '' || $v === ' ' || ord($v)==32) {
        return;
    }
    if ($w != "") {
        $w .= " $con";
    }
    $w .= " " . $n . $opcmp . "'".var_escapa($v, $db)."'";
}


/**
 * Como la función anterior sólo que el valor no lo pone entre apostrofes
 * y supone que ya viene escapado el valor $v
 *
 * @param string &$w    cadena con WHERE que se completa
 * @param string $n     nombre de campo
 * @param string $v     valor esperado
 * @param string $opcmp operador de comparación por usar.
 * @param string $con   con
 *
 * @return void
     */
function consulta_and_sinap(&$w, $n, $v, $opcmp = '=', $con = "AND")
{
    if ($w != "") {
        $w .= " " . $con;
    }
    $w .= " " . $n . $opcmp . $v;
}

/* */

/**
 * Agrega a expresión WHERE los que corresponden a una tabla
 * uno a muchos (uno con tabla de llave $llave_prin con el registro
 * $id_prin). Disyunción de los registros elegidos --de la tabla
 * $ntabla con llave $llave_ntabla y valor $id_prin).
 *
 * @param string  &$w           cadena con WHERE que se completa
 * @param string  &$t           Tablas
 * @param string  $ntabla       Nombre de tabla
 * @param string  $gcon         Operador
 * @param string  $llave_ntabla Llave de tabla
 * @param unknown $id_prin      Campo que referencia a otra tabla
 * @param string  $llave_prin   Llave en otra tabla
 *
 * @return void
     */
function consulta_or_muchos(&$w, &$t, $ntabla, $gcon = "AND",
    $llave_ntabla = 'id_caso', $id_prin = -1, $llave_prin = 'caso.id'
) {
    assert(
        (is_array($llave_ntabla)
        && is_array($id_prin) && is_array($llave_prin))
        || (!is_array($llave_ntabla)
        && !is_array($id_prin) && !is_array($llave_prin))
    );

    if (!is_array($llave_ntabla)) {
        $llave_ntabla = array('0' => $llave_ntabla);
        $id_prin = array('0' => $id_prin);
        $llave_prin = array('0' => $llave_prin);
    }

    $d=& objeto_tabla($ntabla);
    $db = $d->getDatabaseConnection();
    $ks = $d->keys();
    foreach ($llave_ntabla as $il => $vl) {
        $d->$vl = var_escapa($id_prin[$il]);
    }
    if ($d->find()>0) {
        if (strstr($t, $ntabla)==false) {
            $t .= ", " . $ntabla;
            foreach ($llave_ntabla as $il => $vl) {
                consulta_and_sinap(
                    $w, var_escapa($ntabla, $db). "." .
                    var_escapa($vl),
                    var_escapa($llave_prin[$il]),
                    "=", $gcon
                );
            }
        }
        $w3="";
        while ($d->fetch()) {
            $w2="";
            foreach ($ks as $llave) {
                if (!in_array($llave, $llave_ntabla)) {
                    consulta_and(
                        $db, $w2, "$ntabla . $llave",
                        var_escapa($d->$llave, $db), '=', 'AND'
                    );
                }
            }
            if ($w2!="") {
                $w3 = $w3=="" ? "($w2)" : "$w3 OR ($w2)";
            }
        }
        if ($w3!="") {
            $w .= " AND ($w3)";
        }
    }
}


/**
 * Agrega orden a una consulta de casos $q.  El criterio por
 *  el cual se ordena es $pOrdenar
 *
 * @param string &$q       Consulta
 * @param string $pOrdenar Criterio
 *
 * @return void
     */
function consulta_orden(&$q, $pOrdenar)
{
    if ($pOrdenar == 'ubicacion') {
        $nq = 'SELECT sub.*, departamento.nombre as dep, ' .
            'municipio.nombre as mun, clase.nombre as cla FROM ' .
            'departamento, municipio, clase, ubicacion, (' .
            $q . ') AS sub ' .
            'WHERE (' .
            'clase.id=ubicacion.id_clase AND ' .
            'clase.id_municipio=ubicacion.id_municipio AND ' .
            'clase.id_departamento=ubicacion.id_departamento AND ' .
            'municipio.id=ubicacion.id_municipio AND ' .
            'municipio.id_departamento=ubicacion.id_departamento AND ' .
            'departamento.id=ubicacion.id_departamento AND ' .
            'ubicacion.id_caso=sub.id) ' .
            'UNION SELECT sub.*, departamento.nombre AS dep, ' .
            "municipio.nombre AS mun, '' AS cla FROM departamento, " .
            'municipio, ubicacion, (' . $q . ') AS sub ' .
            'WHERE (municipio.id=ubicacion.id_municipio AND ' .
            'departamento.id=ubicacion.id_departamento AND ' .
            'municipio.id_departamento=ubicacion.id_departamento AND ' .
            'ubicacion.id_clase IS NULL AND ' .
            'ubicacion.id_caso=sub.id) ' .
            'UNION SELECT sub.*, departamento.nombre as dep, ' .
            "'' as mun, '' as cla FROM departamento, " .
            'ubicacion, (' . $q . ') AS sub WHERE ' .
            '(departamento.id=ubicacion.id_departamento AND ' .
            'ubicacion.id_clase IS NULL AND ' .
            'ubicacion.id_municipio IS NULL AND ' .
            'ubicacion.id_caso=sub.id) ' .
            "UNION SELECT sub.*, '' AS dep, " .
            "'' AS mun, '' AS cla FROM  " .
            'ubicacion, (' . $q . ') AS sub WHERE ' .
            '(ubicacion.id_clase IS NULL AND ' .
            'ubicacion.id_municipio IS NULL AND ' .
            'ubicacion.id_departamento IS NULL AND ' .
            'ubicacion.id_caso=sub.id) ' .
            "UNION SELECT sub.*, '' AS dep, " .
            "'' AS mun, '' AS cla FROM  " .
            '(' . $q . ') AS sub WHERE ' .
            '(sub.id NOT IN (SELECT id_caso FROM ubicacion)) ' .
            'ORDER by dep, mun, cla;';
        $q = $nq;
    } elseif ($pOrdenar == 'fecha') {
        $q .= ' ORDER by caso.fecha';
    } elseif ($pOrdenar == 'codigo') {
        $q .= ' ORDER by caso.id';
    } elseif ($pOrdenar != '' && isset($GLOBALS['misc_ordencons'])) {
        foreach ($GLOBALS['misc_ordencons'] as $k => $f) {
            if (is_callable($f)) {
                call_user_func_array(
                    $f,
                    array(&$q, $pOrdenar)
                );
            } else {
                echo_esc(
                    sprintf(
                        _("Falta %s de misc_ordencons[%s]"), $f, $k
                    )
                );
            }
        }
    }

}


/**
 * Retorna cadena con lista de relacionados de una tabla
 *
 * @param string $tabla       Tabla inicial
 * @param string $llave       Arreglo que relaciona nombres de campos llave
 *  de tabla con valores, de forma que ubiquen los registrso en tabla
 *  por relacionar.
 * @param string $enlace      Campo que enlaza 'tabla' con otra
 * @param string $csep        Separador entre valores (campo nombre) de la
 *  tabla relacionada
 * @param string $csepi       Prefijo por poner a cadena si hay valores
 * @param string $connombre   Relacioan con nombre en otro caso con ids.
 * @param string $camponombre Campo con nombre en tabla
 *
 * @return string cadena con valores
     */
function lista_relacionados($tabla, $llave,
    $enlace, $csep = '; ', $csepi = '', $connombre = true,
    $camponombre = 'nombre'
) {
    assert(is_array($llave));
    $do = objeto_tabla($tabla);
    foreach ($llave as $nc => $vc) {
        $do->$nc = $vc;
    }
    $do->orderBy($enlace);
    $do->find();
    $r = "";
    $lsr = array();
    while ($do->fetch()) {
        $dr= $do->getLink($enlace);
        if (PEAR::isError($dr) || $dr == null) {
            echo_esc(
                sprintf(
                    _("No hay campo %s en tabla %s"), $enlace, $do->__table
                )
            );
            break;
        }
        //echo "getlink";
        if ($connombre) {
            $lsr[$dr->$camponombre] = $dr->$camponombre;
        } else {
            $lsr[$dr->id] = $dr->id;
        }
        $dr->free();
    }
    $do->free();
    sort($lsr);
    $sep = $csepi;
    foreach ($lsr as $nom) {
        $r .= $sep . trim($nom);
        $sep = $csep;
    }
    unset($lsr);
    return $r;
}


/**
 * Recibe un DB_DataObject y retorna la sentencia SQL INSERT que
 insertaría los datosShort description for function
 *
 * @param handle &$db   Conexión a BD
 * @param object $d     DataObject
 * @param array  $delta Cambio en numeración
 *
 * @return string  instrucción SQL
     */
function inserta_sql(&$db, $d, $delta = null)
{
    $ca = $d->table();
    $ncol = $nval = $sep = "";
    /** Algunas porciones fueron tomadas de DataObject.php de Pear */
    foreach ($ca as $l => $v) { // $v  & DB_DATAOBJECT_BOOL
        // $v & DB_DATAOBJECT_STR
        $ncol .= $sep . $l;
        $val = "";
        if (strtolower($d->$l)==='null' || !isset($d->$l)) {
            $val = 'null';
        } else if (isset($delta) && isset($delta[$l])) {
            $val = "'".($d->$l+$delta[$l])."'";
        } else {
            $val = "'".var_escapa($d->$l, $db)."'";
        }
        $nval .= $sep . $val;
        $sep = ', ';
    }
    $table = $d->tableName();
    $q = "INSERT INTO {$table} ({$ncol}) VALUES ($nval);\n";

    return $q;
}


/**
 * Retorna arreglo de tablas que referencian a $tabla
 *
 * @param handle $base  Conexión
 * @param string $tabla Tabla
 *
 * @return array Tablas que referencian a $tabla
     */
function ref_dataobject($base, $tabla)
{
    $l = $GLOBALS['_DB_DATAOBJECT']['LINKS'][$base];
    $r = array();
    foreach ($l as $nt => $enl) {
        foreach ($enl as $campo => $ref) {
            $p = explode(":", $ref);
            if ($p[0]===$tabla) {
                if (!isset($r[$nt])) {
                    $r[$nt] = $campo;
                }
            }
        }
    }
    return $r;

}


/**
 * Si hace falta, agrega el funcionario a quienes editaron/vieron
 * el caso
 *
 * @param integer $idcaso Id. del caso
 *
 * @return void
     */
function caso_funcionario($idcaso)
{
    if ($idcaso == $GLOBALS['idbus']) {
        return;
    }
    if (!isset($_SESSION['id_funcionario'])
        || $_SESSION['id_funcionario'] == ''
    ) {
        die_esc(_("No es funcionario"));
    }
    $dfc = objeto_tabla('caso_funcionario');
    $dfc->id_caso = $idcaso;
    $dfc->id_funcionario = $_SESSION['id_funcionario'];
    if ($dfc->find()<1) {
        $dfc->fechainicio = @date('Y-m-d H:i');
        $dfc->insert();
    }
}


/**
 * Escapa el valor de una variable o de valores en un arreglo.
 * Si $v es null retorna ''
 * Agradecimientos por correciones a garcez@linuxmail.org
 *
 * @param string  $v       Nombre de variable POST
 * @param handle  &$db     Conexión a BD.
 * @param integer $maxlong Longitud máxima
 *
 * @return string Cadena escapada
     */
function var_escapa($v, &$db = null, $maxlong = 1024)
{
    if (isset($v)) {
        if (is_array($v)) {
            $r = array();
            foreach ($v as $k => $e) {
                $r[$k] = var_escapa($e, $db, $maxlong);
            }
            return $r;
        } else {
            /** Evita buffer overflows */
            $nv = substr($v, 0, $maxlong);

            /** Evita falla %00 en cadenas que vienen de HTTP */
            $p1=str_replace("\0", ' ', $v);

            /** Evita XSS */
            $p2=htmlspecialchars($p1);

            /** Evita inyección de código SQL */
            if (isset($db) && $db != null && !PEAR::isError($db)) {
                $p3 = $db->escapeSimple($p2);
            } else {
                // Tomado de librería de Pear DB/pgsql.php
                $p3 = (!get_magic_quotes_gpc())?str_replace(
                    "'", "''",
                    str_replace('\\', '\\\\', $p2)
                ):$p2;
                //$p3=(!get_magic_quotes_gpc())?addslashes($p2):$p2;
            }

            return $p3;
        }
    } else {
        return '';
    }
}

/**
 * Retorna una variable enviada por método POST tras escaparla
 *  para hacer consultas con DB
 *
 * @param string  $nv      Nombre de variable POST
 * @param handle  $db      Conexión a BD.
 * @param integer $maxlong Longitud máxima
 *
 * @return mixed Cadena escapada
     */
function var_post_escapa($nv, $db = null, $maxlong = 1024)
{
    if (isset($_POST[$nv])) {
        return var_escapa($_POST[$nv], $db, $maxlong);
    } else {
        return '';
    }
}


/**
 * Retorna una variable enviada por método POST o por GET tras escaparla
 * para hacer consultas con DB
 *
 * @param string  $nv      Nombre de variable
 * @param handle  $db      Conexión a BD.
 * @param integer $maxlong Longitud máxima
 *
 * @return mixed Cadena escapada
     */
function var_req_escapa($nv, $db = null, $maxlong = 1024)
{
    if (isset($_REQUEST[$nv])) {
        return var_escapa($_REQUEST[$nv], $db, $maxlong);
    } else {
        return '';
    }
}


/**
 * Convierte un arreglo para fechas a una fecha.
 * Mes y día pueden ser '' y supone valores por defecto (1).
 *
 * @param array $f     Arreglo con indices Y, M, d, el valor $f['Y'] 
 *                      no puede ser ''
 * @param bool  $desde Si es cierto completa suponiendo que es una fecha Desde,
 *                     de lo contrario completa como fecha Hasta.
 *
 * @return string Fecha
     */
function arr_a_fecha($f, $desde = true)
{
    assert(isset($f['Y']));
    assert($f['Y'] != '');

    $ultimo = array(1 => 31, 2 => 28, 3 => 31, 4 => 30, 5 => 31,
        6 => 30, 7 => 31, 8 => 31, 9 => 30, 10 => 31, 11 => 30, 12 => 31
    );

    $mes = '';
    if (isset($f['M']) && $f['M'] != '' && (int)$f['M'] > 0) {
        $mes = $f['M'];
    } else if (isset($f['m']) && $f['m'] != '' && (int)$f['m'] > 0) {
        $mes = $f['m'];
    }
    if ($mes == '') {
        $mes = $desde ? 1 : 12;
    }
    $dia = '';
    if (isset($f['d']) && $f['d'] != '' && (int)$f['d'] > 0) {
        $dia = $f['d'];
    }
    if ($dia == '') {
        $dia = $desde ? 1 : $ultimo[(int)$mes];
    }

    $dcaso = objeto_tabla('caso');
    $fb =& DB_DataObject_FormBuilder::create($dcaso);

    $ft = array('Y' => (int)$f['Y'],
        'M' => (int)$mes,
        'd' => (int)$dia
    );

    return $fb->_array2date($ft);
    // Ojala fuera DB_DataObject_Formbuilder::array2date($ft)
}


/**
 * Convierte fecha en arreglo año, mes, dia
 *
 * @param array $f Fecha por convertir en formato AAAA-MM-DD
 *
 * @return array 'Y' => año, 'M' => mes, 'd' => día
     */
function fecha_a_arr($f)
{
    $ar = array();
    $pf = explode('-', $f);
    assert(count($pf) == 3);
    $ar['Y'] = (int)$pf[0];
    $ar['M'] = (int)$pf[1];
    $ar['d'] = (int)$pf[2];
    return $ar;
}

/**
 * Retorna identificación del rango de edad al que pertenece la edad $er
 *  o 0 si a ninguno.
 *
 * @param integer $er edad
 *
 * @return integer Id del rango
     */
function rango_de_edad($er)
{
    //echo "OJO rango_de_edad($er)<br>";
    $e = (int)$er;

    $do = objeto_tabla('rangoedad');
    $do->find();
    $res = DataObjects_Rangoedad::idSinInfo();
    while ($do->fetch()) {
        if ($do->limiteinferior <= $er && $er <= $do->limitesuperior) {
            $res = $do->id;
        }
    }

    return $res;
}


/**
 * Verifica si una edad está en un rango
 *
 * @param int $e Edad
 * @param int $r Identificación del rango en BD.
 *
 * @return boolean true si la edad está en el rango.
     */
function verifica_edad_y_rango($e, $r)
{
    $do = objeto_tabla('rangoedad');
    $do->get((int)$r);
    if (PEAR::isError($do)) {
        die_esc(
            _("Identificación de rango desconocida") . " (" .
            $do->getMessage() . " - " . $do->getUserInfo() . ")"
        );
    }
    $mine = $do->limiteinferior;
    $maxe = $do->limitesuperior;
    if ($e < $mine || $e > $maxe) {
        return false;
    }
    return true;
}


/**
 * Agrega al formulario un control para evitar CSRF
 *
 * @param object &$form Formulario
 *
 * @return void
     */
function agrega_control_CSRF(&$form)
{
    $_SESSION['sin_csrf'] = mt_rand(0, 1000);
    $form->addElement('hidden', 'evita_csrf', $_SESSION['sin_csrf']);
}


/**
 * Verifica control CSRF añadido por agrega_control_CSRF al formulario
 *
 * @param array $valores Valores recibidos de formulario
 *
 * @return void
     */
function verifica_sin_CSRF($valores)
{
    if (!isset($_SESSION['sin_csrf'])) {
        die_esc(_("Debería existir variable para evitar CSRF en sesión."));
    }
    if (!isset($valores['evita_csrf'])
        || $valores['evita_csrf'] != $_SESSION['sin_csrf']
    ) {
        die_esc(
            _("Datos enviados no pasaron verificación CSRF") . " (" .
            $_SESSION['sin_csrf'] . ", " . (int)$valores['evita_csrf'] . ")"
        );
    }
    return true;
}


/**
 * Retorna cantidad de años entre la fecha de nacimiento y
 * la fecha del hecho.
 *
 * @param integer $anionac   Año nacimiento
 * @param integer $aniohecho Año del hecho
 * @param integer $mesnac    Mes de nacimiento
 * @param integer $meshecho  Mes del hecho
 * @param integer $dianac    Día de nacimiento
 * @param integer $diahecho  Día del hecho
 *
 * @return integer Edad de persona en fecha del hecho
 **/
function edad_de_fechanac($anionac, $aniohecho, $mesnac = null,
    $meshecho = null, $dianac = null, $diahecho = null
) {
    //echo "OJO edad_de_fechanac anionac=$anionac, aniohecho=$aniohecho, "
    // . "mesnac=$mesnac, meshecho=$meshecho, dianac=$dianac<br>";
    if ($anionac == '') {
        return -1;
    }
    $na = $aniohecho-$anionac;
    if ($mesnac != null && $meshecho != null && $mesnac <= $meshecho) {
        if ($mesnac < $meshecho || ($dianac != null && $diahecho != null
            && $dianac < $diahecho)
        ) {
            $na--;
        }
    }
    return $na;
}


/**
 * Agrega una nueva tabla al listado $t
 *
 * @param string &$t Listado de tablas separadas por ,
 * @param string $nt Nueva tabla por agregar si falta
 *
 * @return void Modifica $t de requerirse
     */
function agrega_tabla(&$t, $nt)
{
    $at = explode(',', $t);
    if (!in_array($nt, $at)) {
        $at[] = $nt;
    }
    $t = implode(",", $at);
}



/**
 * Convierte el valor del campo de un DataObject al tipo especificado.
 *
 * @param object  &$do   DataObject
 * @param string  $campo Campo cuyo valor se extraerá
 * @param integer $tipo  Valores numérico como el empleado por DB_DataObject
 *
 * @return integer Valor del campo del objeto recibido convertido al tipo
 */
function convierte_valor(&$do, $campo, $tipo)
{
    //echo "convierte_valor(do, $campo, $tipo)<br>";
    if ($tipo & 1) { // int
        return (int)$do->$campo;
    } else {
        return $do->$campo;
    }
}


/**
 * Asigna un campo de un DataObject con el valor recibido del formulario
 *
 * @param array   $valor  Valor por asignar
 * @param object  $rel    Tabla 
 * @param object  $campo  Campo de tabla $tabla
 * @param array   &$estbd Estructura de base sacada de .ini.  Si es null esta
 *                        función la llena
 *
 * @return Valor asignable a un campo $campo del DataObject de tabla $rel
 */
function valor_fb2do($valor, $rel, $campo, &$estbd)
{
    //echo "OJO valor_fb2do($valor, $rel, $campo, estbd)<br>";
    if ($estbd== null || !isset($estbd)) {
        $estbd = parse_ini_file(
            $_SESSION['dirsitio'] . "/DataObjects/" .
            $GLOBALS['dbnombre'] . ".ini",
            true
        );
    }
    $tipo = $estbd[$rel][$campo];
    //echo "OJO valor_fb2do, tipo=$tipo";
    if ($tipo & 1) {
        if (isset($valor)) {
            $ret = (int)$valor;
        } else {
            $ret = null;
        }
    } else if ($tipo == 18 || $tipo == 146) {
        $ret = ($valor == 1) ? 't' : 'f';
    } else if ($tipo & 6) {
        if (is_array($valor)) {
            $ret = arr_a_fecha($valor);
        } else {
            $ret = (string)$valor;
        }
    } else if ($tipo & 2) {
        $ret = $valor;
    } else {
        $ret = $valor;
    }
    //echo "OJO ret=$ret";

    return $ret;
}


/**
 * Prepara una consulta que coincida con los datos de una tabla.
 *
 * @param object  &$duc    Objeto DataObject del cual se formará consulta
 * @param string  $rel     Relación
 * @param string  $bas     Tabla básica
 * @param string  $crelbas Relación con tabla básica
 * @param boolean $enbas   En tablas básicas
 * @param array   $otrast  Otras tablas que se relacionan con $duc
 * @param string  $iotrast Campo por el cual se relacionan las de $otrast
 * @param array   $nonulos Campos que no pueden ser nulos
 * @param string  $irelot  Campo con identificación en otras
 * @param array   $masenl  Tablas de algunos campos que pueden ser sin info.
 * @param array   $tab     Estructura de base sacada de .ini
 * @param array   $fignora Si un campo de tipo bool es false ignora
 *
 * @return string Consulta SQL
 */
function prepara_consulta_con_tabla(&$duc, $rel, $bas, $crelbas, $enbas,
    $otrast = array(), $iotrast = '', $nonulos = array(), $irelot = "id",
    $masenl = array(), $tab = null, $fignora = true
) {
    //echo "OJO prepara_consulta_con_tabla(duc, $rel, $bas, $crelbas, "
    // . "$enbas, $otrast,  $iotrast,  $nonulos,  $irelot, $masenl,  "
    // . "$tab) <br>";

    if ($tab == null || !isset($tab)) {
        $tab = parse_ini_file(
            $_SESSION['dirsitio'] . "/DataObjects/" .
            $GLOBALS['dbnombre'] . ".ini",
            true
        );
    }
    $w2 = "";
    $cpm = array();
    foreach ($duc->fb_fieldsToRender as $k => $campo) {
        if ($rel != "caso_fotra" || $campo != "fecha") {
            $cpm[$k] = $campo;
        }
    }
    $csininf = @$duc->camposSinInfo();
    foreach ($cpm as $campons) {
        $campo = (string)$campons;
        //echo "OJO campo = $campo, ";
        $tipo = $tab[$rel][$campo];
        $vdc = (string)$duc->$campo;
        //echo "OJO tipo=$tipo, valor=" .  $duc->$campo . ", vdc=$vdc<br>";
        $ignora = false;
        $ignora |= $campo == 'id_caso';
        $ignora |= $vdc === '';
        $ignora |= $vdc == null;

        $ignora |= $tipo == 18 && $duc->$campo == 'f' && $fignora;
        /// Convención interna para fechas si es anio_min - 1 ignorar
        $ignora |= $tipo == 134
            && substr($vdc, 0, 4) == ($GLOBALS['anio_min'] - 1);
        $valor = $ignora ? '' : convierte_valor($duc, $campo, $tipo);
        $ignora |= (is_array($csininf) && isset($csininf[$campo])
            && $csininf[$campo] == $valor
        );
        //echo "campo=$campo, duc->campo=" . $duc->$campo . ", valor=$valor<br>";
        if ($campo == $crelbas
            && is_callable(array("DataObjects_$bas", 'idSinInfo'))
        ) {
            $sini = call_user_func(
                array("DataObjects_$bas",
                'idSinInfo')
            );
            if ($sini == $valor) {
                $ignora = true;
            }
        }
        $ignora |= ($valor == 0 && is_array($nonulos)
            && in_array($campo, $nonulos));
        if (!$ignora) {
            if (($tipo & 2) && $tipo != 134 && $tipo !=18) {
                // Cadena que no es fecha
                if (trim($valor) != '*') {
                    consulta_and(
                        $db, $w2, "$rel.$campo", "%" .trim($valor) . "%",
                        ' ILIKE ', 'AND'
                    );
                }
            } else {
                consulta_and($db, $w2, "$rel.$campo", $valor, '=', 'AND');
            }
            //echo "OJO w2=$w2,<br>";
        }
    }
    if (isset($otrast) && is_array($otrast) && $iotrast != '') {
        foreach ($otrast as $ot) {
            consulta_or_muchos(
                $w2, $t, $ot,
                'AND', $iotrast,
                var_escapa($duc->$irelot, $db), $rel . "." . $irelot
            );
        }
    }

    if ($enbas && $bas != '' && $crelbas != '') {
        $du=& objeto_tabla($bas);
        $du->get((int)($duc->$crelbas));
        //echo "OJO bas=$bas<br>";
        foreach ($tab[$bas] as $campo => $tipo) {
            //echo "OJO campo = $campo, tipo=$tipo<br>";
            if ($du->$campo != '' && $campo != 'id' && $campo != 'id_caso') {
                $valor = convierte_valor($du, $campo, $tipo);
                $ignora = false;
                if (isset($masenl[$campo])) {
                    $esl = is_callable(
                        array("DataObjects_" . $masenl[$campo],
                        'idSinInfo')
                    );
                    if ($esl) {
                        $sini = call_user_func(
                            array("DataObjects_" . $masenl[$campo], 'idSinInfo')
                        );
                        if ($sini == $valor) {
                            $ignora = true;
                        }
                    }
                }
                if (!$ignora) {
                    if (($tipo & 2) && ($tipo != 134) && $valor != '*') {
                        // Cadena que no es fecha
                        consulta_and(
                            $db, $w2, "$bas.$campo",
                            "%" .trim($valor) . "%", ' ILIKE ', 'AND'
                        );
                    } else {
                        consulta_and($db, $w2, "$bas.$campo", $valor, '=', 'AND');
                    }
                }
            }
        }
    }
    return $w2;
}


/**
 * Prepara consulta de forma genérica
 *
 * @param string &$w      Consulta por retornar
 * @param string &$t      Lista de tablas
 * @param object $idcaso  Id. del caso
 * @param string $rel     Tabla con campo id_caso que referencia caso
 * @param string $bas     Tabla referenciada por campo $crelbas de $rel
 * @param string $crelbas Campo que relacion $rel con $bas
 * @param bool   $enbas   Recorrer campos de la tabla $bas?
 * @param array  $otrast  Otras tablas relacionadas con $rel
 * @param array  $iotrast Campo en cada tabla de $otrast que relaciona con $rel
 * @param array  $nonulos Campos en $rel que se ignorarn si son 0
 * @param string $irelot  Campo que identifica
 * @param array  $masenl  Enlaces a otras tablas
 *
 * @return void Resultado queda en $w y $t
     */
function prepara_consulta_gen(&$w, &$t, $idcaso, $rel, $bas, $crelbas, $enbas,
    $otrast = array(), $iotrast = '', $nonulos = array(), $irelot = "id",
    $masenl = array()
) {
    //echo "OJO prepara_consulta_gen(w=$w, t=$t, idcaso=$idcaso, rel=$rel, "
    //. "bas=$bas, crelbas=$crelbas, enbas=$enbas, otrast=$otrast, "
    //. "iotrast=$iotrast, nonulos=$nonulos, irelot=$irelot)<br>";
    $tab = parse_ini_file(
        $_SESSION['dirsitio'] . "/DataObjects/" .
        $GLOBALS['dbnombre'] . ".ini",
        true
    );
    $duc =& objeto_tabla($rel);
    sin_error_pear($duc);
    $db = $duc->getDatabaseConnection();
    $duc->id_caso = (int)($idcaso);
    if (@$duc->find() == 0) {
        return;
    }
    $csininf = @$duc->camposSinInfo();
    $w3="";
    while ($duc->fetch()) {
        $w2 = prepara_consulta_con_tabla(
            $duc, $rel, $bas, $crelbas, $enbas,
            $otrast,  $iotrast, $nonulos,  $irelot, $masenl, $tab
        );
        //echo "<hr>".$w2;
        if ($w2!="") {
            $w3 = $w3=="" ? "($w2)" : "$w3 OR ($w2)";
        }
    }
    agrega_tabla($t, $rel);
    consulta_and_sinap($w, "$rel.id_caso", "caso.id", "=", "AND");
    if ($enbas && $bas != '' && $crelbas != '') {
        agrega_tabla($t, $bas);
        consulta_and_sinap($w, "$rel.$crelbas", "$bas.id", "=", "AND");
    }

    if ($w3 != "") {
        $w = $w == "" ? "($w3)" : "$w AND ($w3)";
    }
}


/**
 * Retorna un DB_DataObject a partir del nombre de una tabla
 *
 * @param string $nom Nombre de la tabla
 *
 * @return object   DB_DataObject si no hay error, en caso de error termina
     */
function objeto_tabla($nom)
{
    assert($nom != '');
    $db = new DB_DataObject();
    sin_error_pear($db);
    $do = $db->factory($nom);
    sin_error_pear($do);

    return $do;
}


/**
 * Busca dato en una tabla básica
 *
 * @param object &$db    Conexión a base de datos
 * @param string $tabla  Tabla en la cual buscar
 * @param string $nombre Nombre por buscar
 * @param string &$obs   Colchon para agregar observaciones
 * @param bool   $sininf Si no esta retornar código del dato SIN INFORMACIÓN?
 * @param string $ncamp  Nombre del campo con el cual comparar
 *
 * @return integer Código en tabla o -1 si no lo encuentra
 */
function conv_basica(&$db, $tabla, $nombre, &$obs, $sininf = true,
    $ncamp = "nombre"
) {
    //echo "OJO conv_basica(db, $tabla, $nombre, $obs)<br>";
    $d = objeto_tabla($tabla);
    if ($sininf && ($nombre == null || $nombre == '')
        && is_callable(array("DataObjects_$tabla", 'idSinInfo'))
    ) {
        $r = call_user_func(
            array("DataObjects_$tabla",
            "idSinInfo")
        );
        return $r;
    } 

    $nom0 = $d->$ncamp = ereg_replace(
        "  *", " ",
        trim(var_escapa($nombre, $db))
    );
    $d->find(1);
    if (PEAR::isError($d)) {
        die($d->getMessage());
    }
    $nom1 = a_mayusculas($nom0);
    if (!isset($d->id)) {
        $d->$ncamp= $nom1;
        $d->find(1);
    }
    if (!isset($d->id)) {
        $nom2 = $d->$ncamp
            = a_mayusculas(sin_tildes(var_escapa($nom0, $db)));
        $d->find(1);
    }
    if (!isset($d->id)) {
        $q = "SELECT id FROM $tabla WHERE $ncamp ILIKE '%${nom0}%'";
        $r = $db->getOne($q);
        if (PEAR::isError($r) || $r == null) {
            $q = "SELECT id FROM $tabla WHERE $ncamp ILIKE '%${nom1}%'";
            $r = $db->getOne($q);
        }
        if (PEAR::isError($r) || $r == null) {
            $q = "SELECT id FROM $tabla WHERE $ncamp ILIKE '%${nom2}%'";
            //echo " q=$q";
            $r = $db->getOne($q);
        }

        if (PEAR::isError($r) || $r == null) {
            rep_obs(
                "-- " . _($tabla) . ": " . _("desconocido") . 
                " '$nombre'", $obs
            );
            if ($sininf
                && is_callable(array("DataObjects_$tabla", 'idSinInfo'))
            ) {
                $r = call_user_func(
                    array("DataObjects_$tabla",
                    "idSinInfo"
                    )
                );
            } else {
                $r = -1;
            }
        } else {
            $d = objeto_tabla($tabla);
            $d->id = $r;
            $d->find(1);
            if (trim($d->$ncamp) != trim($nom0)) {
                rep_obs(
                    "$tabla: elegido registro '$r' con nombre '" .
                    $d->$ncamp . "' que es similar a '$nom0'", $obs
                );
            }
        }
    } else {
        $r = $d->id;
    }

    return $r;
}

/**
 * Decide si un DB_DataObject es nulo o no
 *
 * @param DB_DataObject $do objeto
 *
 * @return true si y solo si es nulo
     */
function es_objeto_nulo($do)
{
    return is_null($do) || $do == null
        || $do == DB_DataObject_Cast::sql('NULL');
}

/**
 * Validaciones globales de un caso
 *
 * @param integer $idcaso Identificación de caso por validar.
 *
 * @return bool Validado
     */
function valida_caso($idcaso)
{
    $valr = true;
    $dcaso = objeto_tabla('caso');
    if (PEAR::isError($dcaso)) {
        die_esc($dcaso->getMessage());
    }
    $db =& $dcaso->getDatabaseConnection();
    // Completo: ubicación, fuentes, clasif., pr. resp,
    // victima excepto en ciertas bélicas, memo.
    $q = "SELECT COUNT(*) FROM caso_ffrecuente WHERE id_caso='"
        .$idcaso . "';";
    $nfue = (int)$db->getOne($q);
    $q = "SELECT COUNT(*) FROM caso_fotra " .
        "WHERE id_caso='" . $idcaso . "';";
    $nfue+=(int)$db->getOne($q);
    if ($nfue <= 0) {
        error_valida('Falta fuente.', array());
        $valr = false;
    }
    $q = "SELECT COUNT(*) FROM acto " .
        "WHERE id_caso='" . $idcaso . "';";
    $ncat = (int)$db->getOne($q);
    $q = "SELECT COUNT(*) FROM actocolectivo " .
        "WHERE id_caso='" . $idcaso . "';";
    $ncat += (int)$db->getOne($q);
    $q = "SELECT COUNT(*) FROM caso_categoria_presponsable " .
        "WHERE id_caso='" . $idcaso . "';";
    $ncat += (int)$db->getOne($q);
    if ($ncat <= 0) {
        error_valida('Falta tipo de violencia.', array());
        $valr = false;
    }
    $q = "SELECT COUNT(*) FROM caso_presponsable " .
        "WHERE id_caso='" . $idcaso . "';";
    $npresp = (int)$db->getOne($q);
    if ($npresp <= 0) {
        error_valida('Falta presunto responsable.', array());
        $valr = false;
    }
    $q = "SELECT COUNT(*) FROM victima " .
        "WHERE id_caso='" . $idcaso . "';";
    $nvic = (int)$db->getOne($q);
    $q = "SELECT COUNT(*) FROM victimacolectiva " .
        "WHERE id_caso='" . $idcaso . "';";
    $nvic+=(int)$db->getOne($q);
    $q = "SELECT COUNT(*) FROM combatiente " .
        "WHERE id_caso='" . $idcaso . "';";
    @$nvic+=(int)$db->getOne($q);
    if ($nvic <= 0) {
        error_valida('Falta victima.', array());
        $valr = false;
    }
    $dcaso->get($idcaso);
    if (trim($dcaso->memo)=='') {
        error_valida('Falta memo.', array());
        $valr = false;
    }
    if (isset($GLOBALS['ISPELL']) && $GLOBALS['ISPELL'] != '') {
        $cmd = "";
        if (isset($GLOBALS['DICCIONARIO'])) {
            // http://mintaka.sdsu.edu/GF/bibliog/ispell/multi.html
            $cmd = "echo \"". escapeshellcmd($dcaso->memo) ."\" | " .
                $GLOBALS['ISPELL'] . " -d spanish -Tlatin1 -p" .
                $GLOBALS['DICCIONARIO'] . " -l";
        }
        $r=`$cmd`;
        if ($r != "") {
            error_valida(
                _("Errores ortográficos en memo") . ": $r<br>" .
                str_replace(
                    '%l', $GLOBALS['CHROOTDIR'] . getcwd() . "/" .
                    $GLOBALS['DICCIONARIO'], $GLOBALS['MENS_ORTOGRAFIA']
                ),
                array()
            );
        }
    }
    if (!$valr) {
        echo "<hr>";
    }
    return $valr;
}


/**
 * Crear un patrón de búsqueda a partir de un arreglo de palabras por buscar
 * en ese orden
 *
 * @param array $ar Arreglo de palabras por buscar en ese orden
 *
 * @return string Patrón de búsqueda para usar con ~ en PostgreSQL
 */
function crea_patron($ar)
{
    assert(is_array($ar));

    $patron = "";
    $inipat = "";
    // Grupos de caracteres equivalentes
    $c = array (
        'aáAÁ', 'eéEÉ', 'iíIÍ', 'oóOÓ', 'uúUúÜü', 'zZsS', 'nNñÑ'
    );
    $u = "";
    foreach ($ar as $ni) {
        $patron .= $inipat . ".*";
        for ($i = 0 ; $i < strlen($ni); $i++) {
            $np = $ni[$i];
            //echo "i=$i, np=$np<br>";
            if ($np == " " && $u != " ") {
                $np = " *";
            } else {
                foreach ($c as $ce) {
                    //echo "ce=$ce, np=$np ";
                    if (strpos($ce, $np) !== false) {
                        $np = "[$ce]";
                        break;
                    }
                }
            }
            $patron .= $np;
            $u = $ni[$i];
        }
        $patron .= ".*";
        $inipat =" *";
    }

    return $patron;
}



/* -------- XML y RELATO */

/**
 * a_elementos_xml($r, $ind, $ad) convierte vector con datos [ad] a
 * cadena de elementos XML que adiciona al final de [r] indentando a
 * [ind] espacios.
 *
 * @param string  &$r  Cadena por completar
 * @param integer $ind Cantidad de espacios a los cuales indentar
 * @param array   $ad  Vector con datos
 * @param array   $ren Renombra indices de $ad
 *
 * @return string Cadena XML con datos de $ad convertidos
     */
function a_elementos_xml(&$r, $ind, $ad, $ren = null)
{
    foreach ($ad as $ie => $dato) {

        if (isset($ren) && isset($ren[$ie])) {
            $marca = $ren[$ie];
        } else {
            $marca = $ie;
        }
        if (isset($dato) && $dato != '') {
            for ($i = 0; $i < $ind; $i++) {
                $r .= " ";
            }

            if (($pi = strpos($marca, '{'))
                && ($pm = strpos($marca, '->'))
                && ($pd = strpos($marca, '}'))
            ) {
                $marcad = substr($marca, 0, $pi);
                $atr = " " . substr($marca, $pi+1, $pm - $pi - 1) . "=\"" .
                    substr($marca, $pm+2, $pd - $pm - 2) . "\"";
            } else {
                $marcad = $marca;
                $atr = "";
            }
            $r .= "<$marcad$atr>" . trim($dato) . "</$marcad>\n";
        }
    }
}


/**
 * dato_relacionado(&$ad, $tabla, $campoid, $id, $camporel, $camponombre)
 * Abre tabla $tabla, ubica los que tengan $campoid en $id y por
 * cada uno agrega el nombre $camponombre de la tabla relacionada
 * por el campo $camporel.  La información la agrega al arreglo
 * por convertir a XML ad como observacion cuyo tipo es $camporel.
 *
 * @param array  &$ad         Arreglo al cual agrega información convertida
 * @param string $tabla       nombre de tabla (e.g comunidad_sectorsocial)
 * @param string $id          Arreglo con llaves y valores
 * @param string $camporel    Campo de $tabla
 * @param string $camponombre nombre de campo por agregar a $ad
 *
 * @return void
     */
function dato_relacionado(&$ad, $tabla,
    $id, $camporel = 'id_sectorsocial', $camponombre = 'nombre'
) {
    $do = objeto_tabla($tabla);
    foreach ($id as $vc => $vv) {
        $do->$vc = $vv;
    }
    $do->find();
    $ad["observaciones{tipo->$camporel}"] = $sep2 = '';
    while ($do->fetch()) {
        $dorel = $do->getLink($camporel);
        $ad["observaciones{tipo->$camporel}"] .=
            $sep2.trim($dorel->$camponombre);
        $sep2=", ";
    }
}


/**
 * enlaza_relato($do, $campo, $elemento, $ad, $valor = null)
 * Agrega al arreglo de datos $ad el elemento $elemento, con el
 * valor $valor o el que corresponda al $campo del objeto $do o
 * al valor del registro que tal campo enlace.
 *
 * @param object &$do      DB_DataObject
 * @param string $campo    nombre de campo en $do
 * @param string $elemento Elemento
 * @param array  &$ad      Arreglo al cual agrega información convertida
 * @param string $valor    Valor por incluir en $ad
 *
 * @return void
     */
function enlaza_relato(&$do, $campo, $elemento, &$ad, $valor = null)
{
    assert($do != null);
    assert(isset($do->$campo));
    assert($elemento != null && $elemento != '');
    assert(is_array($ad));

    //echo "OJO enlaza_relato campo=$campo, elemento=$elemento, "
    //. "ad=$ad, valor=$valor\n";
    global $dbnombre;

    $ad[$elemento] = '';
    $exc = isset(
        $GLOBALS['_DB_DATAOBJECT']['LINKS'][$dbnombre][$do->__table][$campo]
    );
    if (isset($valor) && $valor != null && $valor != '') {
        //echo "OJO Valor pasado por par\ufffdmetros";
        $ad[$elemento] = $valor;
    } else if ($exc) {
        //echo "OJO enlazada con otra tabla";
        $rel = $GLOBALS['_DB_DATAOBJECT']['LINKS'][$dbnombre][$do->__table][$campo];
        $pd = strpos($rel, ':');
        $ndo = substr($rel, 0, $pd);
        $ndoc = "DataObject_" . ucfirst($ndo);
        //echo "OJO ndoc=$ndoc";
        $vsi = '';
        $csinf = $do->camposSinInfo();
        //print_r($csinf);
        if (isset($csinf[$campo])) {
            $vsi = $csinf[$campo];
        }
        //echo "OJO vsi=$vsi";
        if (isset($do->$campo) && $do->$campo != '' && $do->$campo != $vsi) {
            //echo "OJO por tomar enlazada para campo= " . $do->$campo . "\n";
            $dorel= $do->getLink($campo);
            $ad[$elemento] = $dorel->valorRelato();
        }
    } else {
        //echo "OJO valor de campo";
        $ad[$elemento] = $do->$campo;
    }

    //echo "OJO por retornar : " . $ad[$elemento] ."\n";
}


/* -------------- DEPURACIÓN */

/**
 * Muestra variables
 *
 * @param string $nom Nombre
 * @param array  $a   Variable
 *
 * @return void
     */
function impvar($nom, $a)
{
    foreach ($a as $k => $v) {
        if (!is_array($v)) {
            echo_esc("$" . $nom . "['" . $k . "'] = '" . $v . "';");
        } else {
            impvar($nom . "['" . $k . "']", $v);
        }
    }
}


/**
 * Presenta ambiente de ejcución
 *
 * @return void
     */
function ambiente()
{
    echo "<pre>";
    impvar("_GET", $_GET);
    impvar("_POST", $_POST);
    impvar("_COOKIE", $_COOKIE);
    impvar("_SESSION", $_SESSION);
    echo "</pre><hr>";
}

/**
 * Retorna cantidad de memoria empleada por un arreglo.
 * Idea de grey - greywyvern.com en comentario de memory-get-usage
 *
 * @param array $arr Arreglo
 *
 * @return integer Aproximación a cantidad de memoria usada.
 **/
function tam_arreglo($arr)
{
    $tmem = 0;
    foreach ($arr as $k => $v) {
        ob_start();
        print_r($v);
        $mem = ob_get_contents();
        ob_end_clean();
        $mem = preg_replace("/\n +/", "", $mem);
        $mem = strlen($mem);
        //echo "$k -> $mem<br>";
        $tmem += $mem;
    }
    return $tmem;
}

if (!function_exists('get_called_class')) {
    /**
     * Retorna nombre de clase llamadora
     *
     * @return void
     */
    function get_called_class()
    {
        $bt = debug_backtrace();
        $lines = file($bt[1]['file']);
        preg_match(
            '/([a-zA-Z0-9\_]+)::' . $bt[1]['function'] . '/',
            $lines[$bt[1]['line']-1],
            $matches
        );
        return $matches[1];
    }
}

?>
