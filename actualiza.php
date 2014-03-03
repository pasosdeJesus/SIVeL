<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
* Actualiza base de datos después de actualizar fuentes
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2006 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 */

/** Actualiza base de datos después de actualizar fuentes */
require_once "aut.php";
require_once $_SESSION['dirsitio'] . '/conf.php';

$aut_usuario = "";
$db = autentica_usuario($dsn, $aut_usuario, 21);

require_once $_SESSION['dirsitio'] . '/conf_int.php';
require_once "confv.php";
require_once "misc.php";
require_once "DataObjects/Categoria.php";
require_once "misc_actualiza.php";

if (!in_array(63, $_SESSION['opciones'])) {
    hace_consulta(
        $db, "INSERT INTO opcion VALUES ('63', 'Actualizar', '60', " .
        "'actualiza')", false, false
    );
    hace_consulta(
        $db, "INSERT INTO opcion_rol (id_opcion, id_rol) " .
        "VALUES (63, 1)", false, false
    );
    include "terminar.php";
    die("");
}
$db = autentica_usuario($dsn, $aut_usuario, 63);

/**
 * Regenera esquemas para base de datos base.ini y base.links.in
 * sacando informacion de los archivos estructura.ini y estructura.links.ini
 * de SIVeL basico y de los modulos.
 *
 * @return void
 */
function regenera_esquemas()
{
    global $dirserv, $dirsitio, $dbnombre, $dirchroot, $modulos;
    $nini = "$dirserv/$dirsitio/DataObjects/$dbnombre.ini";
    $nlinksini = "$dirserv/$dirsitio/DataObjects/$dbnombre.links.ini";
    $nestini = "$dirserv/$dirsitio/DataObjects/estructura-dataobject.ini";
    if (is_writable($nini) && is_writable($nlinksini)) {
        lee_escritura($dirserv, $dbnombre, "$dirserv/$dirsitio", "w");
        foreach (explode(" ", $modulos) as $i) {
            lee_escritura("$dirserv/$i", $dbnombre, "$dirserv/$dirsitio", "a");
        }
        if (file_exists($nestini)) {
            lee_escritura(
                "$dirserv/$dirsitio", $dbnombre, "$dirserv/$dirsitio", "a"
            );
        }
        echo_esc(
            "Regenerados $dirserv/$dirsitio/DataObjects/$dbnombre.ini y "
            . "$dirserv/$dirsitio/DataObjects/$dbnombre.ini "
        );
        echo "<br><font color='#FF9999'>Se sugiere quitar "
            . "permiso de escritura desde el servidor web a estos archivos.</font> "
            . "Desde la línea de comandos intente el siguiente comando y vuelva "
            . " a cargar esta página: <br><tt>";
        echo_esc(
            "  sudo chmod a-w $dirchroot/$dirserv/$dirsitio/DataObjects/$dbnombre.*"
        );
        echo "</tt>";
    } else {
        echo_esc(
            "No se regenerará esquema, rengerelo manualmente o de permiso "
            . " de escritura a "
            . "$dirchroot/$dirserv/$dirsitio/DataObjects/$dbnombre.ini y "
            . "$dirchroot/$dirserv/$dirsitio/DataObjects/$dbnombre.links.ini "
            . "Puede ser desde una línea de comandos con:"
        );
        echo "<tt>";
        echo_esc(
            "  sudo chown www:www "
            . "$dirchroot/$dirserv/$dirsitio/DataObjects/$dbnombre.*"
        );
        echo_esc(
            "  sudo chmod u+w "
            . "$dirchroot/$dirserv/$dirsitio/DataObjects/$dbnombre.*"
        );
        echo "</tt>";
    }
}

encabezado_envia(_('Actualizando'));
echo '<table width="100%"><td style="white-space: nowrap; '
    . 'background-color: #CCCCCC;" align="left" valign="top" colspan="2">'
    . '<b><div align=center>' . _('Actualizando') . '</div></b></td></table>';
$r = $db->getOne('SELECT COUNT(*) FROM actualizacionbase');
if (PEAR::isError($r)) {
    $r = $db->query(
        'ALTER TABLE actualizacion_base RENAME TO actualizacionbase'
    );
    regenera_esquemas();
}


echo "Preactualizando sitio<br>";
$ap = $_SESSION['dirsitio'] . '/preactualiza.php';
if (file_exists($ap)) {
    echo "Preactualizando personalización<br>";
    include_once "$ap";
}

$act = objeto_tabla('Actualizacionbase');
if (PEAR::isError($act)) {
    die("No pudo act");
}

// Se eliminaron todas las anteriores a la versión 1.2
// Toca actualizar de 1.1 o anterior a 1.2 y después de 1.2 a 1.3

$idac = '1.2-sm';
if (!aplicado($idac)) {
    hace_consulta($db, 'DROP TABLE opcion_rol', false);
    hace_consulta($db, 'DROP TABLE opcion', false);
    hace_consulta(
        $db, "ALTER TABLE usuario DROP CONSTRAINT usuario_id_rol_fkey", false
    );
    hace_consulta(
        $db, "ALTER TABLE usuario DROP CONSTRAINT usuario_id_rol_check", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE usuario ADD CONSTRAINT usuario_id_rol_check "
        . " CHECK (rol>='1' AND rol<='4')", false
    );
    hace_consulta($db, 'DROP TABLE rol', false);
    hace_consulta($db, 'DROP SEQUENCE rol_seq', false);

    aplicaact(
        $act, $idac, 'Menú pasa de base de datos a interfaz'
    );
}

$idac = '1.2-lu';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "ALTER TABLE usuario ADD COLUMN idioma "
        . " VARCHAR(6) NOT NULL DEFAULT 'es_CO'", false
    );

    aplicaact($act, $idac, 'Idioma preferido por usuario');
}


$idac = '1.2-rt';
if (!aplicado($idac)) {
    hace_consulta(
        $db,
        "ALTER TABLE categoria_p_responsable_caso RENAME TO "
        . "caso_categoria_presponsable", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE presuntos_responsables RENAME TO "
        . "presponsable ", false
    );
    hace_consulta(
        $db,
        "ALTER SEQUENCE presuntos_responsables_seq RENAME TO "
        . "presponsable_seq ", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE presuntos_responsables_caso RENAME TO "
        . "caso_presponsable", false
    );

    aplicaact($act, $idac, 'Renombrando tablas con presunto responsable');
}

$idac = '1.2-rt2';
if (!aplicado($idac)) {
    hace_consulta(
        $db,
        "ALTER TABLE vinculo_estado RENAME TO "
        . "vinculoestado ", false
    );
    hace_consulta(
        $db,
        "ALTER SEQUENCE vinculo_estado_seq RENAME TO "
        . "vinculoestado_seq ", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE vinculo_estado_comunidad RENAME TO "
        . "comunidad_vinculoestado", false
    );

    aplicaact($act, $idac, 'Renombrando tablas vinculoestado');
}

$idac = '1.2-rt3';
if (!aplicado($idac)) {
    hace_consulta(
        $db,
        "ALTER TABLE filiacion_comunidad RENAME TO "
        . "comunidad_filiacion", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE frontera_caso RENAME TO "
        . "caso_frontera", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE funcionario_caso RENAME TO "
        . "caso_funcionario", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE organizacion_comunidad RENAME TO "
        . "comunidad_organizacion", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE profesion_comunidad RENAME TO "
        . "comunidad_profesion", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE region_caso RENAME TO "
        . "caso_region", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE victima_colectiva RENAME TO "
        . "victimacolectiva", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE tipo_violencia RENAME TO "
        . "tviolencia", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE tipo_sitio RENAME TO "
        . "tsitio", false
    );
    hace_consulta(
        $db,
        "ALTER SEQUENCE tipo_sitio_seq RENAME TO "
        . "tsitio_seq ", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE tipo_relacion RENAME TO "
        . "trelacion", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE tipo_clase RENAME TO "
        . "tclase", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE sector_social RENAME TO "
        . "sectorsocial", false
    );
    hace_consulta(
        $db,
        "ALTER SEQUENCE sector_social_seq RENAME TO "
        . "sectorsocial_seq ", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE sector_social_comunidad RENAME TO "
        . "comunidad_sectorsocial", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE resultado_agresion RENAME TO "
        . "resagresion", false
    );
    hace_consulta(
        $db,
        "ALTER SEQUENCE resultado_agresion_seq RENAME TO "
        . "resagresion_seq ", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE relacion_personas RENAME TO "
        . "persona_trelacion", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE rango_edad RENAME TO "
        . "rangoedad", false
    );
    hace_consulta(
        $db,
        "ALTER SEQUENCE rango_edad_seq RENAME TO "
        . "rangoedad_seq ", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE rango_edad_comunidad RENAME TO "
        . "comunidad_rangoedad", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE parametros_reporte_consolidado RENAME TO "
        . "pconsolidado", false
    );
    hace_consulta(
        $db,
        "ALTER SEQUENCE parametros_reporte_consolidado_seq RENAME TO "
        . "pconsolidado_seq ", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE prensa RENAME TO "
        . "ffrecuente", false
    );
    hace_consulta(
        $db,
        "ALTER SEQUENCE prensa_seq RENAME TO "
        . "ffrecuente_seq ", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE escrito_caso RENAME TO "
        . "caso_ffrecuente", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE fuente_directa RENAME TO "
        . "fotra", false
    );
    hace_consulta(
        $db,
        "ALTER SEQUENCE fuente_directa_seq RENAME TO "
        . "fotra_seq ", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE fuente_directa_caso RENAME TO "
        . "caso_fotra", false
    );

    aplicaact($act, $idac, 'Renombrando tablas para seguir estándares SQL');
}

