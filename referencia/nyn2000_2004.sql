
-- Ingeniería inversa de datos a la base empleada en el "Banco de Datos 
-- de derechos humanos y violencia política" de 2000 a 2004. 
-- Las llaves primarias y la integridad referencial se dedujeron a partir
-- de los datos.  
-- Dominio público. Sin garantias. 2004. vtamara@pasosdeJesus.org

CREATE TABLE antecedente (
		id INTEGER PRIMARY KEY,
		nombre CHAR(25) NOT NULL,
		fecha_creacion	DATE NOT NULL,
		fecha_deshabilitacion	DATE CHECK (fecha_deshabilitacion=NULL OR fecha_deshabilitacion>fecha_creacion)
); 

-- Mejor inicio, fin (?)
CREATE TABLE intervalo (
		id CHAR(5) PRIMARY KEY,
		nombre CHAR(25) NOT NULL,
		rango CHAR(25) NOT NULL
);

CREATE TABLE caso (
	id INTEGER PRIMARY KEY,
	titulo CHAR(50),
	fecha DATE NOT NULL,
	hora CHAR(10),
	duracion CHAR(10),
	memo	TEXT NOT NULL,
	gr_confiabilidad CHAR(5), 
	gr_esclarecimiento CHAR(5),
	gr_impunidad CHAR(5),
	gr_informacion CHAR(5),
	bienes TEXT,
	tipo_ubicacion CHAR(1) CHECK (tipo_ubicacion='R' OR
		tipo_ubicacion='S' OR tipo_ubicacion='U' OR
		tipo_ubicacion=NULL),
	id_intervalo CHAR(5) REFERENCES intervalo
); 


CREATE TABLE tipo_violencia (
		id CHAR(1) PRIMARY KEY,
		nombre CHAR(50) NOT NULL,
		fecha_creacion DATE NOT NULL,
		fecha_deshabilitacion	DATE CHECK (fecha_deshabilitacion=NULL OR fecha_deshabilitacion>fecha_creacion)
);

CREATE TABLE supracategoria (
		id INTEGER NOT NULL,
		nombre CHAR(50) NOT NULL,
		fecha_creacion DATE NOT NULL,
		fecha_deshabilitacion	DATE CHECK (fecha_deshabilitacion=NULL OR fecha_deshabilitacion>fecha_creacion), 
		id_tipo_violencia CHAR(1) REFERENCES tipo_violencia,
		PRIMARY KEY (id, id_tipo_violencia)
);

CREATE TABLE categoria (
		id INTEGER PRIMARY KEY,
		nombre CHAR(50) NOT NULL,
		fecha_creacion	DATE NOT NULL,
		fecha_deshabilitacion	DATE CHECK (fecha_deshabilitacion=NULL OR fecha_deshabilitacion>fecha_creacion), 
		id_supracategoria INTEGER,
		id_tipo_violencia CHAR(1) REFERENCES tipo_violencia,
		col_rep_consolidad INTEGER,
		FOREIGN KEY (id_supracategoria, id_tipo_violencia) 
			REFERENCES supracategoria (id, id_tipo_violencia)
);

CREATE TABLE tipo_clase (
		id VARCHAR(3) PRIMARY KEY,
		nombre CHAR(50) NOT NULL
);

CREATE TABLE departamento (
	id INTEGER PRIMARY KEY,
	nombre CHAR(50) NOT NULL
);

-- La información geográfica se ha actualizado con los datos del DANE
-- y del Depto. Nacional de Planeacion.
-- Por esto se ha perdido información porque han remplazado registros
-- manteniendo códigos antiguos (para actualizar con respecto al
-- DANE).
CREATE TABLE municipio (
	id INTEGER NOT NULL,
	nombre CHAR(50) NOT NULL,
	id_departamento INTEGER NOT NULL REFERENCES departamento,
	PRIMARY KEY (id, id_departamento)
);

