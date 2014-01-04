class Usuario < ActiveRecord::Base
  # Include default devise modules. Others available are:
  # :recoverable :registerable, :confirmable, :lockable, :timeoutable and :omniauthable
  devise :database_authenticatable, :rememberable, :trackable, :validatable, :registerable

	has_many :caso_usuario, foreign_key: "id_usuario", validate: true
	has_many :caso_etiqueta, foreign_key: "id_usuario", validate: true
	has_many :casosjr, foreign_key: "asesor", validate: true

  #http://stackoverflow.com/questions/1200568/using-rails-how-can-i-set-my-primary-key-to-not-be-an-integer-typed-column
  self.primary_key=:id
end
