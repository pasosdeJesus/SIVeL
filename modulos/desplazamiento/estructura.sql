
SET client_encoding = 'UTF8'; 

-- Desplazamiento

CREATE SEQUENCE clasifdesp_seq;
CREATE TABLE clasifdesp (
	id	INTEGER PRIMARY KEY DEFAULT (nextval('clasifdesp_seq')),
	id_tproceso INTEGER NOT NULL REFERENCES tproceso,
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	observaciones VARCHAR(200),
	fechacreacion	DATE NOT NULL DEFAULT '2013-05-24',
	fechadeshabilitacion	DATE CHECK (fechadeshabilitacion IS NULL OR fechadeshabilitacion>=fechacreacion)
);

CREATE SEQUENCE tipodesp_seq;
CREATE TABLE tipodesp (
	id	INTEGER PRIMARY KEY DEFAULT (nextval('tipodesp_seq')),
	id_tproceso INTEGER NOT NULL REFERENCES tproceso,
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	observaciones VARCHAR(200),
	fechacreacion	DATE NOT NULL DEFAULT '2013-05-24',
	fechadeshabilitacion	DATE CHECK (fechadeshabilitacion IS NULL OR fechadeshabilitacion>=fechacreacion)
);

CREATE SEQUENCE causadesp_seq;
CREATE TABLE causadesp (
	id	INTEGER PRIMARY KEY DEFAULT (nextval('causadesp_seq')),
	id_tproceso INTEGER NOT NULL REFERENCES tproceso,
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	observaciones VARCHAR(200),
	fechacreacion	DATE NOT NULL DEFAULT '2013-05-24',
	fechadeshabilitacion	DATE CHECK (fechadeshabilitacion IS NULL OR fechadeshabilitacion>=fechacreacion)
);

CREATE SEQUENCE declaroante_seq;
CREATE TABLE declaroante (
	id	INTEGER PRIMARY KEY DEFAULT (nextval('declaroante_seq')),
	id_tproceso INTEGER NOT NULL REFERENCES tproceso,
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	observaciones VARCHAR(200),
	fechacreacion	DATE NOT NULL DEFAULT '2013-05-24',
	fechadeshabilitacion	DATE CHECK (fechadeshabilitacion IS NULL OR fechadeshabilitacion>=fechacreacion)
);

CREATE SEQUENCE inclusion_seq;
CREATE TABLE inclusion (
	id	INTEGER PRIMARY KEY DEFAULT (nextval('inclusion_seq')),
	id_tproceso INTEGER NOT NULL REFERENCES tproceso,
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	observaciones VARCHAR(200),
	fechacreacion	DATE NOT NULL DEFAULT '2013-05-24',
	fechadeshabilitacion	DATE CHECK (fechadeshabilitacion IS NULL OR fechadeshabilitacion>=fechacreacion)
);

CREATE SEQUENCE acreditacion_seq;
CREATE TABLE acreditacion (
	id	INTEGER PRIMARY KEY DEFAULT (nextval('acreditacion_seq')),
	id_tproceso INTEGER NOT NULL REFERENCES tproceso,
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	observaciones VARCHAR(200),
	fechacreacion	DATE NOT NULL DEFAULT '2013-05-24',
	fechadeshabilitacion	DATE CHECK (fechadeshabilitacion IS NULL OR fechadeshabilitacion>=fechacreacion)
);

CREATE SEQUENCE modalidadtierra_seq;
CREATE TABLE modalidadtierra (
	id	INTEGER PRIMARY KEY DEFAULT (nextval('modalidadtierra_seq')),
	id_tproceso INTEGER NOT NULL REFERENCES tproceso,
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	observaciones VARCHAR(200),
	fechacreacion	DATE NOT NULL DEFAULT '2013-05-24',
	fechadeshabilitacion	DATE CHECK (fechadeshabilitacion IS NULL OR fechadeshabilitacion>=fechacreacion)
);

CREATE SEQUENCE tdesplazamiento_seq;
CREATE TABLE desplazamiento (
	id	INTEGER PRIMARY KEY DEFAULT (nextval('tdesplazamiento_seq')),
    id_caso INTEGER,
    fecha_expulsion DATE NOT NULL,
	expulsion INTEGER NOT NULL REFERENCES ubicacion(id),
    fecha_llegada DATE NOT NULL,
	llegada INTEGER NOT NULL REFERENCES ubicacion(id),
    id_clasifdesp INTEGER NOT NULL REFERENCES clasifdesp,
    id_tipodesp INTEGER NOT NULL REFERENCES tipodesp,
    id_presponsable INTEGER NOT NULL REFERENCES presponsable,
    id_causadesp INTEGER NOT NULL REFERENCES causadesp,
    descripcion VARCHAR(5000), 
    otrosdatos VARCHAR(1000), 
    declaro CHAR CHECK (declaro = 'S' OR declaro = 'N' OR declaro = 'R'),
    hechosdeclarados VARCHAR(5000),
    fechadeclaracion DATE,
    departamentodecl INTEGER REFERENCES departamento,
    municipiodecl INTEGER REFERENCES municipio,
    id_declaroante INTEGER REFERENCES declaroante,
    id_inclusion  INTEGER REFERENCES inclusion,
    id_acreditacion INTEGER REFERENCES acreditacion,
    retornado   BOOL,
    reubicado   BOOL,
    connacionalretorno BOOL,
    acompestado BOOL,
    connacionaldeportado BOOL,
    oficioantes VARCHAR(5000),
    id_modalidadtierra INTEGER REFERENCES modalidadtierra,
    materialesperdidos VARCHAR(5000),
    inmaterialesperdidos VARCHAR(5000),
    protegiorupta BOOL,
    documentostierra VARCHAR(5000),
);


