module ControllerHelpers
	def sign_in(usuario = double('usuario'))
		if usuario.nil?
			allow(request.env['warden']).to receive(:authenticate!).and_throw(:warden, {:scope => :usuario})
			allow(controller).to receive(:current_usuario).and_return(nil)
		else
			allow(request.env['warden']).to receive(:authenticate!).and_return(usuario)
			allow(controller).to receive(:current_usuario).and_return(usuario)
		end
	end
end

RSpec.configure do |config|
end
