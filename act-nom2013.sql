-- Actualización para SIVeL de nombres de departamentos, municipios y centros poblados de Colombia
-- Informacion consultada en DIVPOLA 2012 y 2013 (30.Jun.2013) http://www.dane.gov.co/Divipola/
-- vtamara@pasosdeJesus.org. 2013. Dominio público.


-- NUEVAS CORRECCIONES A DIVIPOLA
-- 76001045,"LAS PALMAS-LA CASTILLA" -> "LAS PALMAS - LA CASTILLA"


SET CLIENT_ENCODING='UTF8';

-- Devolvieron cambio 2012, TEBF vuelven a ser CD
UPDATE tclase SET fechadeshabilitacion=NULL WHERE id='CD';
UPDATE tclase SET fechadeshabilitacion='2013-11-13' WHERE id='TEBF';


-- Centros poblados deshabilitados 71
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='5' AND id_municipio='895' AND id='2'; -- EL REAL
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='5' AND id_municipio='895' AND id='7'; -- COLOMBIA
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='41' AND id_municipio='530' AND id='1'; -- ALTO DE LA CRUZ
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='52' AND id_municipio='560' AND id='5'; -- LA PALMA
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='52' AND id_municipio='560' AND id='8'; -- LA CENTINELA
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='54' AND id_municipio='720' AND id='9'; -- LA CRISTALINA
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='54' AND id_municipio='720' AND id='10'; -- PARAMILLO
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='54' AND id_municipio='720' AND id='11'; -- RÍO NUEVO
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='54' AND id_municipio='720' AND id='13'; -- EL HIGUERÓN
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='54' AND id_municipio='720' AND id='14'; -- EL RIECITO
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='54' AND id_municipio='720' AND id='16'; -- FÁTIMA
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='54' AND id_municipio='720' AND id='17'; -- JORDANCITO
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='54' AND id_municipio='720' AND id='18'; -- LA ESMERALDA
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='54' AND id_municipio='720' AND id='20'; -- LA PRIMAVERA
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='54' AND id_municipio='720' AND id='22'; -- LOS GUAMOS
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='54' AND id_municipio='720' AND id='23'; -- PLANADAS
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='54' AND id_municipio='720' AND id='25'; -- SAN ISIDRO
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='54' AND id_municipio='720' AND id='26'; -- SAN SEBASTIÁN
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='54' AND id_municipio='720' AND id='27'; -- LA CARTAGENA
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='63' AND id_municipio='401' AND id='4'; -- FUNDACION AMANECER
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='70' AND id_municipio='771' AND id='2'; -- BAJOGRANDE
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='70' AND id_municipio='771' AND id='29'; -- BUENOS AIRES
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='70' AND id_municipio='771' AND id='30'; -- CACAGUAL
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='70' AND id_municipio='771' AND id='33'; -- CUCHARAL
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='70' AND id_municipio='771' AND id='38'; -- FUNDACIÓN
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='70' AND id_municipio='771' AND id='40'; -- GARRAPATA
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='70' AND id_municipio='771' AND id='41'; -- GUAMALITO
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='70' AND id_municipio='771' AND id='43'; -- LA REDONDA
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='70' AND id_municipio='771' AND id='46'; -- MOJANITA
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='70' AND id_municipio='771' AND id='47'; -- PUEBLO NUEVO
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='70' AND id_municipio='771' AND id='49'; -- SAN CAYETANO
UPDATE clase SET fechadeshabilitacion='2013-11-14' WHERE id_departamento='76' AND id_municipio='736' AND id='17'; -- TRES ESQUINAS
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=05 AND id_municipio=576 AND id=002;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=05 AND id_municipio=576 AND id=003;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=15 AND id_municipio=367 AND id=001;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=15 AND id_municipio=494 AND id=001;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=23 AND id_municipio=466 AND id=010;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=23 AND id_municipio=466 AND id=011;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=23 AND id_municipio=466 AND id=012;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=23 AND id_municipio=466 AND id=016;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=23 AND id_municipio=466 AND id=020;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=23 AND id_municipio=466 AND id=024;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=23 AND id_municipio=466 AND id=025;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=23 AND id_municipio=466 AND id=032;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=23 AND id_municipio=466 AND id=034;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=23 AND id_municipio=660 AND id=027;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=23 AND id_municipio=660 AND id=042;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=25 AND id_municipio=740 AND id=009;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=52 AND id_municipio=051 AND id=010;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=52 AND id_municipio=051 AND id=013;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=52 AND id_municipio=051 AND id=015;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=52 AND id_municipio=051 AND id=016;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=52 AND id_municipio=585 AND id=003;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=52 AND id_municipio=585 AND id=005;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=52 AND id_municipio=585 AND id=006;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=54 AND id_municipio=720 AND id=008;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=68 AND id_municipio=686 AND id=001;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=68 AND id_municipio=686 AND id=003;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=68 AND id_municipio=720 AND id=003;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=76 AND id_municipio=111 AND id=003;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=76 AND id_municipio=111 AND id=004;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=76 AND id_municipio=111 AND id=009;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=76 AND id_municipio=111 AND id=017;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=76 AND id_municipio=111 AND id=019;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=76 AND id_municipio=111 AND id=028;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=86 AND id_municipio=885 AND id=003;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=94 AND id_municipio=001 AND id=002;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=94 AND id_municipio=001 AND id=005;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=94 AND id_municipio=001 AND id=007;
UPDATE clase SET fechadeshabilitacion='2013-11-13' WHERE id_departamento=94 AND id_municipio=001 AND id=008;

