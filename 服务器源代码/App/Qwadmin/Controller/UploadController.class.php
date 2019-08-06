<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：小马哥<hanchuan@qiawei.com>
* 日    期：2015-09-17
* 版    本：1.0.3
* 功能说明：文件上传控制器。
*
**/

// namespace Qwadmin\Controller;
// use Qwadmin\Controller\ComController;
// class UploadController extends ComController{
    
namespace Qwadmin\Controller;
use Common\Controller\BaseController;
use Think\Controller;
class UploadController extends BaseController{    
    public function index($type = null){

    }
    private function saveimg($file){
        $uptypestemp=C(UPTYPES);
        $uptypes=arrtrim(explode(',',$uptypestemp));
        $max_file_size=C('MAXFILESIZE');     //上传文件大小限制, 单位BYTE
        $destination_folder='Public/attached/'.date('Ym').'/'; //上传文件路径
        if($max_file_size < $file["size"]){
            echo "文件太大!";
            return null;
        }
        if(!in_array($file["type"], $uptypes)){
            echo "文件类型不符!".$file["type"];
            return null;
        }
        if(!file_exists($destination_folder)){
            mkdir($destination_folder);
        }
        $filename=$file["tmp_name"];
        $image_size = getimagesize($filename);
        $pinfo=pathinfo($file["name"]);
        $ftype=$pinfo['extension'];
        $destination = $destination_folder.date("Ymd_His").".".$ftype;
        if (file_exists($destination) && $overwrite != true){
            echo "同名文件已经存在了";
            return null;
        }
        if(!move_uploaded_file ($filename, $destination)) {
            return null;
        }
        return "/".$destination;
    }


    public function uploadpic(){
      $Img=I('Img');
      $Path=null;
      if($_FILES['img']){
            $Img=$this->saveimg($_FILES['img']);
      }
      $BackCall=I('BackCall');
      $Width=I('Width');
      $Height=I('Height');
      if(!$BackCall)$Width=$_POST['BackCall'];
      if(!$Width)$Width=$_POST['Width'];
      if(!$Height)$Width=$_POST['Height'];

// 增加一个缩略图
      $orgpic=I('get.pic');
      $smallImg=$this->savesmallimg($Img);  
      $this->assign('smallImg',$smallImg);
      
      $this->assign('Width',$Width);
      $this->assign('BackCall',$BackCall);
      $this->assign('Img',$Img);
      $this->assign('Height',$Height);
      $this->display('Uploadpic');
    }



Public function savesmallimg($bigimg_path){
$bigimg_path ='.'.$bigimg_path;
$filename=basename($bigimg_path) ;
$folder=dirname($bigimg_path);


$tempfolder=str_replace(".","",$folder);


if(empty($width)){
    $width=C('PICWIDTH');}
//小图路径
$smallfolder='./temp'.$tempfolder;
$returnpath= './temp'.$tempfolder.'/'.$filename;


is_dir($smallfolder) OR mkdirs($smallfolder, 0777); 
if(file_exists($returnpath)){
}
else{
    
    $img = new \Think\Image(); 
    $img->open($bigimg_path); //打开被处理的图片
    $ff=$img->thumb($width,$width,\Think\Image::IMAGE_THUMB_CENTER); 
    // $ff=$img->crop($width,$width); //制作缩略图(100*100)

// echo $ff;
    $img->save($returnpath); //保存缩略图到服务器
    unset($img);
    
}





// pr($returnpath);
// addlog($returnpath,'$returnpath');


return $returnpath;
}


Public function rot($scr="./temp/Public/attached/201904/20190423_153346.jpg"){
pr($image);
// if($src){
// $image = imagecreatefromstring(file_get_contents($src));
// pr($image);
// $exif = exif_read_data($src);
// if(!empty($exif['Orientation'])) {
// switch($exif['Orientation']) {
//     case 8:
//     $rotate = imagerotate($image,90,0);
//     break;
//     case 3:
//     $rotate = imagerotate($image,180,0);
//     break;
//     case 6:
//     $rotate = imagerotate($image,-90,0);
//     break;
//     }
// }
// }    
    
}

}
