#!/bin/sh

t=$1
if (test "$t" = "") then {
    echo "Falta nombre de tabla como primer parametro"
    exit 1;
} fi;

tm=`echo $t | tr '[:upper:]' '[:lower:]'`
pl=`echo $tm | sed -e "s/^\(.\).*/\1/g"`
plm=`echo $pl | tr '[:lower:]' '[:upper:]'`
pr=`echo $tm | sed -e "s/^.\(.*\)/\1/g"`
tg="${plm}${pr}"
hoy=`date "+%Y-%m-%d"`
anio=`date "+%Y"`


echo -n "estructura.sql - "
grep -q "CREATE  *TABLE  *$tm " estructura.sql 2> /dev/null
if (test "$?" != "0") then {
	cat >> estructura.sql <<EOF
CREATE SEQUENCE ${tm}_seq;
CREATE TABLE $tm (
	id INTEGER PRIMARY KEY DEFAULT nextval('${tm}_seq'::regclass),
	nombre VARCHAR(50) NOT NULL,
	fechacreacion DATE DEFAULT '$hoy'::DATE NOT NULL,
	fechadeshabilitacion DATE CHECK (((fechadeshabilitacion IS NULL) OR (fechadeshabilitacion >= fechacreacion)))
);
EOF
	echo " - Modificado"
} else {
	echo " - Mantenido"
} fi;


awk "/^ *\);/ { ent = 0; } /.*/ { if (ent == 1) { print \$0; } } /CREATE  *TABLE  *$tm / { ent = 1; }" estructura.sql | grep -e "INTEGER" -e VARCHAR -e DATE -e BOOL > /tmp/campos

echo -n "estructura_databoject.ini - "
grep -q "\[$tm\]" DataObjects/estructura-dataobject.ini 2>/dev/null
if (test "$?" != "0") then {
	echo "[${tm}]" >> DataObjects/estructura-dataobject.ini
	sed -e "s/^[^a-zA-Z_]*\([a-zA-Z_]*\).*INTEGER.*PRIMARY KEY.*/\1 = 129/g;s/^[^a-zA-Z_]*\([a-zA-Z_]*\).*INTEGER.*NOT NULL.*/\1 = 129/g;s/^[^a-zA-Z_]*\([a-zA-Z_]*\).*INTEGER.*/\1 = 1/g;s/^[^a-zA-Z_]*\([a-zA-Z_]*\).*VARCHAR.*NOT NULL.*/\1 = 130/g;s/^[^a-zA-Z_]*\([a-zA-Z_]*\).*VARCHAR.*/\1 = 2/g;s/^[^a-zA-Z_]*\([a-zA-Z_]*\).*DATE.*NOT NULL.*/\1 = 134/g;s/^[^a-zA-Z_]*\([a-zA-Z_]*\).*DATE.*/\1 = 6/g;s/^[^a-zA-Z_]*\([a-zA-Z_]*\).*BOOL.*/\1 = 18/g" /tmp/campos >> DataObjects/estructura-dataobject.ini
	cat >> DataObjects/estructura-dataobject.ini <<EOF
[${tm}__keys]
id = K

EOF
	echo " - Modificado"
} else {
	echo " - Mantenido"
} fi;

echo -n "estructura_databoject.links.ini - "
grep -q "\[$tm\]" DataObjects/estructura-dataobject.links.ini 2>/dev/null
if (test "$?" != "0") then {
	grep "REFERENCES" /tmp/campos > /tmp/ref
	if (test -s /tmp/ref) then {
		echo "" >> DataObjects/estructura-dataobject.links.ini
		echo "[$tm]" >> DataObjects/estructura-dataobject.links.ini
		sed -e "s/^[^A-Za-z_]*\([a-zA-Z_]*\).*REFERENCES \([a-zA-Z_]*\).*,.*/\1 = \2:id/g" /tmp/ref >> DataObjects/estructura-dataobject.links.ini
	} fi;
	echo " - Modificado"
} else {
	echo " - Mantenido"

} fi;

grep -q fechacreacion /tmp/campos
if (test "$?" = "0") then {
	basica=1;
} else {
	basica=0;
} fi;

