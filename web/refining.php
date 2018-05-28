<?php
/**
 * This page calculate all refining price and display all the informations used to calculed it.
 */


require_once("../config.php");
require_once("../resources/pricesHistory.php");
require_once("../resources/pricesCalculation.php");

$rarity = (isset($_GET['rarity']) && $_GET['rarity'] >= 0 && $_GET['rarity'] <= 3) ? $_GET['rarity'] : 0 ;
$focus = isset($_GET['focus']) ? true : false ;
$taxe = isset($_GET['taxe']) ? $_GET['taxe'] : 22;
$location = isset($_GET['location']) ? $_GET['location'] : 3005; // 3005 : Caerleon

$resourcesPrices = getLatestPrices(array_merge(array_keys($resourcesTypes), $resourcesTypes), [3, 4, 5, 6, 7, 8], $rarity, $location);
$refiningCosts = getResourcesRefiningCost($resourcesTypes, [4, 5, 6, 7, 8], $resourcesPrices);
$refiningProfits = getResourcesRefiningProfit($resourcesTypes, [4, 5, 6, 7, 8], $resourcesPrices, $refiningCosts, $taxe, $focus);

$result = isset($_GET['noJson']) ? $refiningProfits : json_encode($refiningProfits);
print_r($result);
