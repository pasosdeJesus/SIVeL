
SET client_encoding = 'UTF8';

SELECT setval('combatiente_seq', max(id)) FROM combatiente;
