<?php

// This is the model for updating user/taxi geoposition of Taxi Express

class Tx_update extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	function update_location($mobile, $latitude, $longtitute) {

		// check mobile exist
		$check_sql = 'SELECT mobile_id FROM tx_mobile WHERE mobile_phone = ?';
		$check_query = $this -> db -> query($check_sql, array($mobile))
		$check_result = $check_query -> result();
		// if no matched query, return false
		if (count($check_result) == 0) return false;

		// perform a geolocation update, alivetime (void time) changes to current time + 5 min
		$update_id = $check_result[0]['mobile_id'];
		$update_sql = 'UPDATE tx_mobile SET mobile_latitude = ?, mobile_longitude = ?, mobile_alivetime = now() + INTERVAL 5 MINUTE WHERE mobile_id = ?';
		$update_query = $this -> db -> query($update_sql, array($latitude, $longtitute, $update_id));

		return true;

	}


}
