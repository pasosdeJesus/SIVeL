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

      # Datos básicos
      fill_in "Fecha de Recepción", with: '2014-08-06'
      fill_in "Fecha del Hecho", with: '2014-08-05'
      fill_in "Descripción", with: 'descripcion'
			click_button "Guardar"
		  expect(page).to have_content("2014-08-05")

      # Solicitante Principal
			click_on "Editar"
      fill_in "Dirección actual", with: 'direccion'
      fill_in "Teléfono", with: '1234'
      select("COMUNIDAD DE ACOGIDA", from: 'Como supo del SJR')
      fill_in "Nombres", with: 'Nombres Solicitante'
      fill_in "Apellidos", with: 'Apellidos Solicitante'
      fill_in "Año Nacimiento", with: '1999'
      fill_in "Mes Nacimiento", with: '1'
      fill_in "Día Nacimiento", with: '1'
      select("MASCULINO", from: 'Sexo')
      select("CÉDULA DE CIUDADANÍA", from: 'Tipo de Documento')
      fill_in "Número Documento", with: '19222'
      select('ALBANIA', from: 'País de Nacionalidad')
      select('RUSIA', from: 'País de Nacimiento')
      select('OTRO', from: 'Profesión')
      select('De 0 a 15 Años', from: 'Rango de Edad')
      select('ROM', from: 'Etnia') 
      select('IGLESIA DE DIOS', from: 'Religión/Iglesia') 
      select('HETEROSEXUAL', from: 'Orientación Sexual') 
      select('CASADO/A', from: 'Estado Civil') 
      select('HIJO(A)', from: 'Rol en Familia') 
      select('GESTANTE', from: 'Maternidad') 
      select('SENSORIAL', from: 'Discapacidad') 
      fill_in "Enfermedad", with: 'Enfermedad'
      select('PESCADOR', from: 'Actividad/Oficio actual') 
      select('PRIMARIA', from: 'Nivel Escolar') 
      fill_in "Cuantos dependen econ", with: '10'
      fill_in "Sustento familiar", with: 'Papá'
      check 'Sabe leer y escribir'
      fill_in "Ingreso mensual", with: '1000'
      fill_in "Estimado de gastos", with: '1000'
      select('B', from: 'Estrato') 
      select('ALEMÁN', from: 'Idioma') 
      select('MIGRANTE', from: 'Status migratorio') 
      select('NO RECONOCIDOS', from: 'Necesidad de protección internacional') 
      check 'Concentimiento Informado compartir SJR'
      check 'Concentimiento Informado compartir BD'
      click_button "Guardar"
		  expect(page).to have_content("2014-08-05") 
      puts page.body
			# Driver no acepta: accept_confirm do click_on "Eliminar" end
		  #expect(page).to have_content("Casos")
    end

  end

end
