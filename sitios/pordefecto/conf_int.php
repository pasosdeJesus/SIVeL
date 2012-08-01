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


// INTERFAZ DE USUARIO

/**
 * Color del fondo de la ficha de captura en notacion HTML
 * @global string $GLOBALS['ficha_color_fondo']
 */
$GLOBALS['ficha_color_fondo'] = '#EEE';


/** Cadena en caso de no existir usuario o clave */
$accno = "Acceso no autorizado\n";

/** Mensaje por presentar si se encuentran fallas ortográficas al validar
 * @global string $GLOBALS['MENS_ORTOGRAFIA']
 */
$GLOBALS['MENS_ORTOGRAFIA'] = 'Las palabras que estén bien por favor agreguelas al diccionario (%l).';

/** Mensaje por presentar en la página principal para indicar donde reportar fallas.
 * @global string $GLOBALS['REPORTA_FALLAS']
 */
$GLOBALS['REPORTA_FALLAS'] = "<a href=\"http://sivel.sf.net/\">" 
    .  _('Documentaci&oacute;n') 
    . "</a><br><a href=\"http://190.25.231.236/Divipola/Divipola.asp\" target=\"2\">DIVIPOLA</a><br>" . _("Por favor reporte fallas o requerimientos en el sistema de seguimiento disponible")
    . " <a href='http://sourceforge.net/tracker/?group_id=104373&atid=637817'>"
    . _("en l&iacute;nea") . "</a>";

/** Ancho en porcentaje de tablas en reporte general.
 * Puede cambiarse en caso de que tenga problemas al imprimir (por ejemplo
 * si las fuentes de su computador no son iguales a las de la impresora).
 * @global string $GLOBALS['ancho-tabla']
 */
$GLOBALS['ancho-tabla'] = "78%";

/** Determina si se indentan o no víctimas en reporte general y revista
 * @global string $GLOBALS['reporte_indenta_victimas']
 */
$GLOBALS['reporte_indenta_victimas'] = true;



/** Dirección de correo a la cual enviar mensajes cifrados.
 * @global string $GLOBALS['receptor_correo']
 */
$GLOBALS['receptor_correo'] = 'sivel@localhost';

/** Dirección de la cual provendrán mensajes cifrados.
 * @global string $GLOBALS['emisor_correo']
 */
$GLOBALS['emisor_correo'] = 'bancodat@nocheyniebla.org';

/** Cabezote en consulta_web.
 * Dejar '' si no hay
 * @global string $GLOBALS['cabezote_consulta_web']
 */
$GLOBALS['cabezote_consulta_web'] = '';

/** Pie en consulta_web.
 * Dejar '' si no hay
 * @global string $GLOBALS['pie_consulta_web']
 */
$GLOBALS['pie_consulta_web'] = '';

/** Pie en consulta_web publica.
 * Dejar '&nbsp;' si no hay
 * @global string $GLOBALS['pie_consulta_web_publica']
 */
$GLOBALS['pie_consulta_web_publica'] = '<div align="right"><a href="http://sivel.sourceforge.net/1.1/consultaweb.html">' . _('Documentación') .'</a></div>';

/** Cabezote para enviar correos desde consulta_web.
 * Dejar '' si no hay
 * @global string $GLOBALS['cabezote_consulta_web_correo']
 */
$GLOBALS['cabezote_consulta_web_correo'] = '';

/** Pie para enviar correos desde consulta_web.
 * Dejar '' si no hay
 * @global string $GLOBALS['pie_consulta_web_correo']
 */
$GLOBALS['pie_consulta_web_correo'] = '<hr/><a href="consulta_web.php">Consulta web</a>';

/** Archivo HTML que se pone como cabezote (antes del menú) del menú principal
 * Dejar '' si no hay
 * @global string $GLOBALS['cabezote_principal']
 */
$GLOBALS['cabezote_principal'] = '';

/** Archivo HTML que se pone en el centro del menú principal
 * Dejar '' si no hay
 * @global string $GLOBALS['centro_principal']
 */
$GLOBALS['centro_principal'] = 'centro_principal.html';

/** Imagen de fondo
 * @global string $GLOBALS['fondo']
 */
$GLOBALS['fondo']= $dirsitio . '/fondo.jpg';




