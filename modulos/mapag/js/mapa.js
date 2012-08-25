// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
// Mapa
//
// @category  SIVeL
// @package   SIVeL
// @author    Luca Urech <lucaurech@yahoo.de>
// @copyright 2011 Dominio público. Sin garantías.
// @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
// @version   $$
// @link      http://sivel.sf.net
//

// inicializa variables globales
var map = null;
var mc = null;
var markersClusterer = [];
var bounds;
var mcOptions = {gridSize: 40, maxZoom: 10}; // configuracion del MarkerClusterer

function showLoader() {
	$('#loader').show();
}

function hideLoader() {
	$('#loader').hide();
}

function showAll() {
	var zoomLevel = map.getBoundsZoomLevel(bounds)-1;
	if (zoomLevel > 13) {
		zoomLevel = 13;
	}
	map.setZoom(zoomLevel);
	map.setCenter(bounds.getCenter());
}

function initialize() {

  if (GBrowserIsCompatible()) {
  
	map = new GMap2(document.getElementById("map_canvas"));
	
	// configuracion basica del mapa
	map.setCenter(new GLatLng(8.3190, -72.4487), 7);
	map.setMapType(G_PHYSICAL_MAP);
	map.addMapType(G_PHYSICAL_MAP);
	map.addControl(new GMapTypeControl());
	map.addControl(new GLargeMapControl3D());
	map.addControl(new GScaleControl());
	map.addControl(new GOverviewMapControl());
	new GKeyboardHandler(map);
	
	// agrega casos al mapa
	window.setTimeout(addCases, 0);

  }
}

function addCases(refresh) {

	bounds = new GLatLngBounds();

	// leer filtros desde el formulario
	var desde = $('#inputDesde').val();
	var hasta = $('#inputHasta').val();
	var departamento = $('#departamento').val();
	//var municipio = $('#municipio').val();
	var prresp = $('#prresp').val();
	var tvio = $('#tvio').val();

		
	var requestUrl = "modulos/mapag/casos_sivel_remote.php?desde=" + desde;
	requestUrl += "&hasta=" + hasta;
	if (departamento != 0) {
		requestUrl += "&departamento=" + departamento;
	}
	if (tvio != 0) {
		requestUrl += "&tvio=" + tvio;
	}
	if (prresp != 0) {
		requestUrl += "&prresp=" + prresp;
	}
	//window.alert(requestUrl);
	
	if (refresh == true) {
		markersClusterer.length = 0;
		mc.clearMarkers();
	};
	
	showLoader();

	GDownloadUrl(requestUrl, function(data) {
	
		var xml = GXml.parse(data);
		var markers = xml.documentElement.getElementsByTagName("caso");
		var numResult = markers.length;
		
		for (var i = 0; i < markers.length; i++) {
			
			var codigo = markers[i].getAttribute("id_relato");
			var lat = markers[i].getAttribute("latitud");
			var lng = markers[i].getAttribute("longitud");
			var titulo = markers[i].getAttribute("titulo");
			var fecha = markers[i].getAttribute("fecha");
			
			var point = new GLatLng(parseFloat(lat), parseFloat(lng));
			var title = fecha + ": " + titulo;
			var marker = createMarker(point, codigo, title);
			markersClusterer.push(marker);
		}
		
		$('#nrcasos').html('(<strong>' + numResult + '</strong> casos mostrados)');
		
		mc = new MarkerClusterer(map, markersClusterer, mcOptions);
		
		showAll();
		
		hideLoader();
	
	});
}

function createMarker(point, codigo, title) {
			
	// crear icon
	var icon = new GIcon();
	icon.image = "modulos/mapag/images/icon.png";
	icon.iconSize = new GSize(20,20);
	icon.infoWindowAnchor = new GPoint(10,5);
	icon.iconAnchor = new GPoint(9,9);
	
	// crear marker
	var marker = new GMarker(point, {icon: icon, title: title});
	
	// extender bounds
	bounds.extend(point);

	GEvent.addListener(marker, 'click', function() {
		
		showLoader();
		
		GDownloadUrl("modulos/mapag/caso_detalles_sivel_remote.php?codigo=" + codigo, function(data) {
		
			var xml = GXml.parse(data);
			
			var casos = xml.documentElement.getElementsByTagName("caso");
			
			var id = casos[0].getAttribute("id");
			var titulo = GXml.value(casos[0].getElementsByTagName("titulo")[0]);
			var hechos = GXml.value(casos[0].getElementsByTagName("hechos")[0]);
			var fecha = GXml.value(casos[0].getElementsByTagName("fecha")[0]);
			var hora = GXml.value(casos[0].getElementsByTagName("hora")[0]);
			var departamento = GXml.value(casos[0].getElementsByTagName("departamento")[0]);
			var municipio = GXml.value(casos[0].getElementsByTagName("municipio")[0]);
			var centro_poblado = GXml.value(casos[0].getElementsByTagName("centro_poblado")[0]);
			var victimas = casos[0].getElementsByTagName("victimas")[0].getElementsByTagName('persona');
			var prresp = casos[0].getElementsByTagName("presponsable")[0].getElementsByTagName('presponsable');
			
			// generar contenido
			var descripcionCont = '<div class="infowindowcont"><h3>' + titulo + '</h3>' + hechos + '</div>';
			
			var hechosCont = '<div class="infowindowcont"><table>';
				hechosCont += (fecha != "") ? '<tr><td>Fecha:</td><td>' + fecha + '</td></tr>' : '';
				hechosCont += (hora != "") ? '<tr><td>Hora:</td><td>' + hora + '</td></tr>' : '';
				hechosCont += (departamento != "") ? '<tr><td>Departamento:</td><td>' + departamento + '</td></tr>' : '';
				hechosCont += (municipio != "") ? '<tr><td>Municipio:</td><td>' + municipio + '</td></tr>' : '';
				hechosCont += (centro_poblado != "") ? '<tr><td>Centro Poblado:</td><td>' + centro_poblado + '</td></tr>' : '';
				hechosCont += (codigo != "") ? '<tr><td>Codigo:</td><td>' + codigo + '</td></tr>' : '';
				hechosCont += '</table></div>';
				
				
			var victimasCont = '<div class="infowindowcont"><table><tr><td>Victimas:</td><td>';
			
				for (var i = 0; i < victimas.length; i++) {
					var victima = GXml.value(victimas[i]);
					victimasCont += (victima != "") ? victima + '<br />' : 'SIN INFORMACIÓN';
				}
				
				victimasCont += (victimas.length == 0) ? 'SIN INFORMACIÓN' : '';
				
				victimasCont += '</td></tr><tr><td>Presuntos Responsables:</td><td>';
				
				for (var i = 0; i < prresp.length; i++) {
					var prrespel = GXml.value(prresp[i]);
					victimasCont += (prrespel != "") ? prrespel + '<br />' : 'SIN INFORMACIÓN';
				}
				
				victimasCont += (prresp.length == 0) ? 'SIN INFORMACIÓN' : '';
				
				victimasCont += '</td></tr></table>';
				
			
			infoTabs = [
					new GInfoWindowTab("Descripción", descripcionCont),
					new GInfoWindowTab("Datos", hechosCont),
					new GInfoWindowTab("Víctimas", victimasCont)
			]
								
			var options= { maxWidth: 400 }; 
			marker.openInfoWindowTabsHtml(infoTabs, options);
			
			hideLoader();
	
		});
		
	});
	
	return marker;
}
