<?php
	require("conn.php");
	$url=$_GET["url"];
	if($url){
		$url=stripslashes($url);
		$row=$dbh->query('SELECT id,url FROM url where url="'.$url.'";')->fetch();
		if($row[0]){
			echo "prompt('".addslashes($row[1])."\\n已经存在：','".$config["baseurl"].base_convert($row[0],10,36)."');";
			die();
		}
		$sql="INSERT INTO url (url,userid) VALUES ('$url',1);";
		$dbh->exec($sql);
		$ret = $dbh->query("select last_insert_rowid()")->fetch();

		echo "prompt('".addslashes($url)."\\n的短网址是：','".$config["baseurl"].base_convert($ret[0],10,36)."');";
		die();
	}
	header("Location: add.php");
?>