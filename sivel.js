// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/** 
 * Funciones varias en JavaScript 
 * @author Vladimir Támara Patiño. vtamara@pasosdeJesus.org. 2007. 
 * Dominio público.
 */


// PESTAÑA VÍCTIMA INDIVIDUAL

/* 
 * Abre ventana para elegir persona.
 * Esta función toma ídeas de https://linea.davivienda.com/funciones.js
 *  que a su vez parece inspirado en 
 *	http://developer.mozilla.org/en/docs/DOM:window.open
 *  Documentación:
 *	http://developer.mozilla.org/en/docs/Core_JavaScript_1.5_Guide
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


/*
 * Abre ventana para elegir grupo de personas
 */
function abrirBusquedaGrupoper() 
{
    var n = document.getElementById('nombre').value;
    window.open("buscarGrupo.php?nombre=" + encodeURIComponent(n),
            "buscarGrupo", 
            "resizable=yes,scrollbars=yes,status=yes,width=400");
}

/** 
 * Envia datos de una persona a ventana con ficha 
 */
function enviarPersona(rol, id, nombres, apellidos, anionac, mesnac, 
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

/** 
 * Envia datos de un grupo de personas a ventana con ficha 
 */
function enviarGrupoPer(id, nombre, anotaciones)
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

var aniocaso;
var mescaso;
var diacaso;
var anioactual;
var mesactual;
var diaactual;
var rangoedad = [];

/** 
 * Pone en blanco fecha de nacimiento y edades
 */
function limpiarFechaNac()
{
	$("[name='anionac']").val('');
	$("[name='mesnac']").val('');
	$("[name='dianac']").val('');
	$("[name='edadactual']").val('');
	$("[name='edad']").val('');
}

/**
 * Retorna cantidad de años entre la fecha de nacimiento y
 * la fecha del hecho.
 *
 * Convertido de misc.php
 */
function edadDeFechaNac(anioref, mesref, diaref)
{
	var anionac= +$("[name='anionac']").val();
	var mesnac= +$("[name='mesnac']").val();
	var dianac= +$("[name='dianac']").val();

	//alert("OJO edad_de_fechanac anionac=" + anionac + ", anioref=" + anioref+ ", mesnac=" + mesnac + ", mesref=" + mesref+ ", dianac=" + dianac + ", diaref=" + diaref);
	if (anionac == '') {
		return -1;
	}
	na = anioref-anionac;
	if (mesnac != undefined && mesnac != '' && mesnac > 0
			&& mesref != undefined && mesref!= '' && mesref > 0
			&& mesnac <= mesref) {
		if (mesnac < mesref || (dianac != undefined && dianac != '' 
                    && dianac > 0 && diaref != undefined && diaref!= '' 
                    && diaref > 0 && dianac < diaref)
		   ) {
			na--;
		}
	}
	return na;
}

/**
 * Establece rango de edad
 */
function ponerRangoEdad() {
	var r = $("[name='id_rangoedad']");
	var e = $("[name='edad']").val();
	var sin = -1;
	var res = -1;
    	for (var i in rangoedad) {
		if (+rangoedad[i].limiteinferior == -1) {
			sin = i;
		} else if (e != '' && +rangoedad[i].limiteinferior <= e 
				&& e <= +rangoedad[i].limitesuperior) {
			res = i;
		} 
	}
	if (res == -1) {
		res = sin;
	}
	r.val(res);
	if (e == '') {
		r.prop('readonly', false);
	} else {
		r.prop('readonly', true);
	}
}



// UBICACIÓN


// Llena coordenada con datos de latitud y longitud genericos de
// acuerdo al departamento, municipio o clase suministrados en par
function poneCoord(par) {
    var lat = $("[name='latitud']");
    var lon = $("[name='longitud']");
    if (lat.length > 0 && lon.length > 0) {
        var y = $.getJSON("json_busca.php", par);
        y.done(function( data ) {
            if (data.length > 0) {
                var d = data.pop();
                nla = +d.latitud + Math.random()/1000-0.0005
                lat.val(nla);
                nlo = +d.longitud + Math.random()/1000-0.0005
                lon.val(nlo);
            }
        });
        y.error(function(m1, m2, m3) {
            ar = "";
            sep = "";
            for(var i in par) {
                ar = ar + sep + i + ":" + par[i];
                sep = ", ";
            }
            alert('Problema leyendo ' + ar + ". " + m1 + ' ' + m2 + ' ' + m3);
        });
    }
}


/**
 * Completa municipio
 * Basada en función de Luca Urech <lucaurech@yahoo.de>
 */
function llenaMunicipio(iddep, idmun, idcla) {
    var dep = $("#" + iddep).val();
    var par = { 
        tabla: 'municipio',
        id_departamento: dep
    };
    if (+dep > 0) {
        var x = $.getJSON("json_busca.php", par);
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
            alert(
                'Problema leyendo Municipios de ' + dep + ' ' + m1 + ' ' 
                + m2 + ' ' + m3
                );
        });
        par = { 
            tabla: 'departamento',
            id: dep
        };
        poneCoord(par);
        $("#" + idmun).attr("disabled", false);
    } else  {
        $("#" + idmun).val("");
        $("#" + idmun).attr("disabled", true);
    }
    if (idcla != '') {
        $("#" + idcla).val("");
        $("#" + idcla).attr("disabled", true);
    }
}


/** 
 * Completa cuadro de selección para clase de acuerdo a depto y mcpio.
 */
