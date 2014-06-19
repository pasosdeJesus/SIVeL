class AddLockableToUsuario < ActiveRecord::Migration
  def change
    add_column :usuario, :failed_attempts, :integer, default: 0
    add_column :usuario, :unlock_token, :string
    add_column :usuario, :locked_at, :datetime
	end
end
