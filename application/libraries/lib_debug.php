<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lib_debug {
	
	public function list_debug() {
		$CI =& get_instance();
		return $CI -> router -> class;
	}

	private $error_msg = array(
					'1': 'Not enough parameters',
					'2': 'No data return',
					'3': 'Parameters type no correct'
				);


}