// Mapa
//
// @category  SIVeL
// @author    Luca Urech <lucaurech@yahoo.de>
// @copyright 2011 Dominio público. Sin garantías.
// @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
// @version   $$
// @link      http://sivel.sf.net
//

$(function(){
	$("select#departamento").change(function(){
		$.getJSON("modulos/mapag/municipios_json.php",{id_departamento: $(this).val(), ajax: 'true'}, function(j) {
			var options = '<option value="">Mostrar todos</option><option value="">-----------------------</option>';
			if (j != null) {
			for (var i = 0; i < j.length; i++) {
				options += '<option value="' + j[i].id + '">' + j[i].name + '</option>';
			}
			$("select#municipio").html(options);
			}
		})
		if ($(this).val() == 0) {
			$("select#municipio").attr("disabled", true);
		} else {
			$("select#municipio").attr("disabled", false);
		}
	})
})
