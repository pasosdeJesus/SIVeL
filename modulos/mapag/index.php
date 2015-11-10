<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Arma interfaz
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Luca Urech <lucaurech@yahoo.de>
 * @copyright 2009 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 *
 * Acceso: CONSULTA PÚBLICA
 */

require_once "aut.php";
require_once "confv.php";
require_once $_SESSION['dirsitio'] . "/conf.php";

// Si $GLOBALS['mapag_autentica'] es false, es consulta pública
if (isset($GLOBALS['mapag_autentica']) && $GLOBALS['mapag_autentica']) {
    $aut_usuario = "";
    $accno = "";
    autentica_usuario($dsn, $aut_usuario, 0);
}

?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv = "content-type" content="text/html; charset=utf8"/>
    <title>Mapa de violaciones a los derechos humanos e infracciones al
    derecho internacional humanitario</title>

   <!-- Elementos de interfaz -->
    <link rel="stylesheet" media="screen" type="text/css"
        href="modulos/mapag/css/mapa.css" />
    <link rel="stylesheet" media="screen" type="text/css"
        href="https://raw.github.com/pasosdeJesus/js/master/www.eyecon.ro_Stefan_Petre/datepicker.css" />
    <script type="text/javascript"
        src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js">
    </script>
    <script type="text/javascript"
        src="https://raw.github.com/pasosdeJesus/js/master/www.eyecon.ro_Stefan_Petre/datepicker.js">
    </script>
    <script type="text/javascript"
        src="https://raw.github.com/pasosdeJesus/js/master/www.eyecon.ro_Stefan_Petre/eye.js">
    </script>
    <script type="text/javascript"
        src="https://raw.github.com/pasosdeJesus/js/master/www.eyecon.ro_Stefan_Petre/utils.js">
    </script>
    <!-- script type = "text/javascript"
        src = "modulos/mapag/js/datepicker_mapa.js"></script -->
    <!-- Mapas -->
    <script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDVATZz-dHXuzNkySAo1O2VQ30ixzhwf7w&sensor=false&language=es&region=CO">
    <script type="text/javascript"
        src="https://gmaps-utility-library.googlecode.com/svn/trunk/markerclusterer/1.0/src/markerclusterer.js">
    </script>
    <script type="text/javascript"
        src="https://google-maps-utility-library-v3.googlecode.com/svn/tags/markerclusterer/1.0/src/markerclusterer.js">
    </script>
    <script type="text/javascript"
        src="https://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobubble/src/infobubble.js">
    </script>
    <script type="text/javascript" src="modulos/mapag/js/mapa.js"></script>
    <script type="text/javascript"
        src="modulos/mapag/js/municipios_autopopulate.js">
    </script>
</head>

<body onload="initialize()">

<div id="container">
    <div id="map_canvas"></div>
    <div id="settings_canvas">
        <div id="loader" style="display:none;"></div>
        <form name="settings" onsubmit="addCases(true); return false;">
        <input name="num" type="hidden" value="200" />
        <div class="settings_box" id="settings_box1">
            <h3>Fecha</h3>
            <div class="settings_element">
                <div class="left">Desde:</div>
                <div class="right">
                <input type="text" name="desde" class="inputDesde"
                    id="inputDesde"
                    value="<?php
    // @codingStandardsIgnoreEnd
    $d=new DateTime();
    $d->sub(new DateInterval('P6M'));
    echo isset($GLOBALS['mapag_fechadesde']) ? $GLOBALS['mapag_fechadesde'] :
        $d->format('Y-m-d'); ?>"
                    size="11" style="float:right;" />
                </div>
                <div class="clear"></div>
            </div>
            <div class="settings_element">
                <div class="left">Hasta:</div>
                <div class="right">
                    <input type="text" name="hasta" class="inputHasta"
                        id="inputHasta"
                        value="<?php
    echo isset($GLOBALS['mapag_fechahasta']) ? $GLOBALS['mapag_fechahasta'] :
        date('Y-m-d'); ?>"
                        size="11" style="float:right;" />
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="settings_box" id="settings_box2">
            <h3>Localización</h3>
            <div class="settings_element">
                <div class="left">Dep:</div>
                <div class="right">
                    <select name="departamento" id="departamento"
                        style="width:140px;">
                    <option value="0">Mostrar todos</option>
                    <option value="">-----------------------</option>
<?php
$d = objeto_tabla('departamento');
$d->find();
while ($d->fetch()) {
    echo "<option value=\"" . (int)$d->id . "\">";
    echo htmlentities($d->nombre, ENT_QUOTES, "UTF-8") . "</option>\n";
}
?>
                    </select>
                        </div>
                      </div>
            <div class="clear"></div>
        </div>
        <div class="settings_box" id="settings_box3">
            <h3>Otros Filtros</h3>
            <div class="settings_element">
                <div class="left">P. Resp:</div>
                <div class="right">
                    <select name="prresp" id="prresp" style="width:150px;">
                        <option value="0">Mostrar todos</option>
                        <option value="">-----------------------</option>
<?php
$d = objeto_tabla('presponsable');
$d->find();
while ($d->fetch()) {
    echo "<option value=\"" . (int)$d->id . "\">";
    echo htmlentities($d->nombre, ENT_QUOTES, "UTF-8") . "</option>\n";
}
?>
                    </select>
                 </div>
            </div>
            <div class="settings_element">
                <div class="left">Violencia:</div>
                <div class="right">
                    <select name="tvio" id="tvio" style="width:150px;">
                        <option value="0">Mostrar todos</option>
                        <option value="">-----------------------</option>
<?php
$d = objeto_tabla('tviolencia');
$d->find();
while ($d->fetch()) {
    echo "<option value=\"" . 
        htmlentities($d->id, ENT_QUOTES, "UTF-8") . "\">";
    echo htmlentities($d->nombre, ENT_QUOTES, "UTF-8") . "</option>\n";
}
?>
                    </select>
                 </div>
            </div>

        </div>
        <div class="clear"></div>
        <div class="submit_box">
            <input type="button" class="button" value="Filtrar casos"
                onclick="addCases(true); return false;"/>
            <span id="nrcasos" class="nrcasos"></span>
        </div>
        </form>
    </div>

</div>

</body>

</html>
<?php
/**
 * Punto de entrada
 *
 * @return void
 */
function muestra($dsn)
{
    assert($dsn !== null);
}
?>
