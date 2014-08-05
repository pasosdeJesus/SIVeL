require 'rails_helper'

RSpec.describe Usuario, :type => :model do
  it "valido" do
		usuario = FactoryGirl.build(:usuario)
		expect(usuario).to be_valid
	end

  it "no valido" do
		usuario = FactoryGirl.build(:usuario, nusuario: '')
		expect(usuario).not_to be_valid
	end

end
