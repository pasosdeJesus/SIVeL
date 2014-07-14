class ActividadesController < ApplicationController
  before_action :set_actividad, only: [:show, :edit, :update, :destroy]
  load_and_authorize_resource

  # GET /actividades
  # GET /actividades.json
  def index
    @actividades = @actividades.paginate(:page => params[:pagina], per_page: 20)
  end

  # GET /actividades/1
  # GET /actividades/1.json
  def show
  end

  # GET /actividades/new
  def new
    @actividad.current_usuario = current_usuario
    @actividad.regionsjr_id = current_usuario.regionsjr_id.nil? ?  
      1 : current_usuario.regionsjr_id
  end

  # GET /actividades/1/edit
  def edit
  end

  # POST /actividades
  # POST /actividades.json
  def create
    @actividad.current_usuario = current_usuario

    respond_to do |format|
      if @actividad.save
        format.html { redirect_to @actividad, notice: 'Actividad creada.' }
        format.json { render action: 'show', status: :created, location: @actividad }
      else
        format.html { render action: 'new' }
        format.json { render json: @actividad.errors, status: :unprocessable_entity }
      end
    end
  end

  # PATCH/PUT /actividades/1
  # PATCH/PUT /actividades/1.json
  def update
    respond_to do |format|
      if @actividad.update(actividad_params)
        format.html { redirect_to @actividad, notice: 'Actividad actualizada.' }
        format.json { head :no_content }
      else
        format.html { render action: 'edit' }
        format.json { render json: @actividad.errors, status: :unprocessable_entity }
      end
    end
  end

  # DELETE /actividades/1
  # DELETE /actividades/1.json
  def destroy
    @actividad.destroy
    respond_to do |format|
      format.html { redirect_to actividades_url }
      format.json { head :no_content }
    end
  end

  private
    # Use callbacks to share common setup or constraints between actions.
    def set_actividad
      @actividad = Actividad.find(params[:id])
    	@actividad.current_usuario = current_usuario
    end

    # Never trust parameters from the scary internet, only allow the white list through.
    def actividad_params
      params.require(:actividad).permit(:regionsjr_id, :minutos, :nombre, 
        :objetivo, :proyecto, :resultado, :fecha, :actividad, 
        :observaciones, :actividadarea_ids => [],
        :actividad_rangoedadac_attributes => 
            [:id, :rangoedadac_id, :fl, :fr, :ml, :mr, :_destroy] 
      )
    end
end
