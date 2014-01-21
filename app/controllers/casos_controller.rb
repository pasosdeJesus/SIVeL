class CasosController < ApplicationController
  before_action :set_caso, only: [:show, :edit, :update, :destroy]
  load_and_authorize_resource

  # GET /casos
  # GET /casos.json
  def index
    @casos = Caso.all
  end

  # GET /casos/1
  # GET /casos/1.json
  def show
  end

  # GET /casos/new
  def new
    @caso = Caso.new
    @caso.casosjr = Casosjr.new
  end

  # GET /casos/1/edit
  def edit
  end

  # POST /casos
  # POST /casos.json
  def create
    @caso = Caso.new(caso_params)
    @caso.memo = ''
    @caso.titulo = ''

    respond_to do |format|
      if @caso.save
        format.html { redirect_to @caso, notice: 'Caso creado.' }
        format.json { render action: 'show', status: :created, location: @caso }
      else
        format.html { render action: 'new' }
        format.json { render json: @caso.errors, status: :unprocessable_entity }
      end
    end
  end

  def elimina_dep
    @caso.victimasjr.clear
    @caso.victima.clear
    @caso.caso_etiqueta.clear
    @caso.desplazamiento.clear
    @caso.actosjr.clear
    @caso.acto.clear
    @caso.respuesta.each { |r| r.ayudasjr.clear }
  end

  # PATCH/PUT /casos/1
  # PATCH/PUT /casos/1.json
  def update
    respond_to do |format|
    # http://archive.railsforum.com/viewtopic.php?id=41569
    # child = ChildModel.save(params[:child].except(:parent_attributes))
    # parent = Parent.new(params[:child][:parent_attributes].merge(:child_id => child.id))
    # parent.save
    #  k = params[:caso][:victima_attributes].keys[0]
    #  if params[:caso][:victima_attributes][k][:id_persona] == ''
    #    if params[:pnueva_nombres] != ''
    #        p = Persona.new
    #        p.nombres = params[:pnueva_nombres]
    #        p.apellidos = params[:pnueva_apellidos]
    #        p.anionac = params[:pnueva_anionac]
    #        p.mesnac= params[:pnueva_mesnac]
    #        p.dianac= params[:pnueva_dianac]
    #        p.sexo = params[:pnueva_sexo]
    #        p.id_departamento = params[:pnueva_id_departamento]
    #        p.tipodocumento = params[:pnueva_tipodocumento]
    #        p.numerodocumento = params[:pnueva_numerodocumento]
    #        p.save
    #        params[:caso][:victima_attributes][k][:id_persona] = p.id
    #        debugger
    #    else
    #      flash[:error] = "Falta nombre de víctima"
    #    end
    #  end
      if @caso.valid?
        elimina_dep
        if (!params[:caso][:actosjr_attributes].nil?) 
          params[:caso][:actosjr_attributes].each {|k,v| 
            acto = Acto.new
            acto.id_presponsable = v[:id_presponsable]
            acto.id_persona = v[:id_persona]
            acto.id_categoria = v[:id_categoria]
            acto.id_caso = @caso.id
            acto.save
          }
        end
        if @caso.update(caso_params)
          format.html { redirect_to @caso, notice: 'Caso actualizado.' }
          format.json { head :no_content }
        else
          format.html { render action: 'edit' }
          format.json { render json: @caso.errors, status: :unprocessable_entity }
        end
      end
    end
  end

  # DELETE /casos/1
  # DELETE /casos/1.json
  def destroy
    elimina_dep
    @caso.casosjr.destroy
    @caso.destroy
    respond_to do |format|
      format.html { redirect_to casos_url }
      format.json { head :no_content }
    end
  end

  private
    # Use callbacks to share common setup or constraints between actions.
    def set_caso
      @caso = Caso.find(params[:id])
    end

    # Never trust parameters from the scary internet, only allow the white list through.
    def caso_params
      params.require(:caso).permit(:id, :titulo, :fecha, :hora, :duracion, 
        :grconfiabilidad, :gresclarecimiento, :grimpunidad, :grinformacion, 
        :bienes, :id_intervalo, :memo, 
        :casosjr_attributes => [:fecharec, :asesor, :id_regionsjr, :direccion, 
          :telefono, :comosupo, :contacto, :_destroy], 
        :victima_attributes => [:id_persona, :id_profesion, 
          :id_rangoedad, :id_etnia, :id_iglesia, :orientacionsexual, 
          :_destroy, :persona_attributes => [:id_persona, :nombres, 
            :apellidos, :anionac, :mesnac, :dianac, :numerodocumento,
            :sexo, :id_departamento, :tipodocumento]], 
        :ubicacion_attributes => [:id, :id_departamento, :id_municipio, 
          :id_clase, :lugar, :sitio, :latitud, :longitud, :id_tsitio, 
          :_destroy],
        :desplazamiento_attributes => [:fechaexpulsion, :id_expulsion, 
          :fechallegada, :id_llegada, :descripcion, :_destroy],
        :caso_presponsable_attributes => [:id_presponsable, :id, :tipo, 
          :bloque, :frente, :brigada, :batallon, :division, :otro, :_destroy],
        :actosjr_attributes => [:id_presponsable, :id_categoria, 
          :id_persona, :fecha, :fechaexpulsion, :_destroy],
        :respuesta_attributes => [:id, :fechaatencion, :fechaexpulsion,
          :descamp, :observaciones, :orientaciones, :compromisos,
          :gestionessjr, :_destroy, 
          :ayudasjr_respuesta_attributes => 
            [:id_ayudasjr, :detallear, :_destroy]
        ],
        :anexo_attributes => [:id, :fecha, :descripcion, :archivo, :_destroy],
        :caso_etiqueta_attributes => [:id_etiqueta, :observaciones, :_destroy]
      )
    end
end
