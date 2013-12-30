class Caso < ActiveRecord::Base
	belongs_to :intervalo, foreign_key: "id_intervalo", validate: true
        has_one :casosjr, foreign_key: "id_caso"

        accepts_nested_attributes_for :casosjr, allow_destroy: true
        validates_presence_of :fecha
end
