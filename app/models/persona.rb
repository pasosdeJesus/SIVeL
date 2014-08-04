class Persona < ActiveRecord::Base
	has_many :acto, foreign_key: "id_persona", validate: true
	has_many :antecedente_victima, foreign_key: "id_persona", validate: true
	has_many :persona_trelacion, foreign_key: "persona1", validate: true
	has_many :persona_trelacion, foreign_key: "persona2", validate: true
	has_many :victima, foreign_key: "id_persona", validate: true
  has_many :casos, :through => :victimas
	has_many :casosjr, foreign_key: "contacto", validate: true
	has_many :victimasjr, foreign_key: "id_persona", validate: true
	has_many :actosjr, foreign_key: "id_persona", validate: true
	belongs_to :nacional, class_name: "Pais", foreign_key: "nacionalde", 
		validate: true
	belongs_to :pais, class_name: "Pais", foreign_key: "id_pais", validate: true
	belongs_to :departamento, foreign_key: "id_departamento", validate: true
	belongs_to :municipio, foreign_key: "id_municipio", validate: true
	belongs_to :clase, foreign_key: "id_clase", validate: true

  validates_presence_of :nombres
  validates_presence_of :apellidos
  validates_presence_of :sexo
end
