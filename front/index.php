<?php

require_once("src/php/index.php");

if (isset($_REQUEST["debug"])) {
	enableDebug();
}

$dropDownRefined = getDropdown('refined');
$dropDownRessources = getDropdown('ressources');

?>

<html>
<head>
	<title>AR | HOME</title>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="asset/css/index.css">
	<link rel="shortcut icon" href="asset/img/favicon.ico">
</head>
<body>
	<div class="container">
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
          			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          				<span class="sr-only">Toggle navigation</span>
          				<span class="icon-bar"></span>
          				<span class="icon-bar"></span>
          				<span class="icon-bar"></span>
          			</button>
          			<a class="navbar-brand" href="https://pouicou.fr/albionRates">ALBION RATES</a>
          		</div>
				<div id="navbar" class="navbar-collapse collapse">
            		<ul class="nav navbar-nav">
            			<li class="navbar-item active"><a class="content-item" href="#">HOME</a></li>
            			<li class="navbar-item dropdown">
              				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">REFINING<span class="caret"></span></a>
              				<ul class="dropdown-menu">
              					<?= $dropDownRefined ?>
              				</ul>
              			</li>
            			<li class="navbar-item dropdown">
              				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">RESSOURCES<span class="caret"></span></a>
              				<ul class="dropdown-menu">
              					<?= $dropDownRessources ?>
              				</ul>
              			</li>
              			<li class="navbar-item"><a class="content-item" href="#">CONTACT</a></li>
            		</ul>
          		</div>
			</div>
		</nav>
		<div class="row">
			<section class="">
				<div class="panel panel-default">
					<div class="panel-heading">HOME</div>
					<div class="panel-body"><?php require('asset/html/home.html'); ?></div>
				</div>
			</section>
		</div>
	</div>
	<footer>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="asset/js/index.js"></script>
		<?php if (isset($_REQUEST["watch"])) { echo '<script>setFromAjax("'.substr($_REQUEST["watch"], 0, strlen($_REQUEST["watch"])).'");</script>'; } ?>
	</footer>
</body>
</html>