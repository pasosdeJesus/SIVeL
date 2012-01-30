-- Datos para tablas básicas
-- Dominio público. 2004. Sin garantías.

SET client_encoding = 'LATIN1';

-- rol

INSERT INTO rol VALUES (1, 'Administrador');
INSERT INTO rol VALUES (2, 'Analista');
INSERT INTO rol VALUES (3, 'Consulta');
INSERT INTO rol VALUES (4, 'Ayudante');

SELECT setval('rol_seq', max(id_rol)) FROM rol;

-- opciones

INSERT INTO opcion VALUES ('0', 'Menús', NULL, NULL);
INSERT INTO opcion VALUES ('10', 'Administración', 0, NULL);
INSERT INTO opcion VALUES ('11', 'Tablas Básicas', 10, 'tablas_basicas');
INSERT INTO opcion VALUES ('12', 'Usuarios', 10, 'usyroles');
INSERT INTO opcion VALUES ('20', 'Caso', 0);
INSERT INTO opcion VALUES ('21', 'Ficha', 20, 'captura_caso');
INSERT INTO opcion VALUES ('30', 'Consultas', 0 );
INSERT INTO opcion VALUES ('31', 'Consulta Detallada', 30, 'consulta');
INSERT INTO opcion VALUES ('32', 'Consulta Web', 30, 'consulta_web');
INSERT INTO opcion VALUES ('40', 'Reportes', 0);
INSERT INTO opcion VALUES ('41', 'Revista', 40, 'consulta_web?mostrar=revista&sincampos=caso_id');
INSERT INTO opcion VALUES ('42', 'General', 40, 'consulta_web?mostrar=general');
INSERT INTO opcion VALUES ('43', 'Consolidado', 40, 'consolidado');
INSERT INTO opcion VALUES ('44', 'General por Localizacion', 40, 'consulta_web?mostrar=general&orden=localizacion');
INSERT INTO opcion VALUES ('45', 'Revista con código', 40, 'consulta_web?mostrar=revista');
INSERT INTO opcion VALUES ('50', 'Conteos', 0);
INSERT INTO opcion VALUES ('51', 'V. Individuales', 50, 'estadisticas');
INSERT INTO opcion VALUES ('60', 'Otros', 0);
INSERT INTO opcion VALUES ('61', 'Importar Relatos', '60', 'importaRelato');
INSERT INTO opcion VALUES ('62', 'Completar Actos', '60', 'completaActos');
INSERT INTO opcion VALUES ('63', 'Actualizar', '60', 'actualiza');
INSERT INTO opcion VALUES ('64', 'Validar', '60', 'valida');
INSERT INTO opcion VALUES ('65', 'Buscar repetidos', '60', 'buscaRepetidos');
INSERT INTO opcion VALUES ('69', 'Salir', 60, 'terminar');


--- opcion-rol



INSERT INTO opcion_rol VALUES ('0', '1');
INSERT INTO opcion_rol VALUES ('0', '2');
INSERT INTO opcion_rol VALUES ('0', '3');
INSERT INTO opcion_rol VALUES ('0', '4');
INSERT INTO opcion_rol VALUES ('11', '1');
INSERT INTO opcion_rol VALUES ('12', '1');
INSERT INTO opcion_rol VALUES ('21', '1');
INSERT INTO opcion_rol VALUES ('21', '2');
INSERT INTO opcion_rol VALUES ('31', '1');
INSERT INTO opcion_rol VALUES ('31', '2');
INSERT INTO opcion_rol VALUES ('31', '3');
INSERT INTO opcion_rol VALUES ('41', '1');
INSERT INTO opcion_rol VALUES ('41', '2');
INSERT INTO opcion_rol VALUES ('42', '1');
INSERT INTO opcion_rol VALUES ('42', '2');
INSERT INTO opcion_rol VALUES ('42', '4');
INSERT INTO opcion_rol VALUES ('43', '1');
INSERT INTO opcion_rol VALUES ('51', '1');
INSERT INTO opcion_rol VALUES ('44', '1');
INSERT INTO opcion_rol VALUES ('44', '2');
INSERT INTO opcion_rol VALUES ('45', '1');
INSERT INTO opcion_rol VALUES ('45', '2');
INSERT INTO opcion_rol VALUES ('60', '1');
INSERT INTO opcion_rol VALUES ('60', '2');
INSERT INTO opcion_rol VALUES ('60', '3');
INSERT INTO opcion_rol VALUES ('60', '4');
INSERT INTO opcion_rol VALUES ('61', '1');
INSERT INTO opcion_rol VALUES ('61', '2');
INSERT INTO opcion_rol VALUES ('61', '3');
INSERT INTO opcion_rol VALUES ('62', '1');
INSERT INTO opcion_rol VALUES ('63', '1');
INSERT INTO opcion_rol VALUES ('64', '1');
INSERT INTO opcion_rol VALUES ('64', '2');
INSERT INTO opcion_rol VALUES ('64', '3');
INSERT INTO opcion_rol VALUES ('65', '1');
INSERT INTO opcion_rol VALUES ('65', '2');
INSERT INTO opcion_rol VALUES ('65', '3');



