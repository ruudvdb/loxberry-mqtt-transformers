#!/usr/bin/php
<?php
	if( $argv[1] == 'skills' ) {
		echo "description=Converting escaped double quotes to normal double quotes\n";
		echo "link=https://github.com/ruudvdb/loxberry-mqtt-transformers\n";
		echo "input=text\n";
		echo "output=text\n";
		exit();
	}
	
	// ---- THIS CAN BE USED ALWAYS ----
	// Remove the script name from parameters
	array_shift($argv);
	// Join together all command line arguments
	$commandline = implode( ' ', $argv );	
	// Split topic and data by separator
	list( $topic, $data ) = explode( '#', $commandline, 2);
	// ----------------------------------
	
	list($id, $state) = explode(' ', $data);

	$data = str_replace('\"', '"', $data);
	
	echo $topic."#".$data."\n";
	
