-- Datos aportados por Servicio Jesuita de Refugiados
-- Dominio publico con autorizacion de sacerdote John Jairo Montoya

SET client_encoding = 'UTF8';
INSERT INTO clasifdesp (id, nombre) VALUES (0, 'SIN INFORMACIÓN');
INSERT INTO clasifdesp (id, nombre) VALUES (1, 'INTER-MUNICIPAL');
INSERT INTO clasifdesp (id, nombre) VALUES (3, 'INTRA-URBANO');
INSERT INTO clasifdesp (id, nombre) VALUES (4, 'INTER-VEREDAL');
INSERT INTO clasifdesp (id, nombre) VALUES (5, 'INTER-FRONTERA');
INSERT INTO clasifdesp (id, nombre) VALUES (6, 'URBANO');
INSERT INTO clasifdesp (id, nombre) VALUES (7, 'TRANSFRONTERIZO');
INSERT INTO clasifdesp (id, nombre) VALUES (8, 'INTERDEPARTAMENTAL');

INSERT INTO tipodesp (id, nombre) VALUES (0, 'SIN INFORMACIÓN');
INSERT INTO tipodesp (id, nombre) VALUES (1, 'GOTA A GOTA');
INSERT INTO tipodesp (id, nombre) VALUES (2, 'MASIVO');
INSERT INTO tipodesp (id, nombre) VALUES (3, 'FAMILIAR');

INSERT INTO declaroante (id, nombre) VALUES (0, 'SIN INFORMACIÓN');
INSERT INTO declaroante (id, nombre) VALUES (1, 'PERSONERIA MUNICIPAL');
INSERT INTO declaroante (id, nombre) VALUES (2, 'DEFENSORIA REGIONAL');
INSERT INTO declaroante (id, nombre) VALUES (3, 'ROCURADURIA REGIONAL');
INSERT INTO declaroante (id, nombre) VALUES (4, 'PROCURADURIA PROVINCIAL ');
INSERT INTO declaroante (id, nombre) VALUES (5, 'OTRO');

INSERT INTO inclusion (id, nombre) VALUES (0, 'SIN INFORMACIÓN');
INSERT INTO inclusion (id, nombre) VALUES (1, 'SIN RESPUESTA');
INSERT INTO inclusion (id, nombre) VALUES (2, 'INCLUIDO');
INSERT INTO inclusion (id, nombre) VALUES (3, 'NO INCLUIDO');
INSERT INTO inclusion (id, nombre) VALUES (4, 'EN VALORACIÓN');
INSERT INTO inclusion (id, nombre) VALUES (5, 'EXCLUIDO');

INSERT INTO acreditacion (id, nombre) VALUES (0, 'SIN INFORMACIÓN');
INSERT INTO acreditacion (id, nombre) VALUES (1, 'CARTA');
INSERT INTO acreditacion (id, nombre) VALUES (2, 'DESPRENDIBLE');
INSERT INTO acreditacion (id, nombre) VALUES (3, 'CÓDIGO');

INSERT INTO modalidadtierra (id, nombre) VALUES (0, 'SIN INFORMACIÓN');
INSERT INTO modalidadtierra (id, nombre) VALUES (1, 'TENEDOR');
INSERT INTO modalidadtierra (id, nombre) VALUES (2, 'COLONO');
INSERT INTO modalidadtierra (id, nombre) VALUES (3, 'NO DEJÓ');
INSERT INTO modalidadtierra (id, nombre) VALUES (4, 'POSEEDOR');
INSERT INTO modalidadtierra (id, nombre) VALUES (5, 'PROPIETARIO');
INSERT INTO modalidadtierra (id, nombre) VALUES (6, 'RESGUARDO');
INSERT INTO modalidadtierra (id, nombre) VALUES (7, 'CONSEJO COMUNITARIO');


INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('des-e1', '2013-05-24', 'Estructura inicial');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('des-d1', '2013-05-24', 'Datos iniciales');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('des-ext', '2013-06-13', 'Cambio depot EXTERIOR de código 0 a 1000');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('des-13e', '2013-06-13', 'Renombra expulsión por id_exulsion y llegada por id_llegada');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('des-13p', '2014-02-23', 'Paises');
