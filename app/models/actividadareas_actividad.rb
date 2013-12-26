class ActividadareasActividad < ActiveRecord::Base
	belongs_to :actividad
	belongs_to :actividadarea

end
