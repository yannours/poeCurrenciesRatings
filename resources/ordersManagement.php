<?php

/**
 * File with all functions managing orders from ADC
 */

/**
 * Return the minimal price of all $products presents in $ordersList
 * $orderList example :
 *        [0] => Array
 *        	(
 * 		       [Id] => 149385216
 * 		       [ItemTypeId] => T4_ORE_LEVEL2@2
 * 		       [ItemGroupTypeId] => T4_ORE_LEVEL2
 * 		       [LocationId] => 3005
 * 		       [QualityLevel] => 1
 * 		       [EnchantmentLevel] => 0
 * 		       [UnitPriceSilver] => 80000
 * 		       [Amount] => 60
 * 		       [AuctionType] => offer
 * 		       [Expires] => 2018-06-22T14:26:16.20772
 *        	)
 *        [1] => Array
 *        	( ... )
 */
function savePricesToDB($ordersList) {

	$currentTimestamp = time();

	$prices = [];

	foreach ($ordersList as $order) {

		if(empty($prices['ItemTypeId']) or $order['UnitPriceSilver'] < $prices['ItemTypeId']['price']) {
			$prices['ItemTypeId'] = [
				'location_id' => $order['LocationId'],
				'price' => $order['UnitPriceSilver']
			]
		}

	}

	// Insert in db : Set location & date

	return $prices;
}


Array
(
    [Orders] => Array
        (
            [0] => Array
                (
                    [Id] => 149385216
                    [ItemTypeId] => T3_FURNITUREITEM_TROPHY_GENERAL
                    [ItemGroupTypeId] => T3_FURNITUREITEM_TROPHY_GENERAL
                    [LocationId] => 3005
                    [QualityLevel] => 1
                    [EnchantmentLevel] => 0
                    [UnitPriceSilver] => 80000
                    [Amount] => 60
                    [AuctionType] => offer
                    [Expires] => 2018-06-22T14:26:16.20772
                )

            [1] => Array
                (
                    [Id] => 149386080
                    [ItemTypeId] => T3_FURNITUREITEM_TROPHY_GENERAL
                    [ItemGroupTypeId] => T3_FURNITUREITEM_TROPHY_GENERAL
                    [LocationId] => 3005
                    [QualityLevel] => 1
                    [EnchantmentLevel] => 0
                    [UnitPriceSilver] => 80000
                    [Amount] => 47
                    [AuctionType] => offer
                    [Expires] => 2018-05-30T14:27:49.011185
                )