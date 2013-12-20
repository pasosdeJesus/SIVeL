require 'spec_helper'

describe "usuario/index" do
  before(:each) do
    assign(:usuario, [
      stub_model(Usuario,
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
      ),
      stub_model(Usuario,
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
      )
    ])
  end

  it "renders a list of usuario" do
    render
    # Run the generator again with the --webrat flag if you want to use webrat matchers
    assert_select "tr>td", :text => "Id".to_s, :count => 2
    assert_select "tr>td", :text => "Password".to_s, :count => 2
    assert_select "tr>td", :text => "Nombre".to_s, :count => 2
    assert_select "tr>td", :text => "Descripcion".to_s, :count => 2
    assert_select "tr>td", :text => 1.to_s, :count => 2
    assert_select "tr>td", :text => "Idioma".to_s, :count => 2
    assert_select "tr>td", :text => "Email".to_s, :count => 2
    assert_select "tr>td", :text => "Encrypted Password".to_s, :count => 2
    assert_select "tr>td", :text => "Reset Password Token".to_s, :count => 2
    assert_select "tr>td", :text => 2.to_s, :count => 2
    assert_select "tr>td", :text => "Current Sign In Ip".to_s, :count => 2
    assert_select "tr>td", :text => "Last Sign In Ip".to_s, :count => 2
  end
end
