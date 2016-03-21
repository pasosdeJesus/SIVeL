
# Reglas para distribuir sivel
# Fuentes de dominio público. Sin garantías.
# http://www.sf.net/projects/sivel

# Configuration variables
include Make.inc

USERCVS?=$(USER)
USERACT=$(USERCVS),$(PROYECTO)

# Variables requeridas por comdist.mak
GENDIST=Instala.txt Novedades.txt #usrdoc
ACTHOST=web.sourceforge.net
ACTDIR=/home/groups/s/si/sivel/htdocs/1.2/
GITHOST=https://github.com/pasosdeJesus/
GITPRY=SIVeL
GENACT=distsf
FILESACT=$(PROYECTO)-$(PRY_VERSION).tar.gz

all:

docact: dist #distcvs
	rm -rf $(PROYECTO)-$(PRY_VERSION)
	tar xvfz $(PROYECTO)-$(PRY_VERSION).tar.gz	
	(cd $(PROYECTO)-$(PRY_VERSION)/doc; ./conf.sh; make; make act)

limpia:
	find . -name "*~" | xargs rm -f 
	-cd doc/ && make limpia

limpiamas: limpia
	find . -type file -name "*bak" | xargs rm -f 
	find . -type file -name "*~" | xargs rm -f 
	rm -f confaux.tmp doc/conf.php confv.copia 
	-cd doc/ && make limpiamas

limpiadist: limpiamas
	rm -rf $(PROYECTO)-$(PRY_VERSION).tar.gz
	rm -rf doc/personaliza.ent.copia
	rm -rf locale/m.pot
	rm -f fixlinks.sh
	rm -f confv.sh confv.php
	rm -f Anotaciones.txt varses.sh Mejoras.txt clave-* sivel-*.ini sivel-*.links.ini vardb.sh aut/conf.php DataObject.ini doc/personaliza.ent FondoSecc.png DataObject-*ini
	rm -rf tmp
	#for i in `find . -name *plantilla`; do n=`echo $$i | sed -e "s/.plantilla//g"`; rm -f $$n;  done;
	rm -rf ewiki ultimoenvio.txt priv bak valida/* cuenta-datos.out
	rm -rf st; mkdir -p st; mv sitios/nuevo.sh sitios/pordefecto sitios/pruebas st/; rm -rf sitios/*; mv st/* sitios/; rm -rf st
	#rm -rf mt; mkdir -p mt; mv modulos/{anexos,bdcinep,belicas,desplazamiento,estrotulos,etiquetas,segjudicial} mt/; rm -rf modulos/*; mv mt/* modulos/; rm -rf mt
	rm -rf web tmp sitios/pruebas/salida pdoc
	find . -name ".#*" -exec rm {} ';'
	-(cd doc;make limpiadist)



website/index.html:  website website/index.html.in
	cd website; make



# Distribution and publication
include herram/comdist.mak

# Documentation

usrdoc: 
	cp doc/confv.empty doc/confv.empty.bak
	sed -e "s/PRY_VERSION[ ]*=.*/PRY_VERSION=\"$(PRY_VERSION)\"/g" doc/confv.empty.bak | sed -e "s|FECHA_PROX[ ]*=.*|FECHA_PROX=\"$(FECHA_PROX)\"|g" > doc/confv.empty
	rm -f doc/confv.sh
	(cd doc/ ; ./conf.sh; make; make ../Instala.txt; make ../Novedades.txt; make ../Creditos.txt; make ../Derechos.txt)

.PRECIOUS: .pdoc
tecdoc1: 
	mkdir -p pdoc
	phpdoc -dc SIVeL -t pdoc -d . -i "pruebas/*,conf*php,doc/*,Make.inc,*sitios**,*web*index.php,/herram/ind.php,/web/index.php"

tecdoc2:
	for i in `find pdoc -name "*html"`; do \
		cp $$i $$i.copia; \
		sed -e 's/iso-8859-1/utf-8/g' $$i.copia > $$i; \
	done

tecdoc: tecdoc1 tecdoc2
	rm -rf pdoc/*copia

tecdoc-act: tecdoc-$(ACT_PROC)
	if (test "$(OTHER_ACT)" != "") then { make tecdoc-$(OTHER_ACT); } fi;

tecdoc-act-scp:
	        $(SCP) -r pdoc/* $(USERACT)@$(ACTHOST):$(ACTDIR)/tec/

tecdoc-act-ncftpput:
	        $(NCFTPPUT) -u $(USERACT) $(ACTHOST) $(ACTDIR)/tec/ pdoc/


doc: usrdoc Creditos.txt Instala.txt Derechos.txt Novedades.txt

docdist: 
	(cd doc/ ; make dist)

distsf: doc distgh
	cp doc/html/index.html doc/html/index.html-sinsf
	sed -e "s/HTML comprimido<\/a>./ HTML comprimido<\/a>. Agradecemos el hospedaje brindado por SourceForge <a href=\"http:\/\/sourceforge.net\/projects\/sivel\"><img src=\"http:\/\/sflogo.sourceforge.net\/sflogo.php?group_id=104373&amp;type=8\" width=\"80\" height=\"15\" alt=\"Get SIVeL at SourceForge.net. Fast, secure and Free Open Source software downloads\" \/><\/a>/g" doc/html/index.html-sinsf > doc/html/index.html

actualiza-pot:
	echo '' > locale/m.pot
	find . -type f -name "*.php" | xgettext -o locale/m.pot -j --from-code=UTF-8  -f -
	msgmerge -N locale/sivel.pot locale/m.pot > locale/nuevo.pot
	mv locale/nuevo.pot locale/sivel.pot

actualiza-po: actualiza-pot
	for i in locale/?? ; do \
		echo $$i;\
		msgmerge -vU $$i/LC_MESSAGES/sivel.po locale/sivel.pot; \
	done
	
actualiza-mo: actualiza-po
	for i in locale/?? ; do \
		echo $$i;\
		msgcat $$i/LC_MESSAGES/sivel.po | msgfmt -vo $$i/LC_MESSAGES/sivel.mo - ; \
	done

trad: actualiza-mo
	
