<?php

define("MAXPOSITION", 10);

$baseCurrency = 4; // Chaos
$league = 'Bestiary';
$currencies = [
	'1' => 'Alt',
	'2' => 'Fus',
	'3' => 'Alch',
	'5' => 'Gcp',
	'7' => 'Chroma',
	'8' => 'Jew',
	'9' => 'Chance',
	'10' => 'Chisel',
	'11' => 'Scour',
	'13' => 'Regret',
	'16' => 'Vaal',
	'45' => 'White sext',
	'46' => 'Yellow sext',
	'47' => 'Red sext',
];

$allOffers = [
	'buy' => [],
	'sell' => []
];

foreach($currencies as $currencyKey => $currency) {
	$allOffers['sell'][$currency] = getOffer($currencyKey, $baseCurrency);
	$allOffers['buy'][$currency] = getOffer($baseCurrency, $currencyKey);
}

generateExcel($allOffers);

function getOffer ($want, $have, $maxPosition = MAXPOSITION) {

	$html = file_get_contents('http://currency.poe.trade/search?league=Bestiary&online=x&want='.$want.'&have='.$have);

	$doc = new DOMDocument;
	$doc->loadHTML($html);

	$xpath = new DOMXpath($doc);
	$rows = $xpath->query('//div[@class = "displayoffer " and position() <= '.$maxPosition.']');
	$allOffers = [];
	foreach ($rows as $row) {
		// var_dump($row);
		$allOffers[] = [
			'seller' => $row->getAttribute('data-username'),
			'price' => number_format($row->getAttribute('data-buyvalue')/$row->getAttribute('data-sellvalue'),3),
			'offer' => number_format($row->getAttribute('data-buyvalue'),1).'/'.number_format($row->getAttribute('data-sellvalue'),1)
		];
	}

	return $allOffers;
}


function generateExcel ($allOffers) {
	$string = '';

	foreach ($allOffers as $offerType => $offers) {
		$string .= PHP_EOL.$offerType.PHP_EOL;

		foreach ($offers as $currency => $currencyOffers) {
			$string .= $currency.';;';
			foreach ($currencyOffers as $offer) {
				$string .= $offer['price'].' - '.$offer['offer'].';';
			}
			$string .= PHP_EOL;
		}
	}


	print_r($string);
}

