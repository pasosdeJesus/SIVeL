class Caso < ActiveRecord::Base
	belongs_to :intervalo, foreign_key: "id_intervalo", validate: true
        has_one :casosjr, foreign_key: "id_caso", inverse_of: :caso
        accepts_nested_attributes_for :casosjr, allow_destroy: true, update_only: true
	has_many :victima,  :dependent => :delete_all
	has_many :persona, :through => :victima
        accepts_nested_attributes_for :victima, allow_destroy: true, update_only: true, reject_if: :all_blank

        validates_presence_of :fecha
end
