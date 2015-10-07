
-- Inicialmente basada en ingeniería inversa de datos a la base empleada en el 
-- "Banco de Datos de derechos humanos y violencia política" de 2000 a 2004. 
-- Dominio público. Sin garantias. vtamara@pasosdeJesus.org. 2004. 

-- Ver convenciones de nomenclatura SQL en manual de SIVeL, sección estándares

--SET client_encoding = 'UTF8';

CREATE COLLATION es_co_utf_8 (LOCALE = 'es_CO.UTF-8');

CREATE EXTENSION unaccent;

ALTER TEXT SEARCH DICTIONARY unaccent (RULES='unaccent');

ALTER FUNCTION unaccent(text) IMMUTABLE;

CREATE TABLE actualizacionbase (
	id VARCHAR(10) PRIMARY KEY,
	fecha DATE NOT NULL,
	descripcion VARCHAR(500) NOT NULL
);

CREATE SEQUENCE antecedente_seq;

CREATE TABLE antecedente (
	id INTEGER PRIMARY KEY DEFAULT(nextval('antecedente_seq')),
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	fechacreacion	DATE NOT NULL,
	fechadeshabilitacion DATE CHECK (
		fechadeshabilitacion IS NULL 
		OR fechadeshabilitacion>=fechacreacion
	)
); 


-- CREATE SEQUENCE intervalo_seq;

CREATE SEQUENCE intervalo_seq;

-- Mejor inicio, fin (?)
CREATE TABLE intervalo (
	id INTEGER PRIMARY KEY DEFAULT (nextval('intervalo_seq')),
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	rango VARCHAR(25) NOT NULL,
	fechacreacion DATE NOT NULL,
	fechadeshabilitacion	DATE CHECK (
		fechadeshabilitacion IS NULL 
		OR fechadeshabilitacion>=fechacreacion
	)
);

CREATE SEQUENCE caso_seq;

CREATE TABLE caso (
	id INTEGER PRIMARY KEY DEFAULT(nextval('caso_seq')),
	titulo VARCHAR(50),
	fecha DATE NOT NULL,
	hora VARCHAR(10),
	duracion VARCHAR(10),
	memo TEXT NOT NULL,
	grconfiabilidad VARCHAR(5), 
	gresclarecimiento VARCHAR(5),
	grimpunidad VARCHAR(5),
	grinformacion VARCHAR(5),
	bienes TEXT,
	id_intervalo INTEGER REFERENCES intervalo DEFAULT '5'
); 

CREATE INDEX caso_titulo ON caso 
USING gin(to_tsvector('spanish', unaccent(caso.titulo)));

CREATE INDEX caso_memo ON caso 
USING gin(to_tsvector('spanish', unaccent(caso.memo)));

CREATE SEQUENCE pconsolidado_seq;

CREATE TABLE pconsolidado (
	id INTEGER PRIMARY KEY DEFAULT (nextval('pconsolidado_seq')),
	rotulo VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	tipoviolencia VARCHAR(25) NOT NULL,
	clasificacion VARCHAR(25) NOT NULL,
	peso	INTEGER DEFAULT '0',
	fechacreacion DATE NOT NULL,
	fechadeshabilitacion	DATE CHECK (
		fechadeshabilitacion IS NULL OR 
		fechadeshabilitacion>=fechacreacion
	)
);

CREATE TABLE  tviolencia (
	id CHAR(1) PRIMARY KEY,
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	nomcorto VARCHAR(10) NOT NULL,
	fechacreacion DATE NOT NULL,
	fechadeshabilitacion	DATE CHECK (
		fechadeshabilitacion IS NULL OR 
		fechadeshabilitacion>=fechacreacion
	)
);

CREATE TABLE supracategoria (
	id INTEGER NOT NULL,
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	fechacreacion DATE NOT NULL,
	fechadeshabilitacion	DATE CHECK (
		fechadeshabilitacion IS NULL OR 
		fechadeshabilitacion>=fechacreacion
	), 
	id_tviolencia VARCHAR(1) REFERENCES tviolencia NOT NULL,
	PRIMARY KEY (id, id_tviolencia)
);


CREATE TABLE categoria (
	id INTEGER PRIMARY KEY,
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	fechacreacion	DATE NOT NULL,
	fechadeshabilitacion	DATE CHECK (
		fechadeshabilitacion IS NULL OR 
		fechadeshabilitacion>=fechacreacion
	),
	id_supracategoria INTEGER NOT NULL,
	id_tviolencia VARCHAR(1) NOT NULL REFERENCES tviolencia,
	id_pconsolidado INTEGER REFERENCES pconsolidado (id),
	contadaen INTEGER REFERENCES categoria,
	tipocat	CHAR DEFAULT 'I' CHECK (
		tipocat='I' OR tipocat='C' OR tipocat='O'
	) ,
	FOREIGN KEY (id_supracategoria, id_tviolencia) REFERENCES 
		supracategoria (id, id_tviolencia)
);


