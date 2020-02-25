<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2016-01-20
* 版    本：1.0.0
* 功能说明：用户控制器。
*
**/
 
namespace Qwadmin\Controller;
use Think\Controller;
class LilyregController extends Controller{
public function index(){
$url=U('Lilyreg/easy'); 
        // $url=U('Lilynoticeview/index');
header("Location: $url");

    
}




    public function zhbd($wx_openid=''){
   $wx_openid=I('get.wx_openid');
//   addlog('zhbd.$wx_openid11111-- '.$wx_openid);
    if(!empty($wx_openid)){
        $con['wx_openid']=array('like',"%".$wx_openid."%");
        $exsitwx=M('member')->where($con)->find();    
// pr($exsitwx);
    }
    // $data=$wx_openid;
    if(isset($exsitwx)){
        $title='已绑定微信';
        $content='请关闭本页面。';
      echo h5page($title,$content);
    }else{
        $this->assign('wx_openid',$wx_openid); 
       	$this -> display();        
    }
// pr('$data2');
// pr($data);    

    }
    public function zhbdupdate(){
    $data=I('post.');
// pr('$data3');
// pr($data);    
     // addlog('$data'.json_encode($data));
    $con['user']=$data['user'];
    $con['password']=password($data['password']);
    $userarr=M('member')->where($con)->find();
    if($userarr && !empty($data['wx_openid'])){
       $userarr['wx_openid']= $userarr['wx_openid'].$data['wx_openid'].';';
       M('member')->data($userarr)->save();
       
    //   addlog('fdfdfdf$userarr'.json_encode($userarr));
    //   $this->success('绑定成功');
    //   $title='绑定成功';
    //   $content='请关闭本页面。';
    //   echo h5page($title,$content);
       $this -> success('绑定成功',U("index/index"),5);
       R('Login/wxlogin',array($userarr['wx_openid']));
      

        // $msgarr['remark']='欢迎关注三思网络，您已注册并绑定。';
        // $msgarr['first']='具体功能请见网页介绍';
        // $msgarr['keyword3']='三思网络';
        // R('Task/SendTplMsg',array($msgarr,$userarr));      
      
    }else{

      $this->error('用户名或密码错误。'); 
    }

}


    
    //结尾处
}