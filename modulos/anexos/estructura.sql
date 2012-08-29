
SET client_encoding = 'LATIN1';

-- Anexos

CREATE SEQUENCE anexo_seq;

-- Inspirado en base de Reiniciar de Luis Alberto Clavijo.
-- Los archivos pueden guardarse en un directorio predeterminado
-- con nombres de la forma "id"_"id_caso"_"nombre", preferir formatos
-- estandarizados
CREATE TABLE anexo (
	id      INTEGER PRIMARY KEY DEFAULT (nextval('anexo_seq')),
	id_caso INTEGER REFERENCES caso NOT NULL,
	fecha   DATE NOT NULL,
	descripcion     VARCHAR(1500) NOT NULL,
	archivo VARCHAR(255) NOT NULL,
	id_ffrecuente INTEGER REFERENCES ffrecuente,
	fechaffrecuente DATE,
	id_fotra INTEGER REFERENCES fotra
);