CREATE TABLE tclase (
	id VARCHAR(10) PRIMARY KEY,
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	fechacreacion DATE NOT NULL,
	fechadeshabilitacion	DATE CHECK (
		fechadeshabilitacion IS NULL OR 
		fechadeshabilitacion>=fechacreacion
	)
);


CREATE SEQUENCE departamento_seq;


CREATE TABLE departamento (
	id INTEGER PRIMARY KEY DEFAULT(nextval('departamento_seq')),
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	latitud FLOAT,
	longitud FLOAT,
	fechacreacion	DATE NOT NULL,
	fechadeshabilitacion	DATE CHECK (
		fechadeshabilitacion IS NULL OR 
		fechadeshabilitacion>=fechacreacion
	)
);


CREATE SEQUENCE municipio_seq;

-- Convencion en nombre del municipio, a continuacion del nombre entre parentesis puede venir un nombre alternativo.  Bien porque historicamente se conociera de otra forma o bien el nombre de la cabecera municipal si es diferente al nombre del municipio.  Si se requieren varios nombres alternativos separarlos dentro del paréntesis con comas.
-- Preferimos la comunidad (directa, wikipedia, alcaldias y despues Divipola) pues hay municipios que no estan en el DIVIPOLA. Por ejemplo consultando el 3.Ene.2013 http://www.dane.gov.co/index.php?option=com_content&view=article&id=1770&Itemid=92 no se encuentra SAN JOSÉ DE OCUNE, VICHADA, si está pero sin alcalde en http://www.portalterritorial.gov.co/dir_vichada.shtml, tampoco esta en Openstreetmap, si esta en http://en.wikipedia.org/wiki/San_Jose_de_Ocune
-- Hay nombres que cambian, por ejemplo en 3.Ene.2012 no hay CERRO DE SAN ANTONIO en MAGDALENA, se encunetra CERRO SAN ANTONIO, aunque la pagina de la alcaldia es http://www.cerrodesanantonio-magdalena.gov.co/index.shtml
CREATE TABLE municipio (
	id INTEGER NOT NULL DEFAULT(nextval('municipio_seq')),
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	id_departamento INTEGER NOT NULL REFERENCES departamento 
	ON DELETE CASCADE,
	latitud FLOAT,
	longitud FLOAT,
	fechacreacion	DATE NOT NULL,
	fechadeshabilitacion	DATE CHECK (
		fechadeshabilitacion IS NULL OR 
		fechadeshabilitacion>=fechacreacion
	),
	PRIMARY KEY (id, id_departamento)
);


CREATE SEQUENCE clase_seq;

CREATE TABLE clase (
	id INTEGER NOT NULL DEFAULT(nextval('clase_seq')),
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	id_departamento INTEGER REFERENCES departamento ON DELETE CASCADE,
	id_municipio INTEGER,
	id_tclase VARCHAR(10) REFERENCES tclase, 
	latitud FLOAT,
	longitud FLOAT,
	fechacreacion	DATE NOT NULL,
	fechadeshabilitacion	DATE CHECK (
		fechadeshabilitacion IS NULL OR 
		fechadeshabilitacion>=fechacreacion
	),
	FOREIGN KEY (id_municipio, id_departamento) REFERENCES 
		municipio (id, id_departamento) ON DELETE CASCADE,
	PRIMARY KEY (id, id_municipio, id_departamento)
);


CREATE SEQUENCE contexto_seq;

CREATE TABLE contexto (
	id INTEGER PRIMARY KEY DEFAULT(nextval('contexto_seq')),
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	fechacreacion	DATE NOT NULL,
	fechadeshabilitacion	DATE CHECK (
		fechadeshabilitacion IS NULL OR 
		fechadeshabilitacion>=fechacreacion
	)
);

CREATE SEQUENCE etnia_seq;

CREATE TABLE etnia (
	id INTEGER PRIMARY KEY DEFAULT(nextval('etnia_seq')),
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	descripcion VARCHAR(1000),
	fechacreacion	DATE NOT NULL,
	fechadeshabilitacion	DATE CHECK (
		fechadeshabilitacion IS NULL OR 
		fechadeshabilitacion>=fechacreacion
	)
);

CREATE SEQUENCE iglesia_seq;

CREATE TABLE iglesia (
	id INTEGER PRIMARY KEY DEFAULT(nextval('iglesia_seq')),
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	descripcion VARCHAR(1000),
	fechacreacion DATE NOT NULL,
	fechadeshabilitacion DATE CHECK (
		fechadeshabilitacion IS NULL OR 
		fechadeshabilitacion>=fechacreacion
	)
);

