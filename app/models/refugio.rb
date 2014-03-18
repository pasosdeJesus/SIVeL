class Refugio < ActiveRecord::Base
	belongs_to :caso, foreign_key: "id_caso", validate: true
	belongs_to :causaref, foreign_key: "id_causaref", validate: true

	belongs_to :salida, class_name: "Ubicacion", foreign_key: "id_salida", validate: true
	belongs_to :llegada, class_name: "Ubicacion", foreign_key: "id_llegada", validate: true

  validates_presence_of :fechasalida, :salida, :fechallegada, :llegada
end
