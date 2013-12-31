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
ctabla antecedente_comunidad           
ctabla antecedente_victima             
ctabla caso                            
ctabla caso_contexto                   
ctabla caso_ffrecuente
ctabla caso_fotra
ctabla caso_usuario
ctabla categoria                       
ctabla caso_categoria_presponsable
ctabla clase                           
ctabla comunidad_filiacion
ctabla comunidad_organizacion
ctabla comunidad_profesion
ctabla comunidad_rangoedad
ctabla comunidad_sectorsocial
ctabla comunidad_vinculoestado
ctabla contexto                        
ctabla departamento                    
ctabla filiacion                       
ctabla fotra
ctabla ffrecuente
ctabla frontera                        
ctabla intervalo                       
ctabla municipio                       
ctabla organizacion                    
ctabla pconsolidado
ctabla presponsable
ctabla caso_presponsable
ctabla persona 
ctabla profesion                       
ctabla rangoedad                      
ctabla region                          
ctabla resagresion              
ctabla sectorsocial                   
ctabla supracategoria                  
ctabla tclase                      
ctabla tviolencia                  
ctabla ubicacion                       
ctabla usuario                         
ctabla victima                         
ctabla victimacolectiva               
ctabla vinculoestado                  
