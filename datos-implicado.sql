-- Datos para tablas básicas
-- Información sobre implicados tomada del Banco de 
-- Datos de Derechos Humanos y Violencia Politica.
-- Dominio público. 2004. Sin garantías.

SET client_encoding = 'UTF8';


--  filiacion

INSERT INTO filiacion(id, nombre, fechacreacion) VALUES (1, 'LIBERAL', '2001-01-01');
INSERT INTO filiacion(id, nombre, fechacreacion) VALUES (2, 'CONSERVADOR', '2001-01-01');
INSERT INTO filiacion(id, nombre, fechacreacion) VALUES (3, 'ALIANZAS TRADICIONALES', '2001-01-01');
INSERT INTO filiacion(id, nombre, fechacreacion) VALUES (4, 'IZQUIERDA', '2001-01-01');
INSERT INTO filiacion(id, nombre, fechacreacion) VALUES (5, 'ALIANZAS IZQUIERDA', '2001-01-01');
INSERT INTO filiacion(id, nombre, fechacreacion) VALUES (6, 'DERECHA', '2001-01-01');
INSERT INTO filiacion(id, nombre, fechacreacion) VALUES (7, 'DESMOVILIZADOS', '2001-01-01');
INSERT INTO filiacion(id, nombre, fechacreacion) VALUES (8, 'CIVICO POLITICOS ELECTORA', '2001-01-01');
INSERT INTO filiacion(id, nombre, fechacreacion) VALUES (9, 'OTRO', '2001-01-01');
INSERT INTO filiacion(id, nombre, fechacreacion) VALUES (10, 'SIN INFORMACIÓN', '2001-01-01');
INSERT INTO filiacion(id, nombre, fechacreacion) VALUES (11, 'UNIÓN PATRÍOTICA', '2001-01-01');
INSERT INTO filiacion(id, nombre, fechacreacion) VALUES (12, 'COMUNISTA', '2001-01-01');
INSERT INTO filiacion(id, nombre, fechacreacion) VALUES (13, 'POLO DEMOCRÁTICO', '2001-01-01');
INSERT INTO filiacion(id, nombre, fechacreacion) VALUES (14, 'FRENTE SOCIAL Y POLÍTICO', '2001-01-01');

SELECT setval('filiacion_seq', max(id)) FROM filiacion;

-- organizacion

INSERT INTO organizacion(id, nombre, fechacreacion) VALUES (1, 'CAMPESINA', '2001-01-01');
INSERT INTO organizacion(id, nombre, fechacreacion) VALUES (2, 'INDIGENA', '2001-01-01');
INSERT INTO organizacion(id, nombre, fechacreacion) VALUES (3, 'SINDICAL', '2001-01-01');
INSERT INTO organizacion(id, nombre, fechacreacion) VALUES (4, 'CIVICA', '2001-01-01');
INSERT INTO organizacion(id, nombre, fechacreacion) VALUES (5, 'ESTUDIANTIL', '2001-01-01');
INSERT INTO organizacion(id, nombre, fechacreacion) VALUES (6, 'PROFESIONAL', '2001-01-01');
INSERT INTO organizacion(id, nombre, fechacreacion) VALUES (7, 'NEGRITUDES', '2001-01-01');
INSERT INTO organizacion(id, nombre, fechacreacion) VALUES (8, 'FEMENINA', '2001-01-01');
INSERT INTO organizacion(id, nombre, fechacreacion) VALUES (9, 'RELIGIOSA', '2001-01-01');
INSERT INTO organizacion(id, nombre, fechacreacion) VALUES (10, 'HUMANITARIA', '2001-01-01');
INSERT INTO organizacion(id, nombre, fechacreacion) VALUES (11, 'DERECHOS HUMANOS', '2001-01-01');
INSERT INTO organizacion(id, nombre, fechacreacion) VALUES (12, 'GREMIAL', '2001-01-01');
INSERT INTO organizacion(id, nombre, fechacreacion) VALUES (13, 'JUVENIL', '2001-01-01');
INSERT INTO organizacion(id, nombre, fechacreacion) VALUES (14, 'AMBIENTALISTA', '2001-01-01');
INSERT INTO organizacion(id, nombre, fechacreacion) VALUES (15, 'OTRA', '2001-01-01');
INSERT INTO organizacion(id, nombre, fechacreacion) VALUES (16, 'SIN INFORMACIÓN', '2001-01-01');
INSERT INTO organizacion(id, nombre, fechacreacion) VALUES (17, 'VÍCTIMAS', '2013-07-05');

