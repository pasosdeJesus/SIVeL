class Usuario < ActiveRecord::Base
  # Include default devise modules. Others available are:
  # :recoverable :registerable, :confirmable, :lockable, :timeoutable and :omniauthable
  devise :database_authenticatable, :rememberable, :trackable, :validatable, :registerable

  #http://stackoverflow.com/questions/1200568/using-rails-how-can-i-set-my-primary-key-to-not-be-an-integer-typed-column
  self.primary_key=:id
end
