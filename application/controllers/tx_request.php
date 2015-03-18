<?php

// This is the controller for request taxi of Taxi Express

class Tx_request extends CI_Controller {

	public function order() {
		$this -> load -> model('tx_list');

		$data['arr']['output'] = $this -> tx_list -> list_availabletaxi(26);

		$this -> load -> view('output', $data);

	}

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

	}



}