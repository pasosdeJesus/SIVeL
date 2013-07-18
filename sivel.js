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
function llenaMunicipio(iddep, idmun) {
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
	});
	x.error(function() {
		alert('Problema leyendo Municipios');
	});
	if (dep == 0) {
		$("#" + idmun).attr("disabled", true);
	} else {
		$("#" + idmun).attr("disabled", false);
	}
}

$(function() {
 
	function sel_contacto( label, id ) {
		cs = id.split(";");
		id = parseInt(cs[0]);
		tn = parseInt(cs[1]);
		ta = parseInt(cs[2]);
		nom = label.substring(0, tn);
		ap = label.substring(tn + 1, tn + 1 + ta);

		$( "#contactoapellidos" ).val(ap); 
		$( "#contactonombres" ).val(nom); 
		$( "#idcontacto" ).val(id); 
		$( "#contactonombres" ).autocomplete("disable");
	}
 
    $( "#contactonombres" ).autocomplete({
      source: "json_persona.php",
      minLength: 2,
      select: function( event, ui ) {
	      if (ui.item) {
		      sel_contacto(ui.item.value, ui.item.id);
		      event.stopPropagation();
		      event.preventDefault();
	      }
      }
    });
});
