<?php

	set_time_limit(5);
	
	$url = $argv[1];
	$s = preg_replace("/\/\d{2,5}_\d{2,5}", '/size/', $url);

	var_dump($s);

	if (is_null($s)) {
		echo 'error_log';
		exit;
	}

	echo "$s";
    
?>