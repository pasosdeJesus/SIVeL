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

global $dbservidor;
/** Servidor/socket del Motor de bases de datos */
// Si prefiere TCP/IP (no recomendado) use tcp(localhost)
$dbservidor = "unix(/tmp)";

global $dbnombre;
/** Nombre de base de datos */
$dbnombre = "sivel";

global $dbusuario;
/** Usuario del MBD */
$dbusuario = "sivel";

global $dbclave;
/** Clave del usuario ante el MBD */
$dbclave = "xyz";

global $socketopt;
/** Opciones especiales para acceder base de datos desde consola */
$socketopt = "-h /var/www/tmp";

global $dirchroot;
/** Directorio en el que correo servidor web */
$dirchroot = "/var/www";

global $dirserv;
/** Directorio de fuentes en servidor web */
$dirserv = "/htdocs/sivel";

global $dirsitio;
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

/** Indica si debe deshabilitazarse consulta pública de consulta_web.php
 * @global string $GLOBALS['consulta_publica_deshabilitada']
 */
$GLOBALS['consulta_publica_deshabilitada'] = false;

/** Fecha máxima de caso por usar en consulta web.
 * año-mes-año
 * @global string $GLOBALS['consulta_web_fecha_max']
 */
$GLOBALS['consulta_web_fecha_max'] = '2024-11-30';

/** Fecha mínima de caso por consultar en web
 * @global string $GLOBALS['consulta_web_fecha_min']
 */
$GLOBALS['consulta_web_fecha_min'] = '1990-1-1';

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

/** Consulta adicional para determinar casos en los que aparece una
 * persona además de lo estándar de SIVeL básico (ver json_persona.php).
 * Emplear id para comparar identificación de persona.
 * @global bool $GLOBALS['persona_en_caso']
*/
$GLOBALS['persona_en_caso'] = '';


// Opciones de Reporte Tabla
$GLOBALS['reporte_tabla_fila_totales'] = false;


// VOLCADOS  - COPIAS DE RESPALDO LOCALES

global $imagenrlocal;
/** Contenedor cifrado de volcados */
$imagenrlocal = "/var/resbase.img";

global $rlocal;
/** Directorio local donde quedara volcado diarío del último mes
 * Se espera que se almacene en el contenedor cifrado.
 */
$rlocal = "/var/www/resbase";

global $copiaphp;
/**
 * Se copian fuentes de PHP en directorio de respaldos?
 */
$copiaphp = false;

// COPIAS DE RESPALDO REMOTAS

global $rremotos;
/** Destinos a los cuales copiar volcado diario de la última semana.
 * e.g "usuario1@maquina1: usuario2@maquina2:" */
$rremotos = "";

global $llave;
/** Llave ssh. Generela con ssh-keygen sin clave, el dueño debe ser quien
 * ejecuta el script respaldo.sh, no debe permitir lectura de nadie más */
$llave = "/home/miusuario/.ssh/rsa_respaldo";


// PUBLICACIÓN EN PÁGINA WEB

global $usuarioact;
/**  Usuario */
$usuarioact = "sivel";

global $maquinaweb;
/** Comptuador al cual copiar */
$maquinaweb = "otramaquina";

global $dirweb;
/** Directorio */
$dirweb = "/tmp";

global $opscpweb;
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

/** Nivel de depuración para DB_DataObject. 0 a 5.
 * @global string $GLOBALS['DB_Debug']
 */
$GLOBALS['DB_Debug'] = 0;

/** Pestañas de la Ficha  de captura
    'id', 'Clase', 'orden en eliminación (no rep)' */
$GLOBALS['ficha_tabuladores'] = array(
    0 => array('basicos', 'PagBasicos', 11),
    1 => array('ubicacion', 'PagUbicacion', 4),
    2 => array('frecuentes', 'PagFuentesFrecuentes', 7),
    3 => array('otras', 'PagOtrasFuentes', 9),
    4 => array('tipoViolencia', 'PagTipoViolencia', 5),
    5 => array('pResponsables', 'PagPResponsables', 6),
    6 => array('victimaIndividual', 'PagVictimaIndividual', 2),
    7 => array('victimaColectiva', 'PagVictimaColectiva',3),
    8 => array('acto', 'PagActo', 1),
    9 => array('memo', 'PagMemo', 8),
    10 => array('evaluacion', 'PagEvaluacion', 10)
);

