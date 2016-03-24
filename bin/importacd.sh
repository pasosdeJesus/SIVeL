#!/bin/sh
# Restaura SIVeL de CD quemado con copiacd.sh. 
# Dominio público. vtamara@pasosdeJesus.org. 2008

if (test ! -f ./vardb.sh -o ! -f conf.php) then {
	echo "Ejecute desde directorio del sitio";
	exit 1;
} fi;


. ./vardb.sh

doas mount /mnt/cdrom/ 
doas /sbin/umount /dev/svnd0c 
doas /usr/sbin/vnconfig -u svnd0c
doas /usr/sbin/vnconfig -ckv svnd0 /mnt/cdrom/resbase.img
doas /sbin/fsck_ffs -y /dev/svnd0c
doas mkdir -p /mnt/tmp
doas /sbin/mount -o ro /dev/svnd0c /mnt/tmp
(cd /mnt/tmp;ls -l /mnt/tmp)
echo -n "¿Qué volcado restaurar? ";
read nom;
while (test ! -f "/mnt/tmp/$nom"); do
	echo "Ingrese uno de los volcados que pueden recuperarse:";
	(cd /mnt/tmp;ls -l /mnt/tmp)
	echo -n "¿Qué volcado restaurar? ";
	read nom;
done;
nomsql=`echo $nom | sed -e "s/sql.gz/sql/g"`;
mkdir -p tmp/res
cp /mnt/tmp/$nom tmp/res
gzip -d tmp/res/$nom
echo "Por remplazar base $dbnombre con volcado tmp/res/$nomsql";
cmd="../../bin/psql.sh -f tmp/res/$nomsql";
echo "[ENTER] para ejecutar $cmd";
read
eval $cmd
echo "Actualizando indices";
cmd="../../bin/psql.sh -f prepara_indices.sql";
echo $cmd
eval $cmd



