require 'spec_helper'

describe "actividadarea/new" do
  before(:each) do
    assign(:actividadarea, stub_model(Actividadarea,
      :nombre => "MyString",
      :observaciones => "MyString"
    ).as_new_record)
  end

  it "renders new actividadarea form" do
    render

    # Run the generator again with the --webrat flag if you want to use webrat matchers
    assert_select "form[action=?][method=?]", actividadareas_path, "post" do
      assert_select "input#actividadarea_nombre[name=?]", "actividadarea[nombre]"
      assert_select "input#actividadarea_observaciones[name=?]", "actividadarea[observaciones]"
    end
  end
end
