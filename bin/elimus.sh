#!/bin/sh
# Permite eliminar un usuario con un rol
# Dominio p�blico. 2009.

if (test "$DIRSEG" = "") then {
	DIRSEG="../../"; # Suponemos que corre desde dir con fuentes
} fi;

if (test ! -f "vardb.sh" -o ! -f "conf.php" ) then {
	echo "Ejecute desde directorio del sitio o especifiquelo en DIRSEG";
	exit 1;
} fi;


. ./vardb.sh

id="$1"
if (test "$id" = "") then {
	echo "Primer par�metro debe ser id del usuario por eliminar";
	exit 1;
} fi;


q="SET client_encoding to 'LATIN1'; DELETE FROM usuario WHERE nusuario='$id'"
echo $q;
$DIRSEG/bin/psql.sh -c "$q"


