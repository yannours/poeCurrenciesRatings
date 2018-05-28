<?php
/**
 * Landing page of the informations sent by the albion data client.
 * Save the datas to a database
 * In order to redirect the ADC to this page, launch it with -i="websiteadresse/albionDataClient.php"
 */

require_once("../resources/pricesHistory.php");

$post = file_get_contents('php://input');
$json = json_decode($post, true);

// Decode and save orders
savePricesToDB($json['Orders']);
