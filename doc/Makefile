# Reglas para generar HTML, PostScript y PDF de docsivel
# Basadas en infraestructura de dominio público de repasa 
#   (http://structio.sourceforge.net/repasa)

include Make.inc

# Variables requeridas por comdocbook.mak

USERCVS=$(USER)
USERACT=$(USERCVS),sivel

EXT_DOCBOOK=xdbk

SOURCES=$(PROYECTO).$(EXT_DOCBOOK) principios.$(EXT_DOCBOOK) ambiente.$(EXT_DOCBOOK) instala.$(EXT_DOCBOOK) uso.$(EXT_DOCBOOK) administracion.$(EXT_DOCBOOK) personalizacion.$(EXT_DOCBOOK) creditos.$(EXT_DOCBOOK) novedades.$(EXT_DOCBOOK) varconfphp.$(EXT_DOCBOOK)
# Listado de fuentes XML. Preferiblemente en el orden de inclusión

IMAGES=img/home.png img/prev.png img/toc-minus.png img/blank.png img/important.png img/toc-plus.png img/caution.png img/next.png img/tip.png img/up.png img/draft.png img/note.png img/toc-blank.png img/warning.png img/nivel1.png img/nivel2.png img/nivel3.png img/nivel4.png img/nivel5.png img/nivel6.png img/pfichabasicos.png img/pfichaubicacion.png img/pfichafrecuentes.png img/pfichaotras.png img/pfichacontexto.png img/pfichapresponsables.png img/pfichavictimaindividual.png img/pfichavictimacolectiva.png img/pfichavictimacombatiente.png img/pfichaactos.png img/pfichamemo.png img/pfichaanexos.png img/pfichaetiquetas.png img/pfichaevaluacion.png img/pconsultaweb.png img/pautentica.png img/pmenu.png img/peditafrecuente.png img/pnuevafrecuente.png img/preporteconsolidado.png img/preporterevistanobelicas.png img/ptablasbasicas.png img/ptablasbasicas-noautorizado.png img/pusuarios.png img/pusuarionuevo.png img/ptablafrecuente.png img/pconteovindividuales.png img/presconteo.png img/presconteo2.png img/estructura.png img/arqseg.png

# Listado de imagenes, preferiblemente en formato PNG 
# Note que desde home.png son estandar en DocBook

HTML_DIR=html
# Directorio en el que se generará información en HTML (con reglas por defecto)

HTML_TARGET=$(HTML_DIR)/index.html
# Nombre del HTML principal (debe coincidir con el especificado en estilohtml.xsl)

XSLT_HTML=estilohtml.xsl
# Hoja XSLT para generar HTML con regla por defecto

PRINT_DIR=imp
# Directorio en el que se genera PostScript y PDF en reglas por defecto

DSSSL_PRINT=estilo.dsl\#print
# Hoja de estilo DSSSL para generar TeX en reglas por defecto

DSSSL_HTML=estilo.dsl\#html
# Hoja de estilo DSSSL para generar HTML en reglas por defecto

OTHER_HTML=


INDEX=indice.$(EXT_DOCBOOK)
# Si habrá un índice, nombre del archivo con el que debe generarse (incluirlo al final del documento)


# Variables requeridas por comdist.mk

GENDIST=$(SOURCES) $(IMAGES)
# Dependencias por cumplir antes de generar distribución

ACTHOST=web.sourceforge.net
# Sitio en Internet donde actualizar. Método indicado por ACT_PROC de confv.sh

ACTDIR=/home/groups/s/si/sivel/htdocs/1.1/
# Directorio en ACTHOST por actualizar

#USER=
# Usuario en ACTHOST.  Si es el mismo que en la máquina local comentar


GENACT=dist all $(PROYECTO)-$(PRY_VERSION)_html.tar.gz #$(PRINT_DIR)/$(PROYECTO)-$(PRY_VERSION).ps.gz $(PRINT_DIR)/$(PROYECTO)-$(PRY_VERSION).pdf
# Dependencias por cumplir antes de actualizar sitio en Internet al publicar

