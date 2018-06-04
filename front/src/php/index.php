<?php

/**
 * @brief enable php debug
 */
function enableDebug() {
	error_reporting(-1);
	ini_set('display_errors', 'On');
}

/**
 * @brief return html page content in var
 * @param  string $page the asset to return
 * @return string       html block
 */
function getHtml($page) {
	ob_start();
	require($page);
	return ob_get_clean();
}

/**
 * @brief return the conf as a php object
 * @return php object from json
 */
function getConf() {
	return (json_decode(file_get_contents("https://pouicou.fr/albionRates/conf/index.json")));
}

/**
 * @brief  return the dropdown of data for the target
 * @param  string $target name of the conf array to list in the dropdown
 * @return html block
 */
function getDropdown($target) {
	$cnf = getConf();

	$html = '<li class="navbar-dropdown-item"><a class="content-item" href="#">ALL '.strtoupper($target).'</a></li>';
	foreach ($cnf->albion->game->$target as $key => $value) {
		$html .= '<li class="navbar-dropdown-item"><a class="content-item" href="#">'.$value.'</a></li>';
	}
	return ($html);
}

/**
 * @brief return the url for a given tier type albion item
 * @param  string $tier tier of albion item (should be [2...8] etc...)
 * @param  string $type item type (shoud be PLANKS, ORE etc...)
 * @return string       url
 */
function getAlbionIcon($tier, $type) {
	$cnf = getConf();

	return $cnf->albion->meta->icons->base.$tier.'_'.$type.$cnf->albion->meta->icons->ext;
}

?>