<?php
/*
	Suhosin Configuration Checker
	@author  NewEraCracker
	@version 0.6.1
	@date    2013/01/24
	@license Public Domain
*/

/* -------------
   Configuration
   ------------- */

// Value has to be false or zero to pass tests
$test_false = array(
	'suhosin.mail.protect',
	'suhosin.sql.bailout_on_error',
	'suhosin.cookie.encrypt',
	'suhosin.session.encrypt'
);

// Value has to be the same or higher to pass tests
$test_values = array(
	array( 'suhosin.cookie.max_name_length', 64),
	array( 'suhosin.cookie.max_totalname_length', 256),
	array( 'suhosin.cookie.max_value_length', 10000),
	array( 'suhosin.get.max_name_length', 512 ),
	array( 'suhosin.get.max_totalname_length', 512 ),
	array( 'suhosin.get.max_value_length', 2048 ),
	array( 'suhosin.post.max_array_index_length', 256 ),
	array( 'suhosin.post.max_name_length', 512 ),
	array( 'suhosin.post.max_totalname_length', 8192 ),
	array( 'suhosin.post.max_vars', 4096 ),
	array( 'suhosin.post.max_value_length', 1000000 ),
	array( 'suhosin.request.max_array_index_length', 256 ),
	array( 'suhosin.request.max_totalname_length', 8192 ),
	array( 'suhosin.request.max_vars', 4096 ),
	array( 'suhosin.request.max_value_length', 1000000 ),
	array( 'suhosin.request.max_varname_length', 512 )
);

// Value has to be zero (protection disabled), equal or higher than x to pass
$test_zero_or_higher_than_value = array(
	array( 'suhosin.executor.max_depth', 10000),
	array( 'suhosin.executor.include.max_traversal', 6),
);

/* ---------
   Main code
   --------- */

$informations = $problems = array();

if( !extension_loaded('suhosin') )
{
	$informations[] = "<b>There is no Suhosin in here :)</b>";
}
else
{
	$informations[] = "<b>Suhosin installation detected!</b>";

	foreach($test_false as $test)
	{
		if( @ini_get($test) )
		{
			if( $test == 'suhosin.mail.protect' )
				$problems[] = $test.' is required to be set to <b>0 (zero)</b> in php.ini. Your server does not meet this requirement.';
			else
				$problems[] = $test.' is required to be set to <b>off</b> in php.ini. Your server does not meet this requirement.';
		}
	}

	foreach($test_values as $test)
	{
		if( isset($test['0']) && isset($test['1']) )
		{
			if( @ini_get($test['0']) < $test['1'] )
				$problems[] = 'It is required that <b>'.$test['0'].'</b> is set to <b>'.$test['1'].'</b> or higher.';
		}
	}

	foreach($test_zero_or_higher_than_value as $test)
	{
		if( @ini_get($test['0']) )
		{
			if( @ini_get($test['0']) < $test['1'] )
				$problems[] = 'It is required that <b>'.$test['0'].'</b> is set to either 0 (zero), <b>'.$test['1'].'</b> or higher.';
		}
	}

	if( !count($problems) )
		$informations[] = "<b>No problems detected!</b>";
}

echo "<pre>";
foreach($informations as $info)
	echo $info."\r\n";

foreach($problems as $problem)
	echo $problem."\r\n";

echo "</pre>";
?>