/** Tablas básicas */
$GLOBALS['menu_tablas_basicas'] = array(
    array('title' => _('Información geográfica'), 'url'=> null, 'sub' => array(
        array('title'=>_('Departamento'), 'url'=>'departamento','sub'=>null),
        array('title'=>_('Municipio'), 'url'=>'municipio', 'sub'=>null),
        array('title'=>_('Tipo Clase'), 'url'=>'tipo_clase', 'sub'=>null),

        array('title'=>_('Clase'), 'url'=>'clase', 'sub'=>null),
        array('title'=>_('Región'), 'url'=>'region', 'sub'=>null),
        array('title'=>_('Frontera'), 'url'=>'frontera', 'sub'=>null),
        array('title'=>_('Tipo de Sitio'), 'url'=>'tipo_sitio', 'sub'=>null),
        ),
    ),
    array('title'=>_('Información implicado'), 'url'=> null, 'sub' => array(
        array('title'=>_('Etnia'), 'url'=>'etnia', 'sub'=>null),
        array('title'=>_('Filiación'), 'url'=>'filiacion', 'sub'=>null),
        array('title'=>_('Iglesia'), 'url'=>'iglesia', 'sub'=>null),
        array('title'=>_('Organización Social'), 'url'=>'organizacion', 'sub'=>null),
        array('title'=>_('Profesión'), 'url'=>'profesion', 'sub'=>null),
        array('title'=>_('Rango de Edad'), 'url'=>'rango_edad', 'sub'=>null),
        array('title'=>_('Resultado Agresión'), 'url'=>'resultado_agresion', 'sub'=>null),
        array('title'=>_('Sector Social'), 'url'=>'sector_social', 'sub'=>null),
        array('title'=>_('Tipo de Relación'), 'url'=>'tipo_relacion', 'sub'=>null),
        array('title'=>_('Vínculo con el Estado'), 'url'=>'vinculo_estado', 'sub'=>null),
        ),
    ),
    array('title'=>_('Información caso'), 'url'=> null, 'sub' => array(
        array('title'=>_('Tipo de Violencia'), 'url'=>'tipo_violencia', 'sub'=>null),
        array('title'=>_('Supracategoria'), 'url'=>'supracategoria', 'sub'=>null),
        array('title'=>_('Categoria'), 'url'=>'categoria', 'sub'=>null),
        array('title'=>_('Contexto'), 'url'=>'contexto', 'sub'=>null),
        array('title'=>_('Presuntos Responsables'), 'url'=>'presuntos_responsables', 'sub'=>null),
        array('title'=>_('Antecedentes'), 'url'=>'antecedente', 'sub'=>null),
        array('title'=>_('Intervalo'), 'url'=>'intervalo', 'sub'=>null),
        ),
    ),
    array('title'=>_('Información Fuentes'), 'url'=> null, 'sub' => array(
        array('title'=>_('Fuentes Frecuentes'), 'url'=>'prensa', 'sub'=>null),
        ),
    ),
    array('title'=>_('Reportes'), 'url'=> null, 'sub' => array(
        array('title'=>_('Columnas de Reporte Consolidado'),
            'url'=>'parametros_reporte_consolidado', 'sub'=>null),
        ),
    ),
);
/** Etiquetas que aparecen en la interfaz */
$GLOBALS['etiqueta'] = array(
// Caso
    'titulo' => _('Titulo'),
    'fecha' => _('Fecha'),
    'hora' => _('Hora'),
    'duracion' => _('Duración'),
    'tipo_ubicacion' => _('Tipo de Ubicación'),
    'id_intervalo' => _('Intervalo'),

// Ubicación
    'region' => _('Región'),
    'frontera' => _('Frontera'),

    'departamento' => _('Departamento'),
    'municipio' => _('Municipio'),
    'clase' => _('Clase'),

    'ubicacion' => _('Ubicación'),
    'lugar' => _('Lugar'),
    'sitio' => _('Sitio'),

// Fuente frecuente
    'id_prensa' => _('Fuente'),
    'fecha_fuente' => _('Fecha'),
    'ubicacion_fuente' => _('Ubicación'),
    'clasificacion_fuente' => _('Clasificación'),
    'ubicacion_fisica' => _('Ubicación Física'),

// Otras fuentes
    'nombre' => _('Nombre'),
    'ubicacion_fisica' => _('Ubicación Física'),
    'tipo_fuente' => _('Tipo de Fuente'),
    'anotacion' => _('Anotacion'),

// Tipo de violencia
    'clasificacion' => _('Contexto'),
    'contexto' => _('Contexto'),
    'antecedente' => _('Antecedente'),
    'bienes' => _('Bienes Afectados'),

// Presuntos responsables
    'p_responsable' => _('Presunto Responsable'),
    'p_responsables' => _('Presuntos Responsables'),
    'tipo' => _('Bando'),
    'bloque' => _('Bloque'),
    'frente' => _('Frente'),
    'brigada' => _('Brigada'),
    'batallon' => _('Batallón'),
    'division' => _('División'),
    'otro' => _('Otro'),

// Víctima Individual
    'victimas_individuales'=> _('Víctimas Individuales'),
//    'nombre'=> _('Nombre'),
    'edad'=> _('Edad'),
    'hijos'=> _('Hijos'),
    'sexo'=> _('Sexo'),
    'profesion'=> _('Profesión'),
    'rango_edad'=> _('Rango de Edad'),
    'filiacion'=> _('Filiación Política'),
    'sector_social'=> _('Sector Social'),
    'organizacion'=> _('Organización Social'),
    'vinculo_estado'=> _('Vínculo con el Estado'),
    'organizacion_armada'=> _('Organización Armada Víctima'),
    'anotaciones_victima' => _('Anotaciones'),

    'p_responsable'=> _('Presunto Responsable'),
    'antecedentes'=> _('Antecedentes'),
    'tipo_violencia'=> _('Tipo Violencia'),

// Víctima Colectiva
    'victimas_colectivas'=> _('Víctimas Colectivas'),
//   'nombre' => _('Nombre'),
//   'organizacion_armada'=> _('Organización Armada Víctima'),
    'personas_aprox' => _('Num. Aprox. Personas'),
    'anotacion' => _('Anotaciones'),

//    'tipo_violencia' =>
//    'antecedentes' =>
//    'p_responsable'=> _('Presunto Responsable'),
//    'rango_edad'=> _('Rango de Edad'),
//    'sector_social'=> _('Sector Social'),
//    'vinculo_estado'=> _('Vínculo con el Estado'),
//    'filiacion'=> _('Filiación Política'),
//    'profesion'=> _('Profesión'),
//    'organizacion'=> _('Organización Social'),


// Víctima Combatiente
    'victimas_combatientes'=> _('Víctimas Combatientes'),
//       'nombre'=> _('Nombre'),
    'alias'=> _('Alias'),
//       'edad'=> _('Edad'),
//       'sexo'=> _('Sexo'),
//       'rango_edad' => _('Rango de Edad'),
//       'sector_social'=> _('Sector Social'),
//       'vinculo_estado'=> _('Vínculo Estado'),
//       'filiacion'=> _('Filiación Política'),
//       'profesion'=> _('Profesion'),
//       'organizacion'=> _('Organización Social'),
//       'organizacion_armada'=> _('Organización Armada'),
    'resultado_agresion'=> _('Resultado Agresión'),

//Actos
    'Actos' => _('Actos'),
//Memo
    'memo' => _('Memo'),

//Evaluación
    'gr_confiabilidad' => _('Gr. Confiabilidad Fuente'),
    'gr_esclarecimiento' => _('Gr.Esclarecimiento'),
    'gr_impunidad' => _('Gr. Impunidad'),
    'gr_informacion' => _('Gr. Informacion'),

// Otros
    'analista' => _('Analista'),
    'meses' => _('Meses'),
    'victimas' => _('Víctimas'),

// Pestañas

    'PagBasicos' => _('Datos básicos'),
    'PagUbicacion' => _('Ubicación'),
    'PagFuentesFrecuentes' => _('Fuentes Frecuentes'),
    'PagOtrasFuentes' => _('Otras Fuentes'),
    'PagTipoViolencia' => _('Contexto'),
    'PagPResponsables' => _('Presuntos Responsables'),
    'PagVictimaColectiva' => _('Víctima Colectiva'),
    'PagVictimaIndividual' => _('Víctimas Individuales'),
    'PagVictimaCombatiente' => _('Víctima Combatiente'),
    'PagMemoPagEvaluacion' => _('Evaluación'),

);

