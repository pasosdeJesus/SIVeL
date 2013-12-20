--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- Name: es_co_utf_8; Type: COLLATION; Schema: public; Owner: -
--

CREATE COLLATION es_co_utf_8 (lc_collate = 'es_CO.UTF-8', lc_ctype = 'es_CO.UTF-8');


--
-- Name: unaccent; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS unaccent WITH SCHEMA public;


--
-- Name: EXTENSION unaccent; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION unaccent IS 'text search dictionary that removes accents';


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: acto; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE acto (
    id_presponsable integer NOT NULL,
    id_categoria integer NOT NULL,
    id_persona integer NOT NULL,
    id_caso integer NOT NULL
);


--
-- Name: actocolectivo; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE actocolectivo (
    id_presponsable integer NOT NULL,
    id_categoria integer NOT NULL,
    id_grupoper integer NOT NULL,
    id_caso integer NOT NULL
);


--
-- Name: actualizacionbase; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE actualizacionbase (
    id character varying(10) NOT NULL,
    fecha date NOT NULL,
    descripcion character varying(500) NOT NULL
);


--
-- Name: antecedente_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE antecedente_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: antecedente; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE antecedente (
    id integer DEFAULT nextval('antecedente_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    CONSTRAINT antecedente_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: antecedente_caso; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE antecedente_caso (
    id_antecedente integer NOT NULL,
    id_caso integer NOT NULL
);


--
-- Name: antecedente_comunidad; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE antecedente_comunidad (
    id_antecedente integer NOT NULL,
    id_grupoper integer NOT NULL,
    id_caso integer NOT NULL
);


--
-- Name: antecedente_victima; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE antecedente_victima (
    id_antecedente integer NOT NULL,
    id_persona integer NOT NULL,
    id_caso integer NOT NULL
);


--
-- Name: caso_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE caso_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: caso; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE caso (
    id integer DEFAULT nextval('caso_seq'::regclass) NOT NULL,
    titulo character varying(50),
    fecha date NOT NULL,
    hora character varying(10),
    duracion character varying(10),
    memo text NOT NULL,
    grconfiabilidad character varying(5),
    gresclarecimiento character varying(5),
    grimpunidad character varying(5),
    grinformacion character varying(5),
    bienes text,
    id_intervalo integer
);


--
-- Name: caso_categoria_presponsable; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE caso_categoria_presponsable (
    id_tviolencia character varying(1) NOT NULL,
    id_supracategoria integer NOT NULL,
    id_categoria integer NOT NULL,
    id integer NOT NULL,
    id_caso integer NOT NULL,
    id_presponsable integer NOT NULL
);


--
-- Name: caso_contexto; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE caso_contexto (
    id_caso integer NOT NULL,
    id_contexto integer NOT NULL
);


--
-- Name: caso_ffrecuente; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE caso_ffrecuente (
    fecha date NOT NULL,
    ubicacion character varying(100),
    clasificacion character varying(100),
    ubicacionfisica character varying(100),
    id_ffrecuente integer NOT NULL,
    id_caso integer NOT NULL
);


--
-- Name: caso_fotra; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE caso_fotra (
    id_caso integer NOT NULL,
    id_fotra integer NOT NULL,
    anotacion character varying(200),
    fecha date NOT NULL,
    ubicacionfisica character varying(100),
    tfuente character varying(25)
);


--
-- Name: caso_frontera; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE caso_frontera (
    id_frontera integer NOT NULL,
    id_caso integer NOT NULL
);


--
-- Name: caso_funcionario; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE caso_funcionario (
    id_funcionario integer NOT NULL,
    id_caso integer NOT NULL,
    fechainicio date
);


--
-- Name: caso_presponsable; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE caso_presponsable (
    id_caso integer NOT NULL,
    id_presponsable integer NOT NULL,
    tipo integer NOT NULL,
    bloque character varying(50),
    frente character varying(50),
    brigada character varying(50),
    batallon character varying(50),
    division character varying(50),
    otro character varying(500),
    id integer NOT NULL
);


--
-- Name: caso_region; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE caso_region (
    id_region integer NOT NULL,
    id_caso integer NOT NULL
);


--
-- Name: categoria; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE categoria (
    id integer NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    id_supracategoria integer NOT NULL,
    id_tviolencia character varying(1) NOT NULL,
    id_pconsolidado integer,
    contadaen integer,
    tipocat character(1) DEFAULT 'I'::bpchar,
    CONSTRAINT categoria_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion))),
    CONSTRAINT categoria_tipocat_check CHECK ((((tipocat = 'I'::bpchar) OR (tipocat = 'C'::bpchar)) OR (tipocat = 'O'::bpchar)))
);


