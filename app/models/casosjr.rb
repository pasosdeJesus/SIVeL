class Casosjr < ActiveRecord::Base
	belongs_to :caso, foreign_key: "id_caso", validate: true, inverse_of: :casosjr
        belongs_to :funcionario, foreign_key: "asesor", validate: true
        belongs_to :regionsjr, foreign_key: "id_regionsjr", validate: true
        belongs_to :persona, foreign_key: "contacto", validate: true

        validates_presence_of :fecharec

        self.primary_key = :id_caso
end
