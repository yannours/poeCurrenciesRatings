<?php
/**
 * This page calculate the min / max price of all resources from the last X days
 */


require_once(__DIR__."/../resources/pricesHistory.php");
require_once(__DIR__."/../resources/pricesCalculation.php");

$rarities = (isset($_GET['rarity']) && $_GET['rarity'] >= 0 && $_GET['rarity'] <= 3) ? [$_GET['rarity']] : [0, 1] ;
$days = isset($_GET['days']) ? $_GET['days'] : 5;
$location = isset($_GET['location']) ? $_GET['location'] : 3005; // 3005 : Caerleon

$itemsToProcess = [];

// Add resources to the list
$tiers = [3, 4, 5, 6, 7, 8];
foreach ($tiers as $tier) {
	foreach ($rarities as $rarity) {
		foreach ($resourcesTypes as $rawResource => $refinedResource) {
			$rawResourceCode = 'T'.$tier.'_'.$rawResource;
			$refinedResourceCode = 'T'.$tier.'_'.$refinedResource;

			if ($rarity > 0 && $tier > 3 && $rawResource !== 'ROCK') {
				$rawResourceCode .= '_LEVEL'.$rarity.'@'.$rarity;
				$refinedResourceCode .= '_LEVEL'.$rarity.'@'.$rarity;
			}

			$itemsToProcess[] = $rawResourceCode;
			$itemsToProcess[] = $refinedResourceCode;
		}
	}
}

// Add pie and poison
$itemsToProcess[] = 'T7_MEAL_PIE';
$itemsToProcess[] = 'T4_POTION_COOLDOWN';
$itemsToProcess[] = 'T6_POTION_COOLDOWN';
$itemsToProcess[] = 'T8_POTION_COOLDOWN';

// Get prices
$resourcesMinMaxPrices = getMinMaxPrices($itemsToProcess, $days, $location);
$stats = getPricesStats($resourcesMinMaxPrices);

if (isset($_GET['noJson'])) {
	echo "<pre>".print_r($stats, true)."</pre>";
} else {
	print_r(json_encode($stats));
}