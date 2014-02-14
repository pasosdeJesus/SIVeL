require 'spec_helper'

describe "casos/index" do
  before(:each) do
    assign(:caso, [
      stub_model(Caso,
        :titulo => "Titulo",
        :hora => "Hora",
        :duracion => "Duracion",
        :memo => "MyText",
        :grconfiabilidad => "Grconfiabilidad",
        :gresclarecimiento => "Gresclarecimiento",
        :grimpunidad => "Grimpunidad",
        :grinformacion => "Grinformacion",
        :bienes => "MyText",
        :id_intervalo => 1
      ),
      stub_model(Caso,
        :titulo => "Titulo",
        :hora => "Hora",
        :duracion => "Duracion",
        :memo => "MyText",
        :grconfiabilidad => "Grconfiabilidad",
        :gresclarecimiento => "Gresclarecimiento",
        :grimpunidad => "Grimpunidad",
        :grinformacion => "Grinformacion",
        :bienes => "MyText",
        :id_intervalo => 1
      )
    ])
  end

  it "renders a list of caso" do
    render
    # Run the generator again with the --webrat flag if you want to use webrat matchers
    assert_select "tr>td", :text => "Titulo".to_s, :count => 2
    assert_select "tr>td", :text => "Hora".to_s, :count => 2
    assert_select "tr>td", :text => "Duracion".to_s, :count => 2
    assert_select "tr>td", :text => "MyText".to_s, :count => 2
    assert_select "tr>td", :text => "Grconfiabilidad".to_s, :count => 2
    assert_select "tr>td", :text => "Gresclarecimiento".to_s, :count => 2
    assert_select "tr>td", :text => "Grimpunidad".to_s, :count => 2
    assert_select "tr>td", :text => "Grinformacion".to_s, :count => 2
    assert_select "tr>td", :text => "MyText".to_s, :count => 2
    assert_select "tr>td", :text => 1.to_s, :count => 2
  end
end
