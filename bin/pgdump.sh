#!/bin/sh
# Saca volcado de la base de datos completas al archivo del mes.
# Dominio público. 2005. Sin garantías.

if (test ! -f ./vardb.sh -o ! -f conf.php) then {
	echo "Ejecute desde directorio del sitio";
	exit 1;
} fi;

. ./vardb.sh

rm -f $rlocal/$n-s $rlocal/$nommes.gz
cmd="/usr/local/bin/pg_dump --encoding=UTF8 $socketopt --attribute-inserts --inserts -U $dbusuario -cO $dbnombre > $rlocal/$n-s"
echo $cmd;
eval $cmd;
cp $rlocal/$n-s $rlocal/$nommes
gzip -f $rlocal/$nommes
