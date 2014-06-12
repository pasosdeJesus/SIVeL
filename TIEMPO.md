
# EVOLUCION DEL TIEMPO DE RESPUESTA DEL PROTOTIPO DE SIVEL 2

Tiempos en segundos.

## ESPECIFICACION DE SERVIDORES

SERVIDOR SJR-1:
* AMD FX-6100 3315MHz, 6 núcleos
* RAM: 8G
* Disco: 2 discos de 1T
* Plataforma: adJ 5.4, PostgreSQL 9.2.4, ruby 1.9, RoR 4.1.0rc1, unicorn 4.7.0


SERVIDOR SJR-2:
El mismo SJR-1 pero con
* Plataforma: adJ 5.4, PostgreSQL 9.3.2, ruby 2.0.0, RoR 4.1.0rc1, unicorn-4.8.2


SERVIDOR SJR-2d:
El mismo SJR-2 pero con
* Plataforma: adJ 5.4, PostgreSQL 9.3.2, ruby 2.0.0, RoR 4.1.0rc1, webricks

SERVIDOR SJR-3:
El mismo SJR-1 pero con
* Plataforma: adJ 5.4p1, PostgreSQL 9.3.2, ruby 2.0.0, RoR 4.1.0, unicorn-4.8.2


SERVIDOR SJR-3d:
El mismo SJR-3 pero con
* Plataforma: adJ 5.4p1, PostgreSQL 9.3.2, ruby 2.0.0, RoR 4.1.0, webricks

SERVIDOR SJR-4:
El mismo SJR-3 pero con
* Conexión a Internet por Claro 12MB fibra óptica. 6M de subida.


## ESPECIFICACION DE CLIENTES

CLIENTE V-1:
* AMD E-450. 1647.97 MHz
* RAM: 4G
* Disco: 500G
* Conexión a Internet por UNE Inalámbrico 2MB
* Plataforma: adJ 5.4, chrome, ruby 2.0, rails 4.0

CLIENTE V-1m:
El mismo CLIENTE V pero con
* Conexión a Internet por Movistar Mobil 3G.


CLIENTE V-2:
* AMD E-450. 1647.97 MHz
* RAM: 4G
* Disco: 500G
* Conexión a Internet por UNE Inalámbrico 2MB
* Plataforma: OpenBSD current (5.6), chrome, ruby 2.1, rails 4.1

CLIENTE V-2:
* AMD E-450. 1647.97 MHz
* RAM: 4G
* Disco: 500G
* Conexión a Internet por UNE Inalámbrico 2MB
* Plataforma: OpenBSD current (5.6), chrome, ruby 2.1, rails 4.1

CLIENTE V-3d:
* AMD E-450. 1647.97 MHz
* RAM: 4G
* Disco: 500G
* Conexión a Internet por UNE Inalámbrico 2MB. 
* Plataforma: OpenBSD current (5.6), chrome, ruby 2.1.2, rails 4.1.1


CLIENTE BD-CF1:
* AMD FX(tm)-8320 Eight-Core Processor 
* RAM: 8G
* Disco: 1T
* Conectado en red Ethernet de 1G con SJR-4
* Plataforma: adJ 5.4, chrome 28.0.1500.45

CLIENTE BD-CF2d:
* AMD FX(tm)-8320 Eight-Core Processor 
* RAM: 8G
* Disco: 1T
* Conectado en red Ethernet de 1G
* Plataforma: adJ 5.5, ruby 2.1.0, rails 4.1.1, webrick, chrome 32

CLIENTE BD-CF3d:
* AMD FX(tm)-8320 Eight-Core Processor 
* RAM: 8G
* Disco: 1T
* Conectado en red Ethernet de 1G
* Plataforma: adJ 5.5, ruby 2.1.2, rails 4.1.1, webrick, chrome 32


## MEDICIONES

### Fecha: 19.Mar.2014. Servidor: SJR-1. Cliente: V-1m
* Autenticar: 6.45
* Editar una actividad: 4.45


### Fecha: 20.Mar.2014. Servidor: SJR-2. Cliente: V-1m
* Autenticar: 6.97
* Listado de casos: 7.75
* Listado de casos: 1.51
* Editar un caso: 10.58
* Agregar etiqueta y guardar: 8.29
* Editar de nuevo: 9:37


### Fecha: 20.Mar.2014. Servidor: SJR-2d. Cliente: V-1m
* Autenticar: 10.72
* Listado de casos: 2.11
* Editar un caso: 10.34
* Agregar etiqueta y guardar: 8.76


### Fecha: 20.Mar.2014. Servidor: SJR-2. Cliente: V-1
* Autenticar: 6.43
* Lista de actividades: 1.94
* Editar una actividad: 1.09
* Listado de casos: 1.16
* Editar un caso: 7.08
* Agregar etiqueta y guardar: 2.37
* Editar de nuevo: 5.40


