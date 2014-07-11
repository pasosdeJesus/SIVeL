class CambiaRefCausaref < ActiveRecord::Migration
	# Esta migración debe ejecutarse después de haber añadido
	# nuevas categorias --con rake sivel:actbasicas
  def self.up
		if !Categoria.exists?(2002) then
    	raise ActiveRecord::IrreversibleMigration
		end
		execute <<-SQL
		ALTER TABLE casosjr DROP CONSTRAINT casosjr_id_causaref_fkey;
		ALTER TABLE casosjr ALTER COLUMN id_causaref DROP DEFAULT;
	 	UPDATE casosjr SET id_causaref=NULL WHERE id_causaref='0'; --SIN INFORMACIÓN
	 	UPDATE casosjr SET id_causaref='2003' WHERE id_causaref='1'; --GRUPO SOCIAL

    UPDATE casosjr SET id_causaref='2002' WHERE id_causaref='2'; --NACIONALIDAD
    UPDATE casosjr SET id_causaref='2004' WHERE id_causaref='3'; --OPINIÓN POLÍTICA
    UPDATE casosjr SET id_causaref='2000' WHERE id_causaref='4'; --RAZA
    UPDATE casosjr SET id_causaref='2001' WHERE id_causaref='5'; --RELIGIÓN
    UPDATE casosjr SET id_causaref='2031' WHERE id_causaref='6'; --SEXO
    UPDATE casosjr SET id_causaref='2010' WHERE id_causaref='7'; --VIOLENCIA GENERALIZADA
    UPDATE casosjr SET id_causaref='2012' WHERE id_causaref='10'; --AGRESIÓN EXTRANJERA
    UPDATE casosjr SET id_causaref='2013' WHERE id_causaref='11'; --CONFLICTOS INTERNOS
    UPDATE casosjr SET id_causaref='2014' WHERE id_causaref='12'; --VIOLACIÓN MASIVA A LOS DDHH
    UPDATE casosjr SET id_causaref='2015' WHERE id_causaref='13'; --GRAVE PERTURBACIÓN AL ORDEN PÚBLICO
    UPDATE casosjr SET id_causaref='2020' WHERE id_causaref='14'; --GENERO
    UPDATE casosjr SET id_causaref='2021' WHERE id_causaref='15'; --RAZONES ECONÓMICAS
		ALTER TABLE casosjr RENAME COLUMN id_causaref TO categoriaref;
		ALTER TABLE casosjr ADD FOREIGN KEY(categoriaref) REFERENCES categoria(id);
SQL
  end
  def self.down
    raise ActiveRecord::IrreversibleMigration
  end

end
