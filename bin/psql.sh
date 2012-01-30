#!/bin/sh
# Abre base de datos con parametros configurados
# Dominio público.

if (test ! -f ./vardb.sh -o ! -f conf.php) then {
	echo "Ejecute desde directorio del sitio";
	exit 1;
} fi;


. ./vardb.sh

echo psql $socketopt -U $dbusuario -d $dbnombre "$@"
psql $socketopt -U $dbusuario -d $dbnombre "$@"
return "$?" 