CREATE SEQUENCE filiacion_seq;

CREATE TABLE filiacion (
	id INTEGER PRIMARY KEY DEFAULT(nextval('filiacion_seq')),
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	fechacreacion DATE NOT NULL,
	fechadeshabilitacion DATE CHECK (
		fechadeshabilitacion IS NULL OR 
		fechadeshabilitacion>=fechacreacion
	)
);

CREATE SEQUENCE frontera_seq;

CREATE TABLE frontera (
	id INTEGER PRIMARY KEY DEFAULT(nextval('frontera_seq')),
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	fechacreacion DATE NOT NULL,
	fechadeshabilitacion	DATE CHECK (
		fechadeshabilitacion IS NULL OR 
		fechadeshabilitacion>=fechacreacion
	)
);

CREATE SEQUENCE fotra_seq;

CREATE TABLE fotra (
	id INTEGER PRIMARY KEY DEFAULT(nextval('fotra_seq')),
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL
);

CREATE SEQUENCE organizacion_seq;

CREATE TABLE organizacion (
	id INTEGER PRIMARY KEY DEFAULT(nextval('organizacion_seq')),
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	fechacreacion DATE NOT NULL,
	fechadeshabilitacion DATE CHECK (
		fechadeshabilitacion IS NULL OR 
		fechadeshabilitacion>=fechacreacion
	)
);

CREATE SEQUENCE ffrecuente_seq;

CREATE TABLE ffrecuente (
	id INTEGER PRIMARY KEY DEFAULT(nextval('ffrecuente_seq')),
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	tfuente VARCHAR(25) NOT NULL,
	fechacreacion DATE NOT NULL,
	fechadeshabilitacion	DATE CHECK (
		fechadeshabilitacion IS NULL OR 
		fechadeshabilitacion>=fechacreacion
	)
);

CREATE SEQUENCE presponsable_seq;

CREATE TABLE presponsable (
	id INTEGER PRIMARY KEY DEFAULT(nextval('presponsable_seq')),
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	papa INTEGER REFERENCES presponsable,
	fechacreacion DATE NOT NULL,
	fechadeshabilitacion	DATE CHECK (
		fechadeshabilitacion IS NULL OR 
		fechadeshabilitacion>=fechacreacion
	)
);

CREATE SEQUENCE rangoedad_seq;

CREATE TABLE rangoedad ( 
	id INTEGER PRIMARY KEY DEFAULT(nextval('rangoedad_seq')),
	nombre VARCHAR(20) COLLATE es_co_utf_8 NOT NULL,
	rango VARCHAR(20) NOT NULL,
	limiteinferior INTEGER NOT NULL DEFAULT '0',
	limitesuperior INTEGER NOT NULL DEFAULT '0',
	fechacreacion DATE NOT NULL,
	fechadeshabilitacion	DATE CHECK (
		fechadeshabilitacion IS NULL OR 
		fechadeshabilitacion>=fechacreacion
	)
);

CREATE SEQUENCE region_seq;

CREATE TABLE region (
	id INTEGER PRIMARY KEY DEFAULT(nextval('region_seq')),
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	fechacreacion DATE NOT NULL,
	fechadeshabilitacion	DATE CHECK (
		fechadeshabilitacion IS NULL OR 
		fechadeshabilitacion>=fechacreacion
	)

); 

CREATE SEQUENCE resagresion_seq;

CREATE TABLE resagresion (
	id INTEGER PRIMARY KEY DEFAULT(nextval('resagresion_seq')),
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	fechacreacion DATE NOT NULL,
	fechadeshabilitacion	DATE CHECK (
		fechadeshabilitacion IS NULL OR 
		fechadeshabilitacion>=fechacreacion
	)
); 

CREATE SEQUENCE sectorsocial_seq;

CREATE TABLE sectorsocial (
	id INTEGER PRIMARY KEY DEFAULT(nextval('sectorsocial_seq')),
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	fechacreacion DATE NOT NULL,
	fechadeshabilitacion	DATE CHECK (
		fechadeshabilitacion IS NULL OR 
		fechadeshabilitacion>=fechacreacion
	)
); 

CREATE SEQUENCE tsitio_seq; 

CREATE TABLE tsitio (
	id INTEGER PRIMARY KEY DEFAULT(nextval('tsitio_seq')),
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	fechacreacion DATE NOT NULL,
	fechadeshabilitacion	DATE CHECK (
		fechadeshabilitacion IS NULL OR 
		fechadeshabilitacion>=fechacreacion
	)
);

CREATE SEQUENCE ubicacion_seq;

