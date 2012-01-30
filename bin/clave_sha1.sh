#!/bin/sh
# Cambia condensado de clave de un usuario de md5 a sha1
# Dominio público. 2009

if (test "$DIRSEG" = "") then {
	DIRSEG="../../"; # Suponemos que corre desde dir con fuentes
} fi;

if (test ! -f "$DIRSEG/vardb.sh" ) then {
	echo "Ejecute desde directorio de un sitio o especifiquelo en DIRSEG";
	exit 1;
} fi;


. $DIRSEG/vardb.sh

usuario="$1"
if (test "$usuario" = "") then {
	echo "Primer parámetro debe ser usuario";
	exit 1;
} fi;

# Aseguramos que se podrá convertir
$DIRSEG/../../bin/psql.sh -c "ALTER TABLE usuario ADD COLUMN npass VARCHAR(64);"  > /dev/null
$DIRSEG/../../bin/psql.sh -c "UPDATE usuario SET npass=password;" > /dev/null
$DIRSEG/../../bin/psql.sh -c "ALTER TABLE usuario DROP COLUMN password;" > /dev/null
$DIRSEG/../../bin/psql.sh -c "ALTER TABLE usuario RENAME COLUMN npass TO password;" > /dev/null

c=`$DIRSEG/../../bin/psql.sh -t -c "SELECT password FROM usuario WHERE id_usuario='$usuario';" | grep -v "psql" | sed -e "s/  *//g"`

echo "usuario: $usuario";
esmd5=`echo $c | sed -e "s/................................//"`
if (test "$esmd5" != "") then {
	echo "El condensado de la clave no es md5.";
	exit 1;
} fi;

echo -n "clave? ";
stty -echo; read clave; stty echo
echo

clavemd5=`php -n -r "echo md5(\"$clave\");"`;
if (test "$c" != "$clavemd5") then {
	echo "Clave incorrecta, ingresada '$clavemd5', esperada '$c'";
	exit 1;
} fi;

clavesha1=`php -n -r "echo sha1(\"$clave\");"`;
q="SET client_encoding to 'LATIN1'; UPDATE usuario SET password='$clavesha1' WHERE id_usuario='$usuario';"
$DIRSEG/../../bin/psql.sh -c "$q" 
echo "Condensado de la clave cambiado de md5 a sha1";

