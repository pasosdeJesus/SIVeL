#!/bin/sh

# This source is released to the public domain since 2001. No warranty.
# Citation of the source is appreciated.
# http://structio.sourceforge.net/repasa

# The command line management of this script is based on public
# domain code of WWWeb Tide Team 
#    http://www.ebbtide.com/Util/ksh_parse.html 



# Reading configuration variables
if (test ! -f confv.sh) then {
        cp confv.empty confv.sh
} fi;
. ./confv.sh


# Reading functions to help in configuration
. herram/confaux.sh

#Parsing options

BASENAME=$(basename $0)
USAGE="$BASENAME [-v] [-h] [-M] [-p prefijo] [-i]"
# Remember: if you add a switch above, don't forget to add it to: 
#	1) "Parse command line options" section 
#	2) "MANPAGE_HEREDOC" section
#	3) "check for help" section
ARG_COUNT=0 	# This nubmer must reflect true argument count
OPT_FLAG=0 	# Command line mistake flag
OPT_COUNT=0	# Number of options on the command line
MAN_FLAG=0 	# Default: no man pages requited
HELP_FLAG=0	# Default: no help required
VERBOSE_FLAG=0 	# Default: no verbose
WARNING=0 	# General purpose no fail warning flag

# initialize local variables
vbs=""
prefix=""
dirsitiodef="sitios/sivel";
confphpdef="$dirsitiodef/conf.php" 

# Parse command line options
while getopts :p:Mhvi arguments 
do
   # remember treat r: switches with: R_VALE = $OPTARG ;;
   case $arguments in
      p)    prefix=$OPTARG;;
      M)    MAN_FLAG=1 ;;		# Display man page
      v)    # increment the verboseness count and...
	    VERBOSE_FLAG=$(($VERBOSE_FLAG+1))
	    # gather up all "-v" switches
	    vbs="$vbs -v"
	    ;;
      h)    HELP_FLAG=1;;		# display on-line help
      i)    
	    regenera=1;
	    cp confv.empty confv.sh;
            . ./confv.sh;;
      \?)   echo "Opción no reconocida: $OPTARG" >&2	# flag illegal switch 
	    OPT_FLAG=1;;
   esac
done
OPT_COUNT=$(($OPTIND-1))
shift $OPT_COUNT

options_help="
   -p prefijo	Prefijo de la ruta de instalación (por defecto /usr/local)
   -h           Presenta ayuda corta
   -M           Presenta ayuda más completa
   -v           Presenta información de depuración durante ejecución
   -i           Elimina configuración anterior e inicializa valores en particular si no hay un sitio configurado configura el sitio sivel intentando copiar de configuración anterior si la hay"
 
# check for man page request
if (test "$MAN_FLAG" = "1" ) then {
	if (test "$PAGER" = "" ) then {
		if ( test "$VERBOSE_FLAG" -gt "0" ) then {
			echo "$BASENAME: Resetting PAGER variable to more" >&2

	       	} fi;
	       	export PAGER=more;
	} fi;
	$PAGER << MANPAGE_HEREDOC

NOMBRE

	$BASENAME - Configura fuentes de $PROYECTO

	$USAGE


DESCRIPCIÓN

	Establece el valor de las variables de configuración y genera
	archivos en diversos formatos empleados por las fuentes de 
	$PROYECTO.
	* $PRY_DESC
	* $URLSITE

	Las variables de configuración y sus valores por defecto están
	en confv.empty (debajo de cada variable hay un comentario con la 
	descripción).
	Este script genera los archivos
		confv.sh, confv.php, $confphpdef y Make.inc
	con las variables de configuración instanciadas.  
	Para la instanciación este script puede:

	* Verificar el valor de algunas variables (por ejemplo que
	  la versión de un programa sea la requerida).
	* Buscar valor para algunas variables (por ejemplo ubicación
	  de algún programa).
	* Completar el valor de algunas variables (por ejemplo fecha actual)
	* Dejar el valor por defecto configurado en confv.empty (por ejemplo 
	  nombre del proyecto y versión).
	* Pedir información al usuario en los casos que no logra instanciar


OPCIONES

$options_help


EJEMPLOS

	./conf.sh
	Configura fuentes y deja como prefijo para la ruta de instalación 
	"/usr/local"

	./conf.sh -p /usr/
	Configura fuentes y deja como prefijo para la ruta de instalación
	"/usr"


ESTÁNDARES
	Este script pretende ser portable. Debe cumplir POSIX.


FALLAS


VER TAMBIÉN
	Para mejorar este script o hacer uno similar ver fuentes de 
	herram/confaux.sh


CRÉDITOS Y DERECHOS DE REPRODUCCIÓN 

 	Script de dominio público.  Sin garantías.
	Fuentes disponibles en: http://structio.sourceforge.net/repasa
	Puede enviar reportes de problemas a 
		structio-info@lists.sourceforge.net

	Incluye porciones de código dominio público escritas por:
	  Miembros de Structio http://structio.sourceforge.net
	  WWWeb Tide Team http://www.ebbtide.com/Util/
	Puede ver más detalles sobre los derechos y créditos de este script en
	las fuentes.
MANPAGE_HEREDOC
   exit 0;
} fi;

