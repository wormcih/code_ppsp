<?php

// This is the model for register account of Taxi Express

class Tx_tables extends CI_Model {

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

	function list_table($table) {
		/** This function return the table contents in array objects  */

		$query = $this->db->get($this -> tables[$table]);
		return $query -> result();
	}

}