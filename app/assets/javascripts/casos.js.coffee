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


# Elimina secciones agregadas con cocoon listadas en elempe
eliminaPendientes = (elempe) ->
  for i, e of elempe
    l = e.find('.remove_fields')
    _cocoon_remove_fields(l)


$(document).on 'ready page:load',  -> 

  root = exports ? this

  $(document).on('cocoon:after-insert', (e) ->
    $('[data-behaviour~=datepicker]').datepicker({
      format: 'yyyy-mm-dd'
      autoclose: true
      todayHighlight: true
      language: 'es'
    })
  )

  # En actos, lista de presuntos responsables se calcula
  $(document).on('focusin', 'select[id^=caso_actosjr_attributes_][id$=id_presponsable]', (e) ->
    #debugger
    sel = $(this).val()
    nh = ''
    lcg = $('#presponsable .control-group[style!="display: none;"]')
    lcg.each((k, v) ->
      id = $(v).find('select[data-actualiza=presponsable]').val()
      nh = nh + "<option value='" + id + "'"
      if id == sel 
        nh = nh + ' selected'
      tx = $(v).find('select[data-actualiza=presponsable] option[value=' + id + ']').text()
      nh = nh + ">" + tx + "</option>" )
    $(this).html(nh)
  )

  # En actos, lista de víctimas se cálcula
  $(document).on('focusin', 'select[id^=caso_actosjr_attributes_][id$=id_persona]', (e) ->
    sel = $(this).val()
    nh = ''
    c = $('#contacto')
    lcg = c.add('#victima .control-group[style!="display: none;"]')
    lcg.each((k, v) ->
      # id: persona
      # Nos gustaría 
      # id = $(v).find('.caso_victima_persona_id input').val()
      # pero como nombre de clase genera caso_victima_279_persona_id
      id = $(v).find('div').filter( () -> this.attributes.class.value.match(/caso_victima[_0-9]*persona_id/)).find('input').val()
      nh = nh + "<option value='" + id + "'"
      if id == sel 
        nh = nh + ' selected'
      # texto: nombres apellidos
      nom = $(v).find('div').filter( () -> this.attributes.class.value.match(/caso_victima[_0-9]*persona_nombres/)).find('input').val()
      nom = $(v).find('.caso_victima_persona_apellidos input').val()
      ap = $(v).find('div').filter( () -> this.attributes.class.value.match(/caso_victima[_0-9]*persona_apellidos/)).find('input').val()
      tx = (nom + " " + ap).trim()
      nh = nh + ">" + tx + "</option>" )
    $(this).html(nh)
  )

  # En actos, lista de desplazamientos se cálcula
  $(document).on('focusin', 'select[id^=caso_actosjr_attributes_][id$=fechaexpulsion]', (e) ->
    sel = $(this).val()
    nh = '<option value=""></option>'
    lcg = $('#desplazamiento .control-group[style!="display: none;"]')
    lcg.each((k, v) ->
      # id: fechaexpulsion
      id = $(v).find('.caso_desplazamiento_fechaexpulsion input').val()
      nh = nh + "<option value='" + id + "'"
      if id == sel 
        nh = nh + ' selected'
      # texto: fechaexpulsion
      tx = id
      nh = nh + ">" + tx + "</option>" )
    $(this).html(nh)
  )

  # En sesiones de atención, lista de desplazamientos se cálcula
  $(document).on('focusin', 'select[id^=caso_respuesta_attributes_][id$=fechaexpulsion]', (e) ->
    sel = $(this).val()
    nh = ''
    lcg = $('#desplazamiento .control-group[style!="display: none;"]')
    lcg.each((k, v) ->
      # id: fechaexpulsion
      id = $(v).find('.caso_desplazamiento_fechaexpulsion input').val()
      nh = nh + "<option value='" + id + "'"
      if id == sel 
        nh = nh + ' selected'
      # texto: fechaexpulsion
      tx = id
      nh = nh + ">" + tx + "</option>" )
    $(this).html(nh)
  )


  # En desplazamientos, lista de sitios de expulsión se cálcula
  $(document).on('focusin', 'select[id^=caso_desplazamiento_attributes_][id$=id_expulsion]', (e) ->
    sel = $(this).val()
    nh = ''
    lcg = $('#ubicacion .control-group[style!="display: none;"]')
    lcg.each((k, v) ->
      # id: ubicacion
      id = $(v).find('.caso_ubicacion_id input').val()
      nh = nh + "<option value='" + id + "'"
      if id == sel 
        nh = nh + ' selected'
      idp = $(v).find('.caso_ubicacion_pais select').val()
      tx = $(v).find('.caso_ubicacion_pais select option[value=' + idp + ']').text()
      idd = $(v).find('.caso_ubicacion_departamento select').val()
      if (idd > 0)
        tx = tx + " / " + $(v).find('.caso_ubicacion_departamento select option[value=' + idd + ']').text()
      nh = nh + ">" + tx + "</option>" )
    $(this).html(nh)
  )

  # En desplazamientos, lista de sitios de llegada se cálcula
  $(document).on('focusin', 'select[id^=caso_desplazamiento_attributes_][id$=id_llegada]', (e) ->
    sel = $(this).val()
    nh = ''
    lcg = $('#ubicacion .control-group[style!="display: none;"]')
    lcg.each((k, v) ->
      # id: ubicacion
      id = $(v).find('.caso_ubicacion_id input').val()
      nh = nh + "<option value='" + id + "'"
      if id == sel 
        nh = nh + ' selected'
      idp = $(v).find('.caso_ubicacion_pais select').val()
      tx = $(v).find('.caso_ubicacion_pais select option[value=' + idp + ']').text()
      idd = $(v).find('.caso_ubicacion_departamento select').val()
      if (idd > 0)
        tx = tx + " / " + $(v).find('.caso_ubicacion_departamento select option[value=' + idd + ']').text()
      nh = nh + ">" + tx + "</option>" )
    $(this).html(nh)
  )

  # Al cambiar país se recalcula lista de departamentos
  $(document).on('change', 'select[id^=caso_][id$=id_pais]', (e) ->
    llenaDepartamento($(this))
  )

  # Al cambiar departamento se recalcula lista de municipios
  $(document).on('change', 'select[id^=caso_][id$=id_departamento]', (e) ->
    llenaMunicipio($(this))
  )

  # Al cambiar municipio se recalcula lista de centros poblados
  $(document).on('change', 'select[id^=caso_][id$=id_municipio]', (e) ->
    llenaClase($(this))
  )

  # Antes de eliminar presponsable confirmar si se eliminan dependientes
  $('#presponsable').on('cocoon:before-remove', '', (e, papa) ->
    # Ingresa 2 veces, evitando duplicar
    if (root.elempe && root.elempe.length>0) 
      return
    root.elempe = []
    esel=papa.find('select[data-actualiza=presponsable]')
    if (esel.length > 0) 
      idp = esel.val()
      otiguales = papa.siblings().filter('div[class*=control-group]').filter('div[style!="display: none;"]').find('select option[selected=selected][value=' + idp + ']')
      if (otiguales.length != 0)
        return
      nomelempe = "causas/antecedentes"
      nomesteelem = "este presunto responsable"
      $('#antecedentes .control-group[style!="display: none;"] .caso_actosjr_presponsable select').each((v, e) ->
        if ($(e).val() == idp) 
          root.elempe.push($(e).parent().parent());
      )
       
    if (root.elempe.length>0)
      r = confirm("Hay " + root.elempe.length + " " + nomelempe + 
        " que se eliminarán con " + nomesteelem + ", ¿Continuar?")
      if (r==false)
        papa.data('remove-cancel', 'true')
      else
        papa.data('remove-cancel', 'false')
  )

  # Tras eliminar presponsable, eliminar dependientes
  $('#presponsable').on('cocoon:after-remove', '', (e, presponsable) ->
    eliminaPendientes(root.elempe);
    root.elempe = []
  )
 
  # Antes de eliminar víctima confirmar si se eliminan dependientes
  $('#victima').on('cocoon:before-remove', '', (e, papa) ->
    # Ingresa 2 veces, evitando duplicar
    if (root.elempe && root.elempe.length>0) 
      return
    root.elempe = []
    vsel=papa.find('.caso_victima_persona_id input')
    if (vsel.length>0)
      idv = vsel.val()
      nomelempe = "causas/antecedentes"
      nomesteelem = "esta víctima"
      $('#antecedentes .control-group[style!="display: none;"] .caso_actosjr_persona select').each((v, e) ->
        if ($(e).val() == idv) 
          root.elempe.push($(e).parent().parent());
      )
       
    if (root.elempe.length>0)
      r = confirm("Hay " + root.elempe.length + " " + nomelempe + 
        " que se eliminarán con " + nomesteelem + ", ¿Continuar?")
      if (r==false)
        papa.data('remove-cancel', 'true')
      else
        papa.data('remove-cancel', 'false')
  )

  # Tras eliminar víctima, eliminar dependientes
  $('#victima').on('cocoon:after-remove', '', (e, presponsable) ->
    eliminaPendientes(root.elempe);
    root.elempe = []
  )
 
  # Antes de eliminar ubicacion confirmar si se eliminan dependientes
  $('#ubicacion').on('cocoon:before-remove', (e, papa) ->
    # Si ingresa más de una vez se evita duplicar
    if (root.elempe && root.elempe.length>0) 
      return
    root.elempe = []
    usel=papa.find('.caso_ubicacion_id input')
    if (usel.length>0)
      id = usel.val()
      nomelempe = "desplazamientos"
      nomesteelem = "este sitio geográfico"
      $('#desplazamiento .control-group[style!="display: none;"] .caso_desplazamiento_expulsion select').each((v, e) ->
        if ($(e).val() == id) 
          root.elempe.push($(e).parent().parent());
      )
      $('#desplazamiento .control-group[style!="display: none;"] .caso_desplazamiento_llegada select').each((v, e) ->
        if ($(e).val() == id) 
          root.elempe.push($(e).parent().parent());
      )
       
    if (root.elempe.length>0)
      r = confirm("Hay " + root.elempe.length + " " + nomelempe + 
        " que se eliminarán con " + nomesteelem + ", ¿Continuar?")
      if (r==false)
        papa.data('remove-cancel', 'true')
      else
        papa.data('remove-cancel', 'false')
  )

  # Tras eliminar ubicacion, eliminar dependientes
  $('#ubicacion').on('cocoon:after-remove', (e, papa) ->
    eliminaPendientes(root.elempe);
    root.elempe = []
  )
 
  # Antes de eliminar desplazamiento confirmar si se eliminan dependientes
  $('#desplazamiento').on('cocoon:before-remove', (e, papa) ->
    # Si ingresa más de una vez se evita duplicar
    if (root.elempe && root.elempe.length>0) 
      return
    root.elempe = []
    usel=papa.find('.caso_desplazamiento_fechaexpulsion input')
    if (usel.length>0)
      id = usel.val()
      nomelempe = "causas/antecedentes"
      nomesteelem = "este desplazamiento"
      $('#antecedentes .control-group[style!="display: none;"] .caso_actosjr_desplazamiento select').each((v, e) ->
        if ($(e).val() == id) 
          root.elempe.push($(e).parent().parent());
      )
      if (root.elempe.length>0)
        r = confirm("Hay " + root.elempe.length + " " + nomelempe + 
          " que se eliminarán con " + nomesteelem + ", ¿Continuar?")
        if (r==false)
          papa.data('remove-cancel', 'true')
        else
          papa.data('remove-cancel', 'false')
      lelempe = root.elempe.length
      nomelempe = "sesionesantecedentes"
      nomesteelem = "este desplazamiento"
      $('#ayudasjr .control-group[style!="display: none;"] .caso_respuesta_desplazamiento select').each((v, e) ->
        if ($(e).val() == id) 
          root.elempe.push($(e).parent().parent());
      )

    if (root.elempe.length > lelempe)
      r = confirm("Hay " + root.elempe.length + " " + nomelempe + 
        " que se eliminarán con " + nomesteelem + ", ¿Continuar?")
      if (r==false)
        papa.data('remove-cancel', 'true')
  )

  # Tras eliminar desplazamiento, eliminar dependientes
  $('#desplazamiento').on('cocoon:after-remove', (e, papa) ->
    eliminaPendientes(root.elempe);
    root.elempe = []
  )
 

  # Deshabilitar parte para obligar a completar partes para continuar
  # http://stackoverflow.com/questions/16777003/what-is-the-easiest-way-to-disable-enable-buttons-and-links-jquery-bootstrap
  #$('body').on('click', 'a.disabled', (e) -> 
  #  e.preventDefault() )

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

  return
