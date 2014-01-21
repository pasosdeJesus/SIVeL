class Anexo < ActiveRecord::Base
	belongs_to :caso, foreign_key: "id_caso", validate: true
	belongs_to :ffrecuente, foreign_key: "id_ffrecuente", validate: true
	belongs_to :fotra, foreign_key: "id_fotra", validate: true
	belongs_to :caso_ffrecuente, foreign_key: "id_caso,id_ffrecuente,fechaffrecuente", validate: true

	validates_presence_of :fecha, :descripcion, :archivo
end