FILESACT=$(PROYECTO)-$(PRY_VERSION).tar.gz $(PROYECTO)-$(PRY_VERSION)_html.tar.gz $(HTML_DIR)/* #$(PRINT_DIR)/$(PROYECTO)-$(PRY_VERSION).ps.gz $(PRINT_DIR)/$(PROYECTO)-$(PRY_VERSION).pdf
# Archivos que se debe actualizar en sitio de Internet cuando se publica

all: $(HTML_TARGET) #$(PRINT_DIR)/$(PROYECTO).ps $(PRINT_DIR)/$(PROYECTO).pdf

repasa:
	DEF=$(PROYECTO).def CLA=$(PROYECTO).cla SEC=$(PROYECTO).sec DESC="Información extraída de $(PROYECTO) $(PRY_VERSION)" FECHA="$(FECHA_ACT)" BIBLIO="$(URLSITE)" TIPO_DERECHOS="descripcion corta derechos (cambiar Makefile)" TIEMPO_DERECHOS="$(MES_ACT)" DERECHOS="descripción larga derechos (cambiar en Makefile)" AUTORES="aqui poner autores (cambiar en Makefile)" IDSIGNIFICADO="sig" $(AWK) -f herram/db2rep $(SOURCES)

# Para usar DocBook
include herram/comdocbook.mak

# Para crear distribución de fuentes y publicar en Internet
include herram/comdist.mak

# Elimina hasta configuración
limpiadist: limpiamas
	rm -f confv.sh confv.ent Make.inc .#*
	rm -rf $(HTML_DIR)
	rm -rf $(PRINT_DIR)

# Elimina archivos generables
limpiamas: limpia
	rm -f img/*.eps img/*.ps
	rm -f $(PROYECTO)-$(PRY_VERSION).tar.gz
	rm -f $(INDEX) $(INDEX).m genindice.xdbk genindice.xdbk.m genindice.xml.m HTML.index.m


# Elimina backups y archivos temporales
limpia:
	rm -f *bak *~ *tmp confaux.tmp $(PROYECTO)-$(PRY_VERSION)_html.tar.gz
	rm -f $(PROYECTO)-4.1.*

../Instala.txt: html/instalacion.html html/pasos.html
	$(W3M) $(W3M_OPT) -dump html/instalacion.html | awk -f herram/conthtmldoc.awk | awk '/\. Prerrequisitos/ { e=e+1; } /.*/ {if (e>1) { print $$0; }} BEGIN { e=0; } ' > ../Instala.txt
	$(W3M) $(W3M_OPT) -dump html/pasos.html | awk -f herram/conthtmldoc.awk | awk '/\. Procedimiento/ { e=e+1; } /.*/ {if (e>0) { print $$0; }} BEGIN { e=0; } ' >> ../Instala.txt

../Novedades.txt: html/novedades.html 
	$(W3M) $(W3M_OPT) -dump html/novedades.html | awk -f herram/conthtmldoc.awk | awk '/\. Novedades/ { e=e+1; } /.*/ {if (e>0) { print $$0; }} BEGIN { e=0; } ' > ../Novedades.txt


../Creditos.txt: html/creditos.html
	$(W3M) $(W3M_OPT) -dump html/creditos.html | awk -f herram/conthtmldoc.awk | awk '/\. Créditos/ { e=e+1; } /.*/ {if (e>=1) { print $$0; }} BEGIN { e=0; } ' > ../Creditos.txt


../Derechos.txt: html/index.html 
	$(W3M) $(W3M_OPT) -dump html/index.html | awk -f herram/conthtmldoc.awk | awk '/.*/ {if (e>=1) { print $$0; }} /Documentación/ { e=e+1; } BEGIN { e=0; } ' > ../Derechos.txt

varconfphp.xdbk: ../sitios/pordefecto/conf.php.plantilla
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>" > varconfphp.xdbk
	awk -f herram/convar.awk ../sitios/pordefecto/conf.php.plantilla >> varconfphp.xdbk


