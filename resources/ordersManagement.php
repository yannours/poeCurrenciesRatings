<?php

/**
 * File with all functions managing orders from ADC
 */

require_once("../databaseConfig.php");

/**
 * Return the minimal price of all $products presents in $ordersList
 * $orderList example :
 *        [0] => Array
 *          (
 *             [Id] => 149385216
 *             [ItemTypeId] => T4_ORE_LEVEL2@2
 *             [ItemGroupTypeId] => T4_ORE_LEVEL2
 *             [LocationId] => 3005
 *             [QualityLevel] => 1
 *             [EnchantmentLevel] => 0
 *             [UnitPriceSilver] => 80000
 *             [Amount] => 60
 *             [AuctionType] => offer
 *             [Expires] => 2018-06-22T14:26:16.20772
 *          )
 *        [1] => Array
 *          ( ... )
 */
function savePricesToDB($ordersList) {

    $prices = [];

    foreach ($ordersList as $order) {

        if(empty($prices['ItemTypeId']) or $order['UnitPriceSilver'] < $prices['ItemTypeId']['price']) {
            $prices[$order['ItemTypeId']] = [
                'location_id' => $order['LocationId'],
                'price' => ($order['UnitPriceSilver']/10000)
            ];
        }

    }

    $connection = new PDO("mysql:host=".DB_HOST.";dbname=".DB_BASE, DB_USER, DB_PASSWORD);
    $statement = $connection->prepare("INSERT INTO item_price (item_type, location_id, price) VALUES(?, ?, ?)");

    foreach ($prices as $item => $price) {
        $statement->execute([$item, $price['location_id'], $price['price']]);
    }

    return $prices;
}