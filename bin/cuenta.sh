#!/bin/sh
# Muestra cuenta de registros de todas las tablas.
# Dominio público. 2006. vtamara@pasosdeJesus.org

if (test ! -f vardb.sh -o ! -f conf.php) then {
       echo "Ejecute desde el directorio del sitio";
} fi;


function ctabla {
	tabla=$1;
	n=`../../bin/psql.sh -c "SELECT COUNT(*) FROM $tabla;" | grep "^ [ ]*[0-9]*$"`
	printf "%30s %10s\n" "$tabla" "$n";
}


ctabla acto
ctabla actocolectivo
ctabla antecedente                     
ctabla antecedente_caso                
ctabla antecedente_combatiente         
ctabla antecedente_comunidad           
ctabla antecedente_victima             
ctabla caso                            
ctabla caso_contexto                   
ctabla categoria                       
ctabla categoria_caso                  
ctabla categoria_p_responsable_caso    
ctabla clase                           
ctabla combatiente                     
ctabla contexto                        
ctabla departamento                    
ctabla departamento_region             
ctabla descripcion_frontera            
ctabla escrito_caso                    
ctabla filiacion                       
ctabla filiacion_comunidad             
ctabla frontera                        
ctabla frontera_caso                   
ctabla fuente_directa                  
ctabla fuente_directa_caso             
ctabla funcionario                     
ctabla funcionario_caso                
ctabla intervalo                       
ctabla municipio                       
ctabla opcion                          
ctabla opcion_rol                      
ctabla organizacion                    
ctabla organizacion_comunidad          
ctabla p_responsable_agrede_combatiente
ctabla parametros_reporte_consolidado  
ctabla prensa                          
ctabla presuntos_responsables          
ctabla presuntos_responsables_caso     
ctabla profesion                       
ctabla profesion_comunidad             
ctabla rango_edad                      
ctabla rango_edad_comunidad            
ctabla region                          
ctabla region_caso                     
ctabla resultado_agresion              
ctabla rol                             
ctabla sector_social                   
ctabla sector_social_comunidad         
ctabla supracategoria                  
ctabla tipo_clase                      
ctabla tipo_violencia                  
ctabla ubicacion                       
ctabla usuario                         
ctabla victima                         
ctabla victima_colectiva               
ctabla vinculo_estado                  
ctabla vinculo_estado_comunidad        
