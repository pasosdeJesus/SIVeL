class Rangoedad < ActiveRecord::Base
	has_many :actividad_rangoedad, :dependent => :delete_all
end
