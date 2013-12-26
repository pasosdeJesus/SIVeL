class Caso < ActiveRecord::Base
	belongs_to :intervalo, foreign_key: "id_intervalo", validate: true
end
