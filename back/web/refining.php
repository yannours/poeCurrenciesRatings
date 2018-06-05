<?php
/**
 * This page calculate all refining price and display all the informations used to calculed it.
 */

require_once(__DIR__."/../resources/pricesHistory.php");
require_once(__DIR__."/../resources/pricesCalculation.php");

$rarities = (isset($_GET['rarity']) && $_GET['rarity'] >= 0 && $_GET['rarity'] <= 3) ? [$_GET['rarity']] : [0, 1] ;
$focus = isset($_GET['focus']) ? true : false ;
$taxe = isset($_GET['taxe']) ? $_GET['taxe'] : 22;
$location = isset($_GET['location']) ? $_GET['location'] : 3005; // 3005 : Caerleon

$resourcesTypes = [
	"WOOD" => "PLANKS",
 	"ORE" => "METALBAR",
 	"HIDE" => "LEATHER",
 	"FIBER" => "CLOTH",
 	"ROCK" => "STONEBLOCK"
];

$resourcesPrices = getLatestPrices(array_merge(array_keys($resourcesTypes), $resourcesTypes), $location, [3, 4, 5, 6, 7, 8], $rarities);
$refiningProfits = getResourcesRefiningProfit($resourcesTypes, [4, 5, 6, 7, 8], $rarities, $resourcesPrices, $taxe, $focus);

if (isset($_GET['noJson'])) {
	echo "<pre>".print_r($refiningProfits, true)."</pre>";
} else {
	print_r(json_encode($refiningProfits));
}

/**
* Calculate full refining profit assuming the resources returned after first refining are sold back to their buying price
* $resourcesTypes = ["WOOD" => "PLANKS", ...]
* $tiers = [1, 3, 5]
* $rarities = [0, 1, 2, 3]
* $resourcesPrices = based on getLatestPrices return
* $taxe = taxe in %
* $focus = (true|false) : is focus used ?
* Return array with all needed informations
*/
function getResourcesRefiningProfit($resourcesTypes, $tiers, $rarities, $resourcesPrices, $taxe, $focus) {

   $return = [];

	//special case : T2 and T3 doesn't have a rarity but we fake it to keep the algorithm simple
   	foreach (array_merge(array_keys($resourcesTypes), $resourcesTypes) as $resourceType) {
		foreach ([2, 3] as $tier) {
			if (!empty($resourcesPrices[$resourceType][$tier][0])) {
				$resourcesPrices[$resourceType][$tier][1] = $resourcesPrices[$resourceType][$tier][0];
				$resourcesPrices[$resourceType][$tier][2] = $resourcesPrices[$resourceType][$tier][0];
				$resourcesPrices[$resourceType][$tier][3] = $resourcesPrices[$resourceType][$tier][0];
			}
		}
	}

	// For tier $key you need $value raw ressources
	$rawResourcesNeeded = [
	   2 => 1,
	   3 => 2,
	   4 => 2,
	   5 => 3,
	   6 => 4,
	   7 => 5,
	   8 => 5
	];

	// Item value of all refined resources by tiers / rarity
	$refinedResourcesValues = [
	  2 => [0 => 0], // It's 2 in reality, but crafting fee is null for T2
	  3 => [0 => 6],
	  4 => [
				0 => 14,
				1 => 30,
				2 => 54,
				3 => 102
			],
	  5 => [
				0 => 30.02,
				1 => 61.98,
				2 => 118.02,
				3 => 229.98
			],
	  6 => [
				0 => 62.02,
				1 => 125.98,
				2 => 246.02,
				3 => 485.98
			],
	  7 => [
				0 => 126.02,
				1 => 253.98,
				2 => 502.02,
				3 => 997.98
			],
	  8 => [
				0 => 254.02,
				1 => 509.98,
				2 => 1014.02,
				3 => 2021.98
			]
	];

   	foreach ($resourcesTypes as $rawResourceType => $refinedResourceType) {
	   	foreach ($tiers as $tier) {
			foreach($rarities as $rarity) {
	   			if (!empty($resourcesPrices[$rawResourceType][$tier][$rarity]) && !empty($resourcesPrices[$refinedResourceType][$tier][$rarity]) && ! empty($resourcesPrices[$refinedResourceType][($tier-1)][$rarity])) {

					$refiningTaxe = ceil($refinedResourcesValues[$tier][$rarity] * 5 * $taxe / 100);
					// Profit = Selling price * return rate (base on 15% rr) * (1- selling taxes) - (resource cost + crafting taxes)
					// Selling taxe : 2% selling taxe + 1% per sale order, made 2 times if the first one fail
					// Return rate : 100%-45% with focus, 100% - 15% without.
					$resourcesUse = $focus ? 0.55 : 0.85 ;
					$resourcesCost = $resourcesPrices[$refinedResourceType][($tier-1)][$rarity] + $resourcesPrices[$rawResourceType][$tier][$rarity] * $rawResourcesNeeded[$tier];
					$profit = round($resourcesPrices[$refinedResourceType][$tier][$rarity]*$returnRate*0.96
						- ($resourcesCost*$resourcesUse + $refiningTaxe));

					$return[$refinedResourceType][$tier][$rarity] = [
						"raw_resource_cost" => $resourcesPrices[$rawResourceType][$tier][$rarity],
						"refined_resource_cost" => $resourcesPrices[$refinedResourceType][($tier-1)][$rarity],
						"selling_price" => $resourcesPrices[$refinedResourceType][$tier][$rarity],
						"taxe" => $refiningTaxe,
						"profit" => $profit
					];
				}
			}
	   }
   }

   return $return;
}