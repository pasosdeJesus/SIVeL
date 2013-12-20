# Load the Rails application.
require File.expand_path('../application', __FILE__)

ActiveRecord::Base.pluralize_table_names=false

# Initialize the Rails application.
Sivel2::Application.initialize!
