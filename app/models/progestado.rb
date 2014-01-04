class Progestado < ActiveRecord::Base
	has_many :progestado_respuesta, foreign_key: "id_progestado", validate: true
end
