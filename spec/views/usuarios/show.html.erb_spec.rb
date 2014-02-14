require 'spec_helper'

describe "usuarios/show" do
  before(:each) do
    @usuario = assign(:usuario, stub_model(Usuario,
      :id => "Id",
      :password => "Password",
      :nombre => "Nombre",
      :descripcion => "Descripcion",
      :rol => 1,
      :idioma => "Idioma",
      :email => "Email",
      :encrypted_password => "Encrypted Password",
      :reset_password_token => "Reset Password Token",
      :sign_in_count => 2,
      :current_sign_in_ip => "Current Sign In Ip",
      :last_sign_in_ip => "Last Sign In Ip"
    ))
  end

  it "renders attributes in <p>" do
    render
    # Run the generator again with the --webrat flag if you want to use webrat matchers
    rendered.should match(/Id/)
    rendered.should match(/Password/)
    rendered.should match(/Nombre/)
    rendered.should match(/Descripcion/)
    rendered.should match(/1/)
    rendered.should match(/Idioma/)
    rendered.should match(/Email/)
    rendered.should match(/Encrypted Password/)
    rendered.should match(/Reset Password Token/)
    rendered.should match(/2/)
    rendered.should match(/Current Sign In Ip/)
    rendered.should match(/Last Sign In Ip/)
  end
end