CREATE TABLE clase (
	id INTEGER NOT NULL,
	nombre VARCHAR(50) NOT NULL,
	id_municipio INTEGER,
	id_tipo_clase VARCHAR(3) REFERENCES tipo_clase, 
	id_departamento INTEGER REFERENCES departamento,
	FOREIGN KEY (id_municipio, id_departamento) REFERENCES municipio (id, id_departamento),
	PRIMARY KEY (id, id_municipio, id_departamento)
);

CREATE TABLE contexto (
		id INTEGER PRIMARY KEY,
		nombre CHAR(25) NOT NULL,
		fecha_creacion	DATE NOT NULL,
		fecha_deshabilitacion	DATE CHECK (fecha_deshabilitacion=NULL OR fecha_deshabilitacion>fecha_creacion)
);

CREATE TABLE filiacion (
		id INTEGER PRIMARY KEY,
		nombre CHAR(25) NOT NULL
);

CREATE TABLE frontera (
		id INTEGER PRIMARY KEY,
		nombre CHAR(25) NOT NULL
);


CREATE TABLE fuente_directa (
		id INTEGER PRIMARY KEY,
		nombre CHAR(150) NOT NULL
);

CREATE TABLE funcionario (
		id INTEGER PRIMARY KEY,
		anotacion CHAR(50),
		nombre CHAR(50) NOT NULL
);


CREATE TABLE opcion (
		id_opcion INTEGER PRIMARY KEY,
		descripcion CHAR(50) NOT NULL
);

CREATE TABLE organizacion (
		id INTEGER PRIMARY KEY,
		nombre CHAR(25) NOT NULL
);

CREATE TABLE parametros_reporte_consolidado (
		no_columuna INTEGER PRIMARY KEY,
		rotulo VARCHAR(25) NOT NULL,
		tipo_violencia VARCHAR(25) NOT NULL,
		clasificacion VARCHAR(25) NOT NULL
);

CREATE TABLE prensa (
		id INTEGER PRIMARY KEY,
		nombre CHAR(40) NOT NULL,
		tipo_fuente VARCHAR(25) NOT NULL
);

CREATE TABLE presuntos_responsables (
		id INTEGER PRIMARY KEY,
		nombre CHAR(50) NOT NULL,
		polo	CHAR(50) NOT NULL,
		fecha_creacion DATE NOT NULL,
		fecha_deshabilitacion	DATE CHECK (fecha_deshabilitacion=NULL OR fecha_deshabilitacion>fecha_creacion)
);


CREATE TABLE rango_edad ( -- podria ser edad inicial, edad final
		id INTEGER PRIMARY KEY,
		nombre CHAR(20) NOT NULL,
		rango CHAR(20) NOT NULL
);

CREATE TABLE region (
		id INTEGER PRIMARY KEY,
		nombre CHAR(50) NOT NULL
); 


CREATE TABLE resultado_agresion (
		id INTEGER PRIMARY KEY,
		nombre CHAR(50) NOT NULL
); 


CREATE TABLE rol (
		id_rol INTEGER PRIMARY KEY,
		nombre VARCHAR(20) NOT NULL
); 


CREATE TABLE sector_social (
		id INTEGER PRIMARY KEY,
		nombre VARCHAR(25) NOT NULL
); 

CREATE TABLE ubicacion (
	id INTEGER PRIMARY KEY,
	lugar VARCHAR(60),
	sitio VARCHAR(60),
	id_clase INTEGER,
	id_municipio INTEGER,
	id_departamento INTEGER references departamento,
	tipo CHAR(1),
	FOREIGN KEY (id_municipio, id_departamento) REFERENCES
		municipio (id, id_departamento),
	FOREIGN KEY (id_clase, id_municipio, id_departamento) REFERENCES
		clase (id, id_municipio, id_departamento)
); 

CREATE TABLE usuario (
	id_usuario VARCHAR(15) PRIMARY KEY,
	password VARCHAR(25) NOT NULL,
	nombre VARCHAR(25),
	descripcion VARCHAR(50),
	id_rol INTEGER REFERENCES rol,
	dias_edicion_caso INTEGER 
);

CREATE TABLE vinculo_estado (
	id INTEGER PRIMARY KEY,
	nombre VARCHAR(25) NOT NULL
);

