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

require_once "bcrypt.php";

/**
 * Funciones para autenticación de usuarios con Auth de Pear.
 */
require_once "Auth.php";
require_once "DB.php";
require_once "HTML/Javascript.php";
if (!file_exists(dirname(__FILE__) . "/confv.php")) {
    die(
        "No existe archivo confv.php.\n"
        . "Configure desde interprete de comandos con ./conf.sh"
    );
}
require_once dirname(__FILE__) . "/confv.php";


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
function var_escapa_aut($v, &$db = null, $maxlong = 1024)
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
/**
 * Realiza una consulta SQL y retorna resultado
 *
 * @param object &$db      Base de datos
 * @param string $q        Consulta
 * @param bool   $finerror Verdadero indica que termina si hay error
 *
 * @return object Resultado
 */

function hace_consulta_aut(&$db, $q, $finerror = true)
{
    $result = $db->query($q);
    if (PEAR::isError($result)) {
        echo htmlentities("{$result->getMessage()} - {$result->getUserInfo()}");
        echo "<br>" . _("&iquest;Ya") . " <a href='actualiza.php'>"
            . _("actualiz&oacute;") . "</a> "
            . _("y regener&oacute; esquema?");
        if ($finerror) {
            exit(1);
        }
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
    include "confv.php";
    $ld = explode(" ", $LENGDISP);
    if (!in_array($l, $ld)) {
        echo "El idioma '" 
            . htmlentities($l, ENT_COMPAT, 'UTF-8')
            . "', se solicitó pero no está disponible.<br>";
        echo "Los idiomas disponibles son: ";
        $sep ="";
        foreach ($ld as $nl) {
            echo $sep . htmlentities($nl, ENT_COMPAT, 'UTF-8');
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
    bindtextdomain($td, $locales_dir);
    bind_textdomain_codeset($td, 'UTF-8');
    textdomain($td);
    if ($l == "en_US" && "Fuente" == _("Fuente")) {
        echo
            htmlentities("Error al inicializar idioma $l", ENT_COMPAT, 'UTF-8')
            . "<br>";
        debug_print_backtrace();
        die();
    }
}


/**
 * Formulario de ingreso.
 *
 * @return  Nada
 */
function login_function()
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
function no_login_function()
{
}



/**
 * Extrae opciones para un usuario.
 *
 * @param string  $usuario   Login del usuario
 * @param handle  &$db       Conexión a BD
 * @param array   &$op       Se llena con opciones del usuario.
 * @param integer &$rol      Rol del usuario
 * @param string  $cnusuario Campo con nombre de usuario
 *
 * @return  Nada
 */
function saca_opciones($usuario, &$db, &$op, &$rol, $cnusuario = "nusuario")
{
    $q = "SELECT rol FROM usuario " .
        "WHERE $cnusuario='" .  $usuario . "';";
    $result = hace_consulta_aut($db, $q, true);
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
function nom_sesion()
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
function autentica_usuario($dsn,  &$usuario, $opcion)
{
    //echo "OJO autentica dsn=$dsn, usuario=$usuario, opcion=$opcion<br>";
    $accno = _("Acceso no autorizado");
    $snru = nom_sesion();
    if (!isset($_SESSION) || session_name() != $snru) {
        session_name($snru);
        session_start();
    }
    if (CRYPT_BLOWFISH != 1) {
        die("Se requiere PHP con CRYPT_BLOWFISH. Por favor actualice");
    }
    $options = array('debug'       => 5);
    $db = DB::connect($dsn, $options);
    if (PEAR::isError($db)) {
        $m = $db->getMessage()."<br>\n" . $db->getUserInfo();
        $m = str_replace($dsn, '', $m);
        die($m);
    }

    $username = var_escapa_aut($_POST['username'], $db, 32);
    $db1 = new DB();
    $db->query('SET client_encoding TO UTF8');
    $q = "SELECT COUNT(id) FROM usuario";
    $result = hace_consulta_aut($db, $q, false);
    if (PEAR::isError($result)) {
        echo "<br>" . _("Intentado actualizar usuario");
        $result = hace_consulta_aut(
            $db, "ALTER TABLE usuario RENAME COLUMN id_rol TO rol", false
        );
        $result = hace_consulta_aut(
            $db, "ALTER TABLE usuario RENAME COLUMN id_usuario TO id", false
        );
        hace_consulta_aut(
            $db, "ALTER TABLE usuario ADD COLUMN idioma "
            . " VARCHAR(6) NOT NULL DEFAULT 'es_CO'", false
        );
        $result = hace_consulta_aut($db, $q, false);
    }
    if (PEAR::isError($result)) {
        echo "<br>" . _("No pudo emplear tabla de usuarios");
        exit(1);
    }
    $n = array();
    $result->fetchInto($n);
    if ((int)$n[0] == 0) {
        die("No hay usuarios en la base de datos creelos desde una terminal");
    }
    $q = "SELECT locked_at FROM usuario WHERE nusuario='$username'";
    $locked = $db->getOne($q);
    if (PEAR::isError($locked)) {
        echo "<br>" . _("No pudo determinar si está bloqueado");
    }
    if (strlen($locked) > 0) {
        $t = strtotime($locked);
        $ta = time();
        $d = $ta - $t;
        if ((isset($GLOBALS['segundos_desbloqueo']) 
            && $d <= $GLOBALS['segundos_desbloqueo']) 
            || (!isset($GLOBALS['segundos_desbloqueo'])  
            && $d <= 60)
        ) {
            echo "<br>" .  _("Cuenta bloqueada. Por favor informe al administrador");
            $username = $_GET['username'] = 
                $_POST['username'] = $_REQUEST['username'] = "";
        } else {
            // Desbloquea tras una hora  o los segundos especificados en
            // $GLOBALS['segundos_desbloqueo']
            $q = "UPDATE usuario SET failed_attempts=0, locked_at=NULL, 
                unlock_token=NULL
                WHERE nusuario='$username'"; 
            $locked = "";
            hace_consulta_aut($db, $q, false);
            echo "<br>" .  _("Cuenta desbloqueada tras $d segundos");
        }
    }

    $camponusuario = "nusuario";
    $params = array(
        "dsn" => $dsn,
        "table" => "usuario",
        "usernamecol" => "nusuario",
        "passwordcol" => "encrypted_password",
        "cryptType" => "crypt",
        "db_where" => "fechadeshabilitacion IS NULL",
    );
    $q = "SELECT COUNT(nusuario) FROM usuario WHERE encrypted_password<>''";
    $n = $db->getOne($q);
    if (PEAR::isError($n)) {
        $camponusuario = "id";
        $params['usernamecol'] = 'id';
        $params['passwordcol'] = 'password';
        $params['cryptType'] = 'sha1';
        $params['db_where'] = '';
        echo "<hr>" . _("Aun no se emplea nueva tabla usuario.")
           . _("Solicite actualización a un administrador") 
           . "<hr>";
    }
    //echo "OJO params=";print_r($params);echo "<hr>";
    $a = new Auth("DB", $params, "login_function");
    $a->setSessionName($snru);
    //echo "<hr>OJO autentica_usuario $opcion Auth sesion:";
    //print_r($a->session); echo "<br>";
    $a->start();
    //echo "OJO snru=$snru";
    if (strlen($locked) == 0 && $a->checkAuth()) {
        //echo "<hr>OJO autenticó 1";
        $q = "UPDATE usuario SET failed_attempts=0, locked_at=NULL, 
            unlock_token=NULL
            WHERE nusuario='$username'";
        hace_consulta_aut($db, $q, false);

        ini_set('session.cookie_httponly', true);
        ini_set('session.cookie_secure', true);
        $texp = 60*60*4; // 4 horas de sesión
        ini_set('session.gc_maxlifetime', $texp);
        $a->setExpire($texp);
        $a->setIdle($texp / 2);

        $_SESSION['dirsitio'] = localiza_conf();
        //echo "<script>alert(document.cookie);</script>";
        $usuario = $a->getUsername();
        if (!isset($_SESSION['opciones']) || count($_SESSION['opciones']) == 0
            || !isset($_SESSION['id_usuario'])
        ) {
            $op = array();
            $rol = -1;

            //Prevenir session fixation
            //http://shiflett.org/articles/session-fixation
            if (!headers_sent()) {
                session_regenerate_id();
            }
            saca_opciones($usuario, $db, $op, $rol, $camponusuario);
            if (count($op) == 0) {
                echo "Este usuario no tiene opciones, " .
                    "¿seguro la base está bien inicializada?";
            }
            $_SESSION['opciones'] = $op;
            $q = "SELECT id FROM usuario 
                WHERE $camponusuario='" . $usuario . "';";
            $result = hace_consulta_aut($db, $q);
            $row = array();
            if ($result->fetchInto($row)) {
                $idf = $row[0];
            }
            $_SESSION['id_nusuario'] = $usuario;
            $_SESSION['id_usuario'] = $idf;
            $q = "SELECT idioma FROM usuario " .
                "WHERE $camponusuario = '" . $usuario . "';";
            $result = hace_consulta_aut($db, $q, false);
            $row = array();
            if (!PEAR::isError($result) && $result->fetchInto($row)) {
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
    } 
    /* No autenticó con nuevo método, intentar anteriores */
    if (isset($_SESSION['idioma_usuario'])) {
        idioma($_SESSION['idioma_usuario']);
    }
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $params = array(
            "dsn" => $dsn,
            "table" => "usuario",
            "usernamecol" => "nusuario",
            "passwordcol" => "password",
            "cryptType" => "sha1"
        );
        $b = new Auth("DB", $params, "no_login_function");
        $b->setSessionName($snru);
        $b->start();
        $clavebf = crypt(
            var_escapa_aut($_POST['password'], $db, 32), gen_sal_bcrypt(10)
        );
        //echo  "OJO clavebf=$clavebf<br>";
        if (strlen($locked) == 0 && $b->checkAuth()) {
            /*$clavebf = crypt(
                var_post_escapa('password', $db, 32), gen_sal_bcrypt(10)
            ); */

            if (strlen($clavebf) > 10) {
                $un = var_escapa_aut($_POST['username'], $db, 15);
                $q = "UPDATE usuario SET password='',
                    encrypted_password='$clavebf' 
                    WHERE nusuario='$un';";
                hace_consulta_aut($db, $q);
            }
            $htmljs = new HTML_Javascript();
            echo $htmljs->startScript();
            echo $htmljs->alert(
                'Condensado de la clave cambiado a bcrypt.' .
                ' Por favor autentiquese nuevamente'
            );
            echo $htmljs->endScript();
            cierra_sesion($dsn);
            exit(1);
        }
        $params = array(
            "dsn" => $dsn,
            "table" => "usuario",
            "usernamecol" => "id",
            "passwordcol" => "password",
            "cryptType" => 'md5',
        );
        $b = new Auth("DB", $params, "no_login_function");
        $b->setSessionName($snru);
        $b->start();
        if (strlen($locked) == 0 && $b->checkAuth()) {
            $clavesha1 = sha1(var_escapa_aut($_POST['password'], $db, 32));
            $un = var_escapa_aut($_POST['username'], $db, 15);
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
                "id='$un';";
            hace_consulta_aut($db, $q);
            $htmljs = new HTML_Javascript();
            echo $htmljs->startScript();
            echo $htmljs->alert(
                'Condensado de la clave cambiado a sha1.' .
                ' Por favor autentiquese nuevamente'
            );
            echo $htmljs->endScript();
            cierra_sesion($dsn);
            exit(1);
        }
        $q = "UPDATE usuario SET failed_attempts=COALESCE(failed_attempts, 0)+1 
            WHERE nusuario='$username'";
        hace_consulta_aut($db, $q, false);
        $q = "SELECT failed_attempts FROM usuario WHERE nusuario='$username'";
        $intentos = $db->getOne($q);
        if (PEAR::isError($locked)) {
            echo "<br>" . _("No pudo determinar intentos fallidos");
        }
        if ((isset($GLOBALS['max_intentos_fallidos']) && 
            (int)$intentos >= $GLOBALS['max_intentos_fallidos']) ||
            (!isset($GLOBALS['max_intentos_fallidos']) && 
            (int)$intentos >= 3)
        ) {
            $d = @date('Y-m-d H:i');
            $q = "UPDATE usuario SET locked_at='$d'
                WHERE nusuario='$username'";
            hace_consulta_aut($db, $q);
            echo "<br>" . _("Usuario bloqueado.");
            // Si no tiene token poner
            // $tok = base64_encode(colchon_aleatorios(16));
            // Enviar correo con URL y token
            // Hacer página que recibe token y desbloquea
        }
    }
    $clavesha1 = sha1(var_escapa_aut($_POST['password'], $db, 32));
    $clavemd5 = md5(var_escapa_aut($_POST['password'], $db, 32));
    unset($_POST['password']);
    die($accno . " (2)");
}


/**
 * Cierra sesión iniciada con autentica_usuario.
 *
 * @param string $dsn URL de base de datos
 *
 * @return nada
 */
function cierra_sesion($dsn)
{
    $snru = nom_sesion();
    if (!isset($_SESSION) || session_name() != $snru) {
        session_name($snru);
        session_start();
    }
    $params = array(
        "dsn" => $dsn,
        "table" => "usuario",
        "usernamecol" => "nusuario",
        "passwordcol" => "encrypted_password",
        "cryptType" => "crypt",
        "db_where" => "fechadeshabilitacion IS NULL"
    );
    $a = new Auth("DB", $params, "login_function");
    $a->setSessionName($snru);
    $nv = "_auth_" . nom_sesion();
    unset($_SESSION[$nv]);
    unset($_SESSION['_authession']);
    unset($_SESSION['id_usuario']);
    unset($_SESSION['id_nusuario']);
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
function localiza_conf()
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
    while (substr($n, strlen($n)-1, 1) == '_') {
        $n = substr($n, 0, strlen($n)-1);
    }
    if (strpos($n, "_") > 0) {
        $nn = substr($n, strpos($n, "_") + 1);
    } else if (($pp = strpos($n, ".")) == true) {
        $nn = substr($n, 0, $pp);
    } else {
        $nn = $n;
    }
    $pbase = $nn;

    $pref = "";
    if (isset($_SESSION['localiza_conf_pref'])) {
        $pref = $_SESSION['localiza_conf_pref'];
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
            //echo("OJO Probando t='$t'<br>");
            if (file_exists($t)) {
                $dirsitio = "$d/$ds";
                $existe = true;
                break 2;
            }
        }
    }
    if (!$existe) {
        global $CHROOTDIR;
        if ($pbase == '127') {
            $pbase = 'sivel';
        }
        encabezado_envia('Error');
        echo "No existe configuraci&oacute;n '"
            . htmlentities($dirsitio, ENT_COMPAT, 'UTF-8') . "'<br>";
        if (isset($_SERVER['PATH_TRANSLATED'])) {
            $r = dirname($_SERVER['PATH_TRANSLATED']) . "/sitios";
        } elseif (isset($_SERVER['SCRIPT_FILENAME'])) {
            $r = dirname($_SERVER['SCRIPT_FILENAME']) . "/sitios";
        } else {
            $r = $_SERVER['DOCUMENT_ROOT'] . "/sitios";
        } 
        $rs = $CHROOTDIR . $r;
        $cmd ="cd $rs; sudo chown \$USER:\$USER .; ./nuevo.sh $pbase; ln -s $pbase "
            . strtoupper($n);
        foreach (array($nn, 'sivel') as $pn) {
            $rp = $r . "/" . $pn . "/conf.php";
            if (file_exists($rp)) {
                $fn = $pn;
                echo "Existe ruta "
                    . htmlentities("$CHROOTDIR$rp", ENT_COMPAT, 'UTF-8')
                    . "<br>";
                $cmd ="cd $rs; sudo ln -s $pn " . strtoupper($n);
            }
        }
        echo "Posiblemente basta que ejecute desde una terminal: <br>";
        echo "<font size='-1' color='#db9090'>"
            . htmlentities($cmd, ENT_COMPAT, 'UTF-8') . "</font>";
        pie_envia();
        exit(1);
    }
    //echo "OJO retornando $dirsitio<br>";
    return $dirsitio;
}

$_SESSION['dirsitio'] = localiza_conf();

?>
