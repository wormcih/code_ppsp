<?php

// This is the model for register account of Taxi Express

class Tx_list extends CI_Model {

	private $tables;
	function __construct() {
		parent::__construct();

		/** Table name */
		$this -> tables = array(
			'user' => 'tx_user',
			'taxi' => 'tx_taxi',
			'mobile' => 'tx_mobile',
			'order' => 'tx_order',
			'beta' => 'tx_drivers'
			);
	}

	function list_availabletaxi($mobile_id, $distance = 1) {
		/** return array of the available taxis
			where are near to the customer
			*> Default distance: 1 km */

		if (!$mobile_id) return false;

		$user_location = $this -> get_geolocation($mobile_id);
		if (!$user_location) return false;

		$list_sql = 'SELECT mobile.*, 
					(((acos(sin((? * pi() / 180)) * 
					sin((mobile.mobile_latitude * pi() / 180)) + 
					cos((? * pi() / 180)) * 
					cos((mobile.mobile_latitude * pi() / 180)) 
					* cos(((? - mobile.mobile_longitude) * 
					pi() / 180)))) * 180 / pi()) * 60 * 1.1515 * 1.609344) 
					as distance FROM `tx_mobile` as mobile
					INNER JOIN `tx_taxi` as taxi
					ON taxi.mobile_id = mobile.mobile_id
					WHERE taxi.taxi_available = 1
					having distance <= ?';
		$list_escape = array($user_location['latitude'], $user_location['latitude'], $user_location['longitude'], $distance);
		$list_query = $this -> db -> query($list_sql, $list_escape);

		return $list_query -> result();

	}

	private function get_geolocation($mobile_id) {
		/** return array of the latitude and 
			longitude of mobile device. */

		if (!$mobile_id) return false;

		$location_sql = 'SELECT mobile_latitude AS latitude, 
						mobile_longitude AS longitude
						FROM `tx_mobile` WHERE mobile_id = ?';
		$location_query = $this -> db -> query($location_sql, array($mobile_id));

		return $location_query -> result_array()[0]; 

	}


}