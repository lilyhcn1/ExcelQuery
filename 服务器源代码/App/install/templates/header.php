<!doctype html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title><?php echo $config['name'].' '.$config['version']; ?> - <?php echo $config['powered']; ?></title>
  <!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
  <!--<link rel="stylesheet" href="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap.min.css">  -->
  <!--<script src="http://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js"></script>-->
  <!--<script src="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
		<link rel="stylesheet" href="./templates/css/install.css" />
	</head>
	<body>
		<div class="wrap">
			<div class="header">
				<h1 class="header-title"><?php echo $config['name']; ?></h1>
				<div class="header-install">安装向导</div>
				<div class="header-version">版本：<?php echo $config['version'];?></div>
			</div>
			<div class="step">
				<ul>
					<?php echo $step_html;?>
				</ul>
			</div>