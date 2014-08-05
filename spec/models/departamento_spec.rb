require 'rails_helper'

RSpec.describe Departamento, :type => :model do
  it "valido" do
		departamento = FactoryGirl.build(:departamento)
		expect(departamento).to be_valid
	end

  it "no valido" do
		departamento = FactoryGirl.build(:departamento, nombre: '')
		expect(departamento).not_to be_valid
	end

end
