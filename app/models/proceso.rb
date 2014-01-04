class Proceso < ActiveRecord::Base
	has_many :accion, foreign_key: "id_proceso", validate: true
	has_many :procesosjr, foreign_key: "id_proceso", validate: true
	belongs_to :tproceso, foreign_key: "id_tproceso", validate: true
	belongs_to :caso, foreign_key: "id_caso", validate: true
	belongs_to :etapa, foreign_key: "id_etapa", validate: true
end
