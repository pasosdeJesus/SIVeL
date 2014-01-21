class AyudasjrRespuesta < ActiveRecord::Base
  belongs_to :ayudasjr, foreign_key: "id_ayudasjr", validate: true
  belongs_to :respuesta, foreign_key: "id_respuesta", validate: true
end
