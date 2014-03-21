#!/bin/sh
# Script to facilitate regression tests.
# Implemented for the project Structio http://structio.sourceforge.net
# Released to the public domain. Citation of the source is appreciated.
# No warranties.

. ../confv.sh
echo "Regression test for $PROJECT-$PRJ_VERSION" > test.log
date >> test.log
uname -a >> test.log
echo "" >> test.log

# Indicates if the output files must be created (in that case no checking
# besides return value is done).
if (test "$create" != 1) then {
	create=0;
} fi;

# Checks output of a command.
# @param $1 Command to execute
# @param $2 Expected return value
# @param $3 File with expected output by stdout
# @param $4 File with expected output by stderr
# @return 1 if the output doesn't match (besides it informs the users
# and waits for a key to continue) or 0 otherwise.
function rtest {
	echo "$1 $2 $3 $5" >> test.log
	if (test "$create" != "1" -a ! -f "$3") then {
		echo "Third parameter ($3) must be file with expected stdout";
		exit 1;
	} fi;
	if (test "$create" != "1" -a ! -f "$4") then {
		echo "Fourth parameter ($4) must be file with expected stderr";
		exit 1;
	} fi;
	echo -n $1;
	eval "$1 > test.out 2> test.err";
	vret=$?;
	prob="";
	if (test "$create" == 1) then {
		cp test.out $3;	
		cp test.err $4;	
	} fi;
	if (test "$vret" != "$2") then {
		prob="${prob}Return value $vret, expected $2.  ";
	} fi;
	cmp -s test.out $3
	if (test "$?" != "0") then {
		prob="${prob}Stdout is not the contents of $3.  ";
	} fi;
	cmp -s test.err $4
	if (test "$?" != "0") then {
		prob="${prob}Stderr is not the contents of $4.  ";
	} fi;

	if (test "$prob" != "") then {
		echo "***Problem. $prob. " >> test.log
		echo "---stdout" >> test.log
		cat test.out >> test.log
		echo "---stderr " >> test.log
		cat test.err >> test.log
		echo "---" >> test.log
		echo " - Problem";
		echo $prob;
		read;
		return 1;
	} 
	else {
		echo " - OK";
	} fi;
	return 0;
}



