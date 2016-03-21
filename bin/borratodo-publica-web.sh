#!/bin/sh
# Instala copia de base
# vtamara@pasosdeJesus.org. Dominio público. 2007

# Este archivo es apropiado para ser usado con cron.
# Por ejemplo programe la actualización diaria a las 14:00M con
# $ crontab -e
# 0 14 * * * cd /home/sivel/sivel/; ./pubweb.sh > /tmp/pubweb-stdout 2>&1

# Ojo con permisos.  Verifique que opera bien con el usuario que
# ejecutará la tarea.

if (test ! -f vardb.sh -o ! -f conf.php) then {
        echo "Ejecute desde directorio del sitio";
        exit 1;
} fi;

. ./vardb.sh


echo "Presiones [ENTER] para borrar  la base $dbnombre del usuario $dbusuario";
read;

cmd="dropdb $socketopt -U $dbusuario $dbnombre";
echo $cmd;
eval $cmd;

cmd="createdb $socketopt -U $dbusuario $dbnombre";
echo $cmd;
eval $cmd;

gzip -d $dirweb/web-sf-sinf-$dm.sql.gz
../../bin/psql.sh -f $dirweb/web-sf-sinf-$dm.sql;
# "Y modifique fechas en /home/sivel/sivel/auth/conf.php";
../../bin/psql.sh -c "INSERT INTO usuario(id, nusuario, password, nombre, 
	descripcion, rol, idioma, fechacreacion)  VALUES 
	(1, 'admin', '45bdfc3bf7e421561805fb56b59d577e', 
	'Administrador@', '', '1', 'es_CO', '2001-01-01'); "
../../bin/psql.sh -c "INSERT INTO caso_usuario  (id_usuario, id_caso)
	SELECT 1, id FROM caso";


