<?php

/**
 * File with all functions managing prices on the DB
 *
 */

require_once(__DIR__."/../config/databaseConfig.php");


/**
 * Get the most recent prices of all items, for all tiers and for all rarities if they are provided
 */
function getLatestPrices($items, $location, $tiers = null, $rarities = null) {

    $dbConnection = new PDO("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_BASE, DB_USER, DB_PASSWORD);
	$selectStatement = $dbConnection->prepare("SELECT price FROM item_latest_price WHERE item_id = ? AND location = ?");

	$prices = [];

	foreach ($items as $item) {
		if (!empty($tiers)) {
			foreach ($tiers as $tier) {

				$itemId = "T".$tier."_".$item;

				if (!empty($rarities)) {
					foreach ($rarities as $rarity) {

						// Prevent rarity call if tier > 0
						if ($rarity > 0 && $tier > 3) {
							// Special case : resources
							if (in_array($item, ["WOOD", "PLANKS", "ORE", "METALBAR", "HIDE", "LEATHER", "FIBER", "CLOTH"])) {
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
	$minStatement = $dbConnection->prepare("SELECT min(price) FROM item_prices_history WHERE item_id = ? AND updated_at > ? AND location = ?");
	$maxStatement = $dbConnection->prepare("SELECT max(price) FROM item_prices_history WHERE item_id = ? AND updated_at > ? AND location = ?");

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
