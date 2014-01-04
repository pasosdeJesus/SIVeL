class Caso < ActiveRecord::Base
	has_many :actocolectivo, foreign_key: "id_caso", validate: true
	has_many :antecedente_caso, foreign_key: "id_caso", validate: true
	has_many :antecedente_comunidad, foreign_key: "id_caso", validate: true
	has_many :antecedente_victima, foreign_key: "id_caso", validate: true
	has_one :casosjr, foreign_key: "id_caso", inverse_of: :caso
	has_many :caso_categoria_presponsable, foreign_key: "id_caso", validate: true
	has_many :caso_contexto, foreign_key: "id_caso", validate: true
	has_many :caso_ffrecuente, foreign_key: "id_caso", validate: true
	has_many :caso_frontera, foreign_key: "id_caso", validate: true
	has_many :caso_fotra, foreign_key: "id_caso", validate: true
	has_many :caso_usuario, foreign_key: "id_caso", validate: true
	has_many :caso_presponsable, foreign_key: "id_caso", validate: true
	has_many :caso_region, foreign_key: "id_caso", validate: true
	has_many :comunidad_filiacion, foreign_key: "id_caso", validate: true
	has_many :comunidad_organizacion, foreign_key: "id_caso", validate: true
	has_many :comunidad_profesion, foreign_key: "id_caso", validate: true
	has_many :comunidad_rangoedad, foreign_key: "id_caso", validate: true
	has_many :comunidad_sectorsocial, foreign_key: "id_caso", validate: true
	has_many :comunidad_vinculoestado, foreign_key: "id_caso", validate: true
	has_many :ubicacion, foreign_key: "id_caso", validate: true
	has_many :victimas,  foreign_key: "id_caso", :dependent => :delete_all
	has_many :victimacolectiva, foreign_key: "id_caso", validate: true
	has_many :anexo, foreign_key: "id_caso", validate: true
	has_many :caso_etiqueta, foreign_key: "id_caso", validate: true
	has_many :personas, :through => :victima
	has_many :proceso, foreign_key: "id_caso", validate: true
	has_many :casosjr, foreign_key: "id_caso", validate: true
	has_many :victimasjr, foreign_key: "id_caso", validate: true
	has_many :actosjr, foreign_key: "id_caso", validate: true

	belongs_to :intervalo, foreign_key: "id_intervalo", validate: true

	accepts_nested_attributes_for :casosjr, allow_destroy: true, update_only: true
	accepts_nested_attributes_for :victimas, allow_destroy: true, update_only: true, reject_if: :all_blank

	validates_presence_of :fecha
end
