<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Variables de configuración relacionadas con servidor y fuentes.
 * Basado en script de configuración http://structio.sourceforge.net/seguidor
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

/** Servidor/socket del Motor de bases de datos */
$dbservidor = "unix(/tmp)"; # Si prefiere TCP/IP (no recomendado) use tcp(localhost)

/** Nombre de base de datos */
$dbnombre = "sivel";

/** Usuario del MBD */
$dbusuario = "sivel";

/** Clave del usuario ante el MBD */
$dbclave = "xyz";

/** Opciones especiales para acceder base de datos desde consola */
$socketopt = "-h /var/www/tmp";

/** Directorio en el que correo servidor web */
$dirchroot = "/var/www";

/** Directorio de fuentes en servidor web */
$dirserv = "/htdocs/sivel";

/** Directorio del sitio relativo a $dirserv */
$dirsitio = "sitios/sivel";

// RELATOS

/**
 * Directorio con relatos
 * @global string $GLOBALS['DIR_RELATOS']
 */
$GLOBALS['DIR_RELATOS'] = '/sincodh-publico/relatos/';

/**
 * Prefijo para nombres de archivo de relatos
 * @global string $GLOBALS['PREF_RELATOS']
 */
$GLOBALS['PREF_RELATOS'] = 'org';

/**
 * Estilo: nombres y apellidos de victimas.
 * MAYUSCULAS seria JOSE PEREZ
 * a_minusculas seria Jose Perez
 *
 * @global string $GLOBALS['estilo_nombres']
 */
$GLOBALS['estilo_nombres'] = 'MAYUSCULAS';


// VOLCADOS  - COPIAS DE RESPALDO LOCALES

/** Contenedor cifrado de volcados */
$imagenrlocal = "/var/resbase.img";

/** Directorio local donde quedara volcado diarío del último mes
 * Se espera que se almacene en el contenedor cifrado.
 */
$rlocal = "/var/www/resbase";

/**
 * Se copian fuentes de PHP en directorio de respaldos?
 */
$copiaphp = false;

// COPIAS DE RESPALDO REMOTAS

/** Destinos a los cuales copiar volcado diario de la última semana.
 * e.g "usuario1@maquina1: usuario2@maquina2:" */
$rremotos = "";

/** Llave ssh. Generela con ssh-keygen sin clave, el dueño debe ser quien
 * ejecuta el script respaldo.sh */
$llave = $dirchroot . $dirserv . $dirsitio . "/id_rsa";

// PARTICULARIDADES

/** Incluir iglesias cristianas en ficha
 * @global string $GLOBALS['iglesias_cristianas']
 */
$GLOBALS['iglesias_cristianas'] = true;


// PUBLICACIÓN EN PÁGINA WEB

/**  Usuario */
$usuarioact = "sivel";

/** Comptuador al cual copiar */
$maquinaweb = "otramaquina";

/** Directorio */
$dirweb = "/tmp";

/** Opciones para scp de actweb, e.g -i ... */
$opscpweb = "";


// Mejor no empleamos sobrecarga porque no funciona en
// diversas versiones de PHP
if (!defined('DB_DATAOBJECT_NO_OVERLOAD')) {
    define('DB_DATAOBJECT_NO_OVERLOAD',1);
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

// MODULOS

/** Módulos empleados (relativos a directorio con fuentes) */
$modulos = "modulos/anexos modulos/etiquetas modulos/mapag";

/** Directorio donde se almacenan anexos */
$GLOBALS['dir_anexos'] = '/resbase/anexos';

// Opciones de Reporte Tabla
$GLOBALS['reporte_tabla_fila_totales'] = false;

// Opciones del menú

// $GLOBALS['modulo'][100] = 'modulos/estrotulos/estrotulos.php';
// $GLOBALS['modulo'][101] = 'modulos/estrotulos/estcolectivas.php';
// $GLOBALS['modulo'][200] = 'modulos/belicas/estadisticas_comb.php';
$GLOBALS['modulo'][300] = 'modulos/mapag/index.php';

// Posibilidades de módulos
// $GLOBALS['consultaweb_ordenarpor'][0] = "rotulos_cwebordenar";
// $GLOBALS['gancho_rc_reginicial'][0] = "rotulos_inicial";
// $GLOBALS['gancho_rc_regfinal'][0] = "rotulos_final";
// $GLOBALS['misc_ordencons'][0] = "rotulos_orden_cons";


/* Rutas en particular donde haya subdirectorios DataObjects */
$rutas_include = ini_get('include_path').
    ":.:$dirserv:$dirserv/$dirsitio:$dirsitio:";
$lm = explode(" ", $modulos);
foreach ($lm as $m) {
    $rutas_include .= "$m:$m/DataObjects/:";
}

/* La siguiente requiere AllowOverride All en configuración de Apache */
ini_set('include_path', $rutas_include);

/** Palabra clave para algunos cifrados.
 * @global string $GLOBALS['PALABRA_SITIO']
 */
$GLOBALS['PALABRA_SITIO'] = 'sigamos el ejemplo de Jesús';

/** Deshabilita operaciones con usuarios
 * @global string $GLOBALS['deshabilita_manejo_usuarios']
 */
$GLOBALS['deshabilita_manejo_usuarios'] = false;

/** Pestañas de la Ficha  de captura
    'id', 'Clase', 'orden en eliminación (no rep)' */
$GLOBALS['ficha_tabuladores'] = array(
    0 => array('basicos', 'PagBasicos', 13),
    1 => array('ubicacion', 'PagUbicacion', 4),
    2 => array('frecuentes', 'modulos/anexos/PagFrecuenteAnexo', 7),
    3 => array('otras', 'modulos/anexos/PagOtraAnexo', 9),
    4 => array('tipoViolencia', 'PagTipoViolencia', 5),
    5 => array('pResponsables', 'PagPResponsables', 6),
    6 => array('victimaIndividual', 'PagVictimaIndividual', 2),
    7 => array('victimaColectiva', 'PagVictimaColectiva',3),
    8 => array('acto', 'PagActo', 1),
    9 => array('memo', 'PagMemo', 8),
    10 => array('anexos', 'modulos/anexos/PagAnexo', 10),
    11 => array('etiquetas', 'modulos/etiquetas/PagEtiquetas', 11),
    12 => array('evaluacion', 'PagEvaluacion', 12)
);


