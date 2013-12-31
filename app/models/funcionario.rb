class Funcionario < ActiveRecord::Base
    has_many :casosjr, validate: :true, foreign_key: 'asesor'
    validates_presence_of :nombre
end
