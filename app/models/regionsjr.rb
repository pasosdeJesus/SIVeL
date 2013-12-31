class Regionsjr < ActiveRecord::Base
	has_many :casosjr, validate: :true, foreign_key: 'id_regionsjr'
        validates_presence_of :nombre
        validates_presence_of :fechacreacion
end
