class Fechacreacion < ActiveRecord::Migration

  TABLASBASICAS = [:actividadarea, :ayudaestado, :departamento,
    :municipio, :actividadoficio, :aslegal, :aspsicosocial, :ayudasjr, 
    :categoria, :causaref, :emprendimiento, :escolaridad, 
    :estadocivil, :etiqueta, :iglesia, :maternidad, :pais, :presponsable, 
    :profesion, :proteccion, :regionsjr, :rolfamilia, :statusmigratorio, 
    :tsitio
  ]

  def change
    TABLASBASICAS.each do |t|
      execute <<-SQL
        ALTER TABLE #{t} ALTER COLUMN fechacreacion 
            SET DEFAULT CURRENT_DATE;
SQL
    end
  end
end