CREATE TABLE profesion (
	id INTEGER PRIMARY KEY,
	nombre VARCHAR(25) NOT NULL
);

-- Victima depende de caso.  Podría hacerse una tabla persona relacionada
-- con esta.
CREATE TABLE victima (
	id VARCHAR(15) PRIMARY KEY,
	nombre CHAR(100) NOT NULL,
	edad INTEGER CHECK (edad=NULL OR (edad>='0' AND edad<='130')), 
	hijos INTEGER CHECK (hijos=NULL OR (hijos>='0' AND hijos<='100')),
	sexo	CHAR(1) CHECK (sexo=NULL OR sexo='F' OR sexo='M'),
	id_profesion INTEGER REFERENCES profesion,
	id_rango_edad	INTEGER REFERENCES rango_edad,
	id_filiacion	INTEGER	REFERENCES filiacion,
	id_sector_social	INTEGER	REFERENCES sector_social,
	id_organizacion	INTEGER REFERENCES organizacion,
	id_vinculo_estado INTEGER REFERENCES vinculo_estado,
	id_caso	INTEGER REFERENCES caso,
	id_organizacion_armada INTEGER REFERENCES presuntos_responsables
);

CREATE TABLE combatiente (
	id VARCHAR(15) PRIMARY KEY,
	nombre CHAR(100) NOT NULL,
	alias CHAR(100),
	edad INTEGER CHECK (edad=NULL OR edad>=0), 
	sexo	CHAR(1)  CHECK (sexo=NULL OR sexo='M' OR sexo='F'), 
	id_resultado_agresion INTEGER REFERENCES resultado_agresion,
	id_profesion INTEGER REFERENCES profesion,
	id_rango_edad	INTEGER REFERENCES rango_edad,
	id_filiacion	INTEGER	REFERENCES filiacion,
	id_sector_social	INTEGER	REFERENCES sector_social,
	id_organizacion	INTEGER REFERENCES organizacion,
	id_vinculo_estado INTEGER REFERENCES vinculo_estado,
	id_caso	INTEGER REFERENCES caso,
	id_organizacion_armada INTEGER REFERENCES presuntos_responsables
);

CREATE TABLE victima_colectiva (
	id INTEGER PRIMARY KEY,
	anotacion VARCHAR(100),
	nombre CHAR(150),
	personas_aprox INTEGER,
	id_organizacion_armada INTEGER REFERENCES presuntos_responsables
);

CREATE TABLE antecedente_caso (
	id_antecedente INTEGER REFERENCES antecedente,
	id_caso INTEGER REFERENCES caso,
	PRIMARY KEY(id_antecedente, id_caso)
);

CREATE TABLE antecedente_combatiente (
	id_antecedente INTEGER REFERENCES antecedente,
	id_combatiente INTEGER REFERENCES combatiente,
	PRIMARY KEY(id_antecedente, id_combatiente)
);

CREATE TABLE antecedente_comunidad (
	id_antecedente INTEGER REFERENCES antecedente,
	id_v_colectiva INTEGER REFERENCES victima_colectiva,
	PRIMARY KEY(id_antecedente, id_v_colectiva)
);

CREATE TABLE caso_actual (
	id_caso INTEGER PRIMARY KEY REFERENCES caso
);


CREATE TABLE caso_contexto (
	id_caso INTEGER REFERENCES caso,
	id_contexto INTEGER REFERENCES contexto,
	PRIMARY KEY(id_caso, id_contexto)
);

CREATE TABLE categoria_caso (
	id_caso INTEGER REFERENCES caso,
	id_tipo_violencia CHAR(1) REFERENCES tipo_violencia,
	id_supracategoria INTEGER, 
	id_categoria INTEGER REFERENCES categoria,
	PRIMARY KEY(id_caso, id_tipo_violencia, 
		id_supracategoria, id_categoria),
	FOREIGN KEY (id_supracategoria, id_tipo_violencia) 
		REFERENCES supracategoria (id, id_tipo_violencia)
);

