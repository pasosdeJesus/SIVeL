# encoding: UTF-8
# This file is auto-generated from the current state of the database. Instead
# of editing this file, please use the migrations feature of Active Record to
# incrementally modify your database, and then regenerate this schema definition.
#
# Note that this schema.rb definition is the authoritative source for your
# database schema. If you need to create the application database on another
# system, you should be using db:schema:load, not running all the migrations
# from scratch. The latter is a flawed and unsustainable approach (the more migrations
# you'll amass, the slower it'll run and the greater likelihood for issues).
#
# It's strongly recommended that you check this file into your version control system.

ActiveRecord::Schema.define(version: 20131220103409) do

  # These are extensions that must be enabled in order to support this database
  enable_extension "plpgsql"
  enable_extension "unaccent"

  create_table "accion", force: true do |t|
    t.integer "id_proceso",                       null: false
    t.integer "id_taccion",                       null: false
    t.integer "id_despacho",                      null: false
    t.date    "fecha",                            null: false
    t.string  "numeroradicado",      limit: 50
    t.string  "observacionesaccion", limit: 4000
    t.boolean "respondido"
  end

  create_table "acreditacion", force: true do |t|
    t.string "nombre",               limit: 500,                        null: false
    t.date   "fechacreacion",                    default: '2013-05-24', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "actividad", force: true do |t|
    t.integer  "numero"
    t.integer  "minutos"
    t.string   "nombre",        limit: 500
    t.string   "objetivo",      limit: 500
    t.string   "proyecto",      limit: 500
    t.string   "resultado",     limit: 500
    t.date     "fecha"
    t.string   "actividad",     limit: 500
    t.string   "observaciones", limit: 5000
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  create_table "actividad_rangoedad", force: true do |t|
    t.integer  "actividad_id"
    t.integer  "rangoedad_id"
    t.integer  "m"
    t.integer  "f"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  add_index "actividad_rangoedad", ["actividad_id"], name: "index_actividad_rangoedad_on_actividad_id", using: :btree
  add_index "actividad_rangoedad", ["rangoedad_id"], name: "index_actividad_rangoedad_on_rangoedad_id", using: :btree

  create_table "actividadarea", force: true do |t|
    t.string   "nombre",               limit: 500
    t.string   "observaciones",        limit: 5000
    t.date     "fechacreacion"
    t.date     "fechadeshabilitacion"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  create_table "actividadareas_actividad", id: false, force: true do |t|
    t.integer "actividadarea_id", null: false
    t.integer "actividad_id",     null: false
  end

  create_table "actividadoficio", force: true do |t|
    t.string "nombre",               limit: 50,                        null: false
    t.date   "fechacreacion",                   default: '2013-05-13', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "acto", id: false, force: true do |t|
    t.integer "id_presponsable", null: false
    t.integer "id_categoria",    null: false
    t.integer "id_persona",      null: false
    t.integer "id_caso",         null: false
  end

  create_table "actocolectivo", id: false, force: true do |t|
    t.integer "id_presponsable", null: false
    t.integer "id_categoria",    null: false
    t.integer "id_grupoper",     null: false
    t.integer "id_caso",         null: false
  end

  create_table "actosjr", id: false, force: true do |t|
    t.integer "id_presponsable", null: false
    t.integer "id_categoria",    null: false
    t.integer "id_persona",      null: false
    t.integer "id_caso",         null: false
    t.date    "fecha",           null: false
    t.date    "fechaexpulsion"
  end

  create_table "actualizacionbase", id: false, force: true do |t|
    t.string "id",          limit: 10,  null: false
    t.date   "fecha",                   null: false
    t.string "descripcion", limit: 500, null: false
  end

  create_table "anexo", force: true do |t|
    t.integer "id_caso",                      null: false
    t.date    "fecha",                        null: false
    t.string  "descripcion",     limit: 1500, null: false
    t.string  "archivo",                      null: false
    t.integer "id_ffrecuente"
    t.date    "fechaffrecuente"
    t.integer "id_fotra"
  end

  create_table "antecedente", force: true do |t|
    t.string "nombre",               limit: 500, null: false
    t.date   "fechacreacion",                    null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "antecedente_caso", id: false, force: true do |t|
    t.integer "id_antecedente", null: false
    t.integer "id_caso",        null: false
  end

  create_table "antecedente_comunidad", id: false, force: true do |t|
    t.integer "id_antecedente", null: false
    t.integer "id_grupoper",    null: false
    t.integer "id_caso",        null: false
  end

  create_table "antecedente_victima", id: false, force: true do |t|
    t.integer "id_antecedente", null: false
    t.integer "id_persona",     null: false
    t.integer "id_caso",        null: false
  end

  create_table "ayudaestado", force: true do |t|
    t.string "nombre",               limit: 50,                        null: false
    t.date   "fechacreacion",                   default: '2013-06-16', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "ayudaestado_respuesta", id: false, force: true do |t|
    t.integer "id_caso",                    null: false
    t.date    "fechaatencion",              null: false
    t.integer "id_ayudaestado",             null: false
    t.string  "cantidad",       limit: 50
    t.string  "institucion",    limit: 100
  end

  create_table "ayudasjr", force: true do |t|
    t.string "nombre",               limit: 100,                        null: false
    t.date   "fechacreacion",                    default: '2013-06-16', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "ayudasjr_respuesta", id: false, force: true do |t|
    t.integer "id_caso",                    null: false
    t.date    "fechaatencion",              null: false
    t.integer "id_ayudasjr",                null: false
    t.string  "detallear",     limit: 5000
  end

  create_table "caso", force: true do |t|
    t.string   "titulo",            limit: 50
    t.date     "fecha",                        null: false
    t.string   "hora",              limit: 10
    t.string   "duracion",          limit: 10
    t.text     "memo",                         null: false
    t.string   "grconfiabilidad",   limit: 5
    t.string   "gresclarecimiento", limit: 5
    t.string   "grimpunidad",       limit: 5
    t.string   "grinformacion",     limit: 5
    t.text     "bienes"
    t.integer  "id_intervalo"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  create_table "caso_categoria_presponsable", id: false, force: true do |t|
    t.string  "id_tviolencia",     limit: 1, null: false
    t.integer "id_supracategoria",           null: false
    t.integer "id_categoria",                null: false
    t.integer "id",                          null: false
    t.integer "id_caso",                     null: false
    t.integer "id_presponsable",             null: false
  end

  create_table "caso_contexto", id: false, force: true do |t|
    t.integer "id_caso",     null: false
    t.integer "id_contexto", null: false
  end

  create_table "caso_etiqueta", id: false, force: true do |t|
    t.integer "id_caso",                     null: false
    t.integer "id_etiqueta",                 null: false
    t.integer "id_funcionario",              null: false
    t.date    "fecha",                       null: false
    t.string  "observaciones",  limit: 5000
  end

  create_table "caso_ffrecuente", id: false, force: true do |t|
    t.date    "fecha",                       null: false
    t.string  "ubicacion",       limit: 100
    t.string  "clasificacion",   limit: 100
    t.string  "ubicacionfisica", limit: 100
    t.integer "id_ffrecuente",               null: false
    t.integer "id_caso",                     null: false
  end

  create_table "caso_fotra", id: false, force: true do |t|
    t.integer "id_caso",                     null: false
    t.integer "id_fotra",                    null: false
    t.string  "anotacion",       limit: 200
    t.date    "fecha",                       null: false
    t.string  "ubicacionfisica", limit: 100
    t.string  "tfuente",         limit: 25
  end

  create_table "caso_frontera", id: false, force: true do |t|
    t.integer "id_frontera", null: false
    t.integer "id_caso",     null: false
  end

  create_table "caso_funcionario", id: false, force: true do |t|
    t.integer "id_funcionario", null: false
    t.integer "id_caso",        null: false
    t.date    "fechainicio"
  end

  create_table "caso_presponsable", id: false, force: true do |t|
    t.integer "id_caso",                     null: false
    t.integer "id_presponsable",             null: false
    t.integer "tipo",                        null: false
    t.string  "bloque",          limit: 50
    t.string  "frente",          limit: 50
    t.string  "brigada",         limit: 50
    t.string  "batallon",        limit: 50
    t.string  "division",        limit: 50
    t.string  "otro",            limit: 500
    t.integer "id",                          null: false
  end

  create_table "caso_region", id: false, force: true do |t|
    t.integer "id_region", null: false
    t.integer "id_caso",   null: false
  end

  create_table "casosjr", id: false, force: true do |t|
    t.integer "id_caso",                   null: false
    t.date    "fecharec",                  null: false
    t.integer "asesor",                    null: false
    t.integer "id_regionsjr"
    t.string  "direccion",    limit: 1000
    t.string  "telefono",     limit: 1000
    t.string  "comosupo",     limit: 5000
    t.integer "contacto"
  end

  create_table "categoria", id: false, force: true do |t|
    t.integer "id",                                             null: false
    t.string  "nombre",               limit: 500,               null: false
    t.date    "fechacreacion",                                  null: false
    t.date    "fechadeshabilitacion"
    t.integer "id_supracategoria",                              null: false
    t.string  "id_tviolencia",        limit: 1,                 null: false
    t.integer "id_pconsolidado"
    t.integer "contadaen"
    t.string  "tipocat",              limit: 1,   default: "I"
  end

  create_table "causaref", force: true do |t|
    t.string "nombre",               limit: 50,                        null: false
    t.date   "fechacreacion",                   default: '2013-06-17', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "clase", force: true do |t|
    t.string  "nombre",               limit: 500, null: false
    t.integer "id_departamento",                  null: false
    t.integer "id_municipio",                     null: false
    t.string  "id_tclase",            limit: 10
    t.float   "latitud"
    t.float   "longitud"
    t.date    "fechacreacion",                    null: false
    t.date    "fechadeshabilitacion"
  end

  create_table "clasifdesp", force: true do |t|
    t.string "nombre",               limit: 500,                        null: false
    t.date   "fechacreacion",                    default: '2013-05-24', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "comunidad_filiacion", id: false, force: true do |t|
    t.integer "id_filiacion", null: false
    t.integer "id_grupoper",  null: false
    t.integer "id_caso",      null: false
  end

  create_table "comunidad_organizacion", id: false, force: true do |t|
    t.integer "id_organizacion", null: false
    t.integer "id_grupoper",     null: false
    t.integer "id_caso",         null: false
  end

  create_table "comunidad_profesion", id: false, force: true do |t|
    t.integer "id_profesion", null: false
    t.integer "id_grupoper",  null: false
    t.integer "id_caso",      null: false
  end

  create_table "comunidad_rangoedad", id: false, force: true do |t|
    t.integer "id_rangoedad", null: false
    t.integer "id_grupoper",  null: false
    t.integer "id_caso",      null: false
  end

  create_table "comunidad_sectorsocial", id: false, force: true do |t|
    t.integer "id_sector",   null: false
    t.integer "id_grupoper", null: false
    t.integer "id_caso",     null: false
  end

  create_table "comunidad_vinculoestado", id: false, force: true do |t|
    t.integer "id_vinculoestado", null: false
    t.integer "id_grupoper",      null: false
    t.integer "id_caso",          null: false
  end

  create_table "contexto", force: true do |t|
    t.string "nombre",               limit: 500, null: false
    t.date   "fechacreacion",                    null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "declaroante", force: true do |t|
    t.string "nombre",               limit: 500,                        null: false
    t.date   "fechacreacion",                    default: '2013-05-24', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "departamento", force: true do |t|
    t.string "nombre",               limit: 500, null: false
    t.float  "latitud"
    t.float  "longitud"
    t.date   "fechacreacion",                    null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "derecho", force: true do |t|
    t.string "nombre",               limit: 100,                        null: false
    t.date   "fechacreacion",                    default: '2013-06-12', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "derecho_procesosjr", id: false, force: true do |t|
    t.integer "id_proceso", null: false
    t.integer "id_derecho", null: false
  end

  create_table "derecho_respuesta", id: false, force: true do |t|
    t.integer "id_caso",                    null: false
    t.date    "fechaatencion",              null: false
    t.integer "id_derecho",                 null: false
    t.boolean "informacion"
    t.string  "acciones",      limit: 5000
  end

  create_table "despacho", force: true do |t|
    t.integer "id_tproceso",                                             null: false
    t.string  "nombre",               limit: 500,                        null: false
    t.string  "observaciones",        limit: 500
    t.date    "fechacreacion",                    default: '2001-01-01', null: false
    t.date    "fechadeshabilitacion"
  end

  create_table "desplazamiento", id: false, force: true do |t|
    t.integer "id_caso",                           null: false
    t.date    "fechaexpulsion",                    null: false
    t.integer "expulsion",                         null: false
    t.date    "fechallegada",                      null: false
    t.integer "llegada",                           null: false
    t.integer "id_clasifdesp",                     null: false
    t.integer "id_tipodesp",                       null: false
    t.string  "descripcion",          limit: 5000
    t.string  "otrosdatos",           limit: 1000
    t.string  "declaro",              limit: 1
    t.string  "hechosdeclarados",     limit: 5000
    t.date    "fechadeclaracion"
    t.integer "departamentodecl"
    t.integer "municipiodecl"
    t.integer "id_declaroante"
    t.integer "id_inclusion"
    t.integer "id_acreditacion"
    t.boolean "retornado"
    t.boolean "reubicado"
    t.boolean "connacionalretorno"
    t.boolean "acompestado"
    t.boolean "connacionaldeportado"
    t.string  "oficioantes",          limit: 5000
    t.integer "id_modalidadtierra"
    t.string  "materialesperdidos",   limit: 5000
    t.string  "inmaterialesperdidos", limit: 5000
    t.boolean "protegiorupta"
    t.string  "documentostierra",     limit: 5000
  end

  create_table "escolaridad", force: true do |t|
    t.string "nombre",               limit: 50,                        null: false
    t.date   "fechacreacion",                   default: '2013-05-13', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "estadocivil", force: true do |t|
    t.string "nombre",               limit: 50,                        null: false
    t.date   "fechacreacion",                   default: '2013-05-13', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "etapa", force: true do |t|
    t.integer "id_tproceso",                                             null: false
    t.string  "nombre",               limit: 500,                        null: false
    t.string  "observaciones",        limit: 200
    t.date    "fechacreacion",                    default: '2001-01-01', null: false
    t.date    "fechadeshabilitacion"
  end

  create_table "etiqueta", force: true do |t|
    t.string "nombre",               limit: 500,                        null: false
    t.string "observaciones",        limit: 500
    t.date   "fechacreacion",                    default: '2001-01-01', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "etnia", force: true do |t|
    t.string "nombre",               limit: 500,  null: false
    t.string "descripcion",          limit: 1000
    t.date   "fechacreacion",                     null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "ffrecuente", force: true do |t|
    t.string "nombre",               limit: 500, null: false
    t.string "tfuente",              limit: 25,  null: false
    t.date   "fechacreacion",                    null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "filiacion", force: true do |t|
    t.string "nombre",               limit: 500, null: false
    t.date   "fechacreacion",                    null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "fotra", force: true do |t|
    t.string "nombre", limit: 500, null: false
  end

  create_table "frontera", force: true do |t|
    t.string "nombre",               limit: 500, null: false
    t.date   "fechacreacion",                    null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "funcionario", force: true do |t|
    t.string "anotacion", limit: 50
    t.string "nombre",    limit: 15, null: false
  end

  add_index "funcionario", ["nombre"], name: "funcionario_nombre_key", unique: true, using: :btree

  create_table "grupoper", force: true do |t|
    t.string "nombre",      limit: 500,  null: false
    t.string "anotaciones", limit: 1000
  end

  create_table "iglesia", force: true do |t|
    t.string "nombre",               limit: 500,  null: false
    t.string "descripcion",          limit: 1000
    t.date   "fechacreacion",                     null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "inclusion", force: true do |t|
    t.string "nombre",               limit: 500,                        null: false
    t.date   "fechacreacion",                    default: '2013-05-24', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "instanciader", force: true do |t|
    t.string "nombre",               limit: 50,                        null: false
    t.date   "fechacreacion",                   default: '2013-06-12', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "intervalo", force: true do |t|
    t.string   "nombre",               limit: 500, null: false
    t.string   "rango",                limit: 25,  null: false
    t.date     "fechacreacion",                    null: false
    t.date     "fechadeshabilitacion"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  create_table "maternidad", force: true do |t|
    t.string "nombre",               limit: 50,                        null: false
    t.date   "fechacreacion",                   default: '2013-05-13', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "mecanismoder", force: true do |t|
    t.string "nombre",               limit: 50,                        null: false
    t.date   "fechacreacion",                   default: '2013-06-12', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "modalidadtierra", force: true do |t|
    t.string "nombre",               limit: 500,                        null: false
    t.date   "fechacreacion",                    default: '2013-05-24', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "motivoconsulta", force: true do |t|
    t.string "nombre",               limit: 50,                        null: false
    t.date   "fechacreacion",                   default: '2013-05-13', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "motivosjr", force: true do |t|
    t.string "nombre",               limit: 100,                        null: false
    t.date   "fechacreacion",                    default: '2013-06-16', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "motivosjr_respuesta", id: false, force: true do |t|
    t.integer "id_caso",                    null: false
    t.date    "fechaatencion",              null: false
    t.integer "id_motivosjr",               null: false
    t.string  "detalle",       limit: 5000
  end

  create_table "municipio", force: true do |t|
    t.string  "nombre",               limit: 500, null: false
    t.integer "id_departamento",                  null: false
    t.float   "latitud"
    t.float   "longitud"
    t.date    "fechacreacion",                    null: false
    t.date    "fechadeshabilitacion"
  end

  create_table "organizacion", force: true do |t|
    t.string "nombre",               limit: 500, null: false
    t.date   "fechacreacion",                    null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "pconsolidado", force: true do |t|
    t.string  "rotulo",               limit: 500,             null: false
    t.string  "tipoviolencia",        limit: 25,              null: false
    t.string  "clasificacion",        limit: 25,              null: false
    t.integer "peso",                             default: 0
    t.date    "fechacreacion",                                null: false
    t.date    "fechadeshabilitacion"
  end

  create_table "persona", force: true do |t|
    t.string  "nombres",         limit: 100, null: false
    t.string  "apellidos",       limit: 100, null: false
    t.integer "anionac"
    t.integer "mesnac"
    t.integer "dianac"
    t.string  "sexo",            limit: 1,   null: false
    t.integer "id_departamento"
    t.integer "id_municipio"
    t.integer "id_clase"
    t.string  "tipodocumento",   limit: 2
    t.integer "numerodocumento", limit: 8
  end

  create_table "persona_trelacion", id: false, force: true do |t|
    t.integer "persona1",                  null: false
    t.integer "persona2",                  null: false
    t.string  "id_trelacion",  limit: 2,   null: false
    t.string  "observaciones", limit: 200
  end

  create_table "personadesea", force: true do |t|
    t.string "nombre",               limit: 50,                        null: false
    t.date   "fechacreacion",                   default: '2013-06-17', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "presponsable", force: true do |t|
    t.string  "nombre",               limit: 500, null: false
    t.integer "papa"
    t.date    "fechacreacion",                    null: false
    t.date    "fechadeshabilitacion"
  end

  create_table "proceso", force: true do |t|
    t.integer "id_caso",                   null: false
    t.integer "id_tproceso",               null: false
    t.integer "id_etapa",                  null: false
    t.date    "proximafecha"
    t.string  "demandante",    limit: 100
    t.string  "demandado",     limit: 100
    t.string  "poderdante",    limit: 100
    t.string  "telefono",      limit: 50
    t.string  "observaciones", limit: 500
  end

  create_table "procesosjr", id: false, force: true do |t|
    t.integer "id_proceso",                     null: false
    t.integer "id_motivoconsulta"
    t.string  "narracion",         limit: 5000
    t.string  "hapresentado",      limit: 1
    t.integer "id_mecanismoder"
    t.integer "id_instanciader"
    t.string  "detinstancia",      limit: 5000
    t.string  "mecrespondido",     limit: 1
    t.date    "fecharespuesta"
    t.string  "ajustaley",         limit: 1
    t.string  "estadomecanismo",   limit: 5000
    t.string  "orientacion",       limit: 5000
    t.string  "compromisossjr",    limit: 5000
    t.string  "compromisosper",    limit: 5000
    t.string  "surtioefecto",      limit: 1
    t.integer "otromecanismo"
    t.integer "otrainstancia"
    t.string  "detotrainstancia",  limit: 5000
    t.boolean "persistevul"
    t.string  "resultado",         limit: 5000
    t.string  "casoregistro",      limit: 1
    t.string  "motivacionjuez",    limit: 5000
  end

  create_table "profesion", force: true do |t|
    t.string "nombre",               limit: 500, null: false
    t.date   "fechacreacion",                    null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "progestado", force: true do |t|
    t.string "nombre",               limit: 50,                        null: false
    t.date   "fechacreacion",                   default: '2013-06-17', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "progestado_respuesta", id: false, force: true do |t|
    t.integer "id_caso",                    null: false
    t.date    "fechaatencion",              null: false
    t.integer "id_progestado",              null: false
    t.string  "difobs",        limit: 5000
  end

  create_table "rangoedad", force: true do |t|
    t.string   "nombre",               limit: 20,             null: false
    t.string   "rango",                limit: 20,             null: false
    t.integer  "limiteinferior",                  default: 0, null: false
    t.integer  "limitesuperior",                  default: 0, null: false
    t.date     "fechacreacion",                               null: false
    t.date     "fechadeshabilitacion"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  create_table "regimensalud", force: true do |t|
    t.string "nombre",               limit: 50,                        null: false
    t.date   "fechacreacion",                   default: '2013-05-13', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "region", force: true do |t|
    t.string "nombre",               limit: 500, null: false
    t.date   "fechacreacion",                    null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "regionsjr", force: true do |t|
    t.string "nombre",               limit: 50,                        null: false
    t.date   "fechacreacion",                   default: '2013-05-13', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "resagresion", force: true do |t|
    t.string "nombre",               limit: 500, null: false
    t.date   "fechacreacion",                    null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "respuesta", id: false, force: true do |t|
    t.integer "id_caso",                      null: false
    t.date    "fechaatencion",                null: false
    t.date    "fechaexpulsion",               null: false
    t.boolean "prorrogas"
    t.integer "numprorrogas"
    t.integer "montoprorrogas"
    t.date    "fechaultima"
    t.string  "lugarultima",     limit: 500
    t.boolean "entregada"
    t.boolean "proxprorroga"
    t.string  "turno",           limit: 100
    t.string  "lugar",           limit: 500
    t.string  "descamp",         limit: 5000
    t.string  "compromisos",     limit: 5000
    t.string  "remision",        limit: 5000
    t.string  "orientaciones",   limit: 5000
    t.string  "gestionessjr",    limit: 5000
    t.string  "observaciones",   limit: 5000
    t.integer "id_personadesea"
    t.integer "id_causaref"
    t.string  "verifcsjr",       limit: 5000
    t.string  "verifcper",       limit: 5000
    t.string  "efectividad",     limit: 5000
  end

  create_table "rolfamilia", force: true do |t|
    t.string "nombre",               limit: 50,                        null: false
    t.date   "fechacreacion",                   default: '2013-06-20', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "sectorsocial", force: true do |t|
    t.string "nombre",               limit: 500, null: false
    t.date   "fechacreacion",                    null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "supracategoria", id: false, force: true do |t|
    t.integer "id",                               null: false
    t.string  "nombre",               limit: 500, null: false
    t.date    "fechacreacion",                    null: false
    t.date    "fechadeshabilitacion"
    t.string  "id_tviolencia",        limit: 1,   null: false
  end

  create_table "taccion", force: true do |t|
    t.string "nombre",               limit: 500,                        null: false
    t.string "observaciones",        limit: 200
    t.date   "fechacreacion",                    default: '2001-01-01', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "tclase", id: false, force: true do |t|
    t.string "id",                   limit: 10,  null: false
    t.string "nombre",               limit: 500, null: false
    t.date   "fechacreacion",                    null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "tipodesp", force: true do |t|
    t.string "nombre",               limit: 500,                        null: false
    t.date   "fechacreacion",                    default: '2013-05-24', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "tproceso", force: true do |t|
    t.string "nombre",               limit: 500,                        null: false
    t.string "observaciones",        limit: 200
    t.date   "fechacreacion",                    default: '2001-01-01', null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "trelacion", id: false, force: true do |t|
    t.string  "id",                   limit: 2,   null: false
    t.string  "nombre",               limit: 500, null: false
    t.boolean "dirigido",                         null: false
    t.string  "observaciones",        limit: 200
    t.date    "fechacreacion",                    null: false
    t.date    "fechadeshabilitacion"
  end

  create_table "tsitio", force: true do |t|
    t.string "nombre",               limit: 500, null: false
    t.date   "fechacreacion",                    null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "tviolencia", id: false, force: true do |t|
    t.string "id",                   limit: 1,   null: false
    t.string "nombre",               limit: 500, null: false
    t.string "nomcorto",             limit: 10,  null: false
    t.date   "fechacreacion",                    null: false
    t.date   "fechadeshabilitacion"
  end

  create_table "ubicacion", force: true do |t|
    t.string  "lugar",           limit: 500
    t.string  "sitio",           limit: 500
    t.integer "id_clase"
    t.integer "id_municipio"
    t.integer "id_departamento"
    t.integer "id_tsitio",                   null: false
    t.integer "id_caso",                     null: false
    t.float   "latitud"
    t.float   "longitud"
  end

  create_table "usuario", id: false, force: true do |t|
    t.string   "id",                     limit: 15,                   null: false
    t.string   "password",               limit: 64,                   null: false
    t.string   "nombre",                 limit: 50
    t.string   "descripcion",            limit: 50
    t.integer  "rol"
    t.integer  "diasedicion"
    t.string   "idioma",                 limit: 6,  default: "es_CO", null: false
    t.string   "email",                             default: "",      null: false
    t.string   "encrypted_password",                default: "",      null: false
    t.string   "reset_password_token"
    t.datetime "reset_password_sent_at"
    t.datetime "remember_created_at"
    t.integer  "sign_in_count",                     default: 0,       null: false
    t.datetime "current_sign_in_at"
    t.datetime "last_sign_in_at"
    t.string   "current_sign_in_ip"
    t.string   "last_sign_in_ip"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  add_index "usuario", ["email"], name: "index_usuario_on_email", unique: true, using: :btree
  add_index "usuario", ["reset_password_token"], name: "index_usuario_on_reset_password_token", unique: true, using: :btree

  create_table "victima", id: false, force: true do |t|
    t.integer "id_persona",                                    null: false
    t.integer "id_caso",                                       null: false
    t.integer "hijos"
    t.integer "id_profesion",                                  null: false
    t.integer "id_rangoedad",                                  null: false
    t.integer "id_filiacion",                                  null: false
    t.integer "id_sectorsocial",                               null: false
    t.integer "id_organizacion",                               null: false
    t.integer "id_vinculoestado",                              null: false
    t.integer "organizacionarmada",                            null: false
    t.string  "anotaciones",        limit: 1000
    t.integer "id_etnia"
    t.integer "id_iglesia"
    t.string  "orientacionsexual",  limit: 1,    default: "H", null: false
  end

  create_table "victimacolectiva", id: false, force: true do |t|
    t.integer "id_grupoper",        null: false
    t.integer "id_caso",            null: false
    t.integer "personasaprox"
    t.integer "organizacionarmada"
  end

  create_table "victimasjr", id: false, force: true do |t|
    t.integer "id_persona",                                  null: false
    t.integer "id_caso",                                     null: false
    t.boolean "sindocumento"
    t.integer "id_estadocivil"
    t.integer "id_rolfamilia",                   default: 0, null: false
    t.boolean "cabezafamilia"
    t.integer "id_maternidad"
    t.boolean "discapacitado"
    t.integer "id_actividadoficio"
    t.integer "id_escolaridad"
    t.boolean "asisteescuela"
    t.boolean "tienesisben"
    t.integer "id_departamento"
    t.integer "id_municipio"
    t.integer "nivelsisben"
    t.integer "id_regimensalud"
    t.string  "eps",                limit: 1000
    t.boolean "libretamilitar"
    t.integer "distrito"
    t.boolean "progadultomayor"
    t.date    "fechadesagregacion"
  end

  create_table "vinculoestado", force: true do |t|
    t.string "nombre",               limit: 500, null: false
    t.date   "fechacreacion",                    null: false
    t.date   "fechadeshabilitacion"
  end

end
