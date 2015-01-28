# Taxi Express - DATABASE STRUCTURES #

CREATE TABLE tx_mobile(
	mobile_id INT NOT NULL AUTO_INCREMENT,
	mobile_phone VARCHAR(11) UNIQUE NOT NULL,
	mobile_gcm VARCHAR(255) NOT NULL,
	mobile_uuid VARCHAR(255) NOT NULL,
	mobile_latitude FLOAT(10, 6) DEFAULT 0,
	mobile_longitude FLOAT(10, 6) DEFAULT 0,
	mobile_alivetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (mobile_id)
)ENGINE=InnoDB;

CREATE TABLE tx_user(
	user_id INT NOT NULL AUTO_INCREMENT,
	mobile_id INT NOT NULL,
	user_name VARCHAR(255),
	PRIMARY KEY (user_id),
	FOREIGN KEY (mobile_id) REFERENCES tx_mobile(mobile_id)
)ENGINE=InnoDB;

CREATE TABLE tx_taxi(
	taxi_id INT NOT NULL AUTO_INCREMENT,
	user_id INT NOT NULL,
	mobile_id INT NOT NULL,
	taxi_carplate VARCHAR(20) NOT NULL,
	taxi_available TINYINT(1) DEFAULT 0,
	PRIMARY KEY (taxi_id),
	FOREIGN KEY (mobile_id) REFERENCES tx_mobile(mobile_id)
)ENGINE=InnoDB;

CREATE TABLE tx_order(
	order_id INT NOT NULL AUTO_INCREMENT,
	user_id INT NOT NULL,
	taxi_id INT NOT NULL,
	order_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (order_id),
	FOREIGN KEY (user_id) REFERENCES tx_user(user_id),
	FOREIGN KEY (taxi_id) REFERENCES tx_taxi(taxi_id)
)ENGINE=InnoDB;


# Taxi Express - Dummy data #

INSERT INTO tx_mobile (mobile_phone, mobile_gcm, mobile_uuid) 
VALUES ('68511695', 'SDREF#@$#Q#@#!', 'EA-AEB4545-AEBC-34');

