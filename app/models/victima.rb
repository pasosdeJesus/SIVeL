class Victima < ActiveRecord::Base
    belongs_to: caso
    belongs_to: persona
end
