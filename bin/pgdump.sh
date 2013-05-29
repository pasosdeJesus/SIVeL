#!/bin/sh
# Saca volcado de la base de datos completas al archivo del mes.
# Dominio público. 2005. Sin garantías.

if (test ! -f ./vardb.sh -o ! -f conf.php) then {
	echo "Ejecute desde directorio del sitio";
	exit 1;
} fi;

. ./vardb.sh

rm -f $rlocal/$h-s $rlocal/$nommes.gz
cmd="pg_dump --encoding=LATIN1 $socketopt --attribute-inserts --inserts -U $dbusuario -cO $dbnombre > $rlocal/$n-s"
echo $cmd;
eval $cmd;
cp $rlocal/$n-s $rlocal/$nommes
gzip $rlocal/$nommes
