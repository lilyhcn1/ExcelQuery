<?php
/**
*
* 版权所有：三思网络<upsir.com>
* 作    者：老黄牛<53053056>
* 日    期：2018
* 版    本：1.0.0
*
**/

namespace Qwadmin\Controller;
use Common\Controller\BaseController;
use Think\Controller;
class Test4Controller extends BaseController{

public function index(){
$url="http://test.r34.cc/index.php/Qwadmin/Ad/addedit/sheetname/%E7%BB%8F%E8%B4%B9%E4%BD%BF%E7%94%A8%E6%B5%8B%E8%AF%95";
pr(shorturl($url));
// $d_urlall=$_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"].'/'.$d_url;
//     $d_url='d/dwz_api.php';
//     $uu=$d_urlall.'?url='.$url;
// pr($uu,'34');

//     if(file_exists($d_url)){
//   pr($uu,'5435');
//         $short_url=geturl($uu);   //这个不能抓有端口的       
// pr($short_url,'r23fes');              
//         if(empty($short_url)){
//             $short_url=$url;
//         }
//     }else{
//         $short_url=$url;
//     }
    
//     return $short_url;
// pr(geturl());
}


   public function havelogin(){
       pr($_SESSION);
      if(session('login')=="yes"){
          echo "已登陆";
      }else{
          echo "no no no ";
      }
       
   }
    
    
}