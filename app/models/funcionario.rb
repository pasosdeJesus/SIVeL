class Funcionario < ActiveRecord::Base
    has_many :casosjr
    validates_presence_of :nombre
end