function llenaClase(iddep, idmun, idcla) {
	var dep = +$("#" + iddep).val();
	var mun = +$("#" + idmun).val();
	var par = { 
        tabla: 'clase',
		id_departamento: dep,
		id_municipio: mun,
       	};
	var x = $.getJSON("json_busca.php", par);
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
    par = { 
        tabla: 'municipio',
        id_departamento: dep,
        id: mun,
    };
    poneCoord(par);
	if (dep == 0 || mun == 0) {
		$("#" + idcla).attr("disabled", true);
	} else {
		$("#" + idcla).attr("disabled", false);
	}
}


/** 
 * Completa latitud y longitud a partir de departamento/municipio/clase
 * dados por el usuario
 */
function llenaCoord(iddep, idmun, idcla) {
	var dep = $("#" + iddep).val();
    var mun = +$("#" + idmun).val();
	var cla = +$("#" + idcla).val();
    par = { 
        tabla: 'clase',
        id_departamento: dep,
        id_municipio: mun,
        id: cla,
    };
    // Por el momento no ponemos coordenadas en caso de clase porque 
    // falta la información de dominio público de ubicación de clases 
    // poneCoord(par);
}

// AUTOCOMPLETACIÓN PERSONA

/**
 * Elije una persona
 */
function selContacto( label, id, urls, cnom, cape, cdoc, ccasos, cid) {
	cs = id.split(";");
	var pl = [];
	var ini = 0;
	for(var i=1; i < cs.length; i++) {
		t = parseInt(cs[i]);
		pl[i] = label.substring(ini, ini + t);
		ini = ini + t + 1;
	}

	$("#" + cnom).val(pl[1]).attr('readonly', true); 
	$("#" + cape).val(pl[2]).attr('readonly', true); 
	$("#" + cdoc).val(pl[3]).attr('readonly', true); 
	$("#" + ccasos).html(urls); 
	$("#" + cid).val(cs[0]); 
	$("#" + cnom).autocomplete("disable");
}

/** 
 * Activa completación por nombre, apellido e identificación de persona
 */
function autocompletaPersona(cnom, cape, cdoc, ccasos, cid) {
	var v = $("#" + cnom).data('autocompleta');
	if (v != 1) {
		$("#" + cnom).data('autocompleta', 1);
		$("#" + cnom).autocomplete({
			source: "json_persona.php",
			minLength: 2,
			select: function( event, ui ) {
				if (ui.item) {
					selContacto(ui.item.value, ui.item.id, 
						ui.item.urls,
						cnom, cape, cdoc, ccasos, cid);
					event.stopPropagation();
					event.preventDefault();
				}
			}
		});
	}
}



// INICIALIZACIÓN GENERAL

$( document ).ready(function () {

    if ($("[name='aniocaso']").length > 0) {
        var aniocaso = $("[name='aniocaso']").val();
        var mescaso = $("[name='mescaso']").val();
        var diacaso = $("[name='diacaso']").val();
        var anioactual = $("[name='anioactual']").val();
        var mesactual = $("[name='mesactual']").val();
        var diaactual = $("[name='diaactual']").val();
        var par = { 
            tabla: 'rangoedad'
        };
        var x = $.getJSON("json_busca.php", par);
        x.done(function( data ) {
            $.each( data, function ( i, item ) {
                rangoedad[item.id] = {
                    limiteinferior: item.limiteinferior,
                limitesuperior: item.limitesuperior,
                }
            });
        });
        x.error(function(m1, m2, m3) {
            alert('Problema leyendo Rangos de edad ' + m1 + m2 + m3);
        });
        $("[name='anionac']").on('change', function (event) {
            anionac = $(this).val();
            if (anionac == '') {
                limpiarFechaNac();
            } else {
                $("[name='edad']").val(
                    edadDeFechaNac(aniocaso, mescaso, diacaso));
                $("[name='edadactual']").val(edadDeFechaNac(
                        anioactual, mesactual, diaactual
                        ));
            }
            ponerRangoEdad();
        });
        $("[name='mesnac']").on('change', function (event) {
            $("[name='edad']").val(
                edadDeFechaNac(aniocaso, mescaso, diacaso));
            $("[name='edadactual']").val(
                edadDeFechaNac(anioactual, mesactual, diaactual));
            ponerRangoEdad();
        });
        $("[name='dianac']").on('change', function (event) {
            $("[name='edad']").val(
                edadDeFechaNac(aniocaso, mescaso, diacaso));
            $("[name='edadactual']").val(
                edadDeFechaNac(anioactual, mesactual, diaactual));
            ponerRangoEdad();
        });
        $("[name='edad']").on('change', function (event) {
            var edad = $(this).val();
            if (edad == '') {
                limpiarFechaNac();
            } else {
                $("[name='anionac']").val((+aniocaso) - (+edad));
                $("[name='mesnac']").val('');
                $("[name='dianac']").val('');
                $("[name='edadactual']").val(edadDeFechaNac(
                        anioactual, mesactual, diaactual));
            }
            ponerRangoEdad();
        });
        $("[name='edadactual']").on('change', function (event) {
            var edadactual = $(this).val();
            if (edadactual == '') {
                limpiarFechaNac();
            } else {
                $("[name='anionac']").val((+anioactual) 
                    - (+edadactual));
                $("[name='mesnac']").val('');
                $("[name='dianac']").val('');
                $("[name='edad']").val(
                    edadDeFechaNac(aniocaso, mescaso, diacaso));
            }
            ponerRangoEdad();
        });
    }
});
