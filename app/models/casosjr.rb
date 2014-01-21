class Casosjr < ActiveRecord::Base
	has_many :ayudaestado_respuesta, foreign_key: "id_caso", validate: true, dependent: :destroy
	has_many :derecho_respuesta, foreign_key: "id_caso", validate: true, dependent: :destroy
	has_many :ayudasjr_respuesta, foreign_key: "id_caso", validate: true, dependent: :destroy
	has_many :motivosjr_respuesta, foreign_key: "id_caso", validate: true, dependent: :destroy
	has_many :progestado_respuesta, foreign_key: "id_caso", validate: true, dependent: :destroy
	has_many :respuesta, foreign_key: "id_caso", validate: true, dependent: :destroy
	belongs_to :caso, foreign_key: "id_caso", validate: true, inverse_of: :casosjr
	belongs_to :persona, foreign_key: "contacto", validate: true
	belongs_to :regionsjr, foreign_key: "id_regionsjr", validate: true
	belongs_to :usuario, foreign_key: "asesor", validate: true

	validates_presence_of :fecharec

	self.primary_key = :id_caso
end
