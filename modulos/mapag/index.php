<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Detalls de un caso
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Luca Urech <lucaurech@yahoo.de>
 * @copyright 2009 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * Acceso: CONSULTA PÚBLICA
 * @link      http://sivel.sf.net
 */

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml" xml:lang="es" lang="es" dir="ltr">
<head>
    <meta http-equiv = "content-type" content="text/html; charset=iso-8859-1"/>
    <title>Mapa de violaciones a los derechos humanos e infracciones al
    derecho internacional humanitario</title>
    <link rel = "stylesheet" media="screen" type="text/css" href="modulos/mapag/css/mapa.css" />
    <!-- Datepicker Archivos -->
    <link rel = "stylesheet" media="screen" type="text/css" href="http://sivel.sourceforge.net/externo/datepicker/css/datepicker.css" />
    <script type = "text/javascript" src="http://sivel.sourceforge.net/externo/js/jquery.js"></script>
    <script type = "text/javascript" src="mhttp://sivel.sourceforge.net/externo/datepicker/js/datepicker.js"></script>
    <script type = "text/javascript" src="mhttp://sivel.sourceforge.net/externo/datepicker/js/eye.js"></script>
    <script type = "text/javascript" src="mhttp://sivel.sourceforge.net/externo/datepicker/js/utils.js"></script>
    <script type = "text/javascript" src="modulos/mapag/js/datepicker_mapa.js"></script>
    <!-- Mapa Archivos -->
    <script src = "http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAJjGQDHUAtp1OAf8h04ZaRBSIx-UbHUVEOrEDN8P-styeTeH7fRR41b4nuvAU9edEPu1SEhMYt1Z2Bg&amp;sensor=false&amp;lang=es&amp;oe=iso-8859-1" type="text/javascript"></script>
    <script type = "text/javascript" src="http://gmaps-utility-library.googlecode.com/svn/trunk/markerclusterer/1.0/src/markerclusterer.js"></script>
    <script type = "text/javascript" src="modulos/mapag/js/mapa.js"></script>
    <script type = "text/javascript" src="modulos/mapag/js/municipios_autopopulate.js"></script>
</head>

<body onload = "initialize()" onunload="GUnload()">

<div id = "container">
    <div id = "map_canvas"></div>
    <div id = "settings_canvas">
        <div id = "loader" style="display:none;"></div>
        <form name = "settings" action="opcion.php" onsubmit="addCases(true); return false;">
        <input name = "num" type="hidden" value="200" />
        <div class = "settings_box" id="settings_box1">
            <h3>Fecha</h3>
            <div class = "settings_element">
                <div class = "left">Desde:</div>
                <div class = "right">
                    <input type = "text" name="desde" class="inputDesde" id="inputDesde" value="2001-01-01" size="7" style="float:right;" />
                </div>
                <div class = "clear"></div>
            </div>
            <div class = "settings_element">
                <div class = "left">Hasta:</div>
                <div class = "right">
                    <input type = "text" name="hasta" class="inputHasta" id="inputHasta" value="<?php echo date('Y-m-d') ?>" size="7" style="float:right;" />
                </div>
                <div class = "clear"></div>
            </div>
        </div>
        <div class = "settings_box" id="settings_box2">
            <h3>Localización</h3>
            <div class = "settings_element">
                <div class = "left">Dep:</div>
                <div class = "right">
                    <select name = "departamento" id="departamento" style="width:140px;">
                    <option value = "0">Mostrar todos</option>
                    <option value = "">-----------------------</option>
<?php
$d = objeto_tabla('departamento');
$d->find();
while ($d->fetch()) {
    echo "<option value=\"" . (int)$d->id . "\">";
    echo htmlentities($d->nombre, ENT_COMPAT, 'UTF-8') . "</option>\n";
}
?>
                    </select>
                        </div>
                      </div>
            <div class = "clear"></div>
        </div>
        <div class = "settings_box" id="settings_box3">
            <h3>Otros Filtros</h3>
            <div class = "settings_element">
                <div class = "left">P. Resp:</div>
                <div class = "right">
                    <select name = "prresp" id="prresp" style="width:150px;">
                        <option value = "0">Mostrar todos</option>
                                    <option value = "">-----------------------</option>
<?php
    $d = objeto_tabla('presponsable');
    $d->find();
    while ($d->fetch()) {
        echo "<option value=\"" . (int)$d->id . "\">";
        echo htmlentities($d->nombre, ENT_COMPAT, 'UTF-8') . "</option>\n";
    }
?>
                    </select>
                 </div>
            </div>
            <div class = "settings_element">
                <div class = "left">Violencia:</div>
                <div class = "right">
                    <select name = "tvio" id="tvio" style="width:150px;">
                        <option value = "0">Mostrar todos</option>
                                    <option value = "">-----------------------</option>
<?php
    $d = objeto_tabla('tviolencia');
    $d->find();
    while ($d->fetch()) {
        echo "<option value=\"" . $d->id . "\">";
        echo htmlentities($d->nombre, ENT_COMPAT, 'UTF-8') . "</option>\n";
    }
?>
                    </select>
                 </div>
            </div>

        </div>
        <div class = "clear"></div>
        <div class = "submit_box">
            <input type = "submit" class="button" value="Filtrar casos" onclick="addCases(true); return false;" /> <span id="nrcasos" class="nrcasos"></span>
        </div>
        </form>
    </div>

</div>

</body>

</html>

<?php
    function muestra()
    {
        // No autenticamos porque es consulta pública
    }
?>
