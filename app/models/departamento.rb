class Departamento < ActiveRecord::Base
	has_many :clase, foreign_key: "id_departamento", validate: true
	has_many :municipio, foreign_key: "id_departamento", validate: true
	belongs_to :pais, foreign_key: "id_pais", validate: true
	has_many :persona, foreign_key: "id_departamento", validate: true
	has_many :ubicacion, foreign_key: "id_departamento", validate: true
	has_many :desplazamiento, foreign_key: "departamentodecl", validate: true
	has_many :victimasjr, foreign_key: "id_departamento", validate: true
end
