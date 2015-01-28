<?php

// This is the model for register account of Taxi Express

class Tx_reg extends CI_Model {

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

	function add_user($user_name, $mobile_phone, $mobile_gcm, $mobile_uuid) {
		/** This function return true (Pass) if the user added in the database,
			Otherwise, return false (Fail) 
			require ALL parameters */
		if (!($user_name)||!($mobile_phone)||!($mobile_gcm)||!($mobile_uuid)) return false;

		// check duplicate of user name
		if ($this -> check_duplicate('user', array('user_name' => $user_name))) {
			// create mobile profile
			if ($this -> add_mobile($mobile_phone, $mobile_gcm, $mobile_uuid)) {

				// get mobile_id to register user
				$mobile_id = $this -> db -> insert_id();

				// create user account
				$insert_sql = 'INSERT INTO tx_user(mobile_id, user_name) VALUES (?, ?)';
				$insert_query = $this -> db -> query($insert_sql, array($mobile_id, $user_name));
				
				return true;
			}
		} 

		return false;
	}

	function add_taxi($taxi_carplate, $mobile_phone, $mobile_gcm, $mobile_uuid) {
		/** This function return true (Pass) if the taxi added in the database,
			Otherwise, return false (Fail) 
			require ALL parameters */
		if (!($taxi_carplate)||!($mobile_phone)||!($mobile_gcm)||!($mobile_uuid)) return false;

		// check duplicate of taxi car plate
		if ($this -> check_duplicate('taxi', array('taxi_carplate' => $taxi_carplate))) {
			// create mobile profile
			if ($this -> add_mobile($mobile_phone, $mobile_gcm, $mobile_uuid)) {

				// get mobile_id to register user
				$mobile_id = $this -> db -> insert_id();

				// create user account
				$insert_sql = 'INSERT INTO tx_taxi(mobile_id, taxi_carplate) VALUES (?, ?)';
				$insert_query = $this -> db -> query($insert_sql, array($mobile_id, $taxi_carplate));
				
				return true;
			}
		} 

		return false;
	}
	
	private function add_mobile($phone, $gcm, $uuid) {
		/** This function return true if mobile information are inserted.  
			Otherwise, return false */

		if ($this -> check_duplicate('mobile', array('mobile_phone' => $phone))) {

			$insert_sql = 'INSERT INTO tx_mobile(mobile_phone, mobile_gcm, mobile_uuid) VALUES (?, ?, ?)';
			$insert_query = $this-> db -> query($insert_sql, array($phone, $gcm, $uuid));

			return true;

			$index_sql = 'SELECT mobile_id FROM tx_mobile WHERE mobile_phone = ?';
			$index_query = $this-> db -> query($index_sql, array($phone));


			return $index_query -> result()[0] -> mobile_id;
			
		} else {

			return false;
		}
	}

	private function check_duplicate($table_name, $keyval) {
		/** This function return true (Pass) if the data not exists in the database with the input parameters,
			Otherwise, return false (Fail) 
			Accept array value(s) ONLY */
		if (!is_array($keyval)) return false;

		$check_sql = 'SELECT * FROM '.$this -> tables[$table_name].' WHERE ';

		$sql_addition = '';
		foreach ($keyval as $col => $val) {
			$sql_addition = $sql_addition.$col.' = '.$this->db->escape($val).' OR ';
		}
		
		$check_sql = $check_sql.rtrim($sql_addition, ' OR ');
		
		if (count($this -> db -> query($check_sql) -> result()) == 0) return true;

		return false;
	}

}