# Saca y envia volcado de la base a DVD
# Dominio público. 2009

if (test ! -f ./conf.sh -o ! -f ./confv.sh) then {
	echo "Ejecute desde directorio con fuentes de SIVeL";
	exit 1;
} fi;

. ./confv.sh

if (test "${IMAGENRLOCAL}" = "") then {
    echo "Favor ejecutar ./conf.sh -i antes";
    exit 1;
} fi;


./bin/resptodositio.sh
cmd="doas growisofs -Z /dev/rcd0c -R ${IMAGENRLOCAL}"
echo "$cmd"
eval "$cmd"