CREATE TABLE ubicacion (
	id INTEGER PRIMARY KEY DEFAULT (nextval('ubicacion_seq')),
	lugar VARCHAR(500) COLLATE es_co_utf_8,
	sitio VARCHAR(500) COLLATE es_co_utf_8,
	id_clase INTEGER,
	id_municipio INTEGER,
	id_departamento INTEGER REFERENCES departamento,
	id_tsitio INTEGER REFERENCES tsitio NOT NULL DEFAULT '1',
	id_caso INTEGER NOT NULL REFERENCES caso,
	latitud FLOAT,
	longitud FLOAT,

	FOREIGN KEY (id_municipio, id_departamento) REFERENCES
		municipio (id, id_departamento),
	FOREIGN KEY (id_clase, id_municipio, id_departamento) REFERENCES
		clase (id, id_municipio, id_departamento)
); 

CREATE SEQUENCE usuario_seq;

-- Sólo deben poderse autenticar quienes tengan NULL en fechadeshabilitacion
CREATE TABLE usuario (
	id INTEGER PRIMARY KEY DEFAULT(nextval('usuario_seq')),
	nusuario VARCHAR(15) NOT NULL UNIQUE,
	password VARCHAR(64) NOT NULL,
	nombre VARCHAR(50) COLLATE es_co_utf_8,
	descripcion VARCHAR(50),
	rol INTEGER DEFAULT '4' CHECK (rol>='1' AND rol<='4'),
	idioma VARCHAR(6) NOT NULL DEFAULT 'es_CO',
	email VARCHAR(255) NOT NULL DEFAULT '',
	encrypted_password VARCHAR(255) NOT NULL DEFAULT '',
	sign_in_count INTEGER NOT NULL DEFAULT '0',
	fechacreacion DATE NOT NULL,
	fechadeshabilitacion DATE CHECK (
		fechadeshabilitacion IS NULL OR 
		fechadeshabilitacion>=fechacreacion
	),
	failed_attempts INTEGER,
	unlock_token VARCHAR(64),
	locked_at TIMESTAMP
);

CREATE SEQUENCE vinculoestado_seq;

CREATE TABLE vinculoestado (
	id INTEGER PRIMARY KEY DEFAULT(nextval('vinculoestado_seq')),
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	fechacreacion DATE NOT NULL,
	fechadeshabilitacion DATE CHECK (
		fechadeshabilitacion IS NULL OR 
		fechadeshabilitacion>=fechacreacion
	)
);

CREATE SEQUENCE profesion_seq;

CREATE TABLE profesion (
	id INTEGER PRIMARY KEY DEFAULT(nextval('profesion_seq')),
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	fechacreacion DATE NOT NULL,
	fechadeshabilitacion DATE CHECK (
		fechadeshabilitacion IS NULL OR 
		fechadeshabilitacion>=fechacreacion
	)
);


CREATE SEQUENCE persona_seq;

CREATE TABLE persona (
	id INTEGER PRIMARY KEY DEFAULT(nextval('persona_seq')),
	nombres VARCHAR(100) COLLATE es_co_utf_8 NOT NULL,
	apellidos VARCHAR(100) COLLATE es_co_utf_8 NOT NULL,
	anionac         INTEGER,
	mesnac          INTEGER CHECK (
		mesnac IS NULL OR (mesnac>='1' AND mesnac<='12')
	),
	dianac          INTEGER CHECK (
		dianac IS NULL OR (dianac>='1' AND 
			(((mesnac='1' OR mesnac='3' OR mesnac='5' OR 
				mesnac='7' OR mesnac='8' OR mesnac='10' OR 
				mesnac='12') AND dianac<='31')) OR 
			((mesnac='4' OR mesnac='6' OR mesnac='9' OR 
					mesnac='11') AND dianac<='30') OR 
			(mesnac='2' AND dianac<='29'))
	),
	sexo CHAR(1) NOT NULL CHECK (sexo='S' OR sexo='F' OR sexo='M'),
	id_departamento INTEGER REFERENCES departamento ON DELETE CASCADE,
	id_municipio    INTEGER,
	id_clase        INTEGER,
	-- Verificar en interfaz
	tipodocumento VARCHAR(2),
	numerodocumento BIGINT
);

CREATE INDEX persona_nombres_apellidos ON persona 
USING gin(to_tsvector('spanish', unaccent(persona.nombres) 
		|| ' ' || unaccent(persona.apellidos)));

CREATE INDEX persona_apellidos_nombres ON persona 
USING gin(to_tsvector('spanish', unaccent(persona.apellidos)
		|| ' ' || unaccent(persona.nombres)));

