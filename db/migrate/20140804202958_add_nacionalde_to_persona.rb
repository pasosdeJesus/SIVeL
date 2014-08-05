class AddNacionaldeToPersona < ActiveRecord::Migration
  def change
		add_column :persona, :nacionalde, :integer
    execute <<-SQL
        ALTER TABLE persona ADD FOREIGN KEY (nacionalde)
            REFERENCES pais(id);
SQL
  end
end
