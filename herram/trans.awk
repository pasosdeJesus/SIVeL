#!/usr/bin/awk
# Pequeño cambio a fuentes PHP.
# Agrega package a class que no tengan


# Removes spaces at left and right
function trim(str) {
	gsub(/^  */, "", str);
	gsub(/  *$/, "", str);
	return str;
}

# [transear(sa)] recieves id => val and returns id => _(val)
function transear(sa)
{
	r = sa;
	sa = trim(sa);
	if (match(sa, /=> .*,/)) {
		r = substr(sa, 1, RSTART - 1) "=> _(";
	        r = r substr(sa, RSTART + 3, RLENGTH - 4) "),";
	} else if (match(sa, /=> .*/)) {
		r = substr(sa, 1, RSTART - 1) "=> _(";
	        r = r substr(sa, RSTART + 3, RLENGTH - 3) "),";
	}

	return r;
}

/^#/ {
	$0 = "//" substr($0, 2, length($0) - 1);
}

# Fin DocBlock o comentario
/\*\// {
	if (depura == 1) {
		print "findb=1";
	}
	findb = 1;
}

/ PHP version 5/ {
	phpv5 = 1;
}

/ * @author/ {
	if (inidb == 1 && phpv5 == 0) {
		db = db "\n *\n * PHP version 5\n *\n * @category  SIVeL\n * @package   SIVeL";
		$0 = " * @author    Vladimir Támara <vtamara@pasosdeJesus.org>";
	}
}

/ @see DataObjects_Caso/ {
	if (phpv5 == 0) {
		$0 = " *\n * @category SIVeL\n * @package  SIVeL\n * @author   Vladimir Támara <vtamara@pasosdeJesus.org>\n * @license  Dominio público.\n * @link     http://sivel.sf.net/tec\n * @see      DataObjects_Caso";
 		haysee = 1;
	}
}

/ * @copyright/ {
	if (inidb == 1 && phpv5 == 0) {
		match($0, /[0-9][0-9]*/);
		db = db "\n * @copyright " substr($0, RSTART, RLENGTH) " Dominio público. Sin garantías."
		$0 = " * @license   Dominio público. Sin garantías.\n * @version   $CVS: $\n * @link      http://sivel.sf.net";
	}
}

# Package documentado en DocBlock
/^ *\* *@package / {
	haypackage = 1;
	if (phpv5 == 0) {
		salta = 1;
	}
}

# Mitad de DocBlock
/.*/ {
	if (inidb == 1 && findb == 0) {
		if (depura == 1) {
			print "db concatena" ;
		}
		if (salta != 1) {
			db = db "\n" $0;
		} else {
			salta = 0;
		}
	}
}

# Inicio DocBlock
/^ *\/\*\*/ {
	if (depura == 1) {
		print "inidb=1";
	}
	inidb = 1;
	findb = 0;
	db = $0;
	haypackage = 0;
	if (match($0, /\*\//)>0) {
		cfindb = "";
		findb = 1;
		db = ""; # Sera tomado por cfindb
	}
}

# Inicio clase
/^ *class / {
	if (depura == 1) {
		print "class" ;
	}
	if (findb != 0 && haypackage != 1) {
		db = db "\n * @package SIVeL";
	}
	if (findb != 0 && haypackage = 1 && phpv5 == 0 && haysee == 0) {
		db = db "\n * @category SIVeL\n * @package  SIVeL\n * @author   Vladimir Támara <vtamara@pasosdeJesus.org>\n * @license  Dominio público.\n * @link     http://sivel.sf.net/tec";
	}

}

/^    ###START_AUTOCODE/ {
	$0 = "    // START_AUTOCODE";
}

/^    ###END_AUTOCODE/ {
	$0 = "    // END_AUTOCODE";
}

/ \/\* ZE2 compatibility trick\*\// {
	$0 = "    /**\n     * ZE2 compatibility trick\n     *\n     * @return copia del objeto\n     */";
}

/function __clone.. { return \$this;}/ {
	$0 = "    function __clone() \n    {\n        return $this;\n    }";
}

/\/\* Static get \*\// {
	$0 = "    /**\n     * Static get\n     *\n     * @param mixed $k Llave\n     * @param mixed $v Valor\n     *\n     * @return Registro cuya llave sea el valor.\n     */";
}

