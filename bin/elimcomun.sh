#!/bin/sh
# Función para eliminar casos que no están en una vista

function eliminafuera {

	nvista=$1;
	if (test "$nvista" = "") then {
		echo "elimina_fuera requiere nombre de la vista con casos por preservar como primer   parámetro.  La vista debe tener un campo id_caso";
		exit 1;
	} fi;
	../../bin/psql.sh -c "SELECT COUNT(id_caso) FROM $nvista" >/dev/null 2> /dev/null
	if (test "$?" != "0") then {
		echo "No hay codigos de casos por preservar en vista $nvista, campo id_caso";
		exit 1;
	} fi;

	t=`../../bin/psql.sh -c "SELECT COUNT(id) FROM caso" | tail -n 3 | head -n 1`
	cc=`../../bin/psql.sh -c "SELECT COUNT(id_caso) FROM $nvista" | tail -n 3 | head -n 1`
	pe=`expr $t - $cc`;
	echo "Total de casos antes: $t";
	echo "Vista con casos por preservar: $nvista";
	echo "Total de casos en consulta por preservar: $cc";
	echo "Total de casos por eliminar: $pe";
	echo "Presione  [RETORNO] para continuar";
        read 

	for nt in caso_contexto caso_categoria_presponsable caso_presponsable antecedente_caso ubicacion caso_usuario acto antecedente_victima victima caso_region caso_frontera antecedente_comunidad comunidad_vinculoestado comunidad_profesion comunidad_filiacion comunidad_organizacion comunidad_rangoedad comunidad_sectorsocial actocolectivo victimacolectiva caso_etiqueta caso_ffrecuente caso_fotra anexo; do
		../../bin/psql.sh -c "DELETE FROM $nt WHERE id_caso NOT IN (SELECT DISTINCT id_caso FROM $nvista ORDER BY 1);"
	done
	../../bin/psql.sh -c "DELETE FROM caso WHERE id NOT IN (SELECT DISTINCT id_caso FROM $nvista ORDER BY 1);"

}


