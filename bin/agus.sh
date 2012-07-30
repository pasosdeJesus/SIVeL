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
echo -n "usuario (sin espacios): ";
read id;
echo -n "nombre: ";
read nombre;
echo -n "descripcion: ";
read descripcion;
echo "Roles:";
echo "  1- Administrador"
echo "  2- Analista"
echo "  3- Consulta"
echo "  4- Ayudante"
echo -n "Rol (1-4): ";
read idrol;
echo -n "anotación: ";
read anotacion;
echo -n "clave: ";
stty -echo; read clave; stty echo

clavesha1=$($PHP -n -r "echo sha1('$clave');")
q="SET client_encoding to 'UTF8'; INSERT INTO usuario(id_usuario, password, nombre, descripcion, id_rol)  VALUES ('$id', '$clavesha1', '$nombre', '$descripcion', '$idrol'); INSERT INTO funcionario(anotacion, nombre) VALUES ('$anotacion', '$id');" 
echo $q;
../../bin/psql.sh -c "$q"


