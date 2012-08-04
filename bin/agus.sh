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
echo "  1- Administrador"
echo "  2- Analista"
echo "  3- Consulta"
echo "  4- Ayudante"
echo -n "Rol (1-4): ";
read idrol;
if (test "$idrol" != "1" -a "$idrol" != "2" -a "$idrol" != "3" -a "$idrol" != "4") then {
	echo "NO se eligió rol disponible, eligiendo Analista";
	$idrol = "2";
} fi;
echo -n "Anotación: ";
read anotacion;
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

clavesha1=$($PHP -n -r "echo sha1('$clave');")
q="SET client_encoding to 'UTF8'; INSERT INTO usuario(id_usuario, password, nombre, descripcion, id_rol, idioma)  VALUES ('$id', '$clavesha1', '$nombre', '$descripcion', '$idrol', '$idsel'); INSERT INTO funcionario(anotacion, nombre) VALUES ('$anotacion', '$id');" 
echo $q;
../../bin/psql.sh -c "$q"


