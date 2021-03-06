#!/bin/sh
# Pruebas de regresión.
# Dominio público. 2007. vtamara@pasosdeJesus.org

. ./confv.sh

function die {
	echo $1;
	exit 1;
}

# Genera DataObject.ini con el nivel de depuración especificado en primer 
# parámetro
function genDataObject {
	depura=$1;
#	sed -e "s/\(database *=.*(\).*).*\"/\1$ds)sivel-prueba\"/g;s/schema_location *=.*/schema_location=$fuentes/g;s/class_location *=.*/class_location=$fuentes/g;s/debug *=.*/debug=$depura/g" ../DataObject.ini > DataObject.ini
}


function prueba {
	a=$1;          # Archivo PHP
	d=$2;          # Descripción
	sal=$3;        # Archivo de salida
	saca="$4";     # Que lineas sacar del resultado antes de comparar
	sintab="$5";   # 
	otrocomp="$6"  # Otro archivo por comparar entre salida y esperado
	saca2="$7"      # Lineas por sacar de otrocomp
	saca3="$8"      # Mas lineas por sacar de otrocomp
	saca4="$9"      # Mas lineas por sacar del resultado principal
	shift
	saca5="$9"     # Mas lineas por sacar de otrocomp

	echo -n "$a $d : ";
	echo -n "$a $d : " >> sitios/pruebas/pruebas.bitacora;
	if (test "$sal" != "") then {
	    echo -n " sal=$sal" >> sitios/pruebas/pruebas.bitacora;
		genDataObject 0;
		mkdir -p sitios/pruebas/salida
		$PHP -n $a | grep -v evita_csrf | grep -v Warning > sitios/pruebas/salida/$sal.tmp 2>&1
		if (test "$saca" != "") then {
			grep -v Warning sitios/pruebas/salida/$sal.tmp | grep -v "$saca" > sitios/pruebas/salida/$sal.espreg
			if (test "$saca4" != "") then {
				cp sitios/pruebas/salida/$sal.espreg sitios/pruebas/salida/$sal.tmp2
				grep -v "$saca4" sitios/pruebas/salida/$sal.tmp2 > sitios/pruebas/salida/$sal.espreg
			} fi;
		} else {
			cp sitios/pruebas/salida/$sal.tmp sitios/pruebas/salida/$sal.espreg
		} fi;
		if (test "$sintab" != "") then {
			cp sitios/pruebas/salida/$sal.espreg sitios/pruebas/salida/$sal.tmp2
			tr -d "\t" < sitios/pruebas/salida/$sal.tmp2 > sitios/pruebas/salida/$sal.espreg
		} fi;
		diff -b sitios/pruebas/salida/$sal.espreg sitios/pruebas/esperado/$sal.espreg > /dev/null
	} else {
		genDataObject 5;
		$PHP -n $a >> sitios/pruebas/pruebas.bitacora 2>&1
	} fi;
	if (test "$?" != "0") then {
		echo " Falló";
	} else {
		echo " OK";
	} fi;
	if (test "$otrocomp" != "") then {
		if (test "$saca2" != "") then {
			f=`date "+%Y-%m-%d"`
			sed -e "s/$f//g" sitios/pruebas/salida/$otrocomp > sitios/pruebas/salida/$otrocomp.tmp
			grep -v "$saca2" sitios/pruebas/salida/$otrocomp.tmp | grep -v "Warning" > sitios/pruebas/salida/$otrocomp
			if (test "$saca3" != "") then {
				cp sitios/pruebas/salida/$otrocomp sitios/pruebas/salida/$otrocomp.tmp2
				grep -v "$saca3" sitios/pruebas/salida/$otrocomp.tmp2 > sitios/pruebas/salida/$otrocomp
				if (test "$saca5" != "") then {
					cp sitios/pruebas/salida/$otrocomp sitios/pruebas/salida/$otrocomp.tmp3
					grep -v "$saca5" sitios/pruebas/salida/$otrocomp.tmp3 > sitios/pruebas/salida/$otrocomp
				} fi;
			} fi;
		}  fi;
		diff -b sitios/pruebas/salida/$otrocomp sitios/pruebas/esperado/$otrocomp > /dev/null
		if (test "$?" != "0") then {
			echo " 2-Falló ";
		} else {
		echo " 2-OK";
		} fi;
	} fi;
}

echo "Pruebas de regresión"

if (test ! -f sitios/pruebas/pruebas.sh) then {
	echo "Ejecute desde el directorio con las fuentes de SIVeL";
} fi;

if (test ! -f confv.sh) then {
	die "Configure primero las fuentes";
} fi;

echo "Configurando"
. ./confv.sh