CREATE INDEX persona_nombres_apellidos_doc ON persona 
USING gin(to_tsvector('spanish', unaccent(persona.nombres) 
	|| ' ' || unaccent(persona.apellidos) 
	|| ' ' || COALESCE(persona.numerodocumento::TEXT, '')));

CREATE INDEX persona_apellidos_nombres_doc ON persona 
USING gin(to_tsvector('spanish', unaccent(persona.apellidos) 
	 || ' ' || unaccent(persona.nombres) 
	 || ' ' || COALESCE(persona.numerodocumento::TEXT, '')));

CREATE TABLE trelacion (
	id      CHAR(2) PRIMARY KEY,
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	observaciones VARCHAR(200),
	inverso	CHAR(2) REFERENCES trelacion(id),
	fechacreacion DATE NOT NULL,
	fechadeshabilitacion DATE CHECK (
		fechadeshabilitacion IS NULL OR 
		fechadeshabilitacion>=fechacreacion
	)
);

CREATE TABLE persona_trelacion (
	persona1 INTEGER NOT NULL REFERENCES persona,
	persona2 INTEGER NOT NULL REFERENCES persona,
	id_trelacion CHAR(2) NOT NULL REFERENCES trelacion DEFAULT 'SI',
	observaciones VARCHAR(200),
	PRIMARY KEY(persona1, persona2, id_trelacion)
);


-- Victima depende de caso.  Podría hacerse una tabla persona relacionada
-- con esta. 
CREATE TABLE victima (
	id_persona INTEGER REFERENCES persona NOT NULL,
	id_caso	INTEGER REFERENCES caso NOT NULL,
	hijos INTEGER CHECK (hijos IS NULL OR (hijos>='0' AND hijos<='100')),
	id_profesion INTEGER REFERENCES profesion NOT NULL DEFAULT '22',
	id_rangoedad INTEGER REFERENCES rangoedad NOT NULL DEFAULT '6', 
	id_filiacion INTEGER REFERENCES filiacion NOT NULL DEFAULT '10',
	id_sectorsocial INTEGER REFERENCES sectorsocial NOT NULL DEFAULT '15',
	id_organizacion	INTEGER REFERENCES organizacion NOT NULL DEFAULT '16',
	id_vinculoestado INTEGER REFERENCES vinculoestado NOT NULL DEFAULT '38',
	organizacionarmada INTEGER REFERENCES presponsable NOT NULL DEFAULT '35',
	anotaciones	VARCHAR(1000),
	id_etnia INTEGER REFERENCES etnia DEFAULT '1',
	id_iglesia INTEGER REFERENCES iglesia DEFAULT '1',
       	orientacionsexual CHAR(1) NOT NULL DEFAULT 'H' CHECK (
		orientacionsexual='L' OR 
		orientacionsexual='G' OR 
		orientacionsexual='B' OR 
		orientacionsexual='T' OR 
		orientacionsexual='I' OR 
		orientacionsexual='H'
	),
	PRIMARY KEY(id_persona, id_caso)
);

CREATE SEQUENCE grupoper_seq;

CREATE TABLE grupoper (
	id INTEGER PRIMARY KEY DEFAULT(nextval('grupoper_seq')),
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	anotaciones VARCHAR(1000)
);

CREATE TABLE victimacolectiva (
	id_grupoper INTEGER REFERENCES grupoper,
	id_caso INTEGER REFERENCES caso,
	personasaprox INTEGER,
	organizacionarmada INTEGER REFERENCES presponsable DEFAULT '35',
	PRIMARY KEY(id_grupoper, id_caso)
);

CREATE TABLE comunidad_vinculoestado (
	id_vinculoestado INTEGER REFERENCES vinculoestado DEFAULT '38',
	id_grupoper INTEGER REFERENCES grupoper,
	id_caso INTEGER REFERENCES caso,
	FOREIGN KEY (id_grupoper, id_caso) REFERENCES 
		victimacolectiva(id_grupoper, id_caso),
	PRIMARY KEY(id_vinculoestado, id_grupoper, id_caso)
);

CREATE TABLE comunidad_profesion (
	id_profesion INTEGER REFERENCES profesion DEFAULT '22',
	id_grupoper INTEGER REFERENCES grupoper,
	id_caso INTEGER REFERENCES caso,
	FOREIGN KEY (id_grupoper, id_caso) REFERENCES 
		victimacolectiva(id_grupoper, id_caso),
	PRIMARY KEY(id_profesion, id_grupoper, id_caso)
);


CREATE TABLE antecedente_caso (
	id_antecedente INTEGER REFERENCES antecedente,
	id_caso INTEGER REFERENCES caso,
	PRIMARY KEY(id_antecedente, id_caso)
);

