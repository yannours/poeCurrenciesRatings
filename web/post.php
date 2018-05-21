<?php 
echo "test post php".PHP_EOL;
$content = "";
foreach ($_POST as $key => $value)
 $content .= "Field ".htmlspecialchars($key)." is ".htmlspecialchars($value).PHP_EOL;

file_put_contents("log.txt", $content);