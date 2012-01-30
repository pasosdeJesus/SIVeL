
SET client_encoding = 'LATIN1';

SELECT setval('tipo_accion_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM tipo_accion) AS s;
SELECT setval('tipo_proceso_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM tipo_proceso) AS s;
SELECT setval('despacho_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM despacho) AS s;
SELECT setval('etapa_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM etapa) AS s;

SELECT setval('proceso_seq', max(id)) FROM proceso;
SELECT setval('accion_seq', max(id)) FROM accion;

