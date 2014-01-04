class Regionsjr < ActiveRecord::Base
	has_many :casosjr, foreign_key: "id_regionsjr", validate: true

	validates_presence_of :nombre
	validates_presence_of :fechacreacion
end