if (test "$SALTAINI" != "1") then {
	ds=`echo $SOCKPSQL | sed -e "s/.s.PGSQL.*//g" | sed -e "s/\//\\\\\\\\\//g"`;
	fuentes=`pwd | sed -e "s/\/pruebas//g" | sed -e "s/\//\\\\\\\\\//g"`;

	csitios=`cd sitios ; ls | grep -v CVS | grep -v nuevo.sh | grep -v pordefecto | grep -v pruebas`;
	if (test "$csitios" = "") then {
		die "Primero debe configurar y usar algún sitio";
	} fi;
	dirplant="";
	for i in $csitios; do 
		if (test "$dirplant" = "" -a -f "sitios/$i/conf.php") then {
			dirplant="sitios/$i";
		} fi;
	done
	echo "Copiando datos de usuario de $dirplant";

	dbusuario="";
	if (test -f "$dirplant/conf-local.php") then {
		dbusuario=`grep "\\\$dbusuario *=" $dirplant/conf-local.php | sed -e 's/.*=.*"\([^"]*\)".*$/\1/g' 2> /dev/null`;
	} fi;
	if (test "$dbusuario" = "") then {
		dbusuario=`grep "\\\$dbusuario *=" $dirplant/conf.php | sed -e 's/.*=.*"\([^"]*\)".*$/\1/g' 2> /dev/null`;
	} fi;
	dbclave="";
	if (test -f "$dirplant/conf-local.php") then {
		dbclave=`grep "\\\$dbclave *=" $dirplant/conf-local.php | sed -e 's/.*=.*"\([^"]*\)".*$/\1/g' 2> /dev/null`;
	} fi;
	if (test "$dbclave" = "") then {
		dbclave=`grep "\\\$dbclave *=" $dirplant/conf.php | sed -e 's/.*=.*"\([^"]*\)".*$/\1/g' 2> /dev/null`;
	} fi;
	nombase="";
	if (test -f "$dirplant/conf-local.php") then {
		nombase=`grep "\\\$dbnombre *=" $dirplant/conf-local.php | sed -e 's/.*=.*"\([^"]*\)".*$/\1/g' 2> /dev/null`;
	} fi;
	if (test "$nombase" = "") then {
		nombase=`grep "\\\$dbnombre *=" $dirplant/conf.php | sed -e 's/.*=.*"\([^"]*\)".*$/\1/g' 2> /dev/null`;
	} fi;
	chres=`echo $CHROOTDIR | sed -e "s/\//\\\\\\\\\//g"`;
	ds=`echo $SOCKPSQL | sed -e "s/.s.PGSQL.*//g;"`;
	dssed=`echo $ds | sed -e "s/\//\\\\\\\\\//g"`;
	dschrootsed=`echo $ds | sed -e "s/$chres//g" | sed -e "s/\//\\\\\\\\\//g"`;
	fuentes=`pwd | sed -e "s/\/pruebas//g"`;
	fuentessed=`echo $fuentes | sed -e "s/\//\\\\\\\\\//g"`;
	fuenteschrootsed=`echo $fuentes | sed -e "s/$chres//g" | sed -e "s/\//\\\\\\\\\//g"`;


	( cat sitios/pordefecto/conf.php.plantilla; ) | 
	sed -e "s/^ *\$dbservidor *=.*/\$dbservidor = \"unix($chres$dschrootsed)\";/g" |
	sed -e "s/^ *\$dbusuario *=.*/\$dbusuario = \"$dbusuario\";/g"  |
	sed -e "s/^ *\$dbclave *=.*/\$dbclave = \"$dbclave\";/g"  |
	sed -e "s/^ *\$dbnombre *=.*/\$dbnombre = \"sivelpruebas\";/g"  |
	sed -e "s/^ *\$dirserv *=.*/\$dirserv = \"$chres$fuenteschrootsed\";/g"  |
	sed -e "s/^ *\$dirsitio *=.*/\$dirsitio = \"sitios\/pruebas\";/g"  |
	sed -e "s/^ *\$socketopt *=.*/\$socketopt = \"-h $dssed\";/g"  > sitios/pruebas/conf.php
	ed sitios/pruebas/conf.php >/dev/null 2>&1 <<EOF
/inibdmod.php
i
\$dbservidor="unix($SOCKPSQL)";
\$dirchroot="";
\$GLOBALS['DB_Debug'] = 0;
\$GLOBALS['dir_anexos'] = "sitios/pruebas/esperado";
.
w
q
EOF
	chmod o-rwx sitios/pruebas/conf.php
	doas chgrp www sitios/pruebas/conf.php
	chmod g-wx+r sitios/pruebas/conf.php

	sed -e "s/^ *dirap=.*/dirap=\"$fuentessed\/sitios\/pruebas\"/g" sitios/pordefecto/vardb.sh.plantilla > sitios/pruebas/vardb.sh

	cp sitios/pordefecto/conf_int.php.plantilla sitios/pruebas/conf_int.php

	mkdir -p sitios/pruebas/DataObjects
#	cp $dirplant/DataObjects/$nombase.ini sitios/pruebas/sivelpruebas.ini
#	cp $dirplant/DataObjects/$nombase.links.ini sitios/pruebas/sivelpruebaslinks.ini
	#cp confv.php sitios/pruebas

	(cd sitios/pruebas ; ../../bin/creaesquema.sh)

	echo "Configuración completada";

	cd sitios/pruebas ; . ./vardb.sh ; cd ../..


	echo "Se empleará una base nueva de nombre $dbnombre del usuario $dbusuario "
	echo -n "(se borrará, no la utilice!) ";
	echo "[Enter] para comenzar";
	date > sitios/pruebas/pruebas.bitacora
	(cd sitios/pruebas ; RUTASQL=../../ SIN_DATOS=1 ../../bin/creapg.sh >> pruebas.bitacora 2>&1)
	if (test "$?" != "0") then {
		die "Falló creación de la base";
	} fi;
	RUTASQL=./
	if (test -f ${RUTASQL}datos-us.sql) then {
		cmd="psql $socketopt -U $dbusuario -d $dbnombre -f ${RUTASQL}datos-us.sql";
		echo $cmd;
		eval $cmd;
	} fi;
	cmd="psql $socketopt -U $dbusuario -d $dbnombre -c \"SET client_encoding to 'LATIN1'; INSERT INTO usuario(id, nusuario, password, nombre, descripcion, rol, idioma, fechacreacion) VALUES (100, 'sivelpruebas', 'c2b96950b73332b8386406b6bee5f5db73a2bb7d', '', '', '1', 'es_CO', '2001-01-01');\"";
	echo $cmd;
	echo "Por evaluar";
	eval $cmd;
	echo "Evaluado: $?";
} fi;


