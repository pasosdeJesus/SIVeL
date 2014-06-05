# Saca y envia volcado de la base a otra máquina.
# Es apropiado para cron.
# Dominio público. 2005


if (test ! -f vardb.sh -o ! -f conf.php) then {
	echo "Ejecute desde directorio de un sitio";
	exit 1;
} fi;
. ./vardb.sh

../../bin/pgdump.sh
rm -f $rlocal/$n.gz
cp -f $rlocal/$h.gz $rlocal/$n.gz
if (test "$copiaphp" = "true") then {
	(cd ..; tar cfz $rlocal/sivelphp-$dm.tar.gz .)
} fi;

for i in $rremotos; do 
	echo scp -i $llave $rlocal/$n.gz $i
	scp -i $llave $rlocal/$n.gz $i
	if (test "$RESPALDOMES" = "1") then {
		echo scp -i $llave $rlocal/$h.gz $i
		scp -i $llave $rlocal/$h.gz $i
	} fi;
done;