$idac = '1.2-rc1';
if (!aplicado($idac)) {
    foreach (array(
        array("acto", "id_p_responsable", "id_presponsable"),
        array("actocolectivo", "id_p_responsable", "id_presponsable"),
        array("caso", "gr_confiabilidad", "grconfiabilidad"),
        array("caso", "gr_esclarecimiento", "gresclarecimiento"),
        array("caso", "gr_impunidad", "grimpunidad"),
        array("caso", "gr_informacion", "grinformacion"),
        array("caso_categoria_presponsable", "id_p_responsable", "id_presponsable"),
        array("caso_categoria_presponsable", "id_tipo_violencia", "id_tviolencia"),
        array("caso_presponsable", "id_p_responsable", "id_presponsable"),
        array("caso_fotra", "ubicacion_fisica", "ubicacionfisica"),
        array("caso_fotra", "tipo_fuente", "tfuente"),
        array("pconsolidado", "no_columna", "id"),
        array("pconsolidado", "tipo_violencia", "tipoviolencia"),
        array("supracategoria", "id_tipo_violencia", "id_tviolencia"),
        array("categoria", "col_rep_consolidado", "id_pconsolidado"),
        array("categoria", "contada_en", "contadaen"),
        array("categoria", "id_tipo_violencia", "id_tviolencia"),
        array("clase", "id_tipo_clase", "id_tclase"),
        array("comunidad_rangoedad", "id_rango", "id_rangoedad"),
        array("comunidad_sectorsocial", "id_sector", "id_sectorsocial"),
        array("ffrecuente", "tipo_fuente", "tfuente"),
        array("presponsable_caso", "id_p_responsable", "id_presponsable"),
        array("ubicacion", "id_tipo_sitio", "id_tsitio"),
        array("usuario", "id_rol", "rol"),
        array("usuario", "id_usuario", "id"),
        array("usuario", "dias_edicion_caso", "diasedicion"),
        array("victima", "id_rango_edad", "id_rangoedad"),
        array("victima", "id_sector_social", "id_sectorsocial"),
        array("victima", "id_vinculo_estado", "id_vinculoestado"),
        array("victima", "id_organizacion_armada", "organizacionarmada"),
        array("comunidad_vinculoestado", "id_vinculo_estado", "id_vinculoestado"),
        array("presponsable", "id_papa", "papa"),
        array("persona_trelacion", "id_persona1", "persona1"),
        array("persona_trelacion", "id_persona2", "persona2"),
        array("persona_trelacion", "id_tipo", "id_trelacion"),
        array("victimacolectiva", "personas_aprox", "personasaprox"),
        array("victimacolectiva", "id_organizacion_armada", "organizacionarmada"),
        array("caso_ffrecuente", "ubicacion_fisica", "ubicacionfisica"),
        array("caso_ffrecuente", "id_prensa", "id_ffrecuente"),
        array("caso_fotra", "id_fuente_directa", "id_fotra"),
        array("caso_funcionario", "fecha_inicio", "fechainicio"),
    ) as $v) {
        $tabla = $v[0];
        $ant = $v[1];
        $nue = $v[2];
        hace_consulta(
            $db,
            "ALTER TABLE $tabla RENAME COLUMN $ant TO $nue", false
        );
    }

    aplicaact($act, $idac, 'Renombrando campos para seguir estándares SQL');
}

$idac = '1.2-gc';
if (!aplicado($idac)) {
    foreach (array('departamento', 'municipio', 'clase') as $tabla) {
        hace_consulta(
            $db, "ALTER TABLE $tabla " .
            " ADD COLUMN latitud FLOAT", false
        );
        hace_consulta(
            $db, "ALTER TABLE $tabla " .
            " ADD COLUMN longitud FLOAT", false
        );
    }
    aplicaact($act, $idac, 'Latitude y Longitud en departamento, municipio y clase');
}

$idac = '1.1-dp';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "ALTER TABLE tclase ALTER COLUMN id TYPE VARCHAR(10)", false
    );
    hace_consulta(
        $db, "ALTER TABLE clase ALTER COLUMN id_tclase TYPE VARCHAR(10)", false
    );

    aplicaact($act, $idac, 'Actualiza info. geográfica con DIVIPOLA 2012');
}

$idac = '1.1-dp1';
if (!aplicado($idac)) {
    consulta_archivo($db, 'act-nom2012.sql');
    aplicaact($act, $idac, 'Actualiza info. geográfica con DIVIPOLA 2012');
}

$idac = '1.1-dp13';
if (!aplicado($idac)) {
    consulta_archivo($db, 'act-nom2013.sql');
    aplicaact($act, $idac, 'Actualiza info. geográfica con DIVIPOLA 2013');
}

$idac = '1.2-co';
if (!aplicado($idac)) {
    consulta_archivo($db, 'act-coor.sql');
    aplicaact($act, $idac, 'Agrega coordenadas a departamentos y municipios');
}

$idac = '1.2-coc';
if (!aplicado($idac)) {
    $n = (int)$db->getOne(
        "SELECT COUNT(*) FROM ubicacion, municipio 
        WHERE municipio.id_departamento=ubicacion.id_departamento 
        AND municipio.id=ubicacion.id_municipio
        AND ubicacion.latitud IS NULL; "
    );
    echo_esc("  Agregando coordenadas a $n ubicaciones con municipio");
    echo "<br>";
    hace_consulta(
        $db, "UPDATE ubicacion 
        SET latitud = municipio.latitud+random()/1000-0.0005, 
        longitud=municipio.longitud+random()/1000-0.0005 
        FROM municipio 
        WHERE municipio.id_departamento=ubicacion.id_departamento 
        AND municipio.id=ubicacion.id_municipio
        AND ubicacion.latitud IS NULL; "
    );

    aplicaact($act, $idac, 'Agrega coordenadas a casos que no tienen');
}


$idac = '1.2-loc';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "CREATE COLLATION es_co_utf_8 (LOCALE = 'es_CO.UTF-8')", false
    );
    foreach (array(
        'Antecedente', 'Categoria', 'Clase', 'Contexto',
        'Departamento', 'Etnia', 'Ffrecuente', 'Filiacion', 'Frontera', 
        'Fotra', 'Ffrecuente', 'Grupoper',
        'Iglesia', 'Intervalo', 'Municipio', 'Organizacion', 
        'Presponsable', 'Profesion', 'Rangoedad', 'Region', 'Resagresion', 
        'Sectorsocial', 'Supracategoria', 'Tclase', 'Trelacion', 'Tsitio', 
        'Tviolencia', 'Vinculoestado', 
    ) as $t) {
        hace_consulta(
            $db, "ALTER TABLE $t ALTER nombre "
            . " TYPE VARCHAR(500) COLLATE es_co_utf_8", false
        );
    }
    hace_consulta(
        $db, "ALTER TABLE persona ALTER nombres "
        . " TYPE VARCHAR(100) COLLATE es_co_utf_8", false
    );
    hace_consulta(
        $db, "ALTER TABLE persona ALTER apellidos "
        . " TYPE VARCHAR(100) COLLATE es_co_utf_8", false
    );
    hace_consulta(
        $db, "ALTER TABLE usuario ALTER nombre "
        . " TYPE VARCHAR(50) COLLATE es_co_utf_8", false
    );
    hace_consulta(
        $db, "ALTER TABLE ubicacion ALTER lugar "
        . " TYPE VARCHAR(500) COLLATE es_co_utf_8", false
    );
    hace_consulta(
        $db, "ALTER TABLE ubicacion ALTER sitio "
        . " TYPE VARCHAR(500) COLLATE es_co_utf_8", false
    );
    hace_consulta(
        $db, "ALTER TABLE pconsolidado ALTER rotulo "
        . " TYPE VARCHAR(500) COLLATE es_co_utf_8", false
    );

    aplicaact($act, $idac, 'Localización');
}