-- Centros poblados rehabilitados 9

UPDATE clase SET fechadeshabilitacion=NULL WHERE id_departamento=5 AND id_municipio=313 AND id=4; -- LOS MEDIOS
UPDATE clase SET fechadeshabilitacion=NULL WHERE id_departamento=23 AND id_municipio=660 AND id=34; -- SAN ANDRESITO
UPDATE clase SET fechadeshabilitacion=NULL WHERE id_departamento=23 AND id_municipio=660 AND id=36; -- EL ORGULLO
UPDATE clase SET fechadeshabilitacion=NULL WHERE id_departamento=52 AND id_municipio=51 AND id=5; -- EL EMPATE
UPDATE clase SET fechadeshabilitacion=NULL, id_tclase='C' WHERE id_departamento=52 AND id_municipio=435 AND id=3; -- EL GUABO
UPDATE clase SET fechadeshabilitacion=NULL WHERE id_departamento=52 AND id_municipio=435 AND id=8; -- SAN MIGUEL
UPDATE clase SET fechadeshabilitacion=NULL where id='6' AND id_municipio='720' AND id_departamento='54'; -- SAN MARTÍN DE LOBA
UPDATE clase SET fechadeshabilitacion=NULL where id='0' AND id_municipio='235' AND id_departamento='70'; -- GALERAS
UPDATE clase SET fechadeshabilitacion=NULL where id='7' AND id_municipio='771' AND id_departamento='70'; -- CHAPARRAL

