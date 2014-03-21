/.*/ { echo "k"; if (imp==1) { print $0; } } /-------/ { imp=imp+1; } /.*/ 
