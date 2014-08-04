require 'spec_helper'

describe "Casos" do
	describe "GET /caso" do
		it "muestra nuevos casos" do
			Caso.create!(:memo => 'memo', :fecha => '2014-01-01')
			get casos_path
			response.status.should be(200)
			response.body.should include("2014-01-01")
		end
	end
end