-- Los antecedentes de la victima no deben ser subconjunto de los del caso
CREATE TABLE antecedente_victima (
	id_antecedente INTEGER REFERENCES antecedente,
	id_persona INTEGER NOT NULL REFERENCES persona,
	id_caso INTEGER NOT NULL REFERENCES caso,
	FOREIGN KEY(id_persona, id_caso) REFERENCES 
		victima (id_persona, id_caso),

	PRIMARY KEY(id_antecedente, id_persona, id_caso)
);

-- Si se quisieran reusan victimas_colectivas tal vez esta tabla
-- debería tener id_caso
CREATE TABLE antecedente_comunidad (
	id_antecedente INTEGER REFERENCES antecedente,
	id_grupoper INTEGER REFERENCES grupoper,
	id_caso INTEGER REFERENCES caso,
	FOREIGN KEY (id_grupoper, id_caso) REFERENCES 
		victimacolectiva(id_grupoper, id_caso),
	PRIMARY KEY(id_antecedente, id_grupoper, id_caso)
);


-- Por uniformidad mejor llamarlo contexto_caso
CREATE TABLE caso_contexto (
	id_caso INTEGER REFERENCES caso,
	id_contexto INTEGER REFERENCES contexto,
	PRIMARY KEY(id_caso, id_contexto)
);

CREATE TABLE caso_presponsable (
	id_caso INTEGER REFERENCES caso,
	id_presponsable INTEGER REFERENCES presponsable,
	tipo	INTEGER	NOT NULL,
	bloque	VARCHAR(50),
	frente	VARCHAR(50),
	brigada	VARCHAR(50),
	batallon VARCHAR(50),
	division VARCHAR(50),
	otro VARCHAR(500),
	id INTEGER NOT NULL,
	PRIMARY KEY (id_caso, id_presponsable, id)
);


CREATE TABLE caso_categoria_presponsable (
	id_tviolencia VARCHAR(1) REFERENCES tviolencia,
	id_supracategoria INTEGER,
	id_categoria INTEGER REFERENCES categoria, 
	--En interfaz verificar que categoria es de tipocat Otra ('O')
	id INTEGER NOT NULL,
	id_caso INTEGER REFERENCES caso,
	id_presponsable INTEGER REFERENCES presponsable,
	PRIMARY KEY(id_tviolencia, id_supracategoria, id_categoria,
		id, id_caso, id_presponsable),
	FOREIGN KEY (id_supracategoria, id_tviolencia) 
	REFERENCES supracategoria (id, id_tviolencia),
	FOREIGN KEY (id, id_caso, id_presponsable)
	REFERENCES caso_presponsable (id, id_caso, 
		id_presponsable)
);


-- Algunos campos ubicación, clasificacion y ubicacionfisica son NULL (no pueden ser llave).
-- Puede mejorarse clasificacion, para que esté relacionada con clasificacion
-- del caso
CREATE TABLE caso_ffrecuente (
	fecha DATE,
	ubicacion VARCHAR(100),  -- En interfaz descripción página
	clasificacion VARCHAR(100), -- Categoria que esta fuente clasifica 
	ubicacionfisica VARCHAR(100),
	id_ffrecuente INTEGER REFERENCES ffrecuente,
	id_caso	INTEGER REFERENCES caso,
	PRIMARY KEY(fecha, id_ffrecuente, id_caso)
);

CREATE TABLE comunidad_filiacion (
	id_filiacion INTEGER REFERENCES filiacion DEFAULT '10',
	id_grupoper INTEGER REFERENCES grupoper,
	id_caso INTEGER REFERENCES caso,
	FOREIGN KEY (id_grupoper, id_caso) REFERENCES 
		victimacolectiva(id_grupoper, id_caso),
	PRIMARY KEY(id_filiacion, id_grupoper, id_caso)
);

CREATE TABLE caso_frontera (
	id_frontera INTEGER REFERENCES frontera,
	id_caso INTEGER REFERENCES caso,
	PRIMARY KEY(id_frontera, id_caso)
);

-- Tambien hay fuentes indirectas (pero no frecuentes).
CREATE TABLE caso_fotra (
	id_caso INTEGER REFERENCES caso,
	id_fotra INTEGER REFERENCES fotra,
	anotacion VARCHAR(200),
	fecha DATE,
	ubicacionfisica VARCHAR(100),
	tfuente VARCHAR(25),
	PRIMARY KEY(id_caso, id_fotra, fecha)
);

CREATE TABLE caso_usuario (
	id_usuario INTEGER REFERENCES usuario,
	id_caso INTEGER REFERENCES caso,
	fechainicio DATE,
	PRIMARY KEY(id_usuario, id_caso)
);

