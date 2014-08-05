require 'spec_helper'

describe "Usuarios" do

  describe "inicio de sesion" do
    it "no autentica" do
		  usuario = FactoryGirl.create(:usuario)
      visit new_usuario_session_path 
			fill_in "Usuario", with: usuario.nusuario
			fill_in "Clave", with: 'ERRADA'
			click_button "Iniciar Sesión"
		  expect(page).not_to have_content("Administrar")
    end

    it "autentica" do
		  usuario = FactoryGirl.create(:usuario)
      visit new_usuario_session_path 
			fill_in "Usuario", with: usuario.nusuario
			fill_in "Clave", with: usuario.password
			click_button "Iniciar Sesión"
		  expect(page).to have_content("Administrar")
    end
  end

end
