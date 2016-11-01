// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
// Mapa
//
// @category  SIVeL
// @package   SIVeL
// @author    Luca Urech <lucaurech@yahoo.de> API googlemaps v2
// @author    Vladimir Támara <vtamara@pasosdeJesus.org> API googlemaps v3
// @license   2011 https://www.pasosdejesus.org/dominio_publico_colombia.html 
// @link      http://sivel.sf.net
//

// inicializa variables globales
var map = null;
var mc = null;
var markersClusterer = [];
var bounds;
var mcOptions = {gridSize: 40, maxZoom: 10, imagePath: 'modulos/mapag/vendor/js-marker-clusterer/images/m'}; // conf. de MarkerClusterer

function showLoader() {
	$('#loader').show();
}

function hideLoader() {
	$('#loader').hide();
}

function showAll() {
    map.fitBounds(bounds);
	map.setCenter(bounds.getCenter());
    var bListener = 
        google.maps.event.addListener(map, 'bounds_changed', function(event) {
            if (this.getZoom() > 13) {
                this.setZoom(13);
            }
        });
    setTimeout(function(){google.maps.event.removeListener(bListener)}, 2000);
}

function initialize() {
    /* https://developers.google.com/maps/documentation/javascript/tutorial */
    var mapOptions = {
        zoom: 7,
        mapTypeControl: true,
        largeMapControl3D: true,
        scaleControl: true,
        overviewMapControl: true,
        center: new google.maps.LatLng(8.3190, -72.4487),
        mapTypeId: google.maps.MapTypeId.HYBRID
    }
    map = new google.maps.Map(
            document.getElementById("map_canvas"), 
            mapOptions);

	// agrega casos al mapa
	window.setTimeout(addCases, 0); 
}

function downloadUrl(url, callback) {  
    var request = window.ActiveXObject ? 
        new ActiveXObject('Microsoft.XMLHTTP') : new XMLHttpRequest;   
    request.onreadystatechange = function() {    
        if (request.readyState == 4) {            
            callback(request);    
        } 
    };   
    request.open('GET', url, true);  
    request.send(null); 
}

function addCases(refresh) {

	bounds = new google.maps.LatLngBounds();
	// leer filtros desde el formulario
	var desde = $('#inputDesde').val();
	var hasta = $('#inputHasta').val();
	var departamento = $('#departamento').val();
	var prresp = $('#prresp').val();
	var tvio = $('#tvio').val();

		
	var requestUrl = "modulos/mapag/casos_sivel_remote.php?desde=" + desde;
	requestUrl += "&hasta=" + hasta;
	if (departamento != undefined && departamento != 0) {
		requestUrl += "&departamento=" + departamento;
	}
	if (tvio != undefined && tvio!= 0) {
		requestUrl += "&tvio=" + tvio;
	}
	if (prresp != undefined && prresp != 0) {
		requestUrl += "&prresp=" + prresp;
	}
	//window.alert(requestUrl);
	
	if (refresh == true) {
		markersClusterer.length = 0;
		mc.clearMarkers();
	};
	showLoader();
	downloadUrl(requestUrl, function(req) {
        //window.alert(req);
        data = req.responseText;
        if (data == null || data.substr(0, 1) != '{') {
            hideLoader();
		    $('#nrcasos').html("0");
            window.alert("El URL " + requestUrl 
                + " no retorno información JSON.\n\n"
                + data);
            return;
        }
        var o = jQuery.parseJSON(data);
        var numResult = 0;
        for(var codigo in o) {
            numResult++;
			var lat = o[codigo].latitud;
			var lng = o[codigo].longitud;
			var titulo = o[codigo].titulo;
			var fecha = o[codigo].fecha;
			
			var point = new google.maps.LatLng(parseFloat(lat), 
                parseFloat(lng));
			var title = fecha + ": " + titulo;
			var marker = createMarker(point, codigo, title);
			markersClusterer.push(marker);
		}
		$('#nrcasos').html('(<strong>' + numResult 
                    + '</strong> casos mostrados)');
		mc = new MarkerClusterer(map, markersClusterer, mcOptions);
		showAll();
		hideLoader();
	});
}

function createMarker(point, codigo, title) {
			
    var image = new google.maps.MarkerImage("modulos/mapag/images/icon.png",
            new google.maps.Size(20,20),
            new google.maps.Point(10,5),
            new google.maps.Point(9,9));
	
    var marker = new google.maps.Marker({
        map: map,
        position: point,
        icon: image, 
        title: title,
    });
	
	// extender bounds
	bounds.extend(point);

	google.maps.event.addListener(marker, 'click', function() {
		showLoader();
        requestUrl = "modulos/mapag/caso_detalles_sivel_remote.php?codigo="  
            + codigo;
		downloadUrl(requestUrl, function(req) {
            data = req.responseText;
            //window.alert(data);
            if (data == null || data.substr(0, 1) != '{') {
                hideLoader();
                window.alert("El URL " + requestUrl 
                    + " no retorno detalles del caso\n " + data);
                return;
            }
            var o = jQuery.parseJSON(data);
			
			var id = o["caso"].id;
			var titulo = o["caso"].titulo; 
            var hechos = o["caso"].hechos; 
			var fecha = o["caso"].fecha; 
			var hora = o["caso"].hora; 
			var departamento = o["caso"].departamento; 
			var municipio = o["caso"].municipio; 
			var centro_poblado = o["caso"].centro_poblado;
			var victimas = o["caso"].victimas;
			var prresp = o["caso"].presponsables;
			
			// generar contenido
			var descripcionCont = '<div class="infowindowcont"><h3>' 
                + titulo + '</h3>' + hechos + '</div>';

            var hechosCont = '<div class="infowindowcont"><table>';
            hechosCont += (fecha != "") ? '<tr><td>Fecha:</td><td>' 
                + fecha + '</td></tr>' : '';
            hechosCont += (hora != "") ? '<tr><td>Hora:</td><td>' 
                + hora + '</td></tr>' : '';
            hechosCont += (departamento != "") ? 
                '<tr><td>Departamento:</td><td>' 
                + departamento + '</td></tr>' : '';
            hechosCont += (municipio != "") ? 
                '<tr><td>Municipio:</td><td>' 
                + municipio + '</td></tr>' : '';
            hechosCont += (centro_poblado != "") ? 
                '<tr><td>Centro Poblado:</td><td>' 
                + centro_poblado + '</td></tr>' : '';
            hechosCont += (codigo != "") ? 
                '<tr><td>Codigo:</td><td>' 
                + codigo + '</td></tr>' : '';
            hechosCont += '</table></div>';

            var victimasCont = '<div class="infowindowcont"><table>'
                + '<tr><td>Victimas:</td><td>';
            for(var cv in victimas) {
                var victima = victimas[cv];
                victimasCont += (victima != "") ? victima 
                    + '<br />' : 'SIN INFORMACIÓN';
            }
				
            victimasCont += '</td></tr><tr>'
                + '<td>Presuntos Responsables:</td><td>';
            for(var cp in prresp) {
                var prrespel = prresp[cp];
                victimasCont += (prrespel != "") ? prrespel 
                    + '<br />' : 'SIN INFORMACIÓN';
            }
            victimasCont += '</td></tr></table>';


            // infoBubble: http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobubble/examples/example.html?
            info = new InfoBubble({
                maxWidth: 400,
            });
            info.addTab('Descripción', descripcionCont);
            info.addTab('Datos', hechosCont);
            info.addTab('Víctimas', victimasCont);
            info.open(map, marker);
			
			hideLoader();
	
		});
		
        });
	return marker;
}
