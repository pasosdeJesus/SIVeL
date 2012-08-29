

CREATE SEQUENCE combatiente_seq;

CREATE TABLE combatiente (
	id INTEGER PRIMARY KEY DEFAULT(nextval('combatiente_seq')),
	nombre VARCHAR(100) NOT NULL,
	alias VARCHAR(100),
	edad INTEGER CHECK (edad IS NULL OR edad>=0), 
	sexo	CHAR(1)  NOT NULL CHECK (sexo='S' OR sexo='M' OR sexo='F'), 
	id_resagresion INTEGER NOT NULL REFERENCES resagresion,
	id_profesion INTEGER REFERENCES profesion,
	id_rango_edad	INTEGER REFERENCES rango_edad,
	id_filiacion	INTEGER	REFERENCES filiacion,
	id_sector_social	INTEGER	REFERENCES sector_social,
	id_organizacion	INTEGER REFERENCES organizacion,
	id_vinculo_estado INTEGER REFERENCES vinculo_estado,
	id_caso	INTEGER REFERENCES caso,
	id_organizacion_armada INTEGER REFERENCES presponsable
);


CREATE TABLE antecedente_combatiente (
	id_antecedente INTEGER REFERENCES antecedente,
	id_combatiente INTEGER REFERENCES combatiente,
	PRIMARY KEY(id_antecedente, id_combatiente)
);




