<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>图片上传</title>
    <style type="text/css">
    html,body{margin:0;padding:0;width:<?php echo ($Width); ?>px;height:<?php echo ($Height); ?>px;}
	.uploadpic{position:relative;}
	#ImgPr{border:0;}
	.puloadpic{position:absolute;width:<?php echo ($Width); ?>px;height:<?php echo ($Height); ?>px;}
	.inputfile{position:absolute;top:0px;left:0px;z-index:100;-moz-opacity:0.3;opacity:0.3;filter: alpha(opacity=30);background:#4f99c6;cursor:pointer;}
	#uploadicon{font-size:200px;overflow:hidden;display:block;width:<?php echo ($Width); ?>px;height:<?php echo ($Height); ?>px;-moz-opacity:0.0;opacity:0.0;filter: alpha(opacity=0);cursor:pointer;}
	.uploadtext{position:absolute;top:0px;left:0px;z-index:50;color:red;font-weight:bold;text-align:center;width:<?php echo ($Width); ?>px;height:<?php echo ($Height); ?>px;line-height:<?php echo ($Height); ?>px;cursor:pointer;overflow:hidden;}
	</style>
    <!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='/Public/qwadmin/js/jquery.js'>"+"<"+"/script>");
		</script>
		<!-- <![endif]-->
		<!--[if IE]>
		<script type="text/javascript">
		 window.jQuery || document.write("<script src='/Public/qwadmin/js/jquery1x.js'>"+"<"+"/script>");
		</script>
		<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='/Public/qwadmin/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
</head>

<body>
    <form enctype="multipart/form-data" id="PostMe" action="?" method="post" name="upform">
        <input type="hidden" value="<?php echo ($Width); ?>" name="Width">
        <input type="hidden" value="<?php echo ($Height); ?>" name="Height">
        <input type="hidden" value="<?php echo ($BackCall); ?>" name="BackCall">
        <div class="uploadpic">
		<img id="ImgPr" src="<?php if($Img == ''): ?>/Public/qwadmin/images/nopic.gif<?php else: ?><?php echo ($Img); endif; ?>"  width="<?php echo ($Width); ?>" height="<?php echo ($Height); ?>">
        <div class="uploadtext">上传</div>
			<div class="inputfile" title="点击上传图片">
				<input type="file" name="img" id="uploadicon" value="upload"/>
			</div>
		</div>
		
    </form>
    <script type="text/javascript">
        $('#uploadicon').on("change",function(){
           $('#PostMe').submit();
		   //alert('d');
        })
       $('#<?php echo ($BackCall); ?>',parent.document).val("<?php echo ($Img); ?>"); 
    </script>
</body>

</html>