echo -n "DataObjects/$tg.php - "
if (test ! -f "DataObjects/$tg.php") then {
	grep -q fechacreacion /tmp/campos
	if (test "$?" = "0") then {
		basica=1;
		preq="DataObjects/"
		preq2="DataObjects_"
		cbas="Basica"
	} else {
		basica=0
		preq=""
		preq2=""
		cbas="DB_DataObject_SIVeL"
	} fi;
	cat > DataObjects/$tg.php <<EOF
<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Objeto asociado a una tabla de la base de datos.
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright $anio Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

/**
 * Definicion para la tabla $tm
 */
require_once '${preq}${cbas}.php';

/**
 * Definicion para la tabla $tm
 * Ver documentación de ${preq2}${cbas}.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      ${preq2}${cbas}
 */
class DataObjects_$tg extends ${preq2}$cbas
{
    var \$__table = '$tm';                       
EOF
	if (test "$basica" = "0") then {
		sed -e "s/^[^a-zA-Z_]*\([a-zA-Z_]*\) *.*/    var $\1;/g;" /tmp/campos >> DataObjects/$tg.php
		cat >> DataObjects/$tg.php <<EOF

	var \$fb_preDefOrder = array(
EOF
		sed -e "s/^[^a-zA-Z_]*\([a-zA-Z_]*\) *.*/        '\1',/g;" /tmp/campos >> DataObjects/$tg.php
		cat >> DataObjects/$tg.php <<EOF
	);
	var \$fb_fieldsToRender = array(
EOF
		sed -e "s/^[^a-zA-Z_]*\([a-zA-Z_]*\) *.*/        '\1',/g;" /tmp/campos >> DataObjects/$tg.php
		cat >> DataObjects/$tg.php <<EOF
	);
	var \$fb_addFormHeader = false;
	var \$fb_hidePrimaryKey = false;
EOF
	} fi;

	cat >> DataObjects/$tg.php <<EOF

    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

        \$this->nom_tabla = _('$tg');
EOF
	if (test "$basica" = "0") then {

	cat >> DataObjects/$tg.php <<EOF
        \$this->fb_fieldLabels= array(
EOF

	sed -e "s/^[^a-zA-Z_]*\([a-zA-Z_]*\) *.*/            '\1' => _('\1'),/g;" /tmp/campos >> DataObjects/$tg.php
	cat >> DataObjects/$tg.php <<EOF
        );
EOF
    	} fi;
	cat >> DataObjects/$tg.php <<EOF
    }
EOF
	if (test "$basica" = "1") then {
	cat >> DataObjects/$tg.php <<EOF

    /**
     * Identificacion de registro 'SIN INFORMACIÓN'
     *
     * @return integer Id del registro SIN INFORMACIÓN
     */
    static function idSinInfo()
    {
        return 0;
    }
EOF
	} fi;

	if (test "$basica" = "0") then {
	cat >> DataObjects/$tg.php <<EOF

    var \$fb_textFields = array ();
    var \$fb_enumFields = array();
    var \$fb_booleanFields = array(
EOF
	grep BOOL /tmp/campos | sed -e "s/^[^a-zA-Z_]*\([a-zA-Z_]*\) *.*/        '\1',/g;" >> DataObjects/$tg.php
	cat >> DataObjects/$tg.php <<EOF
    );

    /**
     * Prepara antes de generar formulario.
     *
     * @param object &\$formbuilder Generador DataObject_FormBuilder
     *
     * @return void
     */
    function preGenerateForm(&\$formbuilder)
    {
	    parent::preGenerateForm(\$formbuilder);
    }


    /**
     * Ajusta formulario generado.
     *
     * @param object &\$form        Formulario HTML_QuickForm
     * @param object &\$formbuilder Generador DataObject_FormBuilder
     *
     * @return void
     */
    function postGenerateForm(&\$form, &\$formbuilder)
    {
        parent::postGenerateForm(\$form, \$formbuilder);
    }
EOF
	} fi;
	cat >> DataObjects/$tg.php <<EOF
}
?>
EOF
	echo " - Modificado"
} else {
	echo " - Mantenido"
} fi;

echo -n "datos.sql - "
grep -q "INSERT INTO $tm " datos.sql 2>/dev/null
if (test "$?" != "0") then {
	echo "INSERT INTO $tm (id, nombre, fechacreacion) VALUES (0, 'SIN INFORMACIÓN', '$hoy');" >> datos.sql
	echo " - Modificado"
} else {
	echo " - Mantenido"
} fi;	
