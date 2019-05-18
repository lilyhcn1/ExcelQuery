<?php

class ImgLib{

    private $error;
    public function getError(){

        return $this->error;
    }

/**
*
* 制作缩略图
* @param $src_path string 原图路径
* @param $max_w int 画布的宽度
* @param $max_h int 画布的高度
* @param $flag bool 是否是等比缩略图  默认为false
* @param $prefix string 缩略图的前缀  默认为'sm_'
*
*/
    public function thumb($src_path,$max_w,$max_h,$prefix = 'sm_',$flag = true){

        //获取文件的后缀
        $ext=  strtolower(strrchr($src_path,'.')); 

        //判断文件格式
        switch($ext){
            case '.jpg':
                $type='jpeg';
                break;
            case '.gif':
                $type='gif';
                break;
            case '.png':
                $type='png';
                break;
            default:
                $this->error='文件格式不正确';
                return false;
        }


        //拼接打开图片的函数
        $open_fn = 'imagecreatefrom'.$type;
        //打开源图
        $src = $open_fn($src_path);
        //创建目标图
        $dst = imagecreatetruecolor($max_w,$max_h);

        //源图的宽
        $src_w = imagesx($src);
        //源图的高
        $src_h = imagesy($src);

        //是否等比缩放
        if ($flag) { //等比
            
            //求目标图片的宽高
            if ($max_w/$max_h < $src_w/$src_h) {

                //横屏图片以宽为标准
                $dst_w = $max_w;
                $dst_h = $max_w * $src_h/$src_w;
            }else{

                //竖屏图片以高为标准
                $dst_h = $max_h;   
                $dst_w = $max_h * $src_w/$src_h;
            }
            //在目标图上显示的位置
            $dst_x=(int)(($max_w-$dst_w)/2);
            $dst_y=(int)(($max_h-$dst_h)/2);
        }else{    //不等比

            $dst_x=0;
            $dst_y=0;
            $dst_w=$max_w;
            $dst_h=$max_h;
        }

        //生成缩略图
        imagecopyresampled($dst,$src,$dst_x,$dst_y,0,0,$dst_w,$dst_h,$src_w,$src_h);

        //文件名
        $filename = basename($src_path);
        //文件夹名
        
        $foldername=substr(dirname($src_path),0);
        $tempfolder=str_replace(".","",$foldername);
        //缩略图存放路径
        $smallfolder="./temp".$tempfolder;
        is_dir($smallfolder) OR mkdirs($smallfolder, 0777); 
        $thumb_path = $smallfolder.'/'.$filename;

        //把缩略图上传到指定的文件夹
        imagepng($dst,$thumb_path);
        //销毁图片资源
        imagedestroy($dst);
        imagedestroy($src);

        //返回新的缩略图的文件名
        return $thumb_path.$prefix.$filename;
    }

}

 ?>