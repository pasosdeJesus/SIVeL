#!/bin/sh
# Permite eliminar un usuario con un rol
# Dominio público. 2009.

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
	echo "Primer parámetro debe ser id del usuario por eliminar";
	exit 1;
} fi;


q="SET client_encoding to 'LATIN1'; DELETE FROM usuario WHERE id_usuario='$id'"
echo $q;
$DIRSEG/psql.sh -c "$q"


