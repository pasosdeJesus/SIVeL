#!/bin/sh
# Función para eliminar casos que no están en una vista

function eliminafuera {

	nvista=$1;
	if (test "$nvista" = "") then {
		echo "elimina_fuera requiere nombre de la vista con casos por preservar como primer   parámetro.  La vista debe tener un campo id_caso";
		exit 1;
	} fi;
	../../bin/psql.sh -c "select count(id_caso) from $nvista" >/dev/null 2> /dev/null
	if (test "$?" != "0") then {
		echo "No hay codigos de casos por preservar en vista $nvista, campo id_caso";
		exit 1;
	} fi;

	t=`../../bin/psql.sh -c "select count(id) from caso" | tail -n 3 | head -n 1`
	cc=`../../bin/psql.sh -c "select count(id_caso) from $nvista" | tail -n 3 | head -n 1`
	pe=`expr $t - $cc`;
	echo "Total de casos antes: $t";
	echo "Vista con casos por preservar: $nvista";
	echo "Total de casos en consulta por preservar: $cc";
	echo "Total de casos por eliminar: $pe";
	echo "Presione  [RETORNO] para continuar";
        read 

	for nt in caso_contexto categoria_p_responsable_caso presuntos_responsables_caso antecedente_caso ubicacion funcionario_caso acto antecedente_victima victima region_caso frontera_caso antecedente_comunidad vinculo_estado_comunidad profesion_comunidad filiacion_comunidad organizacion_comunidad rango_edad_comunidad sector_social_comunidad actocolectivo victima_colectiva etiquetacaso escrito_caso fuente_directa_caso anexo; do
		../../bin/psql.sh -c "delete from $nt where id_caso not in (select distinct id_caso from $nvista order by 1);"
	done
	../../bin/psql.sh -c "delete from caso where id not in (select distinct id_caso from $nvista order by 1);"

}


