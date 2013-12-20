class CreateJoinTableActividadareasActividad < ActiveRecord::Migration
  def change
    create_join_table :actividadareas, :actividades, table_name: :actividadareas_actividad do |t|
      # t.index [:actividadarea_id, :actividad_id]
      # t.index [:actividad_id, :actividadarea_id]
    end
  end
end
