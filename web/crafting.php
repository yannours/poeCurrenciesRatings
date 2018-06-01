<?php

require_once("../config.php");
require_once("../resources/pricesHistory.php");
require_once("../resources/pricesCalculation.php");
require_once("../resources/recipes.php");

$rarities = (isset($_GET['rarity']) && $_GET['rarity'] >= 0 && $_GET['rarity'] <= 3) ? [$_GET['rarity']] : [0, 1] ;
$focus = isset($_GET['focus']) ? true : false ;
$taxe = isset($_GET['taxe']) ? $_GET['taxe'] : 22;
$location = isset($_GET['location']) ? $_GET['location'] : 3005; // 3005 : Caerleon

$resourcesPrices = getLatestPrices($resourcesTypes, [3, 4, 5, 6, 7, 8], $rarities, $location);
$craftingProfits = getCraftingProfit($recipes, [4, 5, 6, 7, 8], $resourcesPrices, $rarities, $taxe, $focus, $location);

if (isset($_GET['noJson'])) {
	echo "<pre>".print_r($craftingProfits, true)."</pre>";
} else {
	print_r(json_encode($craftingProfits));
}
