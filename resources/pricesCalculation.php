<?php

/**
 * File with all functions calculing prices
 */

// Item value of all refined resources by tiers / rarity
const REFINED_RESOURCES_VALUE = [
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

/**
 * Calculate refining cost using only resources price
 * $resourcesTypes = ["WOOD" => "PLANKS", ...]
 * $tiers = [1, 3, 5]
 * $resourcesPrices = based on getLatestPrices return
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
* Calculate full refining profit assuming the resources returned after first refining are fully crafted without focus
* $resourcesTypes = ["WOOD" => "PLANKS", ...]
* $tiers = [1, 3, 5]
* $rarity = 0|1|2|3
* $resourcesPrices = based on getLatestPrices return
* $refiningCosts = based on getResourcesRefiningCost return
* $taxe = taxe in %
* $focus = (true|false) : is focus used ?
* Return array with all needed informations
*/
function getResourcesRefiningProfit($resourcesTypes, $tiers, $rarity, $resourcesPrices, $refiningCosts, $taxe, $focus) {

   $return = [];

   foreach ($resourcesTypes as $rawResourceType => $refinedResourceType) {
	   foreach ($tiers as $tier) {
		   	if (!empty($resourcesPrices[$refinedResourceType][$tier]) && ! empty($refiningCosts[$refinedResourceType][$tier])) {

				$refiningTaxe = ceil(REFINED_RESOURCES_VALUE[$tier][$rarity] * 5 * $taxe / 100);
				// Profit = Selling price * return rate (base on 15% rr) * (1- selling taxes) - (resource cost + crafting taxes)
				// Selling taxe : 2% selling taxe + 1% per sale order, made 2 times if the first one fail
				// Return rate : 45% with focus, 15% without. Assuming the return is fully craft without focus
				$returnRate = $focus ? 1.53 : 1.175 ;
				$profit = round($resourcesPrices[$refinedResourceType][$tier]*$returnRate*0.96
					- ($refiningCosts[$refinedResourceType][$tier] + $refiningTaxe*$returnRate));

				$return[$refinedResourceType][$tier] = [
					"raw_resource_cost" => $resourcesPrices[$rawResourceType][$tier],
					"refined_resource_cost" => $resourcesPrices[$refinedResourceType][($tier-1)],
					"selling price" => $resourcesPrices[$refinedResourceType][$tier],
					"taxe" => $refiningTaxe,
					"profit" => $profit
				];
			}
	   }
   }

   return $return;
}

/**
* Calculate full crafting profit assuming the resources returned are sold back without selling fee
* $recipes
* $tiers = [1, 3, 5]
* $resourcesPrices = based on getLatestPrices return
* $taxe = taxe in %
* $focus = (true|false) : is focus used ?
* Return array with all needed informations
*/
function getCraftingProfit($recipes, $tiers, $resourcesPrices, $rarity, $taxe, $focus, $location) {

   	$return = [];

	foreach ($recipes as $group => $subGroup) {

		if ($group === "Consumable") {
			continue; //TODO
		} else {

			foreach ($subGroup as $subGroupName => $itemRecipes) {

				$itemPrices = getLatestPrices(array_keys($itemRecipes), $tiers, $rarity, $location);

				foreach ($tiers as $tier) {

					if (!empty($resourcesPrices['PLANKS'][$tier]) && !empty($resourcesPrices['METALBAR'][$tier])
						&& !empty($resourcesPrices['LEATHER'][$tier])  && !empty($resourcesPrices['CLOTH'][$tier])) {

						$plankPrice = $resourcesPrices['PLANKS'][$tier];
						$metalbarPrice = $resourcesPrices['METALBAR'][$tier];
						$leatherPrice = $resourcesPrices['LEATHER'][$tier];
						$clothPrice = $resourcesPrices['CLOTH'][$tier];

						foreach ($itemRecipes as $code => $recipe) {

							if(!empty($itemPrices[$code][$tier])) {
								// $craftingTaxe is equals to the sum of the item value of all items used in the recipe, x 5 x taxing rate
							   	$craftingTaxe = ($recipe['resources'][0] + $recipe['resources'][1] + $recipe['resources'][2] + $recipe['resources'][3]) *
		 						REFINED_RESOURCES_VALUE[$tier][$rarity] * 5 * ($taxe / 100);
								$sellingPrice = $itemPrices[$code][$tier];
								$resourcesCost = $recipe['resources'][0] * $plankPrice + $recipe['resources'][1] * $metalbarPrice
												+ $recipe['resources'][2] * $leatherPrice + $recipe['resources'][3] * $clothPrice;
								$returnRate = $focus ? 0.45 : 0.15 ;
								// Profit = Selling price * (1- selling taxes) - (resource cost * (1 - return rate) + crafting taxes)
								// Selling taxe : 2% selling taxe + 1% per sale order, made 2 times if the first one fail
								// Return rate : 45% with focus, 15% without.
								$profit = $sellingPrice * 0.96 - ($resourcesCost * (1-$returnRate) + $craftingTaxe);

								$return[$group][$subGroupName][$code][$tier] = [
								   "resources_cost" => $resourcesCost,
								   "selling price" => $sellingPrice,
								   "taxe" => $craftingTaxe,
								   "profit" => $profit
								];
							}
						}
					}
				}
		   	}
		}
   }

   return $return;
}
