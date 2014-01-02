#!/bin/sh
# Cambia codificación de cadenas en español de UTF8 a LATIN1 

sed -e "s/Ã¡/á/g;s/Ã©/é/g;s/Ã­/í/g;s/Ã³/ó/g;s/Ãº/ú/g;s/Ã¼/ü/g;s/Ã±/ñ/g;s/Ã/Á/g;s/Ã‰/É/g;s/Ã/Í/g;s/Ã“/Ó/g;s/Ãš/Ú/g;s/Ãœ/Ü/g;s/Ã‘/Ñ/g" --

