<?php
/**
 * Landing page of the informations sent by the albion data client.
 * Save the datas to a database
 * More on ADC : https://github.com/broderickhyman/albiondata-client
 */

$post = file_get_contents('php://input');

file_put_contents('post.txt', 'post : '.$post, FILE_APPEND);