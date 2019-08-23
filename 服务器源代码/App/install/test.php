<?php
$oldname= $_SERVER['DOCUMENT_ROOT']."/App/Qwadmin/Controller/RwxyController.class.php.empty";
$newname=$_SERVER['DOCUMENT_ROOT']."/App/Qwadmin/Controller/RwxyController.class.php";
if(file_exists($oldname)){
    rename($oldname,$newname);
}

