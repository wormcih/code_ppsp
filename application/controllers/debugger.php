<?php

class Debugger extends CI_Controller {

	public function view($get = 'none') {
		$data['arr']['project'] = 'ppsp';
		$data['arr']['url'] = $get; 
		$data['arr']['post'] = $this->input->post(NULL, TRUE); 
		$data['arr']['get'] = $this->input->get(NULL, TRUE);

		$data['arr']['debug'] = array(
		 		'controller' => 'taxi_controller'
		 	);

		$this -> load -> view('output', $data);
	}

}