<?php

/**
 * File with all functions managing prices on DB
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

require_once(__DIR__."/../config/databaseConfig.php");

/**
 * Return the minimal price of all $items presents in $ordersList
 * $orderList example :
 *        [0] => Array
 *          (
 *             [Id] => 149385216
 *             [ItemTypeId] => T4_ORE_LEVEL2@2
 *             [ItemGroupTypeId] => T4_ORE_LEVEL2
 *             [LocationId] => 3005
 *             [QualityLevel] => 1
 *             [EnchantmentLevel] => 2
 *             [UnitPriceSilver] => 80000
 *             [Amount] => 60
 *             [AuctionType] => offer
 *             [Expires] => 2018-06-22T14:26:16.20772
 *          )
 *        [1] => Array
 *          ( ... )
 */
function savePricesToDB($ordersList) {

    $prices = [];
    $dbConnection = new PDO("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_BASE, DB_USER, DB_PASSWORD);
    $insertStatement = $dbConnection->prepare("INSERT INTO item_prices_history (item_type, location_id, price) VALUES(?, ?, ?)");

    foreach ($ordersList as $order) {
		if ($order['AuctionType'] == 'offer') {
	        if (empty($prices[$order['ItemGroupTypeId']]) or $order['UnitPriceSilver'] < $prices[$order['ItemGroupTypeId']]['price']) {

	        	// For some enchanted items, the "_LEVELX" string is not set
	        	$itemCode = $order['ItemGroupTypeId'];
	        	if ($order['EnchantmentLevel'] > 0 and substr($itemCode, -7, -1) !== '_LEVEL') {
	        		$itemCode .= '_LEVEL'.$order['EnchantmentLevel'];
	        	}
	            $prices[$itemCode] = [
	                'location_id' => $order['LocationId'],
	                'price' => ($order['UnitPriceSilver']/10000)
	            ];
	        }
	    }
    }


    foreach ($prices as $item => $price) {
        $insertStatement->execute([$item, $price['location_id'], $price['price']]);
    }

    return $prices;
}


/**
 * Get the most recent prices of all items, for all tiers and for all rarities if they are provided
 */
function getLatestPrices($items, $location, $tiers = null, $rarities = null) {

    $dbConnection = new PDO("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_BASE, DB_USER, DB_PASSWORD);
	$selectStatement = $dbConnection->prepare("SELECT price FROM item_latest_price WHERE item_type = ? AND location_id = ?");

	$prices = [];

	foreach ($items as $item) {
		if ($tiers) {
			foreach ($tiers as $tier) {

				$itemId = "T".$tier."_".$item;

				if ($rarities) {
					foreach ($rarities as $rarity) {

						// Prevent rarity call if tier > 0
						if ($rarity > 0 && $tier > 3) {
							// Special case : resources
							if (in_array($item, ["WOOD", "PLANKS", "ORE", "METALBAR", "HIDE", "LEATHER", "FIBER", "CLOTH", "ROCK", "STONEBLOCK"])) {
								$itemId .= '_LEVEL'.$rarity;
							}
							$itemId .= '@'.$rarity;
						}

						$selectStatement->execute([$itemId, $location]);
						if($price = $selectStatement->fetchColumn()) {
							$prices[$item][$tier][$rarity] = $price;
						}
					}
				} else {
					$selectStatement->execute([$itemId, $location]);
					if($price = $selectStatement->fetchColumn()) {
						$prices[$item][$tier] = $price;
					}
				}
			}
		} else {
			$selectStatement->execute([$item, $location]);
			if($price = $selectStatement->fetchColumn()) {
				$prices[$item] = $price;
			}
		}
	}

	return $prices;
}


/**
* Get the minimum and maximum price of an item over X days.
*/
function getMinMaxPrices($items, $days, $location) {

	$minDate = date(DATE_ATOM, mktime(date("H") - 24 * $days));

	$dbConnection = new PDO("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_BASE, DB_USER, DB_PASSWORD);
	$minStatement = $dbConnection->prepare("SELECT min(price) FROM item_prices_history WHERE item_type = ? AND date > ? AND location_id = ?");
	$maxStatement = $dbConnection->prepare("SELECT max(price) FROM item_prices_history WHERE item_type = ? AND date > ? AND location_id = ?");

	$currentPrices = getLatestPrices($items, $location);

	$prices = [];
	foreach ($items as $itemCode) {
		if (!empty($currentPrices[$itemCode])) {
			$prices[$itemCode]['current'] = $currentPrices[$itemCode];
			$minStatement->execute([$itemCode, $minDate, $location]);
			$prices[$itemCode]['min'] = $minStatement->fetchColumn();
			$maxStatement->execute([$itemCode, $minDate, $location]);
			$prices[$itemCode]['max'] =  $maxStatement->fetchColumn();
		}
	}

	return $prices;
}