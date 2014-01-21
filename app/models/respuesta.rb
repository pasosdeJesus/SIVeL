class Respuesta < ActiveRecord::Base
  belongs_to :caso, foreign_key: "id_caso", validate: true
	belongs_to :desplazamiento, foreign_key: "fechaexpulsion", validate: true

  has_many :ayudasjr, :through => :ayudasjr_respuesta
  has_many :ayudasjr_respuesta,  foreign_key: "id_respuesta", dependent: :destroy
  accepts_nested_attributes_for :ayudasjr_respuesta, allow_destroy: true, reject_if: :all_blank
	
  validates_presence_of :fechaexpulsion
end
