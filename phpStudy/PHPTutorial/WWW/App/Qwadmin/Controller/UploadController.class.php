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

namespace Qwadmin\Controller;
use Qwadmin\Controller\ComController;

class UploadController extends ComController{
    public function index($type = null){

    }
    private function saveimg($file){
        $uptypes=array(
            'image/jpeg',
            'image/jpg',
            'image/jpeg',
            'image/png',
            'image/pjpeg',
            'image/gif',
            'image/bmp',
            'image/x-png'
        );
        $max_file_size=2000000;     //上传文件大小限制, 单位BYTE
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
        $destination = $destination_folder.time().".".$ftype;
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
       $this->assign('Width',$Width);
       $this->assign('BackCall',$BackCall);
       $this->assign('Img',$Img);
       $this->assign('Height',$Height);
       $this->display('Uploadpic');
    }
    public function batchpic(){
       $ImgStr=I('Img');
       $ImgStr=trim($ImgStr,'|');
      $Img=array();
      if(strlen($ImgStr)>1)$Img=explode('|',$ImgStr);
       $Path=null;
       if($_FILES['img']){
          $filename=$this->saveimg($_FILES['img']);
          array_push($Img, $filename);
          $ImgStr=$ImgStr.'|'.$filename;
//          $Img=$this->saveimg($_FILES['img']);
       }
       $BackCall=I('BackCall');
       $Width=I('u');
       $Height=I('Height');
       if(!$BackCall)$Width=$_POST['BackCall'];
       if(!$Width)$Width=$_POST['Width'];
       if(!$Height)$Width=$_POST['Height'];
       $this->assign('Width',$Width);
       $this->assign('BackCall',$BackCall);
       $this->assign('ImgStr',$ImgStr);
       $this->assign('Img',$Img);
       $this->assign('Height',$Height);
       $this->display('Batchpic');
    }
}
