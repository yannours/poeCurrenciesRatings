<?php
/**
 * Landing page of the informations sent by the albion data client.
 * Save the datas to a database
 * More on ADC : https://github.com/broderickhyman/albiondata-client
 */

//$post = file_get_contents('php://input');
$post = file_get_contents('../resources/ADCReturnExample.json');

print_r(json_decode($post));