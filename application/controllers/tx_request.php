<?php

// This is the controller for request taxi of Taxi Express

class Tx_request extends CI_Controller {

	public function order() {
		$this -> load -> model('tx_list');

		$data['arr']['output'] = $this -> tx_list -> list_availabletaxi(26);

		$this -> load -> view('output', $data);

	}

	private function send_gcm($gcm_list) {

		// load gcm library
		$this -> load -> library('gcm');

		$this->gcm->setMessage('Test message '.date('d.m.Y H:s:i'));

		if (is_array($gcm_list)) {

			foreach ($gcm_list as $gcm) {

				$this->gcm->addRecepient($gcm);

			}

		} else {

			$this->gcm->addRecepient($gcm);

		}

		$this->gcm->setData(array('status' => 'testing'));

        if ($this->gcm->send())
            echo 'Success for all messages';
        else
            echo 'Some messages have errors';

	}

}