<?php
require_once 'AipImageClassify.php';

// 你的 APPID AK SK
const APP_ID = '10258017';
const API_KEY = 'hp3EDs6GfDBuG8GB8IgzG5iK';
const SECRET_KEY = 'YQqzjGNR2lo5tCtLoTpNaHFlydOSjKP6 ';

$aipImageClassify = new AipImageClassify(APP_ID, API_KEY, SECRET_KEY);
$client=$aipImageClassify;

// $image = file_get_contents('http://home.upsir.com:81/php/baidu/IMG_20170707_173932.jpg');

// // 调用植物识别
// $r=$aipImageClassify->plantDetect($image);


$image = file_get_contents('http://home.upsir.com:81/php/baidu/IMG_20170612_181905.jpg');

// // 调用车辆识别
// $r=$client->carDetect($image);
// print_r($r);
// 如果有可选参数
$options = array();
$options["top_num"] = 3;

// // 带参数调用车辆识别
$r=$client->carDetect($image, $options);
print_r($r);



print_r($r);
?>