/*Query of the creation of the database dbadmin*/
CREATE DATABASE dbadmin;

/*Query of accessing the database dbadmin*/
USE dbadmin;

/*Query of the creation of the tables in dbadmin*/
CREATE TABLE mall (
	mall_id INT NOT NULL AUTO_INCREMENT UNIQUE PRIMARY KEY,
	mall_name VARCHAR(32)
);

CREATE TABLE restaurant (
	rest_id INT NOT NULL AUTO_INCREMENT UNIQUE PRIMARY KEY,
	mall_id INT NOT NULL,
	rest_name VARCHAR(255),	
    FOREIGN KEY (mall_id) REFERENCES mall(mall_id)
);
		 
CREATE TABLE item (
	item_id INT NOT NULL AUTO_INCREMENT UNIQUE PRIMARY KEY,
	rest_id INT NOT NULL,
	item_name VARCHAR(255),
	price DECIMAL,
	type VARCHAR(15),
	is_available BOOLEAN DEFAULT 1,
    FOREIGN KEY (rest_id) REFERENCES restaurant(rest_id),
    CHECK(type IN ('Snacks', 'Drinks'))
);

CREATE TABLE account (
	account_id INT NOT NULL AUTO_INCREMENT UNIQUE PRIMARY KEY,
	first_name VARCHAR(15),
	middle_name VARCHAR(15),
	last_name VARCHAR(15),
	username VARCHAR(15) UNIQUE,
	password VARCHAR(15),
	position VARCHAR(10),
	load_balance DECIMAL,
	mall_id INT,
	rest_id INT,
	CHECK(position IN ('Dev', 'Admin', 'Boy', 'Client')),
	FOREIGN KEY (mall_id) REFERENCES mall(mall_id),
	FOREIGN KEY (rest_id) REFERENCES restaurant(rest_id)
);

CREATE TABLE orders (
	order_id INT NOT NULL AUTO_INCREMENT UNIQUE PRIMARY KEY,
	order_time TIME(6),
	mall_id INT,
	cinema_num VARCHAR(2),
	seat_num VARCHAR(3),
	status VARCHAR(10),
	account_id INT,
	boy_id INT, 
	total_price DECIMAL,
	CHECK(status IN ('Pending', 'Assembling', 'Delivered')), 
	FOREIGN KEY (mall_id) REFERENCES mall(mall_id),
	FOREIGN KEY (account_id) REFERENCES account(account_id),
	FOREIGN KEY (boy_id) REFERENCES account(account_id)
);

CREATE TABLE orderitems (
	orderitem_id INT NOT NULL AUTO_INCREMENT,
	order_id INT NOT NULL,
	item_id INT NOT NULL,
	item_qty INT NOT NULL,  /*new*/
	total_price INT NOT NULL, /*new*/
	PRIMARY KEY (orderitem_id ,order_id, item_id),
	FOREIGN KEY (order_id) REFERENCES orders(order_id),
	FOREIGN KEY (item_id) REFERENCES item(item_id)
);

CREATE TABLE tracker(
	tracker_id INT NOT NULL AUTO_INCREMENT,
	session_id VARCHAR(32),
	item_id INT NOT NULL,
	item_qty INT NOT NULL, /*new*/
	total_price INT NOT NULL, /*new*/
	PRIMARY KEY (tracker_id),
	FOREIGN KEY (item_id) REFERENCES item(item_id)
);

CREATE TABLE message(
	message_id INT NOT NULL AUTO_INCREMENT,
	recipient_id INT NOT NULL,					
	/*recipient account id of the message*/
	sender_id INT NOT NULL,						
	/*sender account id of the message*/
	msg_date DATETIME,
	msg TEXT(250),
	PRIMARY KEY(message_id,recipient_id,sender_id),
	FOREIGN KEY (recipient_id) REFERENCES account(account_id),
	FOREIGN KEY (sender_id) REFERENCES account(account_id)
);

/*Query of the creation of the developer account*/
INSERT INTO account (username,password,position) VALUES ("developer","password","Dev");