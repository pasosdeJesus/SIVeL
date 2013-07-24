/** Funciones varias en JavaScript 
 * @author Vladimir Támara Patiño. vtamara@pasosdeJesus.org. 2007. 
 * Dominio público.
 */

/* Esta función toma ídeas de https://linea.davivienda.com/funciones.js
   que a su vez parece inspirado en 
	http://developer.mozilla.org/en/docs/DOM:window.open
   Documentación:
	http://developer.mozilla.org/en/docs/Core_JavaScript_1.5_Guide
 */
function abrirBusquedaPersona(rol) {
	var n = document.getElementById('nombres-' + rol).value;
	var a = document.getElementById('apellidos-' + rol).value;
	window.open("buscarPersona.php?rol=" + rol + 
			"&nombres=" + encodeURIComponent(n) +
			"&apellidos=" + encodeURIComponent(a),
		"buscarPersona", 
		"resizable=yes,scrollbars=yes,status=yes,width=400");
}


function abrirBusquedaGrupoper() 
{
    var n = document.getElementById('nombre').value;
	window.open("buscarGrupo.php?nombre=" + encodeURIComponent(n),
			"buscarGrupo", 
			"resizable=yes,scrollbars=yes,status=yes,width=400");
}


/** Envia datos de una persona a ventana con ficha */
function enviar_persona(rol, id, nombres, apellidos, anionac, mesnac, 
	dianac, sexo, id_departamento, id_municipio, id_clase, 
	tipodocumento, numerodocumento) {

	if (rol == 'persona') {
		forma = window.opener.document.getElementById('victimaIndividual');
		//alert(forma);
		nuevo = forma.id.value == '';
		forma.id.value = id;
		forma.tipodocumento.value = tipodocumento;
		forma.numerodocumento.value = numerodocumento;
		forma.nombres.value = nombres;
		forma.apellidos.value = apellidos;
		forma.anionac.value = anionac;
		forma.mesnac.value = mesnac;
		forma.dianac.value = dianac;
		forma.sexo.value = sexo;
		forma.id_departamento.value = id_departamento;
		forma.id_municipio.value = id_municipio;
		forma.id_clase.value = id_clase;
		forma._qf_default = 'pResponsables:siguiente';
	} else {
		forma = window.opener.document.getElementById('victimaIndividual');
		//alert(forma);
		nuevo = forma.persona2.value == '';
		forma.persona2.value = id;
		forma.fnombres.value = nombres;
		forma.fapellidos.value = apellidos;
		forma._qf_default = 'pResponsables:siguiente';
	}
	
	if (!nuevo) {
		forma.submit();
	} else {
		//echo "Si tiene familiares aparecerán despúes de enviar este formulario";
	}
	window.close();
}

function enviar_grupoper(id, nombre, anotaciones)
{
	forma = window.opener.document.getElementById('victimaColectiva');
	//alert(forma);
	nuevo = forma.id.value == '';
	forma.id.value = id;
	forma.nombre.value = nombre;
	forma.anotaciones.value = anotaciones;
	forma._qf_default = 'pResponsables:siguiente';
	/*h=document.createElement("h1");
	h.appendChild(document.createElement('x'));
	forma.appenChild(h); */
	
	if (!nuevo) {
		forma.submit();
	}
	else {
		//echo "Si tiene familiares aparecerán despúes de enviar este formulario";
	}
	window.close();
}

// Basada en función de Luca Urech <lucaurech@yahoo.de>
function llenaMunicipio(iddep, idmun, idcla) {
	var dep = $("#" + iddep).val();
	var par = { 
		id_departamento: dep
       	};
	var x = $.getJSON("json_municipios.php", par);
	x.done(function( data ) {
		var op = '<option value=""></option>';
		$.each( data, function ( i, item ) {
			op += '<option value="' 
			+ item.id + '">' + item.nombre
			+ '</option>';
		});
		$("#" + idmun ).html(op);
		$("#" + idcla).html('');
	});
	x.error(function(m1, m2, m3) {
		alert('Problema leyendo Municipios' + m1 + m2 + m3);
	});
	if (idcla != '') {
		$("#" + idcla).attr("disabled", true);
	}
	if (dep == 0) {
		$("#" + idmun).attr("disabled", true);
	} else {
		$("#" + idmun).attr("disabled", false);
	}
}

// Completa cuadro de selección para clase de acuerdo a depto y muncpio.
function llenaClase(iddep, idmun, idcla) {
	var dep = +$("#" + iddep).val();
	var mun = +$("#" + idmun).val();
	var par = { 
		id_departamento: dep,
		id_municipio: mun,
       	};
	var x = $.getJSON("json_clases.php", par);
	x.done(function( data ) {
		var op = '<option value=""></option>';
		$.each( data, function ( i, item ) {
			op += '<option value="' 
			+ item.id + '">' + item.nombre
			+ '</option>';
		});
		$("#" + idcla).html(op);
	});
	x.error(function(m1, m2, m3) {
		alert('Problema leyendo Clase ' + x + m1 + m2 + m3);
	});
	if (dep == 0 || mun == 0) {
		$("#" + idcla).attr("disabled", true);
	} else {
		$("#" + idcla).attr("disabled", false);
	}
}

// Elije una persona
function sel_contacto( label, id, urls, cnom, cape, cdoc, ccasos, cid) {
	cs = id.split(";");
	var pl = [];
	var ini = 0;
	for(var i=1; i < cs.length; i++) {
		t = parseInt(cs[i]);
		pl[i] = label.substring(ini, ini + t);
		ini = ini + t + 1;
	}

	$("#" + cnom).val(pl[1]).attr('disabled', true); 
	$("#" + cape).val(pl[2]).attr('disabled', true); 
	$("#" + cdoc).val(pl[3]).attr('disabled', true); 
	$("#" + ccasos).html(urls); 
	$("#" + cid).val(cs[0]); 
	$("#" + cnom).autocomplete("disable");
}

// Activa completación por nombre, apellido e identificación de persona
function autocompleta_persona(cnom, cape, cdoc, ccasos, cid) {
	var v = $("#" + cnom).data('autocompleta');
	if (v != 1) {
		$("#" + cnom).data('autocompleta', 1);
		$("#" + cnom).autocomplete({
			source: "json_persona.php",
			minLength: 2,
			select: function( event, ui ) {
				if (ui.item) {
					sel_contacto(ui.item.value, ui.item.id, 
						ui.item.urls,
						cnom, cape, cdoc, ccasos, cid);
					event.stopPropagation();
					event.preventDefault();
				}
			}
		});
	}
}

