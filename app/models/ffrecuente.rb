class Ffrecuente < ActiveRecord::Base
	has_many :caso_ffrecuente, foreign_key: "id_ffrecuente", validate: true
	has_many :anexo, foreign_key: "id_ffrecuente", validate: true
end
