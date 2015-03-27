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

		$order_id = false;

		// force update current location
		if ($this -> tx_update -> update_location($mobile_phone, $latitude, $longitude)) {
			$order_id = $this -> tx_order -> create_order($mobile_phone, $order_location, $order_destination);
			
			if ($order_id) {
				$taxi_list = $this -> tx_order -> list_availabletaxi($this -> tx_order -> get_mobileid($mobile_phone));
				$gcm_list = $this -> list_taxigcm($taxi_list);
				$data['arr']['gcm_list'] = $gcm_list;
				$data['arr']['taxi_count'] = count($gcm_list);

			}
		}

		$data['arr']['order_id'] = $order_id;
		$this -> load -> view('output', $data);

	}

	private function list_taxigcm($taxi_list) {
		$list_array = array();
		foreach ($taxi_list as $obj) {
			array_push($list_array, $obj -> mobile_gcm);
		}

		return $list_array;

	}

	private function send_gcm($gcm_list) {
		// load gcm library
		$this -> load -> library('gcm');
		$this -> gcm -> setMessage('Test message '.date('d.m.Y H:s:i'));

		if (!$gcm_list) return false;

		foreach ($gcm_list as $gcm) {
			$this -> gcm -> addRecepient($gcm);
		}

		$this -> gcm -> setData(array('status' => 'testing'));

	    if ($this -> gcm -> send())
	        return 'Success for all messages, status => testing';
	        
	    else
	        return 'Some messages have errors';

    }

}

	/*
	public function send_gcm() {

		// load gcm library
		$this -> load -> library('gcm');
		$this->gcm->setMessage('Test message '.date('d.m.Y H:s:i'));
		$gcm_list = $user_name = $this -> input -> post('gcm');

		if (!$gcm_list) {
			$data['arr']['output'] = "gcm send fail, probably code failure, or server problems";
		} else {
			if (is_array($gcm_list)) {
				foreach ($gcm_list as $gcm) {
					$this->gcm->addRecepient($gcm);
				}
			} else {
				$this->gcm->addRecepient($gcm_list);
			}

			$this->gcm->setData(array('status' => 'testing'));

	        if ($this->gcm->send())
	            $data['arr']['output'] = 'Success for all messages, status => testing';
	        else
	            $data['arr']['output'] = 'Some messages have errors';

    	}

        $this -> load -> view('output', $data);

	} */
