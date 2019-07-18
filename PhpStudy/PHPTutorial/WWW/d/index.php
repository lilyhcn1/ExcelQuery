<?php
	require("conn.php");
	$hash=$_GET['q'];
	if(!$hash){
		//header("Location: http://shawphy.com/");
?>
		<a href="add.php">新建短网址</a><br/><a href="list.php">查看缩短的网址</a>
<?php
		die();
	}
	$q=base_convert($hash,36,10);
	$row=$dbh->query("SELECT url,time,click,preview FROM url where id=$q;")->fetch();
	if(!$row[0]){
		header("Location: ".$config["baseurl"]);
		die();
	}
	if(!preg_match("/\\-$/",$hash)){
		$dbh->exec("update url set click=click+1 where id=$q;");
		$dbh->exec("INSERT INTO click (ip,ua,referer,language,urlid) VALUES ('".$_SERVER['REMOTE_ADDR']."','".$_SERVER['HTTP_USER_AGENT']."','".$_SERVER['HTTP_REFERER']."','".$_SERVER['HTTP_ACCEPT_LANGUAGE']."',$q);");
		header("Location: $row[0]");
	}else{
		$dbh->exec("update url set preview=preview+1 where id=$q;");
		$dbh->exec("INSERT INTO preview (ip,ua,referer,language,urlid) VALUES ('".$_SERVER['REMOTE_ADDR']."','".$_SERVER['HTTP_USER_AGENT']."','".$_SERVER['HTTP_REFERER']."','".$_SERVER['HTTP_ACCEPT_LANGUAGE']."',$q);");
?>
	原始地址：<a href="<?php echo $row[0]; ?>"><?php echo $row[0]; ?></a><br/>
	创建时间：<?php echo $row[1]; ?><br/>
	这个地址被访问了：<?php echo $row[2]; ?><br/>
	这个预览地址被访问了：<?php echo $row[3]+1; ?><br/>
	这是第 <?php echo $q; ?> 个创建的短地址。
<?php
	}
?>