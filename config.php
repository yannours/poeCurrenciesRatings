<?php
/**
 * Configuration file
 */


// Constantes
define('API_URL', "https://www.albion-online-data.com/api/v1/stats/prices/");
define('MARKET', "Caerleon Market");
define('RARITY_STRING', "_LEVEL");

// Price must be newer than this date.
define('MIN_PRICE_DATE', date(DATE_ATOM, mktime(date("H")-1)));

$resourcesTypes = [
	"WOOD" => "PLANKS",
 	"ORE" => "METALBAR",
 	"HIDE" => "LEATHER",
 	"FIBER" => "CLOTH",
 	"ROCK" => "STONEBLOCK"
];

