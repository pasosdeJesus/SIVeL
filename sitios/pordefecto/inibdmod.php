<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Inicialización de base de datos y rutas
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2012 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */


// Mejor no empleamos sobrecarga porque no funciona en
// diversas versiones de PHP
if (!defined('DB_DATAOBJECT_NO_OVERLOAD')) {
    define('DB_DATAOBJECT_NO_OVERLOAD',1);
}

if (!isset($dbusuario)) {
    die("inibdmod.php debe incluirse después de incluir conf.php de un sitio");
}

/** DSN de la base de datos.  */
$dsn = "pgsql://$dbusuario:$dbclave@$dbservidor/$dbnombre";

require_once "PEAR.php";

require_once 'DB/DataObject.php';
require_once 'DB/DataObject/FormBuilder.php';

$options = &PEAR::getStaticProperty('DB_DataObject', 'options');
$options = array(
    'database' => $dsn,
    'schema_location' => $dirsitio . '/DataObjects',
    'class_location' => 'DataObjects/',
    'require_prefix' => 'DataObjects/',
    'class_prefix' => 'DataObjects_',
    'extends_location' => 'DataObjects_',
    'debug' => '0',
);

$_DB_DATAOBJECT_FORMBUILDER['CONFIG'] = array (
    'select_display_field' => 'nombre',
    'hidePrimaryKey' => false,
    'submitText' => 'Enviar',
    'linkDisplayLevel' => 2,
    'dbDateFormat' => 1,
    'dateElementFormat' => "d-m-Y",
    'useCallTimePassByReference' => 0
);


/* Rutas en particular donde haya subdirectorios DataObjects */
$rutas_include = ini_get('include_path').
    ":.:$dirserv:$dirserv/$dirsitio:$dirsitio:";
$lm = explode(" ", $modulos);
foreach ($lm as $m) {
    $rutas_include .= "$m:$m/DataObjects/:";
}

/* La siguiente requiere AllowOverride All en configuración de Apache */
ini_set('include_path', $rutas_include);

foreach($lm as $m) {
    if (file_exists("$m/conf.php")) {
        require_once "$m/conf.php";
    }
}
