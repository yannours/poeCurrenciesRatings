<?php 

define('RARITY_STRING', "_ORE_LEVEL1@");
define('MARKET', "Caerleon Market");
define('API_URL', "https://www.albion-online-data.com/api/v1/stats/prices/");

$tiers = ["4","5","6","7","8"];
$resourcesTypes = ["PLANKS", "METALBAR", "LEATHER", "CLOTH"];

$resourcePrices = getResourcePrices($tiers);

// Ressources : ["PLANKS", "METALBAR", "LEATHER", "CLOTH"]
$recipes = [
	[
		"Warbow",
		"2H_WARBOW",
		[32,0,0,0]
	]
];

function getResourcePrices($resourcesTypes, $tiers, rarity = "") {

	$resourcePrices = [];

	foreach ($resourcesTypes as $resourceType) {
		foreach ($tiers as $tier) {
			$requestResult = file_get_contents(API_URL."T".$tier."_".$resourceType);
			$jsonResults = json_decode($requestResult));

			foreach ($jsonResults as $result) {
				if ($result['city'] === MARKET) {
					$resourcePrices[$resourceType][$tier] = $result['sell_price_min'];
				}
			}
		}
	}

}

var_dump($resourcePrices);