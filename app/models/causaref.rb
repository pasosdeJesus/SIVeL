class Causaref < ActiveRecord::Base
    has_many :refugio, foreign_key: "id_refugio", validate: true
    validates_presence_of :nombre
end
