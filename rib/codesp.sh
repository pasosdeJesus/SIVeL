#!/bin/sh
# Cambia codificaci�n de cadenas en espa�ol de UTF8 a LATIN1 

sed -e "s/á/�/g;s/é/�/g;s/í/�/g;s/ó/�/g;s/ú/�/g;s/ü/�/g;s/ñ/�/g;s/Á/�/g;s/É/�/g;s/Í/�/g;s/Ó/�/g;s/Ú/�/g;s/Ü/�/g;s/Ñ/�/g" --

