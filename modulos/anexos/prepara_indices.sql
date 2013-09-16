
SET client_encoding = 'UTF8';

SELECT setval('anexo_seq', max(id)) FROM anexo;
