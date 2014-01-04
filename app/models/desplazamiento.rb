class Desplazamiento < ActiveRecord::Base
	has_many :actosjr, foreign_key: "id_caso,fechaexpulsion", validate: true
	has_many :respuesta, foreign_key: "id_caso,fechaexpulsion", validate: true
	belongs_to :ubicacion, foreign_key: "expulsion", validate: true
	belongs_to :ubicacion, foreign_key: "llegada", validate: true
	belongs_to :clasifdesp, foreign_key: "id_clasifdesp", validate: true
	belongs_to :tipodesp, foreign_key: "id_tipodesp", validate: true
	belongs_to :declaroante, foreign_key: "id_declaroante", validate: true
	belongs_to :inclusion, foreign_key: "id_inclusion", validate: true
	belongs_to :acreditacion, foreign_key: "id_acreditacion", validate: true
	belongs_to :modalidadtierra, foreign_key: "id_modalidadtierra", validate: true
	belongs_to :departamento, foreign_key: "departamentodecl", validate: true
	belongs_to :municipio, foreign_key: "municipiodecl", validate: true
end
