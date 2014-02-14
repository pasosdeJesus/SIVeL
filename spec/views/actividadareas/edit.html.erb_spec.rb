require 'spec_helper'

describe "actividadareas/edit" do
  before(:each) do
    @actividadarea = assign(:actividadarea, stub_model(Actividadarea,
      :nombre => "MyString",
      :observaciones => "MyString"
    ))
  end

  it "renders the edit actividadarea form" do
    render

    # Run the generator again with the --webrat flag if you want to use webrat matchers
    assert_select "form[action=?][method=?]", actividadarea_path(@actividadarea), "post" do
      assert_select "input#actividadarea_nombre[name=?]", "actividadarea[nombre]"
      assert_select "input#actividadarea_observaciones[name=?]", "actividadarea[observaciones]"
    end
  end
end