$PHP -n -r 'phpinfo();' | grep -v "^[^ a-z]* =>" >> sitios/pruebas/pruebas.bitacora 2>&1

#function x {
prueba sitios/pruebas/autentica.php "Autenticación"
prueba sitios/pruebas/pactualiza.php "Actualiza"
prueba sitios/pruebas/insdep.php "Inserta departamento"
prueba sitios/pruebas/actdep.php "Actualiza departamento"
prueba sitios/pruebas/insbasicas.php "Inserta en tablas básicas"
prueba sitios/pruebas/insusu.php "Inserta usuario"
prueba sitios/pruebas/inscaso-basico.php "Inserta un caso basico"
prueba sitios/pruebas/inscaso-basico-valida.php "Verifica inserción caso"
prueba sitios/pruebas/inscaso-ubicacion.php " - Ubicacion"
prueba sitios/pruebas/inscaso-frecuentes.php " - Fuentes frecuentes"
prueba sitios/pruebas/inscaso-otras.php " - Otras fuentes"
prueba sitios/pruebas/inscaso-contexto.php " - Contexto"
prueba sitios/pruebas/inscaso-contexto-valida.php " - Valida Contexto"
prueba sitios/pruebas/inscaso-presponsable.php " - Presunto Responsable"
prueba sitios/pruebas/inscaso-victima.php " - Víctima"
prueba sitios/pruebas/inscaso-victimaColectiva.php " - Víctima Colectiva"
prueba sitios/pruebas/inscaso-acto.php " - Actos"
prueba sitios/pruebas/inscaso-memo.php " - Memo"
prueba sitios/pruebas/inscaso-memo-valida.php " - Valida Memo"
prueba sitios/pruebas/inscaso-anexos.php " - Anexo"
prueba sitios/pruebas/inscaso-etiqueta.php " - Etiqueta"
prueba sitios/pruebas/inscaso-evaluacion.php " - Evaluacion"
prueba sitios/pruebas/inscaso-evaluacion-valida.php " - Valida Evaluacion"
prueba sitios/pruebas/inscaso-valrepgen.php " - Validar y Reporte General" valrepgen "sivelpruebas *[0-9]*-[A-Za-z]*-[0-9]*"
#} # x
prueba sitios/pruebas/reprevista.php " - Reporte Revista" reprevista
prueba sitios/pruebas/reprevista-filtros.php " - Filtros en Reporte Revista" reprevista-filtros "Warning"
prueba sitios/pruebas/repconsolidado.php " - Reporte Consolidado" repconsolidado
prueba sitios/pruebas/estadisticas.php " - Estadísticas " estadisticas
prueba sitios/pruebas/novalida-basicos.php " - Validación básicos" novalida-basicos
prueba sitios/pruebas/novalida-frecuentes.php " - Validación frecuentes" novalida-frecuentes "<option"
prueba sitios/pruebas/externa.php " - Consulta externa" externa
prueba sitios/pruebas/relato.php " - Exporta Relato " relato 
prueba sitios/pruebas/imprelato.php " - Importa Relato " imprelato "sivelpruebas *[0-9]*-[A-Za-z]*-[0-9]*" "" "resimp.xrlt.espreg" "Warning" "fecha_fuente" "D -" "IMPORTA"
#prueba sitios/pruebas/mezcla.php " - Mezcla 2 Casos" mezcla
