<?php
/**
 * This page calculate the min / max price of all resources from the last X days
 */


require_once(__DIR__."/../resources/pricesHistory.php");
require_once(__DIR__."/../resources/pricesCalculation.php");
require_once(__DIR__."/../resources/simpleFront.php");

$rarities = (isset($_GET['rarity']) && $_GET['rarity'] >= 0 && $_GET['rarity'] <= 3) ? [$_GET['rarity']] : [0, 1] ;
$days = isset($_GET['days']) ? $_GET['days'] : 5;
$location = isset($_GET['location']) ? $_GET['location'] : 3005; // 3005 : Caerleon

$itemsToProcess = [];

$resourcesTypes = ["WOOD", "PLANKS", "ORE", "METALBAR", "HIDE", "LEATHER", "FIBER", "CLOTH", "ROCK", "STONEBLOCK"];

// Add resources to the list
$tiers = [3, 4, 5, 6, 7, 8];
foreach ($tiers as $tier) {
	foreach ($rarities as $rarity) {
		foreach ($resourcesTypes as $resourceType) {
			$resourceCode = 'T'.$tier.'_'.$resourceType;

			if ($rarity > 0 && $tier > 3 && $resourceType !== 'ROCK' && $resourceType !== "STONEBLOCK") {
				$resourceCode .= '_LEVEL'.$rarity.'@'.$rarity;
			}

			$itemsToProcess[] = $resourceCode;
		}
	}
}

// Add pie and poison
$itemsToProcess[] = 'T7_MEAL_PIE';
$itemsToProcess[] = 'T4_POTION_COOLDOWN';
$itemsToProcess[] = 'T6_POTION_COOLDOWN';
$itemsToProcess[] = 'T8_POTION_COOLDOWN';

// Get prices
$minMaxPrices = getMinMaxPrices($itemsToProcess, $days, $location);
$stats = getPricesStats($minMaxPrices);

if (isset($_GET['noJson'])) {
	simpleFront::printArray('Evolution des prix sur '.$days.' jours', ['Item', 'Prix actuel', 'Prix min', 'Prix max', 'Variation', 'action', 'Niveau actuel'], $stats);
} else {
	print_r(json_encode($stats));
}