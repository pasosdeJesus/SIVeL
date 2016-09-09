-- Datos para tablas básicas
-- Información sobre caso tomada del Banco de
-- Datos de Derechos Humanos y Violencia Politica.
-- Categorias de "Marco Conceptual" revista Noche y Niebla.
--    http://www.nocheyniebla.org
-- Dominio público. 2004. Sin garantías.

SET client_encoding = 'UTF8';

-- pconsolidado

INSERT INTO pconsolidado (id, rotulo, tipoviolencia, clasificacion, fechacreacion) VALUES (1, 'MUERTOS', 'DH', 'VIDA', '2001-01-01');
INSERT INTO pconsolidado (id, rotulo, tipoviolencia, clasificacion, fechacreacion) VALUES (2, 'MUERTOS', 'DIH', 'VIDA', '2001-01-01');
INSERT INTO pconsolidado (id, rotulo, tipoviolencia, clasificacion, fechacreacion) VALUES (3, 'MUERTOS', 'VP', 'VIDA', '2001-01-01');
INSERT INTO pconsolidado (id, rotulo, tipoviolencia, clasificacion, fechacreacion) VALUES (4, 'TORTURA', 'DH', 'INTEGRIDAD', '2001-01-01');
INSERT INTO pconsolidado (id, rotulo, tipoviolencia, clasificacion, fechacreacion) VALUES (5, 'HERIDOS', 'DH', 'INTEGRIDAD', '2001-01-01');
INSERT INTO pconsolidado (id, rotulo, tipoviolencia, clasificacion, fechacreacion) VALUES (6, 'ATENTADOS', 'DH', 'INTEGRIDAD', '2001-01-01');
INSERT INTO pconsolidado (id, rotulo, tipoviolencia, clasificacion, fechacreacion) VALUES (7, 'AMENAZAS', 'DH', 'INTEGRIDAD', '2001-01-01');
INSERT INTO pconsolidado (id, rotulo, tipoviolencia, clasificacion, fechacreacion) VALUES (8, 'VIOLENCIA SEXUAL', 'DH', 'INTEGRIDAD', '2001-01-01');
INSERT INTO pconsolidado (id, rotulo, tipoviolencia, clasificacion, fechacreacion) VALUES (9, 'TORTURA', 'DIH', 'INTEGRIDAD', '2001-01-01');
INSERT INTO pconsolidado (id, rotulo, tipoviolencia, clasificacion, fechacreacion) VALUES (10, 'HERIDOS', 'DIH', 'INTEGRIDAD', '2001-01-01');
INSERT INTO pconsolidado (id, rotulo, tipoviolencia, clasificacion, fechacreacion) VALUES (11, 'AMENAZAS', 'DIH', 'INTEGRIDAD', '2001-01-01');
INSERT INTO pconsolidado (id, rotulo, tipoviolencia, clasificacion, fechacreacion) VALUES (12, 'VIOLENCIA SEXUAL', 'DIH', 'INTEGRIDAD', '2001-01-01');
INSERT INTO pconsolidado (id, rotulo, tipoviolencia, clasificacion, fechacreacion) VALUES (13, 'TORTURA', 'VP', 'INTEGRIDAD', '2001-01-01');
INSERT INTO pconsolidado (id, rotulo, tipoviolencia, clasificacion, fechacreacion) VALUES (14, 'HERIDOS', 'VP', 'INTEGRIDAD', '2001-01-01');
INSERT INTO pconsolidado (id, rotulo, tipoviolencia, clasificacion, fechacreacion) VALUES (15, 'ATENTADOS', 'VP', 'INTEGRIDAD', '2001-01-01');
INSERT INTO pconsolidado (id, rotulo, tipoviolencia, clasificacion, fechacreacion) VALUES (16, 'AMENAZAS', 'VP', 'INTEGRIDAD', '2001-01-01');
INSERT INTO pconsolidado (id, rotulo, tipoviolencia, clasificacion, fechacreacion) VALUES (17, 'DESAPARICIÓN', 'DH', 'LIBERTAD', '2001-01-01');
INSERT INTO pconsolidado (id, rotulo, tipoviolencia, clasificacion, fechacreacion) VALUES (18, 'DETENCION ARBITRARIA', 'DH', 'LIBERTAD', '2001-01-01');
INSERT INTO pconsolidado (id, rotulo, tipoviolencia, clasificacion, fechacreacion) VALUES (19, 'DEPORTACIÓN', 'DH', 'LIBERTAD', '2001-01-01');
INSERT INTO pconsolidado (id, rotulo, tipoviolencia, clasificacion, fechacreacion) VALUES (20, 'RECLUTAMIENTO DE MENORES', 'DIH', 'LIBERTAD', '2001-01-01');
INSERT INTO pconsolidado (id, rotulo, tipoviolencia, clasificacion, fechacreacion) VALUES (21, 'TOMA DE REHENES', 'DIH', 'LIBERTAD', '2001-01-01');
INSERT INTO pconsolidado (id, rotulo, tipoviolencia, clasificacion, fechacreacion) VALUES (22, 'ESCUDO', 'DIH', 'LIBERTAD', '2001-01-01');
INSERT INTO pconsolidado (id, rotulo, tipoviolencia, clasificacion, fechacreacion) VALUES (23, 'RAPTO', 'VP', 'LIBERTAD', '2001-01-01');
INSERT INTO pconsolidado (id, rotulo, tipoviolencia, clasificacion, fechacreacion) VALUES (24, 'SECUESTRO', 'VP', 'LIBERTAD', '2001-01-01');
INSERT INTO pconsolidado (id, rotulo, tipoviolencia, clasificacion, fechacreacion) VALUES (25, 'COLECTIVO CONFINADO', 'DIH', 'INTEGRIDAD', '2001-01-01');


