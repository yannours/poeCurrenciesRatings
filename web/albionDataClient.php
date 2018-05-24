<?php
/**
 * Landing page of the informations sent by the albion data client.
 * Save the datas to a database
 * More on ADC : https://github.com/broderickhyman/albiondata-client
 * Site inspired by http://albionassistant.com/calculator.aspx
 */

//$post = file_get_contents('php://input');
$post = file_get_contents('../resources/ADCReturnExample.json');
$json = json_decode($post);

// Decode and save orders
savePricesToDB($json['Orders']);

$locationsCode = [
	-1 => Unknown,
	0 => ThetfordMarket,
	1000 => LymhurstMarket,
	2000 => BridgewatchMarket,
	3003 => BlackMarket,
	3004 => MartlockMarket,
	3005 => CaerleonMarket,
	4000 => FortSterlingMarket,

	4 => SwampCrossMarket,
	1006 => ForestCrossMarket,
	2002 => SteppeCrossMarket,
	3002 => HighlandCrossMarket,
	4006 => MountainCrossMarket,
];