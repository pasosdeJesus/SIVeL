# Place all the behaviors and hooks related to the matching controller here.
# All this logic will automatically be available in application.js.
# You can use CoffeeScript in this file: http://coffeescript.org/
#

#//= require cocoon

#  Completa municipio.
llenaMunicipio = ($this) -> 
  iddep=$this.attr('id')
  idmun=iddep.replace('id_departamento', 'id_municipio')
  idcla=iddep.replace('id_departamento', 'id_clase')
  dep = $this.val()
  if (+dep > 0) 
      x = $.getJSON("/casos/lista", {tabla: 'municipio', id_departamento: dep})
      x.done((data) -> 
          op = '<option value=""></option>'
          $.each( data, ( i, item ) -> 
              op += '<option value="' + 
                item.id + '">' + item.nombre + '</option>'
          )
          $("#" + idmun ).html(op)
          $("#" + idcla).html('')
      )
      x.error((m1, m2, m3) -> 
          alert(
              'Problema leyendo Municipios de ' + dep + ' ' + m1 + ' '
              + m2 + ' ' + m3)
      )
      par = {
          tabla: 'departamento',
          id: dep
      }
      #if (sincoord !== true) 
      #    poneCoord(par)
      $("#" + idmun).attr("disabled", false)
  else
      $("#" + idmun).val("")
      $("#" + idmun).attr("disabled", true)
  if (idcla != '') 
      $("#" + idcla).val("")
      $("#" + idcla).attr("disabled", true)

# Completa cuadro de selección para clase de acuerdo a depto y mcpio.
llenaClase = ($this) -> 
  iddep = "id_departamento"
  idcla = "id_clase"
  idmun = $this.attr('id')
  iddep=iddep.replace('id_municipio', 'id_departamento')
  idcla=iddep.replace('id_municipio', 'id_clase')
  sincoord = false
  dep = +$("#" + iddep).val();
  mun = +$("#" + idmun).val();
  par = {
    tabla: 'clase',
    id_departamento: dep,
    id_municipio: mun,
  };
  x = $.getJSON("/casos/lista", par)
  x.done( ( data ) ->
    op = '<option value=""></option>';
    $.each( data, ( i, item ) ->
      op += '<option value="' + item.id + '">' + item.nombre + '</option>';
    )
    $("#" + idcla).html(op);
  )
  x.error( (m1, m2, m3) ->
    alert('Problema leyendo Clase ' + x + m1 + m2 + m3)
  )
  par = {
      tabla: 'municipio',
      id_departamento: dep,
      id: mun,
  }
  #if (sincoord != true) 
  #  poneCoord(par);
  if (dep == 0 || mun == 0) 
    $("#" + idcla).attr("disabled", true);
  else 
    $("#" + idcla).attr("disabled", false);


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
  )

  $(document).on('change', 'select[id^=caso_ubicacion_attributes_][id$=id_departamento]', (e) ->
    llenaMunicipio($(this))
  )
  $(document).on('change', 'select[id^=caso_ubicacion_attributes_][id$=id_municipio]', (e) ->
    llenaClase($(this))
  )
 
  $('#presponsable').on('cocoon:after-delete', (e, presponsable) ->
    debugger 
    cid = presponsable.find('input[id*=nombres]').attr('name')
    re= new RegExp(".*[[]([0-9][0-9]*).*")
    iid = cid.replace(re, "$1") 
  )
  
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
  return
