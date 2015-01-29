<?php

class Tx_geolocation extends CI_Controller {
	
	public function update() {
	/** Update controller:
		allow all vaild user or taxi driver
		update their recently geo-location information */

		$this -> load -> model('tx_update');

	}

}