SELECT setval('organizacion_seq', max(id)) FROM organizacion;

-- profesion

INSERT INTO profesion(id, nombre, fechacreacion) VALUES (1, 'ABOGADO', '2001-01-01');
INSERT INTO profesion(id, nombre, fechacreacion) VALUES (2, 'EDUCADOR', '2001-01-01');
INSERT INTO profesion(id, nombre, fechacreacion) VALUES (3, 'MEDICO', '2001-01-01');
INSERT INTO profesion(id, nombre, fechacreacion) VALUES (4, 'ENFERMERO (A)', '2001-01-01');
INSERT INTO profesion(id, nombre, fechacreacion) VALUES (5, 'PERIODISTA', '2001-01-01');
INSERT INTO profesion(id, nombre, fechacreacion) VALUES (6, 'ESTUDIANTE', '2001-01-01');
INSERT INTO profesion(id, nombre, fechacreacion) VALUES (7, 'DEFENSOR DE DDHH', '2001-01-01');
INSERT INTO profesion(id, nombre, fechacreacion) VALUES (8, 'INGENIERO', '2001-01-01');
INSERT INTO profesion(id, nombre, fechacreacion) VALUES (9, 'SACERDOTE', '2001-01-01');
INSERT INTO profesion(id, nombre, fechacreacion) VALUES (10, 'RELIGIOSO', '2001-01-01');
INSERT INTO profesion(id, nombre, fechacreacion) VALUES (11, 'INVESTIGADOR SOCIAL', '2001-01-01');
INSERT INTO profesion(id, nombre, fechacreacion) VALUES (12, 'TECNOLOGO', '2001-01-01');
INSERT INTO profesion(id, nombre, fechacreacion) VALUES (13, 'ANTROPOLOGO', '2001-01-01');
INSERT INTO profesion(id, nombre, fechacreacion) VALUES (14, 'ARTISTA', '2001-01-01');
INSERT INTO profesion(id, nombre, fechacreacion) VALUES (15, 'ECONOMISTA', '2001-01-01');
INSERT INTO profesion(id, nombre, fechacreacion) VALUES (16, 'CONTADOR PUBLICO', '2001-01-01');
INSERT INTO profesion(id, nombre, fechacreacion) VALUES (17, 'ODONTOLOGO', '2001-01-01');
INSERT INTO profesion(id, nombre, fechacreacion) VALUES (18, 'SOCIOLOGO', '2001-01-01');
INSERT INTO profesion(id, nombre, fechacreacion) VALUES (19, 'TRABAJADOR SOCIAL', '2001-01-01');
INSERT INTO profesion(id, nombre, fechacreacion) VALUES (20, 'ADMINISTRADOR PUBLICO', '2001-01-01');
INSERT INTO profesion(id, nombre, fechacreacion) VALUES (21, 'OTRO', '2001-01-01');
INSERT INTO profesion(id, nombre, fechacreacion) VALUES (22, 'SIN INFORMACIÓN', '2001-01-01');

SELECT setval('profesion_seq', max(id)) FROM profesion;

-- rangoedad

