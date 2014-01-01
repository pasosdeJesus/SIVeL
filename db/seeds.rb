# encoding: UTF-8
# This file should contain all the record creation needed to seed the database with its default values.
# The data can then be loaded with the rake db:seed (or created alongside the db with db:setup).
#
# Examples:
#
#   cities = City.create([{ name: 'Chicago' }, { name: 'Copenhagen' }])
#   Mayor.create(name: 'Emanuel', city: cities.first)

Actividadarea.create(id: 1, nombre: 'Psicosocial', fechacreacion: '2013-12-04')
Actividadarea.create(id: 2, nombre: 'Jurídica - Legal', fechacreacion: '2013-12-04')
Actividadarea.create(id: 3, nombre: 'Organización - Comunal', fechacreacion: '2013-12-04')
Actividadarea.create(id: 4, nombre: 'Emprendimiento', fechacreacion: '2013-12-04')
Actividadarea.create(id: 5, nombre: 'Incidencia', fechacreacion: '2013-12-04')

connection = ActiveRecord::Base.connection();
connection.execute("INSERT INTO usuario 
	(nusuario, email, encrypted_password, password, created_at, 
        updated_at, rol) 
	VALUES ('sjrven', 'sjrven@sjrven.org', 
	'$2a$10$uMAciEcJuUXDnpelfSH6He7BxW0yBeq6VMemlWc5xEl6NZRDYVA3G', 
	'', '2013-12-24', '2013-12-24', 1);")

