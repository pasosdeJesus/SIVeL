require 'spec_helper'

describe "usuario/new" do
  before(:each) do
    assign(:usuario, stub_model(Usuario,
      :id => "MyString",
      :password => "MyString",
      :nombre => "MyString",
      :descripcion => "MyString",
      :rol => 1,
      :idioma => "MyString",
      :email => "MyString",
      :encrypted_password => "MyString",
      :reset_password_token => "MyString",
      :sign_in_count => 1,
      :current_sign_in_ip => "MyString",
      :last_sign_in_ip => "MyString"
    ).as_new_record)
  end

  it "renders new usuario form" do
    render

    # Run the generator again with the --webrat flag if you want to use webrat matchers
    assert_select "form[action=?][method=?]", usuarios_path, "post" do
      assert_select "input#usuario_id[name=?]", "usuario[id]"
      assert_select "input#usuario_password[name=?]", "usuario[password]"
      assert_select "input#usuario_nombre[name=?]", "usuario[nombre]"
      assert_select "input#usuario_descripcion[name=?]", "usuario[descripcion]"
      assert_select "input#usuario_rol[name=?]", "usuario[rol]"
      assert_select "input#usuario_idioma[name=?]", "usuario[idioma]"
      assert_select "input#usuario_email[name=?]", "usuario[email]"
      assert_select "input#usuario_encrypted_password[name=?]", "usuario[encrypted_password]"
      assert_select "input#usuario_reset_password_token[name=?]", "usuario[reset_password_token]"
      assert_select "input#usuario_sign_in_count[name=?]", "usuario[sign_in_count]"
      assert_select "input#usuario_current_sign_in_ip[name=?]", "usuario[current_sign_in_ip]"
      assert_select "input#usuario_last_sign_in_ip[name=?]", "usuario[last_sign_in_ip]"
    end
  end
end
