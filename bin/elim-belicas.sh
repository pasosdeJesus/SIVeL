# Elimina bélicas de la base de datos
# Dominio público. 2008. vtamara@pasosdeJesus.org

if (test ! -f ./vardb.sh -o ! -f conf.php) then {
	echo "Ejecute desde directorio del sitio";
	exit 1;
} fi;

. ./vardb.sh

. ../../bin/elimcomun.sh

echo "Este script eliminará clasificación de bélicas de casos así como casos que sólo tengan clasificación de bélicas (y las víctimas que pudieran tener erradamente  asociadas.  Confirma continuar (s para continuar)";
read sn
if (test "$sn" = "s") then {
	../../bin/psql.sh -c "DELETE FROM antecedente_combatiente;"
	../../bin/psql.sh -c "DELETE FROM combatiente_presponsable;"
	../../bin/psql.sh -c "DELETE FROM combatiente;"
	../../bin/psql.sh -c "DELETE FROM combatiente;"
	../../bin/psql.sh -c "DELETE FROM caso_categoria_presponsable WHERE id_tviolencia='C';"
	../../bin/psql.sh -c "DELETE FROM actocolectivo WHERE id_categoria IN (SELECT id FROM categoria WHERE id_tviolencia='C');"
	../../bin/psql.sh -c "DELETE FROM acto WHERE id_categoria IN (SELECT id FROM categoria WHERE id_tviolencia='C');"

	../../bin/psql.sh -c "DROP VIEW nobelicas" > /dev/null 2> /dev/null
	../../bin/psql.sh -c "CREATE VIEW nobelicas AS (SELECT id_caso FROM acto UNION SELECT id_caso FROM actocolectivo UNION SELECT id_caso FROM caso_categoria_presponsable) ORDER BY 1";
	eliminafuera nobelicas;
} fi;
