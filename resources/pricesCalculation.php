<?php

/**
 * File with all functions calculing prices
 */

/**
 * Calculate refining cost using only resources price
 * return array :
 * 	[
 *		resource1 => [
 *					T4 => 55,
 *					T5 => 95
 *		],
 *		resource2 => [
 *					T4 => 555,
 *					T5 => 955
 *		]
 *	]
 */
function getResourcesRefiningCost($resourcesTypes, $tiers, $resourcesPrices) {

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

	$costs = [];

	foreach ($resourcesTypes as $rawResourceType => $refinedResourceType) {
		foreach ($tiers as $tier) {
			if (!empty($resourcesPrices[$refinedResourceType][($tier-1)]) && !empty($resourcesPrices[$rawResourceType][$tier])) {
				$costs[$refinedResourceType][$tier] = $resourcesPrices[$refinedResourceType][($tier-1)] + $resourcesPrices[$rawResourceType][$tier] * $rawResourcesNeeded[$tier];
			}
		}
	}

	return $costs;
}

/**
* Calculate full refining profit
* return array :
* 	[
*		resource1 => [
*					T4 => 55,
*					T5 => 95
*		],
*		resource2 => [
*					T4 => 555,
*					T5 => 955
*		]
*	]
*/
function getResourcesRefiningProfit($resourcesTypes, $tiers, $resourcesPrices, $refiningCosts, $taxePercent = 8) {

   // For tier $key with 100% taxes, you pay $value silver of taxe
   $fullTaxe = [
	   2 => 0,
	   3 => 30,
	   4 => 70,
	   5 => 160,
	   6 => 320,
	   7 => 64,
	   8 => 128
   ];

   $return = [];

   foreach ($resourcesTypes as $rawResourceType => $refinedResourceType) {
	   foreach ($tiers as $tier) {
		   	if (!empty($resourcesPrices[$refinedResourceType][$tier]) && ! empty($refiningCosts[$refinedResourceType][$tier])) {

				$taxe = ($fullTaxe[$tier] * $taxePercent / 100);
				// Profit = Selling price * return rate (base on 15% rr) * (1- selling taxes) - (resource cost + crafting taxes)
				$profit = $resourcesPrices[$refinedResourceType][$tier]*1.175*0.97 - ($refiningCosts[$refinedResourceType][$tier] + $taxe*1.175);

				$return[$refinedResourceType][$tier] = [
					"raw_resource_cost" => $resourcesPrices[$rawResourceType][$tier],
					"refined_resource_cost" => $resourcesPrices[$refinedResourceType][($tier-1)],
					"selling price" => $resourcesPrices[$refinedResourceType][$tier],
					"taxe" => $taxe,
					"profit" => $profit
				];
			}
	   }
   }

   return $costs;
}