/function staticGet.\$k,\$v=NULL. { return/ {
	$0 = "    function staticGet($k, $v=null) \n    {\n        " substr($0, 38, length($0) - 46) " $k, $v);\n    }";
}

/function postGenerateForm *\( *&\$form, &\$formbuilde?r? *\)/ {
	if (findb == 0 || inidb==0) {
		adc = 0;
		if (match($0, /\{/)) {
			adc = 1;
		}
		match($0, /formbuild[^ )]*/);
		n=substr($0, RSTART, RLENGTH);
		$0 = "    /**\n     * Ajusta formulario generado.\n     *\n     * @param object &$form        Formulario HTML_QuickForm\n     * @param object &$" n " Generador DataObject_FormBuilder\n     *\n     * @return void\n     */\n    function postGenerateForm(&$form, &$" n ")";
		if (adc == 1) {
			$0 = $0 "\n    {";
		}
	}
}

/function preGenerateForm *\( *&\$form[a-z]* *\)/ {
	if (findb == 0 || inidb == 0) {
		adc = 0;
		if (match($0, /{/)) {

			adc = 1;
		}
		match($0, /\$form[^ )]*/);
		n=substr($0, RSTART, RLENGTH);
		$0 = "    /**\n     * Prepara antes de generar formulario.\n     *\n     * @param object &$" n " Generador DataObject_FormBuilder\n     *\n     * @return void\n     */\n    function preGenerateForm(&" n ")";
		if (adc == 1) {
			$0 = $0 "\n    {";
		}
	}
}


/function set.*(.*)/ {
	if (findb == 0 || inidb == 0) {
		adc = 0;
		if (match($0, /{/)) {
			adc = 1;
		}
		match($0, /set[^ (]*/);
		n=substr($0, RSTART, RLENGTH);
		r=substr($0, RSTART+RLENGTH, length($0) - RSTART - RLENGTH + 1 - adc);
		$0 = "    /**\n     * Pone un valor en la base diferente al recibido del formulario.\n     *\n     * @param string $valor Valor en formulario\n     *\n     * @return Valor para BD\n     */\n    function " n r;
		if (adc == 1) {
			$0 = $0 "\n    {";
		}
	}
}


/function get[a-z].*(.*)/ {
	if (findb == 0 || inidb == 0) {
		adc = 0;
		if (match($0, /{/)) {
			adc = 1;
		}
		match($0, /get[^ (]*/);
		n=substr($0, RSTART, RLENGTH);
		r=substr($0, RSTART+RLENGTH, length($0) - RSTART - RLENGTH + 1 - adc);
		$0 = "    /**\n     * Convierte valor de base a formulario.\n     *\n     * @param string $valor Valor en base\n     *\n     * @return Valor para formulario\n     */\n    function " n r;
		if (adc == 1) {
			$0 = $0 "\n    {";
		}
	}
}


/function enumCallback\(\$table, \$key\)/ {
	if (findb == 0 || inidb == 0) {
		adc = 0;
		if (match($0, /{/)) {
			adc = 1;
		}
		$0 = "    /**\n     * Funciona legada \n     *\n     * @param string $table Tabla\n     * @param string $key   Llave\n     *\n     * @return opción enumeada asociada a la llave.\n     */\n    function enumCallback($table, $key)";
		if (adc == 1) {
			$0 = $0 "\n    {";
		}

	}

}

/function dateOptions *\(&?\$field/ {
	if (findb == 0 || inidb == 0) {
		adc = 0;
		if (match($0, /{/)) {
			adc = 1;
		}
		match($0, /dateOptions *\(/);
		r=substr($0, RSTART+RLENGTH, length($0) - RSTART - RLENGTH + 1);
		$0 = "    /**\n     * Opciones de fecha para un campo\n     *\n     * @param string &$field campo\n     *\n     * @return arreglo de opciones\n     */\n    function dateOptions(" r ;
		if (adc == 1) {
			$0 = $0 "\n    {";
		}

	}

}

/var \$nom_tabla/ {
    if (match($0, /'.*'/)) {
        nt = substr($0, RSTART, RLENGTH);
        if (nt != "'nombre de la tabla'") {
            print "    /**\n     * Constructora\n     * return @void\n     */";
            print "    public function __construct()";
            print "    {";
            $0 =  "        $this->nom_tabla = _(";
            $0 = $0 nt ");\n    }\n";
        }
    }
}

/var \$fb_fieldLabels/ {
	if (match($0, /array\(/)) {
		s = trim(substr($0, RSTART + 6));
		print "    /**\n     * Constructora\n     * return @void\n     */";
		print "    public function __construct()";
		print "    {";
		i = "$this->fb_fieldLabels= array(";
		if (s != "") {
			print "        " i;
			$0 = s;
		} else {
			$0 = i;
		}
		rfb = 1;
	}
}

/\);/ {
	if (rfb == 1) {
		if (match($0, /\);/)) {
			a = trim(substr($0, 1, RSTART - 1));
			if (a != "") {
				print "           " transear(a);
			}
		}
		$0 = "        );\n    }\n";
		rfb = 0;
	}
}

/function .* {/ {
	match($0, /^ */);
	i = substr($0, 1, RLENGTH);
	match($0, /^.*function[^\{]*/);
	$0 =  substr($0, 1, RLENGTH) "\n" i "{";
}

/function [^)]*$/ {
	inimultifun = 1;
}

/function perform\(&\$page, \$actionName\)/ {
	if (findb == 0 || inidb == 0) {
		$0 = "    /**\n     * Ejecuta acción\n     *\n     * @param object &$page      Página\n     * @param string $actionName Acción\n     *\n     * @return void\n     */\n    function perform(&$page, $actionName)";
	}
}


/function iniVar\(\)/ {
	if (findb == 0 || inidb == 0) {
		$0 = "    /**\n     * Inicializa variables y datos de la pestaña.\n     * Ver documentación completa en clase base.\n     *\n     * @return handle Conexión a base de datos\n     */\n     function iniVar()";
	}
}

/function Pag[a-zA-Z]*\(/ {
	if (findb == 0 || inidb == 0) {
		$0 = "    /**\n     * Constructora.\n     * Ver documentación completa en clase base.\n     *\n     * @param string $nomForma Nombre \n     * @param string $mreq     Mensaje de dato requerido\n     *\n     * @return void\n     */\n" $0;
	}
}

/function formularioAgrega\(&\$db, \$idcaso\)/ {
	if (findb == 0 || inidb == 0) {
		$0 = "    /**\n     * Agrega elementos al formulario.\n     * Ver documentación completa en clase base.\n     *\n     * @param handle &$db    Conexión a base de datos\n     * @param string $idcaso Id del caso\n     *\n     * @return void\n     *\n     * @see PagBaseSimple\n     */\n" $0;
	}
}


/function formularioValores\(&\$db, \$idcaso\)/ {
	if (findb == 0 || inidb == 0) {
		$0 = "    /**\n     * Llena valores del formulario.  \n     * Ver documentación completa en clase base.\n     *\n     * @param handle  &$db    Conexión a base de datos\n     * @param integer $idcaso Id del caso\n     *\n     * @return void\n     * @see PagBaseSimple\n     */\n" $0;
	}
}

/function eliminaDep\(&\$db, \$idcaso\)/ {
	if (findb == 0 || inidb == 0) {
		$0 = "    /**\n     * Elimina registros de tablas relacionadas con caso de este formulario.\n     * Ver documentación completa en clase base.\n     *\n     * @param handle  &$db    Conexión a base de datos\n     * @param integer $idcaso Id del caso\n     *\n     * @return void\n     * @see PagBaseSimple\n     */\n" $0;
	}
}

/function procesa\(&\$valores\)/ {
	if (findb == 0 || inidb == 0) {
		$0 = "    /**\n     * Procesa valores del formulario enviados por el usuario.\n     * Ver documentación completa en clase base.\n     *\n     * @param handle &$valores Valores ingresados por usuario\n     *\n     * @return bool Verdadero si y solo si puede completarlo con éxito\n     * @see PagBaseSimple\n     */\n" $0;
	}
}


/function datosBusqueda\(&\$w, &\$t, &\$dCaso, &\$subcons\)/ {
	if (findb == 0 || inidb == 0) {
		$0 = "    /**\n     * Prepara consulta SQL para buscar datos de este formulario.\n     * Ver documentación completa en clase base.\n     *\n     * @param string &$w       Consulta que se construye\n     * @param string &$t       Tablas\n     * @param object &$dCaso   Objeto con caso\n     * @param string &$subcons Subconsulta\n     *\n     * @return void\n     * @see PagBaseSimple\n     */\n" $0;
	}
}

/function nullVar()/ {
	if (findb == 0 || inidb == 0) {
		$0 = "    /**\n     * Pone en null variables asociadas a tablas de la pestaña.\n     *\n     * @return null\n     */\n" $0;
	}
}

/function copiaId()/ {
	if (findb == 0 || inidb == 0) {
		$0 = "    /**\n     * Retorna una identificación del registro actual. \n     *\n     * @return string Identifación\n     */\n" $0;
	}
}

/function elimina\(&\$valores\)/ {
	if (findb == 0 || inidb == 0) {
		$0 = "    /**\n     * Elimina de base de datos el registro actual.\n     *\n     * @param array &$valores Valores enviados por formulario.\n     *\n     * @return null\n     */\n" $0;
	}
}

/^ *class .*{/ {
	match($0,/{/);
	$0 = substr($0, 1, RSTART-1) "\n" substr($0, RSTART, length($0) - RSTART +1);
}

/__construct()/ {
	if (abrcons>0) {
		abrcons = 0;
	} else {
		abrcons = 1;
	}
}

/^ *{/ {
	if (abrcons == 1) {
		abrcons = 2;
	}	
}

/^ *}/ {
	if (cierracor != "\n") {
		print cierracor;
	}
	cierracor = $0;
}

/^ *else / {
	if (cierracor != "\n") {
		match($0, /^ *else /);
		$0 = cierracor " else " substr($0, RSTART+RLENGTH, length($0) - RSTART - RLENGTH + 1);
		cierracor = "\n";
	}
}

/^ *elseif / {
	if (cierracor != "\n") {
		match($0, /^ *elseif /);
		$0 = cierracor " elseif " substr($0, RSTART+RLENGTH, length($0) - RSTART - RLENGTH + 1);
		cierracor = "\n";
	}
}

# Parentesis al final sin uno que abra
/^[^(]*[a-zA-Z'"]\)\;$/ {
	match($0,/^ */);
	$0 = substr($0, 1, length($0) - 2) ;
	#print $0;
	f = "";
	for(i = 0; i < RLENGTH-4; i++) {
		f = f " ";
	}
	fnlinea = f ");";
	#exit(1);
}

#/var \$/ {
#	match($0, /var \$/);
#	$0 = substr($0, 1, RSTART - 1) "var    " substr($0, RSTART+4, length($0) - RSTART - 4);
#}

# Otro
/.*/ {
	if (rfb == 1) {
		c = transear($0);
		if (match($0, /fb_fieldLabels/)) {
			$0 = "        ";
		} else {
			$0 = "           " ;
		}
		$0 = $0 c; 
	}
	if (abrcons == 2) {
		abrcons =3;
	} else if (abrcons == 3) {
		print "        parent::__construct();\n"; 
		abrcons = 0;
	} 

	if (cierracor != "\n" && $0 != cierracor) {
	        if (inidb == 0) {
			$0 = cierracor "\n" $0;
		} else {
			print cierracor;
		}
		cierracor = "\n";
	}

	if (inidb != 1) {
		if (depura == 1) {
			print "otro inidb " inidb ;
		}
		if ($0 != cierracor) {
			print $0;
		}
	}
	else if (findb == 1) {
		if (depura == 1) {
			print "otro findb == 1" ;
		}
		findb = 2;
		cfindb = $0;
	}
	else if (findb == 2) {
		if (depura == 1) {
			print "otro findb == 2" ;
		}
		inidb = 0;
		findb = 0;
		haypackage = 0;
		if (db != "") {
			print db;
		}
		print cfindb;
		db = "";
		cfindb = "";
		if ($0 != cierracor) {
			print $0;
		}
	}
	else if (cierracor != "\n") {
	}
	if (fnlinea != "") {
		print fnlinea;
		fnlinea = "";
	}
}

END {
	if (cierracor != "\n") {
		print cierracor;
	}
}

BEGIN {
	depura = 0;
	salta = 0;
	phpv5 = 0;
	haysee = 0;
	cierracor = "\n";
	fnlinea = "";
	rfb = 0;
	abrcons = 0;
}
