class EtiquetaUsuario < ActiveRecord::Base
	belongs_to :etiqueta
	belongs_to :usuario
end
