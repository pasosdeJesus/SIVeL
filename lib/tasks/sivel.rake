namespace :sivel do
  desc "Actualiza indices"
  task indices: :environment do
    connection = ActiveRecord::Base.connection();
    connection.execute("SELECT setval('actividadarea_id_seq', MAX(id)) FROM 
             (SELECT 100 as id UNION SELECT MAX(id) FROM actividadarea) AS s;")
    tb= Ability::tablasbasicas - 
      [ "actividadarea", "categoria", "clase", "departamento", "municipio",
        "supracategoria", "tclase", "tviolencia" ]
    tb.each do |t|
      connection.execute("SELECT setval('#{t}_seq', MAX(id)) FROM 
             (SELECT 100 as id UNION SELECT MAX(id) FROM #{t}) AS s;");
    end
    ['caso', 'persona', 'ubicacion', 'usuario'].each do |t|
      connection.execute("SELECT setval('#{t}_seq', MAX(id)) FROM #{t}");
    end
  end

end
