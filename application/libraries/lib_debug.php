<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lib_debug {
	
	public function list_debug() {
		$CI =& get_instance();
		return $CI -> router -> class;
	}


}