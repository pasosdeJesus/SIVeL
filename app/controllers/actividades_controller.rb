class ActividadesController < ApplicationController
  before_action :set_actividad, only: [:show, :edit, :update, :destroy]
  load_and_authorize_resource

  # GET /actividades
  # GET /actividades.json
  def index
    @actividades = Actividad.all
  end

  # GET /actividades/1
  # GET /actividades/1.json
  def show
  end

  # GET /actividades/new
  def new
    @actividad = Actividad.new
  end

  # GET /actividades/1/edit
  def edit
  end

  # POST /actividades
  # POST /actividades.json
  def create
    @actividad = Actividad.new(actividad_params)

    respond_to do |format|
      if @actividad.save
        format.html { redirect_to @actividad, notice: 'Actividad was successfully created.' }
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
        format.html { redirect_to @actividad, notice: 'Actividad was successfully updated.' }
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
    end

    # Never trust parameters from the scary internet, only allow the white list through.
    def actividad_params
      params.require(:actividad).permit(:numero, :minutos, :nombre, :objetivo, :proyecto, :resultado, :fecha, :actividad, :observaciones, :actividadarea_ids => [])
    end
end
