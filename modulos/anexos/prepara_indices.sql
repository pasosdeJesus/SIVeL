
SET client_encoding = 'LATIN1';

SELECT setval('anexo_seq', max(id)) FROM anexo;