CREATE TABLE comunidad_organizacion (
	id_organizacion INTEGER REFERENCES organizacion DEFAULT '16',
	id_grupoper INTEGER REFERENCES grupoper,
	id_caso INTEGER REFERENCES caso,
	FOREIGN KEY (id_grupoper, id_caso) REFERENCES 
		victimacolectiva(id_grupoper, id_caso),
	PRIMARY KEY(id_organizacion, id_grupoper, id_caso)
);

CREATE TABLE comunidad_rangoedad (
	id_rangoedad INTEGER REFERENCES rangoedad DEFAULT '6',
	id_grupoper INTEGER REFERENCES grupoper,
	id_caso INTEGER REFERENCES caso,
	FOREIGN KEY (id_grupoper, id_caso) REFERENCES 
		victimacolectiva(id_grupoper, id_caso),
	PRIMARY KEY(id_rangoedad, id_grupoper, id_caso)
);


CREATE TABLE caso_region (
	id_region INTEGER REFERENCES region,
	id_caso INTEGER REFERENCES caso,
	PRIMARY KEY(id_region, id_caso)
);


CREATE TABLE comunidad_sectorsocial (
	id_sectorsocial INTEGER REFERENCES sectorsocial DEFAULT '15',
	id_grupoper INTEGER REFERENCES grupoper,
	id_caso INTEGER REFERENCES caso,
	FOREIGN KEY (id_grupoper, id_caso) REFERENCES 
		victimacolectiva(id_grupoper, id_caso),
	PRIMARY KEY(id_sectorsocial, id_grupoper, id_caso)
);


CREATE TABLE acto (
	id_presponsable INTEGER REFERENCES presponsable,
	id_categoria INTEGER REFERENCES categoria,
	id_persona INTEGER REFERENCES persona,
	id_caso INTEGER REFERENCES caso,
	FOREIGN KEY (id_persona, id_caso) REFERENCES 
		victima(id_persona, id_caso),
	PRIMARY KEY(id_presponsable, id_categoria, id_persona, id_caso)
);

CREATE TABLE actocolectivo (
	id_presponsable INTEGER REFERENCES presponsable,
	id_categoria INTEGER REFERENCES categoria,
	id_grupoper INTEGER REFERENCES grupoper,
	id_caso INTEGER REFERENCES caso,
	FOREIGN KEY (id_grupoper, id_caso) REFERENCES 
		victimacolectiva(id_grupoper, id_caso),
	PRIMARY KEY(id_presponsable, id_categoria, id_grupoper, id_caso)
);


-- Funciones y Vistas

-- Soundex

