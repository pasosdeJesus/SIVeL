#!/bin/sh
# Crea esquemas para DataObject de base de datos
# Dominio público. Sin garantías. 2009.  vtamara@pasosdeJesus.org

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

function leeEstructura {
	nd=$1;
echo leeEstructura $nd
	if (test -f $nd/DataObjects/estructura-dataobject.ini) then {
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
} else {
	echo "No existe ${RUTASQL}/estructura.sql";
	exit 1;
} fi;

for i in $modulos; do 
	if (test ! -d $dirfuentes/$i) then {
		echo "Falta directorio de módulo $dirfuentes/$i";
		exit 1;
	} fi;
	verificaDataobject $dirfuentes/$i
done;

verificaDataobject ${dirap}

echo "" > $dirap/DataObjects/$dbnombre.ini
echo "" > $dirap/DataObjects/$dbnombre.links.ini

leeEstructura ${dirfuentes}

for i in $modulos; do
	leeEstructura ${dirfuentes}/$i/
done;

leeEstructura ${dirap}

