<?php

// This is the controller for request taxi of Taxi Express

class Tx_request extends CI_Controller {

	public function order() {
		$this -> load -> model('tx_list');

		$data['arr']['output'] = $this -> tx_list -> list_availabletaxi(26);

		$this -> load -> view('output', $data);

	}
}