<?php 

$html = file_get_contents('https://www.albion-online-data.com/api/v1/stats/prices/T5_ORE');
var_dump(json_decode($html));