<?php

class Tx_debug extends CI_Controller {

	public function load_all() {
		$this -> load -> model('tx_tables');

		$data['arr']['title'] = 'database list';

		foreach (array('user', 'taxi', 'mobile', 'order') as $table) {
			$data['arr']['table'][$table] = $this -> tx_tables -> list_table($table);
		}

		$this -> load -> view('output', $data);

	}
}