--  tviolencia

INSERT INTO tviolencia (id, nombre, nomcorto, fechacreacion, fechadeshabilitacion) VALUES ('A', 'VIOLACIONES A LOS DERECHOS HUMANOS', 'DH', '2000-07-24', NULL);
INSERT INTO tviolencia (id, nombre, nomcorto, fechacreacion, fechadeshabilitacion) VALUES ('B', 'VIOLENCIA POLÍTICO SOCIAL', 'VPS', '2000-07-24', NULL);
INSERT INTO tviolencia (id, nombre, nomcorto, fechacreacion, fechadeshabilitacion) VALUES ('C', 'ACCIONES BÉLICAS', 'BELICAS', '2000-07-24', NULL);
INSERT INTO tviolencia (id, nombre, nomcorto, fechacreacion, fechadeshabilitacion) VALUES ('D', 'INFRACCIONES AL DIH', 'DIH', '2000-07-24', NULL);

-- supracategoria

INSERT INTO supracategoria (id, nombre, fechacreacion, fechadeshabilitacion, id_tviolencia) VALUES (1, 'PERSECUCIÓN POLÍTICA', '2000-07-26', NULL, 'A');
INSERT INTO supracategoria (id, nombre, fechacreacion, fechadeshabilitacion, id_tviolencia) VALUES (2, 'ABUSO DE AUTORIDAD', '2000-07-26', NULL, 'A');
INSERT INTO supracategoria (id, nombre, fechacreacion, fechadeshabilitacion, id_tviolencia) VALUES (3, 'INTOLERANCIA SOCIAL', '2000-07-26', NULL, 'A');
INSERT INTO supracategoria (id, nombre, fechacreacion, fechadeshabilitacion, id_tviolencia) VALUES (2, 'INTOLERANCIA SOCIAL', '2000-07-26', NULL, 'B');
INSERT INTO supracategoria (id, nombre, fechacreacion, fechadeshabilitacion, id_tviolencia) VALUES (1, 'PERSECUCIÓN POLÍTICA', '2000-07-26', NULL, 'B');
INSERT INTO supracategoria (id, nombre, fechacreacion, fechadeshabilitacion, id_tviolencia) VALUES (1, 'BÉLICAS', '2000-07-26', NULL, 'C');
INSERT INTO supracategoria (id, nombre, fechacreacion, fechadeshabilitacion, id_tviolencia) VALUES (1, 'PERSONAS', '2000-07-26', NULL, 'D');
INSERT INTO supracategoria (id, nombre, fechacreacion, fechadeshabilitacion, id_tviolencia) VALUES (2, 'BIENES', '2000-07-26', NULL, 'D');
INSERT INTO supracategoria (id, nombre, fechacreacion, fechadeshabilitacion, id_tviolencia) VALUES (3, 'MÉTODOS', '2000-07-26', NULL, 'D');


--categoria


INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (701, '2000-08-09', NULL, 1, 'D', 2, NULL, 'I', 'HOMICIDIO INTENCIONAL PERSONA PROTEGIDA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (10, '2001-07-26', NULL, 1, 'A', 1, 701, 'I', 'EJECUCIÓN EXTRAJUDICIAL');

INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (101, '2001-05-23', NULL, 1, 'A', 19, NULL, 'I', 'DEPORTACIÓN');

INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (903, '2001-07-11', NULL, 1, 'D', NULL, NULL, 'C', 'COLECTIVO DESPLAZADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (102, '2001-07-11', NULL, 1, 'A', NULL, 903, 'C', 'COLECTIVO DESPLAZADO');

INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (906, '2008-10-21', NULL, 1, 'D', NULL, NULL, 'C', 'CONFINAMIENTO COMO REPRESALIA O CASTIGO COLECTIVO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (104, '2008-10-17', NULL, 1, 'A', 25, 906, 'C', 'CONFINAMIENTO COMO REPRESALIA O CASTIGO COLECTIVO');

INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (11, '2001-07-26', NULL, 1, 'A', 17, NULL, 'I', 'DESAPARICIÓN');

INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (72, '2000-08-09', NULL, 1, 'D', 9, NULL, 'I', 'TORTURA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (12, '2001-07-26', NULL, 1, 'A', 4, 72, 'I', 'TORTURA');

INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (702, '2000-08-09', NULL, 1, 'D', 10, NULL, 'I', 'HERIDO INTENCIONAL PERSONA PROTEGIDA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (13, '2000-08-09', NULL, 1, 'A', 5, 702, 'I', 'HERIDO');

INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (18, '2000-08-09', NULL, 1, 'A', NULL, 706, 'C', 'COLECTIVO AMENAZADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (14, '2001-08-09', NULL, 1, 'A', 18, NULL, 'I', 'DETENCIÓN ARBITRARIA');

INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (73, '2000-08-09', NULL, 1, 'D', 11, NULL, 'I', 'AMENAZA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (15, '2000-08-09', NULL, 1, 'A', 7, 73, 'I', 'AMENAZA');

INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (16, '2000-08-09', NULL, 1, 'A', 6, NULL, 'I', 'ATENTADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (17, '2002-07-16', '2002-07-16', 1, 'A', NULL, NULL, 'I', 'SECUESTRO');

INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (77, '2000-08-09', NULL, 1, 'D', 12, NULL, 'I', 'VIOLENCIA SEXUAL');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (19, '2000-08-09', NULL, 1, 'A', 8, 77, 'I', 'VIOLENCIA SEXUAL');


INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (771, '2008-10-20', NULL, 1, 'D', NULL, NULL, 'I', 'VIOLACIÓN');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (191, '2008-10-20', NULL, 1, 'A', NULL, 771, 'I', 'VIOLACIÓN');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (772, '2008-10-20', NULL, 1, 'D', 12, NULL, 'I', 'EMBARAZO FORZADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (192, '2008-10-20', NULL, 1, 'A', NULL, 772, 'I', 'EMBARAZO FORZADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (773, '2008-10-20', NULL, 1, 'D', 12, NULL, 'I', 'PROSTITUCIÓN FORZADA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (193, '2008-10-20', NULL, 1, 'A', NULL, 773, 'I', 'PROSTITUCIÓN FORZADA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (774, '2008-10-20', NULL, 1, 'D', 12, NULL, 'I', 'ESTERILIZACIÓN FORZADA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (194, '2008-10-20', NULL, 1, 'A', NULL, 774, 'I', 'ESTERELIZACIÓN FORZADA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (775, '2008-10-20', NULL, 1, 'D', 12, NULL, 'I', 'ESCLAVITUD SEXUAL');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (195, '2008-10-20', NULL, 1, 'A', NULL, 775, 'I', 'ESCLAVITUD SEXUAL');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (776, '2008-10-20', NULL, 1, 'D', NULL, NULL, 'I', 'ABUSO SEXUAL');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (196, '2008-10-20', NULL, 1, 'A', NULL, 776, 'I', 'ABUSO SEXUAL');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (777, '2010-04-17', NULL, 1, 'D', 12, NULL, 'I', 'ABORTO FORZADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (197, '2010-04-17', NULL, 1, 'A', NULL, 777, 'I', 'ABORTO FORZADO');

INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (20, '2001-07-26', NULL, 2, 'A', 1, NULL, 'I', 'EJECUCIÓN EXTRAJUDICIAL');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (21, '2001-08-09', NULL, 2, 'A', 17, NULL, 'I', 'DESAPARICIÓN');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (22, '2000-08-09', NULL, 2, 'A', 4, NULL, 'I', 'TORTURA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (23, '2000-08-09', NULL, 2, 'A', 5, NULL, 'I', 'HERIDO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (24, '2001-08-09', NULL, 2, 'A', 18, NULL, 'I', 'DETENCIÓN ARBITRARIA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (25, '2000-08-09', NULL, 2, 'A', 7, NULL, 'I', 'AMENAZA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (26, '2000-08-09', NULL, 2, 'A', 6, NULL, 'I', 'ATENTADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (28, '2000-08-09', NULL, 2, 'A', NULL, NULL, 'C', 'COLECTIVO AMENAZADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (29, '2000-08-09', NULL, 2, 'A', 8, NULL, 'I', 'VIOLENCIA SEXUAL');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (291, '2008-10-20', NULL, 2, 'A', NULL, NULL, 'I', 'VIOLACIÓN');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (292, '2008-10-20', NULL, 2, 'A', NULL, NULL, 'I', 'EMBARAZO FORZADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (293, '2008-10-20', NULL, 2, 'A', NULL, NULL, 'I', 'PROSTITUCIÓN FORZADA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (294, '2008-10-20', NULL, 2, 'A', NULL, NULL, 'I', 'ESTERELIZACIÓN FORZADA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (295, '2008-10-20', NULL, 2, 'A', NULL, NULL, 'I', 'ESCLAVITUD SEXUAL');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (296, '2008-10-20', NULL, 2, 'A', NULL, NULL, 'I', 'ABUSO SEXUAL');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (297, '2010-04-17', NULL, 2, 'A', NULL, NULL, 'I', 'ABORTO FORZADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (30, '2001-08-09', NULL, 3, 'A', 1, NULL, 'I', 'EJECUCIÓN EXTRAJUDICIAL');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (301, '2001-05-23', NULL, 3, 'A', 18, NULL, 'I', 'DETENCIÓN ARBITRARIA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (302, '2001-05-23', NULL, 3, 'A', 17, NULL, 'I', 'DESAPARICIÓN');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (31, '2002-07-16', '2002-07-16', 3, 'A', NULL, NULL, 'I', 'DESAPARICION POR INTOLERANCIA SOCIAL');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (33, '2000-08-09', NULL, 3, 'A', 5, NULL, 'I', 'HERIDO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (35, '2000-08-09', NULL, 3, 'A', 7, NULL, 'I', 'AMENAZA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (36, '2000-08-09', NULL, 3, 'A', 4, NULL, 'I', 'TORTURA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (37, '2000-08-09', NULL, 3, 'A', 6, NULL, 'I', 'ATENTADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (38, '2000-08-09', NULL, 3, 'A', NULL, NULL, 'C', 'COLECTIVO AMENAZADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (39, '2000-08-09', NULL, 3, 'A', 8, NULL, 'I', 'VIOLENCIA SEXUAL');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (391, '2008-10-20', NULL, 3, 'A', NULL, NULL, 'I', 'VIOLACIÓN');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (392, '2008-10-20', NULL, 3, 'A', NULL, NULL, 'I', ' EMBARAZO FORZADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (393, '2008-10-20', NULL, 3, 'A', NULL, NULL, 'I', 'PROSTITUCIÓN FORZADA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (394, '2008-10-20', NULL, 3, 'A', NULL, NULL, 'I', 'ESTERELIZACIÓN FORZADA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (395, '2008-10-20', NULL, 3, 'A', NULL, NULL, 'I', 'ESCLAVITUD SEXUAL');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (396, '2008-10-20', NULL, 3, 'A', NULL, NULL, 'I', 'ABUSO SEXUAL');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (397, '2010-04-17', NULL, 3, 'A', NULL, NULL, 'I', 'ABORTO FORZADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (40, '2000-08-09', NULL, 1, 'B', 3, NULL, 'I', 'ASESINATO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (401, '2001-05-23', NULL, 1, 'B', NULL, NULL, 'C', 'COLECTIVO DESPLAZADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (41, '2000-08-09', NULL, 1, 'B', 24, NULL, 'I', 'SECUESTRO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (420, '2010-04-17', NULL, 1, 'B', NULL, NULL, 'I', 'VIOLENCIA SEXUAL');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (421, '2010-04-17', NULL, 1, 'B', NULL, NULL, 'I', 'VIOLACIÓN');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (422, '2010-04-17', NULL, 1, 'B', NULL, NULL, 'I', 'EMBARAZO FORZADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (423, '2010-04-17', NULL, 1, 'B', NULL, NULL, 'I', 'PROSTITUCIÓN FORZADA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (424, '2010-04-17', NULL, 1, 'B', NULL, NULL, 'I', 'ESTERILIZACIÓN FORZADA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (425, '2010-04-17', NULL, 1, 'B', NULL, NULL, 'I', 'ESCLAVITUD SEXUAL');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (426, '2010-04-17', NULL, 1, 'B', NULL, NULL, 'I', 'ABUSO SEXUAL');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (427, '2010-04-17', NULL, 1, 'B', NULL, NULL, 'I', 'ABORTO FORZADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (43, '2000-07-26', NULL, 1, 'B', 14, NULL, 'I', 'HERIDO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (45, '2000-08-09', NULL, 1, 'B', 16, NULL, 'I', 'AMENAZA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (46, '2000-08-09', NULL, 1, 'B', 15, NULL, 'I', 'ATENTADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (47, '2000-08-09', NULL, 1, 'B', 13, NULL, 'I', 'TORTURA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (48, '2001-08-09', NULL, 1, 'B', 23, NULL, 'I', 'RAPTO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (49, '2000-08-09', NULL, 1, 'B', NULL, NULL, 'C', 'COLECTIVO AMENAZADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (50, '2000-08-09', NULL, 2, 'B', 3, NULL, 'I', 'ASESINATO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (501, '2001-05-23', NULL, 2, 'B', NULL, NULL, 'C', 'COLECTIVO DESPLAZADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (52, '2002-07-16', '2002-07-16', 2, 'B', NULL, NULL, 'I', 'HERIDOS');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (520, '2011-07-07', NULL, 2, 'B', 12, NULL, 'I', 'VIOLENCIA SEXUAL');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (521, '2011-07-07', NULL, 2, 'B', NULL, NULL, 'I', 'VIOLACIÓN');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (522, '2011-07-07', NULL, 2, 'B', 12, NULL, 'I', 'EMBARAZO FORZADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (523, '2011-07-07', NULL, 2, 'B', 12, NULL, 'I', 'PROSTITUCIÓN FORZADA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (524, '2011-07-07', NULL, 2, 'B', 12, NULL, 'I', 'ESTERILIZACIÓN FORZADA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (525, '2011-07-07', NULL, 2, 'B', 12, NULL, 'I', 'ESCLAVITUD SEXUAL');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (526, '2011-07-07', NULL, 2, 'B', 12, NULL, 'I', 'ABUSO SEXUAL');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (527, '2011-07-07', NULL, 2, 'B', 12, NULL, 'I', 'ABORTO FORZADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (53, '2000-08-09', NULL, 2, 'B', 14, NULL, 'I', 'HERIDO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (55, '2000-08-09', NULL, 2, 'B', 16, NULL, 'I', 'AMENAZA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (56, '2000-08-09', NULL, 2, 'B', 13, NULL, 'I', 'TORTURA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (57, '2000-08-09', NULL, 2, 'B', 15, NULL, 'I', 'ATENTADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (58, '2001-08-09', NULL, 2, 'B', 23, NULL, 'I', 'RAPTO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (59, '2000-08-09', NULL, 2, 'B', NULL, NULL, 'C', 'COLECTIVO AMENAZADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (60, '2002-07-16', '2002-07-16', 1, 'C', NULL, NULL, 'I', 'HOSTIGAMIENTO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (61, '2002-07-16', '2002-07-16', 1, 'C', NULL, NULL, 'O', 'ASALTO - TOMA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (62, '2000-07-26', NULL, 1, 'C', NULL, NULL, 'O', 'COMBATE');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (63, '2000-08-09', NULL, 1, 'C', NULL, NULL, 'O', 'EMBOSCADA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (64, '2001-08-09', NULL, 1, 'C', NULL, NULL, 'O', 'CAMPO MINADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (65, '2001-08-09', NULL, 1, 'C', NULL, NULL, 'O', 'BOMBARDEOS / AMETRALLAMIENTO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (66, '2001-08-09', NULL, 1, 'C', NULL, NULL, 'O', 'BLOQUEO DE VíAS');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (67, '2000-08-09', NULL, 1, 'C', NULL, NULL, 'O', 'ATAQUE A OBJETIVOS MILITARES');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (68, '2001-08-09', NULL, 1, 'C', NULL, NULL, 'O', 'INCURSIÓN');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (69, '2000-08-09', NULL, 1, 'C', NULL, NULL, 'O', 'SABOTAJE');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (70, '2002-07-16', '2002-07-16', 1, 'D', NULL, NULL, 'I', 'HOMICIDIO FC');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (703, '2001-08-09', NULL, 1, 'D', 2, NULL, 'I', 'CIVIL MUERTO EN ACCIONES BÉLICAS');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (704, '2001-08-09', NULL, 1, 'D', 10, NULL, 'I', 'CIVIL HERIDO EN ACCIONES BÉLICAS');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (706, '2000-08-09', NULL, 1, 'D', NULL, NULL, 'C', 'COLECTIVO AMENAZADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (707, '2001-07-11', NULL, 3, 'D', NULL, NULL, 'O', 'INFRACCIÓN CONTRA MISIÓN MEDICA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (708, '2001-07-11', NULL, 3, 'D', NULL, NULL, 'O', 'INFRACCIÓN CONTRA MISIÓN RELIGIOSA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (709, '2001-07-11', NULL, 3, 'D', NULL, NULL, 'O', 'INFRACCIÓN CONTRA MISIÓN HUMANITARIA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (71, '2002-07-16', '2002-07-16', 1, 'D', NULL, NULL, 'I', 'HERIDO FC');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (74, '2001-08-09', NULL, 1, 'D', 21, NULL, 'I', 'TOMA DE REHÉN');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (75, '2000-08-09', NULL, 1, 'D', 20, NULL, 'I', 'RECLUTAMIENTO DE MENORES');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (76, '2002-07-16', '2002-07-16', 1, 'D', NULL, NULL, 'I', 'DESPLAZAMIENTO FORZADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (78, '2000-08-09', NULL, 1, 'D', 22, NULL, 'I', 'ESCUDO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (79, '2002-07-23', '2002-07-23', 1, 'D', NULL, NULL, 'I', 'DESAPARICION');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (80, '2000-08-09', NULL, 2, 'D', NULL, NULL, 'O', 'BIENES CIVILES');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (801, '2001-07-26', NULL, 2, 'D', NULL, NULL, 'O', 'ATAQUE A OBRAS / INSTALACIONES QUE CONT. FUERZAS PELIGROSAS');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (81, '2002-07-16', '2002-07-16', 2, 'D', NULL, NULL, 'O', 'OLEODUCTOS');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (82, '2002-07-16', '2002-07-16', 2, 'D', NULL, NULL, 'O', 'INFRAESTRUCTURA ELECTRICA Y COMUNICACIONES');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (83, '2002-07-16', '2002-07-16', 2, 'D', NULL, NULL, 'O', 'INFRAESTRUCTURA VIAL');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (84, '2001-08-09', NULL, 2, 'D', NULL, NULL, 'O', 'INFRACCIÓN CONTRA EL MEDIO AMBIENTE');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (85, '2001-08-09', NULL, 2, 'D', NULL, NULL, 'O', 'INFRACCIÓN CONTRA BIENES CULTURALES Y RELIGIOSOS');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (86, '2001-08-09', NULL, 2, 'D', NULL, NULL, 'O', 'BIENES INDISPENSABLES PARA LA SUPERV. DE LA POB.');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (87, '2001-08-09', NULL, 1, 'D', 2, NULL, 'I', 'MUERTO EN ATAQUE A BIENES CIVILES');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (88, '2001-08-09', NULL, 1, 'D', 10, NULL, 'I', 'HERIDO EN ATAQUE A BIENES CIVILES');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (89, '2001-08-09', NULL, 2, 'D', NULL, NULL, 'O', 'INFRACCIÓN CONTRA LA ESTRUCTURA VIAL');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (90, '2000-08-09', NULL, 3, 'D', NULL, NULL, 'O', 'AMETRALLAMIENTO Y/O BOMBARDEO INDISCRIMINADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (901, '2000-08-09', '2001-07-11', 3, 'D', NULL, NULL, 'I', 'COMUNIDAD DESPLAZADA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (902, '2001-07-11', NULL, 3, 'D', NULL, NULL, 'O', 'DESPLAZAMIENTO FORZADO COLECTIVO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (903, '2001-07-11', NULL, 1, 'D', NULL, NULL, 'C', 'COLECTIVO DESPLAZADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (904, '2001-07-11', NULL, 1, 'D', NULL, NULL, 'C', 'ESCUDO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (91, '2000-08-09', NULL, 3, 'D', NULL, NULL, 'O', 'PERFIDIA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (910, '2006-07-12', NULL, 1, 'C', NULL, NULL, 'O', 'ENFRENTAMIENTO - INTERNO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (92, '2001-08-09', NULL, 3, 'D', NULL, NULL, 'O', 'ARMA PROHIBIDA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (93, '2001-08-09', NULL, 3, 'D', NULL, NULL, 'O', 'MINA ILÍCITA / ARMA TRAMPA');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (94, '2002-07-23', '2002-07-23', 3, 'D', NULL, NULL, 'O', 'MATERIAL BÉLICO ABANDONADO');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (95, '2000-08-09', NULL, 3, 'D', NULL, NULL, 'O', 'PILLAJE');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (97, '2001-08-09', NULL, 1, 'D', 2, NULL, 'I', 'MUERTO POR MÉTODOS Y MEDIOS ILÍCITOS');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (98, '2001-08-09', NULL, 1, 'D', 10, NULL, 'I', 'HERIDO POR MÉTODOS Y MEDIOS ILÍCITOS');
INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (99, '2000-08-09', '2001-05-23', 3, 'D', NULL, NULL, 'I', 'DESPLAZAMIENTO FORZADO');

--UPDATE categoria SET contadaen = '701' WHERE id='10';
--UPDATE categoria SET contadaen = '72' WHERE id='12';
--UPDATE categoria SET contadaen = '702' WHERE id='13';
--UPDATE categoria SET contadaen = '73' WHERE id='15';
--UPDATE categoria SET contadaen = '706' WHERE id='18';
--UPDATE categoria SET contadaen = '77' WHERE id='19';
--UPDATE categoria SET contadaen = '903' WHERE id='102';
--UPDATE categoria SET contadaen = '906' WHERE id='104';
--UPDATE categoria SET contadaen = '771' WHERE id='191';
--UPDATE categoria SET contadaen = '772' WHERE id='192';
--UPDATE categoria SET contadaen = '773' WHERE id='193';
--UPDATE categoria SET contadaen = '774' WHERE id='194';
--UPDATE categoria SET contadaen = '775' WHERE id='195';
--UPDATE categoria SET contadaen = '776' WHERE id='196';
--UPDATE categoria SET contadaen = '777' WHERE id='197';
--UPDATE categoria SET contadaen = '906' WHERE id='104';

-- contexto
INSERT INTO contexto (id, nombre, fechacreacion, fechadeshabilitacion) VALUES (1,'MILITARIZACIÓN','2001-01-29', NULL);
INSERT INTO contexto (id, nombre, fechacreacion, fechadeshabilitacion) VALUES (2,'PARAMILITARIZACIÓN','2001-01-29', NULL);
INSERT INTO contexto (id, nombre, fechacreacion, fechadeshabilitacion) VALUES (3,'PRESENCIA GUERRILLERA','2001-01-29', NULL);
INSERT INTO contexto (id, nombre, fechacreacion, fechadeshabilitacion) VALUES (4,'PRESENCIA DE MILICIAS','2001-01-29', NULL);
INSERT INTO contexto (id, nombre, fechacreacion, fechadeshabilitacion) VALUES (5,'ACCIONES BÉLICAS','2001-01-29', NULL);
INSERT INTO contexto (id, nombre, fechacreacion, fechadeshabilitacion) VALUES (6,'PARO CÍVICO','2001-01-29', NULL);
INSERT INTO contexto(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (7,'MANIFESTACIONES','2001-01-29', NULL);
INSERT INTO contexto(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (8,'PROTESTA','2001-01-29', NULL);
INSERT INTO contexto(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (9,'OCUPACIONES','2001-01-29', NULL);
INSERT INTO contexto(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (27,'ZONAS DE REHAB. Y CONSOL','2003-03-12', NULL);
INSERT INTO contexto(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (28,'CONFLICTO ARMADO','2004-02-18', NULL);
INSERT INTO contexto(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (10,'PARO AGRARIO','2001-01-29', NULL);
INSERT INTO contexto(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (11,'MARCHA CAMPESINA','2001-01-29', NULL);
INSERT INTO contexto(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (12,'TOMA DE TIERRAS','2001-01-29', NULL);
INSERT INTO contexto(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (13,'ENCLAVES ECONÓMICOS','2001-01-29', NULL);
INSERT INTO contexto(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (14,'CULTIVOS DE USO ILÍCITO','2001-01-29', NULL);
INSERT INTO contexto(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (15,'CONFLICTOS LABORALES','2001-01-29', NULL);
INSERT INTO contexto(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (16,'CONFLICTOS ESTUDIANTILES','2001-01-29', NULL);
INSERT INTO contexto(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (17,'PROBL. ÉTNICA (NEG.E IN.)','2001-01-29', NULL);
INSERT INTO contexto(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (18,'PROBLEMÁTICA FRONTERIZA','2001-01-29', NULL);
INSERT INTO contexto(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (19,'PROBLEMÁTICA AMBIENTAL','2001-01-29', NULL);
INSERT INTO contexto(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (20,'PROBLEMÁTICA CARCELARIA','2001-01-29', NULL);
INSERT INTO contexto(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (21,'DESALOJOS','2001-01-29', NULL);
INSERT INTO contexto(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (22,'PROCESOS DE PAZ O DIÁLOGO','2001-01-29', NULL);
INSERT INTO contexto(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (23,'PROCESOS ELECTORALES','2001-01-29', NULL);
INSERT INTO contexto(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (24,'CAMPAÑAS DE INTOLERANCIA','2001-01-29', NULL);
INSERT INTO contexto(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (25,'PERSECUSIÓN A ORGANIZACION','2001-01-29', NULL);
INSERT INTO contexto(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (26,'OTROS','2001-01-29', NULL);
INSERT INTO contexto(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (29,'FALSO POSITIVO','2010-01-29', NULL);
INSERT INTO contexto (id, nombre, fechacreacion, fechadeshabilitacion) VALUES ('30', 'INTOLERANCIA SOCIAL', '2011-04-26', NULL);
INSERT INTO contexto (id, nombre, fechacreacion, fechadeshabilitacion) VALUES ('31', 'SEGURIDAD INFORMÁTICA', '2011-04-28', NULL);

SELECT setval('contexto_seq', max(id)) FROM contexto;

-- presuntos responsables



INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (39, 'POLO ESTATAL', '2009-09-20', NULL, NULL);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (40, 'POLO INSURGENTE', '2009-09-20', NULL, NULL);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (1, 'ESTADO COLOMBIANO', '2001-01-30', NULL, '39');
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (2, 'FUERZA PUBLICA', '2001-01-30', NULL, '1');
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (7, 'POLICÍA', '2001-01-30', NULL, '2');
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (36, 'OTROS', '2001-06-13', NULL, NULL);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (24, 'AGENTE EXTRANJERO', '2001-01-30', NULL, '36');
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (25, 'GUERRILLA', '2001-01-30', NULL, '40');
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (32, 'ERG', '2001-01-30', NULL, '25');
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (33, 'GRUPOS DE INTOLERANCIA SOCIAL', '2001-01-30', NULL, '36');
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (34, 'INFORMACION CONTRADICTORIA', '2001-01-30', NULL, NULL);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (35, 'SIN INFORMACIÓN', '2001-01-30', NULL, NULL);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (37, 'COMBATIENTES', '2004-01-20', NULL, NULL);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (4, 'EJERCITO', '2001-01-30', NULL, 2);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (5, 'ARMADA', '2001-01-30', NULL, 2);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (6, 'FUERZA AEREA', '2001-01-30', NULL, 2);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (3, 'FUERZAS MILITARES', '2001-01-30', NULL, 2);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (8, 'GAULA', '2001-01-30', NULL, 3);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (9, 'SIJIN', '2001-01-30', NULL, 7);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (10, 'DIJIN', '2001-01-30', NULL, 7);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (11, 'INPEC', '2001-01-30', NULL, 2);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (12, 'DAS', '2001-01-30', NULL, 2);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (38, 'FISCALIA', '2004-07-14', NULL, 1);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (13, 'CTI', '2001-01-30', NULL, 38);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (14, 'PARAMILITARES', '2001-01-30', NULL,'39');
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (15, 'AUC', '2001-01-30', NULL, 14);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (16, 'ACCU', '2001-01-30', NULL, 14);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (17, 'AUTODEFENSAS DE PUERTO BOYACA', '2001-01-30', NULL, 14);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (18, 'AUTODEFENSAS DE RAMON ISAZA', '2001-01-30', NULL, 14);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (19, 'AUTODEFENSAS DE LOS LLANOS ORIENTALES', '2001-01-30', NULL, 14);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (20, 'AUTODEFENSAS DE SANTANDER Y SUR DEL CESAR', '2001-01-30', NULL, 14);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (21, 'AUTODEFENSAS DE CASANARE', '2001-01-30', NULL, 14);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (22, 'AUTODEFENSAS DE CUNDINAMARCA', '2001-01-30', NULL, 14);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (23, 'AUTODEFENSAS CAMPESINAS DEL MAGDALENA MEDIO, ACMM', '2001-01-30', NULL, 14);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (26, 'MILICIAS', '2001-01-30', NULL, 25);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (27, 'FARC-EP', '2001-01-30', NULL, 25);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (28, 'ELN', '2001-01-30', NULL, 25);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (29, 'MOVIMIENTO JAIME BATEMAN CAYON', '2001-01-30', NULL, 25);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (30, 'ERP', '2001-01-30', NULL, 25);
INSERT INTO presponsable (id, nombre, fechacreacion, fechadeshabilitacion, papa) VALUES (31, 'EPL', '2001-01-30', NULL, 25);


SELECT setval('presponsable_seq', max(id)) FROM presponsable;


-- antecedentes


INSERT INTO antecedente(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (1,'AMENAZA','2001-01-29', NULL);
INSERT INTO antecedente(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (2,'ATENTADO','2001-01-29', NULL);
INSERT INTO antecedente(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (3,'DETENCION','2001-01-29', NULL);
INSERT INTO antecedente(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (4,'AMNISTIA - INDULTO','2001-01-29', NULL);
INSERT INTO antecedente(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (5,'EXILIO','2001-01-29', NULL);
INSERT INTO antecedente(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (6,'ALLANAMIENTO','2001-01-29', NULL);
INSERT INTO antecedente(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (7,'DESAPARICION','2001-01-29', NULL);
INSERT INTO antecedente(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (8,'SECUESTRO','2001-01-29', NULL);
INSERT INTO antecedente(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (9,'DESPLAZAMIENTO','2001-01-29', NULL);
INSERT INTO antecedente(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (10,'SEGUIMIENTO','2001-01-29', NULL);
INSERT INTO antecedente(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (11,'SEÑALAMIENTO','2001-01-29', NULL);
INSERT INTO antecedente(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (12,'TORTURA','2001-01-29', NULL);
INSERT INTO antecedente(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (13,'OTRO','2001-01-29', NULL);
INSERT INTO antecedente(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (14,'SIN INFORMACIÓN','2001-01-29', NULL);
INSERT INTO antecedente(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (15,'PILLAJE','2006-08-18', NULL);
INSERT INTO antecedente(id, nombre, fechacreacion, fechadeshabilitacion) VALUES (16,'MASACRE','2006-08-18', NULL);

SELECT setval('antecedente_seq', max(id)) FROM antecedente;

-- intervalo

INSERT INTO intervalo(id, nombre, rango, fechacreacion, fechadeshabilitacion) VALUES ('1', 'MADRUGADA', '00:00 A 05:59', '2001-01-01', NULL);
INSERT INTO intervalo(id, nombre, rango, fechacreacion, fechadeshabilitacion) VALUES ('2', 'MAÑANA', '06:00 A 12:59', '2001-01-01', NULL);
INSERT INTO intervalo(id, nombre, rango, fechacreacion, fechadeshabilitacion) VALUES ('3', 'TARDE', '13:00 A 18:59', '2001-01-01', NULL);
INSERT INTO intervalo(id, nombre, rango, fechacreacion, fechadeshabilitacion) VALUES ('4', 'NOCHE', '19:00 A 24:59', '2001-01-01', NULL);
INSERT INTO intervalo(id, nombre, rango, fechacreacion, fechadeshabilitacion) VALUES ('5', 'SIN INFORMACIÓN', 'SIN INFORMACIÓN', '2001-01-01', NULL);

SELECT setval('intervalo_seq', max(id)) FROM intervalo;

