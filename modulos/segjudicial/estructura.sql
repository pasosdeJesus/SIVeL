
SET client_encoding = 'LATIN1';

-- Acciones judiciales

CREATE SEQUENCE tproceso_seq;

CREATE TABLE tproceso (
	id	INTEGER PRIMARY KEY DEFAULT (nextval('tproceso_seq')),
	nombre VARCHAR(50) NOT NULL,
	observaciones VARCHAR(200),
	fechacreacion	DATE NOT NULL DEFAULT '2001-01-01',
	fechadeshabilitacion	DATE CHECK (fechadeshabilitacion IS NULL OR fechadeshabilitacion>=fechacreacion)
);

CREATE SEQUENCE despacho_seq;

CREATE TABLE despacho (
	id 	INTEGER PRIMARY KEY DEFAULT (nextval('despacho_seq')),
	id_tproceso INTEGER NOT NULL REFERENCES tproceso,
	nombre VARCHAR(150) NOT NULL,
	observaciones VARCHAR(500),
	fechacreacion	DATE NOT NULL DEFAULT '2001-01-01',
	fechadeshabilitacion	DATE CHECK (fechadeshabilitacion IS NULL OR fechadeshabilitacion>=fechacreacion)
);

CREATE SEQUENCE etapa_seq;

CREATE TABLE etapa (
	id	INTEGER PRIMARY KEY DEFAULT (nextval('etapa_seq')),
	id_tproceso INTEGER NOT NULL REFERENCES tproceso,
	nombre VARCHAR(50) NOT NULL,
	observaciones VARCHAR(200),
	fechacreacion	DATE NOT NULL DEFAULT '2001-01-01',
	fechadeshabilitacion	DATE CHECK (fechadeshabilitacion IS NULL OR fechadeshabilitacion>=fechacreacion)
);
	
CREATE SEQUENCE proceso_seq;

CREATE TABLE proceso (
	id 	INTEGER PRIMARY KEY DEFAULT (nextval('proceso_seq')),
	id_caso	INTEGER REFERENCES caso NOT NULL,
	id_tproceso INTEGER NOT NULL REFERENCES tproceso,
	id_etapa INTEGER NOT NULL REFERENCES etapa,
	proximafecha DATE,
	demandante	VARCHAR(100),
	demandado VARCHAR(100),
	poderdante VARCHAR(100),
	telefono VARCHAR(50),
	observaciones VARCHAR(500)
);

CREATE SEQUENCE taccion_seq;

CREATE TABLE taccion (
	id	INTEGER PRIMARY KEY DEFAULT (nextval('taccion_seq')),
	nombre 	VARCHAR(50) NOT NULL,
	observaciones VARCHAR(200),
	fechacreacion	DATE NOT NULL DEFAULT '2001-01-01',
	fechadeshabilitacion	DATE CHECK (fechadeshabilitacion IS NULL OR fechadeshabilitacion>=fechacreacion)
);

CREATE SEQUENCE accion_seq;

-- Inspiración inicial en base de Reiniciar más sugerencias de Claudia
CREATE TABLE accion (
	id      INTEGER PRIMARY KEY DEFAULT (nextval('accion_seq')),
	id_proceso INTEGER NOT NULL REFERENCES proceso,
	id_taccion INTEGER REFERENCES taccion NOT NULL,
	id_despacho INTEGER REFERENCES despacho NOT NULL,
	fecha DATE NOT NULL,
	numeroradicado VARCHAR(50),
	observacionesaccion	VARCHAR(4000),
	respondido	BOOLEAN
);
