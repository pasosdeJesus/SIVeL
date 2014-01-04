class Actividadoficio < ActiveRecord::Base
	has_many :victimasjr, foreign_key: "id_actividadoficio", validate: true
end
