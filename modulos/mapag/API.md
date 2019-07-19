El módulo de mapas opera sobre el API de Google Maps y obtiene dinámicamente los datos que presenta de una instancia de SIVeL 1.2.

Obtiene datos de SIVeL 1.2 mediante 2 rutas:
1. casos_sivel_remote.php  Responde con un conjunto de casos y pocos detalles de cada uno
2. caso_detalles_sivel_remote.php Responde con detalles de un sólo caso

A continuación se detallan los parámetros que cada una de estas dos rutas puede manejar:

I. casos_sivel_remote.php:

Los parámetros que puede obtener permiten filtrar los casos que retorna así:

- desde: Fecha inicial en formato ISO (es indispensable).
- hasta: Fecha final en formato ISO 
- departamento:  Identificación del departamento 
- prresp: Identificación del presunto responsable
- tvio: Identificación del tipo de violencia
- etiqueta: Identificación de la etiqueta


Responde con los casos que pasan el filtro en un JSON que consta de:
 - id: identificación del caso
   - latitud, longitud Con latitud y longitud en 
   - titulo: Titulo del caso
   - fecha: Fecha del caso


II. caso_detalles_sivel_remote.php:

Recibe sólo un parámetro que es obligatorio:
- id_caso: Identificación del caso del cual se dan detalles

Responde con los detalles del caso con un JSON que consta de:
-caso
  - id: Identificación
  - titulo
  - hechos: Descripción o memo del caso
  - fecha
  - hora 
  - departamento: principal
  - municipio: principal
  - centro_poblado: principal
  - presponsables:
    - id: nombre
  - victimas:
    - id: nombres apellidos
    
  
