class AddCreatedAtToCaso < ActiveRecord::Migration
  def change
    add_column :caso, :created_at, :datetime
    add_column :caso, :updated_at, :datetime
  end
end
