#!/usr/bin/awk
# Convierte volcado para insertar tablas básicas 
# a uno para actualizar o insertar
# 
# Convierte INSERT a UPDATE seguido del INSERT

/INSERT/ {
	delete campo;
	delete valor;
	if (match($1, /INSERT INTO .* \(/)<=0) { 
		print FILENAME ":" FNR ": Se esperaba INSERT INTO"; 
		exit 1; 
	}; 
	tabla = substr($1, RSTART+12, RLENGTH-14); 
	campo[0] = substr($1, RSTART + RLENGTH);
	pv = 0;
	pid = 0;
	nv = 0;
	for (n=2; n<=NF; n++) { 
		e = $n;
		if (substr(e, 1, 1) == " ") {
			e = substr(e, 2);
		}
		if (pv == 0 && match(e, /\) VALUES \(/) > 0) {
			pv=n;
			campo[pv-1] = substr(e, 1, RSTART-1);
			if (substr(campo[pv-1], 1, 1) == " ") {
				campo[pv-1] = substr(campo[pv-1], 2);
			}
			valor[0] = substr(e, RSTART + RLENGTH);
		} else if (pv == 0){
			campo[n-1] = e;
		} else {
			valor[n-pv] = e;
		}
	}; 
	if (pv==0) {
		print FILENAME ":" FNR ": Se esperaba VALUES ";
		exit 1; 
	}; 
	valor[n-pv-1]=substr(valor[n-pv-1], 1, length(valor[n-pv-1])-2);

	s="UPDATE " tabla " SET ";
	sep="";
	w = " WHERE ";
	sepw = "";
	for (i in campo) {
		if (campo[i]=="id" || campo[i] == "id_pais" || campo[i] == "id_departamento" || campo[i] == "id_municipio") {
			w = w sepw campo[i] "=" valor[i];
			sepw = " AND ";
		} else {
			s = s sep campo[i] "=" valor[i];
			sep=", ";
		}
	}
	print s " " w ";";
}

/.*/ {
	print $0;
}

BEGIN {
	FS=","
}
