
SET client_encoding = 'UTF8';

SELECT setval('etiqueta_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM etiqueta) AS s;

