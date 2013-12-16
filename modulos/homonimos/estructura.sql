
CREATE TABLE homonimosim (
	id_persona1 INTEGER REFERENCES persona,
	id_persona2 INTEGER CHECK (id_persona2 > id_persona1) REFERENCES persona,

	PRIMARY KEY(id_persona1, id_persona2)
);

