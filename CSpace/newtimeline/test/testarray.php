<?php
//**************************************
//     Page load dropdown results     //
//**************************************
function foo($a)
{
	
	global $test;
	$test = $a;

}

function bar()
{
	global $test;
	echo 'got it from bar'.$test;
	global $test2;
	$test2 = 1923;
}

function trio()
{
	global $test;
	global $test2;
	echo 'got it from foot and bar'.$test.''.$test2.'';
}

foo('1887');
bar();
trio();

?>