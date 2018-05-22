<?php



$tiers = ["4","5","6","7","8"];
$refinedResourcesTypes = ["PLANKS", "METALBAR", "LEATHER", "CLOTH"];

$resourcePrices = getResourcePrices($refinedResourcesTypes, $tiers);

// Ressources : ["PLANKS", "METALBAR", "LEATHER", "CLOTH"]
$recipes = [
	[
		"Warbow",
		"2H_BOW",
		[32,0,0,0]
	],
	[
		"Warbow",
		"2H_LONGBOW",
		[32,0,0,0]
	],
	[
		"Warbow",
		"2H_WARBOW",
		[32,0,0,0]
	]
];

function getResourcePrices($refinedResourcesTypes, $tiers, $rarity = "") {

	$resourcePrices = [];

	foreach ($refinedResourcesTypes as $resourceType) {
		foreach ($tiers as $tier) {
			$requestResult = file_get_contents(API_URL."T".$tier."_".$resourceType);
			$jsonResults = json_decode($requestResult, true);

			foreach ($jsonResults as $result) {
				if ($result['city'] === MARKET) {
					$resourcePrices[$resourceType][$tier] = $result['sell_price_min'];
					break;
				}
			}
		}
	}

	return $resourcePrices;
}

var_dump($resourcePrices);