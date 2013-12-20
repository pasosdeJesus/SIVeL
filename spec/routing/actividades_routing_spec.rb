require "spec_helper"

describe ActividadesController do
  describe "routing" do

    it "routes to #index" do
      get("/actividad").should route_to("actividad#index")
    end

    it "routes to #new" do
      get("/actividad/new").should route_to("actividad#new")
    end

    it "routes to #show" do
      get("/actividad/1").should route_to("actividad#show", :id => "1")
    end

    it "routes to #edit" do
      get("/actividad/1/edit").should route_to("actividad#edit", :id => "1")
    end

    it "routes to #create" do
      post("/actividad").should route_to("actividad#create")
    end

    it "routes to #update" do
      put("/actividad/1").should route_to("actividad#update", :id => "1")
    end

    it "routes to #destroy" do
      delete("/actividad/1").should route_to("actividad#destroy", :id => "1")
    end

  end
end
