<?php 

define('RARITY_STRING', "_ORE_LEVEL1@");
define('MARKET', "Caerleon Market");
define('API_URL', "https://www.albion-online-data.com/api/v1/stats/prices/");

$tiers = ["4","5","6","7","8"];
$resourcesTypes = ["PLANKS", "METALBAR", "LEATHER", "CLOTH"];

$resourcePrices = getResourcePrices($resourcesTypes, $tiers);

// Ressources : ["PLANKS", "METALBAR", "LEATHER", "CLOTH"]
$recipes = [
	[
		"Warbow",
		"2H_WARBOW",
		[32,0,0,0]
	]
];

function getResourcePrices($resourcesTypes, $tiers, $rarity = "") {

	$resourcePrices = [];

	foreach ($resourcesTypes as $resourceType) {
		foreach ($tiers as $tier) {
			$requestResult = file_get_contents(API_URL."T".$tier."_".$resourceType);
			$resourcePrices = json_decode($requestResult);
break 2;
			/*foreach ($jsonResults as $result) {
				if ($result['city'] === MARKET) {
					$resourcePrices[$resourceType][$tier] = $result['sell_price_min'];
					break;
				}
			}*/
		}
	}

	return $resourcePrices;
}

var_dump($resourcePrices);