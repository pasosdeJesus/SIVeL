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
 * Estilo: nombres y apellidos de victimas.
 * MAYUSCULAS seria JOSE PEREZ
 * a_minusculas seria Jose Perez
 *
 * @global string $GLOBALS['estilo_nombres']
 */
$GLOBALS['estilo_nombres'] = 'MAYUSCULAS';

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

/** Incluir iglesias cristianas en ficha
 * @global string $GLOBALS['iglesias_cristianas']
 */
$GLOBALS['iglesias_cristianas'] = true;


/** Fecha máxima de caso por usar en consulta web.
 * año-mes-año
 * @global string $GLOBALS['consulta_web_fecha_max']
 */
$GLOBALS['consulta_web_fecha_max'] = '2024-11-30';

/** Fecha mínima de caso por consultar en web
 * @global string $GLOBALS['consulta_web_fecha_min']
 */
$GLOBALS['consulta_web_fecha_min'] = '2001-1-1';

/** Máximo de registros por retornar en una consulta web (0 es ilimitado)
 * @global string $GLOBALS['consulta_web_max']
 */
$GLOBALS['consulta_web_max']=4000;

/** Año mínimo que puede elegirse en fechas de la Ficha
 * @global string $GLOBALS['anio_min']
 */
$GLOBALS['anio_min']=1990;

/** Indica si en la pestaña Actos deben presentarse actos colectivos
 * @global bool $GLOBALS['actoscolectivos']
*/
$GLOBALS['actoscolectivos'] = true;

// Opciones de Reporte Tabla
$GLOBALS['reporte_tabla_fila_totales'] = false;


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


// PUBLICACIÓN EN PÁGINA WEB

/**  Usuario */
$usuarioact = "sivel";

/** Comptuador al cual copiar */
$maquinaweb = "otramaquina";

/** Directorio */
$dirweb = "/tmp";

/** Opciones para scp de actweb, e.g -i ... */
$opscpweb = "";

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

/** Solicitudes de nuevas pestañas en ficha de captura */
$GLOBALS['nueva_ficha_tabuladores'] = array();

// MODULOS

/** Módulos empleados (relativos a directorio con fuentes) */
$modulos = "modulos/anexos modulos/etiquetas modulos/mapag";


