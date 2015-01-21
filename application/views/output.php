<?php
	
	// set header as "JSON"
	header('Content-Type: application/json');

	// check ENVIRONMENT
	if (ENVIRONMENT == 'production') {
		unset($arr['debug']);
	}

	echo json_encode($arr, JSON_PRETTY_PRINT);