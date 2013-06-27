#!/bin/sh
# Variables relacionadas con la base de datos y respaldos. 
# Dominio público. 2013. Sin garantías.
# Basado en script de http://structio.sourceforge.net/seguidor

# Directorio donde reside la aplicación
dirap=/var/www/htdocs/sivel//sitios/sivel

if (test -f ./vardb-particular.sh) then {
	. ./vardb-particular.sh
} fi;

. ../pordefecto/vardb.sh


