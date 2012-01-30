
-- Ingeniería inversa de datos a la base empleada en el "Banco de Datos 
-- de derechos humanos y violencia política" de 1998 a 2000. 
-- Las llaves primarias y la integridad referencial se dedujeron a partir
-- de los datos.  
-- Dominio público. Sin garantias. 2004. vtamara@pasosdeJesus.org

CREATE TABLE tiposvdih (
		tpvdih_codigo CHAR(1) PRIMARY KEY,
		tpvdih_nombre CHAR(40) NOT NULL
); -- 4

CREATE TABLE regiones (
		region_codigo CHAR(2) PRIMARY KEY,
		region_nombre CHAR(40) NOT NULL,
		region_nomcor CHAR(20)
); -- 14

CREATE TABLE departamentos(
		deptos_codigo CHAR(2) PRIMARY KEY,
		deptos_nombre CHAR(30) NOT NULL,
		deptos_nomcor CHAR(15)
); -- 34

CREATE TABLE municipios (
		mncpio_depto CHAR(2) REFERENCES departamentos,
		mncpio_codigo CHAR(3) ,
		mncpio_nombre CHAR(50) NOT NULL,
		mncpio_nomcor CHAR(23),
		PRIMARY KEY (mncpio_depto, mncpio_codigo)
); -- 1101


CREATE TABLE clasecrrgmnto (
	clacrrg_codigo CHAR(3) PRIMARY KEY, 
	claccr_nombre CHAR(40)
); -- 11

CREATE TABLE corregimientos (
		crrgmnto_depto CHAR(2) REFERENCES departamentos,
		crrgmnto_mncpio CHAR(3) NOT NULL,
		crrgmnto_codigo CHAR(3) NOT NULL, 
		crrgmnto_nombre CHAR(60), 
		crrgmnto_nomcor CHAR(30), 
		crrgmnt_clase CHAR(3) REFERENCES clasecrrgmnto,
		FOREIGN KEY (crrgmnto_depto, crrgmnto_mncpio) REFERENCES
			municipios (mncpio_depto, mncpio_codigo),
		PRIMARY KEY (crrgmnto_depto, crrgmnto_mncpio, crrgmnto_codigo)
); --9537

CREATE TABLE fuente (
		fuente_codigo CHAR(10) PRIMARY KEY,
		fuente_nombre CHAR(40)
); -- 41

CREATE TABLE abusos(
		abuso_codigo CHAR(3) PRIMARY KEY, 
		abuso_nombre CHAR(40)
); --6

CREATE TABLE tpvdihsucesos (
		tpvsuc_tpvdih CHAR(1) REFERENCES tiposvdih,
		tpvsuc_codigo CHAR(3) NOT NULL,
		tpvsuc_nombre CHAR(50),
		tpvsuc_abuso CHAR(3) REFERENCES abusos,
		PRIMARY KEY (tpvsuc_tpvdih, tpvsuc_codigo)
); -- 81

CREATE TABLE responsables (
		respon_codigo CHAR(2) PRIMARY KEY,
		respon_nombre CHAR(40) NOT NULL,
		respon_subdivision CHAR(1)
); -- 90

CREATE TABLE responsubdivi (
		ressub_codres CHAR(2) REFERENCES responsables,
		ressub_codigo CHAR(2),
		ressub_nombre CHAR(30) NOT NULL,
		PRIMARY KEY (ressub_codres, ressub_codigo)
); -- 19

CREATE TABLE contexto (
	contex_codigo CHAR(2) PRIMARY KEY,
	contex_nombre CHAR(40) NOT NULL
); -- 29


CREATE TABLE usuario (
		codigo CHAR(4) PRIMARY KEY, 
		apellidos CHAR(20) NOT NULL,
		nombres CHAR(20) NOT NULL,
		clave CHAR(10) NOT NULL,
		nivel CHAR(1) NOT NULL,
		activo CHAR(1) NOT NULL
); -- 19


