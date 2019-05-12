<?php
/**
*
* 版权所有：三思网络<upsir.com>
* 作    者：老黄牛<53053056>
* 日    期：2018
* 版    本：1.0.0
* 功能说明：用户控制器。
*
**/
namespace Qwadmin\Controller;
use Common\Controller\BaseController;
use Think\Controller;
class Test4Controller extends BaseController{
public function index(){
// echo http://gcl.upsir.com/Public/timthumb.php?src=/Public/attached/201903/20190329_221732.jpg&w=200&h=200
$bigimg_path = './Public/attached/201903/20190329_221732.jpg';
$a=basename($bigimg_path) ;
$b=dirname($bigimg_path);
pr($a,'a');
pr($b,'b');
// //小图路径
// // $smallimg_path = $upload->rootPath.$file_info['savepath'].'small_'.$file_info['savename'];

// $img = new \Think\Image(); //实例化
// $img->open($bigimg_path); //打开被处理的图片
// $ff=$img->thumb(200,200); //制作缩略图(100*100)
// // echo $ff;
// $img->save('./afdsfdsfsd.jpg'); //保存缩略图到服务器

// // //把上传好的附件及缩略图存到数据库
// $_POST['goods_big_img']=$bigimg_path;
// $_POST['goods_small_img']=$smallimg_path; 
}


public function pic(){
    // pr('fdsfds');
    $f=R('upload/savesmallimg',array('/temp/111.png'));
pr($f,'f');
// echo $f;

}


public function pic1(){
require_once './ThinkPHP/Library/Think/img_thumb.class.php';
$image = new \ImgLib();

//源图路径
$src_path='./temp/111.jpg';
//把新图片的名称返回浏览器
echo  $image->thumb($src_path,300,300);
}



Public function rot($src="./temp/Public/attached/201904/20190423_153346.jpg"){
pr($src,'src');
if($src){
$image = imagecreatefromstring(file_get_contents($src));
pr($image);
$exif = exif_read_data($src);
if(!empty($exif['Orientation'])) {
switch($exif['Orientation']) {
    case 8:
    $rotate = imagerotate($image,90,0);
    break;
    case 3:
    $rotate = imagerotate($image,180,0);
    break;
    case 6:
    $rotate = imagerotate($image,-90,0);
    break;
    }
}
}    
    
}



// 结尾处
}
