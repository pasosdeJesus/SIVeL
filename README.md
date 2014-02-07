# Diseño de SIVeL 2

## Maqueta

En el directorio rib se han ubicado maquetas para las fichas de captura.  

Estas maqueteas pueden examinarse en línea con RIB: https://01.org/rib/online/
(También puede descargar e instalar RIB en su computador).  
 
Utilice chrome (si tiene RIB local use los parámetros del archivo rib/ic.sh) 
y una vez RIB cargue pulse sobre el botón Hogar y allí pulse sobre Importar, 
e importe cada uno de los diseños del directorio rib (los números
sugieren un orden para abrirlos).  

Haga los cambios que considere, después exportelos como JSON y recodifiquelos
de UTF-8 a LATIN1 (puede ser abriendo con editor como vim que permita 
recodificar o con recode o para el caso de español con el script 
rib/codesp.sh ).


## Prototipo con Ruby on Rails

Las demás carpetas y archivos de estas fuentes son un prototipo desarrollado 
con Ruby on Rails. 


### Requerimientos
* Ruby version >= 1.9
* PostgreSQL >= 9.3
* Se recomienda emplear en paralelo con una instancia de SIVeL 1.2 
* Recomendado sobre adJ 5.4 (que incluye todos los componentes mencionados).  
  Las siguientes instrucciones suponen que opera en este ambiente.

### Configuración de servidor de desarrollo:
* Ubique fuentes por ejemplo en /var/www/htdocs/sivel2/
* Instale gemas requeridas (como Rails 4.1) con:
  sudo bundle20 install
  bundle20 install
* Copie y modifique las plantillas:
  cp config/secrets.yml.plantilla config/secrets.yml
  cp app/views/hogar/_local.html.erb.plantilla app/views/hogar/_local.html.erb
* Configure la misma base de datos de un SIVeL 1.2 en la sección development
  de config/databases.yml y ejecute
  rake db:migrate
  rake db:seed
* En caso de que no tenga un SIVeL 1.2 en paralelo cree el usuario y base
  de datos que configure en config/database.yml e inicialice con:
  rake db:setup
  rake db:migrate
  rake db:seed
* Lance el servidor con
  rails s

### Pruebas:

### Servicios requeridos y prestados:

### Servidor de desarrollo:
  rails s

### Despliegue en sitio de producción con unicorn:
* Siga los mismos 2 primeros pasos para configurar un servidor de desarrollo
* Configure la misma base de datos de un SIVeL 1.2 en sección production
  de config/databases.yml y ejecute
  ```sh
  RAILS_ENV=production rake db:migrate
  RAILS_ENV=production rake db:seed```
* Recomendamos nginx, puede configurar un dominio virtual (digamos
  s2.pasosdeJesus.org) con:

  server {
    listen 443;
    ssl on;
    ssl_certificate /etc/ssl/server.crt;
    ssl_certificate_key /etc/ssl/private/server.key;
    ssl_session_timeout  5m;
    ssl_protocols  SSLv3 TLSv1;
    ssl_ciphers  HIGH:!aNULL:!MD5;
    root /var/www/htdocs/sivel2/;
    server_name s2.pasosdeJesus.org
    error_log logs/s2error.log;

    location ^~ /assets/ {
        gzip_static on;
        expires max;
        add_header Cache-Control public;
        root /var/www/htdocs/sivel2/public/;
    }

    try_files $uri/index.html $uri @unicorn;
    location @unicorn {
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
            proxy_set_header X-Forwarded-Proto $scheme;
            proxy_set_header Host $http_host;
            proxy_redirect off;
            proxy_pass http://unicorn;
            error_page 500 502 503 504 /500.html;
            client_max_body_size 4G;
            keepalive_timeout 10;
    }

  }
* Precompile los recursos 
  ``` rake assets:precompile
* Tras reiniciar nginx, inicie unicorn desde directorio con fuentes con:
  ```sh ./bin/u.sh```
* Puede logarse que inicie en cada arranque por ejemplo creando /etc/rc.d/miapp
service="/var/www/htdocs/sivel2/bin/u.sh"
  ```sh
. /etc/rc.d/rc.subr

rc_cmd $1
```
  E incluyendo miapp en pkg_scripts en /etc/rc.conf.local


### Actualización de servidor de desarrollo

* Actualice fuentes: git pull
* Instale nuevas versiones de gemas requeridas: 
  sudo bundle install
  bundle install
* Aplique cambios a base de datos: rake db:migrate