# check for help
if (test "$HELP_FLAG" = "1" ) then {
   echo " Utilización: $USAGE"
   cat << HLP_OP
$options_help
HLP_OP
   exit 0
} fi;

# check for illegal switches
if (test "$OPT_FLAG" = "1") then {
   echo "$BASENAME: Se encontró alguna opción invalida" >&2
   echo "Utilización: $USAGE" >&2
   exit 1
}
elif (test "$#" != "$ARG_COUNT" ) then {
   echo "$BASENAME: se encontraron $# argumentos, pero se esperaban $ARG_COUNT." >&2
   echo "Utilización: $USAGE" >&2
   exit 1;
} fi;


echo "Configurando $PROYECTO $PRY_VERSION";

if (test "$VERBOSE_FLAG" -gt "0") then {
	echo "Chequeando y detectando valor de variables de configuración";
} fi;


check "AWK" "" "test -x \$AWK" `which awk 2> /dev/null`
check "CP" "" "test -x \$CP" `which cp 2> /dev/null`
check "CVS" "optional" "test -x \$CVS" `which cvs 2> /dev/null`
check "ECHO" "" "test -x \$ECHO" `which ed 2> /dev/null`
check "ED" "" "test -x \$ED" `which ed 2> /dev/null`
check "FIND" "" "test -x \$FIND" `which find 2> /dev/null`
check "GZIP" "" "test -x \$GZIP" `which gzip 2> /dev/null`
check "MAKE" "" "test -x \$MAKE" `which make 2> /dev/null`
check "MV" "" "test -x \$MV" `which mv 2> /dev/null`
check "MKDIR" "" "test -x \$MKDIR" `which mkdir 2> /dev/null`
check "PERL" "optional" "test -x \$PERL" `which perl 2> /dev/null`
check "RM" "" "test -x \$RM" `which rm 2> /dev/null`
check "SCP" "" "test -x \$SCP" `which scp 2> /dev/null`
check "SED" "" "test -x \$SED" `which sed 2> /dev/null`
check "TAR" "" "test -x \$TAR" `which tar 2> /dev/null`
check "TOUCH" "" "test -x \$TOUCH" `which touch 2> /dev/null`
check "W3M" "optional" "test -x \$W3M" `which w3m 2> /dev/null` `which lynx 2> /dev/null`
l=`echo $W3M | sed -e "s|.*lynx.*|si|g"`
W3M_OPT="";
if (test "$l" = "si") then {
	W3M_OPT="-nolist";
} fi;
changeVar W3M_OPT 1;
check "ZIP" "optional" "test -x \$ZIP" `which zip 2> /dev/null`

if (test "$ACT_PROC" = "act-ncftpput") then {
	check "NCFTPPUT" "" "test -x \$NCFTPPUT" `which ncftpput 2> /dev/null`
}
elif (test "$ACT_PROC" = "act-scp") then {
	check "SCP" "" "test -x \$SCP" `which scp 2> /dev/null`
} fi;

function proceso {
	p=$1;
	n=$2;
	pgrep $p > /dev/null
	if (test "$?" != "0") then { 
		echo "$n debería estar corriendo, continua (s/n)";
		read sn
		if (test "$sn" = "n") then {
			exit 1;
		} fi;
	} fi;
}

function estapear {
	p=$1;
	r=$2;
	v=$3;
	pear shell-test $p $r $v
	if (test "$?" != "0") then {
		echo "De PEAR falta $p $r $v (ENTER para continuar)";
		read;
	} fi;
} 

echo " nginx corriendo";
proceso nginx Nginx
c=`ps ax | grep "[h]ttpd:.*parent.*chroot" | sed -e "s/.*chroot //g;s/].*//g"`
check "CHROOTDIR" "optional" "test -d \$CHROOTDIR" $c '/var/www/' 
echo " PostgreSQL corriendo";
proceso post Postgresql 
check "SOCKPSQL" "" "test -S \$SOCKPSQL/.s.PGSQL.5432" '/var/www/tmp/' '/tmp/' '/var/run/postgresql/'

