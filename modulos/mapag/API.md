El módulo de mapas opera sobre el API de Google Maps y obtiene dinámicamente los datos que presenta de una instancia de SIVeL 1.2.

Obtiene datos de SIVeL 1.2 mediante 2 rutas:
1. casos_sivel_remote.php  Responde con un conjunto de casos y pocos detalles de cada uno
2. caso_detalles_sivel_remote.php Responde con detalles de un sólo caso

A continuación se detallan los parámetros que cada una de estas dos rutas puede manejar:

I. casos_sivel_remote.php:

Sólo responde a GET, con parámetros que permiten filtrar los casos que retorna así:

- desde: Fecha inicial en formato ISO (es indispensable).
- hasta: Fecha final en formato ISO 
- departamento:  Identificación del departamento 
- prresp: Identificación del presunto responsable
- tvio: Identificación del tipo de violencia
- etiqueta: Identificación de la etiqueta


Responde con los casos que pasan el filtro en un JSON consistente de un objeto, cada item del objeto tendrá como propiedad la identificacin de un caso y su valor será un objeto con detalles del mismo:
 - id: identificación del caso
   - latitud, longitud Con latitud y longitud en 
   - titulo: Titulo del caso
   - fecha: Fecha del caso

Por ejemplo mediante una peticin GET al URL https://base.nocheyniebla.org/modulos/mapag/casos_sivel_remote.php?desde=2018-01-01&hasta=2018-06-30&departamento=5&tvio=A&prresp=4

Se solicitan los casos del primer semestre del 2018 en el departamento de Antioquia (con id 5 en tabla departamento), que sean violaciones a Derechos Humanos (A es Derechos Humanos en tabla tviolencia) y del presunto reponsable (cuyo código es 4 en la tabla presponsable).

La respuesta será un JSON de la forma:

```JSON
{
  158601: {
    fecha: "2018-01-09"
    latitud: "7.88592040204419"
    longitud: "-76.6348432017632"
    titulo: ""
  },
  158606: {
    fecha: "2018-02-15"
    latitud: "7.88509472352357"
    longitud: "-76.635152884457"
    titulo: ""
  },
  158612: {
    fecha: "2018-03-02"
    latitud: "7.88596001302042"
    longitud: "-76.6343585952416"
    titulo: ""
  },
  158614: {
    fecha: "2018-03-08"
    latitud: "7.88567243205197"
    longitud: "-76.6350480509681"
    titulo: ""
  }, 
  158615: {
    fecha: "2018-03-15"
    latitud: "7.88540798598625"
    longitud: "-76.6345313593739"
    titulo: ""
  }, 
  158626: {
    fecha: "2018-04-27"
    latitud: "7.88550014534744"
    longitud: "-76.6346938766204"
    titulo: ""
  },
  158629: {
    fecha: "2018-04-30"
    latitud: "7.88604828340681"
    longitud: "-76.6351158279358"
    titulo: ""
  },
  158630: {
    fecha: "2018-05-05"
    latitud: "7.88528522478287"
    longitud: "-76.6346952392787"
    titulo: ""
  },
  158926:{ 
    fecha: "2018-04-22"
    latitud: "7.00784579460548"
    longitud: "-73.9143915476742"
    titulo: ""
  }
}
```

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
    
  
