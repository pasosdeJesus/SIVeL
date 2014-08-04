require "spec_helper"

describe UsuariosController do
  describe "routing" do

    it "routes to #index" do
      get("/usuario").should route_to("usuario#index")
    end

    it "routes to #new" do
      get("/usuario/new").should route_to("usuario#new")
    end

    it "routes to #show" do
      get("/usuario/1").should route_to("usuario#show", :id => "1")
    end

    it "routes to #edit" do
      get("/usuario/1/edit").should route_to("usuario#edit", :id => "1")
    end

    it "routes to #create" do
      post("/usuario").should route_to("usuario#create")
    end

    it "routes to #update" do
      patch("/usuario/1").should route_to("usuario#update", :id => "1")
    end

    it "routes to #destroy" do
      delete("/usuario/1").should route_to("usuario#destroy", :id => "1")
    end

  end
end
