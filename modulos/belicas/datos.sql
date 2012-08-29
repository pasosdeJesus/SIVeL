
SET client_encoding = 'LATIN1';

INSERT INTO opcion (id_opcion, descripcion, id_mama, nomid) VALUES ('52', 'V. Combatientes', '50', 'opcion?num=200');
INSERT INTO opcion (id_opcion, descripcion, id_mama, nomid) VALUES ('46', 'Revista Bélicas', '40', 'consulta_web?mostrar=revista&categoria=belicas&sincampos=caso_id');
INSERT INTO opcion (id_opcion, descripcion, id_mama, nomid) VALUES ('47', 'Revista Memo Bélicas', '40', 'consulta_web?mostrar=revista&categoria=belicas&sincampos=caso_id,m_victimas,m_presponsables,m_tipificacion,m_fuentes');
INSERT INTO opcion (id_opcion, descripcion, id_mama, nomid) VALUES ('48', 'Revista NO Bélicas', '40', 'consulta_web?mostrar=revista&categoria=nobelicas&sincampos=caso_id');
INSERT INTO opcion (id_opcion, descripcion, id_mama, nomid) VALUES ('49', 'Revista Memo NO Bélicas', '40', 'consulta_web?mostrar=revista&categoria=nobelicas&sincampos=caso_id,m_victimas,m_presponsables,m_tipificacion,m_fuentes');
INSERT INTO opcion (id_opcion, descripcion, id_mama, nomid) VALUES ('54', 'Colectivas con Rotulos de Rep. Cons.', '50', 'opcion?num=101');

INSERT INTO opcion_rol (id_opcion, id_rol) VALUES ('46', '1');
INSERT INTO opcion_rol (id_opcion, id_rol) VALUES ('46', '2');
INSERT INTO opcion_rol (id_opcion, id_rol) VALUES ('47', '1');
INSERT INTO opcion_rol (id_opcion, id_rol) VALUES ('47', '2');
INSERT INTO opcion_rol (id_opcion, id_rol) VALUES ('48', '1');
INSERT INTO opcion_rol (id_opcion, id_rol) VALUES ('48', '2');
INSERT INTO opcion_rol (id_opcion, id_rol) VALUES ('49', '1');
INSERT INTO opcion_rol (id_opcion, id_rol) VALUES ('49', '2');


INSERT INTO actualizacion_base (id, fecha, descripcion) 
	VALUES ('sbel-1', '2011-02-23', 'Creación de tablas');
INSERT INTO actualizacion_base (id, fecha, descripcion) 
	VALUES ('sbel-2', '2011-02-23', 'Menus');
INSERT INTO actualizacion_base (id, fecha, descripcion) 
	VALUES ('sbel-3', '2011-02-23', 'Roles');
INSERT INTO actualizacion_base (id, fecha, descripcion) 
	VALUES ('1.2-ra', '2012-08-28', 'Renombra');
INSERT INTO actualizacion_base (id, fecha, descripcion) 
	VALUES ('1.2-bel2', '2012-08-28', 'Renombra');
