class Ayudaestado < ActiveRecord::Base
	has_many :ayudaestado_respuesta, foreign_key: "id_ayudaestado", validate: true, dependent: :destroy
end
