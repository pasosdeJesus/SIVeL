# encoding: UTF-8
class Desplazamiento < ActiveRecord::Base

	has_many :actosjr, foreign_key: "id_caso", validate: true

	belongs_to :expulsion, class_name: "Ubicacion", foreign_key: "id_expulsion", validate: true
	belongs_to :llegada, class_name: "Ubicacion", foreign_key: "id_llegada", validate: true
	belongs_to :clasifdesp, foreign_key: "id_clasifdesp", validate: true
	belongs_to :tipodesp, foreign_key: "id_tipodesp", validate: true
	belongs_to :declaroante, foreign_key: "id_declaroante", validate: true
	belongs_to :inclusion, foreign_key: "id_inclusion", validate: true
	belongs_to :acreditacion, foreign_key: "id_acreditacion", validate: true
	belongs_to :modalidadtierra, foreign_key: "id_modalidadtierra", validate: true
	belongs_to :pais, foreign_key: "paisdecl", validate: true
	belongs_to :departamento, foreign_key: "departamentodecl", validate: true
	belongs_to :municipio, foreign_key: "municipiodecl", validate: true
	belongs_to :caso, foreign_key: "id_caso", validate: true

  self.primary_key = nil

  validates_presence_of :fechaexpulsion, :expulsion, :fechallegada, :llegada
  validate :llegada_posterior_a_expulsion
  validate :fechaexpulsion, uniqueness: { scope: :id_caso,
    message: " ya existe otro desplazamiento con la misma fecha de expulsión"
  }

  def llegada_posterior_a_expulsion
    if fechallegada.present? && fechaexpulsion.present? && fechallegada<fechaexpulsion
      errors.add(:fechallegada, " debe ser posterior a la fecha de expulsión")
    end
  end

end
