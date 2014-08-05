class CreateComosupo < ActiveRecord::Migration
  def change
    create_table :comosupo do |t|
      t.string :nombre, limit: 500, null: false
      t.string :observaciones, limit: 5000
      t.date :fechacreacion, null: false
      t.date :fechadeshabilitacion

      t.timestamps
    end
  end
end
