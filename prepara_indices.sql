
SET client_encoding = 'LATIN1';

SELECT setval('antecedente_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM antecedente) AS s;
SELECT setval('caso_seq', max(id)) FROM caso;
sELECT setval('clase_seq', max(id)) FROM clase;
SELECT setval('contexto_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM contexto) AS s;
SELECT setval('departamento_seq', max(id)) FROM departamento;
SELECT setval('etnia_seq', MAX(id)) FROM (SELECT 2000 as id UNION SELECT MAX(id) FROM etnia) AS s;
SELECT setval('filiacion_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM filiacion) AS s;
SELECT setval('iglesia_seq', MAX(id)) FROM (SELECT 1000 as id UNION SELECT MAX(id) FROM iglesia) AS s;
SELECT setval('frontera_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM frontera) AS s;
SELECT setval('fotra_seq', max(id)) FROM fotra;
SELECT setval('funcionario_seq', max(id)) FROM funcionario;
SELECT setval('grupoper_seq', max(id)) FROM grupoper;
SELECT setval('iglesia_seq', MAX(id)) FROM (SELECT 1000 as id UNION SELECT MAX(id) FROM iglesia) AS s;
SELECT setval('intervalo_seq', max(id)) FROM intervalo;
SELECT setval('municipio_seq', max(id)) FROM municipio;
SELECT setval('organizacion_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM organizacion) AS s;
SELECT setval('pconsolidado_seq', max(no_columna)) FROM pconsolidado;
SELECT setval('persona_seq', max(id)) FROM persona;
SELECT setval('prensa_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM prensa) AS s;
SELECT setval('presponsable_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM presponsable) AS s;
SELECT setval('profesion_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM profesion) AS s;
SELECT setval('rangoedad_seq', max(id)) FROM rangoedad;
SELECT setval('region_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM region) AS s;
SELECT setval('resagresion_seq', max(id)) FROM resagresion;
SELECT setval('sectorsocial_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM sectorsocial) AS s;
SELECT setval('tsitio_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM tsitio) AS s;
SELECT setval('ubicacion_seq', max(id)) FROM ubicacion;
SELECT setval('vinculoestado_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM vinculoestado) AS s;


