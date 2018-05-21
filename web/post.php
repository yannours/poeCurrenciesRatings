<?php 
$content = "test post php".PHP_EOL;
foreach ($_POST as $key => $value) {
 	$content .= "Field ".htmlspecialchars($key)." is ".htmlspecialchars($value).PHP_EOL;
}
foreach ($_GET as $key => $value) {
 	$content .= "Field ".htmlspecialchars($key)." is ".htmlspecialchars($value).PHP_EOL;
}

file_put_contents("log.txt", $content, FILE_APPEND);