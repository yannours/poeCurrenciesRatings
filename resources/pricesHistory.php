<?php

/**
 * File with all functions managing prices on DB
 */

require_once("../databaseConfig.php");

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
 *             [EnchantmentLevel] => 0
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

        if (empty($prices[$order['ItemGroupTypeId']]) or $order['UnitPriceSilver'] < $prices[$order['ItemGroupTypeId']]['price']) {
            $prices[$order['ItemGroupTypeId']] = [
                'location_id' => $order['LocationId'],
                'price' => ($order['UnitPriceSilver']/10000)
            ];
        }

    }


    foreach ($prices as $item => $price) {
        $insertStatement->execute([$item, $price['location_id'], $price['price']]);
    }

    return $prices;
}


/**
 * Get the most recent prices of all items, for all tiers, for a specified rarity.
 * return array :
 * 	[
 *		item => [
 *					T3 => 55,
 *					T4 => 95
 *		],
 *		item2 => [
 *					T3 => 555,
 *					T4 => 955
 *		]
 *	]
 * $location : Caerleon (3005) by default
 */
function getLatestPrices($items, $tiers, $rarity = 0, $location = 3005) {

    $dbConnection = new PDO("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_BASE, DB_USER, DB_PASSWORD);
	$selectStatement = $dbConnection->prepare("SELECT price FROM item_latest_price WHERE item_type = ?");

	$prices = [];

	foreach ($items as $item) {
		foreach ($tiers as $tier) {
			// Call API
			$itemType = "T".$tier."_".$item;
			// Prevent rarity call if tier > 0
			if ($rarity > 0 && $tier > 3) {
				$itemType .= RARITY_STRING.$rarity;
			}

			$selectStatement->execute([$itemType]);
			if($price = $selectStatement->fetchColumn()) {
				$prices[$item][$tier] = $price;
			}

		}
	}

	return $prices;
}
