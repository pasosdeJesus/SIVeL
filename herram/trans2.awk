

# [str_from(str,i)] returns the substring of [str] that begins in position
# [i]
function str_from(str,i) {
        return substr(str,i,length(str)-i+1);
}

/.*/ {
	if (opbool != "") {
		match($0, /^ */);
		ind = substr($0, RSTART, RLENGTH);
		$0 = ind opbool " " str_from($0, RSTART+RLENGTH);
		opbool = "";
	}
}

# If terminado en booleano
/.*&& *$/ {
	opbool = "&&";
	match($0, / *&& *$/);
	$0 = substr($0, 1, RSTART - 1);
}

/.*\|\| *$/ {
	opbool = "||";
	match($0, / *\|\| *$/);
	$0 = substr($0, 1, RSTART - 1);
}

/.*/ {
	print $0;	
}


BEGIN {
	opbool = "";
}
