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
$tiers = [4, 5];
foreach ($tiers as $tier) {
	foreach ($rarities as $rarity) {

		$rarityString = '';
		if ($rarity > 0) {
			$rarityString = '@'.$rarity;
		}

		$itemsToProcess[] = 'T'.$tier.'_'.'2H_BOW'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'2H_WARBOW'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'2H_LONGBOW'.$rarityString;

		$itemsToProcess[] = 'T'.$tier.'_'.'HEAD_CLOTH_SET1'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'HEAD_CLOTH_SET2'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'HEAD_CLOTH_SET3'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'ARMOR_CLOTH_SET1'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'ARMOR_CLOTH_SET2'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'ARMOR_CLOTH_SET3'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'SHOES_CLOTH_SET1'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'SHOES_CLOTH_SET2'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'SHOES_CLOTH_SET3'.$rarityString;

		$itemsToProcess[] = 'T'.$tier.'_'.'HEAD_LEATHER_SET1'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'HEAD_LEATHER_SET2'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'HEAD_LEATHER_SET3'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'ARMOR_LEATHER_SET1'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'ARMOR_LEATHER_SET2'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'ARMOR_LEATHER_SET3'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'SHOES_LEATHER_SET1'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'SHOES_LEATHER_SET2'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'SHOES_LEATHER_SET3'.$rarityString;

		$itemsToProcess[] = 'T'.$tier.'_'.'HEAD_PLATE_SET1'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'HEAD_PLATE_SET2'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'HEAD_PLATE_SET3'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'ARMOR_PLATE_SET1'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'ARMOR_PLATE_SET2'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'ARMOR_PLATE_SET3'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'SHOES_PLATE_SET1'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'SHOES_PLATE_SET2'.$rarityString;
		$itemsToProcess[] = 'T'.$tier.'_'.'SHOES_PLATE_SET3'.$rarityString;

	}
}

// Get prices
$minMaxPrices = getMinMaxPrices($itemsToProcess, $days, $location);
$stats = getPricesStats($minMaxPrices);

if (isset($_GET['noJson'])) {
	echo "<pre>".print_r($stats, true)."</pre>";
} else {
	print_r(json_encode($stats));
}