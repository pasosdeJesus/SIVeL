#!/bin/sh
#Elimina un caso por código
# vtamara@pasosdeJesus.org 2013 Dominio Público

c=$1
if (test "$c" = "") then {
	echo "Falto caso por eliminar";
	exit 1;
} fi;

../../bin/psql.sh -c "DELETE FROM caso_funcionario WHERE id_caso='$c'";
../../bin/psql.sh -c "DELETE FROM caso WHERE id='$c'";