-- Funcion Soundex en Español para una cadena sin espacios
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
		-- pasar a mayuscula, eliminar la letra "H" inicial, los acentos y la enie
		-- 'holá coñó' => 'OLA CONO'
		input=translate(ltrim(trim(upper(input)),'H'),'ÑÁÉÍÓÚÀÈÌÒÙÜ','NAEIOUAEIOUU');
 
		-- eliminar caracteres no alfabéticos (números, símbolos como &,%,",*,!,+, etc.
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
 
	-- 3: corregir "letras compuestas" y volverlas una sola
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
;


CREATE OR REPLACE FUNCTION soundexespm(in_text TEXT) RETURNS TEXT AS
$$
	SELECT ARRAY_TO_STRING(ARRAY_AGG(soundexesp(s)),' ')                
	FROM (SELECT UNNEST(STRING_TO_ARRAY(
		REGEXP_REPLACE(TRIM($1), '  *', ' '), ' ')) AS s                
	      ORDER BY 1) AS n;
$$
LANGUAGE SQL IMMUTABLE;

CREATE MATERIALIZED VIEW vvictimasoundexesp AS
	SELECT victima.id_caso, persona.id AS id_persona, 
		(persona.nombres || ' ' || persona.apellidos) AS nomap, 
		soundexespm(nombres || ' ' || apellidos) as nomsoundexesp FROM persona, victima 
	WHERE persona.id=victima.id_persona;

-- Hombres - Mujeres

-- Convierte un arreglo de cadenas en un conjunto de resultados en mayusculas
CREATE OR REPLACE FUNCTION divarr(in_array ANYARRAY) RETURNS SETOF TEXT as
$$
    SELECT ($1)[s] FROM generate_series(1,array_upper($1, 1)) AS s;
$$
LANGUAGE SQL IMMUTABLE;


-- Pareja (nombre, numero de caso)
CREATE TYPE nomcod AS (nombre VARCHAR(100), caso INTEGER);

-- Vista con nombres de mujeres y frecuencia de cada nombre
CREATE MATERIALIZED VIEW nmujeres AS 
	SELECT  s.nombre, COUNT(*) AS frec
	FROM (SELECT 
		divarr(string_to_array(trim(nombres), ' ')) AS nombre
		FROM persona, victima WHERE victima.id_persona=persona.id 
		AND sexo='F') AS s
	GROUP BY s.nombre ORDER BY frec;

-- Vista con nombres de hombres y frecuencia de cada nombre
CREATE MATERIALIZED VIEW nhombres AS 
	SELECT  s.nombre, COUNT(*) AS frec
	FROM (SELECT 
		divarr(string_to_array(trim(nombres), ' ')) AS nombre
		FROM persona, victima WHERE victima.id_persona=persona.id 
		AND sexo='M') AS s
	GROUP BY s.nombre ORDER BY frec;


-- Probabilidad de que una cadena (sin espacios) sea nombre de mujer
CREATE OR REPLACE FUNCTION probcadm(in_text TEXT) RETURNS NUMERIC AS
$$
	SELECT CASE WHEN (SELECT SUM(frec) FROM nmujeres)=0 THEN 0
		WHEN (SELECT COUNT(*) FROM nmujeres WHERE nombre=$1)=0 THEN 0
		ELSE (SELECT frec/(SELECT SUM(frec) FROM nmujeres) 
			FROM nmujeres WHERE nombre=$1)
		END
$$
LANGUAGE SQL IMMUTABLE;

-- Probabilidad de que una cadena (sin espacios) sea un nombre de hombre
CREATE OR REPLACE FUNCTION probcadh(in_text TEXT) RETURNS NUMERIC AS
$$
	SELECT CASE WHEN (SELECT SUM(frec) FROM nhombres)=0 THEN 0
		WHEN (SELECT COUNT(*) FROM nhombres WHERE nombre=$1)=0 THEN 0
		ELSE (SELECT frec/(SELECT SUM(frec) FROM nhombres) 
			FROM nhombres WHERE nombre=$1)
		END
$$
LANGUAGE SQL IMMUTABLE;


-- Heuristica de probabilidad que un nombre compuesto sea de hombre
-- Supone que la primera parte tiene más peso que las demás
CREATE OR REPLACE FUNCTION probhombre(in_text TEXT) RETURNS NUMERIC AS
$$
	SELECT sum(ppar) FROM (SELECT p, peso*probcadh(p) AS ppar FROM (
		SELECT p, CASE WHEN rnum=1 THEN 100 ELSE 1 END AS peso 
		FROM (SELECT p, row_number() OVER () AS rnum FROM 
			divarr(string_to_array(trim($1), ' ')) AS p) 
		AS s) AS s2) AS s3;
$$
LANGUAGE SQL IMMUTABLE;

-- Idea de probabilidad que un nombre compuesto sea de mujer
-- Supone que la primera parte tiene más peso que las demás
CREATE OR REPLACE FUNCTION probmujer(in_text TEXT) RETURNS NUMERIC AS
$$
	SELECT sum(ppar) FROM (SELECT p, peso*probcadm(p) AS ppar FROM (
		SELECT p, CASE WHEN rnum=1 THEN 100 ELSE 1 END AS peso 
		FROM (SELECT p, row_number() OVER () AS rnum FROM 
			divarr(string_to_array(trim($1), ' ')) AS p) 
		AS s) AS s2) AS s3;
$$
LANGUAGE SQL IMMUTABLE;

-- Vista con apellidos y frecuencia de cada uno
CREATE MATERIALIZED VIEW napellidos AS 
	SELECT  s.apellido, COUNT(*) AS frec
	FROM (SELECT 
		divarr(string_to_array(trim(apellidos), ' ')) AS apellido
		FROM persona, victima WHERE victima.id_persona=persona.id) AS s 
	GROUP BY s.apellido ORDER BY frec;

-- Probabilidad de que una cadena (sin espacios) sea un apellido
CREATE OR REPLACE FUNCTION probcadap(in_text TEXT) RETURNS NUMERIC AS
$$
	SELECT CASE WHEN (SELECT SUM(frec) FROM napellidos)=0 THEN 0
		WHEN (SELECT COUNT(*) FROM napellidos WHERE apellido=$1)=0 THEN 0
		ELSE (SELECT frec/(SELECT SUM(frec) FROM napellidos) 
			FROM napellidos WHERE apellido=$1)
		END
$$
LANGUAGE SQL IMMUTABLE;

-- Idea de probabilidad que una cadena con espacios sea apellido
CREATE OR REPLACE FUNCTION probapellido(in_text TEXT) RETURNS NUMERIC AS
$$
	SELECT sum(ppar) FROM (SELECT p, probcadap(p) AS ppar FROM (
		SELECT p FROM divarr(string_to_array(trim($1), ' ')) AS p) 
		AS s) AS s2;
$$
LANGUAGE SQL IMMUTABLE;


