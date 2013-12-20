require 'spec_helper'

describe "actividad/show" do
  before(:each) do
    @actividad = assign(:actividad, stub_model(Actividad,
      :numero => 1,
      :minutos => 2,
      :nombre => "Nombre",
      :objetivo => "Objetivo",
      :proyecto => "Proyecto",
      :resultado => "Resultado",
      :actividad => "Actividad",
      :observaciones => "Observaciones"
    ))
  end

  it "renders attributes in <p>" do
    render
    # Run the generator again with the --webrat flag if you want to use webrat matchers
    rendered.should match(/1/)
    rendered.should match(/2/)
    rendered.should match(/Nombre/)
    rendered.should match(/Objetivo/)
    rendered.should match(/Proyecto/)
    rendered.should match(/Resultado/)
    rendered.should match(/Actividad/)
    rendered.should match(/Observaciones/)
  end
end
