#!/bin/sh
# Inicia produccion
sudo su - vtamara -c 'cd /var/www/htdocs/sivel2; rake assets:precompile; echo "Iniciando unicorn..."; unicorn_rails -c config/unicorn.conf.minimal.rb  -E production -D'

