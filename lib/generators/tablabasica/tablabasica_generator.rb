class TablabasicaGenerator < Rails::Generators::NamedBase
  source_root File.expand_path('../templates', __FILE__)
  # para crear modelo: 
  # sed "s/idioma/${ms}/g;s/Idiomas/${Mp}/g;s/Idioma/${Ms}/g" app/controllers/admin/idiomas_controller.rb > app/controllers/admin/${mp}_controller.rb
  def crea_controlador
    create_file "app/controllers/admin/#{plural_name}_controller.rb", <<-FILE
# encoding: UTF-8
module Admin
  class #{class_name.pluralize}Controller < BasicasController
    before_action :set_#{file_name}, only: [:show, :edit, :update, :destroy]
    load_and_authorize_resource

    def clase 
      "#{file_name}"
    end

    # Use callbacks to share common setup or constraints between actions.
    def set_#{file_name}
      @basica = #{class_name}.find(params[:id])
    end

    def atributos_index
      ["id", "nombre", "fechacreacion", "fechadeshabilitacion"]
    end

    # Never trust parameters from the scary internet, only allow the white list through.
    def #{file_name}_params
      params.require(:#{file_name}).permit( *(atributos_index - ["id"]))
    end

    helper_method :clase, :atributos_index
  end
end
FILE
    gsub_file("app/models/ability.rb", /(@@tablasbasicas = \[.*)/, 
              "\1\n    '#{file_name}',")
  end
end
