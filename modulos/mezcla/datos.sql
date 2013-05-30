
SET client_encoding = 'UTF8';
SELECT setval('etiqueta_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM etiqueta) AS s;
INSERT INTO etiqueta (nombre, observaciones)
        VALUES ('MEZCLA_CASOS', 'Caso tras mezclar dos');

INSERT INTO actualizacionbase (id, fecha, descripcion) 
	VALUES ('rep-1', '2011-02-23', 'Mezcla en menu');
INSERT INTO actualizacionbase (id, fecha, descripcion) 
	VALUES ('mez-em', '2012-11-24', 'Etiqueta mezcla');
