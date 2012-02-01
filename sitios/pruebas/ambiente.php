<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:

/**
* Ambiente para pruebas de regresión.
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2006 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: ambiente.php,v 1.12.2.3 2011/10/18 16:05:05 vtamara Exp $
 * @link      http://sivel.sf.net
*/

/**
 * Ambiente para pruebas de regresión.
 */

if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}


$include_path = ini_get("include_path");
ini_set("include_path", $include_path . ":/var/www/pear/lib:.:..:../..:../../DataObjects/");


$_COOKIE['PHPSESSID'] = 'vr3gae8jvi47847dfnutd6rkr7';
$_SESSION['_auth_PHPSESSID']['registered'] =
    $_SESSION['_authsession']['registered'] = '1';
$_SESSION['_auth_PHPSESSID']['username'] =
    $_SESSION['_authsession']['username'] = 'sivel-prueba';
$_SESSION['_auth_PHPSESSID']['timestamp'] =
    $_SESSION['_authsession']['timestamp'] = '1150453479';
$_SESSION['_auth_PHPSESSID']['idle'] =
    $_SESSION['_authsession']['idle'] = '1150454683';
$_SESSION['id_funcionario'] = '1';


$_SESSION['opciones'][0] = 0;
$_SESSION['opciones'][1] = 11;
$_SESSION['opciones'][2] = 12;
$_SESSION['opciones'][3] = 21;
$_SESSION['opciones'][4] = 31;
$_SESSION['opciones'][5] = 41;
$_SESSION['opciones'][6] = 42;
$_SESSION['opciones'][7] = 43;
$_SESSION['opciones'][8] = 51;
$_SESSION['opciones'][9] = 44;
$_SESSION['opciones'][10] = 45;
$_SESSION['opciones'][11] = 46;
$_SESSION['opciones'][12] = 47;
$_SESSION['opciones'][13] = 48;
$_SESSION['opciones'][14] = 49;
$_SESSION['opciones'][15] = 60;


$_SERVER['SERVER_NAME'] = '127.0.0.1';
$_SERVER['REQUEST_URI'] = 'pruebas';  // así lo pone insdep.php
$_SERVER['HTTP_X_FORWARDED_SERVER'] = '';


require_once "Auth.php";
require_once "DB.php";
require_once "conf.php";


/**
 * Verifica inserción en una o más tablas
 * @param object &$db Conexión a base
 * @param array  $tabla_prueba Tablas por revisar
 * @param array  $na  Número de registros antes de inserción
 * @param bool   $terminaError Salir ante el primer error con exit(1)
 *
 * @return Retorna cantidad de errores de validación.  Hay error en una
 * de las tablas si la cuenta de registros es una más que la de $na
 */
function verificaInsercion(&$db, $tabla_prueba, $na)
{
    if (!isset($db) || PEAR::isError($db)) {
        echo "Error en conexión " . $db->getMesssage();
        exit(1);
    }
//    echo "OJO verificaInsercion(db, " . count($tabla_prueba) . ", " .  count($na) . "\n";
    assert(count($tabla_prueba) == count($na));

    $nume = 0;
    /* Verificando */
    foreach ($tabla_prueba as $nt) {
        $q = "SELECT COUNT(*) FROM $nt";
        $nd[$nt] = (int)($db->getOne($q));
        //echo " nd[nt] = $nd[$nt] ";
        if (($nd[$nt]-$na[$nt])!= 1) {
            echo "No insertaron datos de pestaña en tabla $nt. Antes: " .
                $na[$nt] . ", después: " . $nd[$nt] . "\n";
            $nume++;
        }
        return $nume;
    }
}

/**
 * Agrega datos a una pestaña del formulario Ficha.
 * Sólo puede llamarse una vez por cada ejecución de php (pues el
 * require se ejecuta una vez)
 *
 * @param handle  &$db          Conexión a BD
 * @param string  $tabla_prueba Tabla que debe incrementarse tras pasar pestaña
 * @param array   $post         Valor que debe tomar la variable POST
 * @param integer $basicos_id   Cód. caso si falta $_SESSION['basicos_id']
 * @param boolean $terminaError Si hay error terminar
 * @param array   $files        Valor que debe tomar la variable $_FILES
 *
 * @return nada
 */
