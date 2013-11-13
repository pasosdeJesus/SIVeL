CREATE SEQUENCE resagresion_seq;

CREATE TABLE resagresion (
	id INTEGER PRIMARY KEY DEFAULT(nextval('resagresion_seq')),
	nombre VARCHAR(500) COLLATE es_co_utf_8 NOT NULL,
	fechacreacion	DATE NOT NULL,
	fechadeshabilitacion DATE CHECK (
		fechadeshabilitacion IS NULL 
		OR fechadeshabilitacion>=fechacreacion
	)
);	

CREATE SEQUENCE combatiente_seq;

CREATE TABLE combatiente (
	id INTEGER PRIMARY KEY DEFAULT(nextval('combatiente_seq')),
	nombre VARCHAR(100) NOT NULL,
	alias VARCHAR(100),
	edad INTEGER CHECK (edad IS NULL OR edad>=0), 
	sexo	CHAR(1)  NOT NULL CHECK (sexo='S' OR sexo='M' OR sexo='F'), 
	id_resagresion INTEGER NOT NULL REFERENCES resagresion,
	id_profesion INTEGER REFERENCES profesion,
	id_rangoedad	INTEGER REFERENCES rangoedad,
	id_filiacion	INTEGER	REFERENCES filiacion,
	id_sectorsocial	INTEGER	REFERENCES sectorsocial,
	id_organizacion	INTEGER REFERENCES organizacion,
	id_vinculoestado INTEGER REFERENCES vinculoestado,
	id_caso	INTEGER REFERENCES caso,
	organizacionarmada INTEGER REFERENCES presponsable
);


CREATE TABLE antecedente_combatiente (
	id_antecedente INTEGER REFERENCES antecedente,
	id_combatiente INTEGER REFERENCES combatiente,
	PRIMARY KEY(id_antecedente, id_combatiente)
);