/** Solicitudes de nuevas pestañas en ficha de captura */
$GLOBALS['nueva_ficha_tabuladores'] = array();

// MODULOS
global $modulos;
/** Módulos empleados (relativos a directorio con fuentes) */
$modulos = "modulos/anexos modulos/etiquetas modulos/mapag";


// ROLES Y OPCIONES MENU

/** Roles
 * @global string $GLOBALS['m_rol']
 */
$GLOBALS['m_rol'] = array (
    1 => _('Administrador'),
    2 => _('Analista'),
    3 => _('Consulta'),
    4 => _('Ayudante'),
);


/** Opciones del menú principal
 * @global string $GLOBALS['m_opcion']
 */
$GLOBALS['m_opcion'] = array (
    10 => array('nombre' => _('Administración'), 'idpapa' => 0, 'url' => null),
    11 => array(
        'nombre' => _('Tablas Básicas'), 'idpapa' => 10,
        'url' => 'tablas_basicas'
    ),
    12 => array('nombre' => _('Usuarios'), 'idpapa' => 10, 'url' => 'usyroles'),
    20 => array('nombre' => _('Caso'), 'idpapa' => 0, 'url' => null),
    21 => array('nombre' => _('Ficha'), 'idpapa' => 20, 'url' => 'captura_caso'),
    30 => array('nombre' => _('Consultas'), 'idpapa' => 0, 'url' => null),
    31 => array(
        'nombre' => _('Consulta Detallada'), 'idpapa' => 30, 'url' => 'consulta'
    ),
    32 => array(
        'nombre' => _('Consulta Web'), 'idpapa' => 30, 'url' => 'consulta_web'
    ),
    40 => array('nombre' => _('Reportes'), 'idpapa' => 0, 'url' => null),
    41 => array(
        'nombre' => _('Revista'), 'idpapa' => 40,
        'url' => 'consulta_web?mostrar=revista&sincampos=caso_id'
    ),
    42 => array(
        'nombre' => _('General'), 'idpapa' => 40,
        'url' => 'consulta_web?mostrar=general'
    ),
    43 => array(
        'nombre' => _('Consolidado'), 'idpapa' => 40, 'url' => 'consolidado'
    ),
    44 => array(
        'nombre' => _('General por Localizacion'), 'idpapa' => 40,
        'url' => 'consulta_web?mostrar=general&orden=ubicacion'
    ),
    45 => array(
        'nombre' => _('Revista con código'), 'idpapa' => 40,
        'url' => 'consulta_web?mostrar=revista'
    ),
    50 => array('nombre' => _('Conteos'), 'idpapa' => 0, 'url' => null),
    51 => array(
        'nombre' => _('V. Individuales'), 'idpapa' => 50,
        'url' => 'estadisticas'
    ),
    60 => array('nombre' => _('Otros'), 'idpapa' => 0, 'url' => null),
    61 => array(
        'nombre' => _('Importar Relatos'), 'idpapa' => 60,
        'url' => 'importaRelato'
    ),
    62 => array(
        'nombre' => _('Completar Actos'), 'idpapa' => 60,
        'url' => 'completaActos'
    ),
    63 => array(
        'nombre' => _('Actualizar'), 'idpapa' => 60,
        'url' => 'actualiza'
    ),
    64 => array('nombre' => _('Validar'), 'idpapa' => 60, 'url' => 'valida'),
    65 => array(
        'nombre' => _('Buscar repetidos'), 'idpapa' => 60,
        'url' => 'buscaRepetidos'
    ),
    6900 => array('nombre' => _('Salir'), 'idpapa' => 60, 'url' => 'terminar'),
);

/** Roles para los que está disponible cada opción del menú
 * @global string $GLOBALS['m_opcion']
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