CREATE TABLE categoria_comunidad (
	id_tipo_violencia CHAR(1) REFERENCES tipo_violencia,
	id_supracategoria INTEGER,
	id_categoria INTEGER REFERENCES categoria,
	id_v_colectiva INTEGER REFERENCES victima_colectiva,
	id_caso INTEGER REFERENCES caso,
	PRIMARY KEY(id_tipo_violencia, id_supracategoria, id_categoria,
		id_v_colectiva, id_caso),
	FOREIGN KEY (id_supracategoria, id_tipo_violencia) 
		REFERENCES supracategoria (id, id_tipo_violencia)
);

CREATE TABLE categoria_p_responsable_caso (
	id_tipo_violencia CHAR(1) REFERENCES tipo_violencia,
	id_supracategoria INTEGER,
	id_categoria INTEGER REFERENCES categoria,
	id INTEGER NOT NULL,
	id_caso INTEGER REFERENCES caso,
	id_p_responsable INTEGER REFERENCES presuntos_responsables,
	PRIMARY KEY(id_tipo_violencia, id_supracategoria, id_categoria,
		id, id_caso, id_p_responsable),
	FOREIGN KEY (id_supracategoria, id_tipo_violencia) 
		REFERENCES supracategoria (id, id_tipo_violencia)
);


CREATE TABLE categoria_personal (
	id_tipo_violencia CHAR(1) REFERENCES tipo_violencia,
	id_supracategoria INTEGER,
	id_categoria INTEGER REFERENCES categoria,
	id_victima INTEGER REFERENCES victima,
	PRIMARY KEY(id_tipo_violencia, id_supracategoria, id_categoria,
		id_victima),
	FOREIGN KEY (id_supracategoria, id_tipo_violencia) 
		REFERENCES supracategoria (id, id_tipo_violencia)
);


CREATE TABLE clase_caso (
	id_clase INTEGER, 
	id_municipio INTEGER,
	id_departamento INTEGER REFERENCES departamento,
	id_caso INTEGER REFERENCES caso,
	FOREIGN KEY (id_municipio, id_departamento) REFERENCES
		municipio (id, id_departamento),
	FOREIGN KEY (id_clase, id_municipio, id_departamento) REFERENCES
		clase (id, id_municipio, id_departamento),
	PRIMARY KEY(id_clase, id_municipio, id_departamento,
		id_caso)
);


CREATE TABLE departamento_caso (
	id_caso INTEGER REFERENCES caso,
	id_departamento INTEGER REFERENCES departamento,
	PRIMARY KEY(id_caso, id_departamento)
);

CREATE TABLE departamento_region (
	id_departamento INTEGER REFERENCES departamento,
	id_region INTEGER REFERENCES region,
	PRIMARY KEY(id_departamento, id_region)
);

CREATE TABLE descripcion_frontera (
	id INTEGER PRIMARY KEY,
	id_frontera INTEGER REFERENCES frontera,
	lugar VARCHAR(50) NOT NULL,
	sitio VARCHAR(50),
	tipo  CHAR(1)
);

-- Algunos campos ubicación, clasificacion y ubicacion_fisica son NULL (no pueden ser llave).
-- Puede mejorarse clasificacion, para que esté relacionada con clasificacion
-- del caso
CREATE TABLE escrito_caso (
	fecha DATE,
	ubicacion VARCHAR(100),  -- En interfaz descripción página
	clasificacion VARCHAR(100), -- Categoria que esta fuente clasifica 
	ubicacion_fisica VARCHAR(100),
	id_prensa INTEGER references prensa,
	id_caso	INTEGER REFERENCES caso,
	PRIMARY KEY(fecha, id_prensa,id_caso)
);

CREATE TABLE filiacion_comunidad (
	id_filiacion INTEGER REFERENCES filiacion,
	id_v_colectiva INTEGER REFERENCES victima_colectiva,
	PRIMARY KEY(id_filiacion, id_v_colectiva)
);

CREATE TABLE frontera_caso (
	id_frontera INTEGER REFERENCES frontera,
	id_caso INTEGER REFERENCES caso,
	PRIMARY KEY(id_frontera, id_caso)
);

