class Filiacion < ActiveRecord::Base
	has_many :comunidad_filiacion, foreign_key: "id_filiacion", validate: true
	has_many :victima, foreign_key: "id_filiacion", validate: true
end
