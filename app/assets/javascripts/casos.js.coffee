# Place all the behaviors and hooks related to the matching controller here.
# All this logic will automatically be available in application.js.
# You can use CoffeeScript in this file: http://coffeescript.org/
#

#//= require cocoon

#  Completa departamento
llenaDepartamento = ($this) -> 
  idpais=$this.attr('id')
  iddep=idpais.replace('id_pais', 'id_departamento')
  idmun=idpais.replace('id_pais', 'id_municipio')
  idcla=idpais.replace('id_pais', 'id_clase')
  pais = $this.val()
  if (+pais > 0) 
      x = $.getJSON("/casos/lista", {tabla: 'departamento', id_pais: pais})
      x.done((data) -> 
          op = '<option value=""></option>'
          $.each( data, ( i, item ) -> 
              op += '<option value="' + 
                item.id + '">' + item.nombre + '</option>'
          )
          $("#" + iddep).html(op)
          $("#" + idmun).html('')
          $("#" + idcla).html('')
      )
      x.error((m1, m2, m3) -> 
          alert(
              'Problema leyendo Departamentos de ' + pais + ' ' + m1 + ' '
              + m2 + ' ' + m3)
      )
      par = {
          tabla: 'pais',
          id: pais
      }
      #if (sincoord !== true) 
      #    poneCoord(par)
      $("#" + iddep).attr("disabled", false)
  else
      $("#" + iddep).val("")
      $("#" + iddep).attr("disabled", true)
  if (idmun != '') 
      $("#" + idmun).val("")
      $("#" + idmun).attr("disabled", true)
  if (idcla != '') 
      $("#" + idcla).val("")
      $("#" + idcla).attr("disabled", true)


#  Completa municipio.
llenaMunicipio = ($this) -> 
  iddep=$this.attr('id')
  idpais=iddep.replace('id_departamento', 'id_pais')
  idmun=iddep.replace('id_departamento', 'id_municipio')
  idcla=iddep.replace('id_departamento', 'id_clase')
  dep = $this.val()
  pais = $("#" + idpais ).val()
  if (+pais > 0 && +dep > 0) 
      x = $.getJSON("/casos/lista", {tabla: 'municipio', id_departamento: dep, id_pais: pais})
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
          id_pais: pais,
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
  idpais = idmun.replace('id_municipio', 'id_pais')
  iddep = idmun.replace('id_municipio', 'id_departamento')
  idcla = idmun.replace('id_municipio', 'id_clase')
  sincoord = false
  pais = +$("#" + idpais).val();
  dep = +$("#" + iddep).val();
  mun = +$("#" + idmun).val();
  par = {
    tabla: 'clase',
    id_pais: pais,
    id_departamento: dep,
    id_municipio: mun,
  };
  if (+pais > 0 && +dep > 0 && +mun > 0) 
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
        id_pais: pais,
        id_departamento: dep,
        id: mun,
    }
  #if (sincoord != true) 
  #  poneCoord(par);
  if (pais == 0 || dep == 0 || mun == 0) 
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

  $(document).on('focusin', 'select[id^=caso_actosjr_attributes_][id$=id_presponsable]', (e) ->
    #debugger
    sel = $(this).val()
    nh = ''
    $('#presponsables>div>div[style!="display: none;"] select').each((k, v) ->
      nh = nh + "<option value='" + v.value + "'"
      if v.value == sel 
        nh = nh + ' selected'
      op = $(v).find('[value=' + v.value + ']').text()
      nh = nh + ">" + op + "</option>" )
    $(this).html(nh)
  )

  $(document).on('change', 'select[id^=caso_][id$=id_pais]', (e) ->
    llenaDepartamento($(this))
  )
  $(document).on('change', 'select[id^=caso_][id$=id_departamento]', (e) ->
    llenaMunicipio($(this))
  )
  $(document).on('change', 'select[id^=caso_][id$=id_municipio]', (e) ->
    llenaClase($(this))
  )
  
  $(document).on('cocoon:before-remove', '', (e, presponsable) ->
    root = exports ? this
    # Ingresa 2 veces, evitando duplicar
    if (root.actospe && root.actospe.length>0) 
      return
    root.actospe = []
    esel=presponsable.find('select[data-actualiza=presponsable]')
    if (esel.length > 0) 
      idp = esel.val()
      otiguales = presponsable.siblings().filter('div[class*=control-group]').filter('div[style!="display: none;"]').find('select option[selected=selected][value=' + idp + ']')
      if (otiguales.length != 0)
        return
      $('#antecedentes div[class*=caso_actosjr_presponsable] select').each((v, e) ->
        if ($(e).val() == idp) 
          root.actospe.push($(e).parent().parent());
        )
      if (root.actospe.length>0)
        r = confirm("Hay " + root.actospe.length + " causas/antecedentes que se eliminarán con este presunto responsable, ¿Continuar?")
        if (r==false)
          presponsable.data('remove-cancel', 'true')
        else
          presponsable.data('remove-cancel', 'false')
  )

  $(document).on('cocoon:after-remove', '', (e, presponsable) ->
    root = exports ? this
    for i, e of root.actospe
      l = e.find('.remove_fields')
      _cocoon_remove_fields(l)
    root.actospe = []
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
