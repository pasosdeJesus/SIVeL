-- Datos para tablas básicas
-- Dominio público. 2004. Sin garantías.

SET client_encoding = 'UTF8';


--- Actualizaciones a estructura de base de datos
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('0.92post', '2008-10-21', 'Categorias repetidas marcadas');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('0.94', '2008-10-21', 'Conteos');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.0', '2008-10-21', 'Anotaciones en víctima');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.0cp3', '2009-02-24', 'Consistencia demográficos, nuevas categorias');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.0f', '2009-02-24', 'SIN INFORMACIÓN en Fuentes Frecuentes para permitir consulta externa');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.0g', '2009-08-05', 'Condensado de clave es sha1 en lugar de md5');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1a1-ubi', '2009-08-09', 'Tipo de ubicación en ubicación');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1a1-sld', '2009-08-09', 'Sin departamento_caso');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1a1-slm', '2009-08-09', 'Sin municipio_caso');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1a1-slc', '2009-08-09', 'Sin clase_caso');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1a1-uc', '2009-08-13', 'Sin ubicacion_caso y con latitud, longitud');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1a1-per', '2009-08-19', 'Persona');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1a1-col', '2009-09-7', 'Víctimas colectivas');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1a1-act', '2009-09-7', 'Actos');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1a1-com', '2009-09-7', 'Bélicas es módulo');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1a1-jp', '2009-09-7', 'Jerarquía presuntos responsables');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1a1-tc', '2009-09-7', 'Tipo de categoria');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1a1-org', '2009-09-7', 'Fecha de creación/deshabilitación');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1a2-ccg', '2009-09-7', 'Jerarquía redefinida');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1a2-imp', '2009-09-7', 'Importa relatos');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1b1-vs', '2010-02-7', 'Categorias recientes replicadas');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1b1-cr', '2010-02-7', 'Completar actos');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1b1-ref', '2010-02-7', 'Categorias refinadas');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1b1-ren', '2010-02-7', 'Renombra');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1b1-pes', '2010-02-7', 'Pesos en rótulos de reporte consolidado');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1b1-ctx', '2010-02-7', 'Contexto y detalles');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1b1-tr', '2010-02-7', 'Tipos de relaciones familiares');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1b1-esp', '2010-07-28', 'Acuerdos Esperanza 2010');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1b2-lu', '2010-07-28', 'Tamaño de ubicación');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1b2-vc', '2010-07-28', 'Categoria colectiva');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1b2-val', '2010-07-28', 'Validar');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1b2-br', '2010-07-28', 'Buscar repetidos');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1b2-or', '2010-07-28', 'Opciones de roles');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1b3-cat', '2010-07-30', 'Reversa categoria 902 de colectiva a otros porque es por metodos');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1b3-is', '2010-07-30', 'Grupos de intolerancia es 33Grupos de intolerancia es 33');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1b3-v', '2010-07-30', 'Renumerada categoria 221 a 291 para que coincida en Marco Conceptual');

INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1b3-ran', '2010-07-30', 'Límites en rango de edad');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1cp1-r', '2011-02-22', 'fechacreacion y fechadeshabilitacion');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1cp1-mf', '2011-02-22', 'Añade fechacreacion y fechadeshabilitacion a otras tablas básicas');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1cp2-c', '2011-04-26', 'Añade etnia y contexto intolerancia social');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1cp2-i', '2011-04-26', 'Añade iglesia y contexto seguridad informática');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1-vs', '2011-07-11', 'Violencia sexual más completa');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1-nd', '2011-10-21', 'Nomenclatura en tabla persona');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1-os', '2011-10-21', 'Orientación sexual');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-sm', '2012-07-29', 'Menú pasa de base de datos a interfaz');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-lu', '2012-07-29', 'Usuarios con idioma');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-rt', '2012-08-25', 'Renombrando tablas');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-rt2', '2012-08-25', 'Renombrando tablas II');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-rt3', '2012-08-25', 'Renombrando tablas III');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-rc1', '2012-08-25', 'Renombrando campos I');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1-dp', '2013-01-25', 'Actualizacion DIVIPOLA 2012');
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1-dp1', '2013-01-25', 'Actualizacion DIVIPOLA 2012'); 
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-gc', '2012-01-25', 'Latitud y Longitud en departamento, municipio y clase'); 
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-co', '2013-04-25', 'Coordenadas en departamentos y municipios'); 
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-coc', '2013-04-25', 'Agrega coordenadas a casos que no tienen'); 
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-loc', '2013-05-20', 'Localización'); 
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-btc', '2013-05-20', 'Búsqueda de textos'); 
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-idn', '2013-06-06', 'Número de documento entero'); 
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-ext', '2013-06-12', 'Cambio código departamento Exterior de 0 a 1000'); 
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-tb', '2013-06-12', 'Agrega vincuos con estado'); 
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-sx', '2013-10-04', 'Funcion soundex en español'); 
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.1-dp13', '2013-11-13', 'Actualiza con DIVIPOLA 2013'); 
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-fun', '2013-12-31', 'Fusiona tablas funcionario y usuario'); 
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-fu2', '2013-12-31', 'Fusiona tablas funcionario y usuario 2'); 
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-bc', '2014-01-01', 'Clave con condensado bcrypt'); 
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-nc', '2014-01-08', 'Nombre en Sector Social de Victima Colectiva'); 
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-def', '2014-01-08', 'Valores por defecto en referencias a tablas básicas'); 
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-fam', '2014-03-08', 'Tipos de relaciones'); 
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-et', '2014-03-08', 'Listado de etnias mejorado'); 
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-ig', '2014-03-08', 'Listado de iglesias mejorado'); 
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-mp', '2014-03-08', 'Datos recientes'); 
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-lo', '2014-03-08', 'Cuentas se bloquean tras varios intentos fallidos de ingreso'); 
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-sxe', '2014-03-08', 'Vista con soundexesp de nombres de personas'); 
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-sexo', '2014-03-08', 'Valida sexo de víctimas con modelo prob.'); 
INSERT INTO actualizacionbase (id, fecha, descripcion) VALUES ('1.2-apn', '2014-03-08', 'Valida apellidos/nombres con modelo prob.'); 
    


