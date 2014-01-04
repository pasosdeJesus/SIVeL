class Ayudasjr < ActiveRecord::Base
	has_many :ayudasjr_respuesta, foreign_key: "id_ayudasjr", validate: true
end
