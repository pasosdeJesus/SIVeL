# encoding: UTF-8

require 'spec_helper'

describe "Llenar caso con javascript", :js => true do
  before { 
    usuario = Usuario.find_by(nusuario: 'sjrven')
    usuario.password = 'sjrven123'
    visit new_usuario_session_path 
    fill_in "Usuario", with: usuario.nusuario
    fill_in "Clave", with: usuario.password
    click_button "Iniciar Sesión"
    #print page.html
    #page.save_screenshot('s.png')
    #save_and_open_page
    expect(page).to have_content("Administrar")
  }

  describe "administrador llena" do
    it "puede crear caso" do
      visit new_caso_path
      @numcaso=find_field('Código').value

      # Datos básicos
      fill_in "Fecha de Recepción", with: '2014-08-04'
      fill_in "Fecha del Hecho", with: '2014-08-03'
      fill_in "Descripción", with: 'descripcion con javascript'

      # Núcleo familiar
      click_on "Núcleo Familiar"
      click_on "Añadir Víctima"
      within ("div#victima") do 
        fill_in "Nombres", with: 'Nombres Beneficiario'
        fill_in "Apellidos", with: 'Apellidos Beneficiario'
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
      end
      #click_button "Guardar"
      #expect(page).to have_content("2014-08-03")
      #click_on "Editar"
      click_on "Núcleo Familiar"  # Cerrar requerido en navegadores

      # Sitios Geográficos
      click_on "Sitios geográficos de refugios y desplazamientos"
      click_on "Añadir Sitio Geográfico"
      within ("div#ubicacion") do 
        select('VENEZUELA', from: 'País') 
        select('ARAGUA', from: 'Estado/Departamento') 
        select('CAMATAGUA', from: 'Municipio') 
        select('CARMEN DE CURA', from: 'Centro Poblado') 
        fill_in "Lugar", with: 'Lugar'
        fill_in "Sitio", with: 'Sitio'
        fill_in "Latitud", with: '4.1'
        fill_in "Longitud", with: '-74.3'
        select('URBANO', from: 'Tipo de Sitio') 
      end
      click_on "Añadir Sitio Geográfico"
      su = "//div[@id='ubicacion']/div/div[2]"
      within(:xpath, su) do 
        select('COLOMBIA', from: 'País') 
        select('BOYACÁ', from: 'Estado/Departamento') 
        select('CHISCAS', from: 'Municipio') 
        select('CHISCAS', from: 'Centro Poblado') 
        fill_in "Lugar", with: 'Lugar2'
        fill_in "Sitio", with: 'Sitio2'
        fill_in "Latitud", with: '4.2'
        fill_in "Longitud", with: '-74.32'
        select('RURAL', from: 'Tipo de Sitio') 
      end
      click_on "Sitios geográficos de refugios y desplazamientos"
      #click_button "Guardar"
      #expect(page).to have_content("2014-08-03")
      #click_on "Editar"
      # Desplazamiento
      click_on "Refugio"
      expect(page).to have_field('Fecha de Salida', with: '2014-08-03')
      page.save_screenshot('s.png')
      print page.html
      within ("div#refugio") do 
        select('VENEZUELA / ARAGUA', from: 'Sitio de Salida') 
        fill_in "Fecha de Llegada", with: '2014-08-04'
        select('COLOMBIA / BOYACA', from: 'Sitio de Llegada') 
        select('R2000 RAZA', from: 'Causa del Refugio') 
        fill_in "Observaciones", with: 'Observaciones refugio'
      end
      click_on "Refugio"

      click_button "Guardar"
      expect(page).to have_content("2014-08-03")
    end
  end

end