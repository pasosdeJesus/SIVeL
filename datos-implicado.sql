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

-- trelacion inicialmente de parametros:ParentesecoFamiliar
INSERT INTO trelacion (id, nombre, dirigido, observaciones, fechacreacion) 
VALUES ('AB', 'Abuela', true, NULL, '2001-01-01');
INSERT INTO trelacion (id, nombre, dirigido, observaciones, fechacreacion) 
VALUES ('AO','Abuelo', true, NULL, '2001-01-01');
INSERT INTO trelacion (id, nombre, dirigido, observaciones, fechacreacion) 
VALUES ('CO','Conyuge y/o Companero Permanente', false, NULL, '2001-01-01');
INSERT INTO trelacion (id, nombre, dirigido, observaciones, fechacreacion) 
VALUES ('HA','Hija', true, NULL, '2001-01-01');
INSERT INTO trelacion (id, nombre, dirigido, observaciones, fechacreacion) 
VALUES ('HE','Hermano', false, NULL, '2001-01-01');
INSERT INTO trelacion (id, nombre, dirigido, observaciones, fechacreacion) 
VALUES ('HI','Hijo', true, NULL, '2001-01-01');
INSERT INTO trelacion (id, nombre, dirigido, observaciones, fechacreacion) 
VALUES ('HR','Hermana', false, NULL, '2001-01-01');
INSERT INTO trelacion (id, nombre, dirigido, observaciones, fechacreacion) 
VALUES ('MA','Madrina', true, NULL, '2001-01-01');
INSERT INTO trelacion (id, nombre, dirigido, observaciones, fechacreacion) 
VALUES ('ME','Madre', true, NULL, '2001-01-01');
INSERT INTO trelacion (id, nombre, dirigido, observaciones, fechacreacion) 
VALUES ('PA','Padre', true, NULL, '2001-01-01');
INSERT INTO trelacion (id, nombre, dirigido, observaciones, fechacreacion) 
VALUES ('PO','Padrino', true, NULL, '2001-01-01');
INSERT INTO trelacion (id, nombre, dirigido, observaciones, fechacreacion) 
VALUES ('TA','Tia', true, NULL, '2001-01-01');
INSERT INTO trelacion (id, nombre, dirigido, observaciones, fechacreacion) 
VALUES ('TO','Tio', true, NULL, '2001-01-01');
INSERT INTO trelacion (id, nombre, dirigido, observaciones, fechacreacion) 
VALUES ('SI','SIN INFORMACION', true, NULL, '2001-01-01');


-- etnia

INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (1, 'SIN INFORMACIÓN', '', '2011-04-26');

INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (2, 'Mestizos', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (3, 'Blancos', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (4, 'Negros', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (5, 'Indígenas', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (6, 'Achagua', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (7, 'Andakí', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (8, 'Andoque', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (9, 'Arhuaco', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (10, 'Awá', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (11, 'Bara', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (12, 'Barasana', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (13, 'Barí', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (14, 'Camsá', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (15, 'Carijona', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (16, 'Cocama', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (17, 'Cofán', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (18, 'Coreguaje', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (19, 'Cubeo', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (20, 'Cuiba', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (21, 'Chimila', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (22, 'Desano', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (23, 'Emberá', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (24, 'Chimila', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (25, 'Guambiano', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (26, 'Guanano', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (27, 'Guayabero', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (28, 'Huitoto', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (29, 'Inga', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (30, 'Jupda', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (31, 'Karapana', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (32, 'Kogui', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (33, 'Kurripako', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (34, 'Macuna', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (35, 'Macaguane', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (36, 'Mocaná', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (37, 'Muisca', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (38, 'Nasa', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (39, 'Nukak', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (40, 'Pastos', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (41, 'Piapoco', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (42, 'Pijao', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (43, 'Piratapuyo', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (44, 'Puinave', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (45, 'Saliba', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (46, 'Sikuani', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (47, 'Siona', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (48, 'Tatuyo', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (49, 'Tinigua', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (50, 'Tucano', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (51, 'Umbrá', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (52, 'U''wa', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (53, 'Wayúu', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (54, 'Wiwa', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (55, 'Wounaan', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (56, 'Yagua', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (57, 'Yanacona', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (58, 'Yucuna', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (59, 'Yukpa', '', '2011-04-29');
INSERT INTO etnia (id, nombre, descripcion, fechacreacion) VALUES (60, 'ROM', '', '2013-07-05');

-- iglesia

INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (1, 'SIN INFORMACIÓN', '', '2011-04-26');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (10, 'Iglesia Cristiana no Identificada', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (15, 'AIEC - Asociación de Iglesias Evangélicas del Caribe', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (19, 'Iglesia Interamericana de Colombia', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (21, 'Iglesia Alianza Cristiana Misionera', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (22, 'Iglesia Menonita', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (23, 'Iglesia Hermandad en Cristo', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (24, 'Iglesia Cuadrangular Peniel', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (25, 'Iglesia Cuadrangular', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (27, 'Iglesia Movimiento Misionero Mundial', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (29, 'Iglesia Palabra de Vida (AIEC)', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (30, 'Comunidad Cristiana de Fe', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (31, 'IUMEC - Iglesia Unión Misionera Evangélica de Colombia', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (32, 'Iglesia Bethesda', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (33, 'Iglesia Cristo Viene Pronto', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (34, 'Iglesia Cristo Reina', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (35, 'Iglesia Pentecostal Unida de Colombia', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (36, 'Iglesia Cristiana Unión', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (37, 'Centro de Fe y Esperanza', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (38, 'Iglesia Tiberia', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (39, 'Iglesia Luz y Vida', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (40, 'Iglesia Amor y Vida', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (41, 'Iglesia Cristo el Rey', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (42, 'Iglesia Casa de Alabanza', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (43, 'Iglesia de Dios', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (44, 'Iglesia Cruzada Cristiana', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (45, 'Iglesia Presbiteriana', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (46, 'Iglesia Remanso de Paz', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (47, 'Iglesia Católica', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (48, 'Iglesia Pentecostal', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (49, 'Iglesia Asambleas de Dios', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (50, 'Iglesia Monte Horeb', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (51, 'Iglesia Dios es Amor', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (52, 'Iglesia Atenas', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (53, 'Iglesia Bautista', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (54, 'Iglesia Panamericana', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (55, 'Iglesia Hermanos Menonitas', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (56, 'Iglesia Apostólica', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (57, 'Iglesia Palabra de Vida', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (58, 'Iglesia Cristo Centro', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (59, 'Iglesia Libre', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (60, 'Misión Interamericana de Colombia', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (61, 'Iglesia Evangélica Las Palomas', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (62, 'Misión Cornerstone', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (63, 'Iglesia Evangélica Templo de Belén ', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (64, 'Iglesia El Verbo', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (65, 'Iglesia Aposento Alto', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (66, 'Casa de Oración de Rioacha', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (67, 'Iglesia Luterana', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (68, 'Iglesia Evangelio Vivo', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (69, 'Iglesias Puertas al Cielo', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (70, 'Iglesia Luz y Verdad', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (71, 'Iglesia Adventista', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (72, 'Iglesia Casa de Dios', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (73, 'Integración Cristiana de Fe y Oración (ICFO)', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (74, 'Iglesia Centro Cristiano Siloé', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (75, 'Iglesia Misionera Mundial', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (76, 'Iglesia Nueva Vida ', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (77, 'Iglesia Pérgamo', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (78, 'Iglesia Los Efesios', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (79, 'Iglesia Cristo mi única esperanza', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (80, 'Iglesia Sardi', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (81, 'Iglesia Alianza Colombiana', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (82, 'Iglesia Puertas Abiertas', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (85, 'Iglesia Unión Misionera', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (83, 'Iglesia Cristo Te Llama', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (84, 'Iglesia Confraternidad', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (86, 'Centro de Amor y Fe', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (87, 'Iglesia Carismática Visión a las Naciones ', '', '2011-05-06');
INSERT INTO iglesia (id, nombre, descripcion, fechacreacion) VALUES (88, 'Iglesia Agua de Vida', '', '2011-05-06');


