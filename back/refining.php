<?php
/**
 * This page calculate all refining price and display all the informations used to calculed it.
 */

require_once("../resources/pricesHistory.php");
require_once("../resources/pricesCalculation.php");

$resourcesTypes = [
	"WOOD" => "PLANKS",
 	"ORE" => "METALBAR",
 	"HIDE" => "LEATHER",
 	"FIBER" => "CLOTH",
 	"ROCK" => "STONEBLOCK"
];

$rarities = (isset($_GET['rarity']) && $_GET['rarity'] >= 0 && $_GET['rarity'] <= 3) ? [$_GET['rarity']] : [0, 1] ;
$focus = isset($_GET['focus']) ? true : false ;
$taxe = isset($_GET['taxe']) ? $_GET['taxe'] : 22;
$location = isset($_GET['location']) ? $_GET['location'] : 3005; // 3005 : Caerleon

$resourcesPrices = getLatestPrices(array_merge(array_keys($resourcesTypes), $resourcesTypes), $location, [3, 4, 5, 6, 7, 8], $rarities);
$refiningCosts = getResourcesRefiningCost($resourcesTypes, [4, 5, 6, 7, 8], $resourcesPrices);
$refiningProfits = getResourcesRefiningProfit($resourcesTypes, [4, 5, 6, 7, 8], $rarities, $resourcesPrices, $refiningCosts, $taxe, $focus);

if (isset($_GET['noJson'])) {
	echo "<pre>".print_r($refiningProfits, true)."</pre>";
} else {
	print_r(json_encode($refiningProfits));
}