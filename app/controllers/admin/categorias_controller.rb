# encoding: UTF-8
module Admin
  class CategoriasController < BasicasController
    before_action :set_categoria, only: [:show, :edit, :update, :destroy]
    load_and_authorize_resource

    def clase 
      "categoria"
    end

    # Use callbacks to share common setup or constraints between actions.
    def set_categoria
      @basica = Categoria.find(params[:id])
    end

    def atributos_index
      ["id", "nombre", "id_tviolencia", "id_supracategoria",
        "contadaen", "tipocat", "fechacreacion", "fechadeshabilitacion"
      ]
    end

    def atributos_form
      atributos_index
    end

    # Never trust parameters from the scary internet, only allow the white list through.
    def categoria_params
      params.require(:categoria).permit(*atributos_form)
    end

  end
end
