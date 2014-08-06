# encoding: UTF-8

require 'spec_helper'

describe "Control de acceso " do
  before { 
    @usuario = FactoryGirl.create(:usuario, rol: Ability::ROLSIST, 
                                  regionsjr_id: 100)
                                  visit new_usuario_session_path 
                                  fill_in "Usuario", with: @usuario.nusuario
                                  fill_in "Clave", with: @usuario.password
                                  click_button "Iniciar Sesión"
                                  expect(page).to have_content("Administrar")
  }

  describe "sistematizador" do
    it "puede crear caso de su oficina" do
      visit new_caso_path
      @numcaso=find_field('Código').value
      fill_in "Fecha del Hecho", with: '2014-08-05'
      fill_in "Descripción", with: 'descripcion'
      fill_in "Dirección actual", with: 'direccion'
      fill_in "Teléfono", with: 'direccion'
      select("COMUNIDAD DE ACOGIDA", from: 'Como supo del SJR')
			click_button "Guardar"
		  expect(page).to have_content("2014-08-05")
      #puts page.body
			# Driver no acepta: accept_confirm do click_on "Eliminar" end
		  #expect(page).to have_content("Casos")
    end
  end

end
