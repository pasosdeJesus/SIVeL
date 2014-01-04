class Presponsable < ActiveRecord::Base
	has_many :acto, foreign_key: "id_presponsable", validate: true
	has_many :actocolectivo, foreign_key: "id_presponsable", validate: true
	has_many :caso_categoria_presponsable, foreign_key: "id_presponsable", validate: true
	has_many :caso_presponsable, foreign_key: "id_presponsable", validate: true
	has_many :presponsable, foreign_key: "papa", validate: true
	has_many :victima, foreign_key: "organizacionarmada", validate: true
	has_many :victimacolectiva, foreign_key: "organizacionarmada", validate: true
	has_many :actosjr, foreign_key: "id_presponsable", validate: true
	belongs_to :presponsable, foreign_key: "papa", validate: true
end
