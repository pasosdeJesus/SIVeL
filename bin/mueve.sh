#!/bin/sh
# Mueve SIVeL a otro URL y otra base de datos
# Dominio público. 2010. Sin garantías. vtamara@pasosdeJesus.org

dirdest=$1;
nuevonombd=$2;
if (test "$dirdest" = "") then {
	dirdest="/var/www/htdocs/sivel";
} fi;
if (test "$nuevonombd" = "") then {
	nuevonombd="sivel";
} fi;


dirac=`pwd`;

dpl=`stat -f "%Su" ../../confv.php`
if (test "$USER" != "$dpl") then {
	echo "*** El usuario que ejecuta debería ser el dueño de las fuentes de $dirac ($dpl) y tener permiso para ejecutar doas";
	exit 1;
} fi;

if (test ! -f vardb.sh -o ! -f ../../confv.php) then {
	echo "*** Ejecute desde el directorio del sitio para SIVeL 1.1";
	exit 1;
} fi;

gdpl=`stat -f "%Sg" ../../confv.php`

. ./vardb.sh

echo "Respaldando base en $rlocal/movido-$nommes.gz";
../../bin/pgdump.sh
if (test "$?" != "0") then {
	echo "*** Volcado de la base $dbnombre no pudo realizarse";
	exit 1;
} fi;
doas cp $rlocal/$nommes.gz $rlocal/movido-$nommes.gz

echo "Respaldando fuentes en $rlocal/fuentes11-$dbnombre.tar.gz";
(cd ../..; tar cfz $rlocal/fuentes11-$dbnombre.tar.gz .)

echo "¿Seguro que desea mover las fuentes de SIVeL 1.1 del directorio $dirac/../.. al directorio $dirdest y copiar los datos de la base $dbnombre del usuario $dbusuario a la base $nuevonombd del mismo usuario? (Cancele con Control-C o continúe con ENTER)";
read

doas mkdir -p $dirdest 2> /dev/null
if (test "$?" != "0") then {
	echo "*** No pudo crearse directorio destino $dirdest";
	exit 1;
} fi;
doas chown $dpl:$gdpl $dirdest

cmd="(cd $dirdest; tar xvfz $rlocal/fuentes11-$dbnombre.tar.gz)";
eval $cmd;
if (test "$?" != "0") then {
	echo $cmd;
	echo "*** No pudo sacarse copia del directorio actual a $dirdest";
	exit 1;
} fi;

schlac=`echo $dirac | sed -e "s/\/var\/www//g"`
schlace=`echo $schlac | sed -e "s/\//\\\\\\\\\//g"`
schln=`echo $dirdest | sed -e "s/\/var\/www//g"`
schlne=`echo $schln | sed -e "s/\//\\\\\\\\\//g"`
dirace=`echo $dirac | sed -e "s/\//\\\\\\\\\//g"`
dirdeste=`echo $dirdest/sitios/$nuevonombd | sed -e "s/\//\\\\\\\\\//g"`


mkdir $dirdest/sitios/$nuevonombd/

# Cambiar nombre base
e=0;
cmd="cp conf.php $dirdest/sitios/$nuevomonbd/conf.php-antesmueve";
echo "$cmd";
eval "$cmd";
e=`expr $e + $?`
cp vardb.sh $dirdest/sitios/$nuevonombd/vardb.sh-antesmueve
e=`expr $e + $?`

dirserv=`echo $dirdest | sed -e "s/\/var\/www\///g"`;
sed -e "s/dbnombre *=.*;/dbnombre=\"$nuevonombd\";/g;s/dirserv=.*/dirserv=\"$schlne\";/g;s/dirsitio=.*/dirsitio=\"sitios\/$nuevonombd\";/g" conf.php > $dirdest/sitios/$nuevonombd/conf.php
e=`expr $e + $?`
sed -e "s/dirap=.*/dirap=\"$dirdeste\"/g" vardb.sh > $dirdest/sitios/$nuevonombd/vardb.sh
e=`expr $e + $?`
echo "Revisar $dirdest/sitios/$nuevonombd/conf.php y $dirdest/sitios/$nuevonombd/vardb.sh"
if (test "$e" != "0") then {
	echo "No pudieron modificarse archivos";
	exit 1;
} fi;

#echo "Revisar conf.php otra vez";
#exit 1;
cd $dirdest/

./conf.sh -i

cd sitios

if (test -h 127.0.0.1) then {
	doas rm 127.0.0.1
} fi;

ln -s $nuevonombd/ 127.0.0.1

cd $nuevonombd
# Crear base 
SIN_ESQUEMA=1 ../../bin/creapg.sh
../../bin/restaura.sh $rlocal/movido-$nommes.gz

# Cambiar URL
doas cp /var/www/conf/httpd.conf /var/www/conf/httpd.conf-antesmueve
ba=`dirname $diract`;
ba=`dirname $ba`;
grep "DocumentRoot $ba" /var/www/conf/httpd.conf 
r=$?;
if (test "$r" != "0") then {
	grep "DocumentRoot .*.var.www.users.sivel.*" /var/www/conf/httpd.conf 
	r=$?;
} fi;

if (test "$r" = "0") then {
	doas chmod +w /var/www/conf/httpd.conf
	doas cp /var/www/conf/httpd.conf /var/www/conf/httpd.conf-antesmueve
	doas rm -rf /tmp/httpd.conf
	doas sed -e "s/DocumentRoot .*$ba.*/DocumentRoot \"$schlne\"/g;s/DocumentRoot .*var.www.users.sivel.*/DocumentRoot \"$schlne\"/g" /var/www/conf/httpd.conf > /tmp/httpd.conf
	doas cp /tmp/httpd.conf /var/www/conf/httpd.conf
	doas chmod -w /var/www/conf/httpd.conf
} fi;


# Comprobar que funciona
grep "^ *FORCE_SSL_PROMPT:yes" /etc/lynx.cfg > /dev/null 2>&1
if (test "$?" != "0") then {
	doas cp /etc/lynx.cfg /etc/lynx.cfg-antesmueve
	doas sed -e "s/#* *FORCE_SSL_PROMPT.*/FORCE_SSL_PROMPT:yes/g" /etc/lynx.cfg-antesmueve > /tmp/lynx.cfg-mueve
	doas cp /tmp/lynx.cfg-mueve /etc/lynx.cfg
} fi;
lynx -dump https://127.0.0.1/ > /tmp/dump-mueve
grep "Autenticación" /tmp/dump-mueve > /dev/null 2>&1
if (test "$?" != "0") then {
	echo "La comprobación de funcionamiento del nuevo URL falló";
	exit 1;
} fi;

echo "Con un navegador verifique sus datos en https://127.0.0.1/";
echo "Si desea borrar las fuentes de su localización inicial presione la letra 's'";
read sn
if (test "$sn" = "s") then {
	# Eliminar fuentes anteriores
	doas rm -rf $dirac
} fi;


