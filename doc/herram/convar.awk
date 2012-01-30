
/^\/\*\* [A-Za-z]/ {
	com=substr($0, 4, length($0)-2);
	if (match(com, / *\*\//)>0) {
		com=substr(com, 1, RSTART-1);
	}
}

/^\$GLOBALS/ {
	r=$0;
	if (match($0, /GLOBALS\['.*'\]/)>0) {
		r=substr($0, RSTART+9,RLENGTH-11);
	}
	print "<varlistentry><term><literal>" r "</literal></term>"
        print "<listitem><para>" com "</para></listitem></varlistentry>";
}