-- Tambien hay fuentes indirectas (pero no frecuentes).
CREATE TABLE fuente_directa_caso (
	id_caso INTEGER REFERENCES caso,
	id_fuente_directa INTEGER REFERENCES fuente_directa,
	anotacion VARCHAR(200),
	fecha DATE,
	ubicacion_fisica VARCHAR(100),
	tipo_fuente VARCHAR(25),
	PRIMARY KEY(id_caso, id_fuente_directa, fecha)
);

CREATE TABLE funcionario_caso (
	id_funcionario INTEGER REFERENCES funcionario,
	id_caso INTEGER REFERENCES caso,
	fecha_inicio DATE,
	PRIMARY KEY(id_funcionario, id_caso)
);

CREATE TABLE municipio_caso (
	id_municipio INTEGER,
	id_departamento INTEGER REFERENCES departamento,
	id_caso INTEGER REFERENCES caso,
	FOREIGN KEY (id_municipio, id_departamento) REFERENCES
		municipio (id, id_departamento),
	PRIMARY KEY(id_municipio, id_departamento, id_caso)
);


CREATE TABLE opcion_rol (
	id_opcion INTEGER REFERENCES opcion,
	id_rol INTEGER REFERENCES rol,
	PRIMARY KEY(id_opcion, id_rol)
);

CREATE TABLE organizacion_comunidad (
	id_organizacion INTEGER REFERENCES organizacion,
	id_v_colectiva INTEGER REFERENCES victima_colectiva,
	PRIMARY KEY(id_organizacion, id_v_colectiva)
);

-- No falta caso en esta ?
CREATE TABLE p_responsable_agrede_combatiente (
	id_p_responsable INTEGER REFERENCES presuntos_responsables,
	id_combatiente INTEGER REFERENCES combatiente,
	PRIMARY KEY(id_p_responsable, id_combatiente)
);

CREATE TABLE p_responsable_agrede_comunidad (
	id_p_responsable INTEGER REFERENCES presuntos_responsables,
	id_v_colectiva INTEGER REFERENCES victima_colectiva,
	id_caso INTEGER REFERENCES caso,
	PRIMARY KEY(id_p_responsable, id_v_colectiva, id_caso)
);

CREATE TABLE p_responsable_agrede_persona (
	id_p_responsable INTEGER REFERENCES presuntos_responsables,
	id_victima INTEGER REFERENCES victima,
	PRIMARY KEY(id_p_responsable, id_victima)
);

CREATE TABLE presuntos_responsables_caso (
	id_caso INTEGER REFERENCES caso,
	id_p_responsable INTEGER REFERENCES presuntos_responsables,
	tipo	INTEGER	NOT NULL,
	bloque	VARCHAR(50),
	frente	VARCHAR(50),
	brigada	VARCHAR(50),
	batallon VARCHAR(50),
	division VARCHAR(50),
	otro VARCHAR(50),
	id INTEGER NOT NULL,
	PRIMARY KEY (id_caso, id_p_responsable, id)
);

-- id_rango_edad en lugar de id_rango
CREATE TABLE rango_edad_comunidad (
	id_rango INTEGER REFERENCES rango_edad,
	id_v_colectiva INTEGER REFERENCES victima_colectiva,
	PRIMARY KEY(id_rango, id_v_colectiva)
);


CREATE TABLE region_caso (
	id_region INTEGER REFERENCES region,
	id_caso INTEGER REFERENCES caso,
	PRIMARY KEY(id_region, id_caso)
);


-- id_sector_social en lugar de id_sector
CREATE TABLE sector_social_comunidad (
	id_sector INTEGER REFERENCES sector_social,
	id_v_colectiva INTEGER REFERENCES victima_colectiva,
	PRIMARY KEY(id_sector, id_v_colectiva)
);

CREATE TABLE ubicacion_caso (
	id_ubicacion INTEGER REFERENCES ubicacion,
	id_caso INTEGER REFERENCES caso,
	PRIMARY KEY(id_ubicacion, id_caso)
);

