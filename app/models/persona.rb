class Persona < ActiveRecord::Base
    has_many :casosjr
    belongs_to :departamento, foreign_key: "id_departamento", validate: true
    belongs_to :municipio, foreign_key: "id_municipio", validate: true
    belongs_to :clase, foreign_key: "id_clase", validate: true

    has_many :victimas
    has_many :casos, :through => :victimas

    validates_presence_of :nombres
    validates_presence_of :apellidos
end
