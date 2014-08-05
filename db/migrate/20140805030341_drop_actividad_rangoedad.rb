class DropActividadRangoedad < ActiveRecord::Migration
  def change
		drop_table :actividad_rangoedad
  end
end
