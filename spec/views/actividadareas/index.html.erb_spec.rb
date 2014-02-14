require 'spec_helper'

describe "actividadareas/index" do
  before(:each) do
    assign(:actividadarea, [
      stub_model(Actividadarea,
        :nombre => "Nombre",
        :observaciones => "Observaciones"
      ),
      stub_model(Actividadarea,
        :nombre => "Nombre",
        :observaciones => "Observaciones"
      )
    ])
  end

  it "renders a list of actividadarea" do
    render
    # Run the generator again with the --webrat flag if you want to use webrat matchers
    assert_select "tr>td", :text => "Nombre".to_s, :count => 2
    assert_select "tr>td", :text => "Observaciones".to_s, :count => 2
  end
end
