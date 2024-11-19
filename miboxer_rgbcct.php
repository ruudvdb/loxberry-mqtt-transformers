#!/usr/bin/php
<?php
	
	function RGB_to_HSV($r, $g, $b) {
		$r = max(0, min((int)$r, 255));
		$g = max(0, min((int)$g, 255));
		$b = max(0, min((int)$b, 255));
		$result = [];
		$min = min($r, $g, $b);
		$max = max($r, $g, $b);
		$delta_min_max = $max - $min;
		$result_h = 0;
		if     ($delta_min_max !== 0 && $max === $r && $g >= $b) $result_h = 60 * (($g - $b) / $delta_min_max) +   0;
		elseif ($delta_min_max !== 0 && $max === $r && $g <  $b) $result_h = 60 * (($g - $b) / $delta_min_max) + 360;
		elseif ($delta_min_max !== 0 && $max === $g            ) $result_h = 60 * (($b - $r) / $delta_min_max) + 120;
		elseif ($delta_min_max !== 0 && $max === $b            ) $result_h = 60 * (($r - $g) / $delta_min_max) + 240;
		$result_s = $max === 0 ? 0 : (1 - ($min / $max));
		$result_v = $max;
		$result['hue'] = (int)(round($result_h));
		$result['saturation'] = (int)($result_s * 100);
		$result['level'] = (int)($result_v / 2.55);
		return $result;
    }
	
	if( $argv[1] == 'skills' ) {
		echo "description=MiBoxer RGB and CCT control for RGB+CCT devices\n";
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
	
	$command = str_pad($data, 9, '0', STR_PAD_LEFT);
	
	$data = array('state' => 'ON');
	
	if (stripos($command, '20') === 0) {
		//tunable white
		$bright = substr($command, -7, 3);
		if ($bright == 0) {
			$data['state'] = 'OFF';
		} else {
			$temp = intval(substr($command, -4, 4));
			//MiBoxer: cold white = kelvin 0, warm white = kelvin 100
			//Loxone: cold white = 6500, warm white = 2700
			$temp = round((6500 - $temp) / 38);
			$data['color_mode'] = 'color_temp';
			$data['level'] = intval($bright);
			$data['kelvin'] = $temp;
		}
	} else {
		//color
		if ($command == '000000000') {
			$data['state'] = 'OFF';
		} else {
			$red = round( substr($command, -3, 3) / 100 * 255 );
			$green = round( substr($command, -6, 3) / 100 * 255 );
			$blue = round( substr($command, -9, 3) / 100 * 255 );
			$data['color_mode'] = 'rgb';
			$data = array_merge($data, RGB_TO_HSV($red, $green, $blue));
		}
	}
	
	echo $topic."#".json_encode($data, JSON_UNESCAPED_UNICODE )."\n";
	
