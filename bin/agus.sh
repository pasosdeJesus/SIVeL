#!/bin/sh
# Permite agregar un usuario con un rol
# Dominio público. vtamara@pasosdeJesus.org

DIRSEG=$1
if (test "$DIRSEG" = "") then {
	DIRSEG="."; # Suponemos que corre desde dir de un sitio
} fi;

if (test ! -f "$DIRSEG/vardb.sh" ) then {
	echo "Ejecute desde directorio de un sitio o especifique este como primer parámetro";
	exit 1;
} fi;


. $DIRSEG/vardb.sh
. $DIRSEG/../../confv.sh

if (test $PHP = "") then {
	echo "Falta configurar fuentes";
	exit 1;
} fi;
echo -n "Usuario (sin espacios): ";
read id;
echo -n "Nombre: ";
read nombre;
echo -n "Descripcion: ";
read descripcion;
echo "Roles:";
minr=0;
maxr=0;
for i in ${ROLESDISP}; do
	n=`echo $i | sed -e "s/[^0-9]*\([0-9]*\),.*/\1/g"`
	l=`echo $i | sed -e "s/[^0-9]*[0-9]*,\(.*\)/\1/g"`
	echo "  " $n "- " $l;
	if (test "$n" -lt "$minr" -o "$minr" = "0") then {
		minr=$n;
	} fi;
	if (test "$n" -gt "$maxr" -o "$maxr" = "0") then {
		maxr=$n;
	} fi;
done;
echo -n "Rol ($minr-$maxr): ";
read idrol;
sidrol=""
for i in ${ROLESDISP}; do
	n=`echo $i | sed -e "s/[^0-9]*\([0-9]*\),.*/\1/g"`
	l=`echo $i | sed -e "s/[^0-9]*[0-9]*,\(.*\)/\1/g"`
	if (test "$idrol" = "$n") then {
		sidrol="$n";
	} fi;
done;
if (test "$sidrol" = "") then {
	echo "No se eligió uno de los roles disponibles, eligiendo 2";
	sidrol="2";
} fi;
echo "Idiomas disponibles: ";
for i in ${LENGDISP}; do
	echo "  " $i;
done;
echo -n "Idioma: ";
read idioma
idsel="";
for i in ${LENGDISP}; do
	if (test "$idioma" = "$i") then {
		idsel="$i";
	} fi;
done;
if (test "$idsel" = "") then {
	echo "No se eligió uno de los idiomas disponibles, eligiendo es_CO";
	idsel="es_CO";
} fi;
echo -n "Clave: ";
stty -echo; read clave; stty echo
fecha=`date +%Y-%m-%d`
clavesha1=$($PHP -n -r "echo sha1('$clave');")
q="SET client_encoding to 'UTF8'; INSERT INTO usuario(nusuario, password, nombre, descripcion, rol, idioma, fechacreacion)  VALUES ('$id', '$clavesha1', '$nombre', '$descripcion', '$sidrol', '$idsel', '$fecha');" 
echo $q;
../../bin/psql.sh -c "$q"