// Opciones del menú
//

// $GLOBALS['modulo'][1] = 'sitios/misitio/miopcion.php';

/** Campos que pueden elegirse en consultas */
$GLOBALS['cw_ncampos'] = array('caso_id' => _('Código'),
    'caso_memo' => _('Descripción'),
    'caso_fecha' => _('Fecha'),
    'm_ubicacion' => _('Ubicación'),
    'm_victimas' => _('Víctimas'),
    'm_presponsables' => _('Pr. Resp.'),
    'm_tipificacion' => _('Tipificación'),
    #'m_observaciones' => 'Observaciones',
    #'m_anexos' => 'Anexos',
);


$GLOBALS['m_rol'] = array (
    1 => _('Administrador'),
    2 => _('Analista'),
    3 => _('Consulta'),
    4 => _('Ayudante'),
);

$GLOBALS['m_opcion'] = array (
    10 => array('nombre' => _('Administración'), 'idpapa' => 0, 'url' => NULL),
    11 => array(
        'nombre' => _('Tablas Básicas'), 'idpapa' => 10,
        'url' => 'tablas_basicas'
    ),
    12 => array('nombre' => _('Usuarios'), 'idpapa' => 10, 'url' => 'usyroles'),
    20 => array('nombre' => _('Caso'), 'idpapa' => 0, 'url' => NULL),
    21 => array('nombre' => _('Ficha'), 'idpapa' => 20, 'url' => 'captura_caso'),
    30 => array('nombre' => _('Consultas'), 'idpapa' => 0, 'url' => NULL ),
    31 => array(
        'nombre' => _('Consulta Detallada'), 'idpapa' => 30, 'url' => 'consulta'
    ),
    32 => array(
        'nombre' => _('Consulta Web'), 'idpapa' => 30, 'url' => 'consulta_web'
    ),
    40 => array('nombre' => _('Reportes'), 'idpapa' => 0, 'url' => NULL),
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
        'url' => 'consulta_web?mostrar=general&orden=localizacion'
    ),
    45 => array(
        'nombre' => _('Revista con código'), 'idpapa' => 40,
        'url' => 'consulta_web?mostrar=revista'
    ),
    50 => array('nombre' => _('Conteos'), 'idpapa' => 0, 'url' => NULL),
    51 => array(
        'nombre' => _('V. Individuales'), 'idpapa' => 50,
        'url' => 'estadisticas'
    ),
    60 => array('nombre' => _('Otros'), 'idpapa' => 0, 'url' => NULL),
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
    69 => array('nombre' => _('Salir'), 'idpapa' => 60, 'url' => 'terminar'),
);