check "PHP" "" "test -x \$PHP" `which php-5.4 2> /dev/null` `which php-5.3 2> /dev/null` `which php-5.2 2> /dev/null` `which php 2> /dev/null` 
verphp=`$PHP -v | grep "PHP " | sed -e "s|.*PHP \([0-9.]*\).*|\1|g"`;
if (test "$?" != 0 -o "x$verphp" = "x") then {
	echo "  $PROYECTO funciona con versiones de PHP posteriores a la 5.0";
} fi;
check "PHPDOC" "optional" "test -x phpdoc" `which phpdoc 2> /dev/null`
check "PEAR" "" "test -x \$PEAR" `which pear 2> /dev/null`
echo " Paquetes de PEAR";
estapear Auth
estapear HTML_Common
estapear HTML_Menu
estapear HTML_QuickForm
estapear HTML_QuickForm_Controller
#estapear HTML_Table
estapear HTML_Javascript 
estapear HTML_CSS
estapear Date
estapear DB_DataObject
estapear Mail
estapear Mail_Mime
estapear Net_SMTP
estapear Net_Socket
estapear Validate
estapear DB_DataObject_FormBuilder

echo -n " ";
echo "<?php

require_once \"HTML/QuickForm/date.php\";

\$d = new HTML_QuickForm_Date();

if (\$d->_options['maxYear'] < date('Y')) {
	        echo \"HTML/QuickForm/date no soporta años superiores a 2010\n\";
		        exit(1);
		}

echo \"HTML/QuickForm/date si soporta años superiores a 2010\n\";
exit(0);
?>" > /tmp/rfecha.php

$PHP /tmp/rfecha.php
if (test "$?" != "0") then {
	echo "Aplique solución descrita en http://pear.php.net/bugs/bug.php?id=18171&edit=12&patch=date&revision=1295481833";
	exit 1;
} fi;
rm /tmp/rfecha.php

check "ISPELL" "optional" "test -x \$CHROOTDIR/\$ISPELL" "/usr/local/bin/ispell" `which ispell 2> /dev/null`
touch $DICCIONARIO

check "OPENSSL" "optional" "test -x \$CHROOTDIR/\$OPENSSL" "/usr/sbin/openssl" `which openssl 2> /dev/null`
check "ECHO" "optional" "test -x \$CHROOTDIR/\$ECHO" "/usr/sbin/echo" `which echo 2> /dev/null`

FECHA_ACT=`date "+%d/%m/%Y"`;
changeVar FECHA_ACT 1;
m=`date "+%m" | sed -e "s/01/Enero/;s/02/Febrero/;s/03/Marzo/;s/04/Abril/;s/05/Mayo/;s/06/Junio/;s/07/Julio/;s/08/Agosto/;s/09/Septiembre/;s/10/Octubre/;s/11/Noviembre/;s/12/Diciembre/"`
a=`date "+%Y"`
MES_ACT="$m de $a";
changeVar MES_ACT 1;


if (test "$VERBOSE_FLAG" -gt "0") then {
	echo "Guardando variables de configuración"
} fi;
changeConfv;

if (test "$VERBOSE_FLAG" -gt "0") then {
	echo "Generando Make.inc";
} fi;

echo "# Some variables for Makefile" > Make.inc;
echo "# This file is generated automatically by conf.sh.  Don't edit" >> Make.inc;
echo "" >> Make.inc

if (test "$VERBOSE_FLAG" -gt "1") then {
	echo "Adding configuration variables to Make.inc";
} fi;
addMakeConfv Make.inc;

echo "<?php " > confv.php
echo "// @codingStandardsIgnoreFile" >> confv.php
echo "// Acceso: SÓLO DEFINICIONES" >> confv.php
addPHPConfv confv.php
echo "?>" >> confv.php

echo "Preparando archivos de configuración";

if (test "$VERBOSE_FLAG" -gt "0") then {
	echo "Modificando $confphp"
} fi;

