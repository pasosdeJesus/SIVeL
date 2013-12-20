require 'spec_helper'

describe "actividad/index" do
  before(:each) do
    assign(:actividad, [
      stub_model(Actividad,
        :numero => 1,
        :minutos => 2,
        :nombre => "Nombre",
        :objetivo => "Objetivo",
        :proyecto => "Proyecto",
        :resultado => "Resultado",
        :actividad => "Actividad",
        :observaciones => "Observaciones"
      ),
      stub_model(Actividad,
        :numero => 1,
        :minutos => 2,
        :nombre => "Nombre",
        :objetivo => "Objetivo",
        :proyecto => "Proyecto",
        :resultado => "Resultado",
        :actividad => "Actividad",
        :observaciones => "Observaciones"
      )
    ])
  end

  it "renders a list of actividad" do
    render
    # Run the generator again with the --webrat flag if you want to use webrat matchers
    assert_select "tr>td", :text => 1.to_s, :count => 2
    assert_select "tr>td", :text => 2.to_s, :count => 2
    assert_select "tr>td", :text => "Nombre".to_s, :count => 2
    assert_select "tr>td", :text => "Objetivo".to_s, :count => 2
    assert_select "tr>td", :text => "Proyecto".to_s, :count => 2
    assert_select "tr>td", :text => "Resultado".to_s, :count => 2
    assert_select "tr>td", :text => "Actividad".to_s, :count => 2
    assert_select "tr>td", :text => "Observaciones".to_s, :count => 2
  end
end