/* Mensajes para formularios */

$mreq = '<span style = "font-size:80%; color:#ff0000;">*</span>
    <span style = "font-size:80%;"> '
    . _('marca un campo requerido') . '</span>';

/**
 * Meses
 * @global array $GLOBALS['mes']
 * @name   $mes
 */
$GLOBALS['mes'] = array(
    1=> _('Enero'), 2=> _('Febrero'), 3=> _('Marzo'),
    4=> _('Abril'), 5=> _('Mayo'), 6=> _('Junio'),
    7=> _('Julio'), 8=> _('Agosto'), 9=> _('Septiembre'),
    10=> _('Octubre'), 11=> _('Noviembre'), 12=> _('Diciembre')
);

/**
 * Nombres cortos de meses
 * @global array $GLOBALS['mes_corto']
 * @name   $mes_corto
 */
$GLOBALS['mes_corto'] = array(
    1=> _('Ene'), 2=> _('Feb'), 3=> _('Mar'),
    4=> _('Abr'), 5=> _('May'), 6=> _('Jun'),
    7=> _('Jul'), 8=> _('Ago'), 9=> _('Sep'),
    10=> _('Oct'), 11=> _('Nov'), 12=> _('Dic')
);


/**
 * mensaje de campos indispensables
 * @global string $GLOBALS['mreglareq']
 * @name   $mreglareq
 */
$GLOBALS['mreglareq'] = _('El campo %s es indispensable.');

/**
 * Mensaje de valores no válidos
 * @global string $GLOBALS['mreglavio']
 * @name   $mreglavio
 */
$GLOBALS['mreglavio'] = '%s: El valor que ha ingresado no es válido.';

/**
 * Campos por mostrar por defecto en reportes
 * @global array $GLOBALS['cw_ncampos']
 * @name   $cw_ncampos
 */
if (!isset($GLOBALS['cw_ncampos'])) {
    $GLOBALS['cw_ncampos'] = array(
        'caso_id' => _('Código'),
        'caso_memo' => _('Descripción'),
        'caso_fecha' => _('Fecha'),
        'm_ubicacion' => _('Ubicación'),
        'm_victimas' => _('Víctimas'),
        'm_presponsables' => _('P. Resp.'),
        'm_tipificacion' => _('Tipificación')
    );
}



