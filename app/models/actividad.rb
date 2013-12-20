class Actividad < ActiveRecord::Base
	has_many :actividadareas_actividad, :dependent => :delete_all
	has_many :actividadareas, :through => :actividadareas_actividad
	has_many :actividad_rangoedad, :dependent => :delete_all
end