-- Nuevos centros poblados 27
INSERT INTO clase (id, id_municipio, id_departamento, nombre, id_tclase, fechacreacion) VALUES ('4', '286', '25', 'CENTRO AGROINDUSTRIAL', 'CP', '2013-11-13');
INSERT INTO clase (id, id_municipio, id_departamento, nombre, id_tclase, fechacreacion) VALUES ('5', '286', '25', 'TERMINAL DE CARGA', 'CP', '2013-11-13');
INSERT INTO clase (id, id_municipio, id_departamento, nombre, id_tclase, fechacreacion) VALUES ('65', '1', '54', 'EL PORVENIR', 'CP', '2013-11-13');
INSERT INTO clase (id, id_municipio, id_departamento, nombre, id_tclase, fechacreacion) VALUES ('66', '1', '54', 'LA CHINA', 'CP', '2013-11-13');
INSERT INTO clase (id, id_municipio, id_departamento, nombre, id_tclase, fechacreacion) VALUES ('12', '320', '86', 'EMPALME', 'CP', '2013-11-13');
INSERT INTO clase (id, id_municipio, id_departamento, nombre, id_tclase, fechacreacion) VALUES ('23', '773', '99', 'WERIMA', 'IP', '2013-11-13');
INSERT INTO clase (id, id_departamento, id_municipio, nombre, id_tclase, fechacreacion) VALUES (017, 23, 466, 'SITIO NUEVO', 'CAS', '2013-11-13');
INSERT INTO clase (id, id_departamento, id_municipio, nombre, id_tclase, fechacreacion) VALUES (037, 23, 466, 'VILLA CARMINIA', 'CP', '2013-11-13');
INSERT INTO clase (id, id_departamento, id_municipio, nombre, id_tclase, fechacreacion) VALUES (048, 23, 660, 'KILÓMETRO 34', 'CAS', '2013-11-13');
INSERT INTO clase (id, id_departamento, id_municipio, nombre, id_tclase, fechacreacion) VALUES (055, 23, 660, 'LAS CUMBRES', 'CAS', '2013-11-13');
INSERT INTO clase (id, id_departamento, id_municipio, nombre, id_tclase, fechacreacion) VALUES (069, 23, 660, 'TREMENTINO BULERO', 'CP', '2013-11-13');
INSERT INTO clase (id, id_departamento, id_municipio, nombre, id_tclase, fechacreacion) VALUES (012, 25, 740, 'LA UNIÓN SECTOR LA UNIÓN', 'CP', '2013-11-13');
INSERT INTO clase (id, id_departamento, id_municipio, nombre, id_tclase, fechacreacion) VALUES (013, 25, 740, 'LA UNIÓN SECTOR PIE DE ALTO', 'CP', '2013-11-13');
INSERT INTO clase (id, id_departamento, id_municipio, nombre, id_tclase, fechacreacion) VALUES (014, 25, 740, 'SAN BENITO SECTOR JAZMÍN', 'CP', '2013-11-13');
INSERT INTO clase (id, id_departamento, id_municipio, nombre, id_tclase, fechacreacion) VALUES (009, 52, 435, 'EL CARMELO', 'CP', '2013-11-13');
INSERT INTO clase (id, id_departamento, id_municipio, nombre, id_tclase, fechacreacion) VALUES (010, 52, 435, 'EL ARCO', 'CP', '2013-11-13');
INSERT INTO clase (id, id_departamento, id_municipio, nombre, id_tclase, fechacreacion) VALUES (011, 52, 435, 'EL ARENAL', 'CP', '2013-11-13');
INSERT INTO clase (id, id_departamento, id_municipio, nombre, id_tclase, fechacreacion) VALUES (013, 52, 885, 'ZARAGOZA', 'IPM', '2013-11-13');
INSERT INTO clase (id, id_departamento, id_municipio, nombre, id_tclase, fechacreacion) VALUES (020, 52, 885, 'INANTAS BAJO', 'CP', '2013-11-13');
INSERT INTO clase (id, id_departamento, id_municipio, nombre, id_tclase, fechacreacion) VALUES (039, 76, 001, 'ALTOS DE NORMANDIA - SECTOR TRES CRUCES BAJO', 'CP', '2013-11-13');
INSERT INTO clase (id, id_departamento, id_municipio, nombre, id_tclase, fechacreacion) VALUES (040, 76, 001, 'ALTOS DE NORMANDIA-LA ERMITA', 'CP', '2013-11-13');
INSERT INTO clase (id, id_departamento, id_municipio, nombre, id_tclase, fechacreacion) VALUES (047, 76, 001, 'LA FONDA', 'CP', '2013-11-13');
INSERT INTO clase (id, id_departamento, id_municipio, nombre, id_tclase, fechacreacion) VALUES (048, 76, 001, 'LOS LIMONES', 'CP', '2013-11-13');
INSERT INTO clase (id, id_departamento, id_municipio, nombre, id_tclase, fechacreacion) VALUES (049, 76, 001, 'MORGAN', 'CP', '2013-11-13');



