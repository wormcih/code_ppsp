<?php

// This is the model for order process of Taxi Express

class Tx_order extends CI_Model {

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

	function create_order($mobile_uuid, $order_location, $order_destination) {

		$mobile_id = $this -> get_mobileid($mobile_uuid);
		$user_id = $this -> get_roleid($mobile_id, 'user_id');

		if ($this -> check_existorder($user_id)) return false;

		$create_sql = 'INSERT INTO tx_order(user_id, order_location, order_destination) values (?, ?, ?)';
		$create_query = $this -> db -> query($create_sql, array($user_id, $order_location, $order_destination));
		$order_id = $this -> db -> insert_id();

		$this -> list_availabletaxi($mobile_id);

		return $order_id;

	}


	function check_existorder($user_id) {

		/* return true if order exist */

		if (!$user_id) return true;

		$check_sql = 'SELECT order_id FROM tx_order WHERE taxi_id IS NULL AND order_time > now() - INTERVAL 5 MINUTE AND order_alive = 1 AND user_id = ?';
		$check_query = $this -> db -> query($check_sql, array($user_id));

		$check_result = $check_query -> result();

		if (count($check_result) == 0) {
			return false;
		}

		return true;

	}

	function check_orderstatus($order_id) {
		$status_sql = 'SELECT * FROM tx_order WHERE order_id = ? AND taxi_id IS NULL AND order_time > now() - INTERVAL 5 MINUTE AND order_alive = 1';
		$status_query = $this -> db -> query($status_sql, array($order_id));
		$status_result = $status_query -> result();

		if (count($status_result) > 0) {
			return true;
		}

		return false;

	}

	function confirm_order($mobile_uuid, $order_id) {

		if (!$mobile_uuid || !$order_id) return false;

		$mobile_id = $this -> get_mobileid($mobile_uuid);
		$taxi_id = $this -> get_roleid($mobile_id, 'taxi_id');

		if (!$taxi_id) return false;

		if ($this -> check_orderstatus($order_id)) {
			$comfirm_sql = 'UPDATE `tx_order` SET taxi_id = ?, order_alive = 0 WHERE order_id = ?';
			$confirm_query = $this -> db -> query($comfirm_sql, array($taxi_id, $order_id));

			return true;
		}

		return false;

	}

	function cancel_order($order_id) {
		
	}


	function get_roleid($mobile_id, $user_type) {

		if (!$user_type || !$mobile_id) return false;

		if ($user_type == 'user_id') {
			$role_sql = 'SELECT user_id AS id FROM `tx_user` WHERE mobile_id = ?';

		} elseif ($user_type == 'taxi_id') {
			$role_sql = 'SELECT taxi_id AS id FROM `tx_taxi` WHERE mobile_id = ?';

		} else {
			return false;

		}

		$mobile_query = $this -> db -> query($role_sql, array($mobile_id));
		$mobile_result = $mobile_query -> result();

		if (count($mobile_result) > 0) {
			return $mobile_result[0] -> id;
		}

		return false;
	}

	function get_mobileid($mobile_uuid) {

		if (!$mobile_uuid) return false;

		$mobile_sql = 'SELECT mobile_id FROM `tx_mobile` WHERE mobile_uuid = ?';
		$mobile_query = $this -> db -> query($mobile_sql, array($mobile_uuid));
		
		$mobile_result = $mobile_query -> result();

		if (count($mobile_result) > 0) {
			return $mobile_result[0] -> mobile_id;
		}

		return false;
	}

}