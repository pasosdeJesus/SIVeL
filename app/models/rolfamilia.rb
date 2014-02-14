class Rolfamilia < ActiveRecord::Base
	has_many :victimasjr, foreign_key: "id_rolfamilia", validate: true
end
