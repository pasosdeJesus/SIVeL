#!/bin/sh
# Restaura SIVeL de una copia SQL
# Dominio público. vtamara@pasosdeJesus.org. 2009

if (test ! -f ./vardb.sh -o ! -f conf.php) then {
	echo "Ejecute desde directorio del sitio";
	exit 1;
} fi;

. ./vardb.sh

n=$1

if (test "$n" = "" -o ! -f "$n") then  {
	echo "Falta nombre del volcado SQL por restaurar como primer parámetro";
	exit 1;
} fi;

nomsql=$n;
echo $n | grep "sql.gz$" 
if (test "$?" = "0") then {
	nomsql=`echo $n | sed -e "s/.*\///g;s/^\([^\/]*.\).sql.gz/\/tmp\/\1.sql/g"`;
	cp $n $nomsql.gz
	gzip -df $nomsql.gz
} fi;

echo "Por remplazar base $dbnombre con volcado $nomsql";
cmd="../../bin/psql.sh -f \"$nomsql\"";
echo "[ENTER] para ejecutar $cmd";
read
echo $cmd
eval $cmd
echo "Actualizando indices";
cmd="../../bin/psql.sh -f ../../prepara_indices.sql";
echo $cmd
eval $cmd

