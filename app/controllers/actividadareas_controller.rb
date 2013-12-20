class ActividadareasController < ApplicationController
  before_action :set_actividadarea, only: [:show, :edit, :update, :destroy]
  load_and_authorize_resource

  # GET /actividadareas
  # GET /actividadareas.json
  def index
    @actividadareas = Actividadarea.all
  end

  # GET /actividadareas/1
  # GET /actividadareas/1.json
  def show
  end

  # GET /actividadareas/new
  def new
    @actividadarea = Actividadarea.new
  end

  # GET /actividadareas/1/edit
  def edit
  end

  # POST /actividadareas
  # POST /actividadareas.json
  def create
    @actividadarea = Actividadarea.new(actividadarea_params)

    respond_to do |format|
      if @actividadarea.save
        format.html { redirect_to @actividadarea, notice: 'Actividadarea was successfully created.' }
        format.json { render action: 'show', status: :created, location: @actividadarea }
      else
        format.html { render action: 'new' }
        format.json { render json: @actividadarea.errors, status: :unprocessable_entity }
      end
    end
  end

  # PATCH/PUT /actividadareas/1
  # PATCH/PUT /actividadareas/1.json
  def update
    respond_to do |format|
      if @actividadarea.update(actividadarea_params)
        format.html { redirect_to @actividadarea, notice: 'Actividadarea was successfully updated.' }
        format.json { head :no_content }
      else
        format.html { render action: 'edit' }
        format.json { render json: @actividadarea.errors, status: :unprocessable_entity }
      end
    end
  end

  # DELETE /actividadareas/1
  # DELETE /actividadareas/1.json
  def destroy
    @actividadarea.destroy
    respond_to do |format|
      format.html { redirect_to actividadareas_url }
      format.json { head :no_content }
    end
  end

  private
    # Use callbacks to share common setup or constraints between actions.
    def set_actividadarea
      @actividadarea = Actividadarea.find(params[:id])
    end

    # Never trust parameters from the scary internet, only allow the white list through.
    def actividadarea_params
      params.require(:actividadarea).permit(:nombre, :observaciones, :fechacreacion, :fechadeshabilitacion)
    end
end
