<?php

// This is the controller for request taxi of Taxi Express

class Tx_request extends CI_Controller {

	public function order() {
		$this -> load -> model('tx_order');
		$this -> load -> model('tx_update');

		//$data['arr']['output'] = $this -> tx_order -> list_availabletaxi(26);

		$mobile_uuid = $this -> input -> post('mobile_uuid');
		$order_location = $this -> input -> post('order_location');
		$order_destination = $this -> input -> post('order_destination');

		$latitude = $this -> input -> post('latitude');
		$longitude = $this -> input -> post('longitude');

		$order_id = false;

		// force update current location
		if ($this -> tx_update -> update_location($mobile_uuid, $latitude, $longitude)) {
			$order_id = $this -> tx_order -> create_order($mobile_uuid, $order_location, $order_destination);
			
			if ($order_id) {
				$taxi_list = $this -> tx_order -> list_availabletaxi($this -> tx_order -> get_mobileid($mobile_uuid));
				$gcm_list = $this -> list_taxigcm($taxi_list);
				$gcm_data = $this -> list_gcmsenddata($taxi_list);
				$data['arr']['gcm_list'] = $gcm_list;
				$data['arr']['taxi_count'] = count($gcm_list);

				$gcm_data = array('order_id' => $order_id, 'order_location' => $order_location, 'order_destination' => $order_destination);
				$data['arr']['gcm_data'] = $this -> send_gcm($gcm_list, $gcm_data);

			}
		}

		$data['arr']['order_id'] = $order_id;
		$this -> load -> view('output', $data);

	}


	public function confirm_order() {
		$this -> load -> model('tx_order');

		$mobile_uuid = $this -> input -> post('mobile_uuid');
		$order_id = $this -> input -> post('order_id');

		$data['arr']['confirm'] = $this -> tx_order -> confirm_order($mobile_uuid, $order_id);

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

		foreach ($gcm_list as $gcm) {
			$this -> gcm -> addRecepient($gcm);
		}

		$this -> gcm -> setData($gcm_data);

	    /*if ($this -> gcm -> send())
	        return 'Success for all messages, status => testing';
	        
	    else
	        return 'Some messages have errors';
		*/
	    $this -> gcm -> send();

  		return $gcm_data;

    }

}
