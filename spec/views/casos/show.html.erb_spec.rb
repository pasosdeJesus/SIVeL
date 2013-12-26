require 'spec_helper'

describe "caso/show" do
  before(:each) do
    @caso = assign(:caso, stub_model(Caso,
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
    ))
  end

  it "renders attributes in <p>" do
    render
    # Run the generator again with the --webrat flag if you want to use webrat matchers
    rendered.should match(/Titulo/)
    rendered.should match(/Hora/)
    rendered.should match(/Duracion/)
    rendered.should match(/MyText/)
    rendered.should match(/Grconfiabilidad/)
    rendered.should match(/Gresclarecimiento/)
    rendered.should match(/Grimpunidad/)
    rendered.should match(/Grinformacion/)
    rendered.should match(/MyText/)
    rendered.should match(/1/)
  end
end
