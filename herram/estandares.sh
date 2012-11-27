#!/bin/sh
# Chequea estándares de programación en PHPs del directorio en el que está
# Dominio público. 2009. vtamara@pasosdeJesus.org

i=$1;
if (test "$i" = "") then {
	phpcs --standard=herram/estandares.xml . > /tmp/e
	less /tmp/e
	#| grep -v "space.* but" | grep -v "Equals sign not aligned" | grep -v "Invalid version" | grep -v "in file comment" | grep -v "SVN" | grep -v "spaces" | grep -v "name " | grep -v "instead" | grep -v "caps " | grep -v "\| found " | grep -v "\| but found " | grep -v "\| [0-9]* *\|" 
} else {
	phpcs --standard=herram/estandares.xml $i > /tmp/e
	less /tmp/e
} fi;
