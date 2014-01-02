class Caso < ActiveRecord::Base
	belongs_to :intervalo, foreign_key: "id_intervalo", validate: true
        has_one :casosjr, foreign_key: "id_caso", inverse_of: :caso
        accepts_nested_attributes_for :casosjr, allow_destroy: true, update_only: true
	has_many :victima,  :dependent => :delete_all
	has_many :persona, :through => :victima

        validates_presence_of :fecha
end
