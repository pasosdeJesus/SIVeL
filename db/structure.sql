--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
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


--
-- Name: addauth(text); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION addauth(text) RETURNS boolean
    LANGUAGE plpgsql
    AS $_$ 
DECLARE
	lockid alias for $1;
	okay boolean;
	myrec record;
BEGIN
	-- check to see if table exists
	--  if not, CREATE TEMP TABLE mylock (transid xid, lockcode text)
	okay := 'f';
	FOR myrec IN SELECT * FROM pg_class WHERE relname = 'temp_lock_have_table' LOOP
		okay := 't';
	END LOOP; 
	IF (okay <> 't') THEN 
		CREATE TEMP TABLE temp_lock_have_table (transid xid, lockcode text);
			-- this will only work from pgsql7.4 up
			-- ON COMMIT DELETE ROWS;
	END IF;

	--  INSERT INTO mylock VALUES ( $1)
--	EXECUTE 'INSERT INTO temp_lock_have_table VALUES ( '||
--		quote_literal(getTransactionID()) || ',' ||
--		quote_literal(lockid) ||')';

	INSERT INTO temp_lock_have_table VALUES (getTransactionID(), lockid);

	RETURN true::boolean;
END;
$_$;


--
-- Name: addgeometrycolumn(character varying, character varying, integer, character varying, integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION addgeometrycolumn(character varying, character varying, integer, character varying, integer) RETURNS text
    LANGUAGE plpgsql STRICT
    AS $_$
DECLARE
	ret  text;
BEGIN
	SELECT AddGeometryColumn('','',$1,$2,$3,$4,$5) into ret;
	RETURN ret;
END;
$_$;


--
-- Name: addgeometrycolumn(character varying, character varying, character varying, integer, character varying, integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION addgeometrycolumn(character varying, character varying, character varying, integer, character varying, integer) RETURNS text
    LANGUAGE plpgsql STABLE STRICT
    AS $_$
DECLARE
	ret  text;
BEGIN
	SELECT AddGeometryColumn('',$1,$2,$3,$4,$5,$6) into ret;
	RETURN ret;
END;
$_$;


--
-- Name: addgeometrycolumn(character varying, character varying, character varying, character varying, integer, character varying, integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION addgeometrycolumn(character varying, character varying, character varying, character varying, integer, character varying, integer) RETURNS text
    LANGUAGE plpgsql STRICT
    AS $_$
DECLARE
	catalog_name alias for $1;
	schema_name alias for $2;
	table_name alias for $3;
	column_name alias for $4;
	new_srid alias for $5;
	new_type alias for $6;
	new_dim alias for $7;
	rec RECORD;
	sr varchar;
	real_schema name;
	sql text;

BEGIN

	-- Verify geometry type
	IF ( NOT ( (new_type = 'GEOMETRY') OR
			   (new_type = 'GEOMETRYCOLLECTION') OR
			   (new_type = 'POINT') OR
			   (new_type = 'MULTIPOINT') OR
			   (new_type = 'POLYGON') OR
			   (new_type = 'MULTIPOLYGON') OR
			   (new_type = 'LINESTRING') OR
			   (new_type = 'MULTILINESTRING') OR
			   (new_type = 'GEOMETRYCOLLECTIONM') OR
			   (new_type = 'POINTM') OR
			   (new_type = 'MULTIPOINTM') OR
			   (new_type = 'POLYGONM') OR
			   (new_type = 'MULTIPOLYGONM') OR
			   (new_type = 'LINESTRINGM') OR
			   (new_type = 'MULTILINESTRINGM') OR
			   (new_type = 'CIRCULARSTRING') OR
			   (new_type = 'CIRCULARSTRINGM') OR
			   (new_type = 'COMPOUNDCURVE') OR
			   (new_type = 'COMPOUNDCURVEM') OR
			   (new_type = 'CURVEPOLYGON') OR
			   (new_type = 'CURVEPOLYGONM') OR
			   (new_type = 'MULTICURVE') OR
			   (new_type = 'MULTICURVEM') OR
			   (new_type = 'MULTISURFACE') OR
			   (new_type = 'MULTISURFACEM')) )
	THEN
		RAISE EXCEPTION 'Invalid type name - valid ones are:
	POINT, MULTIPOINT,
	LINESTRING, MULTILINESTRING,
	POLYGON, MULTIPOLYGON,
	CIRCULARSTRING, COMPOUNDCURVE, MULTICURVE,
	CURVEPOLYGON, MULTISURFACE,
	GEOMETRY, GEOMETRYCOLLECTION,
	POINTM, MULTIPOINTM,
	LINESTRINGM, MULTILINESTRINGM,
	POLYGONM, MULTIPOLYGONM,
	CIRCULARSTRINGM, COMPOUNDCURVEM, MULTICURVEM
	CURVEPOLYGONM, MULTISURFACEM,
	or GEOMETRYCOLLECTIONM';
		RETURN 'fail';
	END IF;


	-- Verify dimension
	IF ( (new_dim >4) OR (new_dim <0) ) THEN
		RAISE EXCEPTION 'invalid dimension';
		RETURN 'fail';
	END IF;

	IF ( (new_type LIKE '%M') AND (new_dim!=3) ) THEN
		RAISE EXCEPTION 'TypeM needs 3 dimensions';
		RETURN 'fail';
	END IF;


	-- Verify SRID
	IF ( new_srid != -1 ) THEN
		SELECT SRID INTO sr FROM spatial_ref_sys WHERE SRID = new_srid;
		IF NOT FOUND THEN
			RAISE EXCEPTION 'AddGeometryColumns() - invalid SRID';
			RETURN 'fail';
		END IF;
	END IF;


	-- Verify schema
	IF ( schema_name IS NOT NULL AND schema_name != '' ) THEN
		sql := 'SELECT nspname FROM pg_namespace ' ||
			'WHERE text(nspname) = ' || quote_literal(schema_name) ||
			'LIMIT 1';
		RAISE DEBUG '%', sql;
		EXECUTE sql INTO real_schema;

		IF ( real_schema IS NULL ) THEN
			RAISE EXCEPTION 'Schema % is not a valid schemaname', quote_literal(schema_name);
			RETURN 'fail';
		END IF;
	END IF;

	IF ( real_schema IS NULL ) THEN
		RAISE DEBUG 'Detecting schema';
		sql := 'SELECT n.nspname AS schemaname ' ||
			'FROM pg_catalog.pg_class c ' ||
			  'JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace ' ||
			'WHERE c.relkind = ' || quote_literal('r') ||
			' AND n.nspname NOT IN (' || quote_literal('pg_catalog') || ', ' || quote_literal('pg_toast') || ')' ||
			' AND pg_catalog.pg_table_is_visible(c.oid)' ||
			' AND c.relname = ' || quote_literal(table_name);
		RAISE DEBUG '%', sql;
		EXECUTE sql INTO real_schema;

		IF ( real_schema IS NULL ) THEN
			RAISE EXCEPTION 'Table % does not occur in the search_path', quote_literal(table_name);
			RETURN 'fail';
		END IF;
	END IF;


	-- Add geometry column to table
	sql := 'ALTER TABLE ' ||
		quote_ident(real_schema) || '.' || quote_ident(table_name)
		|| ' ADD COLUMN ' || quote_ident(column_name) ||
		' geometry ';
	RAISE DEBUG '%', sql;
	EXECUTE sql;


	-- Delete stale record in geometry_columns (if any)
	sql := 'DELETE FROM geometry_columns WHERE
		f_table_catalog = ' || quote_literal('') ||
		' AND f_table_schema = ' ||
		quote_literal(real_schema) ||
		' AND f_table_name = ' || quote_literal(table_name) ||
		' AND f_geometry_column = ' || quote_literal(column_name);
	RAISE DEBUG '%', sql;
	EXECUTE sql;


	-- Add record in geometry_columns
	sql := 'INSERT INTO geometry_columns (f_table_catalog,f_table_schema,f_table_name,' ||
										  'f_geometry_column,coord_dimension,srid,type)' ||
		' VALUES (' ||
		quote_literal('') || ',' ||
		quote_literal(real_schema) || ',' ||
		quote_literal(table_name) || ',' ||
		quote_literal(column_name) || ',' ||
		new_dim::text || ',' ||
		new_srid::text || ',' ||
		quote_literal(new_type) || ')';
	RAISE DEBUG '%', sql;
	EXECUTE sql;


	-- Add table CHECKs
	sql := 'ALTER TABLE ' ||
		quote_ident(real_schema) || '.' || quote_ident(table_name)
		|| ' ADD CONSTRAINT '
		|| quote_ident('enforce_srid_' || column_name)
		|| ' CHECK (ST_SRID(' || quote_ident(column_name) ||
		') = ' || new_srid::text || ')' ;
	RAISE DEBUG '%', sql;
	EXECUTE sql;

	sql := 'ALTER TABLE ' ||
		quote_ident(real_schema) || '.' || quote_ident(table_name)
		|| ' ADD CONSTRAINT '
		|| quote_ident('enforce_dims_' || column_name)
		|| ' CHECK (ST_NDims(' || quote_ident(column_name) ||
		') = ' || new_dim::text || ')' ;
	RAISE DEBUG '%', sql;
	EXECUTE sql;

	IF ( NOT (new_type = 'GEOMETRY')) THEN
		sql := 'ALTER TABLE ' ||
			quote_ident(real_schema) || '.' || quote_ident(table_name) || ' ADD CONSTRAINT ' ||
			quote_ident('enforce_geotype_' || column_name) ||
			' CHECK (GeometryType(' ||
			quote_ident(column_name) || ')=' ||
			quote_literal(new_type) || ' OR (' ||
			quote_ident(column_name) || ') is null)';
		RAISE DEBUG '%', sql;
		EXECUTE sql;
	END IF;

	RETURN
		real_schema || '.' ||
		table_name || '.' || column_name ||
		' SRID:' || new_srid::text ||
		' TYPE:' || new_type ||
		' DIMS:' || new_dim::text || ' ';
END;
$_$;


--
-- Name: checkauth(text, text); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION checkauth(text, text) RETURNS integer
    LANGUAGE sql
    AS $_$ SELECT CheckAuth('', $1, $2) $_$;


--
-- Name: checkauth(text, text, text); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION checkauth(text, text, text) RETURNS integer
    LANGUAGE plpgsql
    AS $_$ 
DECLARE
	schema text;
BEGIN
	IF NOT LongTransactionsEnabled() THEN
		RAISE EXCEPTION 'Long transaction support disabled, use EnableLongTransaction() to enable.';
	END IF;

	if ( $1 != '' ) THEN
		schema = $1;
	ELSE
		SELECT current_schema() into schema;
	END IF;

	-- TODO: check for an already existing trigger ?

	EXECUTE 'CREATE TRIGGER check_auth BEFORE UPDATE OR DELETE ON ' 
		|| quote_ident(schema) || '.' || quote_ident($2)
		||' FOR EACH ROW EXECUTE PROCEDURE CheckAuthTrigger('
		|| quote_literal($3) || ')';

	RETURN 0;
END;
$_$;


--
-- Name: disablelongtransactions(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION disablelongtransactions() RETURNS text
    LANGUAGE plpgsql
    AS $$ 
DECLARE
	rec RECORD;

BEGIN

	--
	-- Drop all triggers applied by CheckAuth()
	--
	FOR rec IN
		SELECT c.relname, t.tgname, t.tgargs FROM pg_trigger t, pg_class c, pg_proc p
		WHERE p.proname = 'checkauthtrigger' and t.tgfoid = p.oid and t.tgrelid = c.oid
	LOOP
		EXECUTE 'DROP TRIGGER ' || quote_ident(rec.tgname) ||
			' ON ' || quote_ident(rec.relname);
	END LOOP;

	--
	-- Drop the authorization_table table
	--
	FOR rec IN SELECT * FROM pg_class WHERE relname = 'authorization_table' LOOP
		DROP TABLE authorization_table;
	END LOOP;

	--
	-- Drop the authorized_tables view
	--
	FOR rec IN SELECT * FROM pg_class WHERE relname = 'authorized_tables' LOOP
		DROP VIEW authorized_tables;
	END LOOP;

	RETURN 'Long transactions support disabled';
END;
$$;


--
-- Name: dropgeometrycolumn(character varying, character varying); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION dropgeometrycolumn(character varying, character varying) RETURNS text
    LANGUAGE plpgsql STRICT
    AS $_$
DECLARE
	ret text;
BEGIN
	SELECT DropGeometryColumn('','',$1,$2) into ret;
	RETURN ret;
END;
$_$;


--
-- Name: dropgeometrycolumn(character varying, character varying, character varying); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION dropgeometrycolumn(character varying, character varying, character varying) RETURNS text
    LANGUAGE plpgsql STRICT
    AS $_$
DECLARE
	ret text;
BEGIN
	SELECT DropGeometryColumn('',$1,$2,$3) into ret;
	RETURN ret;
END;
$_$;


--
-- Name: dropgeometrycolumn(character varying, character varying, character varying, character varying); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION dropgeometrycolumn(character varying, character varying, character varying, character varying) RETURNS text
    LANGUAGE plpgsql STRICT
    AS $_$
DECLARE
	catalog_name alias for $1;
	schema_name alias for $2;
	table_name alias for $3;
	column_name alias for $4;
	myrec RECORD;
	okay boolean;
	real_schema name;

BEGIN


	-- Find, check or fix schema_name
	IF ( schema_name != '' ) THEN
		okay = 'f';

		FOR myrec IN SELECT nspname FROM pg_namespace WHERE text(nspname) = schema_name LOOP
			okay := 't';
		END LOOP;

		IF ( okay <> 't' ) THEN
			RAISE NOTICE 'Invalid schema name - using current_schema()';
			SELECT current_schema() into real_schema;
		ELSE
			real_schema = schema_name;
		END IF;
	ELSE
		SELECT current_schema() into real_schema;
	END IF;

	-- Find out if the column is in the geometry_columns table
	okay = 'f';
	FOR myrec IN SELECT * from geometry_columns where f_table_schema = text(real_schema) and f_table_name = table_name and f_geometry_column = column_name LOOP
		okay := 't';
	END LOOP;
	IF (okay <> 't') THEN
		RAISE EXCEPTION 'column not found in geometry_columns table';
		RETURN 'f';
	END IF;

	-- Remove ref from geometry_columns table
	EXECUTE 'delete from geometry_columns where f_table_schema = ' ||
		quote_literal(real_schema) || ' and f_table_name = ' ||
		quote_literal(table_name)  || ' and f_geometry_column = ' ||
		quote_literal(column_name);

	-- Remove table column
	EXECUTE 'ALTER TABLE ' || quote_ident(real_schema) || '.' ||
		quote_ident(table_name) || ' DROP COLUMN ' ||
		quote_ident(column_name);

	RETURN real_schema || '.' || table_name || '.' || column_name ||' effectively removed.';

END;
$_$;


--
-- Name: dropgeometrytable(character varying); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION dropgeometrytable(character varying) RETURNS text
    LANGUAGE sql STRICT
    AS $_$ SELECT DropGeometryTable('','',$1) $_$;


--
-- Name: dropgeometrytable(character varying, character varying); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION dropgeometrytable(character varying, character varying) RETURNS text
    LANGUAGE sql STRICT
    AS $_$ SELECT DropGeometryTable('',$1,$2) $_$;


--
-- Name: dropgeometrytable(character varying, character varying, character varying); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION dropgeometrytable(character varying, character varying, character varying) RETURNS text
    LANGUAGE plpgsql STRICT
    AS $_$
DECLARE
	catalog_name alias for $1;
	schema_name alias for $2;
	table_name alias for $3;
	real_schema name;

BEGIN

	IF ( schema_name = '' ) THEN
		SELECT current_schema() into real_schema;
	ELSE
		real_schema = schema_name;
	END IF;

	-- Remove refs from geometry_columns table
	EXECUTE 'DELETE FROM geometry_columns WHERE ' ||
		'f_table_schema = ' || quote_literal(real_schema) ||
		' AND ' ||
		' f_table_name = ' || quote_literal(table_name);

	-- Remove table
	EXECUTE 'DROP TABLE '
		|| quote_ident(real_schema) || '.' ||
		quote_ident(table_name);

	RETURN
		real_schema || '.' ||
		table_name ||' dropped.';

END;
$_$;


--
-- Name: enablelongtransactions(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION enablelongtransactions() RETURNS text
    LANGUAGE plpgsql
    AS $$ 
DECLARE
	"query" text;
	exists bool;
	rec RECORD;

BEGIN

	exists = 'f';
	FOR rec IN SELECT * FROM pg_class WHERE relname = 'authorization_table'
	LOOP
		exists = 't';
	END LOOP;

	IF NOT exists
	THEN
		"query" = 'CREATE TABLE authorization_table (
			toid oid, -- table oid
			rid text, -- row id
			expires timestamp,
			authid text
		)';
		EXECUTE "query";
	END IF;

	exists = 'f';
	FOR rec IN SELECT * FROM pg_class WHERE relname = 'authorized_tables'
	LOOP
		exists = 't';
	END LOOP;

	IF NOT exists THEN
		"query" = 'CREATE VIEW authorized_tables AS ' ||
			'SELECT ' ||
			'n.nspname as schema, ' ||
			'c.relname as table, trim(' ||
			quote_literal(chr(92) || '000') ||
			' from t.tgargs) as id_column ' ||
			'FROM pg_trigger t, pg_class c, pg_proc p ' ||
			', pg_namespace n ' ||
			'WHERE p.proname = ' || quote_literal('checkauthtrigger') ||
			' AND c.relnamespace = n.oid' ||
			' AND t.tgfoid = p.oid and t.tgrelid = c.oid';
		EXECUTE "query";
	END IF;

	RETURN 'Long transactions support enabled';
END;
$$;


--
-- Name: find_srid(character varying, character varying, character varying); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION find_srid(character varying, character varying, character varying) RETURNS integer
    LANGUAGE plpgsql IMMUTABLE STRICT
    AS $_$
DECLARE
	schem text;
	tabl text;
	sr int4;
BEGIN
	IF $1 IS NULL THEN
	  RAISE EXCEPTION 'find_srid() - schema is NULL!';
	END IF;
	IF $2 IS NULL THEN
	  RAISE EXCEPTION 'find_srid() - table name is NULL!';
	END IF;
	IF $3 IS NULL THEN
	  RAISE EXCEPTION 'find_srid() - column name is NULL!';
	END IF;
	schem = $1;
	tabl = $2;
-- if the table contains a . and the schema is empty
-- split the table into a schema and a table
-- otherwise drop through to default behavior
	IF ( schem = '' and tabl LIKE '%.%' ) THEN
	 schem = substr(tabl,1,strpos(tabl,'.')-1);
	 tabl = substr(tabl,length(schem)+2);
	ELSE
	 schem = schem || '%';
	END IF;

	select SRID into sr from geometry_columns where f_table_schema like schem and f_table_name = tabl and f_geometry_column = $3;
	IF NOT FOUND THEN
	   RAISE EXCEPTION 'find_srid() - couldnt find the corresponding SRID - is the geometry registered in the GEOMETRY_COLUMNS table?  Is there an uppercase/lowercase missmatch?';
	END IF;
	return sr;
END;
$_$;


--
-- Name: fix_geometry_columns(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION fix_geometry_columns() RETURNS text
    LANGUAGE plpgsql
    AS $$
DECLARE
	mislinked record;
	result text;
	linked integer;
	deleted integer;
	foundschema integer;
BEGIN

	-- Since 7.3 schema support has been added.
	-- Previous postgis versions used to put the database name in
	-- the schema column. This needs to be fixed, so we try to
	-- set the correct schema for each geometry_colums record
	-- looking at table, column, type and srid.
	UPDATE geometry_columns SET f_table_schema = n.nspname
		FROM pg_namespace n, pg_class c, pg_attribute a,
			pg_constraint sridcheck, pg_constraint typecheck
			WHERE ( f_table_schema is NULL
		OR f_table_schema = ''
			OR f_table_schema NOT IN (
					SELECT nspname::varchar
					FROM pg_namespace nn, pg_class cc, pg_attribute aa
					WHERE cc.relnamespace = nn.oid
					AND cc.relname = f_table_name::name
					AND aa.attrelid = cc.oid
					AND aa.attname = f_geometry_column::name))
			AND f_table_name::name = c.relname
			AND c.oid = a.attrelid
			AND c.relnamespace = n.oid
			AND f_geometry_column::name = a.attname

			AND sridcheck.conrelid = c.oid
		AND sridcheck.consrc LIKE '(srid(% = %)'
			AND sridcheck.consrc ~ textcat(' = ', srid::text)

			AND typecheck.conrelid = c.oid
		AND typecheck.consrc LIKE
		'((geometrytype(%) = ''%''::text) OR (% IS NULL))'
			AND typecheck.consrc ~ textcat(' = ''', type::text)

			AND NOT EXISTS (
					SELECT oid FROM geometry_columns gc
					WHERE c.relname::varchar = gc.f_table_name
					AND n.nspname::varchar = gc.f_table_schema
					AND a.attname::varchar = gc.f_geometry_column
			);

	GET DIAGNOSTICS foundschema = ROW_COUNT;

	-- no linkage to system table needed
	return 'fixed:'||foundschema::text;

END;
$$;


--
-- Name: get_proj4_from_srid(integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION get_proj4_from_srid(integer) RETURNS text
    LANGUAGE plpgsql IMMUTABLE STRICT
    AS $_$
BEGIN
	RETURN proj4text::text FROM spatial_ref_sys WHERE srid= $1;
END;
$_$;


--
-- Name: lockrow(text, text, text); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION lockrow(text, text, text) RETURNS integer
    LANGUAGE sql STRICT
    AS $_$ SELECT LockRow(current_schema(), $1, $2, $3, now()::timestamp+'1:00'); $_$;


--
-- Name: lockrow(text, text, text, text); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION lockrow(text, text, text, text) RETURNS integer
    LANGUAGE sql STRICT
    AS $_$ SELECT LockRow($1, $2, $3, $4, now()::timestamp+'1:00'); $_$;


--
-- Name: lockrow(text, text, text, timestamp without time zone); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION lockrow(text, text, text, timestamp without time zone) RETURNS integer
    LANGUAGE sql STRICT
    AS $_$ SELECT LockRow(current_schema(), $1, $2, $3, $4); $_$;


--
-- Name: lockrow(text, text, text, text, timestamp without time zone); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION lockrow(text, text, text, text, timestamp without time zone) RETURNS integer
    LANGUAGE plpgsql STRICT
    AS $_$ 
DECLARE
	myschema alias for $1;
	mytable alias for $2;
	myrid   alias for $3;
	authid alias for $4;
	expires alias for $5;
	ret int;
	mytoid oid;
	myrec RECORD;
	
BEGIN

	IF NOT LongTransactionsEnabled() THEN
		RAISE EXCEPTION 'Long transaction support disabled, use EnableLongTransaction() to enable.';
	END IF;

	EXECUTE 'DELETE FROM authorization_table WHERE expires < now()'; 

	SELECT c.oid INTO mytoid FROM pg_class c, pg_namespace n
		WHERE c.relname = mytable
		AND c.relnamespace = n.oid
		AND n.nspname = myschema;

	-- RAISE NOTICE 'toid: %', mytoid;

	FOR myrec IN SELECT * FROM authorization_table WHERE 
		toid = mytoid AND rid = myrid
	LOOP
		IF myrec.authid != authid THEN
			RETURN 0;
		ELSE
			RETURN 1;
		END IF;
	END LOOP;

	EXECUTE 'INSERT INTO authorization_table VALUES ('||
		quote_literal(mytoid::text)||','||quote_literal(myrid)||
		','||quote_literal(expires::text)||
		','||quote_literal(authid) ||')';

	GET DIAGNOSTICS ret = ROW_COUNT;

	RETURN ret;
END;
$_$;


--
-- Name: longtransactionsenabled(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION longtransactionsenabled() RETURNS boolean
    LANGUAGE plpgsql
    AS $$ 
DECLARE
	rec RECORD;
BEGIN
	FOR rec IN SELECT oid FROM pg_class WHERE relname = 'authorized_tables'
	LOOP
		return 't';
	END LOOP;
	return 'f';
END;
$$;


--
-- Name: populate_geometry_columns(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION populate_geometry_columns() RETURNS text
    LANGUAGE plpgsql
    AS $$
DECLARE
	inserted    integer;
	oldcount    integer;
	probed      integer;
	stale       integer;
	gcs         RECORD;
	gc          RECORD;
	gsrid       integer;
	gndims      integer;
	gtype       text;
	query       text;
	gc_is_valid boolean;

BEGIN
	SELECT count(*) INTO oldcount FROM geometry_columns;
	inserted := 0;

	EXECUTE 'TRUNCATE geometry_columns';

	-- Count the number of geometry columns in all tables and views
	SELECT count(DISTINCT c.oid) INTO probed
	FROM pg_class c,
		 pg_attribute a,
		 pg_type t,
		 pg_namespace n
	WHERE (c.relkind = 'r' OR c.relkind = 'v')
	AND t.typname = 'geometry'
	AND a.attisdropped = false
	AND a.atttypid = t.oid
	AND a.attrelid = c.oid
	AND c.relnamespace = n.oid
	AND n.nspname NOT ILIKE 'pg_temp%';

	-- Iterate through all non-dropped geometry columns
	RAISE DEBUG 'Processing Tables.....';

	FOR gcs IN
	SELECT DISTINCT ON (c.oid) c.oid, n.nspname, c.relname
		FROM pg_class c,
			 pg_attribute a,
			 pg_type t,
			 pg_namespace n
		WHERE c.relkind = 'r'
		AND t.typname = 'geometry'
		AND a.attisdropped = false
		AND a.atttypid = t.oid
		AND a.attrelid = c.oid
		AND c.relnamespace = n.oid
		AND n.nspname NOT ILIKE 'pg_temp%'
	LOOP

	inserted := inserted + populate_geometry_columns(gcs.oid);
	END LOOP;

	-- Add views to geometry columns table
	RAISE DEBUG 'Processing Views.....';
	FOR gcs IN
	SELECT DISTINCT ON (c.oid) c.oid, n.nspname, c.relname
		FROM pg_class c,
			 pg_attribute a,
			 pg_type t,
			 pg_namespace n
		WHERE c.relkind = 'v'
		AND t.typname = 'geometry'
		AND a.attisdropped = false
		AND a.atttypid = t.oid
		AND a.attrelid = c.oid
		AND c.relnamespace = n.oid
	LOOP

	inserted := inserted + populate_geometry_columns(gcs.oid);
	END LOOP;

	IF oldcount > inserted THEN
	stale = oldcount-inserted;
	ELSE
	stale = 0;
	END IF;

	RETURN 'probed:' ||probed|| ' inserted:'||inserted|| ' conflicts:'||probed-inserted|| ' deleted:'||stale;
END

$$;


--
-- Name: populate_geometry_columns(oid); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION populate_geometry_columns(tbl_oid oid) RETURNS integer
    LANGUAGE plpgsql
    AS $$
DECLARE
	gcs         RECORD;
	gc          RECORD;
	gsrid       integer;
	gndims      integer;
	gtype       text;
	query       text;
	gc_is_valid boolean;
	inserted    integer;

BEGIN
	inserted := 0;

	-- Iterate through all geometry columns in this table
	FOR gcs IN
	SELECT n.nspname, c.relname, a.attname
		FROM pg_class c,
			 pg_attribute a,
			 pg_type t,
			 pg_namespace n
		WHERE c.relkind = 'r'
		AND t.typname = 'geometry'
		AND a.attisdropped = false
		AND a.atttypid = t.oid
		AND a.attrelid = c.oid
		AND c.relnamespace = n.oid
		AND n.nspname NOT ILIKE 'pg_temp%'
		AND c.oid = tbl_oid
	LOOP

	RAISE DEBUG 'Processing table %.%.%', gcs.nspname, gcs.relname, gcs.attname;

	DELETE FROM geometry_columns
	  WHERE f_table_schema = gcs.nspname
	  AND f_table_name = gcs.relname
	  AND f_geometry_column = gcs.attname;

	gc_is_valid := true;

	-- Try to find srid check from system tables (pg_constraint)
	gsrid :=
		(SELECT replace(replace(split_part(s.consrc, ' = ', 2), ')', ''), '(', '')
		 FROM pg_class c, pg_namespace n, pg_attribute a, pg_constraint s
		 WHERE n.nspname = gcs.nspname
		 AND c.relname = gcs.relname
		 AND a.attname = gcs.attname
		 AND a.attrelid = c.oid
		 AND s.connamespace = n.oid
		 AND s.conrelid = c.oid
		 AND a.attnum = ANY (s.conkey)
		 AND s.consrc LIKE '%srid(% = %');
	IF (gsrid IS NULL) THEN
		-- Try to find srid from the geometry itself
		EXECUTE 'SELECT srid(' || quote_ident(gcs.attname) || ')
				 FROM ONLY ' || quote_ident(gcs.nspname) || '.' || quote_ident(gcs.relname) || '
				 WHERE ' || quote_ident(gcs.attname) || ' IS NOT NULL LIMIT 1'
			INTO gc;
		gsrid := gc.srid;

		-- Try to apply srid check to column
		IF (gsrid IS NOT NULL) THEN
			BEGIN
				EXECUTE 'ALTER TABLE ONLY ' || quote_ident(gcs.nspname) || '.' || quote_ident(gcs.relname) || '
						 ADD CONSTRAINT ' || quote_ident('enforce_srid_' || gcs.attname) || '
						 CHECK (srid(' || quote_ident(gcs.attname) || ') = ' || gsrid || ')';
			EXCEPTION
				WHEN check_violation THEN
					RAISE WARNING 'Not inserting ''%'' in ''%.%'' into geometry_columns: could not apply constraint CHECK (srid(%) = %)', quote_ident(gcs.attname), quote_ident(gcs.nspname), quote_ident(gcs.relname), quote_ident(gcs.attname), gsrid;
					gc_is_valid := false;
			END;
		END IF;
	END IF;

	-- Try to find ndims check from system tables (pg_constraint)
	gndims :=
		(SELECT replace(split_part(s.consrc, ' = ', 2), ')', '')
		 FROM pg_class c, pg_namespace n, pg_attribute a, pg_constraint s
		 WHERE n.nspname = gcs.nspname
		 AND c.relname = gcs.relname
		 AND a.attname = gcs.attname
		 AND a.attrelid = c.oid
		 AND s.connamespace = n.oid
		 AND s.conrelid = c.oid
		 AND a.attnum = ANY (s.conkey)
		 AND s.consrc LIKE '%ndims(% = %');
	IF (gndims IS NULL) THEN
		-- Try to find ndims from the geometry itself
		EXECUTE 'SELECT ndims(' || quote_ident(gcs.attname) || ')
				 FROM ONLY ' || quote_ident(gcs.nspname) || '.' || quote_ident(gcs.relname) || '
				 WHERE ' || quote_ident(gcs.attname) || ' IS NOT NULL LIMIT 1'
			INTO gc;
		gndims := gc.ndims;

		-- Try to apply ndims check to column
		IF (gndims IS NOT NULL) THEN
			BEGIN
				EXECUTE 'ALTER TABLE ONLY ' || quote_ident(gcs.nspname) || '.' || quote_ident(gcs.relname) || '
						 ADD CONSTRAINT ' || quote_ident('enforce_dims_' || gcs.attname) || '
						 CHECK (ndims(' || quote_ident(gcs.attname) || ') = '||gndims||')';
			EXCEPTION
				WHEN check_violation THEN
					RAISE WARNING 'Not inserting ''%'' in ''%.%'' into geometry_columns: could not apply constraint CHECK (ndims(%) = %)', quote_ident(gcs.attname), quote_ident(gcs.nspname), quote_ident(gcs.relname), quote_ident(gcs.attname), gndims;
					gc_is_valid := false;
			END;
		END IF;
	END IF;

	-- Try to find geotype check from system tables (pg_constraint)
	gtype :=
		(SELECT replace(split_part(s.consrc, '''', 2), ')', '')
		 FROM pg_class c, pg_namespace n, pg_attribute a, pg_constraint s
		 WHERE n.nspname = gcs.nspname
		 AND c.relname = gcs.relname
		 AND a.attname = gcs.attname
		 AND a.attrelid = c.oid
		 AND s.connamespace = n.oid
		 AND s.conrelid = c.oid
		 AND a.attnum = ANY (s.conkey)
		 AND s.consrc LIKE '%geometrytype(% = %');
	IF (gtype IS NULL) THEN
		-- Try to find geotype from the geometry itself
		EXECUTE 'SELECT geometrytype(' || quote_ident(gcs.attname) || ')
				 FROM ONLY ' || quote_ident(gcs.nspname) || '.' || quote_ident(gcs.relname) || '
				 WHERE ' || quote_ident(gcs.attname) || ' IS NOT NULL LIMIT 1'
			INTO gc;
		gtype := gc.geometrytype;
		--IF (gtype IS NULL) THEN
		--    gtype := 'GEOMETRY';
		--END IF;

		-- Try to apply geometrytype check to column
		IF (gtype IS NOT NULL) THEN
			BEGIN
				EXECUTE 'ALTER TABLE ONLY ' || quote_ident(gcs.nspname) || '.' || quote_ident(gcs.relname) || '
				ADD CONSTRAINT ' || quote_ident('enforce_geotype_' || gcs.attname) || '
				CHECK ((geometrytype(' || quote_ident(gcs.attname) || ') = ' || quote_literal(gtype) || ') OR (' || quote_ident(gcs.attname) || ' IS NULL))';
			EXCEPTION
				WHEN check_violation THEN
					-- No geometry check can be applied. This column contains a number of geometry types.
					RAISE WARNING 'Could not add geometry type check (%) to table column: %.%.%', gtype, quote_ident(gcs.nspname),quote_ident(gcs.relname),quote_ident(gcs.attname);
			END;
		END IF;
	END IF;

	IF (gsrid IS NULL) THEN
		RAISE WARNING 'Not inserting ''%'' in ''%.%'' into geometry_columns: could not determine the srid', quote_ident(gcs.attname), quote_ident(gcs.nspname), quote_ident(gcs.relname);
	ELSIF (gndims IS NULL) THEN
		RAISE WARNING 'Not inserting ''%'' in ''%.%'' into geometry_columns: could not determine the number of dimensions', quote_ident(gcs.attname), quote_ident(gcs.nspname), quote_ident(gcs.relname);
	ELSIF (gtype IS NULL) THEN
		RAISE WARNING 'Not inserting ''%'' in ''%.%'' into geometry_columns: could not determine the geometry type', quote_ident(gcs.attname), quote_ident(gcs.nspname), quote_ident(gcs.relname);
	ELSE
		-- Only insert into geometry_columns if table constraints could be applied.
		IF (gc_is_valid) THEN
			INSERT INTO geometry_columns (f_table_catalog,f_table_schema, f_table_name, f_geometry_column, coord_dimension, srid, type)
			VALUES ('', gcs.nspname, gcs.relname, gcs.attname, gndims, gsrid, gtype);
			inserted := inserted + 1;
		END IF;
	END IF;
	END LOOP;

	-- Add views to geometry columns table
	FOR gcs IN
	SELECT n.nspname, c.relname, a.attname
		FROM pg_class c,
			 pg_attribute a,
			 pg_type t,
			 pg_namespace n
		WHERE c.relkind = 'v'
		AND t.typname = 'geometry'
		AND a.attisdropped = false
		AND a.atttypid = t.oid
		AND a.attrelid = c.oid
		AND c.relnamespace = n.oid
		AND n.nspname NOT ILIKE 'pg_temp%'
		AND c.oid = tbl_oid
	LOOP
		RAISE DEBUG 'Processing view %.%.%', gcs.nspname, gcs.relname, gcs.attname;

	DELETE FROM geometry_columns
	  WHERE f_table_schema = gcs.nspname
	  AND f_table_name = gcs.relname
	  AND f_geometry_column = gcs.attname;
	  
		EXECUTE 'SELECT ndims(' || quote_ident(gcs.attname) || ')
				 FROM ' || quote_ident(gcs.nspname) || '.' || quote_ident(gcs.relname) || '
				 WHERE ' || quote_ident(gcs.attname) || ' IS NOT NULL LIMIT 1'
			INTO gc;
		gndims := gc.ndims;

		EXECUTE 'SELECT srid(' || quote_ident(gcs.attname) || ')
				 FROM ' || quote_ident(gcs.nspname) || '.' || quote_ident(gcs.relname) || '
				 WHERE ' || quote_ident(gcs.attname) || ' IS NOT NULL LIMIT 1'
			INTO gc;
		gsrid := gc.srid;

		EXECUTE 'SELECT geometrytype(' || quote_ident(gcs.attname) || ')
				 FROM ' || quote_ident(gcs.nspname) || '.' || quote_ident(gcs.relname) || '
				 WHERE ' || quote_ident(gcs.attname) || ' IS NOT NULL LIMIT 1'
			INTO gc;
		gtype := gc.geometrytype;

		IF (gndims IS NULL) THEN
			RAISE WARNING 'Not inserting ''%'' in ''%.%'' into geometry_columns: could not determine ndims', quote_ident(gcs.attname), quote_ident(gcs.nspname), quote_ident(gcs.relname);
		ELSIF (gsrid IS NULL) THEN
			RAISE WARNING 'Not inserting ''%'' in ''%.%'' into geometry_columns: could not determine srid', quote_ident(gcs.attname), quote_ident(gcs.nspname), quote_ident(gcs.relname);
		ELSIF (gtype IS NULL) THEN
			RAISE WARNING 'Not inserting ''%'' in ''%.%'' into geometry_columns: could not determine gtype', quote_ident(gcs.attname), quote_ident(gcs.nspname), quote_ident(gcs.relname);
		ELSE
			query := 'INSERT INTO geometry_columns (f_table_catalog,f_table_schema, f_table_name, f_geometry_column, coord_dimension, srid, type) ' ||
					 'VALUES ('''', ' || quote_literal(gcs.nspname) || ',' || quote_literal(gcs.relname) || ',' || quote_literal(gcs.attname) || ',' || gndims || ',' || gsrid || ',' || quote_literal(gtype) || ')';
			EXECUTE query;
			inserted := inserted + 1;
		END IF;
	END LOOP;

	RETURN inserted;
END

$$;


--
-- Name: postgis_full_version(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION postgis_full_version() RETURNS text
    LANGUAGE plpgsql IMMUTABLE
    AS $$
DECLARE
	libver text;
	projver text;
	geosver text;
	libxmlver text;
	usestats bool;
	dbproc text;
	relproc text;
	fullver text;
BEGIN
	SELECT postgis_lib_version() INTO libver;
	SELECT postgis_proj_version() INTO projver;
	SELECT postgis_geos_version() INTO geosver;
	SELECT postgis_libxml_version() INTO libxmlver;
	SELECT postgis_uses_stats() INTO usestats;
	SELECT postgis_scripts_installed() INTO dbproc;
	SELECT postgis_scripts_released() INTO relproc;

	fullver = 'POSTGIS="' || libver || '"';

	IF  geosver IS NOT NULL THEN
		fullver = fullver || ' GEOS="' || geosver || '"';
	END IF;

	IF  projver IS NOT NULL THEN
		fullver = fullver || ' PROJ="' || projver || '"';
	END IF;

	IF  libxmlver IS NOT NULL THEN
		fullver = fullver || ' LIBXML="' || libxmlver || '"';
	END IF;

	IF usestats THEN
		fullver = fullver || ' USE_STATS';
	END IF;

	-- fullver = fullver || ' DBPROC="' || dbproc || '"';
	-- fullver = fullver || ' RELPROC="' || relproc || '"';

	IF dbproc != relproc THEN
		fullver = fullver || ' (procs from ' || dbproc || ' need upgrade)';
	END IF;

	RETURN fullver;
END
$$;


--
-- Name: postgis_scripts_build_date(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION postgis_scripts_build_date() RETURNS text
    LANGUAGE sql IMMUTABLE
    AS $$SELECT '2012-07-31 12:22:28'::text AS version$$;


--
-- Name: postgis_scripts_installed(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION postgis_scripts_installed() RETURNS text
    LANGUAGE sql IMMUTABLE
    AS $$SELECT '1.5 r7360'::text AS version$$;


--
-- Name: probe_geometry_columns(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION probe_geometry_columns() RETURNS text
    LANGUAGE plpgsql
    AS $$
DECLARE
	inserted integer;
	oldcount integer;
	probed integer;
	stale integer;
BEGIN

	SELECT count(*) INTO oldcount FROM geometry_columns;

	SELECT count(*) INTO probed
		FROM pg_class c, pg_attribute a, pg_type t,
			pg_namespace n,
			pg_constraint sridcheck, pg_constraint typecheck

		WHERE t.typname = 'geometry'
		AND a.atttypid = t.oid
		AND a.attrelid = c.oid
		AND c.relnamespace = n.oid
		AND sridcheck.connamespace = n.oid
		AND typecheck.connamespace = n.oid
		AND sridcheck.conrelid = c.oid
		AND sridcheck.consrc LIKE '(srid('||a.attname||') = %)'
		AND typecheck.conrelid = c.oid
		AND typecheck.consrc LIKE
		'((geometrytype('||a.attname||') = ''%''::text) OR (% IS NULL))'
		;

	INSERT INTO geometry_columns SELECT
		''::varchar as f_table_catalogue,
		n.nspname::varchar as f_table_schema,
		c.relname::varchar as f_table_name,
		a.attname::varchar as f_geometry_column,
		2 as coord_dimension,
		trim(both  ' =)' from
			replace(replace(split_part(
				sridcheck.consrc, ' = ', 2), ')', ''), '(', ''))::integer AS srid,
		trim(both ' =)''' from substr(typecheck.consrc,
			strpos(typecheck.consrc, '='),
			strpos(typecheck.consrc, '::')-
			strpos(typecheck.consrc, '=')
			))::varchar as type
		FROM pg_class c, pg_attribute a, pg_type t,
			pg_namespace n,
			pg_constraint sridcheck, pg_constraint typecheck
		WHERE t.typname = 'geometry'
		AND a.atttypid = t.oid
		AND a.attrelid = c.oid
		AND c.relnamespace = n.oid
		AND sridcheck.connamespace = n.oid
		AND typecheck.connamespace = n.oid
		AND sridcheck.conrelid = c.oid
		AND sridcheck.consrc LIKE '(st_srid('||a.attname||') = %)'
		AND typecheck.conrelid = c.oid
		AND typecheck.consrc LIKE
		'((geometrytype('||a.attname||') = ''%''::text) OR (% IS NULL))'

			AND NOT EXISTS (
					SELECT oid FROM geometry_columns gc
					WHERE c.relname::varchar = gc.f_table_name
					AND n.nspname::varchar = gc.f_table_schema
					AND a.attname::varchar = gc.f_geometry_column
			);

	GET DIAGNOSTICS inserted = ROW_COUNT;

	IF oldcount > probed THEN
		stale = oldcount-probed;
	ELSE
		stale = 0;
	END IF;

	RETURN 'probed:'||probed::text||
		' inserted:'||inserted::text||
		' conflicts:'||(probed-inserted)::text||
		' stale:'||stale::text;
END

$$;


--
-- Name: rename_geometry_table_constraints(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION rename_geometry_table_constraints() RETURNS text
    LANGUAGE sql IMMUTABLE
    AS $$
SELECT 'rename_geometry_table_constraint() is obsoleted'::text
$$;


--
-- Name: st_area(text); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION st_area(text) RETURNS double precision
    LANGUAGE sql IMMUTABLE STRICT
    AS $_$ SELECT ST_Area($1::geometry);  $_$;


--
-- Name: st_asbinary(text); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION st_asbinary(text) RETURNS bytea
    LANGUAGE sql IMMUTABLE STRICT
    AS $_$ SELECT ST_AsBinary($1::geometry);  $_$;


--
-- Name: st_asgeojson(text); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION st_asgeojson(text) RETURNS text
    LANGUAGE sql IMMUTABLE STRICT
    AS $_$ SELECT ST_AsGeoJson($1::geometry);  $_$;


--
-- Name: st_asgml(text); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION st_asgml(text) RETURNS text
    LANGUAGE sql IMMUTABLE STRICT
    AS $_$ SELECT ST_AsGML($1::geometry);  $_$;


--
-- Name: st_askml(text); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION st_askml(text) RETURNS text
    LANGUAGE sql IMMUTABLE STRICT
    AS $_$ SELECT ST_AsKML($1::geometry);  $_$;


--
-- Name: st_assvg(text); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION st_assvg(text) RETURNS text
    LANGUAGE sql IMMUTABLE STRICT
    AS $_$ SELECT ST_AsSVG($1::geometry);  $_$;


--
-- Name: st_astext(text); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION st_astext(text) RETURNS text
    LANGUAGE sql IMMUTABLE STRICT
    AS $_$ SELECT ST_AsText($1::geometry);  $_$;


--
-- Name: st_coveredby(text, text); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION st_coveredby(text, text) RETURNS boolean
    LANGUAGE sql IMMUTABLE
    AS $_$ SELECT ST_CoveredBy($1::geometry, $2::geometry);  $_$;


--
-- Name: st_covers(text, text); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION st_covers(text, text) RETURNS boolean
    LANGUAGE sql IMMUTABLE
    AS $_$ SELECT ST_Covers($1::geometry, $2::geometry);  $_$;


--
-- Name: st_distance(text, text); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION st_distance(text, text) RETURNS double precision
    LANGUAGE sql IMMUTABLE STRICT
    AS $_$ SELECT ST_Distance($1::geometry, $2::geometry);  $_$;


--
-- Name: st_dwithin(text, text, double precision); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION st_dwithin(text, text, double precision) RETURNS boolean
    LANGUAGE sql IMMUTABLE
    AS $_$ SELECT ST_DWithin($1::geometry, $2::geometry, $3);  $_$;


--
-- Name: st_intersects(text, text); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION st_intersects(text, text) RETURNS boolean
    LANGUAGE sql IMMUTABLE
    AS $_$ SELECT ST_Intersects($1::geometry, $2::geometry);  $_$;


--
-- Name: st_length(text); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION st_length(text) RETURNS double precision
    LANGUAGE sql IMMUTABLE STRICT
    AS $_$ SELECT ST_Length($1::geometry);  $_$;


--
-- Name: unlockrows(text); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION unlockrows(text) RETURNS integer
    LANGUAGE plpgsql STRICT
    AS $_$ 
DECLARE
	ret int;
BEGIN

	IF NOT LongTransactionsEnabled() THEN
		RAISE EXCEPTION 'Long transaction support disabled, use EnableLongTransaction() to enable.';
	END IF;

	EXECUTE 'DELETE FROM authorization_table where authid = ' ||
		quote_literal($1);

	GET DIAGNOSTICS ret = ROW_COUNT;

	RETURN ret;
END;
$_$;


--
-- Name: updategeometrysrid(character varying, character varying, integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION updategeometrysrid(character varying, character varying, integer) RETURNS text
    LANGUAGE plpgsql STRICT
    AS $_$
DECLARE
	ret  text;
BEGIN
	SELECT UpdateGeometrySRID('','',$1,$2,$3) into ret;
	RETURN ret;
END;
$_$;


--
-- Name: updategeometrysrid(character varying, character varying, character varying, integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION updategeometrysrid(character varying, character varying, character varying, integer) RETURNS text
    LANGUAGE plpgsql STRICT
    AS $_$
DECLARE
	ret  text;
BEGIN
	SELECT UpdateGeometrySRID('',$1,$2,$3,$4) into ret;
	RETURN ret;
END;
$_$;


--
-- Name: updategeometrysrid(character varying, character varying, character varying, character varying, integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION updategeometrysrid(character varying, character varying, character varying, character varying, integer) RETURNS text
    LANGUAGE plpgsql STRICT
    AS $_$
DECLARE
	catalog_name alias for $1;
	schema_name alias for $2;
	table_name alias for $3;
	column_name alias for $4;
	new_srid alias for $5;
	myrec RECORD;
	okay boolean;
	cname varchar;
	real_schema name;

BEGIN


	-- Find, check or fix schema_name
	IF ( schema_name != '' ) THEN
		okay = 'f';

		FOR myrec IN SELECT nspname FROM pg_namespace WHERE text(nspname) = schema_name LOOP
			okay := 't';
		END LOOP;

		IF ( okay <> 't' ) THEN
			RAISE EXCEPTION 'Invalid schema name';
		ELSE
			real_schema = schema_name;
		END IF;
	ELSE
		SELECT INTO real_schema current_schema()::text;
	END IF;

	-- Find out if the column is in the geometry_columns table
	okay = 'f';
	FOR myrec IN SELECT * from geometry_columns where f_table_schema = text(real_schema) and f_table_name = table_name and f_geometry_column = column_name LOOP
		okay := 't';
	END LOOP;
	IF (okay <> 't') THEN
		RAISE EXCEPTION 'column not found in geometry_columns table';
		RETURN 'f';
	END IF;

	-- Update ref from geometry_columns table
	EXECUTE 'UPDATE geometry_columns SET SRID = ' || new_srid::text ||
		' where f_table_schema = ' ||
		quote_literal(real_schema) || ' and f_table_name = ' ||
		quote_literal(table_name)  || ' and f_geometry_column = ' ||
		quote_literal(column_name);

	-- Make up constraint name
	cname = 'enforce_srid_'  || column_name;

	-- Drop enforce_srid constraint
	EXECUTE 'ALTER TABLE ' || quote_ident(real_schema) ||
		'.' || quote_ident(table_name) ||
		' DROP constraint ' || quote_ident(cname);

	-- Update geometries SRID
	EXECUTE 'UPDATE ' || quote_ident(real_schema) ||
		'.' || quote_ident(table_name) ||
		' SET ' || quote_ident(column_name) ||
		' = setSRID(' || quote_ident(column_name) ||
		', ' || new_srid::text || ')';

	-- Reset enforce_srid constraint
	EXECUTE 'ALTER TABLE ' || quote_ident(real_schema) ||
		'.' || quote_ident(table_name) ||
		' ADD constraint ' || quote_ident(cname) ||
		' CHECK (srid(' || quote_ident(column_name) ||
		') = ' || new_srid::text || ')';

	RETURN real_schema || '.' || table_name || '.' || column_name ||' SRID changed to ' || new_srid::text;

END;
$_$;


--
-- Name: accion_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE accion_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: accion; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE accion (
    id integer DEFAULT nextval('accion_seq'::regclass) NOT NULL,
    id_proceso integer NOT NULL,
    id_taccion integer NOT NULL,
    id_despacho integer NOT NULL,
    fecha date NOT NULL,
    numeroradicado character varying(50),
    observacionesaccion character varying(4000),
    respondido boolean,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: acreditacion_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE acreditacion_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: acreditacion; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE acreditacion (
    id integer DEFAULT nextval('acreditacion_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    fechacreacion date DEFAULT '2013-05-24'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT acreditacion_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: actividad; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE actividad (
    id integer NOT NULL,
    numero integer,
    minutos integer,
    nombre character varying(500),
    objetivo character varying(500),
    proyecto character varying(500),
    resultado character varying(500),
    fecha date,
    actividad character varying(500),
    observaciones character varying(5000),
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: actividad_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE actividad_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: actividad_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE actividad_id_seq OWNED BY actividad.id;


--
-- Name: actividad_rangoedad; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE actividad_rangoedad (
    id integer NOT NULL,
    actividad_id integer,
    rangoedad_id integer,
    m integer,
    f integer,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: actividad_rangoedad_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE actividad_rangoedad_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: actividad_rangoedad_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE actividad_rangoedad_id_seq OWNED BY actividad_rangoedad.id;


--
-- Name: actividadarea; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE actividadarea (
    id integer NOT NULL,
    nombre character varying(500),
    observaciones character varying(5000),
    fechacreacion date,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: actividadarea_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE actividadarea_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: actividadarea_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE actividadarea_id_seq OWNED BY actividadarea.id;


--
-- Name: actividadareas_actividad; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE actividadareas_actividad (
    id integer NOT NULL,
    actividad_id integer,
    actividadarea_id integer,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: actividadareas_actividad_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE actividadareas_actividad_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: actividadareas_actividad_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE actividadareas_actividad_id_seq OWNED BY actividadareas_actividad.id;


--
-- Name: actividadoficio_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE actividadoficio_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: actividadoficio; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE actividadoficio (
    id integer DEFAULT nextval('actividadoficio_seq'::regclass) NOT NULL,
    nombre character varying(50) NOT NULL,
    fechacreacion date DEFAULT '2013-05-13'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT actividadoficio_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: acto; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE acto (
    id_presponsable integer NOT NULL,
    id_categoria integer NOT NULL,
    id_persona integer NOT NULL,
    id_caso integer NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: actocolectivo; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE actocolectivo (
    id_presponsable integer NOT NULL,
    id_categoria integer NOT NULL,
    id_grupoper integer NOT NULL,
    id_caso integer NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: actosjr; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE actosjr (
    id_presponsable integer NOT NULL,
    id_categoria integer NOT NULL,
    id_persona integer NOT NULL,
    id_caso integer NOT NULL,
    fecha date NOT NULL,
    fechaexpulsion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: actualizacionbase; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE actualizacionbase (
    id character varying(10) NOT NULL,
    fecha date NOT NULL,
    descripcion character varying(500) NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: anexo_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE anexo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: anexo; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE anexo (
    id integer DEFAULT nextval('anexo_seq'::regclass) NOT NULL,
    id_caso integer NOT NULL,
    fecha date NOT NULL,
    descripcion character varying(1500) NOT NULL,
    archivo character varying(255) NOT NULL,
    id_ffrecuente integer,
    fechaffrecuente date,
    id_fotra integer,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT antecedente_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: antecedente_caso; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE antecedente_caso (
    id_antecedente integer NOT NULL,
    id_caso integer NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: antecedente_comunidad; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE antecedente_comunidad (
    id_antecedente integer NOT NULL,
    id_grupoper integer NOT NULL,
    id_caso integer NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: antecedente_victima; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE antecedente_victima (
    id_antecedente integer NOT NULL,
    id_persona integer NOT NULL,
    id_caso integer NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: ayudaestado; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE ayudaestado (
    id integer DEFAULT nextval('acreditacion_seq'::regclass) NOT NULL,
    nombre character varying(50) NOT NULL,
    fechacreacion date DEFAULT '2013-06-16'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT ayudaestado_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: ayudaestado_respuesta; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE ayudaestado_respuesta (
    id_caso integer NOT NULL,
    fechaatencion date NOT NULL,
    id_ayudaestado integer NOT NULL,
    cantidad character varying(50),
    institucion character varying(100),
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: ayudaestado_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE ayudaestado_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ayudaestado_seql; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE ayudaestado_seql
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ayudasjr_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE ayudasjr_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ayudasjr; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE ayudasjr (
    id integer DEFAULT nextval('ayudasjr_seq'::regclass) NOT NULL,
    nombre character varying(100) NOT NULL,
    fechacreacion date DEFAULT '2013-06-16'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT ayudasjr_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: ayudasjr_respuesta; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE ayudasjr_respuesta (
    id_caso integer NOT NULL,
    fechaatencion date NOT NULL,
    id_ayudasjr integer NOT NULL,
    detalle character varying(5000),
    created_at timestamp without time zone,
    updated_at timestamp without time zone
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
    id_intervalo integer,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
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
    id_presponsable integer NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: caso_contexto; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE caso_contexto (
    id_caso integer NOT NULL,
    id_contexto integer NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: caso_etiqueta; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE caso_etiqueta (
    id_caso integer NOT NULL,
    id_etiqueta integer NOT NULL,
    id_funcionario integer NOT NULL,
    fecha date NOT NULL,
    observaciones character varying(5000),
    created_at timestamp without time zone,
    updated_at timestamp without time zone
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
    id_caso integer NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
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
    tfuente character varying(25),
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: caso_frontera; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE caso_frontera (
    id_frontera integer NOT NULL,
    id_caso integer NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: caso_funcionario; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE caso_funcionario (
    id_funcionario integer NOT NULL,
    id_caso integer NOT NULL,
    fechainicio date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
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
    id integer NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: caso_region; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE caso_region (
    id_region integer NOT NULL,
    id_caso integer NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: casosjr; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE casosjr (
    id_caso integer NOT NULL,
    fecharec date,
    asesor integer,
    id_regionsjr integer,
    direccion character varying(1000),
    telefono character varying(1000),
    comosupo character varying(5000),
    contacto integer,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT categoria_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion))),
    CONSTRAINT categoria_tipocat_check CHECK ((((tipocat = 'I'::bpchar) OR (tipocat = 'C'::bpchar)) OR (tipocat = 'O'::bpchar)))
);


--
-- Name: causaref_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE causaref_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: causaref; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE causaref (
    id integer DEFAULT nextval('causaref_seq'::regclass) NOT NULL,
    nombre character varying(50) NOT NULL,
    fechacreacion date DEFAULT '2013-06-17'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT causaref_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT clase_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: clasifdesp_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE clasifdesp_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: clasifdesp; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE clasifdesp (
    id integer DEFAULT nextval('clasifdesp_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    fechacreacion date DEFAULT '2013-05-24'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT clasifdesp_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: comunidad_filiacion; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE comunidad_filiacion (
    id_filiacion integer NOT NULL,
    id_grupoper integer NOT NULL,
    id_caso integer NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: comunidad_organizacion; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE comunidad_organizacion (
    id_organizacion integer NOT NULL,
    id_grupoper integer NOT NULL,
    id_caso integer NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: comunidad_profesion; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE comunidad_profesion (
    id_profesion integer NOT NULL,
    id_grupoper integer NOT NULL,
    id_caso integer NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: comunidad_rangoedad; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE comunidad_rangoedad (
    id_rangoedad integer NOT NULL,
    id_grupoper integer NOT NULL,
    id_caso integer NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: comunidad_sectorsocial; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE comunidad_sectorsocial (
    id_sector integer NOT NULL,
    id_grupoper integer NOT NULL,
    id_caso integer NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: comunidad_vinculoestado; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE comunidad_vinculoestado (
    id_vinculoestado integer NOT NULL,
    id_grupoper integer NOT NULL,
    id_caso integer NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT contexto_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: declaroante_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE declaroante_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: declaroante; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE declaroante (
    id integer DEFAULT nextval('declaroante_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    fechacreacion date DEFAULT '2013-05-24'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT declaroante_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT departamento_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: derecho_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE derecho_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: derecho; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE derecho (
    id integer DEFAULT nextval('derecho_seq'::regclass) NOT NULL,
    nombre character varying(50) NOT NULL,
    fechacreacion date DEFAULT '2013-06-12'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT derecho_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: derecho_procesosjr; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE derecho_procesosjr (
    id_proceso integer NOT NULL,
    id_derecho integer NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: derecho_respuesta; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE derecho_respuesta (
    id_caso integer NOT NULL,
    fechaatencion date NOT NULL,
    id_derecho integer NOT NULL,
    informacion boolean,
    acciones character varying(5000),
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: despacho_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE despacho_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: despacho; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE despacho (
    id integer DEFAULT nextval('despacho_seq'::regclass) NOT NULL,
    id_tproceso integer NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    observaciones character varying(500),
    fechacreacion date DEFAULT '2001-01-01'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT despacho_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: desplazamiento; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE desplazamiento (
    id_caso integer NOT NULL,
    fechaexpulsion date NOT NULL,
    expulsion integer NOT NULL,
    fechallegada date NOT NULL,
    llegada integer NOT NULL,
    id_clasifdesp integer NOT NULL,
    id_tipodesp integer NOT NULL,
    descripcion character varying(5000),
    otrosdatos character varying(1000),
    declaro character(1),
    hechosdeclarados character varying(5000),
    fechadeclaracion date,
    departamentodecl integer,
    municipiodecl integer,
    id_declaroante integer,
    id_inclusion integer,
    id_acreditacion integer,
    retornado boolean,
    reubicado boolean,
    connacionalretorno boolean,
    acompestado boolean,
    connacionaldeportado boolean,
    oficioantes character varying(5000),
    id_modalidadtierra integer,
    materialesperdidos character varying(5000),
    inmaterialesperdidos character varying(5000),
    protegiorupta boolean,
    documentostierra character varying(5000),
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT desplazamiento_declaro_check CHECK ((((declaro = 'S'::bpchar) OR (declaro = 'N'::bpchar)) OR (declaro = 'R'::bpchar)))
);


--
-- Name: desplazamiento_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE desplazamiento_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: escolaridad_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE escolaridad_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: escolaridad; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE escolaridad (
    id integer DEFAULT nextval('escolaridad_seq'::regclass) NOT NULL,
    nombre character varying(50) NOT NULL,
    fechacreacion date DEFAULT '2013-05-13'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT escolaridad_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: estadocivil_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE estadocivil_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: estadocivil; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE estadocivil (
    id integer DEFAULT nextval('estadocivil_seq'::regclass) NOT NULL,
    nombre character varying(50) NOT NULL,
    fechacreacion date DEFAULT '2013-05-13'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT estadocivil_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: etapa_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE etapa_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: etapa; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE etapa (
    id integer DEFAULT nextval('etapa_seq'::regclass) NOT NULL,
    id_tproceso integer NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    observaciones character varying(200),
    fechacreacion date DEFAULT '2001-01-01'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT etapa_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: etiqueta_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE etiqueta_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: etiqueta; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE etiqueta (
    id integer DEFAULT nextval('etiqueta_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    observaciones character varying(500),
    fechacreacion date DEFAULT '2001-01-01'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT etiqueta_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
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
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
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
    nombre character varying(15) NOT NULL UNIQUE,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


SET default_with_oids = true;

--
-- Name: geometry_columns; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE geometry_columns (
    f_table_catalog character varying(256) NOT NULL,
    f_table_schema character varying(256) NOT NULL,
    f_table_name character varying(256) NOT NULL,
    f_geometry_column character varying(256) NOT NULL,
    coord_dimension integer NOT NULL,
    srid integer NOT NULL,
    type character varying(30) NOT NULL
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


SET default_with_oids = false;

--
-- Name: grupoper; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE grupoper (
    id integer DEFAULT nextval('grupoper_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    anotaciones character varying(1000),
    created_at timestamp without time zone,
    updated_at timestamp without time zone
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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT iglesia_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: inclusion_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE inclusion_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: inclusion; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE inclusion (
    id integer DEFAULT nextval('inclusion_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    fechacreacion date DEFAULT '2013-05-24'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT inclusion_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: instanciader_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE instanciader_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: instanciader; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE instanciader (
    id integer DEFAULT nextval('instanciader_seq'::regclass) NOT NULL,
    nombre character varying(50) NOT NULL,
    fechacreacion date DEFAULT '2013-06-12'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT instanciader_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT intervalo_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: maternidad_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE maternidad_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: maternidad; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE maternidad (
    id integer DEFAULT nextval('maternidad_seq'::regclass) NOT NULL,
    nombre character varying(50) NOT NULL,
    fechacreacion date DEFAULT '2013-05-13'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT maternidad_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: mecanismoder_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE mecanismoder_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mecanismoder; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE mecanismoder (
    id integer DEFAULT nextval('mecanismoder_seq'::regclass) NOT NULL,
    nombre character varying(50) NOT NULL,
    fechacreacion date DEFAULT '2013-06-12'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT mecanismoder_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: modalidadtierra_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE modalidadtierra_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: modalidadtierra; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE modalidadtierra (
    id integer DEFAULT nextval('modalidadtierra_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    fechacreacion date DEFAULT '2013-05-24'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT modalidadtierra_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: motivoconsulta_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE motivoconsulta_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: motivoconsulta; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE motivoconsulta (
    id integer DEFAULT nextval('motivoconsulta_seq'::regclass) NOT NULL,
    nombre character varying(50) NOT NULL,
    fechacreacion date DEFAULT '2013-05-13'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT motivoconsulta_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: motivosjr; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE motivosjr (
    id integer DEFAULT nextval('acreditacion_seq'::regclass) NOT NULL,
    nombre character varying(100) NOT NULL,
    fechacreacion date DEFAULT '2013-06-16'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT motivosjr_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: motivosjr_respuesta; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE motivosjr_respuesta (
    id_caso integer NOT NULL,
    fechaatencion date NOT NULL,
    id_motivosjr integer NOT NULL,
    detalle character varying(5000),
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: motivosjr_seql; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE motivosjr_seql
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
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
    observaciones character varying(200),
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: personadesea_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE personadesea_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: personadesea; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE personadesea (
    id integer DEFAULT nextval('personadesea_seq'::regclass) NOT NULL,
    nombre character varying(50) NOT NULL,
    fechacreacion date DEFAULT '2013-06-17'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT personadesea_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT presponsable_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: proceso_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE proceso_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: proceso; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE proceso (
    id integer DEFAULT nextval('proceso_seq'::regclass) NOT NULL,
    id_caso integer NOT NULL,
    id_tproceso integer NOT NULL,
    id_etapa integer NOT NULL,
    proximafecha date,
    demandante character varying(100),
    demandado character varying(100),
    poderdante character varying(100),
    telefono character varying(50),
    observaciones character varying(500),
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: procesosjr; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE procesosjr (
    id_proceso integer NOT NULL,
    id_motivoconsulta integer,
    narracion character varying(5000),
    hapresentado character(1),
    id_mecanismoder integer,
    id_instanciader integer,
    detinstancia character varying(5000),
    mecrespondido character(1),
    fecharespuesta date,
    ajustaley character(1),
    estadomecanismo character varying(5000),
    orientacion character varying(5000),
    compromisossjr character varying(5000),
    compromisosper character varying(5000),
    surtioefecto character(1),
    otromecanismo integer,
    otrainstancia integer,
    detotrainstancia character varying(5000),
    persistevul boolean,
    resultado character varying(5000),
    casoregistro character(1),
    motivacionjuez character varying(5000),
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT procesosjr_hapresentado_check CHECK ((((hapresentado = 'S'::bpchar) OR (hapresentado = 'N'::bpchar)) OR (hapresentado = 'A'::bpchar))),
    CONSTRAINT procesosjr_hapresentado_check1 CHECK ((((hapresentado = 'S'::bpchar) OR (hapresentado = 'N'::bpchar)) OR (hapresentado = 'A'::bpchar))),
    CONSTRAINT procesosjr_hapresentado_check2 CHECK ((((hapresentado = 'S'::bpchar) OR (hapresentado = 'N'::bpchar)) OR (hapresentado = 'A'::bpchar))),
    CONSTRAINT procesosjr_hapresentado_check3 CHECK ((((hapresentado = 'S'::bpchar) OR (hapresentado = 'N'::bpchar)) OR (hapresentado = 'A'::bpchar))),
    CONSTRAINT procesosjr_hapresentado_check4 CHECK ((((hapresentado = 'S'::bpchar) OR (hapresentado = 'N'::bpchar)) OR (hapresentado = 'A'::bpchar)))
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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT profesion_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: progestado_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE progestado_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: progestado; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE progestado (
    id integer DEFAULT nextval('progestado_seq'::regclass) NOT NULL,
    nombre character varying(50) NOT NULL,
    fechacreacion date DEFAULT '2013-06-17'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT progestado_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: progestado_respuesta; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE progestado_respuesta (
    id_caso integer NOT NULL,
    fechaatencion date NOT NULL,
    id_progestado integer NOT NULL,
    difobs character varying(5000),
    created_at timestamp without time zone,
    updated_at timestamp without time zone
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
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    rango character varying(20) NOT NULL,
    limiteinferior integer DEFAULT 0 NOT NULL,
    limitesuperior integer DEFAULT 0 NOT NULL,
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT rangoedad_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: regimensalud_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE regimensalud_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: regimensalud; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE regimensalud (
    id integer DEFAULT nextval('regimensalud_seq'::regclass) NOT NULL,
    nombre character varying(50) NOT NULL,
    fechacreacion date DEFAULT '2013-05-13'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT regimensalud_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT region_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: regionsjr_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE regionsjr_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: regionsjr; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE regionsjr (
    id integer DEFAULT nextval('regionsjr_seq'::regclass) NOT NULL,
    nombre character varying(50) NOT NULL,
    fechacreacion date DEFAULT '2013-05-13'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT ubicacion_up_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT resagresion_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: respuesta; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE respuesta (
    id_caso integer NOT NULL,
    fechaatencion date NOT NULL,
    fechaexpulsion date NOT NULL,
    prorrogas boolean,
    numprorrogas integer,
    montoprorrogas integer,
    fechaultima date,
    lugarultima character varying(500),
    entregada boolean,
    proxprorroga boolean,
    turno character varying(100),
    lugar character varying(500),
    descamp character varying(5000),
    compromisos character varying(5000),
    remision character varying(5000),
    orientaciones character varying(5000),
    gestionessjr character varying(5000),
    observaciones character varying(5000),
    id_personadesea integer,
    id_causaref integer,
    verifcsjr character varying(5000),
    verifcper character varying(5000),
    efectividad character varying(5000),
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: rolfamilia_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE rolfamilia_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: rolfamilia; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE rolfamilia (
    id integer DEFAULT nextval('rolfamilia_seq'::regclass) NOT NULL,
    nombre character varying(50) NOT NULL,
    fechacreacion date DEFAULT '2013-06-20'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT rolfamilia_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: schema_migrations; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE schema_migrations (
    version character varying(255) NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT sectorsocial_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: spatial_ref_sys; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE spatial_ref_sys (
    srid integer NOT NULL,
    auth_name character varying(256),
    auth_srid integer,
    srtext character varying(2048),
    proj4text character varying(2048)
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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT supracategoria_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: taccion_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE taccion_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: taccion; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE taccion (
    id integer DEFAULT nextval('taccion_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    observaciones character varying(200),
    fechacreacion date DEFAULT '2001-01-01'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT taccion_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: tclase; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE tclase (
    id character varying(10) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    fechacreacion date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT tclase_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: tdesplazamiento_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE tdesplazamiento_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tipodesp_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE tipodesp_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tipodesp; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE tipodesp (
    id integer DEFAULT nextval('tipodesp_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    fechacreacion date DEFAULT '2013-05-24'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT tipodesp_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: tproceso_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE tproceso_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tproceso; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE tproceso (
    id integer DEFAULT nextval('tproceso_seq'::regclass) NOT NULL,
    nombre character varying(500) COLLATE public.es_co_utf_8 NOT NULL,
    observaciones character varying(200),
    fechacreacion date DEFAULT '2001-01-01'::date NOT NULL,
    fechadeshabilitacion date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT tproceso_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
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
    longitud double precision,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
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
    email character varying(255) DEFAULT ''::character varying NOT NULL,
    encrypted_password character varying(255) DEFAULT ''::character varying NOT NULL,
    reset_password_token character varying(255),
    reset_password_sent_at timestamp without time zone,
    remember_created_at timestamp without time zone,
    sign_in_count integer DEFAULT 0 NOT NULL,
    current_sign_in_at timestamp without time zone,
    last_sign_in_at timestamp without time zone,
    current_sign_in_ip character varying(255),
    last_sign_in_ip character varying(255),
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT usuario_rol_check CHECK (((rol >= 1) AND (rol <= 4)))
);


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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
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
    organizacionarmada integer,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


--
-- Name: victimasjr; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE victimasjr (
    id_persona integer NOT NULL,
    id_caso integer NOT NULL,
    id_estadocivil integer,
    cabezafamilia boolean,
    id_maternidad integer,
    discapacitado boolean,
    id_actividadoficio integer,
    id_escolaridad integer,
    asisteescuela boolean,
    tienesisben boolean,
    id_departamento integer,
    id_municipio integer,
    nivelsisben integer,
    id_regimensalud integer,
    eps character varying(1000),
    libretamilitar boolean,
    distrito integer,
    progadultomayor boolean,
    sindocumento boolean,
    fechadesagregacion date,
    id_rolfamilia integer DEFAULT 0 NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
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
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT vinculoestado_check CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY actividad ALTER COLUMN id SET DEFAULT nextval('actividad_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY actividad_rangoedad ALTER COLUMN id SET DEFAULT nextval('actividad_rangoedad_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY actividadarea ALTER COLUMN id SET DEFAULT nextval('actividadarea_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY actividadareas_actividad ALTER COLUMN id SET DEFAULT nextval('actividadareas_actividad_id_seq'::regclass);


--
-- Name: accion_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY accion
    ADD CONSTRAINT accion_pkey PRIMARY KEY (id);


--
-- Name: acreditacion_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY acreditacion
    ADD CONSTRAINT acreditacion_pkey PRIMARY KEY (id);


--
-- Name: actividad_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY actividad
    ADD CONSTRAINT actividad_pkey PRIMARY KEY (id);


--
-- Name: actividad_rangoedad_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY actividad_rangoedad
    ADD CONSTRAINT actividad_rangoedad_pkey PRIMARY KEY (id);


--
-- Name: actividadarea_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY actividadarea
    ADD CONSTRAINT actividadarea_pkey PRIMARY KEY (id);


--
-- Name: actividadareas_actividad_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY actividadareas_actividad
    ADD CONSTRAINT actividadareas_actividad_pkey PRIMARY KEY (id);


--
-- Name: actividadoficio_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY actividadoficio
    ADD CONSTRAINT actividadoficio_pkey PRIMARY KEY (id);


--
-- Name: acto_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY acto
    ADD CONSTRAINT acto_pkey PRIMARY KEY (id_presponsable, id_categoria, id_persona, id_caso);


--
-- Name: actocolectivo_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY actocolectivo
    ADD CONSTRAINT actocolectivo_pkey PRIMARY KEY (id_presponsable, id_categoria, id_grupoper, id_caso);


--
-- Name: actosjr_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY actosjr
    ADD CONSTRAINT actosjr_pkey PRIMARY KEY (id_presponsable, id_categoria, id_persona, id_caso, fecha);


--
-- Name: actualizacionbase_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY actualizacionbase
    ADD CONSTRAINT actualizacionbase_pkey PRIMARY KEY (id);


--
-- Name: anexo_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY anexo
    ADD CONSTRAINT anexo_pkey PRIMARY KEY (id);


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
-- Name: ayudaestado_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY ayudaestado
    ADD CONSTRAINT ayudaestado_pkey PRIMARY KEY (id);


--
-- Name: ayudaestado_respuesta_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY ayudaestado_respuesta
    ADD CONSTRAINT ayudaestado_respuesta_pkey PRIMARY KEY (id_caso, fechaatencion, id_ayudaestado);


--
-- Name: ayudasjr_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY ayudasjr
    ADD CONSTRAINT ayudasjr_pkey PRIMARY KEY (id);


--
-- Name: ayudasjr_respuesta_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY ayudasjr_respuesta
    ADD CONSTRAINT ayudasjr_respuesta_pkey PRIMARY KEY (id_caso, fechaatencion, id_ayudasjr);


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
-- Name: caso_etiqueta_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY caso_etiqueta
    ADD CONSTRAINT caso_etiqueta_pkey PRIMARY KEY (id_caso, id_etiqueta, id_funcionario, fecha);


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
-- Name: casosjr_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY casosjr
    ADD CONSTRAINT casosjr_pkey PRIMARY KEY (id_caso);


--
-- Name: categoria_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY categoria
    ADD CONSTRAINT categoria_pkey PRIMARY KEY (id);


--
-- Name: causaref_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY causaref
    ADD CONSTRAINT causaref_pkey PRIMARY KEY (id);


--
-- Name: clase_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY clase
    ADD CONSTRAINT clase_pkey PRIMARY KEY (id, id_municipio, id_departamento);


--
-- Name: clasifdesp_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY clasifdesp
    ADD CONSTRAINT clasifdesp_pkey PRIMARY KEY (id);


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
-- Name: declaroante_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY declaroante
    ADD CONSTRAINT declaroante_pkey PRIMARY KEY (id);


--
-- Name: departamento_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY departamento
    ADD CONSTRAINT departamento_pkey PRIMARY KEY (id);


--
-- Name: derecho_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY derecho
    ADD CONSTRAINT derecho_pkey PRIMARY KEY (id);


--
-- Name: derecho_procesosjr_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY derecho_procesosjr
    ADD CONSTRAINT derecho_procesosjr_pkey PRIMARY KEY (id_proceso, id_derecho);


--
-- Name: derecho_respuesta_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY derecho_respuesta
    ADD CONSTRAINT derecho_respuesta_pkey PRIMARY KEY (id_caso, fechaatencion, id_derecho);


--
-- Name: despacho_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY despacho
    ADD CONSTRAINT despacho_pkey PRIMARY KEY (id);


--
-- Name: desplazamiento_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY desplazamiento
    ADD CONSTRAINT desplazamiento_pkey PRIMARY KEY (id_caso, fechaexpulsion);


--
-- Name: escolaridad_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY escolaridad
    ADD CONSTRAINT escolaridad_pkey PRIMARY KEY (id);


--
-- Name: estadocivil_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY estadocivil
    ADD CONSTRAINT estadocivil_pkey PRIMARY KEY (id);


--
-- Name: etapa_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY etapa
    ADD CONSTRAINT etapa_pkey PRIMARY KEY (id);


--
-- Name: etiqueta_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY etiqueta
    ADD CONSTRAINT etiqueta_pkey PRIMARY KEY (id);


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
-- Name: geometry_columns_pk; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY geometry_columns
    ADD CONSTRAINT geometry_columns_pk PRIMARY KEY (f_table_catalog, f_table_schema, f_table_name, f_geometry_column);


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
-- Name: inclusion_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY inclusion
    ADD CONSTRAINT inclusion_pkey PRIMARY KEY (id);


--
-- Name: instanciader_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY instanciader
    ADD CONSTRAINT instanciader_pkey PRIMARY KEY (id);


--
-- Name: intervalo_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY intervalo
    ADD CONSTRAINT intervalo_pkey PRIMARY KEY (id);


--
-- Name: maternidad_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY maternidad
    ADD CONSTRAINT maternidad_pkey PRIMARY KEY (id);


--
-- Name: mecanismoder_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY mecanismoder
    ADD CONSTRAINT mecanismoder_pkey PRIMARY KEY (id);


--
-- Name: modalidadtierra_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY modalidadtierra
    ADD CONSTRAINT modalidadtierra_pkey PRIMARY KEY (id);


--
-- Name: motivoconsulta_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY motivoconsulta
    ADD CONSTRAINT motivoconsulta_pkey PRIMARY KEY (id);


--
-- Name: motivosjr_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY motivosjr
    ADD CONSTRAINT motivosjr_pkey PRIMARY KEY (id);


--
-- Name: motivosjr_respuesta_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY motivosjr_respuesta
    ADD CONSTRAINT motivosjr_respuesta_pkey PRIMARY KEY (id_caso, fechaatencion, id_motivosjr);


--
-- Name: municipio_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY municipio
    ADD CONSTRAINT municipio_pkey PRIMARY KEY (id, id_departamento);


--
-- Name: numerodocumento_key; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY persona
    ADD CONSTRAINT numerodocumento_key UNIQUE (tipodocumento, numerodocumento);


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
-- Name: personadesea_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY personadesea
    ADD CONSTRAINT personadesea_pkey PRIMARY KEY (id);


--
-- Name: presponsable_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY presponsable
    ADD CONSTRAINT presponsable_pkey PRIMARY KEY (id);


--
-- Name: proceso_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY proceso
    ADD CONSTRAINT proceso_pkey PRIMARY KEY (id);


--
-- Name: procesosjr_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY procesosjr
    ADD CONSTRAINT procesosjr_pkey PRIMARY KEY (id_proceso);


--
-- Name: profesion_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY profesion
    ADD CONSTRAINT profesion_pkey PRIMARY KEY (id);


--
-- Name: progestado_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY progestado
    ADD CONSTRAINT progestado_pkey PRIMARY KEY (id);


--
-- Name: progestado_respuesta_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY progestado_respuesta
    ADD CONSTRAINT progestado_respuesta_pkey PRIMARY KEY (id_caso, fechaatencion, id_progestado);


--
-- Name: rangoedad_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY rangoedad
    ADD CONSTRAINT rangoedad_pkey PRIMARY KEY (id);


--
-- Name: regimensalud_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY regimensalud
    ADD CONSTRAINT regimensalud_pkey PRIMARY KEY (id);


--
-- Name: region_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY region
    ADD CONSTRAINT region_pkey PRIMARY KEY (id);


--
-- Name: regionsjr_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY regionsjr
    ADD CONSTRAINT regionsjr_pkey PRIMARY KEY (id);


--
-- Name: resagresion_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY resagresion
    ADD CONSTRAINT resagresion_pkey PRIMARY KEY (id);


--
-- Name: respuesta_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY respuesta
    ADD CONSTRAINT respuesta_pkey PRIMARY KEY (id_caso, fechaatencion);


--
-- Name: rolfamilia_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY rolfamilia
    ADD CONSTRAINT rolfamilia_pkey PRIMARY KEY (id);


--
-- Name: sectorsocial_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY sectorsocial
    ADD CONSTRAINT sectorsocial_pkey PRIMARY KEY (id);


--
-- Name: spatial_ref_sys_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY spatial_ref_sys
    ADD CONSTRAINT spatial_ref_sys_pkey PRIMARY KEY (srid);


--
-- Name: supracategoria_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY supracategoria
    ADD CONSTRAINT supracategoria_pkey PRIMARY KEY (id, id_tviolencia);


--
-- Name: taccion_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY taccion
    ADD CONSTRAINT taccion_pkey PRIMARY KEY (id);


--
-- Name: tclase_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY tclase
    ADD CONSTRAINT tclase_pkey PRIMARY KEY (id);


--
-- Name: tipodesp_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY tipodesp
    ADD CONSTRAINT tipodesp_pkey PRIMARY KEY (id);


--
-- Name: tproceso_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY tproceso
    ADD CONSTRAINT tproceso_pkey PRIMARY KEY (id);


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
-- Name: victimasjr_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY victimasjr
    ADD CONSTRAINT victimasjr_pkey PRIMARY KEY (id_persona, id_caso);


--
-- Name: vinculoestado_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY vinculoestado
    ADD CONSTRAINT vinculoestado_pkey PRIMARY KEY (id);


--
-- Name: index_actividad_rangoedad_on_actividad_id; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE INDEX index_actividad_rangoedad_on_actividad_id ON actividad_rangoedad USING btree (actividad_id);


--
-- Name: index_actividad_rangoedad_on_rangoedad_id; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE INDEX index_actividad_rangoedad_on_rangoedad_id ON actividad_rangoedad USING btree (rangoedad_id);


--
-- Name: index_usuario_on_email; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX index_usuario_on_email ON usuario USING btree (email);


--
-- Name: index_usuario_on_reset_password_token; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX index_usuario_on_reset_password_token ON usuario USING btree (reset_password_token);


--
-- Name: unique_schema_migrations; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX unique_schema_migrations ON schema_migrations USING btree (version);


--
-- Name: accion_id_trelacion_accion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY accion
    ADD CONSTRAINT accion_id_trelacion_accion_fkey FOREIGN KEY (id_taccion) REFERENCES taccion(id);


--
-- Name: acto_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY acto
    ADD CONSTRAINT acto_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: acto_id_categoria_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY acto
    ADD CONSTRAINT acto_id_categoria_fkey FOREIGN KEY (id_categoria) REFERENCES categoria(id);


--
-- Name: acto_id_persona_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY acto
    ADD CONSTRAINT acto_id_persona_fkey FOREIGN KEY (id_persona) REFERENCES persona(id);


--
-- Name: acto_id_persona_fkey1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY acto
    ADD CONSTRAINT acto_id_persona_fkey1 FOREIGN KEY (id_persona, id_caso) REFERENCES victima(id_persona, id_caso);


--
-- Name: acto_id_presponsable_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY acto
    ADD CONSTRAINT acto_id_presponsable_fkey FOREIGN KEY (id_presponsable) REFERENCES presponsable(id);


--
-- Name: actocolectivo_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actocolectivo
    ADD CONSTRAINT actocolectivo_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: actocolectivo_id_categoria_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actocolectivo
    ADD CONSTRAINT actocolectivo_id_categoria_fkey FOREIGN KEY (id_categoria) REFERENCES categoria(id);


--
-- Name: actocolectivo_id_grupoper_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actocolectivo
    ADD CONSTRAINT actocolectivo_id_grupoper_fkey FOREIGN KEY (id_grupoper) REFERENCES grupoper(id);


--
-- Name: actocolectivo_id_grupoper_fkey1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actocolectivo
    ADD CONSTRAINT actocolectivo_id_grupoper_fkey1 FOREIGN KEY (id_grupoper, id_caso) REFERENCES victimacolectiva(id_grupoper, id_caso);


--
-- Name: actocolectivo_id_presponsable_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actocolectivo
    ADD CONSTRAINT actocolectivo_id_presponsable_fkey FOREIGN KEY (id_presponsable) REFERENCES presponsable(id);


--
-- Name: actosjr_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actosjr
    ADD CONSTRAINT actosjr_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: actosjr_id_caso_fkey1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actosjr
    ADD CONSTRAINT actosjr_id_caso_fkey1 FOREIGN KEY (id_caso, fechaexpulsion) REFERENCES desplazamiento(id_caso, fechaexpulsion);


--
-- Name: actosjr_id_categoria_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actosjr
    ADD CONSTRAINT actosjr_id_categoria_fkey FOREIGN KEY (id_categoria) REFERENCES categoria(id);


--
-- Name: actosjr_id_persona_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actosjr
    ADD CONSTRAINT actosjr_id_persona_fkey FOREIGN KEY (id_persona) REFERENCES persona(id);


--
-- Name: actosjr_id_presponsable_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actosjr
    ADD CONSTRAINT actosjr_id_presponsable_fkey FOREIGN KEY (id_presponsable) REFERENCES presponsable(id);


--
-- Name: actosjr_id_presponsable_fkey1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actosjr
    ADD CONSTRAINT actosjr_id_presponsable_fkey1 FOREIGN KEY (id_presponsable, id_categoria, id_persona, id_caso) REFERENCES acto(id_presponsable, id_categoria, id_persona, id_caso);


--
-- Name: anexo_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY anexo
    ADD CONSTRAINT anexo_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: anexo_id_ffrecuente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY anexo
    ADD CONSTRAINT anexo_id_ffrecuente_fkey FOREIGN KEY (id_ffrecuente) REFERENCES ffrecuente(id);


--
-- Name: anexo_id_fotra_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY anexo
    ADD CONSTRAINT anexo_id_fotra_fkey FOREIGN KEY (id_fotra) REFERENCES fotra(id);


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
-- Name: ayudaestado_respuesta_id_ayudaestado_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY ayudaestado_respuesta
    ADD CONSTRAINT ayudaestado_respuesta_id_ayudaestado_fkey FOREIGN KEY (id_ayudaestado) REFERENCES ayudaestado(id);


--
-- Name: ayudaestado_respuesta_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY ayudaestado_respuesta
    ADD CONSTRAINT ayudaestado_respuesta_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES casosjr(id_caso);


--
-- Name: ayudaestado_respuesta_id_caso_fkey1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY ayudaestado_respuesta
    ADD CONSTRAINT ayudaestado_respuesta_id_caso_fkey1 FOREIGN KEY (id_caso, fechaatencion) REFERENCES respuesta(id_caso, fechaatencion);


--
-- Name: ayudasjr_respuesta_id_ayudasjr_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY ayudasjr_respuesta
    ADD CONSTRAINT ayudasjr_respuesta_id_ayudasjr_fkey FOREIGN KEY (id_ayudasjr) REFERENCES ayudasjr(id);


--
-- Name: ayudasjr_respuesta_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY ayudasjr_respuesta
    ADD CONSTRAINT ayudasjr_respuesta_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES casosjr(id_caso);


--
-- Name: ayudasjr_respuesta_id_caso_fkey1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY ayudasjr_respuesta
    ADD CONSTRAINT ayudasjr_respuesta_id_caso_fkey1 FOREIGN KEY (id_caso, fechaatencion) REFERENCES respuesta(id_caso, fechaatencion);


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
-- Name: caso_etiqueta_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY caso_etiqueta
    ADD CONSTRAINT caso_etiqueta_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: caso_etiqueta_id_etiqueta_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY caso_etiqueta
    ADD CONSTRAINT caso_etiqueta_id_etiqueta_fkey FOREIGN KEY (id_etiqueta) REFERENCES etiqueta(id);


--
-- Name: caso_etiqueta_id_funcionario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY caso_etiqueta
    ADD CONSTRAINT caso_etiqueta_id_funcionario_fkey FOREIGN KEY (id_funcionario) REFERENCES funcionario(id);


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
-- Name: casosjr_asesor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY casosjr
    ADD CONSTRAINT casosjr_asesor_fkey FOREIGN KEY (asesor) REFERENCES funcionario(id);


--
-- Name: casosjr_contacto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY casosjr
    ADD CONSTRAINT casosjr_contacto_fkey FOREIGN KEY (contacto) REFERENCES persona(id);


--
-- Name: casosjr_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY casosjr
    ADD CONSTRAINT casosjr_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: casosjr_id_regionsjr_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY casosjr
    ADD CONSTRAINT casosjr_id_regionsjr_fkey FOREIGN KEY (id_regionsjr) REFERENCES regionsjr(id);


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
-- Name: derecho_procesosjr_id_derecho_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY derecho_procesosjr
    ADD CONSTRAINT derecho_procesosjr_id_derecho_fkey FOREIGN KEY (id_derecho) REFERENCES derecho(id);


--
-- Name: derecho_procesosjr_id_proceso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY derecho_procesosjr
    ADD CONSTRAINT derecho_procesosjr_id_proceso_fkey FOREIGN KEY (id_proceso) REFERENCES procesosjr(id_proceso);


--
-- Name: derecho_respuesta_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY derecho_respuesta
    ADD CONSTRAINT derecho_respuesta_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES casosjr(id_caso);


--
-- Name: derecho_respuesta_id_caso_fkey1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY derecho_respuesta
    ADD CONSTRAINT derecho_respuesta_id_caso_fkey1 FOREIGN KEY (id_caso, fechaatencion) REFERENCES respuesta(id_caso, fechaatencion);


--
-- Name: derecho_respuesta_id_derecho_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY derecho_respuesta
    ADD CONSTRAINT derecho_respuesta_id_derecho_fkey FOREIGN KEY (id_derecho) REFERENCES derecho(id);


--
-- Name: despacho_id_tproceso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY despacho
    ADD CONSTRAINT despacho_id_tproceso_fkey FOREIGN KEY (id_tproceso) REFERENCES tproceso(id);


--
-- Name: desplazamiento_departamentodecl_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY desplazamiento
    ADD CONSTRAINT desplazamiento_departamentodecl_fkey FOREIGN KEY (departamentodecl) REFERENCES departamento(id);


--
-- Name: desplazamiento_expulsion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY desplazamiento
    ADD CONSTRAINT desplazamiento_expulsion_fkey FOREIGN KEY (expulsion) REFERENCES ubicacion(id);


--
-- Name: desplazamiento_id_acreditacion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY desplazamiento
    ADD CONSTRAINT desplazamiento_id_acreditacion_fkey FOREIGN KEY (id_acreditacion) REFERENCES acreditacion(id);


--
-- Name: desplazamiento_id_clasifdesp_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY desplazamiento
    ADD CONSTRAINT desplazamiento_id_clasifdesp_fkey FOREIGN KEY (id_clasifdesp) REFERENCES clasifdesp(id);


--
-- Name: desplazamiento_id_declaroante_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY desplazamiento
    ADD CONSTRAINT desplazamiento_id_declaroante_fkey FOREIGN KEY (id_declaroante) REFERENCES declaroante(id);


--
-- Name: desplazamiento_id_inclusion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY desplazamiento
    ADD CONSTRAINT desplazamiento_id_inclusion_fkey FOREIGN KEY (id_inclusion) REFERENCES inclusion(id);


--
-- Name: desplazamiento_id_modalidadtierra_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY desplazamiento
    ADD CONSTRAINT desplazamiento_id_modalidadtierra_fkey FOREIGN KEY (id_modalidadtierra) REFERENCES modalidadtierra(id);


--
-- Name: desplazamiento_id_tipodesp_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY desplazamiento
    ADD CONSTRAINT desplazamiento_id_tipodesp_fkey FOREIGN KEY (id_tipodesp) REFERENCES tipodesp(id);


--
-- Name: desplazamiento_llegada_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY desplazamiento
    ADD CONSTRAINT desplazamiento_llegada_fkey FOREIGN KEY (llegada) REFERENCES ubicacion(id);


--
-- Name: desplazamiento_municipiodecl_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY desplazamiento
    ADD CONSTRAINT desplazamiento_municipiodecl_fkey FOREIGN KEY (municipiodecl, departamentodecl) REFERENCES municipio(id, id_departamento);


--
-- Name: etapa_id_tproceso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY etapa
    ADD CONSTRAINT etapa_id_tproceso_fkey FOREIGN KEY (id_tproceso) REFERENCES tproceso(id);


--
-- Name: motivosjr_respuesta_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY motivosjr_respuesta
    ADD CONSTRAINT motivosjr_respuesta_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES casosjr(id_caso);


--
-- Name: motivosjr_respuesta_id_caso_fkey1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY motivosjr_respuesta
    ADD CONSTRAINT motivosjr_respuesta_id_caso_fkey1 FOREIGN KEY (id_caso, fechaatencion) REFERENCES respuesta(id_caso, fechaatencion);


--
-- Name: motivosjr_respuesta_id_motivosjr_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY motivosjr_respuesta
    ADD CONSTRAINT motivosjr_respuesta_id_motivosjr_fkey FOREIGN KEY (id_motivosjr) REFERENCES motivosjr(id);


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
-- Name: proceso_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY proceso
    ADD CONSTRAINT proceso_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: proceso_id_etapa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY proceso
    ADD CONSTRAINT proceso_id_etapa_fkey FOREIGN KEY (id_etapa) REFERENCES etapa(id);


--
-- Name: proceso_id_tproceso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY proceso
    ADD CONSTRAINT proceso_id_tproceso_fkey FOREIGN KEY (id_tproceso) REFERENCES tproceso(id);


--
-- Name: procesosjr_id_instanciader_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY procesosjr
    ADD CONSTRAINT procesosjr_id_instanciader_fkey FOREIGN KEY (id_instanciader) REFERENCES instanciader(id);


--
-- Name: procesosjr_id_mecanismoder_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY procesosjr
    ADD CONSTRAINT procesosjr_id_mecanismoder_fkey FOREIGN KEY (id_mecanismoder) REFERENCES mecanismoder(id);


--
-- Name: procesosjr_id_motivoconsulta_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY procesosjr
    ADD CONSTRAINT procesosjr_id_motivoconsulta_fkey FOREIGN KEY (id_motivoconsulta) REFERENCES motivoconsulta(id);


--
-- Name: procesosjr_id_proceso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY procesosjr
    ADD CONSTRAINT procesosjr_id_proceso_fkey FOREIGN KEY (id_proceso) REFERENCES proceso(id);


--
-- Name: procesosjr_otrainstancia_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY procesosjr
    ADD CONSTRAINT procesosjr_otrainstancia_fkey FOREIGN KEY (otrainstancia) REFERENCES instanciader(id);


--
-- Name: procesosjr_otromecanismo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY procesosjr
    ADD CONSTRAINT procesosjr_otromecanismo_fkey FOREIGN KEY (otromecanismo) REFERENCES mecanismoder(id);


--
-- Name: progestado_respuesta_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY progestado_respuesta
    ADD CONSTRAINT progestado_respuesta_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES casosjr(id_caso);


--
-- Name: progestado_respuesta_id_caso_fkey1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY progestado_respuesta
    ADD CONSTRAINT progestado_respuesta_id_caso_fkey1 FOREIGN KEY (id_caso, fechaatencion) REFERENCES respuesta(id_caso, fechaatencion);


--
-- Name: progestado_respuesta_id_progestado_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY progestado_respuesta
    ADD CONSTRAINT progestado_respuesta_id_progestado_fkey FOREIGN KEY (id_progestado) REFERENCES progestado(id);


--
-- Name: respuesta_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY respuesta
    ADD CONSTRAINT respuesta_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES casosjr(id_caso);


--
-- Name: respuesta_id_caso_fkey1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY respuesta
    ADD CONSTRAINT respuesta_id_caso_fkey1 FOREIGN KEY (id_caso, fechaexpulsion) REFERENCES desplazamiento(id_caso, fechaexpulsion);


--
-- Name: respuesta_id_causaref_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY respuesta
    ADD CONSTRAINT respuesta_id_causaref_fkey FOREIGN KEY (id_causaref) REFERENCES causaref(id);


--
-- Name: respuesta_id_personadesea_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY respuesta
    ADD CONSTRAINT respuesta_id_personadesea_fkey FOREIGN KEY (id_personadesea) REFERENCES personadesea(id);


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
-- Name: victimasjr_id_actividadoficio_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY victimasjr
    ADD CONSTRAINT victimasjr_id_actividadoficio_fkey FOREIGN KEY (id_actividadoficio) REFERENCES actividadoficio(id);


--
-- Name: victimasjr_id_caso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY victimasjr
    ADD CONSTRAINT victimasjr_id_caso_fkey FOREIGN KEY (id_caso) REFERENCES caso(id);


--
-- Name: victimasjr_id_escolaridad_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY victimasjr
    ADD CONSTRAINT victimasjr_id_escolaridad_fkey FOREIGN KEY (id_escolaridad) REFERENCES escolaridad(id);


--
-- Name: victimasjr_id_estadocivil_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY victimasjr
    ADD CONSTRAINT victimasjr_id_estadocivil_fkey FOREIGN KEY (id_estadocivil) REFERENCES estadocivil(id);


--
-- Name: victimasjr_id_maternidad_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY victimasjr
    ADD CONSTRAINT victimasjr_id_maternidad_fkey FOREIGN KEY (id_maternidad) REFERENCES maternidad(id);


--
-- Name: victimasjr_id_persona_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY victimasjr
    ADD CONSTRAINT victimasjr_id_persona_fkey FOREIGN KEY (id_persona) REFERENCES persona(id);


--
-- Name: victimasjr_id_persona_fkey1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY victimasjr
    ADD CONSTRAINT victimasjr_id_persona_fkey1 FOREIGN KEY (id_persona, id_caso) REFERENCES victima(id_persona, id_caso);


--
-- Name: victimasjr_id_regimensalud_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY victimasjr
    ADD CONSTRAINT victimasjr_id_regimensalud_fkey FOREIGN KEY (id_regimensalud) REFERENCES regimensalud(id);


--
-- Name: victimasjr_id_rolfamilia_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY victimasjr
    ADD CONSTRAINT victimasjr_id_rolfamilia_fkey FOREIGN KEY (id_rolfamilia) REFERENCES rolfamilia(id);


--
-- PostgreSQL database dump complete
--

SET search_path TO "$user",public;

INSERT INTO schema_migrations (version) VALUES ('20131128151014');

INSERT INTO schema_migrations (version) VALUES ('20131204135932');

INSERT INTO schema_migrations (version) VALUES ('20131204143718');

INSERT INTO schema_migrations (version) VALUES ('20131204183530');

INSERT INTO schema_migrations (version) VALUES ('20131205233111');

INSERT INTO schema_migrations (version) VALUES ('20131206081531');

INSERT INTO schema_migrations (version) VALUES ('20131210221541');

INSERT INTO schema_migrations (version) VALUES ('20131220103409');

INSERT INTO schema_migrations (version) VALUES ('20131223175141');
