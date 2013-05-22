
SET client_encoding = 'LATIN1';

INSERT INTO etiqueta (id, nombre, observaciones) 
	VALUES ('1', 'SINCODH:PUBLICO', 'El documento es público');
INSERT INTO etiqueta (id, nombre, observaciones) 
	VALUES ('2', 'SINCODH:PRIVADO', 'El documento es privado');
INSERT INTO etiqueta (id, nombre, observaciones) 
       	VALUES ('3', 'IMPORTA_RELATO', 'Relato importado');
INSERT INTO etiqueta (id, nombre, observaciones) 
        VALUES ('4', 'ERROR_IMPORTACIÓN', 'Error en importación');
INSERT INTO etiqueta (id, nombre, observaciones) 
        VALUES ('5', 'ROJO', 'Color #FF0000');
INSERT INTO etiqueta (id, nombre, observaciones) 
        VALUES ('6', 'VERDE', 'Color #00FF00');
INSERT INTO etiqueta (id, nombre, observaciones) 
        VALUES ('7', 'AZUL', 'Color #0000FF');
INSERT INTO etiqueta (id, nombre, observaciones) 
        VALUES ('8', 'AMARILLO', 'Color #FFFF00'); 
INSERT INTO etiqueta (id, nombre, observaciones)
        VALUES ('9', 'MES_INEXACTO', 'El dia y mes del caso son inexactos'); 
INSERT INTO etiqueta (id, nombre, observaciones) 
	VALUES ('10', 'DIA_INEXACTO', 'El dia del caso es inexacto');

INSERT INTO actualizacionbase (id, fecha, descripcion) 
	VALUES ('eti-1', '2011-02-22', 'Creación de tablas');
INSERT INTO actualizacionbase (id, fecha, descripcion) 
	VALUES ('eti-c1', '2011-02-22', 'Actualiza módulo antiguo');
INSERT INTO actualizacionbase (id, fecha, descripcion) 
	VALUES ('eti-d1', '2011-02-22', 'Actualiza módulo antiguo');
INSERT INTO actualizacionbase (id, fecha, descripcion) 
	VALUES ('eti-ir', '2011-02-22', 'Importado de Relato');
INSERT INTO actualizacionbase (id, fecha, descripcion) 
	VALUES ('eti-col', '2011-02-22', 'Colores');
INSERT INTO actualizacionbase (id, fecha, descripcion) 
	VALUES ('eti-er', '2011-05-07', 'Error en importación');
INSERT INTO actualizacionbase (id, fecha, descripcion) 
	VALUES ('eti-fi', '2011-07-19', 'Etiqueta fecha inexacta');
INSERT INTO actualizacionbase (id, fecha, descripcion) 
	VALUES ('eti-fe', '2011-07-19', 'Fechas en tablas básicas');
INSERT INTO actualizacionbase (id, fecha, descripcion) 
	VALUES ('1.2-re', '2011-07-19', 'Renombra tablas');
INSERT INTO actualizacionbase (id, fecha, descripcion) 
	VALUES ('1.2-el', '2011-07-19', 'Localización');


