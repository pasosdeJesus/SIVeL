class Ubicacion < ActiveRecord::Base
	has_many :desplazamiento, foreign_key: "expulsion", validate: true
	has_many :desplazamiento, foreign_key: "llegada", validate: true
	belongs_to :departamento, foreign_key: "id_departamento", validate: true
	belongs_to :municipio, foreign_key: "id_municipio", validate: true
	belongs_to :municipio, foreign_key: "id_municipio,id_departamento", validate: true
	belongs_to :clase, foreign_key: "id_clase", validate: true
	belongs_to :tsitio, foreign_key: "id_tsitio", validate: true
	belongs_to :caso, foreign_key: "id_caso", validate: true
end