### Fecha: 21.Mar.2014. Servidor: V-1 (sin Internet). Cliente: V-1 (sin Internet)
* Autenticar: 8.73
* Lista de actividades: 0.7
* Editar una actividad: 1.47
* Lista de casos: 1.37
* Editar un caso: 12.85
* Agregar etiqueta y guardar: 9.63
* Editar de nuevo: 12.82

### Fecha: 13.Abr.2014. Servidor: SJR-3d. Cliente: V-1
* Autenticar: 4.32
* Lista de actividades: <1
* Editar una actividad: <1
* Listado de casos: <1
* Editar un caso: 6.5
* Agregar etiqueta y guardar: 4
* Editar de nuevo: 5.1

### Fecha: 19.Abr.2014.  Servidor: V-2 (sin Internet). Cliente: V-2 (sin Internet).
* Autenticar: 2.76
* Lista de actividades: 1.3
* Editar una actividad: 2
* Lista de casos: 1.87
* Editar un caso: 10.27
* Agregar etiqueta y guardar: 5.26
* Editar de nuevo: 10.12

### Fecha: 24.Abr.2014. Servidor: SJR-4. Cliente: V-2 
Autenticar: 2.76
Lista de actividades: 1.3
Editar una actividad: 2
Lista de casos: 1.87
Editar un caso: 10.27
Agregar etiqueta y guardar: 5.26
Editar de nuevo: 10.12

### Fecha: 12.May.2014. Servidor: S-4. Cliente: BD-CF1. 
* Autenticar: 6.39
* Lista de actividades: 1.3
* Editar una actividad: 1.55
* Lista de casos: 0.3
* Editar un caso: 5.3
* Agregar etiqueta y guardar: 2
* Editar de nuevo: 6.31

### Fecha: 13.May.2014. Servidor: SJR-4. Cliente: V-2 
Autenticar: 5.3
Lista de actividades: 0.6
Editar una actividad: 1.1
Lista de casos: 0.4
Editar un caso: 6.5
Agregar etiqueta y guardar: 3
Editar de nuevo: 6.45

### Fecha: 16.May.2014.  Servidor: SJR-4. Cliente: Windows desde Oficina Zulia/Venezuela
* Autenticar: 7
* Lista de actividades: 3.2
* Editar una actividad: 2.1
* Lista de casos: 2.1
* Editar un caso: 9.1
* Agregar etiqueta y guardar: 3.2
* Editar de nuevo: 10.7

### Fecha: 20.May.2014.  Servidor: SJR-4. Cliente: Windows Randolf desde Apure/Venezuela
* Autenticar: 6.7
* Lista de actividades: 7.1
* Editar una actividad: 6.9
* Lista de casos: 7
* Editar un caso: 16.9
* Agregar etiqueta y guardar: 3.6
* Editar de nuevo: 17.1

### Fecha: 21.May.2014.  Servidor: SJR-4. Cliente: Windows Randolf desde Tachira/Venezuela
* Autenticar: 6.4
* Lista de actividades: 0.5
* Editar una actividad: 7.3
* Lista de casos: 1.8
* Editar un caso: 9.7
* Agregar etiqueta y guardar: 2.7
* Editar de nuevo: 8.5


### Fecha: 27.May.2014.  Servidor: V-3 (sin Internet). Cliente: V-3 (sin Internet).
* Autenticar: 9.5
* Lista de actividades: 1.5
* Editar una actividad: 1.47
* Lista de casos: 1.73
* Editar un caso: 13.44
* Agregar etiqueta y guardar: 13
* Editar de nuevo: 9.82

### Fecha: 11.Jun.2014.  Servidor/cliente: BD-CF2d 
Pruebas locales vistas sobre Internet por un proxy sobre conexión UNE de 2MB. 
* Autenticar: 2.8
* Lista de actividades: 0.2
* Editar una actividad: 1.76
* Lista de casos: 0.4
* Editar un caso: 4.57
* Agregar etiqueta y guardar: 3.75
* Editar de nuevo: 3.92


### Fecha: 11.Jun.2014.  Servidor/cliente: BD-CF3d 
Pruebas locales vistas sobre Internet por un proxy sobre conexión UNE de 2MB. 
* Autenticar: 3,3
* Lista de actividades: 0,5
* Editar una actividad: 0,7
* Lista de casos: 0,5
* Editar un caso: 5,65
* Agregar etiqueta y guardar: 4,2
* Editar de nuevo: 3,21

### Fecha: 11.Jun.2014.  Servidor/cliente: BD-CF3
Pruebas locales vistas sobre Internet por un proxy sobre conexión UNE de 2MB. 
* Autenticar: 3,3
* Lista de actividades: 0,5
* Editar una actividad: 0,7
* Lista de casos: 0,5
* Editar un caso: 5,65
* Agregar etiqueta y guardar: 4,2
* Editar de nuevo: 3,21


