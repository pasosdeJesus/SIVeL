class EliminaCausaref < ActiveRecord::Migration
  def up
    execute <<-SQL
    ALTER TABLE respuesta DROP COLUMN IF EXISTS id_causaref
    SQL
    drop_table :causaref
  end
  
  def down
    raise ActiveRecord::IrreversibleMigration
  end
end
