#!/bin/sh
# Aplica a todas las fuentes PHP las transformaciones
# de herram/trans.awk y herram/trans.sed
# Dominio p�blico. 2009. vtamara@pasosdeJesus.org

. ./confv.sh

pa=$1;

# Verifica est�ndares en un archivo
function aplica {
	i=$1;
	if (test $i = "") then {
		echo "Falta nombre de archivo por verificar";
		exit 1;
	} fi;
    if (test "${NOVERIFICA}" != "1") then {
	    echo "Verificando $i";
        $PHP -l $i
        if (test "$?" != "0") then {
            echo "Hay errores de sintaxis en $i";
            exit 1;
        } fi;
    } fi;
	echo "Transformando $i";
	awk -f herram/trans.awk $i | \
	awk -f herram/trans2.awk | \
 	sed -f herram/trans.sed > /tmp/sp/$i.tawk
	#$PHP herram/ind.php /tmp/sp/$i.tawk > /tmp/sp/$i
	cp /tmp/sp/$i.tawk /tmp/sp/$i
    if (test "${NOVERIFICA}" != "1") then {
        $PHP -l /tmp/sp/$i
        if (test "$?" != "0") then {
            echo "Hay errores de sintaxis en transformado /tmp/sp/$i";
            exit 1;
        } fi;
    } fi;
	diff -a $i /tmp/sp/$i;
	if (test "$?" = "1") then {
		echo -n "Remplazar (s/n)? ";
		if (test "$sn" != "S") then {
			read sn
		} fi;
		if (test "$sn" = "s" -o "$sn" = "S") then {
			cp /tmp/sp/$i $i;
		} fi;
	} fi;
}

l=`find . -type d | grep -v CVS`;
for i in $l; do
	mkdir -p /tmp/sp/$i
done;

sn="";
if (test "$pa" != "") then {
	aplica "$pa";
} else {
	for i in `find . -name "*php"`; do
		aplica $i;
	done
} fi;

