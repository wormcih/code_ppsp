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
		$longtitute = $this -> input -> post('longtitute');

		if ($this -> tx_update -> update_location($mobile, $latitude, $longtitute)) {
			
			$data['arr']['location_update'] = 'success';

		} else {

			$data['arr']['error'] = 'Cannot update location!';

		}

		$this -> load -> view('output', $data);

	}

}
