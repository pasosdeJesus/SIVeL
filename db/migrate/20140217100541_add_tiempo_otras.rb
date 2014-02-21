class AddTiempoOtras < ActiveRecord::Migration
  def change
	  add_column :aslegal, :created_at, :datetime 
	  add_column :aslegal, :updated_at, :datetime
	  add_column :aslegal_respuesta, :created_at, :datetime 
	  add_column :aslegal_respuesta, :updated_at, :datetime
	  add_column :aspsicosocial, :created_at, :datetime 
	  add_column :aspsicosocial, :updated_at, :datetime
	  add_column :aspsicosocial_respuesta, :created_at, :datetime 
	  add_column :aspsicosocial_respuesta, :updated_at, :datetime
	  add_column :emprendimiento, :created_at, :datetime 
	  add_column :emprendimiento, :updated_at, :datetime
	  add_column :emprendimiento_respuesta, :created_at, :datetime 
	  add_column :emprendimiento_respuesta, :updated_at, :datetime
	  add_column :idioma, :created_at, :datetime 
	  add_column :idioma, :updated_at, :datetime
	  add_column :pais, :created_at, :datetime 
	  add_column :pais, :updated_at, :datetime 
	  add_column :proteccion, :created_at, :datetime 
	  add_column :proteccion, :updated_at, :datetime 
	  add_column :statusmigratorio, :created_at, :datetime
	  add_column :statusmigratorio, :updated_at, :datetime
  end
end
