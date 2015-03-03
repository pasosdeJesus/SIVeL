#!/bin/sh
# Preparar nuevo sitio
# Dominio p?blico. vtamara@pasosdeJesus.org 2009

ns=$1;
if (test "$ns" == "") then {
	echo "Primer par?metro debe ser nombre de nuevo sitio";
	exit 1;
} fi;

if (test ! -f "nuevo.sh") then {
	echo "Ejecutar desde directorio SIVeL";
	exit 1;
} fi;

if (test ! -f "../confv.sh") then {
	echo "Primero configurar SIVeL con cd ..; ./conf.sh"
	exit 1;
} fi;

dt=`pwd`;
dts=`echo $dt | sed -e "s/sitios$//g"`;
dtchroot=`echo $dts | sed -e "s/^\/var\/www//g"`;
if (test "$dt" = "$dts") then {
	echo "Ejecutar desde directorio SIVeL";
	exit 1;
} fi;

edts=`echo $dts | sed -e "s/\//\\\\\\\\\//g"`
edtchroot=`echo $dtchroot | sed -e "s/\//\\\\\\\\\//g"`

#echo "edts=$edts";
#echo "edtchroot=$edtchroot";

nomplant="conf.php.plantilla"

if (test "$CON_TODO" = "1") then {
	nomplant="conf.php.todomodulo.plantilla"
} fi;

if (test "$usivel" = "") then {
	usivel=`whoami`;
} fi;
CLSIVELPG="xyz"
if (test -f /home/$usivel/.pgpass) then {
	CLSIVELPG=`grep ":sivel:" /home/$usivel/.pgpass | sed -e 's/.*:sivel://g' 2> /dev/null`
} fi;

mkdir -p $ns/DataObjects
if (test ! -f $ns/conf.php) then {
  sed -e "s/dbnombre *= *\".*\"/dbnombre = \"$ns\"/g;s/dbclave *= *\".*\"/dbclave = \"$CLSIVELPG\"/g;s/dirsitio *= *\".*\"/dirsitio = \"sitios\/$ns\"/g;s/dirserv *= *\".*\"/dirserv = \"$edtchroot\"/g" pordefecto/${nomplant} > $ns/conf.php
  sed -e "s/dbnombre *= *\".*\"/dbnombre = \"$ns\"/g;s/dbclave *= *\".*\"/dbclave = \"$CLSIVELPG\"/g;s/dirsitio *= *\".*\"/dirsitio = \"sitios\/$ns\"/g;s/dirserv *= *\".*\"/dirserv = \"$edtchroot\"/g" pordefecto/${nomplant} > $ns/conf.php
} fi;

if (test ! -f $ns/conf-local.php) then {
  sed -e "s/dbnombre *= *\".*\"/dbnombre = \"$ns\"/g;s/dbclave *= *\".*\"/dbclave = \"$CLSIVELPG\"/g;s/dirsitio *= *\".*\"/dirsitio = \"sitios\/$ns\"/g;s/dirserv *= *\".*\"/dirserv = \"$edtchroot\"/g" pordefecto/conf-local.php.plantilla > $ns/conf-local.php
} fi;
if (test ! -f $ns/vardb.sh) then {
  sed -e "s/dirap *= *.*/dirap=$edts\/sitios\/$ns/g" pordefecto/vardb.sh.plantilla > $ns/vardb.sh
} fi;
if (test ! -f $ns/vardb-local.sh) then {
  sed -e "s/dirap *= *.*/dirap=$edts\/sitios\/$ns/g" pordefecto/vardb-local.sh.plantilla > $ns/vardb-local.sh
} fi;
if (test ! -f $ns/conf_int.php) then {
  cp pordefecto/conf_int.php.plantilla $ns/conf_int.php
} fi;
if (test ! -f $ns/conf_int-local.php) then {
  cp pordefecto/conf_int-local.php.plantilla $ns/conf_int-local.php
} fi;
sudo touch $ns/ultimoenvio.txt
sudo chown -f www:www $ns/ultimoenvio.txt
sudo chgrp www $ns/conf*.php
sudo chmod o-rwx $ns/conf*php
touch $ns/DataObjects/estructura-dataobject.ini
touch $ns/DataObjects/estructura-dataobject.links.ini
sudo touch $ns/DataObjects/$ns.ini
sudo touch $ns/DataObjects/$ns.links.ini

sudo chown www:www $ns/DataObjects/$ns.ini
sudo chown www:www $ns/DataObjects/$ns.links.ini
if (test ! -f $ns/centro.jpg) then {
  cp ../imagen/sivel12-es.jpg $ns/centro.jpg
} fi;
if (test ! -f $ns/centro.html) then {
  cp ../centro_principal-es.html $ns/centro.html
} fi;
if (test -f ../imagen/fondo.jpg -a ! -f $ns/fondo.jpg) then {
    cp ../imagen/fondo.jpg $ns/fondo.jpg
} fi;

cd $ns
if (test "$SIN_CREAR" != "1") then {

	../../bin/creapg.sh
	if (test "$SIN_ESQUEMA" != "1") then {
		../../bin/agus.sh
	} fi;
	sudo ../../bin/creaesquema.sh
} fi;
sudo chown -f www:www DataObjects/$ns.ini
sudo chown -f www:www DataObjects/$ns.links.ini