if (test "$regenera" = "1") then {

	csitios=`cd sitios/ ; ls | grep -v CVS | grep -v nuevo.sh | tr "\n" " "`;
	if (test "$csitios" = "pordefecto pruebas ") then {

		chres=`echo $CHROOTDIR | sed -e "s/\//\\\\\\\\\//g"`;
		ds=`echo $SOCKPSQL | sed -e "s/.s.PGSQL.*//g;"`;
		dssed=`echo $ds | sed -e "s/\//\\\\\\\\\//g"`;
		dschrootsed=`echo $ds | sed -e "s/$chres//g" | sed -e "s/\//\\\\\\\\\//g"`;
		fuentes=`pwd | sed -e "s/\/pruebas//g"`;
		fuentessed=`echo $fuentes | sed -e "s/\//\\\\\\\\\//g"`;
		fuenteschrootsed=`echo $fuentes | sed -e "s/$chres//g" | sed -e "s/\//\\\\\\\\\//g"`;


		if (test ! -f /var/www/htdocs/sivel10/aut/conf.php) then {
			# nuevo
			cp -rf sitios/pordefecto sitios/sivel
			dbnombre=sivel
			dbusuario=sivel
			grep ":sivel:" $HOME/.pgpass > /dev/null 2>&1
			if (test "$?" = "0") then {
				dbclave=`grep :sivel: $HOME/.pgpass | sed -e "s/.*:\([^:]*\)$/\1/g"`;
			} else {
				dbclave=`apg | head -n 1`;
			} fi;

		} else { #  aut/conf.php existe
			# de 1.0 a 1.1
			cp -rf sitios/pordefecto sitios/sivel
			odbnombre=`grep "\\\$dbnombre *=" /var/www/htdocs/sivel10/aut/conf.php 2> /dev/null | sed -e 's/.*=.*"\([^"]*\)".*$/\1/g' 2> /dev/null`;
			if (test "$odbnombre" = "sivel") then {
				echo "No se esperaba base sivel en copia disponible en /var/www/htdocs/sivel10/";
				exit 1;
			} fi;
			dbnombre=sivel
			dbusuario=`grep "\\\$dbusuario *=" /var/www/htdocs/sivel10/aut/conf.php | sed -e 's/.*=.*"\([^"]*\)".*$/\1/g' 2> /dev/null`;
			dbclave=`grep "\\\$dbclave *=" /var/www/htdocs/sivel10/aut/conf.php | sed -e 's/.*=.*"\([^"]*\)".*$/\1/g' 2> /dev/null`;
		} fi;

		if (test -f sitios/sivel/conf.php) then {
			cp -f sitios/sivel/conf.php sitios/sivel/conf.php.copia
		} fi;
		sed -e "s/^ *\$dbservidor=.*/\$dbservidor=\"unix($dschrootsed)\";/g" sitios/pordefecto/conf.php.plantilla |
		sed -e "s/^ *\$dbnombre *=.*/\$dbnombre = \"$dbnombre\";/g"  |
		sed -e "s/^ *\$dbusuario *=.*/\$dbusuario = \"$dbusuario\";/g"  |
		sed -e "s/^ *\$dbclave *=.*/\$dbclave = \"$dbclave\";/g"  |
		sed -e "s/^ *\$dirchroot *=.*/\$dirchroot = \"$chres\";/g"  |
		sed -e "s/^ *\$dirserv *=.*/\$dirserv = \"$fuenteschrootsed\";/g"  |
		sed -e "s/^ *\$dirsitio *=.*/\$dirsitio = \"sitios\/sivel\";/g"  |
		sed -e "s/sitios\/pordefecto/sitios\/sivel/g"  |
		sed -e "s/^ *\$socketopt *=.*/\$socketopt = \"-h $dssed\";/g"  > sitios/sivel/conf.php
		chmod o-rwx sitios/sivel/conf.php
		sudo chgrp www sitios/sivel/conf.php
		chmod g-wx+r sitios/sivel/conf.php

		if (test -f sitios/sivel/vardb.sh) then {
			cp -f sitios/sivel/vardb.sh sitios/sivel/vardb.sh.copia
		} fi;
		sed -e "s/^ *dirap=.*/dirap=$fuentessed\/sitios\/sivel/g" sitios/pordefecto/vardb.sh.plantilla > sitios/sivel/vardb.sh

		if (test ! -f sitios/sivel/ultimoenvio.txt) then {
			touch sitios/sivel/ultimoenvio.txt
		} fi;
		sudo chown -f www:www sitios/sivel/ultimoenvio.txt
		(cd sitios/sivel; ../../bin/creaesquema.sh)
		sudo chown -f www:www sitios/sivel/DataObjects/sivel.*
		(cd sitios; ln -s sivel 127.0.0.1)
		sudo mkdir -p /var/www/resbase/anexos
		sudo chown -f www:www /var/www/resbase/anexos

	} fi;
} fi;


echo "Configuración completada";
