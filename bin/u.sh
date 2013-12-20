#!/bin/sh
# Inicia produccion
unicorn_rails19 -c config/unicorn.conf.minimal.rb  -E production
