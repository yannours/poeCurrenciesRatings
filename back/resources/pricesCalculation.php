<?php

/**
 * File with all functions calculing prices
 */




/**
* Calculate full crafting profit assuming the resources returned are sold back without selling fee
* $recipes
* $tiers = [1, 3, 5]
* $resourcesPrices = based on getLatestPrices return
* $taxe = taxe in %
* $focus = (true|false) : is focus used ?
* Return array with all needed informations
*/
function getCraftingProfit($recipes, $tiers, $resourcesPrices, $rarities, $taxe, $focus, $location) {

   	$return = [];
    $refined_resources_value = unserialize(REFINED_RESOURCES_VALUE);

	foreach ($recipes as $group => $subGroup) {

		if ($group === "Consumable") {
			continue; //TODO
		} else {

			foreach ($subGroup as $subGroupName => $itemRecipes) {

				$itemPrices = getLatestPrices(array_keys($itemRecipes), $tiers, $rarities, $location);

				foreach ($tiers as $tier) {

					foreach ($rarities as $rarity) {

						if (!empty($resourcesPrices['PLANKS'][$tier]) && !empty($resourcesPrices['METALBAR'][$tier])
							&& !empty($resourcesPrices['LEATHER'][$tier])  && !empty($resourcesPrices['CLOTH'][$tier])) {

							$plankPrice = $resourcesPrices['PLANKS'][$tier];
							$metalbarPrice = $resourcesPrices['METALBAR'][$tier];
							$leatherPrice = $resourcesPrices['LEATHER'][$tier];
							$clothPrice = $resourcesPrices['CLOTH'][$tier];

							foreach ($itemRecipes as $code => $recipe) {

								if(!empty($itemPrices[$code][$tier][$rarity])) {
									// $craftingTaxe is equals to the sum of the item value of all items used in the recipe, x 5 x taxing rate
								   	$craftingTaxe = ($recipe['resources'][0] + $recipe['resources'][1] + $recipe['resources'][2] + $recipe['resources'][3]) *
			 						$refined_resources_value[$tier][$rarity] * 5 * ($taxe / 100);
									$sellingPrice = $itemPrices[$code][$tier][$rarity];
									$resourcesCost = $recipe['resources'][0] * $plankPrice + $recipe['resources'][1] * $metalbarPrice
													+ $recipe['resources'][2] * $leatherPrice + $recipe['resources'][3] * $clothPrice;
									$returnRate = $focus ? 0.45 : 0.15 ;
									// Profit = Selling price * (1- selling taxes) - (resource cost * (1 - return rate) + crafting taxes)
									// Selling taxe : 2% selling taxe + 1% per sale order, made 2 times if the first one fail
									// Return rate : 45% with focus, 15% without.
									$profit = $sellingPrice * 0.96 - ($resourcesCost * (1-$returnRate) + $craftingTaxe);

									$return[$group][$subGroupName][$code][$tier][$rarity] = [
									   "resources_cost" => $resourcesCost,
									   "selling_price" => $sellingPrice,
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
   }

   return $return;
}

/**
* Calculate stats about price of items from current, min and max prices
* Return array with all needed informations
*/
function getPricesStats($prices, $range = 10) {

   	$stats = [];

	foreach ($prices as $itemCode => $itemDatas) {

		if (!empty($itemDatas['min']) && !empty($itemDatas['max']) ) {
			$currentPrice = $itemDatas['current'];
			$minPrice = $itemDatas['min'];
			$maxPrice = $itemDatas['max'];

			$priceTotalRange = $maxPrice - $minPrice;
			$priceSmallRange = $priceTotalRange * $range / 100;

			$minRangePrice = $minPrice + $priceSmallRange;
			$maxRangePrice = $maxPrice - $priceSmallRange;

			$variation = round($priceTotalRange * 100 / $currentPrice);

			$action = ($variation > 10 && $currentPrice <= $minRangePrice) ? 'Buy' : (($variation > 10 && $currentPrice >= $maxRangePrice) ? 'Sell' : 'Wait');

			$stats[$itemCode]['currentPrice'] = $currentPrice;
			$stats[$itemCode]['minPrice'] = $minPrice;
			$stats[$itemCode]['maxPrice'] = $maxPrice;
			$stats[$itemCode]['minRangePrice'] = $minRangePrice;
			$stats[$itemCode]['maxRangePrice'] = $maxRangePrice;
			$stats[$itemCode]['action'] = $action;
			$stats[$itemCode]['variation'] = $variation.'%';
		}
   }

   return $stats;
}
