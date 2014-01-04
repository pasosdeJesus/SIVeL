class Causaref < ActiveRecord::Base
  has_many :respuesta, foreign_key: "id_causaref", validate: true
end