$idac = '1.2-btc';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "CREATE EXTENSION unaccent", false
    );
    hace_consulta(
        $db, "ALTER TEXT SEARCH DICTIONARY unaccent (RULES='unaccent')", false
    );
    hace_consulta(
        $db, "ALTER FUNCTION unaccent(text) IMMUTABLE", false
    );
    hace_consulta(
        $db, "CREATE INDEX persona_nombres_apellidos ON persona "
        . " USING gin(to_tsvector('spanish', unaccent(persona.nombres) "
        . "|| ' ' || unaccent(persona.apellidos)))", 
        false
    );
    hace_consulta(
        $db, "CREATE INDEX persona_apellidos_nombres ON persona "
        . " USING gin(to_tsvector('spanish', unaccent(persona.apellidos) "
        . " || ' ' || unaccent(persona.nombres)))", 
        false
    );
    hace_consulta(
        $db, "CREATE INDEX persona_nombres_apellidos_doc ON persona "
        . " USING gin(to_tsvector('spanish', unaccent(persona.nombres) "
        . "|| ' ' || unaccent(persona.apellidos) "
        . "|| ' ' || persona.numerodocumento))", 
        false
    );
    hace_consulta(
        $db, "CREATE INDEX persona_apellidos_nombres_doc ON persona "
        . " USING gin(to_tsvector('spanish', unaccent(persona.apellidos) "
        . " || ' ' || unaccent(persona.nombres) "
        . " || ' ' || persona.numerodocumento))", 
        false
    );

    hace_consulta(
        $db, "CREATE INDEX caso_titulo ON caso "
        . " USING gin(to_tsvector('spanish', unaccent(caso.titulo))) ",
        false
    );
    hace_consulta(
        $db, "CREATE INDEX caso_memo ON caso "
        . " USING gin(to_tsvector('spanish', unaccent(caso.memo))) ",
        false
    );

    aplicaact($act, $idac, 'Búsqueda de textos');
}

$idac = '1.2-idn';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "UPDATE persona SET numerodocumento = "
        . " regexp_replace(numerodocumento, '[^0-9]', '', 'g') ", false
    );
    hace_consulta(
        $db, "UPDATE persona SET numerodocumento = NULL "
        . " WHERE numerodocumento = '' ", false
    );
    hace_consulta(
        $db, "ALTER TABLE persona ALTER numerodocumento TYPE BIGINT USING 
        CAST (numerodocumento AS BIGINT)"
    );
    hace_consulta(
        $db, "ALTER TABLE persona DROP CONSTRAINT numerodocumento_key ", false
    );
    hace_consulta(
        $db, "ALTER TABLE persona ADD CONSTRAINT numerodocumento_key "
        . " UNIQUE (tipodocumento, numerodocumento)"
    );
    aplicaact($act, $idac, 'Numero de documento entero');
}

$idac = '1.2-ext';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "INSERT INTO departamento 
        (id, nombre, latitud, longitud, fechacreacion, fechadeshabilitacion) 
        VALUES (10000, 'EXTERIOR', NULL, NULL, '2013-06-13', NULL);", false
    );
    hace_consulta(
        $db, "INSERT INTO municipio
        (id, nombre, id_departamento, latitud, longitud, 
        fechacreacion, fechadeshabilitacion)
        (SELECT id, nombre, 10000, latitud, longitud,
        fechacreacion, fechadeshabilitacion FROM municipio
        WHERE id_departamento='0');", false
    );
    foreach (array('clase', 'ubicacion', 'persona') as $t) {
        hace_consulta(
            $db, "UPDATE $t SET id_departamento = 10000
            WHERE id_departamento = 0", false
        );
    }
    hace_consulta(
        $db, "DELETE FROM municipio WHERE id_departamento = '0'", false
    );
    hace_consulta(
        $db, "DELETE FROM departamento WHERE id = '0'", false
    );
    aplicaact($act, $idac, 'Cambio de código EXTERIOR de 0 a 10000');
}


$idac = '1.2-tb';
if (!aplicado($idac)) {

    hace_consulta(
        $db, "INSERT INTO vinculoestado(id, nombre, fechacreacion) 
        VALUES (40, 'VICEPRESIDENCIA', '2013-07-05')", false
    );
    hace_consulta(
        $db, "INSERT INTO organizacion(id, nombre, fechacreacion) 
        VALUES (17, 'VÍCTIMAS', '2013-07-05')", false
    );
    hace_consulta(
        $db, "INSERT INTO etnia (id, nombre, descripcion, fechacreacion) 
        VALUES (60, 'ROM', '', '2013-07-05')", false
    );

    aplicaact($act, $idac, 'Aumentadas tablas básicas');
}
$idac = '1.2-pr1';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "UPDATE presponsable set papa='39' WHERE id='1'", false
    );
    hace_consulta(
        $db, "UPDATE presponsable set papa='39' WHERE id='14'", false
    );
    hace_consulta(
        $db, "UPDATE presponsable set papa='40' WHERE id='25'", false
    );
    hace_consulta(
        $db, "UPDATE presponsable set papa='36' WHERE id='33'", false
    );

    aplicaact($act, $idac, 'Renombrando tablas');
} 

$idac = '1.2-sx';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "
-- Tomado de http://wiki.postgresql.org/wiki/SoundexESP
-- Oliver Mazariegos http://www.grupovesica.com

CREATE OR REPLACE FUNCTION soundexesp(input text) RETURNS text
IMMUTABLE STRICT COST 500 LANGUAGE plpgsql
AS $$
DECLARE
	soundex text='';	
	-- para determinar la primera letra
	pri_letra text;
	resto text;
	sustituida text ='';
	-- para quitar adyacentes
	anterior text;
	actual text;
	corregido text;
BEGIN
       -- devolver null si recibi un string en blanco o con espacios en blanco
	IF length(trim(input))= 0 then
		RETURN NULL;
	end IF;
 
 
	-- 1: LIMPIEZA:
		-- pasar a mayuscula, eliminar la letra \"H\" inicial, los acentos y la enie
		-- 'holá coñó' => 'OLA CONO'
		input=translate(ltrim(trim(upper(input)),'H'),'ÑÁÉÍÓÚÀÈÌÒÙÜ','NAEIOUAEIOUU');
 
		-- eliminar caracteres no alfabéticos (números, símbolos como &,%,\",*,!,+, etc.
		input=regexp_replace(input, '[^a-zA-Z]', '', 'g');
 
	-- 2: PRIMERA LETRA ES IMPORTANTE, DEBO ASOCIAR LAS SIMILARES
	--  'vaca' se convierte en 'baca'  y 'zapote' se convierte en 'sapote'
	-- un fenomeno importante es GE y GI se vuelven JE y JI; CA se vuelve KA, etc
	pri_letra =substr(input,1,1);
	resto =substr(input,2);
	CASE 
		when pri_letra IN ('V') then
			sustituida='B';
		when pri_letra IN ('Z','X') then
			sustituida='S';
		when pri_letra IN ('G') AND substr(input,2,1) IN ('E','I') then
			sustituida='J';
		when pri_letra IN('C') AND substr(input,2,1) NOT IN ('H','E','I') then
			sustituida='K';
		else
			sustituida=pri_letra;
 
	end case;
	--corregir el parametro con las consonantes sustituidas:
	input=sustituida || resto;		
 
	-- 3: corregir \"letras compuestas\" y volverlas una sola
	input=REPLACE(input,'CH','V');
	input=REPLACE(input,'QU','K');
	input=REPLACE(input,'LL','J');
	input=REPLACE(input,'CE','S');
	input=REPLACE(input,'CI','S');
	input=REPLACE(input,'YA','J');
	input=REPLACE(input,'YE','J');
	input=REPLACE(input,'YI','J');
	input=REPLACE(input,'YO','J');
	input=REPLACE(input,'YU','J');
	input=REPLACE(input,'GE','J');
	input=REPLACE(input,'GI','J');
	input=REPLACE(input,'NY','N');
	-- para debug:    --return input;
 
	-- EMPIEZA EL CALCULO DEL SOUNDEX
	-- 4: OBTENER PRIMERA letra
	pri_letra=substr(input,1,1);
 
	-- 5: retener el resto del string
	resto=substr(input,2);
 
	--6: en el resto del string, quitar vocales y vocales fonéticas
	resto=translate(resto,'@AEIOUHWY','@');
 
	--7: convertir las letras foneticamente equivalentes a numeros  (esto hace que B sea equivalente a V, C con S y Z, etc.)
	resto=translate(resto, 'BPFVCGKSXZDTLMNRQJ', '111122222233455677');
	-- así va quedando la cosa
	soundex=pri_letra || resto;
 
	--8: eliminar números iguales adyacentes (A11233 se vuelve A123)
	anterior=substr(soundex,1,1);
	corregido=anterior;
 
	FOR i IN 2 .. length(soundex) LOOP
		actual = substr(soundex, i, 1);
		IF actual <> anterior THEN
			corregido=corregido || actual;
			anterior=actual;			
		END IF;
	END LOOP;
	-- así va la cosa
	soundex=corregido;
 
	-- 9: siempre retornar un string de 4 posiciones
	soundex=rpad(soundex,4,'0');
	soundex=substr(soundex,1,4);		
 
	-- YA ESTUVO
	RETURN soundex;	
END;	
$$
        ", false
    );
    aplicaact($act, $idac, 'Función Soundex en Español');
} 