CREATE TABLE sucesos (
	suceso_codigo INTEGER PRIMARY KEY,
	suceso_tpvdih CHAR(1) REFERENCES tiposvdih,
	suceso_region CHAR(2) REFERENCES regiones,
	suceso_depto CHAR(2) REFERENCES departamentos,
	suceso_mncpio CHAR(3),
	suceso_crrgmnto CHAR(3),
	suceso_clase CHAR(5),
	suceso_lugar CHAR(50),
	suceso_sitio CHAR(50),
	suceso_rural CHAR(1),
	suceso_fecha DATE,
	suceso_hora TIME, 
	suceso_fuente CHAR(10) REFERENCES fuente,
	suceso_descfuente CHAR(10),
	suceso_fecfuente DATE,
	suceso_tpvsuc CHAR(3),
	suceso_abuso CHAR(3) REFERENCES abusos,
	suceso_respon CHAR(2) REFERENCES responsables,
	suceso_ressub CHAR(2),
	suceso_nvl_inform CHAR(1),
	suceso_nvl_escala CHAR(2),
	suceso_nvl_confia CHAR(2),
	suceso_nvl_impuni CHAR(1),
	suceso_usuario CHAR(4) REFERENCES usuario,
	suceso_fecha_reg DATE,
	suceso_hora_reg TIME,
	suceso_victimas SMALLINT,
	suceso_contexto CHAR(2) REFERENCES contexto,
	suceso_memo text,
	FOREIGN KEY (suceso_depto, suceso_mncpio) REFERENCES 
	municipios(mncpio_depto, mncpio_codigo),
	FOREIGN KEY (suceso_depto, suceso_mncpio, suceso_crrgmnto) REFERENCES 
	corregimientos(crrgmnto_depto, crrgmnto_mncpio, crrgmnto_codigo),
	FOREIGN KEY (suceso_respon, suceso_ressub) REFERENCES
	responsubdivi(ressub_codres, ressub_codigo),
	FOREIGN KEY (suceso_tpvdih, suceso_tpvsuc) REFERENCES
	tpvdihsucesos (tpvsuc_tpvdih, tpvsuc_codigo)
);  -- 9723


CREATE TABLE tipoorganiza (
		tiporg_codigo CHAR(2) PRIMARY KEY,
		tiporg_nombre CHAR(20),
		tiporg_tipo CHAR(1)
); -- 14

CREATE TABLE orgarmada (
 orgarm_nombre CHAR(30) PRIMARY KEY); -- 25


-- Parece que identifica con precisión uno de los actores en una acción bélica

-- Problema suceso 04 consec 01 tiporg
CREATE TABLE acc_belicas(
	accbel_suceso INTEGER REFERENCES sucesos,
	accbel_tiporg CHAR(2) REFERENCES tipoorganiza, 
	accbel_consec SMALLINT, 
	accbel_diveje CHAR(10),
	accbel_brigada CHAR(10),
	accbel_orgarm CHAR(30) REFERENCES orgarmada, 
	accbel_batfren CHAR(30),
	accbel_policia CHAR(1),
	accbel_polidepto CHAR(2),
	PRIMARY KEY (accbel_suceso, accbel_tiporg, accbel_consec)
); -- 4320 


-- Parece que indica cantidad de afectados de un tipo de org en una acción bélica
CREATE TABLE accbelestadis(
	accest_suceso INTEGER REFERENCES sucesos,
	accest_tiporg CHAR(2) REFERENCES tipoorganiza, 
	acest_muertos INTEGER, 
	accest_heridos INTEGER, 
	accest_retenidos INTEGER, 
	accest_desapare INTEGER,
	PRIMARY KEY (accest_suceso, accest_tiporg)
); -- 1747



CREATE TABLE clasepoblado (
	clapob_codigo CHAR(10) PRIMARY KEY,
	clapob_nombre CHAR(40)
); -- 8


CREATE TABLE divipol(
 divpol_codigo CHAR(8) PRIMARY KEY,
 divpol_depto CHAR(15) REFERENCES departamentos,
 divpol_ciudad CHAR(28),
 divpol_crrgmnt CHAR(55) REFERENCES corregimientos,
 divpol_tipo CHAR(15) REFERENCES clasecrrgmnto
); -- 9514


