require 'spec_helper'

describe "caso/edit" do
  before(:each) do
    @caso = assign(:caso, stub_model(Caso,
      :titulo => "MyString",
      :hora => "MyString",
      :duracion => "MyString",
      :memo => "MyText",
      :grconfiabilidad => "MyString",
      :gresclarecimiento => "MyString",
      :grimpunidad => "MyString",
      :grinformacion => "MyString",
      :bienes => "MyText",
      :id_intervalo => 1
    ))
  end

  it "renders the edit caso form" do
    render

    # Run the generator again with the --webrat flag if you want to use webrat matchers
    assert_select "form[action=?][method=?]", caso_path(@caso), "post" do
      assert_select "input#caso_titulo[name=?]", "caso[titulo]"
      assert_select "input#caso_hora[name=?]", "caso[hora]"
      assert_select "input#caso_duracion[name=?]", "caso[duracion]"
      assert_select "textarea#caso_memo[name=?]", "caso[memo]"
      assert_select "input#caso_grconfiabilidad[name=?]", "caso[grconfiabilidad]"
      assert_select "input#caso_gresclarecimiento[name=?]", "caso[gresclarecimiento]"
      assert_select "input#caso_grimpunidad[name=?]", "caso[grimpunidad]"
      assert_select "input#caso_grinformacion[name=?]", "caso[grinformacion]"
      assert_select "textarea#caso_bienes[name=?]", "caso[bienes]"
      assert_select "input#caso_id_intervalo[name=?]", "caso[id_intervalo]"
    end
  end
end
