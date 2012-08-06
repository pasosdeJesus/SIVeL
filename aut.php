<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Funciones para autenticación de usuarios con Auth de Pear.
 * Con base en {@link http://structio.sourceforge.net/seguidor}
 * que a su vez se basa en
 * {@link http://pear.php.net/manual/en/package.authentication.auth.intro.php}
 * Debe incluirse después de incluir misc.php.
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
 * Funciones para autenticación de usuarios con Auth de Pear.
 */
require_once "Auth.php";
require_once "DB.php";
require_once "HTML/Javascript.php";
require_once "confv.php";


/**
 * Realiza una consulta SQL y retorna resultado
 *
 * @param object &$db Base de datos
 * @param string $q   Consulta
 *
 * @return object Resultado
 */

function hace_consulta_aut(&$db, $q) 
{
    $result = $db->query($q);
    if (PEAR::isError($result)) {
        die(htmlentities(
            $result->getMessage() . " - " . $result->getUserInfo()
        ));
    }
    return $result;
}

/**
 * Establece locale
 *
 * @param string $l Nombre del locale
 *
 * @return void
 **/
function idioma($l = "es_CO")
{
    global $LENGDISP;
    //echo "OJO idioma($l)<br>";
    $ld = explode(" ", $LENGDISP);
    if (!in_array($l, $ld)) {
        echo "El idioma '$l', se solicitó pero no está disponible.<br>";
        echo "Los idiomas disponibles son: ";
        $sep ="";
        foreach($ld as $nl) {
            echo $sep . $nl;
            $sep =", ";
        }
        echo "<br>Estableciendo es_CO<br>";
        $l = 'es_CO';
    }
    $td = 'sivel';
    $GLOBALS['LC_ALL'] = $l;
    $_SESSION['idioma_usuario'] = $l;
    $_SESSION['LANG'] = $l;
    putenv("LANGUAGE=$l");
    putenv("LANG=$l");
    putenv("LC_ALL=$l");
    putenv("LC_MESSAGESL=$l");
    setlocale(LC_ALL, $l);
    setlocale(LC_CTYPE, $l);
    $locales_dir = dirname(__FILE__).'/locale';
    $locales_dir = './locale';
    bindtextdomain($td,$locales_dir);
    bind_textdomain_codeset($td, 'UTF-8'); 
    textdomain($td);
    if ($l == "en_US" && "Fuente" == _("Fuente")) {
        echo"Error al inicializar idioma"; 
        debug_print_backtrace();
        die();
    }
}


/**
 * Formulario de ingreso.
 *
 * @return  Nada
 */
function loginFunction()
{
    echo "<form method=\"post\" action=\"" .
        htmlspecialchars($_SERVER['PHP_SELF']) . "\">";
    echo "<table border='0'>";
    echo "<tr><td bgcolor='#c0c0c0' colspan='2'>Autenticaci&oacute;n</td></tr>";
    echo "<tr><td>" .  _("Usuario") 
        . ":</td><td><input type=\"text\" name=\"username\"></td></tr>";
    echo "<tr><td>" . _("Clave")
        . ":</td><td><input type=\"password\" name=\"password\">" .
        "<br></td></tr>";
    echo "<tr><td colspan='2' align='center'><input type=\"submit\" " .
        "value=\"Enviar\"></tr></td>";
    echo "</table>";
    echo "</form>";
}


/**
 * Sin formulario de ingreso.
 *
 * @return  Nada
 */
function noLoginFunction()
{
}


/**
 * Roles para los que está disponible cada opción del menú, ver opciones
 * y roles en sitios/pordefecto/conf_int.php
 */
$GLOBALS['m_opcion_rol'] = array (
    0 => array(1, 2, 3, 4),
    11 => array(1),
    12 => array(1),
    21 => array(1, 2),
    31 => array(1, 2, 3),
    41 => array(1, 2),
    42 => array(1, 2, 4),
    43 => array(1),
    51 => array(1),
    44 => array(1, 2),
    45 => array(1, 2),
    60 => array(1, 2, 3, 4),
    61 => array(1, 2, 3),
    62 => array(1),
    63 => array(1),
    64 => array(1, 2, 3),
    65 => array(1, 2, 3),
);



/**
 * Extrae opciones para un usuario.
 *
 * @param string  $usuario Login del usuario
 * @param handle  &$db     Conexión a BD
 * @param array   &$op     Se llena con opciones del usuario.
 * @param integer &$rol    Rol del usuario
 *
 * @return  Nada
 */
function sacaOpciones($usuario, &$db, &$op, &$rol)
{
    $q = "SELECT id_rol FROM usuario " .
        "WHERE id_usuario='" .  $usuario . "';";
    $result = hace_consulta_aut($db, $q);
    $row = array();
    if ($result->fetchInto($row)) {
        $rol = $row[0];
    }
    foreach ($GLOBALS['m_opcion_rol'] as $idop => $aroles) {
        if (in_array($rol, $aroles)) {
            $op[] = $idop;
        }
    }
}


/**
 * Retorna nombre de la sesión basado en URL.
 * Podría considerarse una forma de almacenar sesiones diferente --drupal
 * usa base de datos.
 *
 * @return string Nombre de la sesión
 */
function nomSesion()
{
    $sru = $_SERVER['REQUEST_URI'];
    if (($l = strrpos($sru, '/')) === false) {
        $dsru = $sru;
    } else {
        $dsru = substr($sru, 0, $l);
    }
    // La idea de usar HTTP_HOST es de fuentes de Drupal pero no basta porque
    // al menos en php 5.2.5 de OpenBSD 4.2 el nombre de la sesión debe
    // ser alfanumérico (documentado) y comenzar con letra (no documentado).
    // Así mismo varias instalaciones en el mismo HOST corriendo simultaneamente
    // confundirían el nombre de sesión.
    $snru = preg_replace(
        '/[^a-z0-9]/i', '',
        "s" . $_SERVER['HTTP_HOST'] . $dsru
    );
    return $snru;
}


/**
 * De requerirlo inicia sesión y autentica un usuario listado como usuario
 * en la base de datos y consulta opciones a las que puede acceder.
 * Si el usuario tiene permiso para la opcion $opcion retorna true.
 * En otro caso falla.
 *
 * @param string  $dsn      URL de base de datos
 * @param string  &$usuario Retorna en esta variable el login del usuario
 * @param integer $opcion   Código de la opción del menu que el usuario desea
 *
 * @return mixed Conectar base de datos
 */
function autenticaUsuario($dsn,  &$usuario, $opcion)
{
    $accno = _("Acceso no autorizado");
    $snru = nomSesion();
    if (!isset($_SESSION) || session_name() != $snru) {
        session_name($snru);
        session_start();
    }
    $options = array('debug'       => 5);
    $db = DB::connect($dsn, $options);
    if (PEAR::isError($db)) {
        $m = $db->getMessage()."<br>\n" . $db->getUserInfo();
        $m = str_replace($dsn, '', $m);
        die($m);
    }
    $db1 = new DB();
    $db->query('SET client_encoding TO UTF8');
    $params = array(
        "dsn" => $dsn,
        "table" => "usuario",
        "usernamecol" => "id_usuario",
        "passwordcol" => "password",
        "cryptType" => 'sha1',
    );
    $a = new Auth("DB", $params, "loginFunction");
    $a->setSessionName($snru);
    //echo "En autenticaUsuario OJO sesion:"; print_r($a->session);
    $a->start();
    //echo "OJO snru=$snru"; die("x");
    if ($a->checkAuth()) {
        ini_set('session.cookie_httponly', true);
        ini_set('session.cookie_secure', true);
        $texp = 60*60*4; // 4 horas de sesión
        ini_set('session.gc_maxlifetime', $texp);
        $a->setExpire($texp);
        $a->setIdle($texp / 2);

        $_SESSION['dirsitio'] = localizaConf();

        //echo "<script>alert(document.cookie);</script>";
        $usuario = $a->getUsername();
        if (!isset($_SESSION['opciones'])
            || !isset($_SESSION['id_funcionario'])
        ) {
            $op = array();
            $rol = -1;

            //Prevenir session fixation
            //http://shiflett.org/articles/session-fixation
            session_regenerate_id();
            sacaOpciones($usuario, $db, $op, $rol);
            if (count($op) == 0) {
                echo "No tiene opciones este usuario, " .
                    "¿seguro la base está bien inicializada?";
            }
            $_SESSION['opciones'] = $op;
            $q = "SELECT id FROM funcionario " .
                "WHERE nombre='" . $usuario . "';";
            $result = hace_consulta_aut($db, $q);
            $row = array();
            if ($result->fetchInto($row)) {
                $idf = $row[0];
            }
            $_SESSION['id_usuario'] = $usuario;
            $_SESSION['id_funcionario'] = $idf;
            $q = "SELECT idioma FROM usuario " .
                "WHERE id_usuario='" . $usuario . "';";
            $result = hace_consulta_aut($db, $q);
            $row = array();
            if ($result->fetchInto($row)) {
                $lang = $row[0];
            }
            $_SESSION['idioma_usuario'] = $lang;
        }
        if (isset($_SESSION['idioma_usuario'])) {
            idioma($_SESSION['idioma_usuario']);
        } else {
            idioma($_SESSION['es_CO']);
        }
        if (in_array($opcion, $_SESSION['opciones'])) {
            return $db;
        }
        die($accno . " (1)");
    } else {
        if (isset($_SESSION['idioma_usuario'])) {
            idioma($_SESSION['idioma_usuario']);
        }
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $params = array(
                "dsn" => $dsn,
                "table" => "usuario",
                "usernamecol" => "id_usuario",
                "passwordcol" => "password",
                "cryptType" => 'md5',
            );
            $b = new Auth("DB", $params, "noLoginFunction");
            $b->setSessionName($snru);
            $b->start();
            if ($b->checkAuth()) {
                $clavesha1 = sha1(var_post_escapa('password', $db, 32));
                $un = var_post_escapa('username', $db, 15);
                hace_consulta_aut(
                    $db,
                    "ALTER TABLE usuario ADD COLUMN npass VARCHAR(64);"
                );
                hace_consulta_aut(
                    $db,
                    "UPDATE usuario SET npass=password;"
                );
                hace_consulta_aut(
                    $db,
                    "ALTER TABLE usuario DROP COLUMN password;"
                );
                hace_consulta_aut(
                    $db,
                    "ALTER TABLE usuario RENAME COLUMN npass TO password;"
                );
                $q = "UPDATE usuario SET password='$clavesha1' WHERE " .
                    "id_usuario='$un';";
                hace_consulta_aut($db, $q);
                $htmljs = new HTML_Javascript();
                echo $htmljs->startScript();
                echo $htmljs->alert(
                    'Condensado de la clave cambiado a sha1.' .
                    ' Por favor autentiquese nuevamente'
                );
                echo $htmljs->endScript();
                cierraSesion($dsn);
                exit(1);
            }
        }
        unset($_POST['password']);
        die($accno . " (2)");
    }
}


/**
 * Cierra sesión iniciada con autenticaUsuario.
 *
 * @param string $dsn URL de base de datos
 *
 * @return nada
 */
function cierraSesion($dsn)
{
    $snru = nomSesion();
    if (!isset($_SESSION) || session_name() != $snru) {
        session_name($snru);
        session_start();
    }
    $params = array(
        "dsn" => $dsn,
        "table" => "usuario",
        "usernamecol" => "id_usuario",
        "passwordcol" => "password"
    );
    $a = new Auth("DB", $params, "loginFunction");
    $a->setSessionName($snru);
    $nv = "_auth_" . nomSesion();
    unset($_SESSION[$nv]);
    unset($_SESSION['_authession']);
    unset($_SESSION['id_funcionario']);
    unset($_SESSION['id_usuario']);
    unset($_SESSION['opciones']);
    $a->logout();
    session_write_close();
    unset($_SESSION);
}


/**
 * Localiza directorio del archivo de configuración según URL
 *
 * @return nada
 */
function localizaConf()
{
    global $dirsitio;
    $pbase = 'sivel';
    if (isset($_SERVER['HTTP_X_FORWARDED_SERVER'])) {
        $n = $_SERVER['HTTP_X_FORWARDED_SERVER'];
    } else {
        $n = $_SERVER['SERVER_NAME'];
    }
    $n .= $_SERVER['REQUEST_URI'];

    $n = htmlspecialchars($n);
    if (($ps = strrpos($n, "/")) !== false) {
        $n = substr($n, 0, $ps);
    }
    if (($pm = strpos($n, "/modulos")) != false) {
        $n = substr($n, 0, $pm);
    }
    $n = str_replace("/", "_", $n);
    $n = str_replace("~", "", $n);
    while (substr($n, strlen($n)-1, 1)=='_') {
        $n = substr($n, 0, strlen($n)-1);
    }
    $nn = substr($n, strpos($n, "_") + 1);
    $pbase = $nn;

    $pref = "";
    if (isset($_SESSION['localizaConf_pref'])) {
        $pref = $_SESSION['localizaConf_pref'];
    }
    $dirsitio = $pref . "sitios/".strtoupper($n);
    $dirsitios = array(
        $dirsitio,
        $pref . "sitios/".$n,
    );

    $ri = explode(":", ":../../:" . ini_get('include_path'));

    $existe = false;
    foreach ($ri as $d) {
        foreach ($dirsitios as $ds) {
            $t = "$d/$ds/conf.php";
            //trigger_error("OJO Probando t='$t'");
            if (file_exists($t)) {
                $dirsitio = "$d/$ds";
                $existe = true;
                break 2;
            }
        }
    }
    if (!$existe) {
        global $CHROOTDIR;
        encabezado_envia('Error');
        echo "No existe configuración '"
            . htmlentities($dirsitio, ENT_COMPAT, 'UTF-8') . "'<br>";
        $r = dirname($_SERVER['PATH_TRANSLATED']) . "/sitios";
        $rs = $CHROOTDIR . $r;
        $cmd ="cd $rs; sudo ./nuevo.sh $pbase; sudo ln -s $pbase "
            . strtoupper($n);
        foreach (array($nn, 'sivel') as $pn) {
            $rp = $r . "/" . $pn;
            if (file_exists($rp)) {
                $fn = $pn;
                echo "Existe ruta " . htmlentities("$CHROOTDIR$rp", ENT_COMPAT, 'UTF-8') . "<br>";
                $cmd ="cd $rs; sudo ln -s $pn " . strtoupper($n);
            }
        }
        echo "Posiblemente basta que ejecute desde una terminal: <br>";
        echo "<font size='-1' color='#db9090'>"
            . htmlentities($cmd, ENT_COMPAT, 'UTF-8') . "</font>";
        pie_envia();
        exit(1);
    }
    //trigger_error("OJO quedo dirsitio='$dirsitio'");
    return $dirsitio;
}

$_SESSION['dirsitio'] = localizaConf();

?>
