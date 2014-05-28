# Be sure to restart your server when you modify this file.

# Add new inflection rules using the following format. Inflections
# are locale specific, and you may define rules for as many different
# locales as you wish. All of these examples are active by default:
# ActiveSupport::Inflector.inflections(:en) do |inflect|
#   inflect.plural /^(ox)$/i, '\1en'
#   inflect.singular /^(ox)en/i, '\1'
#   inflect.irregular 'person', 'people'
#   inflect.uncountable %w( fish sheep )
# end

# These inflection rules are supported but not enabled by default:
# ActiveSupport::Inflector.inflections(:en) do |inflect|
#   inflect.acronym 'RESTful'
# end
ActiveSupport::Inflector.inflections do |inflect|
	inflect.plural /^(.*s)$/i, '\1es'
	inflect.singular /^(.*s)es$/i, '\1'
	inflect.plural /^(.*a)$/i, '\1s'
	inflect.plural /^(.*d)$/i, '\1es'
	inflect.singular /^(.*d)es$/i, '\1'
	inflect.irregular 'actividad', 'actividades'
	inflect.irregular 'ayudaestado', 'ayudasestado'
	inflect.irregular 'clase', 'clases'
	inflect.irregular 'etiqueta', 'etiqueta'
	inflect.irregular 'respuesta', 'respuesta'
	inflect.irregular 'error', 'errores'
end
