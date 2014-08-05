require 'rails_helper'

RSpec.describe Pais, :type => :model do
  it "valido" do
		pais = FactoryGirl.build(:pais)
		expect(pais).to be_valid
	end

  it "no valido" do
		pais = FactoryGirl.build(:pais, nombre: '')
		expect(pais).not_to be_valid
	end

end