--- Actualizaciones a estructura de base de datos
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('0.92post', '2008-10-21', 'Categorias repetidas marcadas');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('0.94', '2008-10-21', 'Conteos');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.0', '2008-10-21', 'Anotaciones en víctima');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.0cp3', '2009-02-24', 'Consistencia demográficos, nuevas categorias');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.0f', '2009-02-24', 'SIN INFORMACIÓN en Fuentes Frecuentes para permitir consulta externa');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.0g', '2009-08-05', 'Condensado de clave es sha1 en lugar de md5');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a1-ubi', '2009-08-09', 'Tipo de ubicación en ubicación');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a1-sld', '2009-08-09', 'Sin departamento_caso');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a1-slm', '2009-08-09', 'Sin municipio_caso');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a1-slc', '2009-08-09', 'Sin clase_caso');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a1-uc', '2009-08-13', 'Sin ubicacion_caso y con latitud, longitud');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a1-per', '2009-08-19', 'Persona');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a1-col', '2009-09-7', 'Víctimas colectivas');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a1-act', '2009-09-7', 'Actos');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a1-com', '2009-09-7', 'Bélicas es módulo');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a1-jp', '2009-09-7', 'Jerarquía presuntos responsables');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a1-tc', '2009-09-7', 'Tipo de categoria');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a1-org', '2009-09-7', 'Fecha de creación/deshabilitación');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a2-ccg', '2009-09-7', 'Jerarquía redefinida');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1a2-imp', '2009-09-7', 'Importa relatos');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b1-vs', '2010-02-7', 'Categorias recientes replicadas');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b1-cr', '2010-02-7', 'Completar actos');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b1-ref', '2010-02-7', 'Categorias refinadas');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b1-ren', '2010-02-7', 'Renombra');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b1-pes', '2010-02-7', 'Pesos en rótulos de reporte consolidado');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b1-ctx', '2010-02-7', 'Contexto y detalles');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b1-tr', '2010-02-7', 'Tipos de relaciones familiares');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b1-esp', '2010-07-28', 'Acuerdos Esperanza 2010');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b2-lu', '2010-07-28', 'Tamaño de ubicación');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b2-vc', '2010-07-28', 'Categoria colectiva');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b2-val', '2010-07-28', 'Validar');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b2-br', '2010-07-28', 'Buscar repetidos');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b2-or', '2010-07-28', 'Opciones de roles');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b3-cat', '2010-07-30', 'Reversa categoria 902 de colectiva a otros porque es por metodos');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b3-is', '2010-07-30', 'Grupos de intolerancia es 33Grupos de intolerancia es 33');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b3-v', '2010-07-30', 'Renumerada categoria 221 a 291 para que coincida en Marco Conceptual');

INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1b3-ran', '2010-07-30', 'Límites en rango de edad');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1cp1-r', '2011-02-22', 'fechacreacion y fechadeshabilitacion');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1cp1-mf', '2011-02-22', 'Añade fechacreacion y fechadeshabilitacion a otras tablas básicas');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1cp2-c', '2011-04-26', 'Añade etnia y contexto intolerancia social');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1cp2-i', '2011-04-26', 'Añade iglesia y contexto seguridad informática');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1-vs', '2011-07-11', 'Violencia sexual más completa');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1-nd', '2011-10-21', 'Nomenclatura en tabla persona');
INSERT INTO actualizacion_base (id, fecha, descripcion) VALUES ('1.1-os', '2011-10-21', 'Orientación sexual');
