class Actividad < ActiveRecord::Base
	has_many :actividadareas_actividad, dependent: :delete_all
	has_many :actividadareas, through: :actividadareas_actividad
	has_many :rangoedad, through: :actividad_rangoedad
	accepts_nested_attributes_for :rangoedad,  reject_if: :all_blank
	has_many :actividad_rangoedad, foreign_key: "actividad_id", dependent: :delete_all
	accepts_nested_attributes_for :actividad_rangoedad, 
    allow_destroy: true, reject_if: :all_blank
end
