require 'spec_helper'

describe "actividadareas/show" do
  before(:each) do
    @actividadarea = assign(:actividadarea, stub_model(Actividadarea,
      :nombre => "Nombre",
      :observaciones => "Observaciones"
    ))
  end

  it "renders attributes in <p>" do
    render
    # Run the generator again with the --webrat flag if you want to use webrat matchers
    rendered.should match(/Nombre/)
    rendered.should match(/Observaciones/)
  end
end
