class Municipio < ActiveRecord::Base
	has_many :clase, foreign_key: "id_municipio", validate: true
	has_many :persona, foreign_key: "id_municipio", validate: true
	has_many :ubicacion, foreign_key: "id_municipio", validate: true
	has_many :ubicacion, foreign_key: "id_municipio,id_departamento", validate: true
	has_many :desplazamiento, foreign_key: "municipiodecl", validate: true
	has_many :victimasjr, foreign_key: "id_municipio", validate: true
	belongs_to :departamento, foreign_key: "id_departamento", validate: true
	belongs_to :pais, foreign_key: "id_pais", validate: true
end
