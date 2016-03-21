#!/bin/sh
#Copia partes de ispell, ispell-spanish, echo y openssl  a directorio 
# chroot de Apache

dchroot=$1;
if (test "$dchroot" = "") then {
	echo "Primer parametro debe ser directorio chroot en el que corre Apache";
	exit 1;
} fi;

mkdir -p $dchroot/usr/local/bin
mkdir -p $dchroot/usr/local/lib/ispell
mkdir -p $dchroot/usr/lib
mkdir -p $dchroot/usr/libexec
mkdir -p $dchroot/bin/
mkdir -p $dchroot/usr/bin/

cp /usr/local/bin/ispell $dchroot/usr/local/bin/ispell
cp /usr/local/lib/ispell/spanish.{aff,hash} $dchroot/usr/local/lib/ispell/
cp /usr/lib/{libc.so.*,libtermcap.so.*,libssl.so.*,libcrypto.so.*} $dchroot/usr/lib/
cp /usr/libexec/ld.so $dchroot/usr/libexec/
cp /usr/bin/openssl $dchroot/usr/bin/
cp /bin/echo $dchroot/bin/

# Pruebas

cp /bin/ksh /bin/sh $dchroot/bin/
echo 'r=`echo "holax nación" | /usr/local/bin/ispell -d spanish -l -Tlatin1`' > $dchroot/usr/local/bin/test-ispell.sh
echo 'if (test "$r" != "holax") then { echo 'Problema con instalación de ispell'; exit 1; } fi;' >> $dchroot/usr/local/bin/test-ispell.sh
echo "echo \"ispell funciona en español en directorio chroot $dchroot/\"" >> $dchroot/usr/local/bin/test-ispell.sh
chmod +x $dchroot/usr/local/bin/test-ispell.sh
chroot -u www $dchroot/ /usr/local/bin/test-ispell.sh
