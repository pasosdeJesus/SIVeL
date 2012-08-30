s/^\([^\/"']*[a-zA-Z)]\)=\([-"'A-Za-z0-9$(]\)/\1 = \2/g
s/\(\]\)=\([$a-zA-Z']\)/\1 = \2/g
s/foreach(/foreach (/g
s/\$CVS:/$Id:/g
s/require_once(\(.*\))/require_once \1/g
s/	/    /g
s/not_null primary_key multiple_key/multiple_key/g
s/DominioPúblico/http:\/\/creativecommons.org\/licenses\/publicdomain\/ Dominio Público/g
s/}\n\( *\) else {/} else {/g
s/\([a-z]\)!=\([a-z"'$0-9]\)/\1 != \2/g
s/\(\]\)!=\([$"'a-z0-9]\)/\1 != \2/g
s/\([a-z]\)==\([a-z"'$0-9]\)/\1 == \2/g
s/\(\]\)==\([$"'a-z0-9]\)/\1 == \2/g
s/\(^[^"'*]*[a-z0-9]\)<\([a-z$0-9]\)/\1 < \2/g
s/\(\]\)<\([a-z0-9$]\)/\1 < \2/g
s/\(^[^"'*]*[a-z0-9]\)>\([a-z$0-9]\)/\1 > \2/g
s/\(\]\)>\([a-z0-9$]\)/\1 > \2/g
s/\([a-z0-9]\)<=\([a-z$0-9]\)/\1 <= \2/g
s/\(\]\)<=\([a-z0-9$]\)/\1 <= \2/g
s/\([a-z0-9]\)>=\([a-z$0-9]\)/\1 >= \2/g
s/\(\]\)>=\([a-z0-9$]\)/\1 >= \2/g
s/function \([A-Za-z0-9_]*\)(  *\([^)]*\) *)/function \1(\2)/g
s/function \([A-Za-z0-9_]*\)( *\([^)]*\)  *)/function \1(\2)/g
s/mt_rand(0,1000)/mt_rand(0, 1000)/g
s/TRUE/true/g
s/FALSE/false/g
s/while *(/while (/g
s/if *(/if (/g
s/\(function [A-Za-z0-9_]*\)  *(/\1(/g
s/( /(/g
s/\([a-z0-5]\) )/\1)/g
s/(\(.*[a-zA-Z]\),\([$a-zA-Z0-9] \)/(\1, \2/g
s/)  *{/) {/g
s/\((.*[^ '"]\),\([^ '"$(]+\)$/\1, \2/g
s/for(/for (/g
s/for   *(/for (/g
s/)  *{/) {/g
s/\([^ ]\)[.]=/\1 .=/g
s/[.]=\([^ ]\)/.= \1/g
s/\([^ ]\)[.]\(\$[A-Za-z_0-9]*\)[.]\([^ ]\)/\1 . \2 . \3/g
s/^\([^"]*[^ ]\)[.]\(\$[A-Za-z_0-9]*\)/\1 . \2/g
s/^\([^"]*\$[A-Za-z_0-9]*\)[.]\([$"']\)/\1 . \2/g
s/\(\]\)[.]\([$"']\)/\1 . \2/g
s/\((.*[^ ]\),\([^'" ]+\)$/\1, \2/g
s/\(["']\)[.]$/\1 ./g
s/ini_captura/iniCaptura/g
s/resconsulta_iniciotabla/resConsultaInicioTabla/g
s/resConsultaIniciotabla/resConsultaInicioTabla/g
s/resConsultaIniciotabla/resConsultaInicioTabla/g
s/GLOBALS\['resconsulta_inicio'/GLOBALS['gancho_rc_inicio'/g
s/GLOBALS\['resConsultaInicio'/GLOBALS['gancho_rc_inicio'/g
s/resconsulta_registro/resConsultaRegistro/g
s/GLOBALS\['resConsultaRegistro'/GLOBALS['gancho_rc_registro'/g
s/resconsulta_final/resConsultaFinal/g
s/GLOBALS\['resConsultaFinal'/GLOBALS['gancho_rc_final'/g
s/resconsulta_filatabla/resConsultaFilaTabla/g
s/resconsulta_finaltabla/resConsultaFinalTabla/g
s/consultaweb_filtro/consultaWebFiltro/g
s/resconsulta_finaltabla/resConsulta_finalTabla/g
s/consultaweb_creaconsulta/consultaWebCreaConsulta/g
s/consultaweb_formapresentacion/consultaWebFormaPresentacion/g
s/consultaweb_detalle/consultaWebDetalle/g
s/consultaweb_ordencons/consultaWebOrden/g
s/^\/\/vim/\/\/ vim/g
s/\([^_]\)minusculas/\1a_minusculas/g
s/aMayusculas/a_mayusculas/g
s/agtabla/agregar_tabla/g
s/errorValida/error_valida/g
s/formularioValoresEstablece/establece_valores_form/g
s/tomaElementoRe/toma_elemento_rec/g
s/formularioValoresDefecto/valores_pordefecto_form/g
s/idDepartamento/ret_id_departamento/g
s/idMunicipio/ret_id_municipio/g
s/idClase/ret_id_clase/g
s/sinErrorPEAR/sin_error_pear/g
s/busand/consulta_and/g
s/busand_sinamp/consulta_and_sin_amp/g
s/busand_sinamp/consulta_and_sin_amp/g
s/busor_muchos/consulta_or_muchos/g
s/busor_muchos/consulta_or_muchos/g
s/ad_orden_cons/consulta_orden/g
s/insertaSQL/inserta_sql/g
s/varEscapa/var_escapa/g
s/varPostEscapa/var_post_escapa/g
s/varReqEscapa/var_req_escapa/g
s/agNuevaTabla/agrega_tabla/g
s/convierteValor/convierte_valor/g
s/preparaConsultaTabla/prepara_consulta_con_tabla/g
s/datosBusquedaGen/prepara_consulta_gen/g
s/objetoTabla/objeto_tabla/g
s/esObjetoNulo/es_objeto_nulo/g
s/aElementosXML/a_elementos_xml/g
s/datoRelacionado/dato_relacionado/g
s/enlazaRelato/enlaza_relato/g
s/enlazaRelato/enlaza_relato/g
s/HTML_Menu_agregaSubmenu/html_menu_agrega_submenu/g
s/HTML_Menu_toma_url/html_menu_toma_url/g
s/id_sininfo/idSinInfo/g
s/reportegeneral_registro/reporteGeneralRegistro/g
s/reporterevista_registro/reporteRevistaRegistro/g
s/convViolacion/conv_violacion/g
s/extrae_ubicacion_caso/extraeUbicacionCaso/g
s/extrae_victimas/extraeVictimas/g
s/extrae_combatientes/extraeCombatientes/g
s/extrae_presponsables/extraePResponsables/g
s/extrae_colectivas/extraeColectivas/g
s/lista_pr_cat_victimas/listaPrCatVictimas/g
s/array_size/tam_arreglo/g
s/version \$I/version CVS: $I/g
s/see BuscarId/see      BuscarId/g
s/  *$//g
s/http:\/\/creativecommons.org\/publicdomain\/zero\/1.0\//https:\/\/www.pasosdejesus.org\/dominio_publico_colombia.html/g
s/vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:/vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:/g
s/autenticaUsuario(\$dsn, \$accno/autenticaUsuario($dsn/g
s/htmlentities(\([^,)]*\))/htmlentities(\1, ENT_COMPAT, 'UTF-8')/g
s/parent::construct/parent::__construct/g
s/autenticaUsuario/autentica_usuario/g
s/noLoginFunction/no_login_function/g
s/loginFunction/login_function/g
s/sacaOpciones/saca_opciones/g
s/nomSesion/nom_sesion/g
s/cierraSesion/cierra_sesion/g
s/localizaConf/localiza_conf/g
s/regeneraEsquemas/regenera_esquemas/g
s/agregaArchivo/agrega_archivo/g
s/leeEstructura/lee_escritura/g
s/creaTabuladores/crea_tabuladores/g
s/creaTabuladoresEstandar/crea_tabuladores_estandar/g
s/habilitaBotones/habilita_botones/g
s/valVarConf/val_var_conf/g
s/extraeVarPHP/extrae_var_php/g
s/muestraVarPhpEnHTML/muestra_var_php_html/g
s/valVarConfPHP/val_var_conf_php/g
s/eliminaCaso/elimina_caso/g
s/actGlobales/act_globales/g
s/repObs/rep_obs/g
s/sinTildes/sin_tildes/g
s/tipo_proceso/tproceso/g
s/tipo_accion/taccion/g
s/observaciones_accion/observacionesaccion/g
s/numero_radicado/numeroradicado/g
s/trelacion_accion/taccion/g