$idac = '1.2-fun';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "ALTER TABLE usuario DROP COLUMN diasedicion", false
    );
    hace_consulta(
        $db, "ALTER TABLE usuario RENAME id TO nusuario", false
    );
    hace_consulta(
        $db, "ALTER TABLE usuario ADD column id INTEGER", false
    );
    hace_consulta(
        $db, "ALTER TABLE usuario 
        ADD COLUMN fechacreacion DATE NOT NULL DEFAULT '2001-01-01'" , false
    );
    hace_consulta(
        $db, "ALTER TABLE usuario " .
        "ADD COLUMN fechadeshabilitacion DATE DEFAULT NULL " .
        "CHECK (fechadeshabilitacion IS NULL OR " .
        "fechadeshabilitacion >= fechacreacion)", false
    );
    hace_consulta(
        $db, "UPDATE usuario set id=funcionario.id, fechadeshabilitacion=NULL 
        FROM funcionario WHERE 
        funcionario.nombre = usuario.nusuario", false
    );
    hace_consulta(
        $db, "INSERT INTO usuario (id, nusuario, password, nombre, descripcion, rol, idioma, fechadeshabilitacion) (SELECT id, nombre, '', nombre, '', 4, 'es_CO', current_date FROM funcionario WHERE nombre NOT IN (SELECT nusuario FROM usuario))", false
    );
    hace_consulta(
        $db, "CREATE UNIQUE INDEX usuario_nusuario ON usuario USING btree (nusuario)", false
    );
    hace_consulta(
        $db, "ALTER TABLE usuario DROP CONSTRAINT usuario_pkey", false
    );
    hace_consulta(
        $db, "ALTER TABLE usuario ADD CONSTRAINT usuario_pkey
        PRIMARY KEY (id)", false
    );
    hace_consulta(
        $db, "ALTER TABLE caso_etiqueta DROP CONSTRAINT caso_etiqueta_pkey", false
    );
    hace_consulta(
        $db, "ALTER TABLE caso_funcionario DROP CONSTRAINT caso_funcionario_pkey", false
    );
    hace_consulta(
        $db, "ALTER TABLE caso_funcionario DROP CONSTRAINT caso_funcionario_id_funcionario_fkey", false
    );
    hace_consulta(
        $db, "ALTER TABLE caso_etiqueta DROP CONSTRAINT caso_etiqueta_id_funcionario_fkey", false
    );
    hace_consulta(
        $db, "ALTER TABLE funcionario DROP CONSTRAINT funcionario_pkey", false
    );
    hace_consulta(
        $db, "ALTER TABLE caso_funcionario RENAME TO caso_usuario", false
    );
    hace_consulta(
        $db, "ALTER TABLE caso_usuario RENAME id_funcionario TO id_usuario", false
    );
    hace_consulta(
        $db, "ALTER TABLE caso_etiqueta RENAME id_funcionario TO id_usuario", false
    );
    hace_consulta(
        $db, "ALTER TABLE caso_usuario 
        ADD CONSTRAINT caso_usuario_id_usuario_fkey 
        FOREIGN KEY (id_usuario) REFERENCES usuario(id)", false
    );
    hace_consulta(
        $db, "ALTER TABLE caso_etiqueta
        ADD CONSTRAINT caso_etiqueta_id_usuario_fkey
        FOREIGN KEY (id_usuario) REFERENCES usuario(id)", false
    );
    hace_consulta(
        $db, "ALTER TABLE caso_etiqueta
        ADD CONSTRAINT caso_etiqueta_pkey
        PRIMARY KEY (id_caso, id_etiqueta, id_usuario, fecha)", false
    );
    hace_consulta(
        $db, "ALTER TABLE funcionario RENAME TO obsoleto_funcionario", false
    );
    hace_consulta(
        $db, "ALTER TABLE obsoleto_funcionario DROP CONSTRAINT 
        funcionario_nombre_key", false
    );
    hace_consulta(
        $db, "ALTER TABLE obsoleto_funcionario ALTER COLUMN id 
        SET DEFAULT NULL", false
    );
    hace_consulta(
        $db, "ALTER SEQUENCE funcionario_seq RENAME TO usuario_seq", false
    );
    hace_consulta(
        $db, "ALTER TABLE usuario ALTER COLUMN id SET DEFAULT nextval('usuario_seq')"
    );
    hace_consulta(
        $db, "ALTER TABLE usuario ALTER COLUMN rol SET DEFAULT '4'"
    );

    aplicaact($act, $idac, 'Fusiona tablas usuario y funcionario');
}

$idac = '1.2-bc';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "ALTER TABLE usuario ADD COLUMN email VARCHAR(255) NOT NULL DEFAULT ''", false
    );
    hace_consulta(
        $db, "ALTER TABLE usuario ADD COLUMN encrypted_password VARCHAR(255) NOT NULL DEFAULT ''", false
    );
    hace_consulta(
        $db, "ALTER TABLE usuario ADD COLUMN sign_in_count INTEGER NOT NULL DEFAULT 0", 
        false
    );
    hace_consulta(
        $db, "UPDATE usuario SET email=(nusuario || '@localhost') 
        WHERE email = ''", false
    );
    hace_consulta(
        $db, "CREATE UNIQUE INDEX index_usuario_on_email ON usuario 
        USING btree (email)",
        false
    );
 
    aplicaact($act, $idac, 'Emplea bcrypt para calcular condensado de claves y agrega inforación a tablas para hacer compatible con autenticación con Devise/Ruby');
}

