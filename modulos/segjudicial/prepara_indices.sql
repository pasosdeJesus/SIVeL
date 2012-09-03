
SET client_encoding = 'LATIN1';

SELECT setval('taccion_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM taccion) AS s;
SELECT setval('tproceso_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM tproceso) AS s;
SELECT setval('despacho_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM despacho) AS s;
SELECT setval('etapa_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM etapa) AS s;

SELECT setval('proceso_seq', max(id)) FROM proceso;
SELECT setval('accion_seq', max(id)) FROM accion;

