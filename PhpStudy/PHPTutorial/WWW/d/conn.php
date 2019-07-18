<?php
	require("config.php");
	header("Content-type: text/html; charset=utf-8");
	if($config["baseurl"]==""){
		$baseUrl=dirname($_SERVER['PHP_SELF']);
		$baseUrl=($baseUrl=="\\"?"":$baseUrl);
		if (!preg_match("/\/$/", $baseUrl)) {
			$baseUrl = $baseUrl.'/';
		} 

		$config["baseurl"]="http://".$_SERVER['HTTP_HOST'].$baseUrl;
	}
	if(!file_exists($config["database"])){
		echo "你似乎没有创建数据库。点击这里<a href='install.php'>创建数据库</a>";
		die();
	}
	$dbh = new PDO('sqlite:'.$config["database"]);
	if (!$dbh)die();
