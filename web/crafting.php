<?php

require_once("../config.php");
require_once("../resources/pricesHistory.php");
require_once("../resources/pricesCalculation.php");
require_once("../resources/recipes.php");

$rarity = (isset($_GET['rarity']) && $_GET['rarity'] >= 0 && $_GET['rarity'] <= 3) ? $_GET['rarity'] : 0 ;
$focus = isset($_GET['focus']) ? true : false ;
$taxe = isset($_GET['taxe']) ? $_GET['taxe'] : 22;
$location = isset($_GET['location']) ? $_GET['location'] : 3005; // 3005 : Caerleon

$resourcesPrices = getLatestPrices($resourcesTypes, [3, 4, 5, 6, 7, 8], $rarity, $location);
$craftingProfits = getCraftingProfit($recipes, [4, 5, 6, 7, 8], $resourcesPrices, $rarity, $taxe, $focus, $location);

print_r($craftingProfits);
