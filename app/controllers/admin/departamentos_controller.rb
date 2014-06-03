# encoding: UTF-8
module Admin
  class DepartamentosController < BasicasController
    before_action :set_departamento, only: [:show, :edit, :update, :destroy]
    load_and_authorize_resource

    def clase 
      "departamento"
    end

    # Use callbacks to share common setup or constraints between actions.
    def set_departamento
      @basica = Departamento.find(params[:id])
    end

    def atributos_index
      ["id", "nombre", "latitud", "longitud", "id_pais",
        "fechacreacion", "fechadeshabilitacion"]
    end

    def atributos_form
      atributos_index
    end

    # Never trust parameters from the scary internet, only allow the white list through.
    def departamento_params
      params.require(:departamento).permit(*atributos_form)
    end

  end
end
