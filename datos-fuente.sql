-- Datos para tablas básicas
-- Información sobre fuentes tomada del Banco de 
-- Datos de Derechos Humanos y Violencia Politica.
-- Dominio público. 2004. Sin garantías.

SET client_encoding = 'LATIN1';

-- ffrecuente


INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (0,'SIN INFORMACIÓN','Indirecta', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (1,'T - EL TIEMPO','Indirecta', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (2,'E - EL ESPECTADOR','Indirecta', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (3,'C - EL COLOMBIANO','Indirecta', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (4,'M - EL MUNDO','Indirecta', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (5,'LP - LA PATRIA','Indirecta', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (6,'LT - LA TARDE','Indirecta', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (7,'EP - EL PAIS','Indirecta', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (8,'H - EL HERALDO','Indirecta', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (9,'LL - LA LIBERTAD','Indirecta', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (10,'U - EL UNIVERSAL','Indirecta', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (11,'ME - EL MERIDIANO','Indirecta', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (12,'VL - VANGUARDIA LIBERAL','Indirecta', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (13,'VLB - VANGUARDIA LIBERAL BARRANCA','Indirecta', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (14,'O - LA OPINION','Indirecta', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (15,'EL - EL LIBERAL','Indirecta', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (16,'ND - EL NUEVO DIA','Indirecta', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (17,'VOZ - VOZ','Indirecta', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (18,'L7 - LLANO 7 DIAS','Indirecta', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (19,'LN - LA NACION','Indirecta', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (22,'FD - FUENTE DIRECTA','Directa', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (24,'CJ - CRONICA JUDICIAL','Indirecta', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (36,'NO - EL NUEVO ORIENTE','Indirecta', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (39,'DM - DIARIO DEL MAGDALENA','Indirecta', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (41,'B7 - BOYACA 7 DIAS','Indirecta', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (42,'DS - DIARIO DEL SUR','Indirecta', '2001-01-01');
INSERT INTO ffrecuente(id, nombre, tfuente, fechacreacion) VALUES (43,'ME - SU MERIDIANO DE SUCRE','Indirecta', '2001-01-01');

SELECT setval('ffrecuente_seq', max(id)) FROM ffrecuente;
