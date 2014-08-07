#!/usr/bin/env ruby
# Deja en orden alfabético INSERTs en volcado de PostgreSQL
# Dominio público.

inserto = false
ordeno = false
porord = []
ARGF.each_line { |line| 
 
  if line[0,6] == "INSERT"
    inserto=true
    porord << line
  else
    if !inserto || (inserto && ordeno) 
        print line
    else
        porord.sort!
        porord.each { |l|
          print l
        }
        ordeno = true
        print line
    end
  end
}

