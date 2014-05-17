class Usuario < ActiveRecord::Base
  # Include default devise modules. Others available are:
  # :recoverable :registerable, :confirmable, :lockable, :timeoutable and :omniauthable
  devise :database_authenticatable, :rememberable, :trackable

	has_many :caso_usuario, foreign_key: "id_usuario", validate: true
	has_many :caso_etiqueta, foreign_key: "id_usuario", validate: true
	has_many :casosjr, foreign_key: "asesor", validate: true

  #http://stackoverflow.com/questions/1200568/using-rails-how-can-i-set-my-primary-key-to-not-be-an-integer-typed-column
  self.primary_key=:id

  def email_required?
    false
  end
  validates_uniqueness_of    :nusuario,     :case_sensitive => false, :allow_blank => true
  validates_format_of :nusuario, :with  => /\A[a-zA-Z_0-9]+\z/, :allow_blank => true

  validates_presence_of   :encrypted_password, :on=>:create
  validates_confirmation_of   :encrypted_password, :on=>:create
  #validates_length_of :password, :within => Devise.password_length, :allow_blank => true

end
