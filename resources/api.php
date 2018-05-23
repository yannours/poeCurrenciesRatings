<?php

/**
 * File with all functions calling the API
 */

/**
 * Get the prices of all products, for all tiers, for a specified rarity.
 * return array :
 * 	[
 *		product => [
 *					T3 => 55,
 *					T4 => 95
 *		],
 *		product2 => [
 *					T3 => 555,
 *					T4 => 955
 *		]
 *	]
 */
function getApiPrices($products, $tiers, $rarity = 0) {

	$ch = curl_init();
	// Disable SSL verification
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	// Will return the response, if false it print the response
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$prices = [];

	foreach ($products as $product) {
		foreach ($tiers as $tier) {
			// Call API
			$url = API_URL."T".$tier."_".$product;
			// Prevent rarity call if tier > 0
			if ($rarity > 0 && $tier > 3) {
				$url .= RARITY_STRING.$rarity;
			}

			curl_setopt($ch, CURLOPT_URL,$url);
			$requestResult = curl_exec($ch);
			$jsonResults = json_decode($requestResult, true);

			foreach ($jsonResults as $result) {
				// Set a minimum date so older prices are not shown
				if (!empty($result['sell_price_min']) && $result['city'] === MARKET &&
						$result['sell_price_min_date'] > MIN_PRICE_DATE) {
					$prices[$product][$tier] = $result['sell_price_min'];
					break;
				}
			}

		}
	}

	// Closing
	curl_close($ch);

	return $prices;
}