# Saca y envia volcado de la base a CD
# Dominio público. 2008

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
cmd="sudo mkisofs -r -l -f -o /tmp/copia.iso  ${IMAGENRLOCAL}"
echo "$cmd"
eval "$cmd"
cmd="sudo cdrecord dev=/dev/rcd0c -data speed=16 /tmp/copia.iso"
echo "$cmd"
eval "$cmd"

