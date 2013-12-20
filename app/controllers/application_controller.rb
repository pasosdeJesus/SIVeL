class ApplicationController < ActionController::Base
  before_filter :configure_permitted_parameters, if: :devise_controller?
  before_filter do
	  resource = controller_name.singularize.to_sym
	  method = "#{resource}_params"
	  params[resource] &&= send(method) if respond_to?(method, true)
  end

  # Prevent CSRF attacks by raising an exception.
  # For APIs, you may want to use :null_session instead.
  protect_from_forgery with: :exception

  def current_ability
	  @current_ability ||= Ability.new(current_usuario)
  end

  protected

  def configure_permitted_parameters
	  devise_parameter_sanitizer.for(:sign_up) << [:id, :password]
  end
end
