class Accion < ActiveRecord::Base
	belongs_to :despacho, foreign_key: "id_despacho", validate: true
	belongs_to :proceso, foreign_key: "id_proceso", validate: true
	belongs_to :taccion, foreign_key: "id_taccion", validate: true
end
