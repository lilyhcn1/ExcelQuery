<?php
require_once 'AipOcr.php';

// 你的 APPID AK SK
const APP_ID = '10266057';
const API_KEY = 'bQ9RmmBOy0OhfzvTjFWWKbBp';
const SECRET_KEY = 'g9GVxu6dCaMPOpo5EO5v6CBoLy2b82vd ';

$apiOcr = new AipOcr(APP_ID, API_KEY, SECRET_KEY);

$result = $apiOcr->licensePlate('http://ms.upsir.com/ThinkPHP/Library/Vendor/BaiduAI/baiduocr/0.jpg');
print_r($result);