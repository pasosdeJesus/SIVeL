class Rangoedad < ActiveRecord::Base
has_many :comunidad_rangoedad, foreign_key: "id_rangoedad", validate: true
has_many :victima, foreign_key: "id_rangoedad", validate: true
	has_many :actividad_rangoedad, :dependent => :delete_all
end
