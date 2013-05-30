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

require 'sitios/pordefecto/conf.php';

// A CONTINUACIÓN REDEFICIÓN DE VARIABLES DE CONFIGURACIÓN.
// LAS POSIBLES OPCIONES DE CONFIGURACIÓN TOMELAS DE sitios/pordefecto/conf.php
// ASÍ COMO DE LOS ARCHIVOS conf.php DE LOS MÓDULOS del directorio modulos/

/** Nombre de base de datos */
$dbnombre = "sivel12";

/** Usuario del MBD */
$dbusuario = "sivel";

/** Clave del usuario ante el MBD */
$dbclave = "super";

/** Directorio de fuentes en servidor web */
$dirserv = "/htdocs/sivel/";

/** Directorio del sitio relativo a $dirserv */
$dirsitio = "sitios/sivel";

/** Palabra clave para algunos cifrados.
 * @global string $GLOBALS['PALABRA_SITIO']
 */
$GLOBALS['PALABRA_SITIO'] = 'sigamos el ejemplo de Jesús';


// RELATOS

/** Organización responsable, aparecerá al exportar relatos
 * @global string $GLOBALS['organizacion_responsable']
 */
$GLOBALS['organizacion_responsable'] = 'D';

/**
 * Prefijo para nombres de archivo de relatos
 * @global string $GLOBALS['PREF_RELATOS']
 */
$GLOBALS['PREF_RELATOS'] = 'org';


/** Derechos de reproducción por defecto, aparecerán al exportar relatos
 * @global string $GLOBALS['derechos']
 */
$GLOBALS['derechos'] = 'Dominio Público';

/** Módulos empleados (relativos a directorio con fuentes) */
$modulos = "modulos/anexos modulos/etiquetas modulos/mapag";


if (file_exists($_SESSION['dirsitio'] . '/conf-particular.php')) {
    require $_SESSION['dirsitio'] . '/conf-particular.php';
}

require 'sitios/pordefecto/inibdmod.php';

// Sobrecarga a configuración de módulos



