
SET client_encoding = 'LATIN1';

SELECT setval('combatiente_seq', max(id)) FROM combatiente;
