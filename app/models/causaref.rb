class Causaref < ActiveRecord::Base
    has_one :casosjr, foreign_key: "id_causaref", validate: true
    validates_presence_of :nombre
end
