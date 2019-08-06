<?php
echo "1111111111222";
$con = mysqli_connect("localhost","root","lily53053067");

if(!$con)

{

　　die("没有连接成功".mysqli_error());

};
mysqli_query($con,"CREATE DATABASE IF NOT EXISTS rr23 DEFAULT CHARACTER SET utf8;");

mysqli_select_db($con,"jiangxia");

$sql = "create table huangyan

(　　userName vachar(15),

　　userSex vachar(15)

)";

mysqli_query($con,$sql);

mysqli_close($con);

?>