CREATE TABLE orgsociopolitica (
 orgsocpol_nombre CHAR(30) PRIMARY KEY
); -- 16


CREATE TABLE sucesofuente (
 sucfue_suceso INTEGER REFERENCES sucesos,
 sucfue_fuente CHAR(10) REFERENCES fuente,
 sucfue_descfuente CHAR(10),
 sucfue_fecha date,
 PRIMARY KEY (sucfue_suceso, sucfue_fuente, sucfue_descfuente)
); -- 14709


CREATE TABLE sucesomodalidad (
 sucmod_codigo INTEGER REFERENCES sucesos,
 sucmod_tpvdih CHAR(1) REFERENCES tiposvdih,
 sucmod_tpvsuc CHAR(3),
 sucmod_abuso CHAR(3) REFERENCES abusos,
 sucmod_respon CHAR(2) REFERENCES responsables,
 sucmod_ressub CHAR(2),
 FOREIGN KEY (sucmod_tpvdih, sucmod_tpvsuc) REFERENCES 
	tpvdihsucesos (tpvsuc_tpvdih, tpvsuc_codigo)
); --  15753


CREATE TABLE filiapolitica (
 filpol_nombre CHAR(30) PRIMARY KEY
); -- 7


CREATE TABLE identidadsocial(
 idesoc_nombre CHAR(30) PRIMARY KEY
); -- 14


CREATE TABLE profoficio (
 proofi_nombre CHAR(30) PRIMARY KEY
); -- 27


CREATE TABLE sectorsocial (
 secsoc_nombre CHAR(30) PRIMARY KEY
); -- 12


CREATE TABLE tipovictima (
 tipvic_codigo CHAR(2) PRIMARY KEY,
 tipvic_nombre CHAR(30)
); -- 0


CREATE TABLE vinculos (
 vinculo_rama CHAR(40) NOT NULL,
 vinculo_nombre CHAR(30) PRIMARY KEY
); --28



CREATE TABLE victimas (
 victim_suceso INTEGER REFERENCES sucesos,
 victim_consec SMALLINT NOT NULL,
 victim_nombre CHAR(25),
 victim_apellido CHAR(25),
 victim_sexo CHAR(1),
 victim_edad SMALLINT,
 victim_rango CHAR(4),
 victim_hijos SMALLINT,
 victim_sector CHAR(30) REFERENCES sectorsocial,
 victim_profesion CHAR(30) REFERENCES profoficio,
 victim_relacion CHAR(30),
 victim_orgnzcion CHAR(30) REFERENCES orgsociopolitica,
 victim_filiacion CHAR(30) REFERENCES filiapolitica,
 victim_orgarmada CHAR(30) REFERENCES orgarmada,
 victim_antcdntes CHAR(30),
 victim_identidad CHAR(30),
 victim_tiporg CHAR(2) REFERENCES tipoorganiza,
 victim_tipo CHAR(2) REFERENCES tipovictima,
 PRIMARY KEY (victim_suceso, victim_consec)
); -- 30680


CREATE TABLE victimmodalidad (
 vicmod_suceso INTEGER REFERENCES sucesos,
 vicmod_consec SMALLINT NOT NULL,
 vicmod_tpvdih CHAR(1) REFERENCES tiposvdih,
 vicmod_tpvsuc CHAR(3),
 vicmod_abuso CHAR(3) REFERENCES abusos,
 vicmod_respon CHAR(2) REFERENCES responsables,
 vicmod_ressub CHAR(2),
 vicmod_tipbel CHAR(3),
 PRIMARY KEY (vicmod_suceso, vicmod_consec, vicmod_tpvdih, vicmod_tpvsuc),
 FOREIGN KEY (vicmod_suceso, vicmod_consec) REFERENCES 
	victimas (victim_suceso, victim_consec),
 FOREIGN KEY (vicmod_tpvdih, vicmod_tpvsuc) REFERENCES 
	tpvdihsucesos (tpvsuc_tpvdih, tpvsuc_codigo)
); -- 38487 


