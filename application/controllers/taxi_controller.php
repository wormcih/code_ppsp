<?php

class Taxi_controller extends CI_Controller {
	
	public function view($page = 'home') {
		 
		 if (!file_exists(APPPATH.'/views/pages/'.$page.'.php')) {
		 	show_404();
		 }

		 // $data['title'] = ucfirst($page);
		 $data['arr'] = array(
		 	"project" => 'ppsp',
		 	"staff" => array ('Dickson', 'Andy')
		 	);

		 $data['arr']['debug'] = array(
		 		'controller' => 'taxi_controller'
		 	);

		 /*
		 $this -> load -> view('templates/header', $data);
		 $this -> load -> view('pages/'.$page, $data);
		 $this -> load -> view('templates/footer', $data);
		 */


		 $this -> load -> view('output', $data);

	}

}