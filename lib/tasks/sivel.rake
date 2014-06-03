namespace :sivel do
  desc "Actualiza indices"
  task indices: :environment do
    connection = ActiveRecord::Base.connection();
    connection.execute("SELECT setval('actividadarea_id_seq', MAX(id)) FROM 
             (SELECT 100 as id UNION SELECT MAX(id) FROM actividadarea) AS s;")
    tb= Ability::tablasbasicas - 
      [ "actividadarea", "categoria", "clase", "departamento", "municipio",
        "tclase" ]
    tb.each do |t|
#    ['contexto', 'etnia', 'filiacion', 'idioma', 'iglesia', 'organizacion', 'presponsable', 'profesion', 'region', 'sectorsocial', 'tsitio', 'vinculoestado'].each do |t|
      connection.execute("SELECT setval('#{t}_seq', MAX(id)) FROM 
             (SELECT 100 as id UNION SELECT MAX(id) FROM #{t}) AS s;");
    end
    ['caso', 'clase', 'departamento', 'municipio', 'persona',
      'ubicacion', 'usuario'].each do |t|
      connection.execute("SELECT setval('#{t}_seq', MAX(id)) FROM #{t}");
    end
  end

end
