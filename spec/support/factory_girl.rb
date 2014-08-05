RSpec.configure do |config|
	config.include FactoryGirl::Syntax::Methods

	config.before(:suite) do
		DatabaseCleaner.strategy = :transaction
		DatabaseCleaner.clean_with(:truncation)
	end

	config.before(:each) do
		DatabaseCleaner.start
	end

	config.after(:each) do
		DatabaseCleaner.clean
	end
	#config.before(:suite) do
	#	begin
	#		DatabaseCleaner.start
	#		FactoryGirl.lint
	#	ensure
	#		DatabaseCleaner.clean
	#	end
	#end
end
