
SET client_encoding = 'UTF8';


--INSERT INTO resagresion(id, nombre, fechacreacion) VALUES (1, 'MUERTO', '2001-01-01');
--INSERT INTO resagresion(id, nombre, fechacreacion) VALUES (2, 'HERIDO', '2001-01-01');
--INSERT INTO resagresion(id, nombre, fechacreacion) VALUES (3, 'PRIVADO DE LA LIBERTAD', '2001-01-01');

--SELECT setval('resagresion_seq', max(id)) FROM resagresion;



INSERT INTO actualizacionbase (id, fecha, descripcion) 
	VALUES ('sbel-1', '2011-02-23', 'Creación de tablas');
INSERT INTO actualizacionbase (id, fecha, descripcion) 
	VALUES ('sbel-2', '2011-02-23', 'Menus');
INSERT INTO actualizacionbase (id, fecha, descripcion) 
	VALUES ('sbel-3', '2011-02-23', 'Roles');
INSERT INTO actualizacionbase (id, fecha, descripcion) 
	VALUES ('1.2-ra', '2012-08-28', 'Renombra');
INSERT INTO actualizacionbase (id, fecha, descripcion) 
	VALUES ('1.2-bel2', '2012-08-28', 'Renombra');
