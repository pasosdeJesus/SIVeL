#!/bin/sh
# Saca volcado de las bases de todos los sitios 
# Dominio público. 2010. Sin garantías. 

# Este archivo es apropiado para ser usado con cron.
# Por ejemplo programe el respaldo diario a las 12:00M con
# $ crontab -e
# 0 12 * * * cd /var/www/htdocs/sivel/; ./bin/resptodositio.sh > /tmp/respaldo-stdout 2> /tmp/respaldo-stderr

# Ojo con permisos.  Verifique que opera bien con el usuario que
# ejecutará la tarea.

if (test ! -f "conf.sh" -o ! -f "confv.sh") then {
	echo "Ejecutar desde el directorio con fuentes de SIVeL";
	exit 1;
} fi;

s=`cd sitios; ls`
for i in $s; do 
	echo -n "sitios/$i ";
	if (test -d "sitios/$i") then {
	       if (test ! -h "sitios/$i" -a "$i" != "pordefecto" -a "$i" != "pruebas" -a -f "sitios/$i/conf.php") then {
		(cd sitios/$i; ../../bin/respaldo.sh)
	} else {
		echo "";
	} fi;
} fi;
done;

