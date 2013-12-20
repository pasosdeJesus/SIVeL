class CreateActividadRangoedad < ActiveRecord::Migration
  def change
    create_table :actividad_rangoedad do |t|
      t.references :actividad, index: true
      t.references :rangoedad, index: true
      t.integer :m
      t.integer :f

      t.timestamps
    end
  end
end
