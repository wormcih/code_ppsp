<?php

// This is the model for updating user/taxi geoposition of Taxi Express

class Tx_update extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	function update_location($mobile_phone, $latitude, $longitude) {
		if (!$mobile_phone || !$latitude || !$longitude) return false;

		// check mobile exist
		$check_sql = 'SELECT mobile_id FROM tx_mobile WHERE mobile_phone = ?';
		$check_query = $this -> db -> query($check_sql, array($mobile_phone));
		$check_result = $check_query -> result();
		// if no matched query, return false
		if (count($check_result) == 0) return false;

		// perform a geolocation update, alivetime (void time) changes to current time + 5 min
		$update_id = $check_result[0] -> mobile_id;
		$update_sql = 'UPDATE tx_mobile SET mobile_latitude = ?, mobile_longitude = ?, mobile_alivetime = now() + INTERVAL 5 MINUTE WHERE mobile_id = ?';
		$update_query = $this -> db -> query($update_sql, array($latitude, $longitude, $update_id));

		return true;

	}

	function clear_timeoutorder() {
		$check_sql = 'UPDATE tx_order SET order_alive = 0 WHERE order_time <= now() - INTERVAL 5 MINUTE';
		$check_query = $this -> db -> query($check_sql);
	}


}
