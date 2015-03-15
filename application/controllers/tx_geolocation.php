<?php

class Tx_geolocation extends CI_Controller {
	
	public function update() {
	/** Update controller:
		allow all vaild user or taxi driver
		update their recently geo-location information */

		$this -> load -> model('tx_update');

		// receive POST request
		$mobile = $this -> input -> post('mobile');
		$latitude = $this -> input -> post('latitude');
		$longitude = $this -> input -> post('longitude');

		if ($this -> tx_update -> update_location($mobile, $latitude, $longitude)) {
			
			$data['arr']['location_update'] = true;

		} else {

			$data['arr']['location_update'] = false;

		}

		$this -> load -> view('output', $data);

	}

}
