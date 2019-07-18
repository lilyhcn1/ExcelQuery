<?php
	require("config.php");
	header("Content-type: text/html; charset=utf-8");
	if(file_exists($config["database"])){
		//echo $config["database"]." 文件已经存在，请手动删除后重建。";
		//die();
	}
	$dbh = new PDO('sqlite:'.$config["database"]);
	$dbh->exec('drop table url;drop table log;drop table preview;drop table user;');

	$dbh->exec('CREATE TABLE "url" ("id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE , "url" TEXT NOT NULL , "click" INTEGER NOT NULL  DEFAULT 0,"preview" INTEGER NOT NULL  DEFAULT 0, "time" DATETIME NOT NULL  DEFAULT CURRENT_TIMESTAMP, "userid" INTEGER NOT NULL );');

	$dbh->exec('CREATE TABLE "click" ("id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE , "ip" TEXT, "ua" TEXT, "referer" TEXT, "language" TEXT, "time" DATETIME DEFAULT CURRENT_TIMESTAMP, "urlid" INTEGER);');

	$dbh->exec('CREATE TABLE "preview" ("id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE , "ip" TEXT, "ua" TEXT, "referer" TEXT, "language" TEXT, "time" DATETIME DEFAULT CURRENT_TIMESTAMP, "urlid" INTEGER);');

	$dbh->exec('INSERT INTO url (url,userid) VALUES ("http://shawphy.com/",1);');
	echo '创建成功 <a href="./">返回首页</a>';
?>