require 'spec_helper'

describe "actividades/new" do
  before(:each) do
    assign(:actividad, stub_model(Actividad,
      :numero => 1,
      :minutos => 1,
      :nombre => "MyString",
      :objetivo => "MyString",
      :proyecto => "MyString",
      :resultado => "MyString",
      :actividad => "MyString",
      :observaciones => "MyString"
    ).as_new_record)
  end

  it "renders new actividad form" do
    render

    # Run the generator again with the --webrat flag if you want to use webrat matchers
    assert_select "form[action=?][method=?]", actividades_path, "post" do
      assert_select "input#actividad_numero[name=?]", "actividad[numero]"
      assert_select "input#actividad_minutos[name=?]", "actividad[minutos]"
      assert_select "input#actividad_nombre[name=?]", "actividad[nombre]"
      assert_select "input#actividad_objetivo[name=?]", "actividad[objetivo]"
      assert_select "input#actividad_proyecto[name=?]", "actividad[proyecto]"
      assert_select "input#actividad_resultado[name=?]", "actividad[resultado]"
      assert_select "input#actividad_actividad[name=?]", "actividad[actividad]"
      assert_select "input#actividad_observaciones[name=?]", "actividad[observaciones]"
    end
  end
end
