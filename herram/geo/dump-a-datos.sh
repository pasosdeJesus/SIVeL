#!/bin/sh
# Genera archivo con datos geofráficos a partir de un sitio actualizado
# Dominio público. 2013. Sin garantías.

arc=$1;
if (test ! -f ./vardb.sh -o ! -f conf.php) then {
	echo "Ejecute desde directorio del sitio con info geográfica actualizada";
	exit 1;
} fi;

. ./vardb.sh

echo "Sacando volcado de base";
. ../../bin/pgdump.sh

if (test ! -f $rlocal/$n-s) then {
	echo "Falta volcado de la base en $rlocal/$n-s";
	exit 1;
} fi;
if (test "$arc" = "") then {
	arc="/tmp/geo.sql";
} fi;
echo "-- Información geográfica SIVeL" > $arc
echo "" >> $arc
echo "-- Información extraida de DIVIPOLA (http://www.dane.gov.co/Divipola/), 
--   Divipolador (http://sidih.colombiassh.org/im/divipolaLH/), 
--   OpenStreetMap (http://www.openstreetmap.org) 
--   Banco de Datos del CINEP y aportes de voluntarios(as) 
--   especialmente de la Red de Bancos de Datos de DH y DIH" >> $arc
echo "-- Recopilado, gracias a Dios, por vtamara@pasosdeJesus.org" >> $arc
echo "-- Dominio público de acuerdo a legislación colombiana. Sin garantías." >> $arc
echo "" >> $arc
echo "-- Los registros con fechadeshabilitacion no nula son históricos." >> $arc;
echo "" >> $arc
echo "" >> $arc
echo "SET client_encoding = 'UTF8';" >> $arc
echo "" >> $arc
echo "Generando tclase";
echo "--" >> $arc
grep "INSERT INTO tclase " $rlocal/$n-s  >> $arc
for i in departamento municipio clase tsitio frontera region ; do
	echo "Generando $i";
	echo "--" >> $arc
	grep "INSERT INTO $i " $rlocal/$n-s  >> $arc
	echo "" >> $arc
	echo "SELECT setval('${i}_seq', max(id)) FROM $i;" >> $arc
	echo "" >> $arc
done

echo "Archivo $arc generado";

