# encoding: UTF-8
module Admin
  class AyudasestadoController < BasicasController
    before_action :set_ayudaestado, only: [:show, :edit, :update, :destroy]
    load_and_authorize_resource

    def clase 
      "ayudaestado"
    end

    # Use callbacks to share common setup or constraints between actions.
    def set_ayudaestado
      @basica = Ayudaestado.find(params[:id])
    end

    # Never trust parameters from the scary internet, only allow the white list through.
    def ayudaestado_params
      params.require(:ayudaestado).permit(
        :nombre, :fechacreacion, :fechadeshabilitacion
      )
    end

    def atributos_index
      ["id", "nombre", "fechacreacion", "fechadeshabilitacion"]
    end

    helper_method :clase, :atributos_index
  end
end
