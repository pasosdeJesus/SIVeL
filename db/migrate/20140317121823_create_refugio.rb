class CreateRefugio < ActiveRecord::Migration
  def change
    create_table :refugio do |t|
      t.integer :id_caso
      t.date :fechasalida
      t.integer :id_salida
      t.date :fechallegada
      t.integer :id_llegada
      t.integer :id_causaref
      t.string :observaciones

      t.timestamps
    end
  end
end
