#!/bin/sh
# Variables relacionadas con la base de datos y respaldos. 
# Dominio público. 2004. Sin garantías.
# Basado en script de http://structio.sourceforge.net/seguidor


# Directorio donde reside la aplicación
dirap=/var/www/htdocs/sivel/sitios/sivel

if (test ! -f $dirap/conf.php) then {
	echo "Modifique la variable dirap del script vardb.sh para que tenga directorio de      instalacion";
	exit 1;
} fi;

function valconf {
	nv=$1;
	if (test  "$nv" = "") then {
		echo "Falta nombre de variable como 1er parámetro";
		exit 1; 
	} fi;
	grep "\$$nv *=" $dirap/conf.php 2> /dev/null > /dev/null
	if (test "$?" != "0") then {
		echo "No está la variable $nv en $dirap/conf.php. Asegurese de ejecutar actualiza.php";
		cmd="grep \"\$$nv *=\" $dirap/conf.php";
		eval "$cmd";
		exit 1;
	} fi;
	res=`grep "\\\$$nv *=" $dirap/conf.php | sed -e 's/.*=.*"\([^"]*\)".*$/\1/g'`;
	cmd="$nv=\"$res\";";
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

## PUBLICACIÓN EN PÁGINA WEB

valconf usuarioact
valconf maquinaweb
valconf dirweb
valconf opscpweb
