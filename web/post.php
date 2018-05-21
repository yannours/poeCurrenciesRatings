<?php 
$content = "test post php".PHP_EOL;
$content .= print_r($_REQUEST, true);

file_put_contents("log.txt", $content, FILE_APPEND);

print_r(file_get_contents("log.txt"));