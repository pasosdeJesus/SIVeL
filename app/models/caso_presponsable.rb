class CasoPresponsable < ActiveRecord::Base
	belongs_to :caso, foreign_key: "id_caso", validate: true
	belongs_to :presponsable, foreign_key: "id_presponsable", validate: true
end
