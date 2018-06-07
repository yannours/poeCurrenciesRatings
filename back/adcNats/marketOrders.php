<?php

require_once("lib/phpnats/vendor/autoload.php");
require_once("../config/databaseConfig.php");

/**
 * Nats client, used to gather and process all data from the Albion Data Client
 *
 *  Note on location codes :
 *  $locationsCode = [
 *  	-1 => "Unknown",
 *  	0 => "ThetfordMarket",
 *  	1000 => "LymhurstMarket",
 *  	2000 => "BridgewatchMarket",
 *  	3003 => "BlackMarket",
 *  	3004 => "MartlockMarket",
 *  	3005 => "CaerleonMarket",
 *  	4000 => "FortSterlingMarket",
 *
 *  	4 => "SwampCrossMarket",
 *  	1006 => "ForestCrossMarket",
 *  	2002 => "SteppeCrossMarket",
 *  	3002 => "HighlandCrossMarket",
 *  	4006 => "MountainCrossMarket",
 *  ];
 */

$connectionOptions = new \Nats\ConnectionOptions();
$connectionOptions->setHost('192.241.250.27')->setPort(4222)->setUser('public')->setPass('thenewalbiondata');

// The connection is not supposed to be stop, but in this event, we relaunch it as ofter as necessary
while (1) {

	try {
		// New connection
		$client = new \Nats\Connection($connectionOptions);
		$client->setStreamTimeout(9000000);
		$client->connect();

		$client->subscribe(
		    'marketorders.ingest',
		    function ($results) {
		    	$results = json_decode($results->getBody(), true);

		    	if(!empty($results['Orders'])) {
		    		$ordersList = $results['Orders'];

			        $prices = [];
				    $dbConnection = new PDO("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_BASE, DB_USER, DB_PASSWORD);
				    $insertStatement = $dbConnection->prepare("INSERT INTO item_prices_history (item_id, location, price) VALUES(?, ?, ?)");

					// All order from the lists are processed in order to get, for each item, the cheaper one
				    foreach ($ordersList as $order) {
						if ($order['AuctionType'] == 'offer') {
					        if (empty($prices[$order['ItemTypeId']]) or $order['UnitPriceSilver'] < $prices[$order['ItemTypeId']]['price']) {

					            $prices[$order['ItemTypeId']] = [
					                'location' => $order['LocationId'],
					                'price' => $order['UnitPriceSilver']
					            ];
					        }
					    }
			    	}

					// Once all prices are fixed, we update them into the database
				    foreach ($prices as $item => $priceDatas) {
				        $insertStatement->execute([$item, $priceDatas['location'], ($priceDatas['price']/10000)]);
				    }
			    }
		    }
		);

		$client->wait();	
	} catch (Exception $e) {
		print_r($e->getMessage().PHP_EOL);
	}
}