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

global $dirsitio;

if ($_SESSION['dirsitio'] != $dirsitio) {
    $n1 = $_SESSION['dirsitio'] . "/conf.php";
    $n2 = $dirsitio . "/conf.php";
    $s1 = stat($n1);
    $s2 = stat($n2);
    if (!isset($s1[1]) || !isset($s2[1]) || $s1[1] != $s2[1]) {
        echo "<hr>$n1: ";var_dump($s1); //OJO sin escapar
        echo "<hr>$n2: ";var_dump($s2); //OJO sin escapar
        echo "<hr>Son diferentes _SESSION['dirsitio'] "
            . " y GLOBALS['dirsitio'] < br>";
        echo "Configurar primero<hr>";
        exit(1);
    }
}

// Mejor no empleamos sobrecarga porque no funciona en
// diversas versiones de PHP
if (!defined('DB_DATAOBJECT_NO_OVERLOAD')) {
    define('DB_DATAOBJECT_NO_OVERLOAD', 1);
}

if (!isset($dbusuario)) {
    die("inibdmod.php debe incluirse después de incluir conf.php de un sitio");
}

global $dsn;
global $dbusuario;
global $dbclave;
global $dbservidor;
global $dbnombre;
/** DSN de la base de datos.  */
$dsn = "pgsql://$dbusuario:$dbclave@$dbservidor/$dbnombre";

require_once "PEAR.php";

require_once 'DB/DataObject.php';
require_once 'DB/DataObject/FormBuilder.php';

global $pear;
$pear = new PEAR();
$options =& $pear->getStaticProperty('DB_DataObject', 'options');
$options['database'] = $dsn;
$options['schema_location'] = $dirsitio . '/DataObjects';
$options['class_location'] = 'DataObjects/';
$options['require_prefix'] = 'DataObjects/';
$options['class_prefix'] = 'DataObjects_';
$options['extends_location'] = 'DataObjects_';
$options['debug'] = isset($GLOBALS['DB_Debug']) ? $GLOBALS['DB_Debug'] : '0';
    //'disable_null_strings' => 'full'

global $_DB_DATAOBJECT_FORMBUILDER;
$_DB_DATAOBJECT_FORMBUILDER['CONFIG'] = array (
    'select_display_field' => 'nombre',
    'hidePrimaryKey' => false,
    'submitText' => 'Enviar',
    'linkDisplayLevel' => 2,
    'dbDateFormat' => 1,
    'dateElementFormat' => "d-m-Y",
    'useCallTimePassByReference' => 0
);


if (!function_exists("esta_nueva_ficha")) {
    /**
     * Determina si una ficha con la identificación dada ya está en las
     * programas para agregar
     *
     * @param string $id Id de ficha
     *
     * @return true sii una ficha con la id dada ya está en nuevas
     */
    function esta_nueva_ficha($id)
    {
        //echo "OJO esta_nueva_ficha($id)<br>";
        foreach ($GLOBALS['nueva_ficha_tabuladores'] as $a) {
            $puesto = $a[0];
            $nom = $a[1];
            $arc = $a[2];
            $puestoelim = $a[3];
            if ($nom == $id) {
                return true;
            }
        }
        return false;
    }
}

global $modulos;
/* Rutas en particular donde haya subdirectorios DataObjects */
$rutas_include = ini_get('include_path').
    ":.:$dirserv:$dirserv/$dirsitio:$dirsitio:";
//echo "OJO modulos=$modulos<br>\n";
$lm = explode(" ", $modulos);
foreach ($lm as $m) {
    $rutas_include .= "$m:$m/DataObjects/:";
}

/* La siguiente requiere AllowOverride All en configuración de Apache */
ini_set('include_path', $rutas_include);

foreach ($lm as $m) {
    //echo "OJO modulo $m<br>\n";
    if (file_exists("$m/conf.php")) {
        //echo "OJO existe<br>\n";
        include "$m/conf.php";
    }
}
//echo "<hr>OJO antes de remplaza " ;print_r($GLOBALS['ficha_tabuladores']);
if (isset($GLOBALS['remplaza_ficha_tabuladores'])) {
    foreach ($GLOBALS['remplaza_ficha_tabuladores'] as $a) {
        $nom = $a[0];
        $arc = $a[1];
        $nft = array();
        for ($nf = 0;
        $nf < count($GLOBALS['ficha_tabuladores']);
        $nf++
        ) {
            $f = $GLOBALS['ficha_tabuladores'][$nf];
            if ($f[0] == $nom) {
                $f[1] = $arc;
            }
            $nft[$nf] = $f;
        }
        $GLOBALS['ficha_tabuladores'] = $nft;
    }
}

//echo "<hr>OJO antes de elimina " ;print_r($GLOBALS['ficha_tabuladores']);
if (isset($GLOBALS['elimina_ficha_tabuladores'])) {
    foreach ($GLOBALS['elimina_ficha_tabuladores'] as $idf) {
        $puesto = -1;
        for ($nf = 0;
        $nf < count($GLOBALS['ficha_tabuladores']);
        $nf++
        ) {
            $f = $GLOBALS['ficha_tabuladores'][$nf];
            if ($f[0] == $idf) {
                $puestoelim = $f[2];
                $puesto = $nf;
                break;
            }
        }
        //echo "OJO puesto por eliminar: $puesto<br>\n";
        if ($puesto >= 0) {
            $nft = array();
            for ($nf = 0;
            $nf < count($GLOBALS['ficha_tabuladores']) - 1;
            $nf++
            ) {
                if ($nf < $puesto) {
                    $f = $GLOBALS['ficha_tabuladores'][$nf];
                } else if ($nf >= $puesto) {
                    $f = $GLOBALS['ficha_tabuladores'][$nf + 1];
                }
                $fpe = $f[2];
                if ($fpe > $puestoelim) {
                    $f[2] = $fpe - 1;
                }
                $nft[$nf] = $f;
            }
            //echo "Tras eliminar 1: "; print_r($nft); echo "<br>";
            $GLOBALS['ficha_tabuladores'] = $nft;
        }
    }
}

//echo "<hr>OJO antes de nuevas " ;print_r($GLOBALS['ficha_tabuladores']);
if (isset($GLOBALS['nueva_ficha_tabuladores'])) {
    foreach ($GLOBALS['nueva_ficha_tabuladores'] as $a) {
        $puesto = $a[0];
        $nom = $a[1];
        $arc = $a[2];
        $puestoelim = $a[3];
        //echo "OJO Por agregar $nom<br>\n";
        $nft = array();
        $incluida = false;
        for ($nf = 0; $nf < count($GLOBALS['ficha_tabuladores']); $nf++) {
            $f = $GLOBALS['ficha_tabuladores'][$nf];
            $fpe = $f[2];
            if ($fpe >= $puestoelim) {
                $f[2] = $fpe + 1;
            }
            if ($nf < $puesto) {
                $nft[$nf] = $f;
            } else if ($nf == $puesto) {
                $nft[$nf] = array($nom, $arc, $puestoelim);
                $nft[$nf + 1] = $f;
                $incluida = true;
            } else {
                $nft[$nf + 1] = $f;
            }
            //echo "OJO nft="; print_r($nft); echo "<br>";
        }
        if (!$incluida) {
            $nft[$nf] = array($nom, $arc, $puestoelim);
        }
        $GLOBALS['ficha_tabuladores'] = $nft;
    }
}

//echo "<hr>OJO Final inibd<br>\n"; var_dump($GLOBALS['ficha_tabuladores']);
