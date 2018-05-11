<?php

define("BASECURRENCY", 4); // Chaos
define("LEAGUE", "Bestiary"); // Chaos

$username = "saltan";
$currencies = [
  ['key' => '5', 'name' => 'Gcp'],
  ['key' => '10', 'name' => 'Chisel'],
  ['key' => '1', 'name' => 'Alt'],
  ['key' => '9', 'name' => 'Chance'],
  ['key' => '3', 'name' => 'Alch'],
  ['key' => '8', 'name' => 'Jew'],
  ['key' => '2', 'name' => 'Fus'],
  ['key' => '7', 'name' => 'Chroma'],
  ['key' => '11', 'name' => 'Scour'],
  ['key' => '13', 'name' => 'Regret'],
  ['key' => '16', 'name' => 'Vaal'],
  ['key' => '45', 'name' => 'White sext'],
  ['key' => '46', 'name' => 'Yellow sext'],
  ['key' => '47', 'name' => 'Red sext'],
];

$allOffers = [];

foreach($currencies as $currency) {
	$allOffers['sell'][$currency['key']] = ['currency' => $currency, 'offers' => getOffers($currency, 'sell')];
	$allOffers['buy'][$currency['key']] = ['currency' => $currency, 'offers' => getOffers($currency, 'buy')];
}

generateHtml($allOffers, $username);

function getOffers ($currency, $offerType) {

	$offers = [];

	if ($offerType === 'sell') {
		$want = $currency['key'];
		$have = BASECURRENCY;
	} else {
		$want = BASECURRENCY;
		$have = $currency['key'];
	}

	$html = file_get_contents('http://currency.poe.trade/search?league='.LEAGUE.'&online=x&want='.$want.'&have='.$have);

	$doc = new DOMDocument;
	$doc->loadHTML($html);

	$xpath = new DOMXpath($doc);
	$rows = $xpath->query('//div[@class = "displayoffer "]');
	foreach ($rows as $row) {
		$offers[] = [
			'seller' => $row->getAttribute('data-username'),
			'price' => number_format($row->getAttribute('data-buyvalue')/$row->getAttribute('data-sellvalue'),3),
			'offer' => number_format($row->getAttribute('data-buyvalue'),1).'/'.number_format($row->getAttribute('data-sellvalue'),1)
		];
	}

	return $offers;
}


function generateHtml ($allOffers, $username) {
	$string = '<h1>POE Currencies Ratings</h1>'.PHP_EOL.PHP_EOL;

	foreach ($allOffers as $offerType => $currenciesOffers) {

		$string .= '<h2>'.$offerType.'</h2>'.PHP_EOL;

		$string .= '<table>'.PHP_EOL;

		$string .= '<tr><th></th>'.PHP_EOL;
		foreach ($currenciesOffers as $currencyOffers) {
			$string .= '<th>'.$currencyOffers['currency']['name'].'</th>';
		}
		$string .= '</tr>'.PHP_EOL;

		$string .= '<tr><td>4eme offre</td>'.PHP_EOL;
		foreach ($currenciesOffers as $currencyOffers) {
			$string .= '<td><b>';
			$string .= $currencyOffers['offers'][3]['price'].'</b><br/>'.$currencyOffers['offers'][3]['offer'];
			$string .= '</td>';
		}
		$string .= '<tr>'.PHP_EOL;

		$string .= '</tr><td>5eme offre</td>'.PHP_EOL;
		foreach ($currenciesOffers as $currencyOffers) {
			$string .= '<td><b>';
			$string .= $currencyOffers['offers'][4]['price'].'</b><br/>'.$currencyOffers['offers'][4]['offer'];
			$string .= '</td>';
		}
		$string .= '</tr>'.PHP_EOL;

		$string .= '</tr><td>'.$username.'</td>'.PHP_EOL;
		foreach ($currenciesOffers as $currencyOffers) {
			$string .= '<td><b>';
			foreach ($currencyOffers['offers'] as $offer) {
				if ($offer['seller'] == $username) {
					$string .= $offer['price'].'</b><br/>'. $offer['offer'];
				}
			}
			$string .= '</td>';
		}
		$string .= '</tr>'.PHP_EOL;

		$string .= '</table>'.PHP_EOL;
	}


	// Add style
	$string = "
		<style type=\"text/css\">
			table {
			 border-collapse:collapse;
			 width:90%;
			 }
			th, td {
			 border:1px solid black;
			 }
			td {
			 text-align:center;
			 }
			caption {
			 font-weight:bold
			 }
		</style>
	".$string;
	print_r($string);
}

