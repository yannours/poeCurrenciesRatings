<?php

require_once("index.php");

if (isset($_REQUEST["debug"])) {
	enableDebug();
}

function processTableHeader() {
	$html = '
	<thead>
		<td class="col-sm-2 col-xs-12">Icon</td>
		<td class="col-sm-2 col-xs-12">Raw Cost</td>
		<td class="col-sm-2 col-xs-12">Refined Cost</td>
		<td class="col-sm-2 col-xs-12">Selling Price</td>
		<td class="col-sm-2 col-xs-12">Taxes</td>
		<td class="col-sm-2 col-xs-12">Profits</td>
	</thead>';
	return ($html);
}

function processTableBody($get, $index) {
	$html = '';
 	foreach ($get->$index as $tier => $values) {
		$html .= '
		<tr>
			<td class="col-sm-2 col-xs-12"><img class="item-img" src="'.getAlbionIcon($tier, $index).'" /></div>
			<td class="col-sm-2 col-xs-12">'.$values->raw_resource_cost.'</div>
			<td class="col-sm-2 col-xs-12">'.$values->refined_resource_cost.'</div>
			<td class="col-sm-2 col-xs-12">'.$values->selling_price.'</div>
			<td class="col-sm-2 col-xs-12">'.$values->taxe.'</div>
			<td class="col-sm-2 col-xs-12">'.$values->profit.'</div>
		</tr>';
	}
	return ($html);
}

/**
 * @brief  build the panel content for the given page
 * @param  object $cnf contains the configuration object
 * @param  object $get the answer of saltan's api
 * @param  array  $ret the return value of ajax
 * @return nothing
 */
function simplePage($get, $ret, $q) {
	$cnf = getConf();
	$ret["body"] = getHtml("../../asset/html/".strtolower($q).".html");
	echo json_encode($ret);
}

/**
 * @brief  build the panel content for the all albion refined
 * @param  object $cnf contains the configuration object
 * @param  object $get the answer of saltan's api
 * @param  array  $ret the return value of ajax
 * @return nothing
 */
function all($get, $ret, $q) {
	$cnf = getConf();

	$type = strtolower(explode(" ", $q)[1]);
	$ret["body"] = '<div class="row"><table class="table text-center table-hover table-sm">'.processTableHeader().'<tbody>';
	foreach ($cnf->albion->game->$type as $index => $type) {
		if (!isset($get->$type)) { continue; }
		//$ret["body"] .= processTable($type, $get, $cnf);
		$ret["body"] .= processTableBody($get, $type);
	}
	$ret["body"] .= '</tbody></table></div>';
	echo json_encode($ret);
}

/**
 * @brief  build the panel content for a specific albion refined
 * @param  object $cnf contains the configuration object
 * @param  object $get the answer of saltan's api
 * @param  array  $ret the return value of ajax
 * @param  string $q   the specific refined
 * @return nothing
 */
function type($get, $ret, $q) {
	$cnf = getConf();

	if (!isset($get->$q)) {
		echo json_encode($ret);
		return;
	}
	//$ret["body"] = processTable($q, $get, $cnf);
	$ret["body"] = '<div class="row"><table class="table text-center table-hover table-sm">'.processTableHeader();
	$ret["body"] .= '<tbody>'.processTableBody($get, $q).'</tbody></table>';
	echo json_encode($ret);
}

/**
 * @brief  setup the fcts tab
 * @return array function tab
 */
function setup() {
	$fcts = [];
	$cnf = getConf();

	//use it like a router express
	//create a nav entry, then add a route for it here as it follow
	//$fcts[ROUTE] = 'simplePage';
	//then create the view in asset/html/route.html
	$fcts['HOME'] = 'simplePage';
	$fcts['CONTACT'] = 'simplePage';
	$fcts['ALL REFINED'] = 'all';
	$fcts['ALL RESSOURCES'] = 'all';
	foreach ($cnf->albion->game as $name => $array) {
		foreach ($array as $key => $value) {
			$fcts[$value] = 'type';
		}
	}
	return $fcts;
}

/**
 * @brief  running process for ajaxCall
 * @param  string $q the query
 * @return nothing
 */
function run($q) {
	$cnf = getConf();
	$fcts = setup();
	$ret = ["header" => $q, "body" => ""];
	$get = json_decode(file_get_contents($cnf->albion->meta->saltanAPI->base.$cnf->albion->meta->saltanAPI->refined));

	if (isset($fcts[$q])) {
		$fcts[$q]($get, $ret, $q);
	}
}

if (isset($_REQUEST["q"])) {
	run($_REQUEST["q"]);
}

?>