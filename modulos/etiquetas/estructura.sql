-- 

CREATE SEQUENCE etiqueta_seq;

CREATE TABLE etiqueta (
	id      INTEGER PRIMARY KEY DEFAULT (nextval('etiqueta_seq')),
	nombre VARCHAR(50) NOT NULL,
	observaciones VARCHAR(200),
	fechacreacion	DATE NOT NULL DEFAULT '2001-01-01',
	fechadeshabilitacion	DATE CHECK (fechadeshabilitacion IS NULL OR fechadeshabilitacion>=fechacreacion)
);

CREATE TABLE etiquetacaso (
	id_caso INTEGER REFERENCES caso NOT NULL,
	id_etiqueta       INTEGER REFERENCES etiqueta NOT NULL,
	id_funcionario INTEGER REFERENCES funcionario NOT NULL,
	fecha   DATE NOT NULL,
	observaciones VARCHAR(2500),
	PRIMARY KEY (id_caso, id_etiqueta,  id_funcionario, fecha)
);

