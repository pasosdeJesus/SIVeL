
CREATE TABLE homonimosim (
	id_persona1 INTEGER REFERENCES persona,
	id_persona2 INTEGER CHECK (id_persona2 > id_persona1) REFERENCES persona,

	PRIMARY KEY(id_persona1, id_persona2)
);

CREATE VIEW homonimia AS 
	(SELECT id_persona1, id_persona2 FROM homonimosim 
	UNION SELECT id_persona2, id_persona1 FROM homonimosim
	);
