#!/bin/sh
# Variables relacionadas con la base de datos y respaldos. 
# Dominio público. 2004. Sin garantías.
# Basado en script de http://structio.sourceforge.net/seguidor

if (test ! -f $dirap/conf.php) then {
	echo "Modifique la variable dirap del script vardb.sh para que tenga directorio de    instalacion";
	exit 1;
} fi;

function valconf {
	nv=$1;
	if (test  "$nv" = "") then {
		echo "Falta nombre de variable como 1er parámetro";
		exit 1; 
	} fi;
	res="ImP0S1BLi"
	for a in $dirap/conf-local.php $dirap/conf.php $dirap/../pordefecto/conf.php; do
		if (test "$res" = "ImP0S1BLi") then {
			grep "\$$nv *=" $a 2> /dev/null > /dev/null
			if (test "$?" = "0") then {
				res=`grep "\\\$$nv *=" $a | sed -e 's/.*=.*"\([^"]*\)".*$/\1/g'`;
			} fi;
		} fi;
	done;
	if (test "$res" = "ImP0S1BLi") then {
		echo "No está la variable $nv en $dirap/conf-local.php ni en $dirap/conf.php ni en $dirap/../pordefecto/conf.php. Asegurese de ejecutar actualiza.php";
		exit 1;
	} fi;
	cmd="$nv=\"$res\";";
	#echo "OJO valconf: $cmd";
	eval "$cmd";
}

# BASE DE DATOS

valconf dbnombre ;
valconf dbusuario ;
valconf socketopt ;
valconf dirchroot ;
valconf dirserv ;
valconf dirsitio ;
valconf modulos ;
valconf copiaphp;

dirfuentes="${dirchroot}${dirserv}";
dirap2="${dirfuentes}/${dirsitio}";
if (test "$dirap" != "$dirap2") then {
	echo "No coinciden dirap de vardb.sh ($dirap) con dirchroot dirserv dirsitio de conf.php ($dirap2)";
	echo "Presione cualquir [ENTER] para terminar";
	read
	exit 1;
} fi;

# VOLCADOS Y RESPALDOS

d=`date "+%a"`  # Dia de la semana
dm=`date "+%d"` # Dia del mes

valconf imagenrlocal
valconf rlocal

# Nombres de archivos
nomsemana=$dbnombre-dump-$d.sql
n=$dbnombre-dump-$d.sql

nommes=$dbnombre-dump-$dm.sql
h=$dbnombre-dump-$dm.sql

## COPIAS DE RESPALDO A OTRO SERVIDOR CON SCP

valconf rremotos 
valconf llave 

## PUBLICACI?N EN P?GINA WEB

valconf usuarioact
valconf maquinaweb
valconf dirweb
valconf opscpweb
