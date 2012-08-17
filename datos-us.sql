-- Datos para tablas b�sicas
-- Dominio p�blico. 2004. Sin garant�as.

SET client_encoding = 'LATIN1';


--- Actualizaciones a estructura de base de datos
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('0.92post', '2008-10-21', 'Categorias repetidas marcadas');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('0.94', '2008-10-21', 'Conteos');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.0', '2008-10-21', 'Anotaciones en v�ctima');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.0cp3', '2009-02-24', 'Consistencia demogr�ficos, nuevas categorias');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.0f', '2009-02-24', 'SIN INFORMACI�N en Fuentes Frecuentes para permitir consulta externa');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.0g', '2009-08-05', 'Condensado de clave es sha1 en lugar de md5');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a1-ubi', '2009-08-09', 'Tipo de ubicaci�n en ubicaci�n');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a1-sld', '2009-08-09', 'Sin departamento_caso');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a1-slm', '2009-08-09', 'Sin municipio_caso');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a1-slc', '2009-08-09', 'Sin clase_caso');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a1-uc', '2009-08-13', 'Sin ubicacion_caso y con latitud, longitud');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a1-per', '2009-08-19', 'Persona');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a1-col', '2009-09-7', 'V�ctimas colectivas');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a1-act', '2009-09-7', 'Actos');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a1-com', '2009-09-7', 'B�licas es m�dulo');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a1-jp', '2009-09-7', 'Jerarqu�a presuntos responsables');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a1-tc', '2009-09-7', 'Tipo de categoria');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a1-org', '2009-09-7', 'Fecha de creaci�n/deshabilitaci�n');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a2-ccg', '2009-09-7', 'Jerarqu�a redefinida');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a2-imp', '2009-09-7', 'Importa relatos');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b1-vs', '2010-02-7', 'Categorias recientes replicadas');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b1-cr', '2010-02-7', 'Completar actos');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b1-ref', '2010-02-7', 'Categorias refinadas');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b1-ren', '2010-02-7', 'Renombra');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b1-pes', '2010-02-7', 'Pesos en r�tulos de reporte consolidado');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b1-ctx', '2010-02-7', 'Contexto y detalles');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b1-tr', '2010-02-7', 'Tipos de relaciones familiares');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b1-esp', '2010-07-28', 'Acuerdos Esperanza 2010');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b2-lu', '2010-07-28', 'Tama�o de ubicaci�n');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b2-vc', '2010-07-28', 'Categoria colectiva');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b2-val', '2010-07-28', 'Validar');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b2-br', '2010-07-28', 'Buscar repetidos');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b2-or', '2010-07-28', 'Opciones de roles');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b3-cat', '2010-07-30', 'Reversa categoria 902 de colectiva a otros porque es por metodos');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b3-is', '2010-07-30', 'Grupos de intolerancia es 33Grupos de intolerancia es 33');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b3-v', '2010-07-30', 'Renumerada categoria 221 a 291 para que coincida en Marco Conceptual');

INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b3-ran', '2010-07-30', 'L�mites en rango de edad');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1cp1-r', '2011-02-22', 'fechacreacion y fechadeshabilitacion');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1cp1-mf', '2011-02-22', 'A�ade fechacreacion y fechadeshabilitacion a otras tablas b�sicas');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1cp2-c', '2011-04-26', 'A�ade etnia y contexto intolerancia social');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1cp2-i', '2011-04-26', 'A�ade iglesia y contexto seguridad inform�tica');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1-vs', '2011-07-11', 'Violencia sexual m�s completa');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1-nd', '2011-10-21', 'Nomenclatura en tabla persona');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1-os', '2011-10-21', 'Orientaci�n sexual');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.2-sm', '2012-07-29', 'Men� pasa de base de datos a interfaz');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.2-lu', '2012-07-29', 'Usuarios con idioma');
 
