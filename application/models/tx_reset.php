<?php

// This is the model for register account of Taxi Express

class Tx_reset extends CI_Model {

	private $tables;
	function __construct() {
		parent::__construct();

		/** Table name */
		$this -> tables = array(
			'user' => 'tx_user',
			'taxi' => 'tx_taxi',
			'mobile' => 'tx_mobile',
			'order' => 'tx_order',
			'beta' => 'tx_drivers'
			);
	}

	function remove_db() {
		/** This function remove every tables  */
		$remove_sql = array('DROP TABLE tx_order;',
						'DROP TABLE tx_taxi;',
						'DROP TABLE tx_user;',
						'DROP TABLE tx_mobile;');
		foreach ($remove_sql as $sql) {
			$remove_query = $this -> db -> query($sql);
		}
		
		return true;		
	}

	function build_db() {
		$build_sql = array('CREATE TABLE tx_mobile(
							mobile_id INT NOT NULL AUTO_INCREMENT,
							mobile_phone VARCHAR(11) UNIQUE NOT NULL,
							mobile_gcm VARCHAR(255) NOT NULL,
							mobile_uuid VARCHAR(255) NOT NULL DEFAULT "none",
							mobile_latitude FLOAT(10, 6) DEFAULT 0,
							mobile_longitude FLOAT(10, 6) DEFAULT 0,
							mobile_alivetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
							PRIMARY KEY (mobile_id)
						)ENGINE=InnoDB;',

						'CREATE TABLE tx_user(
							user_id INT NOT NULL AUTO_INCREMENT,
							mobile_id INT NOT NULL,
							user_name VARCHAR(255),
							user_available TINYINT(1) DEFAULT 1,
							PRIMARY KEY (user_id),
							FOREIGN KEY (mobile_id) REFERENCES tx_mobile(mobile_id)
						)ENGINE=InnoDB;',

						'CREATE TABLE tx_taxi(
							taxi_id INT NOT NULL AUTO_INCREMENT,
							mobile_id INT NOT NULL,
							taxi_type INT NOT NULL,
							taxi_carplate VARCHAR(20) NOT NULL,
							taxi_available TINYINT(1) DEFAULT 1,
							PRIMARY KEY (taxi_id),
							FOREIGN KEY (mobile_id) REFERENCES tx_mobile(mobile_id)
						)ENGINE=InnoDB;',

						'CREATE TABLE tx_order(
							order_id INT NOT NULL AUTO_INCREMENT,
							user_id INT NOT NULL,
							taxi_id INT,
							order_location VARCHAR(255),
							order_destination VARCHAR(255),
							order_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
							order_alive TINYINT(1) DEFAULT 1,
							PRIMARY KEY (order_id),
							FOREIGN KEY (user_id) REFERENCES tx_user(user_id),
							FOREIGN KEY (taxi_id) REFERENCES tx_taxi(taxi_id)
						)ENGINE=InnoDB;');

		foreach ($build_sql as $sql) {
			$build_query = $this -> db -> query($sql);
		}
		
		return true;
	}

	function load_dummydata() {
		$dummy_sql = array("INSERT INTO `tx_mobile` (`mobile_id`, `mobile_phone`, `mobile_gcm`, `mobile_latitude`, `mobile_longitude`) VALUES
			(1, '68511695', 'APA91bHOZ3KnBzQjZw1NHY6cTUxCZ4JJXMA2MryxFcoxgHW_wtE5xwX4Rk2hKFPe5kkSObiTuHlC_9pY1Xq4HDNT4AVAtu57Aj4gb16fLH5UZMfM1pkwh8dshErZeoFg5EFnGhRGr6A81k0cXVkzE-FIIfxsSrAWpGkby8qPcXWY3a5j9bNXilQ', 22.337962, 114.172964),
			(2, '68464618', 'APA91bFr6Axw7Cd-FADdkozllY-JRuQX7kGH6keQNTXDE3jYaq5dPgg6HSX4s6zmk1Vz4X6BlC7rjfP33iqOEhV81YIGbh21fbivuRrGUrv8iNhXzZ-bfiYF2ydRONXTRYMdl-NBOmiUzCCz_Ee32RBBSyc-wbuzzxdCQjsqH7ZV0vDrzzZqASY', 22.337962, 114.172964);",

			"INSERT INTO `tx_taxi` (`taxi_id`, `mobile_id`, `taxi_carplate`, `taxi_available`) VALUES
			(1, 1, 'ANDY 9394', 1);",

			"INSERT INTO `tx_user` (`user_id`, `mobile_id`, `user_name`) VALUES
			(1, 2, 'Dickson');"

			);

		foreach ($dummy_sql as $sql) {
			$dummy_query = $this -> db -> query($sql);
		}
		
		return true;
	}

}