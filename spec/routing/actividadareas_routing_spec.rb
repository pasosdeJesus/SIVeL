require "spec_helper"

describe ActividadareasController do
  describe "routing" do

    it "routes to #index" do
      get("/actividadarea").should route_to("actividadarea#index")
    end

    it "routes to #new" do
      get("/actividadarea/new").should route_to("actividadarea#new")
    end

    it "routes to #show" do
      get("/actividadarea/1").should route_to("actividadarea#show", :id => "1")
    end

    it "routes to #edit" do
      get("/actividadarea/1/edit").should route_to("actividadarea#edit", :id => "1")
    end

    it "routes to #create" do
      post("/actividadarea").should route_to("actividadarea#create")
    end

    it "routes to #update" do
      put("/actividadarea/1").should route_to("actividadarea#update", :id => "1")
    end

    it "routes to #destroy" do
      delete("/actividadarea/1").should route_to("actividadarea#destroy", :id => "1")
    end

  end
end
