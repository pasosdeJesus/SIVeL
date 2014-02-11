# Place all the behaviors and hooks related to the matching controller here.
# All this logic will automatically be available in application.js.
# You can use CoffeeScript in this file: http://coffeescript.org/
#

#//= require cocoon

$(document).on 'ready page:load',  -> 
  $(document).on('cocoon:after-insert', (e) ->
    $('[data-behaviour~=datepicker]').datepicker({
      format: 'yyyy-mm-dd'
      autoclose: true
      todayHighlight: true
      language: 'es'
    })
  )
  actualiza_presponsables = ->
    # Examina objetos que dependen de presponsable, elimina los que no
    # estén y en cuadros de selección pone los de la lista.
    # Causas antecedentes
    
    sel = $(this).val()
    nh = ''
    $('#presponsables select').each((k, v) ->
      nh = nh + "<option value='" + v.value + "'"
      if v.value == sel 
        nh = nh + ' selected'
      op = $(v).find('[value=' + v.value + ']').text()
      nh = nh + ">" + op + "</option>" )
    $(this).html(nh)

  $(document).on('focusin', 'select[id^=caso_actosjr_attributes_][id$=id_presponsable]', (e) ->
    #debugger
    sel = $(this).val()
    nh = ''
    $('#presponsables select').each((k, v) ->
      nh = nh + "<option value='" + v.value + "'"
      if v.value == sel 
        nh = nh + ' selected'
      op = $(v).find('[value=' + v.value + ']').text()
      nh = nh + ">" + op + "</option>" )
    $(this).html(nh)
    return )

#  $('#victima').on('cocoon:after-insert', (e, victima) ->
#    cid = victima.find('input[id*=nombres]').attr('name')
#    re= new RegExp(".*[[]([0-9][0-9]*).*");
#    iid = cid.replace(re, "$1"); 
#    debugger )
  # Deshabilitar parte para obligar a completar partes para continuar
  # http://stackoverflow.com/questions/16777003/what-is-the-easiest-way-to-disable-enable-buttons-and-links-jquery-bootstrap
  #$('body').on('click', 'a.disabled', (e) -> 
  #  e.preventDefault() )

  # Guardar automáticamente caso nuevo cuando se editen fecha de caso o
  # de desplazamiento
  
  # Método para detectar cambios en datepicker de
  # http://stackoverflow.com/questions/17009354/detect-change-to-selected-date-with-bootstrap-datepicker
  #$('#caso_fecha').datepicker({
  #  format: 'yyyy-mm-dd'
  #  autoclose: true
  #  todayHighlight: true
  #  language: 'es'
  #}).on('changeDate', (ev) ->
  #  $("article").css("cursor", "wait")
  #  $(this).parents("form").submit() 
  #  $("article").css("cursor", "default") );

#  $('#caso_fecha').change( () ->
#    console.log($('#date-daily').val()) );

#  $('#caso_fecha').on('change', ->
# )


