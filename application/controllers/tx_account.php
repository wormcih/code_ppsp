<?php

class Tx_account extends CI_Controller {
	
	public function register($method = 'user') {
	/** Register controller:
		allow user to register as user or taxi driver,
		$method allows 'user' and 'taxi' parameters ONLY */

		$this -> load -> model('tx_reg');

		if ($method == 'user') {
			// receive POST data
			$user_name = $this -> input -> post('user_name');
			$mobile_phone = $this -> input -> post('mobile_phone');
			$mobile_gcm = $this -> input -> post('mobile_gcm');
			$mobile_uuid = $this -> input -> post('mobile_uuid');

			$data['arr']['user_reg'] = $this -> tx_reg -> add_user($user_name, $mobile_phone, $mobile_gcm, $mobile_uuid);
		
		} elseif ($method == 'taxi') {
			// receive POST data
			$taxi_carplate = $this -> input -> post('taxi_carplate');
			$mobile_phone = $this -> input -> post('mobile_phone');
			$mobile_gcm = $this -> input -> post('mobile_gcm');
			$mobile_uuid = $this -> input -> post('mobile_uuid');

			$data['arr']['taxi_reg'] = $this -> tx_reg -> add_taxi($taxi_carplate, $mobile_phone, $mobile_gcm, $mobile_uuid);

		} else {
			$data['arr']['error'] = 'no parameter received!';
		}

		$this -> load -> view('output', $data);

		

	}

}