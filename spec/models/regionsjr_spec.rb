require 'rails_helper'

RSpec.describe Regionsjr, :type => :model do
  it "valido" do
		regionsjr = FactoryGirl.build(:regionsjr)
		expect(regionsjr).to be_valid
	end

  it "no valido" do
		regionsjr = FactoryGirl.build(:regionsjr, nombre: '')
		expect(regionsjr).not_to be_valid
	end


end
