
SET client_encoding = 'UTF8';

SELECT setval('antecedente_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM antecedente) AS s;
SELECT setval('caso_seq', MAX(id)) FROM caso;
sELECT setval('clase_seq', MAX(id)) FROM clase;
SELECT setval('contexto_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM contexto) AS s;
SELECT setval('departamento_seq', MAX(id)) FROM departamento;
SELECT setval('etnia_seq', MAX(id)) FROM (SELECT 2000 as id UNION SELECT MAX(id) FROM etnia) AS s;
SELECT setval('filiacion_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM filiacion) AS s;
SELECT setval('iglesia_seq', MAX(id)) FROM (SELECT 1000 as id UNION SELECT MAX(id) FROM iglesia) AS s;
SELECT setval('frontera_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM frontera) AS s;
SELECT setval('fotra_seq', MAX(id)) FROM fotra;
SELECT setval('usuario_seq', MAX(id)) FROM usuario;
SELECT setval('grupoper_seq', MAX(id)) FROM grupoper;
SELECT setval('iglesia_seq', MAX(id)) FROM (SELECT 1000 as id UNION SELECT MAX(id) FROM iglesia) AS s;
SELECT setval('intervalo_seq', MAX(id)) FROM intervalo;
SELECT setval('municipio_seq', MAX(id)) FROM municipio;
SELECT setval('organizacion_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM organizacion) AS s;
SELECT setval('pconsolidado_seq', MAX(id)) FROM pconsolidado;
SELECT setval('persona_seq', MAX(id)) FROM persona;
SELECT setval('ffrecuente_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM ffrecuente) AS s;
SELECT setval('presponsable_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM presponsable) AS s;
SELECT setval('profesion_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM profesion) AS s;
SELECT setval('rangoedad_seq', MAX(id)) FROM rangoedad;
SELECT setval('region_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM region) AS s;
SELECT setval('resagresion_seq', MAX(id)) FROM resagresion;
SELECT setval('sectorsocial_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM sectorsocial) AS s;
SELECT setval('tsitio_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM tsitio) AS s;
SELECT setval('ubicacion_seq', MAX(id)) FROM ubicacion;
SELECT setval('vinculoestado_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM vinculoestado) AS s;


