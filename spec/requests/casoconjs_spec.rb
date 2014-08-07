# encoding: UTF-8

require 'spec_helper'

describe "Llenar caso con javascript", :js => true do
  before { 
    @usuario = FactoryGirl.create(:usuario, rol: Ability::ROLSIST, 
                                  regionsjr_id: 100)
                                  visit new_usuario_session_path 
                                  fill_in "Usuario", with: @usuario.nusuario
                                  fill_in "Clave", with: @usuario.password
                                  click_button "Iniciar Sesión"
                                  expect(page).to have_content("Administrar")
  }

  describe "sistematizador llena" do
    it "puede crear caso de su oficina" do
      visit new_caso_path
      @numcaso=find_field('Código').value

      # Datos básicos
      fill_in "Fecha de Recepción", with: '2014-08-04'
      fill_in "Fecha del Hecho", with: '2014-08-03'
      fill_in "Descripción", with: 'descripcion con javascript'
			click_button "Guardar"
		  expect(page).to have_content("2014-08-03")

    end
  end

end
