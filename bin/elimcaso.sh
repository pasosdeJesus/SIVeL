#!/bin/sh
#Elimina un caso por código
# vtamara@pasosdeJesus.org 2013 Dominio Público

c=$1
if (test "$c" = "") then {
	echo "Falto caso por eliminar";
	exit 1;
} fi;

for nt in acto actocolectivo caso_usuario caso_contexto caso_categoria_presponsable caso_presponsable antecedente_caso ubicacion antecedente_victima victima caso_region caso_frontera antecedente_comunidad comunidad_vinculoestado comunidad_profesion comunidad_filiacion comunidad_organizacion comunidad_rangoedad comunidad_sectorsocial victimacolectiva caso_ffrecuente caso_fotra anexo caso_etiqueta; do
	echo $nt
	../../bin/psql.sh -c "DELETE FROM $nt WHERE id_caso='$c';"
done
../../bin/psql.sh -c "DELETE FROM caso WHERE id='$c'";
