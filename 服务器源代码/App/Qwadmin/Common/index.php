<?php
	header("Content-type:text/html;charset=utf-8;");
	$dwz=$_SERVER['SERVER_NAME'].str_replace('index.php','',$_SERVER['REQUEST_URI']);//短网址主域名
	$data=file_get_contents('ycyjkj.txt');
	if($data) $data=json_decode($data,true);
	$submit=isset($_POST['submit'])?$_POST['submit']:0;
	$link=isset($_POST['link'])?$_POST['link']:0;
	$url=isset($_POST['url'])?$_POST['url']:0;
	if($submit) {
	if(!$link or !$url) {
	$msg='没有填写主链接或者短网址！';
	}elseif(isset($data[$link])){
	$msg='该短网址已存在，请选择其他短网址！';
	}else{
	$data[$link]=str_replace('http://','',$url);
	$data=json_encode($data);
	$data=file_put_contents('ycyjkj.txt',$data);
	$msg='成功生成短网址：http://'.$dwz.$link;
	}
	}
	$msg=isset($msg)?$msg:'';
	if(isset($_GET['link'])){
	    $url=$data[$_GET['link']];
	if(isset($url)) {
	header("Location: $url");
	}else {
	echo '<meta http-equiv="Refresh" content="5; url=http://www.ycyjkj.com/dwz"/>';
	exit("系统未找到该短网址，请检查您输入是否正常，5秒后返回短网址主站！");
	}
	};
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html>
<head>
<title>易捷博客网址服务 - 网址缩短 - 缩短网址 - 短网址 - 短域名 - 还原短网址</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META NAME="Description" CONTENT="易捷博客为您提供:网址缩短服务,短网址之间的互相转换,以及短网址还原,生成和压缩,缩短目前互联网所有的网址,更快速,更高效!">
<META NAME="Keywords" CONTENT="网址缩短,缩短网址,短网址,短域名,域名缩短,短网址转换">
<link rel="stylesheet" type="text/css" href="css/default.css" />
</head>
<body>
<div id="container">
<div id="header">
<a href="http://<?=$dwz?>" ><h1 style="color:black;">易捷博客短网址</h1></a>
</div>
<div id="content">
<div id="msg"><p><?=$msg?></p></div>
<div id="create_form">
<form method="post">
<p><label for="url">请输入您要缩短的网址(禁止任何违法违规网址,易捷博客不负任何法律责任)：</label></p>
<p><input id="url" type="text" name="url" value="" /></p>
<p class="ali"><label for="alias">自定义(必填)：</label>http://<?=$dwz?>
<input id="alias" maxlength="40" type="text" name="link"> <span>可输入字母、数字、破折号。</span></p>
<p class="but"><input type="submit" value="生 成" class="submit2" name="submit"/></p>
</form>
</div>
</div>
<div id="nav">
<ul>
<li class="current"><a href="index.php">生成短网址</a></li>
</ul>
</div>
<div id="footer">
<p><b>友情链接：
<a href="http://www.ycyjkj.com" target="_blank">易捷博客网</a>
</b></p>
<p><b>&copy; 2016 易捷博客网 - Powered by <a href="http://www.ycyjkj.com/">www.ycyjkj.com</a> </b></p>
</p>
</div>
</div>
</body>
</html>