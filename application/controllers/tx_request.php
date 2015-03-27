<?php

// This is the controller for request taxi of Taxi Express

class Tx_request extends CI_Controller {

	public function order() {
		$this -> load -> model('tx_order');
		$this -> load -> model('tx_update');

		//$data['arr']['output'] = $this -> tx_order -> list_availabletaxi(26);

		$mobile_phone = $this -> input -> post('mobile_phone');
		$order_location = $this -> input -> post('order_location');
		$order_destination = $this -> input -> post('order_destination');

		$latitude = $this -> input -> post('latitude');
		$longitude = $this -> input -> post('longitude');

		$this -> tx_update -> clear_timeoutorder();

		$order_id = false;

		// force update current location
		if ($this -> tx_update -> update_location($mobile_phone, $latitude, $longitude)) {
			$order_id = $this -> tx_order -> create_order($mobile_phone, $order_location, $order_destination);
			
			if ($order_id) {
				$taxi_list = $this -> tx_order -> list_availabletaxi($this -> tx_order -> get_mobileid($mobile_phone));
				$gcm_list = $this -> list_taxigcm($taxi_list);
				$gcm_data = $this -> list_gcmsenddata($taxi_list);
				$data['arr']['taxi_count'] = count($gcm_list);

				$gcm_data = array('order_id' => $order_id, 'order_location' => $order_location, 'order_destination' => $order_destination);
				$data['arr']['gcm_data'] = $gcm_data;
				$data['arr']['gcm_success'] = $this -> send_gcm($gcm_list, $gcm_data);

			}
		}

		$data['arr']['order_id'] = $order_id;
		$this -> load -> view('output', $data);

	}

	public function order_status() {
		$this -> load -> model('tx_order');
		$this -> load -> model('tx_update');

		$this -> tx_update -> clear_timeoutorder();

		$mobile_phone = $this -> input -> post('mobile_phone');
		if ($mobile_phone) {

			$mobile_id = $this -> tx_order -> get_mobileid($mobile_phone);
			$user_id = $this -> tx_order -> get_roleid($mobile_id, 'user_id');

			if ($user_id) {
				$data['arr']['order_status'] = $this -> tx_order -> check_existorder($user_id);
			
			} else {
				$data['arr']['order_status'] = false;

			}

		} else {
			$data['arr']['order_status'] = false;

		}

		$this -> load -> view('output', $data);

	}

	public function confirm_order() {
		$this -> load -> model('tx_order');

		$mobile_phone = $this -> input -> post('mobile_phone');
		$order_id = $this -> input -> post('order_id');

		$data['arr']['confirm'] = $this -> tx_order -> confirm_order($mobile_phone, $order_id);

		$this -> load -> view('output', $data);

	}

	public function release_user() {
		$this -> load -> model('tx_order');

		$order_id = $this -> input -> post('order_id');

		$data['arr']['release'] = $this -> tx_order -> release_users($order_id);

		$this -> load -> view('output', $data);

	}

	public function get_orderid($user = 'none') {
		if ($user == 'user' || $user = 'taxi') {
			$this -> load -> model('tx_order');

			$user_array = array('user' => 'user_id', 'taxi' => 'taxi_id');

			if ($user == 'user') $roleid = $this -> input -> post('user_id');
			else $roleid = $this -> input -> post('taxi_id');

			$data['arr']['order_id'] = $this -> tx_order -> get_orderid($roleid, $user_array[$user]);

		} else $data['arr']['order_id'] = false;

		$this -> load -> view('output', $data);

	}

	private function list_taxigcm($taxi_list) {
		$list_array = array();
		foreach ($taxi_list as $obj) {
			array_push($list_array, $obj -> mobile_gcm);
		}

		return $list_array;

	}

	private function list_gcmsenddata($taxi_list) {
		return $taxi_list;
		$list_array = array();
		foreach ($taxi_list as $index => $obj) {
			$list_array[$index]['order_id'] = $obj -> order_id;
			$list_array[$index]['order_location'] = $obj -> order_location;
			$list_array[$index]['order_destination'] = $obj -> order_destination;
		}

		return $list_array;

	}

	private function send_gcm($gcm_list, $gcm_data) {
		// load gcm library

		/* data should be sent
			- order_id
			- start_location
			- dest...ion
		*/
		$this -> load -> library('gcm');
		$this -> gcm -> setMessage('Taxi Express Testing '.date('d.m.Y H:s:i'));

		if (!$gcm_list) return false;

			if (is_array($gcm_list)) {
				foreach ($gcm_list as $gcm) {
					$this -> gcm -> addRecepient($gcm);
				}

			} else {
 				$this -> gcm -> addRecepient($gcm);
 			}

		//$this -> gcm -> setData($gcm_data);
		$this -> gcm -> setData(array('hello' => 'sun'));

	    /*if ($this -> gcm -> send())
	        return 'Success for all messages, status => testing';
	        
	    else
	        return 'Some messages have errors';
		*/
  		$gcm_send = $this -> gcm -> send();

  		return $this->gcm->status;

    }

}
