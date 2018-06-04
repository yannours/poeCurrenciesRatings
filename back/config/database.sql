CREATE TABLE `item_latest_price` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`item_id` varchar(200) NOT NULL,
	`location` int(11) NOT NULL,
	`price` int(11) NOT NULL,
	`updated_at` timestamp NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `item_id__location_UNIQUE` (`item_id`,`location`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

CREATE TABLE `item_prices_history` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`item_id` varchar(200) NOT NULL,
	`location` int(11) NOT NULL,
	`price` int(11) NOT NULL,
	`updated_at` timestamp NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `item_id__location__updated_at_UNIQUE` (`item_id`,`location`,`updated_at`)
) ENGINE=InnoDB AUTO_INCREMENT=225 DEFAULT CHARSET=latin1;

DROP TRIGGER market_orders_insert_after;
DELIMITER //
CREATE TRIGGER market_orders_insert_after
	AFTER INSERT
		ON market_orders
	FOR EACH ROW
BEGIN
	IF (NEW.auction_type = 'offer') THEN
		INSERT INTO
			item_prices_history (item_id, location, price, updated_at)
		VALUES
			(NEW.item_id, NEW.location, NEW.price, NEW.updated_at)
		ON DUPLICATE KEY UPDATE
			 price = LEAST(price, NEW.price);
	END IF;
END; //
DELIMITER ;

DROP TRIGGER market_orders_update_after;
DELIMITER //
CREATE TRIGGER market_orders_update_after
	AFTER UPDATE
		ON market_orders
	FOR EACH ROW
BEGIN
	IF (NEW.auction_type = 'offer') THEN
		INSERT INTO
			item_prices_history (item_id, location, price, updated_at)
		VALUES
			(NEW.item_id, NEW.location, NEW.price, NEW.updated_at)
		ON DUPLICATE KEY UPDATE
			 price = LEAST(price, NEW.price);
	END IF;
END; //
DELIMITER ;

DROP TRIGGER item_prices_history_insert_after;
DELIMITER //
CREATE TRIGGER item_prices_history_insert_after
	AFTER INSERT
		ON item_prices_history
	FOR EACH ROW
BEGIN
	INSERT INTO
		item_latest_price (item_id, location, price, updated_at)
	VALUES
		(NEW.item_id, NEW.location, NEW.price, NEW.updated_at)
	ON DUPLICATE KEY UPDATE
		 price = NEW.price, updated_at = NEW.updated_at;
END; //
DELIMITER ;