INSERT INTO rangoedad (id, nombre, rango, limiteinferior, limitesuperior, fechacreacion) VALUES (1, 'R1', 'De 0 a 15 Años', '0', '15', '2001-03-23');
INSERT INTO rangoedad (id, nombre, rango, limiteinferior, limitesuperior, fechacreacion) VALUES (2, 'R2', 'De 16 a 25 Años', '16', '25', '2001-03-23');
INSERT INTO rangoedad (id, nombre, rango, limiteinferior, limitesuperior, fechacreacion) VALUES (3, 'R3', 'De 26 a 45 Años', '26', '45', '2001-03-23');
INSERT INTO rangoedad (id, nombre, rango, limiteinferior, limitesuperior, fechacreacion) VALUES (4, 'R4', 'De 46 a 60', '46', '60', '2001-03-23');
INSERT INTO rangoedad (id, nombre, rango, limiteinferior, limitesuperior, fechacreacion) VALUES (5, 'R5', 'De 61 en Adelante', '61', '130', '2001-03-23');
INSERT INTO rangoedad (id, nombre, rango, limiteinferior, limitesuperior, fechacreacion) VALUES (6, 'SN', 'SIN INFORMACIÓN', '-1', '-1', '2001-03-23');

SELECT setval('rangoedad_seq', max(id)) FROM rangoedad;

-- resultado agresion

INSERT INTO resagresion(id, nombre, fechacreacion) VALUES (1, 'MUERTO', '2001-01-01');
INSERT INTO resagresion(id, nombre, fechacreacion) VALUES (2, 'HERIDO', '2001-01-01');
INSERT INTO resagresion(id, nombre, fechacreacion) VALUES (3, 'PRIVADO DE LA LIBERTAD', '2001-01-01');

SELECT setval('resagresion_seq', max(id)) FROM resagresion;

-- sector social

INSERT INTO sectorsocial(id, nombre, fechacreacion) VALUES (1, 'CAMPESINO', '2001-01-01');
INSERT INTO sectorsocial(id, nombre, fechacreacion) VALUES (2, 'INDIGENA', '2001-01-01');
INSERT INTO sectorsocial(id, nombre, fechacreacion) VALUES (3, 'OBRERO', '2001-01-01');
INSERT INTO sectorsocial(id, nombre, fechacreacion) VALUES (4, 'COMERCIANTE', '2001-01-01');
INSERT INTO sectorsocial(id, nombre, fechacreacion) VALUES (5, 'EMPLEADO', '2001-01-01');
INSERT INTO sectorsocial(id, nombre, fechacreacion) VALUES (6, 'TRABAJADOR INDEPENDIENTE', '2001-01-01');
INSERT INTO sectorsocial(id, nombre, fechacreacion) VALUES (7, 'PROFESIONAL', '2001-01-01');
INSERT INTO sectorsocial(id, nombre, fechacreacion) VALUES (8, 'EMPRESARIO', '2001-01-01');
INSERT INTO sectorsocial(id, nombre, fechacreacion) VALUES (9, 'INDUSTRIAL', '2001-01-01');
INSERT INTO sectorsocial(id, nombre, fechacreacion) VALUES (10, 'HACENDADO', '2001-01-01');
INSERT INTO sectorsocial(id, nombre, fechacreacion) VALUES (11, 'MARGINADO', '2001-01-01');
INSERT INTO sectorsocial(id, nombre, fechacreacion) VALUES (12, 'TRABAJADOR (A) SEXUAL', '2001-01-01');
INSERT INTO sectorsocial(id, nombre, fechacreacion) VALUES (13, 'DESEMPLEADO (A)', '2001-01-01');
INSERT INTO sectorsocial(id, nombre, fechacreacion) VALUES (14, 'OTRO', '2001-01-01');
INSERT INTO sectorsocial(id, nombre, fechacreacion) VALUES (15, 'SIN INFORMACIÓN', '2001-01-01');
INSERT INTO sectorsocial(id, nombre, fechacreacion) VALUES (16, 'TRANSPORTADOR', '2001-01-01');

SELECT setval('sectorsocial_seq', max(id)) FROM sectorsocial;

-- vinculoestado


INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (1, 'CONGRESO', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (2, 'PRESIDENCIA', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (3, 'MINISTERIOS', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (4, 'DEPTOS. ADMINISTRATIVOS', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (5, 'EMP. IND. Y COM. DEL EST.', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (6, 'SUPERINTENDENCIAS', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (7, 'CONS. SUP. DE LA JUDICAT.', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (8, 'CORT. SUPREMA DE JUSTICIA', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (9, 'CORTE CONSTITUCIONAL', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (10, 'FISCALIA GRAL DE LA NAC.', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (11, 'CONSEJO NACIONAL ELECTOR.', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (12, 'REGIS. NAL DEL EST. CIVIL', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (13, 'PROCURADURIA GENERAL', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (38, 'SIN INFORMACIÓN', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (14, 'CONTRALORIA GENERAL DE R.', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (15, 'DEFENSORIA DEL PUEBLO', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (16, 'ASAMBLEA DEPARTAMENTAL', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (17, 'GOBERNACION', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (18, 'SECRETARIAS (DTO.)', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (19, 'EMPRESAS PUBLICAS DTALES.', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (20, 'TRIBUNALES DTALES.', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (21, 'REGISTRADURIA DTAL.', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (22, 'CONTRALORIA DTAL.', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (23, 'PROCURADURIA DTAL.', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (24, 'DEFENSORIA DTAL.', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (25, 'CONCEJO MUNICIPAL', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (26, 'ALCALDIA', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (27, 'SECRETARIAS MPALES.', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (28, 'EMPRESAS PUBLICAS MPALES.', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (29, 'JUZGADOS (MPALES.)', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (30, 'PROCURADURIA DELEGADA', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (31, 'DEFENSORIA (MPAL.)', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (32, 'PERSONERIA', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (33, 'CONTRALORIA (MPAL.)', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (34, 'JUNTAS ADMINIST. LOCALES', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (35, 'ALCALDIA MENOR', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (36, 'SECRETARIAS LOCALES', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (37, 'CASAS DE JUSTICIA', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (39, 'TRIBUNALES', '2001-01-01');
INSERT INTO vinculoestado(id, nombre, fechacreacion) VALUES (40, 'VICEPRESIDENCIA', '2013-07-05');

SELECT setval('vinculoestado_seq', max(id)) FROM vinculoestado;

-- trelacion 

INSERT INTO trelacion (id, nombre, observaciones, fechacreacion, fechadeshabilitacion, inverso) VALUES ('SI', 'SIN INFORMACION', NULL, '2001-01-01', NULL, NULL);
INSERT INTO trelacion (id, nombre, observaciones, fechacreacion, fechadeshabilitacion, inverso) VALUES ('SO', 'ESPOSA(O)/COMPAÑERA(O)', '', '2001-01-01', NULL, 'SO');
INSERT INTO trelacion (id, nombre, observaciones, fechacreacion, fechadeshabilitacion, inverso) VALUES ('AB', 'ABUELA(O)', '', '2001-01-01', NULL, NULL);
INSERT INTO trelacion (id, nombre, observaciones, fechacreacion, fechadeshabilitacion, inverso) VALUES ('NO', 'NIETA(O)', '', '2011-03-17', NULL, 'AB');
INSERT INTO trelacion (id, nombre, observaciones, fechacreacion, fechadeshabilitacion, inverso) VALUES ('PA', 'MADRE/PADRE', NULL, '2001-01-01', NULL, NULL);
INSERT INTO trelacion (id, nombre, observaciones, fechacreacion, fechadeshabilitacion, inverso) VALUES ('HI', 'HIJA(O)', NULL, '2001-01-01', NULL, 'PA');
INSERT INTO trelacion (id, nombre, observaciones, fechacreacion, fechadeshabilitacion, inverso) VALUES ('HE', 'HERMANA(O)', NULL, '2001-01-01', NULL, NULL);
INSERT INTO trelacion (id, nombre, observaciones, fechacreacion, fechadeshabilitacion, inverso) VALUES ('PO', 'MADRINA/PADRINO', NULL, '2001-01-01', NULL, NULL);
INSERT INTO trelacion (id, nombre, observaciones, fechacreacion, fechadeshabilitacion, inverso) VALUES ('AH', 'AHIJADA(O)', '', '2011-08-04', NULL, 'PO');
INSERT INTO trelacion (id, nombre, observaciones, fechacreacion, fechadeshabilitacion, inverso) VALUES ('TO', 'TIA(O)', NULL, '2001-01-01', NULL, NULL);
INSERT INTO trelacion (id, nombre, observaciones, fechacreacion, fechadeshabilitacion, inverso) VALUES ('OO', 'SOBRINA(O)', '', '2011-07-21', NULL, 'TO');
INSERT INTO trelacion (id, nombre, observaciones, fechacreacion, fechadeshabilitacion, inverso) VALUES ('PD', 'MADRASTRA(PADRASTRO)', '', '2011-09-21', NULL, NULL);
INSERT INTO trelacion (id, nombre, observaciones, fechacreacion, fechadeshabilitacion, inverso) VALUES ('HO', 'HIJASTRA(O)', '', '2011-05-02', NULL, 'PD');
INSERT INTO trelacion (id, nombre, observaciones, fechacreacion, fechadeshabilitacion, inverso) VALUES ('SG', 'SUEGRA(O)', '', '2011-05-27', NULL, NULL);
INSERT INTO trelacion (id, nombre, observaciones, fechacreacion, fechadeshabilitacion, inverso) VALUES ('YE', 'NUERA/YERNO', NULL, '2014-02-18', NULL, 'SG');
INSERT INTO trelacion (id, nombre, observaciones, fechacreacion, fechadeshabilitacion, inverso) VALUES ('PM', 'PRIMA(O)', NULL, '2014-02-18', NULL, NULL);

UPDATE trelacion SET inverso = 'NO' WHERE id='AB';
UPDATE trelacion SET inverso = 'HI' WHERE id='PA';
UPDATE trelacion SET inverso = 'HE' WHERE id='HE';
UPDATE trelacion SET inverso = 'AH' WHERE id='PO';
UPDATE trelacion SET inverso = 'OO' WHERE id='TO';
UPDATE trelacion SET inverso = 'HO' WHERE id='PD';
UPDATE trelacion SET inverso = 'YE' WHERE id='SG';
UPDATE trelacion SET inverso = 'PM' WHERE id='PM';


-- etnia

INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (39, 'NUKAK', ' ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (9, 'ARHUACO', '4 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (10, 'AWA', '5 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (11, 'BARÁ', '6 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (12, 'BARASANA', '7 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (13, 'BARÍ', '8 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (14, 'CAMSA - KAMSA', '35 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (15, 'CARIJONA', '13 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (16, 'COCAMA', '16 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (17, 'COFÁN', '18 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (18, 'COREGUAJE - KOREGUAJE', '37 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (19, 'CUBEO', '20 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (20, 'CUIBA', '21 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (21, 'CHIMILA', ' ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (22, 'DESANO', '23 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (23, 'EMBERA', '25 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (25, 'GUAMBIANO', '29 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (26, 'GUANANO - GUANACA', '30 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (27, 'GUAYABERO', '31 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (28, 'HUITOTO - WITOTO', '73 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (29, 'INGA', '34 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (30, 'JUPDA', ' ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (31, 'KARAPANA - CARAPANA', '12 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (32, 'KOGUI', '36 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (33, 'CURRIPACO', '22 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (34, 'MACUNA', '41 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (35, 'MACAGUAJE', '39 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (36, 'MOCANÁ', ' ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (37, 'MUISCA', '46 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (38, 'NASA - PAÉZ', '49 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (40, 'PASTOS', '50 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (41, 'PIAPOCO', '51 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (42, 'PIJAO', ' ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (43, 'PIRATAPUYO', '53 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (44, 'PUINAVE', '55 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (45, 'SÁLIBA', '56 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (46, 'SIKUANI', '57 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (47, 'SIONA', '58 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (48, 'TATUYO', '64 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (49, 'TINIGUA', ' ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (50, 'TUCANO', '67 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (51, 'UMBRÁ', ' ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (53, 'WAYUU', '72 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (54, 'WIWA - WIWUA', '74 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (55, 'WOUNAAN', '75 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (56, 'YAGUA', '76 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (57, 'YANACONA', '77 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (58, 'YUCUNA', '79 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (1, 'SIN INFORMACIÓN', ' ', '2011-04-26', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (2, 'MESTIZO', ' ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (3, 'BLANCO', ' ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (5, 'INDÍGENA', ' ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (6, 'ACHAGUA', '1 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (52, 'U´WA', '70 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (4, 'NEGRO', '200 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (7, 'ANDAKÍ', ' ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (8, 'ANDOQUE', '3 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (61, 'AMORUA', '2 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (62, 'BETOYE', '9 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (63, 'BORA', '10 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (59, 'YUKPA', ' ', '2011-04-29', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (60, 'ROM', '400 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls ', '2013-07-05', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (64, 'CABIYARI', '11 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (65, 'CARAMANTA', '84 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (66, 'CHAMI', '86 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (67, 'CHIMILA', '14 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (68, 'CHIRICOA', '15 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (69, 'COCONUCO', '17 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (70, 'COROCORO', '87 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (71, 'COYAIMA-NATAGAIMA', '19 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (72, 'DATUANA', '88 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (73, 'DUJOS', '24 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (74, 'EMBERA CATIO', '26 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (75, 'EMBERA CHAMI', '27 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (76, 'EMBERA SIAPIDARA', '28 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (77, 'KATIO', '85 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (78, 'LETUAMA', '38 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (79, 'MASIGUARE', '42 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (80, 'MATAPI', '43 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (81, 'MUINANE', '45 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (82, 'MURA', '90 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (83, 'NONUYA', '47 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (84, 'OCAINA', '48 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (85, 'PAYOARINI', '91 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (86, 'PIAROA', '52 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (87, 'PISAMIRA', '54 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (88, 'POLINDARA', '94 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (89, 'QUIYASINGAS', '93 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (90, 'SIRIANO', '59 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (91, 'SIRIPU', '60 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (92, 'TAIWANO', '61 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (93, 'TAMA', '92 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (94, 'TANIMUKA', '62 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (95, 'TARIANO', '63 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (96, 'TIKUNAS', '65 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (97, 'TULE', '68 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (98, 'TUYUCA', '69 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (99, 'WANANO', '71 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (100, 'YAUNA', '78 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (101, 'YUKO', '80 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (102, 'GARÚ', '89 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (103, 'GUAYUÚ', '32 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (104, 'HITNÚ', '33 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (105, 'MACÚ', '40 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (106, 'MIRAÑA', '44 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (107, 'TOTORÓ', '66 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (108, 'YURUTÍ', '82 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (109, 'YURÍ', '81 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);
INSERT INTO etnia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (110, 'ZENÚ', '83  en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls', '2014-05-30', NULL);

-- iglesia

INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (1, 'SIN INFORMACIÓN', '', '2011-04-26', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (10, 'IGLESIA CRISTIANA NO IDENTIFICADA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (15, 'AIEC - ASOCIACIÓN DE IGLESIAS EVANGÉLICAS DEL CARIBE', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (19, 'IGLESIA INTERAMERICANA DE COLOMBIA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (21, 'IGLESIA ALIANZA CRISTIANA MISIONERA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (22, 'IGLESIA MENONITA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (23, 'IGLESIA HERMANDAD EN CRISTO', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (24, 'IGLESIA CUADRANGULAR PENIEL', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (25, 'IGLESIA CUADRANGULAR', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (27, 'IGLESIA MOVIMIENTO MISIONERO MUNDIAL', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (29, 'IGLESIA PALABRA DE VIDA (AIEC)', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (30, 'COMUNIDAD CRISTIANA DE FE', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (31, 'IUMEC - IGLESIA UNIÓN MISIONERA EVANGÉLICA DE COLOMBIA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (32, 'IGLESIA BETHESDA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (33, 'IGLESIA CRISTO VIENE PRONTO', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (34, 'IGLESIA CRISTO REINA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (35, 'IGLESIA PENTECOSTAL UNIDA DE COLOMBIA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (36, 'IGLESIA CRISTIANA UNIÓN', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (37, 'CENTRO DE FE Y ESPERANZA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (38, 'IGLESIA TIBERIA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (39, 'IGLESIA LUZ Y VIDA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (40, 'IGLESIA AMOR Y VIDA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (41, 'IGLESIA CRISTO EL REY', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (42, 'IGLESIA CASA DE ALABANZA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (43, 'IGLESIA DE DIOS', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (44, 'IGLESIA CRUZADA CRISTIANA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (45, 'IGLESIA PRESBITERIANA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (46, 'IGLESIA REMANSO DE PAZ', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (47, 'IGLESIA CATÓLICA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (48, 'IGLESIA PENTECOSTAL', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (49, 'IGLESIA ASAMBLEAS DE DIOS', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (50, 'IGLESIA MONTE HOREB', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (51, 'IGLESIA DIOS ES AMOR', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (52, 'IGLESIA ATENAS', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (53, 'IGLESIA BAUTISTA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (54, 'IGLESIA PANAMERICANA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (55, 'IGLESIA HERMANOS MENONITAS', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (56, 'IGLESIA APOSTÓLICA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (57, 'IGLESIA PALABRA DE VIDA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (58, 'IGLESIA CRISTO CENTRO', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (59, 'IGLESIA LIBRE', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (60, 'MISIÓN INTERAMERICANA DE COLOMBIA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (61, 'IGLESIA EVANGÉLICA LAS PALOMAS', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (62, 'MISIÓN CORNERSTONE', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (63, 'IGLESIA EVANGÉLICA TEMPLO DE BELÉN ', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (64, 'IGLESIA EL VERBO', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (65, 'IGLESIA APOSENTO ALTO', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (66, 'CASA DE ORACIÓN DE RIOACHA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (67, 'IGLESIA LUTERANA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (68, 'IGLESIA EVANGELIO VIVO', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (69, 'IGLESIAS PUERTAS AL CIELO', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (70, 'IGLESIA LUZ Y VERDAD', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (71, 'IGLESIA ADVENTISTA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (72, 'IGLESIA CASA DE DIOS', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (73, 'INTEGRACIÓN CRISTIANA DE FE Y ORACIÓN (ICFO)', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (74, 'IGLESIA CENTRO CRISTIANO SILOÉ', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (75, 'IGLESIA MISIONERA MUNDIAL', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (76, 'IGLESIA NUEVA VIDA ', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (77, 'IGLESIA PÉRGAMO', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (78, 'IGLESIA LOS EFESIOS', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (79, 'IGLESIA CRISTO MI ÚNICA ESPERANZA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (80, 'IGLESIA SARDI', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (81, 'IGLESIA ALIANZA COLOMBIANA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (82, 'IGLESIA PUERTAS ABIERTAS', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (85, 'IGLESIA UNIÓN MISIONERA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (83, 'IGLESIA CRISTO TE LLAMA', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (84, 'IGLESIA CONFRATERNIDAD', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (86, 'CENTRO DE AMOR Y FE', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (87, 'IGLESIA CARISMÁTICA VISIÓN A LAS NACIONES ', '', '2011-05-06', NULL);
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion, fechadeshabilitacion) VALUES (88, 'IGLESIA AGUA DE VIDA', '', '2011-05-06', NULL);