function pasaPestanaFicha(&$db, $tabla_prueba, $post, $basicos_id = null,
    $terminaError = true, $files = null)
{

    if (!isset($db) || PEAR::isError($db)) {
        echo "Error en conexión " . $db->getMesssage();
        exit(1);
    }
    $_SESSION['fvloc_pag'] = '0';
    $_SESSION['fub_pag'] = '0';
    $_SESSION['ff_pag'] = '0';
    $_SESSION['fd_pag'] = '0';
    $_SESSION['fpr_pag'] = '0';
    $_SESSION['fvi_pag'] = '0';
    $_SESSION['fvc_pag'] = '0';
    $_SESSION['fvm_pag'] = '0';
    $_SESSION['__container']['defaults']['borderpainted'] = '';
    $_SESSION['__container']['defaults']['borderclass'] = 'progressBarBorder';
    $_SESSION['__container']['defaults']['borderstyle']['style'] = 'solid';
    $_SESSION['__container']['defaults']['borderstyle']['width'] = '0';
    $_SESSION['__container']['defaults']['borderstyle']['color'] = '#000000';
    $_SESSION['__container']['defaults']['cellid'] = 'progressCell%01s';
    $_SESSION['__container']['defaults']['cellclass'] = 'cell';
    $_SESSION['__container']['defaults']['cellvalue']['min'] = '0';
    $_SESSION['__container']['defaults']['cellvalue']['max'] = '100';
    $_SESSION['__container']['defaults']['cellvalue']['inc'] = '1';
    $_SESSION['__container']['defaults']['cellsize']['width'] = '15';
    $_SESSION['__container']['defaults']['cellsize']['height'] = '20';
    $_SESSION['__container']['defaults']['cellsize']['spacing'] = '2';
    $_SESSION['__container']['defaults']['cellsize']['count'] = '10';
    $_SESSION['__container']['defaults']['cellcolor']['active'] = '#006600';
    $_SESSION['__container']['defaults']['cellcolor']['inactive'] = '#CCCCCC';
    $_SESSION['__container']['defaults']['cellfont']['family'] =
        'Courier, Verdana';
    $_SESSION['__container']['defaults']['cellfont']['size'] = '8';
    $_SESSION['__container']['defaults']['cellfont']['color'] = '#000000';
    $_SESSION['__container']['defaults']['stringpainted'] = '';
    $_SESSION['__container']['defaults']['stringid'] = 'installationProgress';
    $_SESSION['__container']['defaults']['stringsize']['width'] = '50';
    $_SESSION['__container']['defaults']['stringsize']['height'] = '';
    $_SESSION['__container']['defaults']['stringsize']['bgcolor'] = '#FFFFFF';
    $_SESSION['__container']['defaults']['stringvalign'] = 'right';
    $_SESSION['__container']['defaults']['stringalign'] = 'right';
    $_SESSION['__container']['defaults']['stringfont']['family'] =
        'Verdana, Arial, Helvetica, sans-serif';
    $_SESSION['__container']['defaults']['stringfont']['size'] = '10';
    $_SESSION['__container']['defaults']['stringfont']['color'] = '#000000';
    $_SESSION['__container']['defaults']['phpcss']['P'] = '1';
    $_SESSION['__container']['valid']['basicos'] = '';
    $_SESSION['__container']['valid']['localizacion'] = '';
    $_SESSION['__container']['valid']['ubicacion'] = '';
    $_SESSION['__container']['valid']['frecuentes'] = '';
    $_SESSION['__container']['valid']['otras'] = '';
    $_SESSION['__container']['valid']['tipoViolencia'] = '';
    $_SESSION['__container']['valid']['pResponsables'] = '';
    $_SESSION['__container']['valid']['victimaIndividual'] = '';
    $_SESSION['__container']['valid']['victimaColectiva'] = '';
    $_SESSION['__container']['valid']['victimaCombatiente'] = '';
    $_SESSION['__container']['valid']['memo'] = '';
    $_SESSION['__container']['valid']['evaluacion'] = '';

    $_SERVER['SERVER_NAME'] = '127.0.0.1';
    $_SERVER['HTTP_X_FORWARDED_SERVER'] = '';
//    $_SERVER['SERVER_NAME'] = '';
    $_SERVER['REQUEST_URI'] = 'pruebas';

    $_SESSION['sin_csrf'] =  $post['evita_csrf'] = 1234;

    //var_dump($db); exit(1);
    if (isset($basicos_id) && $basicos_id!= null
        && !isset($_SESSION['basicos_id'])
    ) {
        $_SESSION['basicos_id'] = $basicos_id;
    }

    $na = array();
    foreach ($tabla_prueba as $nt) {
        $q = "SELECT COUNT(*) FROM $nt";
        $na[$nt] = (int)($db->getOne($q));
    }
    $_REQUEST = $_POST = $post;

    if ($files != null) {
        $_FILES = $files;
    }

    require "captura_caso.php";
    //echo "OJO paso captura_caso.php pasaPestanaFicha(db, $tabla_prueba, $post, $basicos_id, $terminaErro)";

    $nume = verificaInsercion($db, $tabla_prueba, $na);
    if ($terminaError && $nume > 0) {
        exit(1);
    }
}


/**
 * Retorna nombre de la sesión basado en URL.
 * Como la de aut.php
 *
 * @return string Nombre de la sesión
 */
function nomSesion2()
{
    $sru = $_SERVER['REQUEST_URI'];
    if (($l = strrpos($sru, '/')) === false) {
        $dsru = $sru;
    } else {
        $dsru = substr($sru, 0, $l);
    }
    $_SERVER['HTTP_HOST'] = 'localhost';
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

global $dsn; //definido en conf.php
$opdb = array('debug' => 5);
$db =& DB::connect($dsn, $opdb);
if (PEAR::isError($db)) {
    echo $db->getMessage() . " - " . $db->getUserInfo();
    exit(1);
}
$_REQUEST['username'] = $_POST['username'] = 'sivelpruebas';
$_REQUEST['password'] = $_POST['password'] = 'sivelpruebas';

$_REQUEST['evita_csrf'] = $_SESSION['sin_csrf'] = $_POST['evita_csrf'] = 1234;


$snru = nomSesion2();
if (!isset($_SESSION) || session_name() != $snru) {
    session_name($snru);
    session_start();
}
$params = array(
    "dsn" => $dsn,
    "table" => "usuario",
    "usernamecol" => "id_usuario",
    "passwordcol" => "password",
    "cryptType" => 'sha1',
);
$a = new Auth("DB", $params, "loginFunction");
$a->enableLogging = true;
$a->setSessionName($snru);
$a->setAuth('sivelpruebas');
$a->start();


?>