$idac = '1.2-nc';
if (!aplicado($idac)) {

    hace_consulta($db, $q, false);
    hace_consulta(
        $db,
        "ALTER TABLE comunidad_sectorsocial 
        RENAME COLUMN id_sector TO id_sectorsocial", false);

    aplicaact($act, $idac, 'Nombre en Sector Social de Victima Colectiva');
}

$idac = '1.2-def';
if (!aplicado($idac)) {

    $basicas = html_menu_toma_url($GLOBALS['menu_tablas_basicas']);
    global $dbnombre;
    $v = null;
    $enl = parse_ini_file(
        $_SESSION['dirsitio'] . "/DataObjects/" .
        $GLOBALS['dbnombre'] . ".links.ini",
        true
    );
    foreach($enl as $t => $e) {
        $do = objeto_tabla($t);
        foreach($e as $c => $rel) {
            if (strpos($c, ',') === FALSE) {
                $pd = strpos($rel, ':');
                $ndo = substr($rel, 0, $pd);
                $ids = valorSinInfo($do, $c);
                if ($ids >= 0 && $ndo != 'presponsable') {
                    $q = "ALTER TABLE $t ALTER COLUMN $c SET DEFAULT '$ids'";
                    hace_consulta($db, $q, false);
                } 
            }
        }
    }
    aplicaact($act, $idac, 'Valores por defecto en referencias a tablas básicas');
}

$idac = '1.3-cp';
if (!aplicado($idac)) {
    
    hace_consulta($db, 'CREATE SEQUENCE caso_presponsable_seq', false);
    hace_consulta($db, "ALTER TABLE caso_presponsable ALTER COLUMN id 
        SET DEFAULT(nextval('caso_presponsable_seq'))", false
    );
    hace_consulta($db, "UPDATE caso_presponsable SET
        id = id_caso*10 + id WHERE id<10", false);
    hace_consulta($db, "ALTER TABLE caso_presponsable ADD UNIQUE(id);", true);
    hace_consulta($db, "SELECT setval('caso_presponsable_seq', MAX(id)) 
        FROM (SELECT 10 as id UNION SELECT MAX(id) 
        FROM caso_presponsable) AS s;", false);
    hace_consulta($db, "ALTER TABLE caso_presponsable ALTER COLUMN tipo
        SET DEFAULT 0", false
    );
    hace_consulta(
        $db, "ALTER TABLE caso_categoria_presponsable 
        ADD COLUMN id_caso_presponsable INTEGER 
          REFERENCES caso_presponsable(id)",
        false
    );
    hace_consulta(
        $db, "UPDATE caso_categoria_presponsable SET
        id_caso_presponsable = id_caso*10 + id WHERE id<10", 
        false
    );
    hace_consulta(
        $db, "ALTER TABLE caso_categoria_presponsable DROP COLUMN id"
    );
    hace_consulta(
        $db, "ALTER TABLE caso_presponsable 
        DROP CONSTRAINT caso_presponsable_pkey;",
        false
    );
    hace_consulta(
        $db, "ALTER TABLE caso_presponsable ADD PRIMARY KEY (id);", 
        false
    );

    aplicaact($act, $idac, 'id en tabla caso_presponsable es identificación');
}

$idac = '1.3-vid';
if (!aplicado($idac)) {
    
    hace_consulta($db, 'CREATE SEQUENCE victima_seq', false);
    hace_consulta(
        $db, "ALTER TABLE antecedente_victima
        DROP CONSTRAINT antecedente_victima_id_persona_fkey1", false
    );
    hace_consulta(
        $db, "ALTER TABLE victima DROP CONSTRAINT victima_pkey CASCADE", false
    );
    hace_consulta(
        $db, "ALTER TABLE victima ADD UNIQUE(id_caso, id_persona);", true
    );
    hace_consulta(
        $db, "ALTER TABLE victima
        ADD COLUMN id INTEGER PRIMARY KEY DEFAULT(nextval('victima_seq'))", 
        false
    );
    hace_consulta(
        $db, "ALTER TABLE victima ADD CONSTRAINT victima_pkey
        PRIMARY KEY (id)", false
    );
    hace_consulta(
        $db, "ALTER TABLE antecedente_victima
        ADD CONSTRAINT victima_fkey
        FOREIGN KEY (id_caso, id_persona) 
        REFERENCES victima(id_caso, id_persona)", false
    );
    hace_consulta(
        $db, "ALTER TABLE antecedente_victima
        ADD COLUMN id_victima INTEGER REFERENCES victima(id)", false
    );
    hace_consulta(
        $db, "UPDATE antecedente_victima SET id_victima=victima.id FROM
        victima WHERE antecedente_victima.id_persona=victima.id_persona AND
        antecedente_victima.id_caso=victima.id_caso", false
    ); 

    aplicaact($act, $idac, 'id en tabla victima es identificación'); 
}

$idac = '1.3-pa';
if (!aplicado($idac)) {
    
    hace_consulta($db, 'CREATE SEQUENCE pais_seq', false);
    hace_consulta(
        $db, "CREATE TABLE pais (
            id INTEGER PRIMARY KEY DEFAULT(nextval('pais_seq')),
            nombre VARCHAR(200) NOT NULL,
            nombreiso VARCHAR(200) NOT NULL,
            latitud FLOAT,
            longitud FLOAT,
            alfa2 VARCHAR(2),
            alfa3 VARCHAR(3),
    codiso INTEGER, 
            div1 VARCHAR(100),
            div2 VARCHAR(100),
            div3 VARCHAR(100),
            fechacreacion    DATE NOT NULL,
            fechadeshabilitacion    DATE CHECK (fechadeshabilitacion IS NULL
                OR fechadeshabilitacion >= fechacreacion)
        );", false
    );

	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('4',  'AFGANISTÁN', 'AFGANISTAN', 'AF', 'AFG', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('248',  'ÅLAND', 'ISLAS ÅLAND', 'AX', 'ALA', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('8',  'ALBANIA', 'ALBANIA', 'AL', 'ALB', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('276',  'ALEMANIA', 'ALEMANIA', 'DE', 'DEU', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('20',  'ANDORRA', 'ANDORRA', 'AD', 'AND', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('24',  'ANGOLA', 'ANGOLA', 'AO', 'AGO', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('660',  'ANGUILA', 'ANGUILA', 'AI', 'AIA', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('10',  'ANTÁRTIDA', 'ANTARTIDA', 'AQ', 'ATA', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('28',  'ANTIGUA Y BARBUDA', 'ANTIGUA Y BARBUDA', 'AG', 'ATG', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('682',  'ARABIA SAUDITA', 'ARABIA SAUDITA', 'SA', 'SAU', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('12',  'ARGELIA', 'ARGELIA', 'DZ', 'DZA', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('32',  'ARGENTINA', 'ARGENTINA', 'AR', 'ARG', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('51',  'ARMENIA', 'ARMENIA', 'AM', 'ARM', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('533',  'ARUBA', 'ARUBA', 'AW', 'ABW', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('36',  'AUSTRALIA', 'AUSTRALIA', 'AU', 'AUS', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('40',  'AUSTRIA', 'AUSTRIA', 'AT', 'AUT', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('31',  'AZERBAIYÁN', 'AZERBAIYAN', 'AZ', 'AZE', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('44',  'BAHAMAS', 'BAHAMAS', 'BS', 'BHS', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('50',  'BANGLADÉS', 'BANGLADES', 'BD', 'BGD', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('52',  'BARBADOS', 'BARBADOS', 'BB', 'BRB', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('48',  'BARÉIN', 'BAREIN', 'BH', 'BHR', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('56',  'BÉLGICA', 'BELGICA', 'BE', 'BEL', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('84',  'BELICE', 'BELICE', 'BZ', 'BLZ', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('204',  'BENÍN', 'BENIN', 'BJ', 'BEN', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('60',  'BERMUDAS', 'BERMUDAS', 'BM', 'BMU', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('112',  'BIELORRUSIA', 'BIELORRUSIA', 'BY', 'BLR', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('104',  'BIRMANIA', 'MYANMAR nota 1', 'MM', 'MMR', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('68',  'BOLIVIA', 'BOLIVIA, ESTADO PLURINACIONAL DE', 'BO', 'BOL', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('70',  'BOSNIA Y HERZEGOVINA', 'BOSNIA Y HERZEGOVINA', 'BA', 'BIH', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('72',  'BOTSUANA', 'BOTSUANA', 'BW', 'BWA', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('76',  'BRASIL', 'BRASIL', 'BR', 'BRA', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('96',  'BRUNÉI', 'BRUNEI DARUSSALAM', 'BN', 'BRN', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('100',  'BULGARIA', 'BULGARIA', 'BG', 'BGR', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('854',  'BURKINA FASO', 'BURKINA FASO', 'BF', 'BFA', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('108',  'BURUNDI', 'BURUNDI', 'BI', 'BDI', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('64',  'BUTÁN', 'BUTAN', 'BT', 'BTN', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('132',  'CABO VERDE', 'CABO VERDE', 'CV', 'CPV', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('116',  'CAMBOYA', 'CAMBOYA', 'KH', 'KHM', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('120',  'CAMERÚN', 'CAMERUN', 'CM', 'CMR', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('124',  'CANADÁ', 'CANADA', 'CA', 'CAN', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('634',  'CATAR', 'QATAR', 'QA', 'QAT', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('535',  'CARIBE NEERLANDÉS', 'BONAIRE, SAN EUSTAQUIO Y SABA', 'BQ', 'BES', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('148',  'CHAD', 'CHAD', 'TD', 'TCD', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('152',  'CHILE', 'CHILE', 'CL', 'CHL', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('156',  'CHINA', 'CHINA, REPUBLICA POPULAR', 'CN', 'CHN', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('196',  'CHIPRE', 'CHIPRE', 'CY', 'CYP', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('170',  'COLOMBIA', 'COLOMBIA', 'CO', 'COL', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('174',  'COMORAS', 'COMORAS', 'KM', 'COM', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('408',  'COREA DEL NORTE', 'COREA, REPUBLICA DEMOCRATICA POPULAR DE', 'KP', 'PRK', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('410',  'COREA DEL SUR', 'COREA, REPUBLICA DE', 'KR', 'KOR', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('384',  'COSTA DE MARFIL', 'COSTA DE MARFIL', 'CI', 'CIV', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('188',  'COSTA RICA', 'COSTA RICA', 'CR', 'CRI', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('191',  'CROACIA', 'CROACIA', 'HR', 'HRV', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('192',  'CUBA', 'CUBA', 'CU', 'CUB', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('531',  'CURAZAO', 'CURAZAO', 'CW', 'CUW', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('208',  'DINAMARCA', 'DINAMARCA', 'DK', 'DNK', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('212',  'DOMINICA', 'DOMINICA', 'DM', 'DMA', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('218',  'ECUADOR', 'ECUADOR', 'EC', 'ECU', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('818',  'EGIPTO', 'EGIPTO', 'EG', 'EGY', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('222',  'EL SALVADOR', 'EL SALVADOR', 'SV', 'SLV', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('784',  'EMIRATOS ÁRABES UNIDOS', 'EMIRATOS ARABES UNIDOS', 'AE', 'ARE', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('232',  'ERITREA', 'ERITREA', 'ER', 'ERI', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('703',  'ESLOVAQUIA', 'ESLOVAQUIA', 'SK', 'SVK', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('705',  'ESLOVENIA', 'ESLOVENIA', 'SI', 'SVN', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('724',  'ESPAÑA', 'ESPAÑA', 'ES', 'ESP', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('840',  'ESTADOS UNIDOS', 'ESTADOS UNIDOS', 'US', 'USA', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('233',  'ESTONIA', 'ESTONIA', 'EE', 'EST', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('231',  'ETIOPÍA', 'ETIOPIA', 'ET', 'ETH', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('608',  'FILIPINAS', 'FILIPINAS', 'PH', 'PHL', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('246',  'FINLANDIA', 'FINLANDIA', 'FI', 'FIN', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('242',  'FIYI', 'FIYI', 'FJ', 'FJI', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('250',  'FRANCIA', 'FRANCIA', 'FR', 'FRA', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('266',  'GABÓN', 'GABON', 'GA', 'GAB', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('270',  'GAMBIA', 'GAMBIA', 'GM', 'GMB', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('268',  'GEORGIA', 'GEORGIA', 'GE', 'GEO', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('288',  'GHANA', 'GHANA', 'GH', 'GHA', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('292',  'GIBRALTAR', 'GIBRALTAR', 'GI', 'GIB', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('308',  'GRANADA', 'GRANADA', 'GD', 'GRD', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('300',  'GRECIA', 'GRECIA', 'GR', 'GRC', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('304',  'GROENLANDIA', 'GROENLANDIA', 'GL', 'GRL', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('312',  'GUADALUPE', 'GUADALUPE', 'GP', 'GLP', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('316',  'GUAM', 'GUAM', 'GU', 'GUM', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('320',  'GUATEMALA', 'GUATEMALA', 'GT', 'GTM', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('254',  'GUAYANA FRANCESA', 'GUAYANA FRANCESA', 'GF', 'GUF', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('831',  'GUERNSEY', 'GUERNSEY', 'GG', 'GGY', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('324',  'GUINEA', 'GUINEA', 'GN', 'GIN', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('624',  'GUINEA-BISÁU', 'GUINEA-BISAU', 'GW', 'GNB', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('226',  'GUINEA ECUATORIAL', 'GUINEA ECUATORIAL', 'GQ', 'GNQ', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('328',  'GUYANA', 'GUYANA', 'GY', 'GUY', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('332',  'HAITÍ', 'HAITI', 'HT', 'HTI', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('340',  'HONDURAS', 'HONDURAS', 'HN', 'HND', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('344',  'HONG KONG', 'HONG KONG', 'HK', 'HKG', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('348',  'HUNGRÍA', 'HUNGRIA', 'HU', 'HUN', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('356',  'INDIA', 'INDIA', 'IN', 'IND', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('360',  'INDONESIA', 'INDONESIA', 'ID', 'IDN', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('368',  'IRAK', 'IRAK', 'IQ', 'IRQ', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('364',  'IRÁN', 'IRAN, REPUBLICA ISLAMICA DE', 'IR', 'IRN', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('372',  'IRLANDA', 'IRLANDA', 'IE', 'IRL', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('74',  'ISLA BOUVET', 'ISLA BOUVET', 'BV', 'BVT', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('833',  'ISLA DE MAN', 'ISLA DE MAN', 'IM', 'IMN', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('162',  'ISLA DE NAVIDAD', 'ISLA DE NAVIDAD', 'CX', 'CXR', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('574',  'NORFOLK', 'ISLA NORFOLK', 'NF', 'NFK', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('352',  'ISLANDIA', 'ISLANDIA', 'IS', 'ISL', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('136',  'ISLAS CAIMÁN', 'ISLAS CAIMAN', 'KY', 'CYM', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('166',  'ISLAS COCOS', 'ISLAS COCOS (KEELING)', 'CC', 'CCK', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('184',  'ISLAS COOK', 'ISLAS COOK', 'CK', 'COK', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('234',  'ISLAS FEROE', 'ISLAS FEROE', 'FO', 'FRO', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('239',  'ISLAS GEORGIAS DEL SUR Y SANDWICH DEL SUR', 'ISLAS GEORGIAS DEL SUR Y SANDWICH DEL SUR', 'GS', 'SGS', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('334',  'ISLAS HEARD Y MCDONALD', 'ISLAS HEARD Y MCDONALD', 'HM', 'HMD', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('238',  'ISLAS MALVINAS', 'ISLAS FALKLAND ( MALVINAS )', 'FK', 'FLK', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('580',  'ISLAS MARIANAS DEL NORTE', 'ISLAS MARIANAS DEL NORTE', 'MP', 'MNP', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('584',  'ISLAS MARSHALL', 'ISLAS MARSHALL', 'MH', 'MHL', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('612',  'ISLAS PITCAIRN', 'PITCAIRN', 'PN', 'PCN', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('90',  'ISLAS SALOMÓN', 'ISLAS SALOMON', 'SB', 'SLB', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('796',  'ISLAS TURCAS Y CAICOS', 'ISLAS TURCAS Y CAICOS', 'TC', 'TCA', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('581',  'ISLAS ULTRAMARINAS DE ESTADOS UNIDOS', 'ISLAS ULTRAMARINAS MENORES DE ESTADOS UNIDOS', 'UM', 'UMI', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('92',  'ISLAS VÍRGENES BRITÁNICAS', 'ISLAS VIRGENES BRITANICAS', 'VG', 'VGB', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('850',  'ISLAS VÍRGENES DE LOS ESTADOS UNIDOS', 'ISLAS VIRGENES DE LOS ESTADOS UNIDOS', 'VI', 'VIR', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('376',  'ISRAEL', 'ISRAEL', 'IL', 'ISR', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('380',  'ITALIA', 'ITALIA', 'IT', 'ITA', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('388',  'JAMAICA', 'JAMAICA', 'JM', 'JAM', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('392',  'JAPÓN', 'JAPON', 'JP', 'JPN', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('832',  'JERSEY', 'JERSEY', 'JE', 'JEY', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('400',  'JORDANIA', 'JORDANIA', 'JO', 'JOR', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('398',  'KAZAJISTÁN', 'KAZAJISTAN', 'KZ', 'KAZ', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('404',  'KENIA', 'KENIA', 'KE', 'KEN', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('417',  'KIRGUISTÁN', 'KIRGUISTAN', 'KG', 'KGZ', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('296',  'KIRIBATI', 'KIRIBATI', 'KI', 'KIR', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('414',  'KUWAIT', 'KUWAIT', 'KW', 'KWT', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('418',  'LAOS', 'REPUBLICA DEMOCRATICA POPULAR LAO', 'LA', 'LAO', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('426',  'LESOTO', 'LESOTO', 'LS', 'LSO', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('428',  'LETONIA', 'LETONIA', 'LV', 'LVA', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('422',  'LÍBANO', 'LIBANO', 'LB', 'LBN', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('430',  'LIBERIA', 'LIBERIA', 'LR', 'LBR', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('434',  'LIBIA', 'LIBIA', 'LY', 'LBY', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('438',  'LIECHTENSTEIN', 'LIECHTENSTEIN', 'LI', 'LIE', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('440',  'LITUANIA', 'LITUANIA', 'LT', 'LTU', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('442',  'LUXEMBURGO', 'LUXEMBURGO', 'LU', 'LUX', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('446',  'MACAO', 'MACAO', 'MO', 'MAC', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('450',  'MADAGASCAR', 'MADAGASCAR', 'MG', 'MDG', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('458',  'MALASIA', 'MALASIA', 'MY', 'MYS', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('454',  'MALAUI', 'MALAUI', 'MW', 'MWI', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('462',  'MALDIVAS', 'MALDIVAS', 'MV', 'MDV', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('466',  'MALÍ', 'MALI', 'ML', 'MLI', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('470',  'MALTA', 'MALTA', 'MT', 'MLT', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('504',  'MARRUECOS', 'MARRUECOS', 'MA', 'MAR', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('474',  'MARTINICA', 'MARTINICA', 'MQ', 'MTQ', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('480',  'MAURICIO', 'MAURICIO', 'MU', 'MUS', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('478',  'MAURITANIA', 'MAURITANIA', 'MR', 'MRT', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('175',  'MAYOTTE', 'MAYOTTE', 'YT', 'MYT', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('484',  'MÉXICO', 'MEXICO', 'MX', 'MEX', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('583',  'MICRONESIA', 'MICRONESIA, ESTADOS FEDERADOS DE', 'FM', 'FSM', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('498',  'MOLDAVIA', 'MOLDAVIA, REPUBLICA DE', 'MD', 'MDA', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('492',  'MÓNACO', 'MONACO', 'MC', 'MCO', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('496',  'MONGOLIA', 'MONGOLIA', 'MN', 'MNG', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('499',  'MONTENEGRO', 'MONTENEGRO', 'ME', 'MNE', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('500',  'MONTSERRAT', 'MONTSERRAT', 'MS', 'MSR', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('508',  'MOZAMBIQUE', 'MOZAMBIQUE', 'MZ', 'MOZ', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('516',  'NAMIBIA', 'NABIMIA', 'NA', 'NAM', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('520',  'NAURU', 'NAURU', 'NR', 'NRU', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('524',  'NEPAL', 'NEPAL', 'NP', 'NPL', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('558',  'NICARAGUA', 'NICARAGUA', 'NI', 'NIC', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('562',  'NÍGER', 'NIGER', 'NE', 'NER', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('566',  'NIGERIA', 'NIGERIA', 'NG', 'NGA', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('570',  'NIUE', 'NIUE', 'NU', 'NIU', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('578',  'NORUEGA', 'NORUEGA', 'NO', 'NOR', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('540',  'NUEVA CALEDONIA', 'NUEVA CALEDONIA', 'NC', 'NCL', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('554',  'NUEVA ZELANDA', 'NUEVA ZELANDA', 'NZ', 'NZL', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('512',  'OMÁN', 'OMAN', 'OM', 'OMN', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('528',  'PAÍSES BAJOS', 'PAISES BAJOS', 'NL', 'NLD', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('586',  'PAKISTÁN', 'PAKISTAN', 'PK', 'PAK', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('585',  'PALAOS', 'PALAOS', 'PW', 'PLW', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('275',  'ESTADO DE PALESTINA', 'PALESTINA, ESTADO DE', 'PS', 'PSE', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('591',  'PANAMÁ', 'PANAMA', 'PA', 'PAN', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('598',  'PAPÚA NUEVA GUINEA', 'PAPUA NUEVA GUINEA', 'PG', 'PNG', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('600',  'PARAGUAY', 'PARAGUAY', 'PY', 'PRY', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('604',  'PERÚ', 'PERU', 'PE', 'PER', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('258',  'POLINESIA FRANCESA', 'POLINESIA FRANCESA', 'PF', 'PYF', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('616',  'POLONIA', 'POLONIA', 'PL', 'POL', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('620',  'PORTUGAL', 'PORTUGAL', 'PT', 'PRT', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('630',  'PUERTO RICO', 'PUERTO RICO', 'PR', 'PRI', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('826',  'REINO UNIDO', 'REINO UNIDO', 'GB', 'GBR', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('140',  'REPÚBLICA CENTROAFRICANA', 'REPUBLICA CENTROAFRICANA', 'CF', 'CAF', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('203',  'REPÚBLICA CHECA', 'REPUBLICA CHECA', 'CZ', 'CZE', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('807',  'REPÚBLICA DE MACEDONIA', 'MACEDONIA, LA ANTIGUA REPUBLICA YUGOSLAVA DE', 'MK', 'MKD', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('178',  'REPÚBLICA DEL CONGO', 'CONGO', 'CG', 'COG', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('180',  'REPÚBLICA DEMOCRÁTICA DEL CONGO', 'CONGO, LA REPUBLICA DEMOCRATICA DEL', 'CD', 'COD', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('214',  'REPÚBLICA DOMINICANA', 'REPUBLICA DOMINICANA', 'DO', 'DOM', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('638',  'REUNIÓN', 'REUNION', 'RE', 'REU', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('646',  'RUANDA', 'RUANDA', 'RW', 'RWA', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('642',  'RUMANIA', 'RUMANIA', 'RO', 'ROU', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('643',  'RUSIA', 'FEDERACION RUSA', 'RU', 'RUS', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('732',  'SAHARA OCCIDENTAL', 'SAHARA OCCIDENTAL', 'EH', 'ESH', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('882',  'SAMOA', 'SAMOA', 'WS', 'WSM', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('16',  'SAMOA AMERICANA', 'SAMOA AMERICANA', 'AS', 'ASM', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('652',  'SAN BARTOLOMÉ', 'SAN BARTOLOME', 'BL', 'BLM', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('659',  'SAN CRISTÓBAL Y NIEVES', 'SAN CRISTOBAL Y NIEVES', 'KN', 'KNA', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('674',  'SAN MARINO', 'SAN MARINO', 'SM', 'SMR', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('663',  'SAN MARTÍN', 'SAN MARTIN (PARTE FRANCESA)', 'MF', 'MAF', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('666',  'SAN PEDRO Y MIQUELÓN', 'SAN PEDRO Y MIQUELON', 'PM', 'SPM', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('670',  'SAN VICENTE Y LAS GRANADINAS', 'SAN VICENTE Y LAS GRANADINAS', 'VC', 'VCT', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('654',  'SANTA HELENA, A. Y T.', 'SANTA HELENA, ASCENSION Y TRISTAN DE ACUÑA', 'SH', 'SHN', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('662',  'SANTA LUCÍA', 'SANTA LUCIA', 'LC', 'LCA', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('678',  'SANTO TOMÉ Y PRÍNCIPE', 'SANTO TOME Y PRINCIPE', 'ST', 'STP', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('686',  'SENEGAL', 'SENEGAL', 'SN', 'SEN', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('688',  'SERBIA', 'SERBIA', 'RS', 'SRB', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('690',  'SEYCHELLES', 'SEYCHELLES', 'SC', 'SYC', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('694',  'SIERRA LEONA', 'SIERRA LEONA', 'SL', 'SLE', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('702',  'SINGAPUR', 'SINGAPUR', 'SG', 'SGP', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('534',  'SINT MAARTEN', 'SINT MAARTEN (PARTE NEERLANDESA)', 'SX', 'SXM', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('760',  'SIRIA', 'REPUBLICA ARABE DE SIRIA', 'SY', 'SYR', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('706',  'SOMALIA', 'SOMALIA', 'SO', 'SOM', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('144',  'SRI LANKA', 'SRI LANKA', 'LK', 'LKA', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('748',  'SUAZILANDIA', 'SUAZILANDIA', 'SZ', 'SWZ', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('710',  'SUDÁFRICA', 'SUDAFRICA', 'ZA', 'ZAF', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('729',  'SUDÁN', 'SUDAN', 'SD', 'SDN', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('728',  'SUDÁN DEL SUR', 'SUDAN DEL SUR', 'SS', 'SSD', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('752',  'SUECIA', 'SUECIA', 'SE', 'SWE', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('756',  'SUIZA', 'SUIZA', 'CH', 'CHE', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('740',  'SURINAM', 'SURINAM', 'SR', 'SUR', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('744',  'SVALBARD Y JAN MAYEN', 'SVALBARD Y JAN MAYEN', 'SJ', 'SJM', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('764',  'TAILANDIA', 'TAILANDIA', 'TH', 'THA', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('158',  'TAIWÁN', 'TAIWAN, PROVINCIA DE CHINA', 'TW', 'TWN', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('834',  'TANZANIA', 'TANZANIA, REPUBLICA UNIDA DE', 'TZ', 'TZA', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('762',  'TAYIKISTÁN', 'TAYIKISTAN', 'TJ', 'TJK', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('86',  'TERRITORIO BRITÁNICO DEL OCÉANO ÍNDICO', 'TERRITORIO BRITANICO DEL OCEANO INDICO', 'IO', 'IOT', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('260',  'TERRITORIOS AUSTRALES FRANCESES', 'TERRITORIOS AUSTRALES FRANCESES', 'TF', 'ATF', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('626',  'TIMOR ORIENTAL', 'TIMOR-LESTE', 'TL', 'TLS', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('768',  'TOGO', 'TOGO', 'TG', 'TGO', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('772',  'TOKELAU', 'TOKELAU', 'TK', 'TKL', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('776',  'TONGA', 'TONGA', 'TO', 'TON', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('780',  'TRINIDAD Y TOBAGO', 'TRINIDAD Y TOBAGO', 'TT', 'TTO', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('788',  'TÚNEZ', 'TUNEZ', 'TN', 'TUN', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('795',  'TURKMENISTÁN', 'TURKMENISTAN', 'TM', 'TKM', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('792',  'TURQUÍA', 'TURQUIA', 'TR', 'TUR', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('798',  'TUVALU', 'TUVALU', 'TV', 'TUV', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('804',  'UCRANIA', 'UCRANIA', 'UA', 'UKR', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('800',  'UGANDA', 'UGANDA', 'UG', 'UGA', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('858',  'URUGUAY', 'URUGUAY', 'UY', 'URY', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('860',  'UZBEKISTÁN', 'UZBEKISTAN', 'UZ', 'UZB', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('548',  'VANUATU', 'VANUATU', 'VU', 'VUT', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('336',  'CIUDAD DEL VATICANO', 'SANTA SEDE (CIUDAD ESTADO VATICAVO)', 'VA', 'VAT', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('862',  'VENEZUELA', 'VENEZUELA, REPUBLICA BOLIVARIANA DE', 'VE', 'VEN', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('704',  'VIETNAM', 'VIET NAM', 'VN', 'VNM', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('876',  'WALLIS Y FUTUNA', 'WALLIS Y FUTUNA', 'WF', 'WLF', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('887',  'YEMEN', 'YEMEN', 'YE', 'YEM', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('262',  'YIBUTI', 'YIBUTI', 'DJ', 'DJI', '2014-02-17');", false);
	hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('894',  'ZAMBIA', 'ZAMBIA', 'ZM', 'ZMB', '2014-02-17');", false);
    hace_consulta($db, "INSERT INTO pais (id, nombre, nombreiso, alfa2, alfa3, fechacreacion) VALUES ('716',  'ZIMBABUE', 'ZIMBABUE', 'ZW', 'ZWE', '2014-02-17');", false);
    hace_consulta(
        $db, "ALTER TABLE departamento ADD COLUMN 
        id_pais INTEGER REFERENCES pais", false
    );
    hace_consulta(
        $db, "UPDATE departamento SET id_pais='170'"
    );
    hace_consulta(
        $db, "ALTER TABLE departamento 
        DROP CONSTRAINT departamento_pkey CASCADE", false
    );
    hace_consulta(
        $db, "ALTER TABLE departamento 
        ADD CONSTRAINT departamento_pkey PRIMARY KEY(id, id_pais)", false
    );

    foreach(array("municipio", "clase", "ubicacion", "persona") as $t
    ) {
        $idp = "id_pais";
        $idd = "id_departamento";
        $idm = "id_municipio";
        hace_consulta(
            $db, "ALTER TABLE $t ADD COLUMN 
            {$idp} INTEGER REFERENCES pais", false
        );
        hace_consulta(
            $db, "UPDATE $t SET {$idp} = '170'"
        );
        hace_consulta(
            $db, "ALTER TABLE {$t}
            DROP CONSTRAINT {$t}_{$idd}_fkey CASCADE", false
        );
        hace_consulta(
            $db, "ALTER TABLE {$t}
            ADD CONSTRAINT {$t}_{$idd}_fkey 
            FOREIGN KEY($idd, $idp) 
            REFERENCES departamento(id, id_pais)", false
        );
        if ($t == "municipio") {
            hace_consulta(
                $db, "ALTER TABLE {$t}
                DROP CONSTRAINT {$t}_pkey CASCADE", false
            );
            hace_consulta(
                $db, "ALTER TABLE {$t}
                ADD CONSTRAINT {$t}_pkey 
                PRIMARY KEY(id, id_departamento, id_pais)", false
            );
        }  else if ($t == "clase") {
            hace_consulta(
                $db, "ALTER TABLE {$t}
                DROP CONSTRAINT {$t}_pkey CASCADE", false
            );
            hace_consulta(
                $db, "ALTER TABLE {$t}
                ADD CONSTRAINT {$t}_pkey
                PRIMARY KEY(id, id_municipio, id_departamento, id_pais)", false
            );
        } 

        if ($t != "municipio") {
            hace_consulta(
                $db, "ALTER TABLE {$t}
                DROP CONSTRAINT {$t}_{$idm}_fkey", false
            );
            hace_consulta(
               $db, "ALTER TABLE {$t}
                ADD CONSTRAINT {$t}_{$idm}_fkey 
                FOREIGN KEY($idm, $idd, $idp) 
                REFERENCES municipio(id, id_departamento, id_pais)", false
            );
            if ($t != "clase") {
                hace_consulta(
                    $db, "ALTER TABLE {$t}
                    DROP CONSTRAINT {$t}_id_clase_fkey", false
                );
                hace_consulta(
                    $db, "ALTER TABLE {$t}
                    ADD CONSTRAINT {$t}_id_clase_fkey 
                    FOREIGN KEY(id_clase, id_municipio, id_departamento, id_pais) 
                    REFERENCES clase(id, id_municipio, id_departamento, id_pais)", 
                    false
                );
            }
        }
    }

    aplicaact($act, $idac, 'País'); 
}

if (isset($GLOBALS['menu_tablas_basicas'])) {
    $hayrep = false;
    foreach ($GLOBALS['menu_tablas_basicas'] as $a) {
        if ($a['title'] == 'Reportes'
            || $a['sub'][0]['url'] == 'pconsolidado'
        ) {
            $hayrep = true;
        }
    }
    if (!$hayrep) {
        echo "<font color='red'>En el arreglo <tt>menu_tablas_basicas</tt> " .
            "del archivo <tt>" .
            htmlentities(
                $_SESSION['dirsitio'] . "/conf_int.php", ENT_COMPAT, 'UTF-8'
            ) . "</tt> falta:
<pre>
    array('title' => 'Reportes', 'url'=> null, 'sub' => array(
        array('title'=>'Columnas de Reporte Consolidado',
            'url'=>'parametros_reporte_consolidado', 'sub'=>null),
        ),
    ),
</pre></font>";
    }
} else {
    echo "<font color='red'>No se encontró variable global " .
        "<tt>menu_tablas_basicas</tt></font>";
}


// Creando esquema
regenera_esquemas();

/**
 * Agrega el contendio del archivo $fuente al $destino
 *
 * @param string $fuente  Nombre de archivo fuente
 * @param string $destino Nombre de archivo destino
 * @param string $modo    Modo para abrir archivo destino
 *
 * @return void
 */
function agrega_archivo($fuente, $destino, $modo = "w")
{
    if (!($fen = fopen($fuente, "r"))) {
        die ("No se pudo leer $fuente");
    }
    if (!($fsal = fopen($destino, $modo))) {
        die ("No se pudo escribir $destino");
    }
    $buf = "";
    while (!feof($fen)) {
        $ll = fread($fen, 1024);
        if (fwrite($fsal, $ll) === false) {
            die("No se pudo escribir completo $destino");
        }
    }
    fclose($fen);
    fclose($fsal);
}

/**
 * Lee estructura-dataobject.ini y estructura-dataobject.links.ini
 * del directorio $nd/DataObjects/ y agrega datos a
 * $dirap/DataObjects/$dbnombre.ini y $dirap/DataObjects/$dbnombre.links.ini
 *
 * @param string $nd       Ruta
 * @param string $dbnombre Nombre de la base de datos
 * @param string $dirap    Directorio de SIVeL
 * @param string $modo     Modo para abrir archivo que crea
 *
 * @return void
 */
function lee_escritura($nd, $dbnombre, $dirap, $modo)
{
    if (!file_exists("$nd/DataObjects/estructura-dataobject.ini")) {
        echo "No puede leerse "
            . htmlentities(
                "$nd/DataObjects/estructura-dataobject.ini",
                ENT_COMPAT, 'UTF-8'
            )
            . "<br>";
        return;
    }
    agrega_archivo(
        "$nd/DataObjects/estructura-dataobject.ini",
        "$dirap/DataObjects/$dbnombre.ini", $modo
    );

    if (!file_exists("$nd/DataObjects/estructura-dataobject.links.ini")) {
        die("No puede leerse $nd/DataObjects/estructura-dataobject.ini");
    }
    agrega_archivo(
        "$nd/DataObjects/estructura-dataobject.links.ini",
        "$dirap/DataObjects/$dbnombre.links.ini", $modo
    );
}

if (!isset($_SESSION['SIN_INDICES']) || !$_SESSION['SIN_INDICES']) {
    echo "Actualizando indices<br>";
    $na = "prepara_indices.sql";
    $r = consulta_archivo($db, $na, false, false, false);
}

echo "Actualizando módulos<br>";
$lm = explode(" ", $modulos);
foreach ($lm as $m) {
    if ($m != '') {
        echo_esc("  $m");
        include_once "$m/actualiza.php";
        if (!isset($_SESSION['SIN_INDICES']) || !$_SESSION['SIN_INDICES']) {
            $na = "$m/prepara_indices.sql";
            $r = consulta_archivo($db, $na, false, false, false);
        }
    }
}

$ap = $_SESSION['dirsitio'] . '/actualiza.php';
if (file_exists($ap)) {
    echo_esc("Actualizando personalización ($ap)");
    include_once $ap;
    if (!isset($_SESSION['SIN_INDICES']) || !$_SESSION['SIN_INDICES']) {
        $na = "$m/prepara_indices.sql";
        $r = consulta_archivo($db, $na, false, false, false);
    }
}

echo '<table width="100%"><td style="white-space: nowrap; '
    . 'background-color: #CCCCCC;" align="left" valign="top" colspan="2">'
    . '<b><div align=right><a href="index.php">'
    . _('Men&uacute; Principal') . '</a></div></b>'
    . '</td></table>';

pie_envia();
?>