-- Correcciones a nombres de municipios 1

UPDATE municipio SET nombre='COLOSÓ (RICAURTE)' WHERE id='204' AND id_departamento='70'; -- antes COLOSO (RICAURTE) (RICAURTE)

-- Correcciones a nombres de centros poblados  114

UPDATE clase SET nombre='PUERTO OSPINA (EL RAUDAL)' WHERE id='11' AND id_municipio='1' AND id_departamento='95'; -- antes PUERTO OSPINA ( EL RAUDAL )

UPDATE clase SET nombre='RICAURTE (COLOSÓ)' WHERE id='0' AND id_municipio='204' AND id_departamento='70'; -- antes RICAURTE (COLOSO)
UPDATE clase SET nombre='SAN MARTÍN DE LOBA' where id='6' AND id_municipio='720' AND id_departamento='54'; -- antes SAN MARTIN DE LOBA

UPDATE clase SET nombre='SANTA FÉ DE ANTIOQUIA', id_tclase='CM' WHERE id_departamento=05 AND id_municipio=042 AND id=000;
UPDATE clase SET nombre='SAN JOSÉ DE APARTADÓ', id_tclase='C' WHERE id_departamento=05 AND id_municipio=045 AND id=001;
UPDATE clase SET nombre='LAS PLATAS (SANTAFÉ)', id_tclase='IPD' WHERE id_departamento=05 AND id_municipio=051 AND id=010;
UPDATE clase SET nombre='BERLÍN (PUEBLO NUEVO)', id_tclase='C' WHERE id_departamento=05 AND id_municipio=107 AND id=001;
UPDATE clase SET nombre='EL JARDÍN (TAMANÁ)', id_tclase='C' WHERE id_departamento=05 AND id_municipio=120 AND id=002;
UPDATE clase SET nombre='LA MERCED (PLAYÓN)', id_tclase='C' WHERE id_departamento=05 AND id_municipio=411 AND id=003;
UPDATE clase SET nombre='PUERTO VENUS (SAMANÁ)', id_tclase='C' WHERE id_departamento=05 AND id_municipio=483 AND id=001;
UPDATE clase SET nombre='OTÚ', id_tclase='CAS' WHERE id_departamento=05 AND id_municipio=604 AND id=005;
UPDATE clase SET nombre='BOGOTÁ, D.C.', id_tclase='CM' WHERE id_departamento=11 AND id_municipio=001 AND id=000;
UPDATE clase SET nombre='PUERTO BADEL (CAÑO SALADO)', id_tclase='C' WHERE id_departamento=13 AND id_municipio=052 AND id=001;
UPDATE clase SET nombre='LA VICTORIA (SEPULTURA)', id_tclase='CAS' WHERE id_departamento=13 AND id_municipio=160 AND id=011;
UPDATE clase SET nombre='LA MONTAÑA DE ALONSO (MARTÍN ALONSO)', id_tclase='CAS' WHERE id_departamento=13 AND id_municipio=212 AND id=003;
UPDATE clase SET nombre='GUAYABAL (FÁTIMA)', id_tclase='IPD' WHERE id_departamento=15 AND id_municipio=599 AND id=001;
UPDATE clase SET nombre='SAN JUAN GUADUA', id_tclase='CP' WHERE id_departamento=19 AND id_municipio=050 AND id=017;
UPDATE clase SET nombre='EL ALBA (PARAISO, LOS MANGOS)', id_tclase='C' WHERE id_departamento=19 AND id_municipio=142 AND id=013;
UPDATE clase SET nombre='ALFONSO LÓPEZ (BALSITAS)', id_tclase='IPD' WHERE id_departamento=19 AND id_municipio=318 AND id=001;
UPDATE clase SET nombre='BENJAMÍN HERRERA (SAN VICENTE)', id_tclase='IPD' WHERE id_departamento=19 AND id_municipio=318 AND id=002;
UPDATE clase SET nombre='EL PLAYÓN (SIGUÍ)', id_tclase='IPD' WHERE id_departamento=19 AND id_municipio=418 AND id=003;
UPDATE clase SET nombre='RÍO MAYA (DOS QUEBRADAS)', id_tclase='IPD' WHERE id_departamento=19 AND id_municipio=418 AND id=011;
UPDATE clase SET nombre='TACUEYO', id_tclase='IPD' WHERE id_departamento=19 AND id_municipio=821 AND id=007;
UPDATE clase SET nombre='MARIANGOLA', id_tclase='C' WHERE id_departamento=20 AND id_municipio=001 AND id=013;
UPDATE clase SET nombre='NABUSIMAKE (SAN SEBASTIÁN)', id_tclase='CAS' WHERE id_departamento=20 AND id_municipio=570 AND id=004;
UPDATE clase SET nombre='EL MARQUEZ', id_tclase='C' WHERE id_departamento=20 AND id_municipio=614 AND id=001;
UPDATE clase SET nombre='SAN JOSÉ DE LAS AMÉRICAS', id_tclase='C' WHERE id_departamento=20 AND id_municipio=770 AND id=005;
UPDATE clase SET nombre='PICA PICA NUEVO', id_tclase='C' WHERE id_departamento=23 AND id_municipio=466 AND id=008;
UPDATE clase SET nombre='PURÍSIMA DE LA CONCEPCIÓN', id_tclase='CM' WHERE id_departamento=23 AND id_municipio=586 AND id=000;
UPDATE clase SET nombre='GUAYABAL LA YE', id_tclase='C' WHERE id_departamento=23 AND id_municipio=660 AND id=008;
UPDATE clase SET nombre='RODANIA (RODÁCULO)', id_tclase='C' WHERE id_departamento=23 AND id_municipio=660 AND id=010;
UPDATE clase SET nombre='EL ROBLE', id_tclase='C' WHERE id_departamento=23 AND id_municipio=660 AND id=020;
UPDATE clase SET nombre='GUÁIMARITO', id_tclase='C' WHERE id_departamento=23 AND id_municipio=660 AND id=028;
UPDATE clase SET nombre='SAN ANDRESITO', id_tclase='CAS' WHERE id_departamento=23 AND id_municipio=660 AND id=034;
UPDATE clase SET nombre='LOS AMARILLOS', id_tclase='C' WHERE id_departamento=23 AND id_municipio=660 AND id=065;
UPDATE clase SET nombre='SIMÓN BOLÍVAR', id_tclase='CAS' WHERE id_departamento=25 AND id_municipio=120 AND id=004;
UPDATE clase SET nombre='EL PLOMO (EL PARAÍSO)', id_tclase='IPM' WHERE id_departamento=25 AND id_municipio=518 AND id=003;
UPDATE clase SET nombre='SAN BENITO CENTRO', id_tclase='CP' WHERE id_departamento=25 AND id_municipio=740 AND id=004;
UPDATE clase SET nombre='CHACUA CENTRO', id_tclase='CP' WHERE id_departamento=25 AND id_municipio=740 AND id=005;
UPDATE clase SET nombre='SAN MIGUEL', id_tclase='CP' WHERE id_departamento=25 AND id_municipio=740 AND id=011;
UPDATE clase SET nombre='BOCA DE NAURITÁ (NAURITÁ)', id_tclase='C' WHERE id_departamento=27 AND id_municipio=001 AND id=044;
UPDATE clase SET nombre='BOCA DE NEMOTÁ (NEMOTÁ)', id_tclase='C' WHERE id_departamento=27 AND id_municipio=001 AND id=052;
UPDATE clase SET nombre='PACURITA (CABÍ)', id_tclase='C' WHERE id_departamento=27 AND id_municipio=001 AND id=054;
UPDATE clase SET nombre='PIE DE PATO', id_tclase='CM' WHERE id_departamento=27 AND id_municipio=025 AND id=000;
UPDATE clase SET nombre='SAN JOSÉ DE PURRÉ', id_tclase='C' WHERE id_departamento=27 AND id_municipio=050 AND id=006;
UPDATE clase SET nombre='SAN MARTÍN DE PURRÉ', id_tclase='C' WHERE id_departamento=27 AND id_municipio=050 AND id=007;
UPDATE clase SET nombre='CIUDAD MÚTIS', id_tclase='CM' WHERE id_departamento=27 AND id_municipio=075 AND id=000;
UPDATE clase SET nombre='SAN JOSÉ DE BUEY (ALTO BUEY)', id_tclase='C' WHERE id_departamento=27 AND id_municipio=425 AND id=007;
UPDATE clase SET nombre='SAN JOSÉ DE QUERÁ', id_tclase='C' WHERE id_departamento=27 AND id_municipio=430 AND id=012;
UPDATE clase SET nombre='CHILLURCO (VILLAS DEL NORTE)', id_tclase='C' WHERE id_departamento=41 AND id_municipio=551 AND id=006;
UPDATE clase SET nombre='CANDELARIA (CAIMÁN)', id_tclase='C' WHERE id_departamento=47 AND id_municipio=161 AND id=002;
UPDATE clase SET nombre='CONCEPCIÓN (COCO)', id_tclase='C' WHERE id_departamento=47 AND id_municipio=161 AND id=003;
UPDATE clase SET nombre='JESÚS DEL MONTE (MICO)', id_tclase='C' WHERE id_departamento=47 AND id_municipio=161 AND id=005;
UPDATE clase SET nombre='SAN JOSÉ DE PREVENCIÓN', id_tclase='C' WHERE id_departamento=47 AND id_municipio=545 AND id=003;
UPDATE clase SET nombre='LAS MULAS (SAN ROQUE)', id_tclase='C' WHERE id_departamento=47 AND id_municipio=660 AND id=009;
UPDATE clase SET nombre='RINCÓN BOLÍVAR', id_tclase='IPD' WHERE id_departamento=50 AND id_municipio=689 AND id=004;
UPDATE clase SET nombre='ROSAFLORIDA (CÁRDENAS)', id_tclase='IPM' WHERE id_departamento=52 AND id_municipio=051 AND id=001;
UPDATE clase SET nombre='EL EMPATE', id_tclase='CAS' WHERE id_departamento=52 AND id_municipio=051 AND id=005;
UPDATE clase SET nombre='LA COCHA', id_tclase='C' WHERE id_departamento=52 AND id_municipio=051 AND id=006;
UPDATE clase SET nombre='LA CAÑADA', id_tclase='C' WHERE id_departamento=52 AND id_municipio=051 AND id=009;
UPDATE clase SET nombre='SANTA TERESA', id_tclase='C' WHERE id_departamento=52 AND id_municipio=051 AND id=014;
UPDATE clase SET nombre='LA CALDERA', id_tclase='C' WHERE id_departamento=52 AND id_municipio=480 AND id=001;
UPDATE clase SET nombre='ALFONSO LÓPEZ PUMAREJO (FLORIDA)', id_tclase='IPD' WHERE id_departamento=52 AND id_municipio=490 AND id=001;
UPDATE clase SET nombre='SIMÓN BOLÍVAR', id_tclase='C' WHERE id_departamento=52 AND id_municipio=520 AND id=005;
UPDATE clase SET nombre='SAN ROQUE (BUENAVISTA)', id_tclase='IPD' WHERE id_departamento=52 AND id_municipio=540 AND id=003;
UPDATE clase SET nombre='PIRI (PARAÍSO)', id_tclase='C' WHERE id_departamento=52 AND id_municipio=621 AND id=015;
UPDATE clase SET nombre='AGUADA', id_tclase='CAS' WHERE id_departamento=52 AND id_municipio=885 AND id=007;
UPDATE clase SET nombre='CÁCHIRA', id_tclase='CM' WHERE id_departamento=54 AND id_municipio=128 AND id=000;
UPDATE clase SET nombre='EL CARMEN DE NAZARETH', id_tclase='C' WHERE id_departamento=54 AND id_municipio=660 AND id=001;
UPDATE clase SET nombre='LA VENTA', id_tclase='C' WHERE id_departamento=68 AND id_municipio=271 AND id=001;
UPDATE clase SET nombre='SAN ANTONIO DE LEONES', id_tclase='CP' WHERE id_departamento=68 AND id_municipio=271 AND id=003;
UPDATE clase SET nombre='OTROMUNDO', id_tclase='CP' WHERE id_departamento=68 AND id_municipio=271 AND id=007;
UPDATE clase SET nombre='LA ARAGUA', id_tclase='CP' WHERE id_departamento=68 AND id_municipio=720 AND id=001;
UPDATE clase SET nombre='CACHIPAY', id_tclase='CP' WHERE id_departamento=68 AND id_municipio=720 AND id=002;
UPDATE clase SET nombre='PLAN DE ALVAREZ', id_tclase='CP' WHERE id_departamento=68 AND id_municipio=720 AND id=004;
UPDATE clase SET nombre='SAN JUAN BOSCO DE LA VERDE', id_tclase='CP' WHERE id_departamento=68 AND id_municipio=720 AND id=005;
UPDATE clase SET nombre='FLAMENGO', id_tclase='CP' WHERE id_departamento=76 AND id_municipio=001 AND id=038;
UPDATE clase SET nombre='LAS PALMAS - LA CASTILLA', id_tclase='CP' WHERE id_departamento=76 AND id_municipio=001 AND id=045;
UPDATE clase SET nombre='BOCAS DE MAYORQUIN', id_tclase='C' WHERE id_departamento=76 AND id_municipio=109 AND id=024;
UPDATE clase SET nombre='SAN ANTONIO (YURUMANGUÍ)', id_tclase='C' WHERE id_departamento=76 AND id_municipio=109 AND id=031;
UPDATE clase SET nombre='RÍO LORO/LA MESA', id_tclase='C' WHERE id_departamento=76 AND id_municipio=111 AND id=013;
UPDATE clase SET nombre='EL OVERO (SECTOR POBLADO)', id_tclase='C' WHERE id_departamento=76 AND id_municipio=113 AND id=004;
UPDATE clase SET nombre='DARIÉN', id_tclase='CM' WHERE id_departamento=76 AND id_municipio=126 AND id=000;
UPDATE clase SET nombre='SAN FRANCISCO (EL LLANITO)', id_tclase='C' WHERE id_departamento=76 AND id_municipio=275 AND id=012;
UPDATE clase SET nombre='POTOSÍ (PRIMAVERA)', id_tclase='IPM' WHERE id_departamento=81 AND id_municipio=065 AND id=013;
UPDATE clase SET nombre='LUCITANIA (CHURUYACO)', id_tclase='IP' WHERE id_departamento=86 AND id_municipio=320 AND id=004;
UPDATE clase SET nombre='SAN MARTÍN DE AMACAYACÚ', id_tclase='CAS' WHERE id_departamento=91 AND id_municipio=001 AND id=007;
UPDATE clase SET nombre='EL ENCANTO', id_tclase='CD' WHERE id_departamento=91 AND id_municipio=263 AND id=000;
UPDATE clase SET nombre='LA CHORRERA', id_tclase='CD' WHERE id_departamento=91 AND id_municipio=405 AND id=000;
UPDATE clase SET nombre='LA PEDRERA', id_tclase='CD' WHERE id_departamento=91 AND id_municipio=407 AND id=000;
UPDATE clase SET nombre='PACOA', id_tclase='CD' WHERE id_departamento=91 AND id_municipio=430 AND id=000;
UPDATE clase SET nombre='MIRITÍ', id_tclase='CD' WHERE id_departamento=91 AND id_municipio=460 AND id=000;
UPDATE clase SET nombre='PUERTO ALEGRÍA', id_tclase='CD' WHERE id_departamento=91 AND id_municipio=530 AND id=000;
UPDATE clase SET nombre='PUERTO ARICA', id_tclase='CD' WHERE id_departamento=91 AND id_municipio=536 AND id=000;
UPDATE clase SET nombre='PUERTO SANTANDER', id_tclase='CD' WHERE id_departamento=91 AND id_municipio=669 AND id=000;
UPDATE clase SET nombre='TARAPACÁ', id_tclase='CD' WHERE id_departamento=91 AND id_municipio=798 AND id=000;
UPDATE clase SET nombre='COCO VIEJO', id_tclase='CP' WHERE id_departamento=94 AND id_municipio=001 AND id=003;
UPDATE clase SET nombre='CHAQUITA', id_tclase='CP' WHERE id_departamento=94 AND id_municipio=001 AND id=009;
UPDATE clase SET nombre='COCO NUEVO', id_tclase='CP' WHERE id_departamento=94 AND id_municipio=001 AND id=010;
UPDATE clase SET nombre='BARRANCO TIGRE', id_tclase='CP' WHERE id_departamento=94 AND id_municipio=001 AND id=011;
UPDATE clase SET nombre='COAYARE', id_tclase='CP' WHERE id_departamento=94 AND id_municipio=001 AND id=012;
UPDATE clase SET nombre='YURÍ', id_tclase='CP' WHERE id_departamento=94 AND id_municipio=001 AND id=013;
UPDATE clase SET nombre='BARRANCO MINAS', id_tclase='CD' WHERE id_departamento=94 AND id_municipio=343 AND id=000;
UPDATE clase SET nombre='MAPIRIPANA', id_tclase='CD' WHERE id_departamento=94 AND id_municipio=663 AND id=000;
UPDATE clase SET nombre='SAN FELIPE', id_tclase='CD' WHERE id_departamento=94 AND id_municipio=883 AND id=000;
UPDATE clase SET nombre='PUERTO COLOMBIA', id_tclase='CD' WHERE id_departamento=94 AND id_municipio=884 AND id=000;
UPDATE clase SET nombre='SEJAL (MAHIMACHI)', id_tclase='IP' WHERE id_departamento=94 AND id_municipio=884 AND id=001;
UPDATE clase SET nombre='LA GUADALUPE', id_tclase='CD' WHERE id_departamento=94 AND id_municipio=885 AND id=000;
UPDATE clase SET nombre='CACAHUAL', id_tclase='CD' WHERE id_departamento=94 AND id_municipio=886 AND id=000;
UPDATE clase SET nombre='CAMPO ALEGRE', id_tclase='CD' WHERE id_departamento=94 AND id_municipio=887 AND id=000;
UPDATE clase SET nombre='MORICHAL NUEVO', id_tclase='CD' WHERE id_departamento=94 AND id_municipio=888 AND id=000;
UPDATE clase SET nombre='PACOA', id_tclase='CD' WHERE id_departamento=97 AND id_municipio=511 AND id=000;
UPDATE clase SET nombre='MORICHAL', id_tclase='CD' WHERE id_departamento=97 AND id_municipio=777 AND id=000;
UPDATE clase SET nombre='YAVARATÉ', id_tclase='CD' WHERE id_departamento=97 AND id_municipio=889 AND id=000;
