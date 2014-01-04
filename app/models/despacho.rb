class Despacho < ActiveRecord::Base
has_many :accion, foreign_key: "id_despacho", validate: true
belongs_to :tproceso, foreign_key: "id_tproceso", validate: true
end
