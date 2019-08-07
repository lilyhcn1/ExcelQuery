
<html >
<head>

<title>无标题文档</title>
</head>

<body>

<form action="file2.php" method="post">
<input type="text" name="ok">
<input type="submit" name="mySubmit" value="添加">
<input type="submit" name="mySubmit" value="修改">
<input type="submit" name="mySubmit" value="删除">
</form>
 

<?php
$action = $HTTP_POST_VARS["mySubmit"];
switch ($action) {
    case "添加":
        echo 'this is '.$action;
        break;
    case "修改":
        echo 'this is '.$action;
        break;
    case "删除":
         echo 'this is '.$action;
        break;
}
?>


</body>
</html>
