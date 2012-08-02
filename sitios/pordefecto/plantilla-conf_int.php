<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Variables de configuración de la interfaz de usuario.
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

require 'sitios/pordefecto/conf_int.php';

// A CONTINUACIÓN REDEFICIÓN DE VARIABLES DE CONFIGURACIÓN DE INTERFAZ.
// TOME LAS POSIBLES OPCIONES DE CONFIGURACIÓN sitios/pordefecto/conf_int.php
// ASÍ COMO DE LOS ARCHIVOS conf_int.php DE LOS MÓDULOS del directorio modulos/

// ESTE ARCHIVO ES INCLUDO DESPUÉS DE QUE SE HA DEFINIDO EL IDIOMA DE LA 
// INTERFAZ 

/**
 * Imagen de fondo en pantalla principal
 * @global string $GLOBALS['fondo']
 */
$GLOBALS['fondo']= $dirsitio . '/fondo-en.jpg';

// PARTICULARIDADES

/**
 * Color del fondo de la ficha de captura en notacion HTML
 * @global string $GLOBALS['ficha_color_fondo']
 */
$GLOBALS['ficha_color_fondo'] = '#EEE';


require 'sitios/pordefecto/iniint.php';
