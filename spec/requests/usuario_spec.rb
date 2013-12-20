require 'spec_helper'

describe "Usuarios" do
  describe "GET /usuario" do
    it "works! (now write some real specs)" do
      # Run the generator again with the --webrat flag if you want to use webrat methods/matchers
      get usuarios_path
      response.status.should be(200)
    end
  end
end
