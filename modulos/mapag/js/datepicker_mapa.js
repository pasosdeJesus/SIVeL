// Mapa
//
// @category  SIVeL
// @author    Luca Urech <lucaurech@yahoo.de>
// @copyright 2011 Dominio público. Sin garantías.
// @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
// @version   $$
// @link      http://sivel.sf.net
//

(function($){
	var initLayout = function() {
		$('.inputDesde').DatePicker({
			format:'Y-m-d',
			date: $('#inputDesde').val(),
			current: $('#inputDesde').val(),
			starts: 1,
			position: 'right',
			onBeforeShow: function(){
				$('#inputDesde').DatePickerSetDate($('#inputDesde').val(), true);
			},
			onChange: function(formated, dates){
				$('#inputDesde').val(formated);
				$('#inputDesde').DatePickerHide();
			}
		});
		
		$('.inputHasta').DatePicker({
			format:'Y-m-d',
			date: $('#inputHasta').val(),
			current: $('#inputHasta').val(),
			starts: 1,
			position: 'right',
			onBeforeShow: function(){
				$('#inputHasta').DatePickerSetDate($('#inputHasta').val(), true);
			},
			onChange: function(formated, dates){
				$('#inputHasta').val(formated);
				$('#inputHasta').DatePickerHide();
			}
		});
		
	};
	EYE.register(initLayout, 'init');
})(jQuery)
