#!/bin/sh
# Inicia produccion
rake assets:precompile
echo "Iniciando unicorn..."
unicorn_rails19 -c config/unicorn.conf.minimal.rb  -E production
