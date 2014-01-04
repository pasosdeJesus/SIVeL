class Taccion < ActiveRecord::Base
	has_many :accion, foreign_key: "id_taccion", validate: true
end
