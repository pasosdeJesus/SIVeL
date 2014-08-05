require 'rails_helper'

RSpec.describe Municipio, :type => :model do
  it "valido" do
		municipio = FactoryGirl.build(:municipio)
		expect(municipio).to be_valid
	end

  it "no valido" do
		municipio = FactoryGirl.build(:municipio, nombre: '')
		expect(municipio).not_to be_valid
	end

end
