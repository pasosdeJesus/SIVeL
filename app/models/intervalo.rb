class Intervalo < ActiveRecord::Base
	has_many :caso, validate: :true, foreign_key: 'id_intervalo'
end
