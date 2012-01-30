#!/bin/sh
# Genera grafo de inclusión de fuentes en PHP
# Para ser visualizado con graphviz

echo "digraph inclusionPHP {

graph [ rankdir = "LR" ]; ";

for i in *php; do 
	for j in  `grep require_once $i | sed -e "s/.*require_once[ ('\"]*\([^'\"]*\).*/\1/g"`; do 
		echo "\"$j\" -> \"$i\""; 
	done; 
done;

echo "}";

