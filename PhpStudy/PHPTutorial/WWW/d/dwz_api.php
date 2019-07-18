<?php
	require("conn.php");
	$url=$_GET["url"];
	if($url){
		if (!preg_match("/^(http|ftp|https):\/\//", $url)) {
			$url = 'http://'.$url;
		} 
		$url=stripslashes($url);
		$row=$dbh->query('SELECT id,url FROM url where url="'.$url.'";')->fetch();
		if(!$row[0]){
		    $sql="INSERT INTO url (url,userid) VALUES ('$url',1);";
			$dbh->exec($sql);
			$ret = $dbh->query("select last_insert_rowid()")->fetch();

		}
		$row=$dbh->query('SELECT id,url FROM url where url="'.$url.'";')->fetch();
		if($row[0]){
		  // echo  $row[1]; 
		  // var_dump($row);
		   
		   $short_url=dirname('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']).'/'.base_convert($row[0],10,36); 
            echo $short_url;
		}else{
		     echo '插入数据错误。';
		}
	}else{
	    echo '请输入url=你要缩短的网址。';
	}
	
?>
