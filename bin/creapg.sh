#!/bin/sh
# Crea base de datos, tablas, datos iniciales y archivos para DataObject
# Dominio público. Sin garantías. 2004.  vtamara@users.sourceforge.net
# Basado en script de dominio público de 
# 	http://structio.sourceforge.net/seguidor

if (test ! -f vardb.sh -o ! -f conf.php) then {
       echo "Ejecute desde el directorio del sitio";
} fi;

. ./vardb.sh

if (test "${RUTASQL}" != "") then {
	echo "RUTASQL=${RUTASQL}";
}
else {
	RUTASQL="$dirfuentes";
} fi;

function leeSQL {
	nd2=$1;
	if (test -f $nd2) then {
		cmd="psql $socketopt -d $dbnombre -U $dbusuario -f $nd2";
		echo $cmd;
		eval $cmd;
	} fi;
}

function leeEstructura {
	nd=$1;
	if (test -f $nd/estructura.sql) then {
		leeSQL $nd/estructura.sql
		cmd="cat $nd/DataObjects/estructura-dataobject.ini >> $dirap/DataObjects/$dbnombre.ini";
		#echo $cmd;
		eval $cmd;
		cmd="cat $nd/DataObjects/estructura-dataobject.links.ini >> $dirap/DataObjects/$dbnombre.links.ini";
		#echo $cmd;
		eval $cmd;
	} fi;
}

function verificaDataobject {
	nd=$1;
	if (test -f $nd/estructura.sql) then {
		if (test ! -f $nd/DataObjects/estructura-dataobject.ini) then {
			echo "Debería existir $nd/DataObjects/estructura-dataobject.ini";
			exit 1;
		} fi;
		if (test ! -f $nd/DataObjects/estructura-dataobject.links.ini) then {
			echo "Debería existir $nd/DataObjects/estructura-dataobject.links.ini";
			exit 1;
		} fi;

		# Verificar que coincidan (tablas y campos) de estructura y estructura-dataobjects
		# Verificar que coincidan tablas y campos de estructura-dataobjects y clases en directorio DataObjects (importante para DataObject)
	} fi;
}


if (test -f ${RUTASQL}/estructura.sql) then {
	verificaDataobject ${RUTASQL}
}
else {
	echo "No existe ${RUTASQL}/estructura.sql";
} fi;

for i in $modulos; do 
	if (test ! -d $dirfuentes/$i) then {
		echo "Falta directorio de módulo $dirfuentes/$i";
		exit 1;
	} fi;
	verificaDataobject $dirfuentes/$i
done;

verificaDataobject ${dirap}

psql $socketopt -U $dbusuario postgres -c ""
if (test "$?" != "0") then {
	echo "Verifique: ";
	echo "  a) Que esté funcionando el servidor PostgreSQL";
	echo "  b) Que esté definido el usuario $dbusuario de PostgreSQL";
	exit 1;
} fi;

echo "Advertencia: este script borrara la información existente en "
echo "la base '$dbnombre' del usuario '$dbusuario' y sobreescribirá "
echo "$dirap/DataObjects/$dbnombre.ini y $dirap/DataObjects/$dbnombre.links.ini"
echo "con información base y de módulos: $modulos"
echo "Presione [Enter] para continuar o detenga con [Ctrl]+[C]";
if (test "${PREGUNTA}" != "no") then {
	read;
} fi;

if (test "$SIN_DROP" != "1") then {
	dropdb $socketopt -U $dbusuario $dbnombre; 
} fi;
	
cmd="createdb $socketopt -E UTF8 -U $dbusuario $dbnombre -T template0"
echo $cmd
eval $cmd
cmd="createlang $socketopt plpgsql -U $dbusuario $dbnombre"
echo $cmd
eval $cmd
echo "CREATE EXTENSION unaccent;" > /tmp/unaccent.sql
if (test -f /tmp/unaccent.sql) then {
	cmd="psql $socketopt -U $dbusuario -d $dbnombre -f /tmp/unaccent.sql"
	echo $cmd
	eval $cmd
} else {
	echo "*** No pudo crearse extension unaccent. Es posible que el usuario $dbusuario no sea superusuario y requiera ejecutar 'ALTER USER $dbusuario WITH SUPERUSER' y crear la extensión en la base $dbnombre manualmente con 'CREATE EXTENSION unaccent'."
} fi;


if (test -f /usr/local/share/postgresql/contrib/postgis-1.5/postgis.sql) then {
	cmd="psql $socketopt -U $dbusuario -d $dbnombre -f /usr/local/share/postgresql/contrib/postgis-1.5/postgis.sql"
	echo $cmd
	eval $cmd
} fi;
if (test -f /usr/local/share/postgresql/contrib/postgis-1.5/spatial_ref_sys.sql) then {
	cmd="psql $socketopt -U $dbusuario -d $dbnombre -f /usr/local/share/postgresql/contrib/postgis-1.5/spatial_ref_sys.sql"
	echo $cmd
	eval $cmd
} fi;
if (test "$SIN_ESQUEMA" = "1") then {
	exit 0;
} fi;

# Iniciar archivo vacío por construir con leeEstructura:
echo "" > $dirap/DataObjects/$dbnombre.ini
echo "" > $dirap/DataObjects/$dbnombre.links.ini

leeEstructura ${RUTASQL}

for i in $modulos; do
	leeEstructura ${dirfuentes}/$i/
done;

leeEstructura ${dirap}

if (test "$SIN_DATOS" = "1") then {
	exit 0;
} fi;

echo "Poblando base";

leeSQL ${RUTASQL}/datos-us.sql;
leeSQL ${RUTASQL}/datos-geo-col.sql;
leeSQL ${RUTASQL}/datos-implicado.sql;
leeSQL ${RUTASQL}/datos-caso.sql;
leeSQL ${RUTASQL}/datos-presp.sql;
leeSQL ${RUTASQL}/datos-fuente.sql;

for i in $modulos; do
	echo "Incluyendo datos del modulo $i";
	leeSQL ${dirfuentes}/$i/datos.sql
done;
leeSQL ${dirap}/datos.sql
leeSQL ${dirap}/priv/datos.sql;


echo "Actualizando indices";

leeSQL ${RUTASQL}/prepara_indices.sql
for i in $modulos; do
	leeSQL ${dirfuentes}/$i/prepara_indices.sql
done;
leeSQL ${dirap}/prepara_indices.sql


