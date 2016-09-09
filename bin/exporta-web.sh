#!/bin/sh
# Saca copia de la base sin fuentes ni usuarios y la envía a la
# máquina destinada para publicación
# vtamara@pasosdeJesus.org. Dominio público. 2007

# Este archivo es apropiado para ser usado con cron.
# Por ejemplo programe el respaldo diario a las 13:00M 
#  (después de respaldo.sh) con $ crontab -e
# 	0 12 * * * cd /home/sivel/sivel/; ./actweb.sh > /tmp/actweb.log 2>&1
# Ojo con permisos.  Verifique que opera bien con el usuario que
# ejecutará la tarea.

if (test ! -f vardb.sh -o ! -f conf.php) then {
        echo "Ejecute desde directorio de un sitio";
        exit 1;
} fi;

. ./vardb.sh

echo "Por generar copia sin fuentes para enviar a  $usuarioact@$maquinaweb:$dirweb";
echo "El usuario puede cambiarse en usuarioact de aut/conf.php";
echo "[ENTER] para continuar o [Ctrl]-[C] para detener";
read a
echo "1 de 3. Copia ..."
. ../../bin/pgdump.sh

echo "2 de 3 . Cambiando ..."
cp -f $rlocal/$h.gz $rlocal/web-sf.sql.gz
rm $rlocal/web-sf.sql
gzip -d $rlocal/web-sf.sql.gz

grep -a -v -f ../../bin/actweb.grep $rlocal/web-sf.sql > $rlocal/web-sf-sinf-$dm.sql
rm $rlocal/web-sf-sinf-$dm.sql.gz
gzip $rlocal/web-sf-sinf-$dm.sql
echo "3 de 3. Transfiriendo ..."
cmd="scp $opscpweb $rlocal/web-sf-sinf-$dm.sql.gz $usuarioact@$maquinaweb:$dirweb"
echo $cmd;
eval $cmd;

if (test "$?" = "0") then {
        echo "Ahora ingrese al servidor $maquinaweb y ejecute desde el directorio del sitio:"
        echo " $ ../../bin/borratodo-publica-web.sh";
        echo "Y modifique fechas en conf.php";
	echo "Para eliminar bélicas desde $maquinaweb ejecute: ";
        echo " $ ../../bin/elim-belicas.sh";
} fi;

