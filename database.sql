CREATE TABLE `item_latest_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_type` varchar(200) NOT NULL,
  `location_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `item_type__location_UNIQUE` (`item_type`,`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

CREATE TABLE `item_prices_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_type` varchar(200) NOT NULL,
  `location_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=225 DEFAULT CHARSET=latin1;

DROP TRIGGER item_prices_history_insert;
DELIMITER //
CREATE TRIGGER item_prices_history_insert
    AFTER INSERT
    	ON item_prices_history
    FOR EACH ROW
BEGIN
	INSERT INTO
		item_latest_price (item_type, location_id, price, date)
	VALUES
		(NEW.item_type, NEW.location_id, NEW.price, NEW.date)
	ON DUPLICATE KEY UPDATE
		 price = NEW.price, date = NEW.date;
END; //
DELIMITER ;