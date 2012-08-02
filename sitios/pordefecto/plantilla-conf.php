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
$dbclave = "incocAcEd2";

/** Directorio de fuentes en servidor web */
$dirserv = "/users/vtamara/sivel12/";

/** Directorio del sitio relativo a $dirserv */
$dirsitio = "sitios/sivel12";

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


require 'sitios/pordefecto/inibdmod.php';
