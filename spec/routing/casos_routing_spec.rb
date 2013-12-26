require "spec_helper"

describe CasosController do
  describe "routing" do

    it "routes to #index" do
      get("/caso").should route_to("caso#index")
    end

    it "routes to #new" do
      get("/caso/new").should route_to("caso#new")
    end

    it "routes to #show" do
      get("/caso/1").should route_to("caso#show", :id => "1")
    end

    it "routes to #edit" do
      get("/caso/1/edit").should route_to("caso#edit", :id => "1")
    end

    it "routes to #create" do
      post("/caso").should route_to("caso#create")
    end

    it "routes to #update" do
      put("/caso/1").should route_to("caso#update", :id => "1")
    end

    it "routes to #destroy" do
      delete("/caso/1").should route_to("caso#destroy", :id => "1")
    end

  end
end