--
-- Name: clase_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE clase_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: clase; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE clase (
    id integer DEFAULT nextval('clase_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    id_departamento integer NOT NULL,
    id_municipio integer NOT NULL,
    id_tclase character varying(10),
    latitud double precision,
    longitud double precision,
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    CONSTRAINT clase_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: comunidad_filiacion; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE comunidad_filiacion (
    id_filiacion integer NOT NULL,
    id_grupoper integer NOT NULL,
    id_caso integer NOT NULL
);


--
-- Name: comunidad_organizacion; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE comunidad_organizacion (
    id_organizacion integer NOT NULL,
    id_grupoper integer NOT NULL,
    id_caso integer NOT NULL
);


--
-- Name: comunidad_profesion; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE comunidad_profesion (
    id_profesion integer NOT NULL,
    id_grupoper integer NOT NULL,
    id_caso integer NOT NULL
);


--
-- Name: comunidad_rangoedad; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE comunidad_rangoedad (
    id_rangoedad integer NOT NULL,
    id_grupoper integer NOT NULL,
    id_caso integer NOT NULL
);


--
-- Name: comunidad_sectorsocial; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE comunidad_sectorsocial (
    id_sector integer NOT NULL,
    id_grupoper integer NOT NULL,
    id_caso integer NOT NULL
);


--
-- Name: comunidad_vinculoestado; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE comunidad_vinculoestado (
    id_vinculoestado integer NOT NULL,
    id_grupoper integer NOT NULL,
    id_caso integer NOT NULL
);


--
-- Name: contexto_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE contexto_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: contexto; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE contexto (
    id integer DEFAULT nextval('contexto_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    CONSTRAINT contexto_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: departamento_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE departamento_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: departamento; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE departamento (
    id integer DEFAULT nextval('departamento_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    latitud double precision,
    longitud double precision,
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    CONSTRAINT departamento_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: etnia_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE etnia_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: etnia; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE etnia (
    id integer DEFAULT nextval('etnia_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    descripcion character varying(1000),
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    CONSTRAINT etnia_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: ffrecuente_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE ffrecuente_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ffrecuente; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE ffrecuente (
    id integer DEFAULT nextval('ffrecuente_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    tfuente character varying(25) NOT NULL,
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    CONSTRAINT ffrecuente_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: filiacion_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE filiacion_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: filiacion; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE filiacion (
    id integer DEFAULT nextval('filiacion_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    CONSTRAINT filiacion_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: fotra_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE fotra_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: fotra; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE fotra (
    id integer DEFAULT nextval('fotra_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL
);


--
-- Name: frontera_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE frontera_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: frontera; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE frontera (
    id integer DEFAULT nextval('frontera_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    CONSTRAINT frontera_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: funcionario_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE funcionario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: funcionario; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE funcionario (
    id integer DEFAULT nextval('funcionario_seq'::regclass) NOT NULL,
    anotacion character varying(50),
    nombre character varying(15) NOT NULL
);


--
-- Name: grupoper_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE grupoper_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: grupoper; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE grupoper (
    id integer DEFAULT nextval('grupoper_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    anotaciones character varying(1000)
);


--
-- Name: iglesia_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE iglesia_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: iglesia; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE iglesia (
    id integer DEFAULT nextval('iglesia_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    descripcion character varying(1000),
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    CONSTRAINT iglesia_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: intervalo_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE intervalo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: intervalo; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE intervalo (
    id integer DEFAULT nextval('intervalo_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    rango character varying(25) NOT NULL,
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    CONSTRAINT intervalo_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: municipio_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE municipio_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: municipio; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE municipio (
    id integer DEFAULT nextval('municipio_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    id_departamento integer NOT NULL,
    latitud double precision,
    longitud double precision,
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    CONSTRAINT municipio_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: organizacion_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE organizacion_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: organizacion; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE organizacion (
    id integer DEFAULT nextval('organizacion_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    CONSTRAINT organizacion_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: pconsolidado_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE pconsolidado_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: pconsolidado; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE pconsolidado (
    id integer DEFAULT nextval('pconsolidado_seq'::regclass) NOT NULL,
    rotulo character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    tipoviolencia character varying(25) NOT NULL,
    clasificacion character varying(25) NOT NULL,
    peso integer DEFAULT 0,
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    CONSTRAINT pconsolidado_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: persona_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE persona_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: persona; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE persona (
    id integer DEFAULT nextval('persona_seq'::regclass) NOT NULL,
    nombres character varying(100) COLLATE public.es_co_utf_8 NOT NULL,
    apellidos character varying(100) COLLATE public.es_co_utf_8 NOT NULL,
    anionac integer,
    mesnac integer,
    dianac integer,
    sexo character(1) NOT NULL,
    id_departamento integer,
    id_municipio integer,
    id_clase integer,
    tipodocumento character varying(2),
    numerodocumento bigint,
    CONSTRAINT persona_check CHECK (((dianac IS NULL) OR ((((dianac >= 1) AND ((((((((mesnac = 1) OR (mesnac = 3)) OR (mesnac = 5)) OR (mesnac = 7)) OR (mesnac = 8)) OR (mesnac = 10)) OR (mesnac = 12)) AND (dianac <= 31))) OR (((((mesnac = 4) OR (mesnac = 6)) OR (mesnac = 9)) OR (mesnac = 11)) AND (dianac <= 30))) OR ((mesnac = 2) AND (dianac <= 29))))),
    CONSTRAINT persona_mesnac_check CHECK (((mesnac IS NULL) OR ((mesnac >= 1) AND (mesnac <= 12)))),
    CONSTRAINT persona_sexo_check CHECK ((((sexo = 'S'::bpchar) OR (sexo = 'F'::bpchar)) OR (sexo = 'M'::bpchar)))
);


--
-- Name: persona_trelacion; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE persona_trelacion (
    persona1 integer NOT NULL,
    persona2 integer NOT NULL,
    id_trelacion character(2) NOT NULL,
    observaciones character varying(200)
);


--
-- Name: presponsable_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE presponsable_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: presponsable; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE presponsable (
    id integer DEFAULT nextval('presponsable_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    papa integer,
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    CONSTRAINT presponsable_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: profesion_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE profesion_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: profesion; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE profesion (
    id integer DEFAULT nextval('profesion_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    CONSTRAINT profesion_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: rangoedad_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE rangoedad_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: rangoedad; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE rangoedad (
    id integer DEFAULT nextval('rangoedad_seq'::regclass) NOT NULL,
    nombre character varying(20) COLLATE public.es_co_utf_8 NOT NULL,
    rango character varying(20) NOT NULL,
    limiteinferior integer DEFAULT 0 NOT NULL,
    limitesuperior integer DEFAULT 0 NOT NULL,
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    CONSTRAINT rangoedad_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: region_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE region_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: region; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE region (
    id integer DEFAULT nextval('region_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    CONSTRAINT region_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: resagresion_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE resagresion_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: resagresion; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE resagresion (
    id integer DEFAULT nextval('resagresion_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    CONSTRAINT resagresion_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: schema_migrations; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE schema_migrations (
    version character varying(255) NOT NULL
);


--
-- Name: sectorsocial_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE sectorsocial_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sectorsocial; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE sectorsocial (
    id integer DEFAULT nextval('sectorsocial_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    CONSTRAINT sectorsocial_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: supracategoria; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE supracategoria (
    id integer NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    id_tviolencia character varying(1) NOT NULL,
    CONSTRAINT supracategoria_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: tclase; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE tclase (
    id character varying(10) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    CONSTRAINT tclase_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: trelacion; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE trelacion (
    id character(2) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    dirigido boolean NOT NULL,
    observaciones character varying(200),
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    CONSTRAINT trelacion_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: tsitio_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE tsitio_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tsitio; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE tsitio (
    id integer DEFAULT nextval('tsitio_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    CONSTRAINT tsitio_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: tviolencia; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE tviolencia (
    id character(1) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    nomcorto character varying(10) NOT NULL,
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    CONSTRAINT tviolencia_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: ubicacion_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE ubicacion_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ubicacion; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE ubicacion (
    id integer DEFAULT nextval('ubicacion_seq'::regclass) NOT NULL,
    lugar character varying(500) COLLATE public.es_co_utf_8,
    sitio character varying(500) COLLATE public.es_co_utf_8,
    id_clase integer,
    id_municipio integer,
    id_departamento integer,
    id_tsitio integer NOT NULL,
    id_caso integer NOT NULL,
    latitud double precision,
    longitud double precision
);


--
-- Name: usuario; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE usuario (
    id character varying(15) NOT NULL,
    password character varying(64) NOT NULL,
    nombre character varying(50) COLLATE public.es_co_utf_8,
    descripcion character varying(50),
    rol integer,
    diasedicion integer,
    idioma character varying(6) DEFAULT 'es_CO'::character varying NOT NULL,
    CONSTRAINT usuario_rol_check CHECK (((rol >= 1) AND (rol <= 4)))
);


--
-- Name: usuarios_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE usuarios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: usuarios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE usuarios_id_seq OWNED BY usuarios.id;


--
-- Name: victima; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE victima (
    id_persona integer NOT NULL,
    id_caso integer NOT NULL,
    hijos integer,
    id_profesion integer NOT NULL,
    id_rangoedad integer NOT NULL,
    id_filiacion integer NOT NULL,
    id_sectorsocial integer NOT NULL,
    id_organizacion integer NOT NULL,
    id_vinculoestado integer NOT NULL,
    organizacionarmada integer NOT NULL,
    anotaciones character varying(1000),
    id_etnia integer,
    id_iglesia integer,
    orientacionsexual character(1) DEFAULT 'H'::bpchar NOT NULL,
    CONSTRAINT victima_hijos_check CHECK (((hijos IS NULL) OR ((hijos >= 0) AND (hijos <= 100)))),
    CONSTRAINT victima_orientacionsexual_check CHECK (((((((orientacionsexual = 'L'::bpchar) OR (orientacionsexual = 'G'::bpchar)) OR (orientacionsexual = 'B'::bpchar)) OR (orientacionsexual = 'T'::bpchar)) OR (orientacionsexual = 'I'::bpchar)) OR (orientacionsexual = 'H'::bpchar)))
);


--
-- Name: victimacolectiva; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE victimacolectiva (
    id_grupoper integer NOT NULL,
    id_caso integer NOT NULL,
    personasaprox integer,
    organizacionarmada integer
);


--
-- Name: vinculoestado_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE vinculoestado_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: vinculoestado; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE vinculoestado (
    id integer DEFAULT nextval('vinculoestado_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    CONSTRAINT vinculoestado_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY usuarios ALTER COLUMN id SET DEFAULT nextval('usuarios_id_seq'::regclass);


--
-- Name: antecedente_caso_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY antecedente_caso
    ADD CONSTRAINT antecedente_caso_pkey PRIMARY KEY (id_antecedente, id_caso);


--
-- Name: antecedente_comunidad_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY antecedente_comunidad
    ADD CONSTRAINT antecedente_comunidad_pkey PRIMARY KEY (id_antecedente, id_grupoper, id_caso);


--
-- Name: antecedente_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY antecedente
    ADD CONSTRAINT antecedente_pkey PRIMARY KEY (id);


--
-- Name: antecedente_victima_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY antecedente_victima
    ADD CONSTRAINT antecedente_victima_pkey PRIMARY KEY (id_antecedente, id_persona, id_caso);


--
-- Name: caso_categoria_presponsable_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY caso_categoria_presponsable
    ADD CONSTRAINT caso_categoria_presponsable_pkey PRIMARY KEY (id_tviolencia, id_supracategoria, id_categoria, id, id_caso, id_presponsable);


--
-- Name: caso_contexto_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY caso_contexto
    ADD CONSTRAINT caso_contexto_pkey PRIMARY KEY (id_caso, id_contexto);


--
-- Name: caso_ffrecuente_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY caso_ffrecuente
    ADD CONSTRAINT caso_ffrecuente_pkey PRIMARY KEY (fecha, id_ffrecuente, id_caso);


--
-- Name: caso_fotra_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY caso_fotra
    ADD CONSTRAINT caso_fotra_pkey PRIMARY KEY (id_caso, id_fotra, fecha);


--
-- Name: caso_frontera_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY caso_frontera
    ADD CONSTRAINT caso_frontera_pkey PRIMARY KEY (id_frontera, id_caso);


--
-- Name: caso_funcionario_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY caso_funcionario
    ADD CONSTRAINT caso_funcionario_pkey PRIMARY KEY (id_funcionario, id_caso);


--
-- Name: caso_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY caso
    ADD CONSTRAINT caso_pkey PRIMARY KEY (id);


--
-- Name: caso_presponsable_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY caso_presponsable
    ADD CONSTRAINT caso_presponsable_pkey PRIMARY KEY (id_caso, id_presponsable, id);


--
-- Name: caso_region_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY caso_region
    ADD CONSTRAINT caso_region_pkey PRIMARY KEY (id_region, id_caso);


--
-- Name: categoria_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY categoria
    ADD CONSTRAINT categoria_pkey PRIMARY KEY (id);


--
-- Name: clase_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY clase
    ADD CONSTRAINT clase_pkey PRIMARY KEY (id, id_municipio, id_departamento);


--
-- Name: comunidad_filiacion_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY comunidad_filiacion
    ADD CONSTRAINT comunidad_filiacion_pkey PRIMARY KEY (id_filiacion, id_grupoper, id_caso);


--
-- Name: comunidad_organizacion_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY comunidad_organizacion
    ADD CONSTRAINT comunidad_organizacion_pkey PRIMARY KEY (id_organizacion, id_grupoper, id_caso);


--
-- Name: comunidad_profesion_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY comunidad_profesion
    ADD CONSTRAINT comunidad_profesion_pkey PRIMARY KEY (id_profesion, id_grupoper, id_caso);


--
-- Name: comunidad_rangoedad_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY comunidad_rangoedad
    ADD CONSTRAINT comunidad_rangoedad_pkey PRIMARY KEY (id_rangoedad, id_grupoper, id_caso);


--
-- Name: comunidad_sectorsocial_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY comunidad_sectorsocial
    ADD CONSTRAINT comunidad_sectorsocial_pkey PRIMARY KEY (id_sector, id_grupoper, id_caso);


--
-- Name: comunidad_vinculoestado_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY comunidad_vinculoestado
    ADD CONSTRAINT comunidad_vinculoestado_pkey PRIMARY KEY (id_vinculoestado, id_grupoper, id_caso);


--
-- Name: contexto_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY contexto
    ADD CONSTRAINT contexto_pkey PRIMARY KEY (id);


--
-- Name: departamento_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY departamento
    ADD CONSTRAINT departamento_pkey PRIMARY KEY (id);


--
-- Name: etnia_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY etnia
    ADD CONSTRAINT etnia_pkey PRIMARY KEY (id);


--
-- Name: ffrecuente_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY ffrecuente
    ADD CONSTRAINT ffrecuente_pkey PRIMARY KEY (id);


--
-- Name: filiacion_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY filiacion
    ADD CONSTRAINT filiacion_pkey PRIMARY KEY (id);


--
-- Name: fotra_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY fotra
    ADD CONSTRAINT fotra_pkey PRIMARY KEY (id);


--
-- Name: frontera_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY frontera
    ADD CONSTRAINT frontera_pkey PRIMARY KEY (id);


--
-- Name: funcionario_nombre_key; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY funcionario
    ADD CONSTRAINT funcionario_nombre_key UNIQUE (nombre);


--
-- Name: funcionario_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY funcionario
    ADD CONSTRAINT funcionario_pkey PRIMARY KEY (id);


--
-- Name: grupoper_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY grupoper
    ADD CONSTRAINT grupoper_pkey PRIMARY KEY (id);


--
-- Name: iglesia_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY iglesia
    ADD CONSTRAINT iglesia_pkey PRIMARY KEY (id);


--
-- Name: intervalo_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY intervalo
    ADD CONSTRAINT intervalo_pkey PRIMARY KEY (id);


--
-- Name: municipio_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY municipio
    ADD CONSTRAINT municipio_pkey PRIMARY KEY (id, id_departamento);


--
-- Name: organizacion_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY organizacion
    ADD CONSTRAINT organizacion_pkey PRIMARY KEY (id);


--
-- Name: pconsolidado_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY pconsolidado
    ADD CONSTRAINT pconsolidado_pkey PRIMARY KEY (id);


--
-- Name: persona_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY persona
    ADD CONSTRAINT persona_pkey PRIMARY KEY (id);


--
-- Name: persona_trelacion_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY persona_trelacion
    ADD CONSTRAINT persona_trelacion_pkey PRIMARY KEY (persona1, persona2, id_trelacion);


--
-- Name: presponsable_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY presponsable
    ADD CONSTRAINT presponsable_pkey PRIMARY KEY (id);


--
-- Name: profesion_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY profesion
    ADD CONSTRAINT profesion_pkey PRIMARY KEY (id);


--
-- Name: rangoedad_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY rangoedad
    ADD CONSTRAINT rangoedad_pkey PRIMARY KEY (id);


--
-- Name: region_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY region
    ADD CONSTRAINT region_pkey PRIMARY KEY (id);


--
-- Name: resagresion_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY resagresion
    ADD CONSTRAINT resagresion_pkey PRIMARY KEY (id);


--
-- Name: sectorsocial_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY sectorsocial
    ADD CONSTRAINT sectorsocial_pkey PRIMARY KEY (id);


--
-- Name: supracategoria_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY supracategoria
    ADD CONSTRAINT supracategoria_pkey PRIMARY KEY (id, id_tviolencia);


--
-- Name: tclase_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY tclase
    ADD CONSTRAINT tclase_pkey PRIMARY KEY (id);


--
-- Name: trelacion_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY trelacion
    ADD CONSTRAINT trelacion_pkey PRIMARY KEY (id);


--
-- Name: tsitio_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY tsitio
    ADD CONSTRAINT tsitio_pkey PRIMARY KEY (id);


--
-- Name: tviolencia_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY tviolencia
    ADD CONSTRAINT tviolencia_pkey PRIMARY KEY (id);


--
-- Name: ubicacion_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY ubicacion
    ADD CONSTRAINT ubicacion_pkey PRIMARY KEY (id);


--
-- Name: usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT usuario_pkey PRIMARY KEY (id);


--
-- Name: usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (id);


--
-- Name: victima_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY victima
    ADD CONSTRAINT victima_pkey PRIMARY KEY (id_persona, id_caso);


--
-- Name: victimacolectiva_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY victimacolectiva
    ADD CONSTRAINT victimacolectiva_pkey PRIMARY KEY (id_grupoper, id_caso);


--
-- Name: vinculoestado_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY vinculoestado
    ADD CONSTRAINT vinculoestado_pkey PRIMARY KEY (id);


--
-- Name: caso_memo; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE INDEX caso_memo ON caso USING gin (to_tsvector('spanish'::regconfig, unaccent(memo)));


--
-- Name: caso_titulo; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE INDEX caso_titulo ON caso USING gin (to_tsvector('spanish'::regconfig, unaccent((titulo)::text)));


--
-- Name: index_usuarios_on_email; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX index_usuarios_on_email ON usuarios USING btree (email);


--
-- Name: index_usuarios_on_reset_password_token; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX index_usuarios_on_reset_password_token ON usuarios USING btree (reset_password_token);


--
-- Name: persona_apellidos_nombres; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE INDEX persona_apellidos_nombres ON persona USING gin (to_tsvector('spanish'::regconfig, ((unaccent((apellidos)::text) || ' '::text) || unaccent((nombres)::text))));


--
-- Name: persona_apellidos_nombres_doc; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE INDEX persona_apellidos_nombres_doc ON persona USING gin (to_tsvector('spanish'::regconfig, ((((unaccent((apellidos)::text) || ' '::text) || unaccent((nombres)::text)) || ' '::text) || COALESCE((numerodocumento)::text, ''::text))));


--
-- Name: persona_nombres_apellidos; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE INDEX persona_nombres_apellidos ON persona USING gin (to_tsvector('spanish'::regconfig, ((unaccent((nombres)::text) || ' '::text) || unaccent((apellidos)::text))));


--
-- Name: persona_nombres_apellidos_doc; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE INDEX persona_nombres_apellidos_doc ON persona USING gin (to_tsvector('spanish'::regconfig, ((((unaccent((nombres)::text) || ' '::text) || unaccent((apellidos)::text)) || ' '::text) || COALESCE((numerodocumento)::text, ''::text))));


--
-- Name: unique_schema_migrations; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX unique_schema_migrations ON schema_migrations USING btree (version);


--
-- Name: antecedente_caso_id_antecedente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY antecedente_caso
    ADD CONSTRAINT antecedente_caso_id_antecedente_fkey FOREIGN KEY (id_antecedente) REFERENCES antecedente(id);


--
-- Name: antecedente_caso_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY antecedente_caso
    ADD CONSTRAINT antecedente_caso_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: antecedente_comunidad_id_antecedente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY antecedente_comunidad
    ADD CONSTRAINT antecedente_comunidad_id_antecedente_fkey FOREIGN KEY (id_antecedente) REFERENCES antecedente(id);


--
-- Name: antecedente_comunidad_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY antecedente_comunidad
    ADD CONSTRAINT antecedente_comunidad_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: antecedente_comunidad_id_grupoper_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY antecedente_comunidad
    ADD CONSTRAINT antecedente_comunidad_id_grupoper_fkey FOREIGN KEY (id_grupoper) REFERENCES grupoper(id);


--
-- Name: antecedente_comunidad_id_grupoper_fkey1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY antecedente_comunidad
    ADD CONSTRAINT antecedente_comunidad_id_grupoper_fkey1 FOREIGN KEY (id_grupoper, id_caso) REFERENCES victimacolectiva(id_grupoper, id_caso);


--
-- Name: antecedente_victima_id_antecedente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY antecedente_victima
    ADD CONSTRAINT antecedente_victima_id_antecedente_fkey FOREIGN KEY (id_antecedente) REFERENCES antecedente(id);


--
-- Name: antecedente_victima_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY antecedente_victima
    ADD CONSTRAINT antecedente_victima_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: antecedente_victima_id_persona_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY antecedente_victima
    ADD CONSTRAINT antecedente_victima_id_persona_fkey FOREIGN KEY (id_persona) REFERENCES persona(id);


--
-- Name: antecedente_victima_id_persona_fkey1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY antecedente_victima
    ADD CONSTRAINT antecedente_victima_id_persona_fkey1 FOREIGN KEY (id_persona, id_caso) REFERENCES victima(id_persona, id_caso);


--
-- Name: caso_categoria_presponsable_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY caso_categoria_presponsable
    ADD CONSTRAINT caso_categoria_presponsable_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: caso_categoria_presponsable_id_categoria_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY caso_categoria_presponsable
    ADD CONSTRAINT caso_categoria_presponsable_id_categoria_fkey FOREIGN KEY (id_categoria) REFERENCES categoria(id);


--
-- Name: caso_categoria_presponsable_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY caso_categoria_presponsable
    ADD CONSTRAINT caso_categoria_presponsable_id_fkey FOREIGN KEY (id, id_caso, id_presponsable) REFERENCES caso_presponsable(id, id_caso, id_presponsable);


--
-- Name: caso_categoria_presponsable_id_presponsable_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY caso_categoria_presponsable
    ADD CONSTRAINT caso_categoria_presponsable_id_presponsable_fkey FOREIGN KEY (id_presponsable) REFERENCES presponsable(id);


--
-- Name: caso_categoria_presponsable_id_supracategoria_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY caso_categoria_presponsable
    ADD CONSTRAINT caso_categoria_presponsable_id_supracategoria_fkey FOREIGN KEY (id_supracategoria, id_tviolencia) REFERENCES supracategoria(id, id_tviolencia);


--
-- Name: caso_categoria_presponsable_id_tviolencia_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY caso_categoria_presponsable
    ADD CONSTRAINT caso_categoria_presponsable_id_tviolencia_fkey FOREIGN KEY (id_tviolencia) REFERENCES tviolencia(id);


--
-- Name: caso_contexto_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY caso_contexto
    ADD CONSTRAINT caso_contexto_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: caso_contexto_id_contexto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY caso_contexto
    ADD CONSTRAINT caso_contexto_id_contexto_fkey FOREIGN KEY (id_contexto) REFERENCES contexto(id);


--
-- Name: caso_ffrecuente_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY caso_ffrecuente
    ADD CONSTRAINT caso_ffrecuente_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: caso_ffrecuente_id_ffrecuente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY caso_ffrecuente
    ADD CONSTRAINT caso_ffrecuente_id_ffrecuente_fkey FOREIGN KEY (id_ffrecuente) REFERENCES ffrecuente(id);


--
-- Name: caso_fotra_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY caso_fotra
    ADD CONSTRAINT caso_fotra_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: caso_fotra_id_fotra_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY caso_fotra
    ADD CONSTRAINT caso_fotra_id_fotra_fkey FOREIGN KEY (id_fotra) REFERENCES fotra(id);


--
-- Name: caso_frontera_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY caso_frontera
    ADD CONSTRAINT caso_frontera_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: caso_frontera_id_frontera_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY caso_frontera
    ADD CONSTRAINT caso_frontera_id_frontera_fkey FOREIGN KEY (id_frontera) REFERENCES frontera(id);


--
-- Name: caso_funcionario_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY caso_funcionario
    ADD CONSTRAINT caso_funcionario_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: caso_funcionario_id_funcionario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY caso_funcionario
    ADD CONSTRAINT caso_funcionario_id_funcionario_fkey FOREIGN KEY (id_funcionario) REFERENCES funcionario(id);


--
-- Name: caso_id_intervalo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY caso
    ADD CONSTRAINT caso_id_intervalo_fkey FOREIGN KEY (id_intervalo) REFERENCES intervalo(id);


--
-- Name: caso_presponsable_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY caso_presponsable
    ADD CONSTRAINT caso_presponsable_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: caso_presponsable_id_presponsable_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY caso_presponsable
    ADD CONSTRAINT caso_presponsable_id_presponsable_fkey FOREIGN KEY (id_presponsable) REFERENCES presponsable(id);


--
-- Name: caso_region_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY caso_region
    ADD CONSTRAINT caso_region_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: caso_region_id_region_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY caso_region
    ADD CONSTRAINT caso_region_id_region_fkey FOREIGN KEY (id_region) REFERENCES region(id);


--
-- Name: categoria_contadaen_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY categoria
    ADD CONSTRAINT categoria_contadaen_fkey FOREIGN KEY (contadaen) REFERENCES categoria(id);


--
-- Name: categoria_id_pconsolidado_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY categoria
    ADD CONSTRAINT categoria_id_pconsolidado_fkey FOREIGN KEY (id_pconsolidado) REFERENCES pconsolidado(id);


--
-- Name: categoria_id_supracategoria_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY categoria
    ADD CONSTRAINT categoria_id_supracategoria_fkey FOREIGN KEY (id_supracategoria, id_tviolencia) REFERENCES supracategoria(id, id_tviolencia);


--
-- Name: categoria_id_tviolencia_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY categoria
    ADD CONSTRAINT categoria_id_tviolencia_fkey FOREIGN KEY (id_tviolencia) REFERENCES tviolencia(id);


--
-- Name: clase_id_departamento_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY clase
    ADD CONSTRAINT clase_id_departamento_fkey FOREIGN KEY (id_departamento) REFERENCES departamento(id) ON DELETE CASCADE;


--
-- Name: clase_id_municipio_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY clase
    ADD CONSTRAINT clase_id_municipio_fkey FOREIGN KEY (id_municipio, id_departamento) REFERENCES municipio(id, id_departamento) ON DELETE CASCADE;


--
-- Name: clase_id_tclase_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY clase
    ADD CONSTRAINT clase_id_tclase_fkey FOREIGN KEY (id_tclase) REFERENCES tclase(id);


--
-- Name: comunidad_filiacion_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY comunidad_filiacion
    ADD CONSTRAINT comunidad_filiacion_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: comunidad_filiacion_id_filiacion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY comunidad_filiacion
    ADD CONSTRAINT comunidad_filiacion_id_filiacion_fkey FOREIGN KEY (id_filiacion) REFERENCES filiacion(id);


--
-- Name: comunidad_filiacion_id_grupoper_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY comunidad_filiacion
    ADD CONSTRAINT comunidad_filiacion_id_grupoper_fkey FOREIGN KEY (id_grupoper) REFERENCES grupoper(id);


--
-- Name: comunidad_filiacion_id_grupoper_fkey1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY comunidad_filiacion
    ADD CONSTRAINT comunidad_filiacion_id_grupoper_fkey1 FOREIGN KEY (id_grupoper, id_caso) REFERENCES victimacolectiva(id_grupoper, id_caso);


--
-- Name: comunidad_organizacion_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY comunidad_organizacion
    ADD CONSTRAINT comunidad_organizacion_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: comunidad_organizacion_id_grupoper_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY comunidad_organizacion
    ADD CONSTRAINT comunidad_organizacion_id_grupoper_fkey FOREIGN KEY (id_grupoper) REFERENCES grupoper(id);


--
-- Name: comunidad_organizacion_id_grupoper_fkey1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY comunidad_organizacion
    ADD CONSTRAINT comunidad_organizacion_id_grupoper_fkey1 FOREIGN KEY (id_grupoper, id_caso) REFERENCES victimacolectiva(id_grupoper, id_caso);


--
-- Name: comunidad_organizacion_id_organizacion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY comunidad_organizacion
    ADD CONSTRAINT comunidad_organizacion_id_organizacion_fkey FOREIGN KEY (id_organizacion) REFERENCES organizacion(id);


--
-- Name: comunidad_profesion_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY comunidad_profesion
    ADD CONSTRAINT comunidad_profesion_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: comunidad_profesion_id_grupoper_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY comunidad_profesion
    ADD CONSTRAINT comunidad_profesion_id_grupoper_fkey FOREIGN KEY (id_grupoper) REFERENCES grupoper(id);


--
-- Name: comunidad_profesion_id_grupoper_fkey1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY comunidad_profesion
    ADD CONSTRAINT comunidad_profesion_id_grupoper_fkey1 FOREIGN KEY (id_grupoper, id_caso) REFERENCES victimacolectiva(id_grupoper, id_caso);


--
-- Name: comunidad_profesion_id_profesion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY comunidad_profesion
    ADD CONSTRAINT comunidad_profesion_id_profesion_fkey FOREIGN KEY (id_profesion) REFERENCES profesion(id);


--
-- Name: comunidad_rangoedad_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY comunidad_rangoedad
    ADD CONSTRAINT comunidad_rangoedad_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: comunidad_rangoedad_id_grupoper_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY comunidad_rangoedad
    ADD CONSTRAINT comunidad_rangoedad_id_grupoper_fkey FOREIGN KEY (id_grupoper) REFERENCES grupoper(id);


--
-- Name: comunidad_rangoedad_id_grupoper_fkey1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY comunidad_rangoedad
    ADD CONSTRAINT comunidad_rangoedad_id_grupoper_fkey1 FOREIGN KEY (id_grupoper, id_caso) REFERENCES victimacolectiva(id_grupoper, id_caso);


--
-- Name: comunidad_rangoedad_id_rangoedad_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY comunidad_rangoedad
    ADD CONSTRAINT comunidad_rangoedad_id_rangoedad_fkey FOREIGN KEY (id_rangoedad) REFERENCES rangoedad(id);


--
-- Name: comunidad_sectorsocial_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY comunidad_sectorsocial
    ADD CONSTRAINT comunidad_sectorsocial_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: comunidad_sectorsocial_id_grupoper_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY comunidad_sectorsocial
    ADD CONSTRAINT comunidad_sectorsocial_id_grupoper_fkey FOREIGN KEY (id_grupoper) REFERENCES grupoper(id);


--
-- Name: comunidad_sectorsocial_id_grupoper_fkey1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY comunidad_sectorsocial
    ADD CONSTRAINT comunidad_sectorsocial_id_grupoper_fkey1 FOREIGN KEY (id_grupoper, id_caso) REFERENCES victimacolectiva(id_grupoper, id_caso);


--
-- Name: comunidad_sectorsocial_id_sector_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY comunidad_sectorsocial
    ADD CONSTRAINT comunidad_sectorsocial_id_sector_fkey FOREIGN KEY (id_sector) REFERENCES sectorsocial(id);


--
-- Name: comunidad_vinculoestado_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY comunidad_vinculoestado
    ADD CONSTRAINT comunidad_vinculoestado_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: comunidad_vinculoestado_id_grupoper_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY comunidad_vinculoestado
    ADD CONSTRAINT comunidad_vinculoestado_id_grupoper_fkey FOREIGN KEY (id_grupoper) REFERENCES grupoper(id);


--
-- Name: comunidad_vinculoestado_id_grupoper_fkey1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY comunidad_vinculoestado
    ADD CONSTRAINT comunidad_vinculoestado_id_grupoper_fkey1 FOREIGN KEY (id_grupoper, id_caso) REFERENCES victimacolectiva(id_grupoper, id_caso);


--
-- Name: comunidad_vinculoestado_id_vinculoestado_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY comunidad_vinculoestado
    ADD CONSTRAINT comunidad_vinculoestado_id_vinculoestado_fkey FOREIGN KEY (id_vinculoestado) REFERENCES vinculoestado(id);


--
-- Name: municipio_id_departamento_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY municipio
    ADD CONSTRAINT municipio_id_departamento_fkey FOREIGN KEY (id_departamento) REFERENCES departamento(id) ON DELETE CASCADE;


--
-- Name: persona_id_departamento_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY persona
    ADD CONSTRAINT persona_id_departamento_fkey FOREIGN KEY (id_departamento) REFERENCES departamento(id) ON DELETE CASCADE;


--
-- Name: persona_trelacion_id_trelacion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY persona_trelacion
    ADD CONSTRAINT persona_trelacion_id_trelacion_fkey FOREIGN KEY (id_trelacion) REFERENCES trelacion(id);


--
-- Name: persona_trelacion_persona1_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY persona_trelacion
    ADD CONSTRAINT persona_trelacion_persona1_fkey FOREIGN KEY (persona1) REFERENCES persona(id);


--
-- Name: persona_trelacion_persona2_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY persona_trelacion
    ADD CONSTRAINT persona_trelacion_persona2_fkey FOREIGN KEY (persona2) REFERENCES persona(id);


--
-- Name: presponsable_papa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY presponsable
    ADD CONSTRAINT presponsable_papa_fkey FOREIGN KEY (papa) REFERENCES presponsable(id);


--
-- Name: supracategoria_id_tviolencia_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY supracategoria
    ADD CONSTRAINT supracategoria_id_tviolencia_fkey FOREIGN KEY (id_tviolencia) REFERENCES tviolencia(id);


--
-- Name: ubicacion_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY ubicacion
    ADD CONSTRAINT ubicacion_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: ubicacion_id_clase_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY ubicacion
    ADD CONSTRAINT ubicacion_id_clase_fkey FOREIGN KEY (id_clase, id_municipio, id_departamento) REFERENCES clase(id, id_municipio, id_departamento);


--
-- Name: ubicacion_id_departamento_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY ubicacion
    ADD CONSTRAINT ubicacion_id_departamento_fkey FOREIGN KEY (id_departamento) REFERENCES departamento(id);


--
-- Name: ubicacion_id_municipio_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY ubicacion
    ADD CONSTRAINT ubicacion_id_municipio_fkey FOREIGN KEY (id_municipio, id_departamento) REFERENCES municipio(id, id_departamento);


--
-- Name: ubicacion_id_tsitio_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY ubicacion
    ADD CONSTRAINT ubicacion_id_tsitio_fkey FOREIGN KEY (id_tsitio) REFERENCES tsitio(id);


--
-- Name: victima_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY victima
    ADD CONSTRAINT victima_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: victima_id_etnia_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY victima
    ADD CONSTRAINT victima_id_etnia_fkey FOREIGN KEY (id_etnia) REFERENCES etnia(id);


--
-- Name: victima_id_filiacion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY victima
    ADD CONSTRAINT victima_id_filiacion_fkey FOREIGN KEY (id_filiacion) REFERENCES filiacion(id);


--
-- Name: victima_id_iglesia_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY victima
    ADD CONSTRAINT victima_id_iglesia_fkey FOREIGN KEY (id_iglesia) REFERENCES iglesia(id);


--
-- Name: victima_id_organizacion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY victima
    ADD CONSTRAINT victima_id_organizacion_fkey FOREIGN KEY (id_organizacion) REFERENCES organizacion(id);


--
-- Name: victima_id_persona_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY victima
    ADD CONSTRAINT victima_id_persona_fkey FOREIGN KEY (id_persona) REFERENCES persona(id);


--
-- Name: victima_id_profesion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY victima
    ADD CONSTRAINT victima_id_profesion_fkey FOREIGN KEY (id_profesion) REFERENCES profesion(id);


--
-- Name: victima_id_rangoedad_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY victima
    ADD CONSTRAINT victima_id_rangoedad_fkey FOREIGN KEY (id_rangoedad) REFERENCES rangoedad(id);


--
-- Name: victima_id_sectorsocial_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY victima
    ADD CONSTRAINT victima_id_sectorsocial_fkey FOREIGN KEY (id_sectorsocial) REFERENCES sectorsocial(id);


--
-- Name: victima_id_vinculoestado_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY victima
    ADD CONSTRAINT victima_id_vinculoestado_fkey FOREIGN KEY (id_vinculoestado) REFERENCES vinculoestado(id);


--
-- Name: victima_organizacionarmada_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY victima
    ADD CONSTRAINT victima_organizacionarmada_fkey FOREIGN KEY (organizacionarmada) REFERENCES presponsable(id);


--
-- Name: victimacolectiva_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY victimacolectiva
    ADD CONSTRAINT victimacolectiva_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: victimacolectiva_id_grupoper_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY victimacolectiva
    ADD CONSTRAINT victimacolectiva_id_grupoper_fkey FOREIGN KEY (id_grupoper) REFERENCES grupoper(id);


--
-- Name: victimacolectiva_organizacionarmada_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY victimacolectiva
    ADD CONSTRAINT victimacolectiva_organizacionarmada_fkey FOREIGN KEY (organizacionarmada) REFERENCES presponsable(id);


--
-- PostgreSQL database dump complete
--

SET search_path TO "$user",public;

INSERT INTO schema_migrations (version) VALUES ('20131122230503');

INSERT INTO schema_migrations (version) VALUES ('20131122230524');

INSERT INTO schema_migrations (version) VALUES ('20131122230540');
