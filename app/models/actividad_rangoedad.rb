class ActividadRangoedad < ActiveRecord::Base
  belongs_to :actividad
  belongs_to :rangoedad
  validates_presence_of :actividad
  validates_presence_of :rangoedad
end
