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
//   addlog('zhbd.$wx_openid11111'.$wx_openid);
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
        $this->assign('data',$wx_openid); 
       	$this -> display();        
    }
// pr('$data2');
// pr($data);    

    }
    public function zhbdupdate(){
    $data=I('post.');
// pr('$data3');
// pr($data);    
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




    public function index3(){
        $data=I('get.');
// 读取openid
        $code=$data['code'];
        $wx_openid=$_SESSION["wx_openid"];
// addlog('lilyreg.code'.$code);        
        if(empty($wx_openid)){
            if($code){
                $wx_openid=R('Lilyweixin/weixincodegetopenid',array($code));
            }
        }

        Session_Start();
        if(empty($_SESSION["wx_openid"])){
            $_SESSION["wx_openid"]=$wx_openid;
        }



        
        if(empty($_SESSION["smscode"])){
            $_SESSION["smscode"]=rand(1000,9999);
        }

// addlog('lilyreg.$_SESSION["smscode"]4'.$_SESSION["smscode"]);
// addlog('lilyreg.wx_openid4'.$wx_openid);
    $this->assign('wx_openid',$wx_openid); 
   	// $this -> display();

        
        $db2 = M('classleader');   
       //一级分类就不用联动了，直接实例化表 输出模板就可以。
        $query=$db2->distinct(true)->field('school')->select();
        $this->assign('data',$query);
        
        // pr($query);
        $this->display();           
        
    }    
    public function smssendbak(){
        $data=I('post.');
        $data['code']=$_SESSION["smscode"];
        
        $con['mphone']=$data['phone'];
        $db = M('teacher','think_','mysql://'.C('DB_USER').':'.C('DB_PWD').'@localhost/thinkphp2#utf8');
        $userarr=$db->where($con)->find();
    
// addlog('msgsend:'.json_encode($data));
// addlog('msgsend:'.json_encode($userarr));
        if($userarr){
// addlog('msgsend:'.json_encode($data));
            send_dayusms($data['phone'],$data['code']);
        }else{   
            $this -> error('暂时禁止学生注册',U('Lilyreg'));
        }
        // addlog('21212'.json_encode($data));
    // send_dayusms();
        
        
    }
    public function sms139send(){
        $data=I('post.');
        
        $data['code']=$_SESSION["smscode"];
// addlog('$data[\'code\']'.$data['code']);

// 用139邮箱进行短信验证
        // $address[1]=GetPhoneEmail($data['phone']);
        // $subject='您注册通知管理系统的';
        // $content='验证码:['.$data['code'].']';
        // send_email($address,$subject,$content);

// 用阿里大于进行短信验证 
    send_dayusms($data['phone'],$data['code']);    

    }    
// 页面注册
public function reg(){
        $data=I('post.');

//  addlog('lilyreg.reg'.json_encode($data)); 
 
   $smscode=I('post.smscode','','strip_tags');
// addlog('$smscode'.$smscode);        
   if($smscode != $_SESSION["smscode"]){
      $title='短信验证码不正确';
      $content='';
      echo h5page($title,$content);
      $this -> error('请返回重填。');
   }else{
       

       

 //先指定数据库
 $data['user'] = I('post.user','','strip_tags');
 $password = isset($_POST['password'])?trim($_POST['password']):false;
 $data['password'] = password($password);
 $data['nickname'] = I('post.nickname','','strip_tags');
 $data['wx_id'] = I('post.wx_id','','strip_tags');
 $data['phone'] = I('post.phone','','strip_tags');
 $data['qq'] = I('post.qq','','strip_tags');
 $data['email'] = I('post.email','','strip_tags');
 $data['wx_openid'] = I('post.wx_openid','','strip_tags').';'; 
 $data['school'] = I('post.classa','','strip_tags'); 
 $data['department'] = I('post.classb','','strip_tags'); 
 $data['stu_class'] = I('post.classc','','strip_tags'); 
 //   这里应该要修改指定了部门等
        $con['mphone']=$data['phone'];
        $db = M('teacher','think_','mysql://'.C('DB_USER').':'.C('DB_PWD').'@localhost/thinkphp2#utf8');
        $userarr=$db->where($con)->find();   
        if($userarr){
            $data['department'] = $userarr['department'];
            $data['stu_class'] = '教师';    
            $data['group_id']=8;
        }else{
            $data['group_id']=6;
        }        


    $group_id=$data['group_id'];
    $data['smscode'] = $smscode;
    $condition['user']= $data['user'];


 


// pr($data);
echo $uid=M('member')->where($condition)->find();
    if($uid){
	    $this -> error('用户已存在。人文学生的用户名为学号，密码也为学号。',U('Lilyreg/index'),15);
	}else{
	    
		$uid=M('member')->data($data)->add();
		M('auth_group_access')->data(array('group_id'=>$group_id,'uid'=>$uid))->add();
        addlog('新增UID'.$uid.json_encode($data));

        if(empty( $data['wx_openid']))	{
            $title='已成功注册';
            $content='请关闭本页面。';
            // ，并进行微信帐号绑定。
        }else{
             $title='请关闭本页面。';
            $content='已成功注册并进行了微信绑定。无需再进行微信绑定<br>
            您应该会收到三思网络发来的一条欢迎信息。';
            // ，并进行微信帐号绑定。   
        }	
        echo h5page($title,$content);       


            
        // $user不写的话发给2013014
        // $msgarr不是数组的话，随便写了
        $con5['uid']=$uid;
        $userarr=M('member')->where($con5)->find();
        $msgarr['remark']='欢迎关注三思网络，您已注册并绑定。';
        $msgarr['first']='具体功能请见网页介绍';
        $msgarr['keyword3']='三思网络';
        R('Task/SendTplMsg',array($msgarr,$userarr));
		}

        
    //   $this -> success('注册成功',U('Lilyreg/regsuccess'),1);
        
    }
}
// 比赛报名页面
public function enroll(){
$time=30*60;
session_set_cookie_params($time);    
    
    $data=I('get.');
// addlog('lilyreg.enrooll'.json_encode($data));

//  通知消息的读取
    $id=$data['id'];
    if(isset($id)){
        $con['id']=$id;
        $noticearr=M('notice')->where($con)->find();
    }
    
    if(empty($noticearr)){
        $id=1;
        $con['id']=$id;
        $noticearr=M('notice')->where($con)->find();
    }
    // pr($noticearr);
// 必须要有没有消息
    if(empty($noticearr)){
        $this->error('请输入正确的消息id',U('index/index'));
    }else{
 // 读取openid,并读取对应的人 // 读取openid,并读取对应的人
        $wx_openid=$_SESSION["wx_openid"];
        if(empty($_SESSION["smscode"])){
            $_SESSION["smscode"]=rand(1000,9999);
        }
        $smscode=$_SESSION["smscode"];
    // 没openid再看看有没有code
 
        if(!empty($wx_openid)){
            
        }else{
            $code=$data['code'];
      
            if($code){
                $wx_openid=R('Lilyweixin/weixincodegetopenid',array($code));
            }        
        }
        if(!empty($wx_openid)){
            $conopenid['wx_openid']=array('like','%'.$wx_openid.';%');
            $userarr=M('member')->where($conopenid)->find();            
        }        
  
    // 是不是存在对应的人       
            if(empty($userarr)){
                Session_Start();
                if(empty($_SESSION["wx_openid"])){
                    $_SESSION["wx_openid"]=$wx_openid;
                }         
            }else{


//                 R('Login/Login',array($dataarr));
             R('Login/loginidentifier',array($userarr['uid']));    
            }
            if(empty($wx_openid)){
                $wx_openid='';
            }
 // 读取openid,并读取对应的人 // 读取openid,并读取对应的人    
    
    
        
    
            $title=$noticearr['title'];
            $content=$noticearr['content'];
            $content=R('Reply/returnmsg',array($content,'web'));
             $this->assign("id",$id);  
            $this->assign("title",$title);
            $this->assign("content",$content);  
            $this->assign("smscode",$smscode);  
            $this->assign("wx_openid",$wx_openid);  
            
            $this->display();
       
        
    }

    
     
}


// 比赛报名页面的处理
public function enrollupdate(){
        $data=I('post.');
$notice_id=$data['notice_id'];
//   addlog('lilyreg.enrollupdate.1.data'.json_encode($data)); 
         
   $smscode=I('post.smscode','','strip_tags');
// addlog('$smscode'.$smscode);        
   if($smscode != $_SESSION["smscode"]){
      $title='短信验证码不正确';
      $content='';
      echo h5page($title,$content);
      $this -> error('请返回重填。');
   }else{
       

       

 //先指定数据库
 $data['user'] = I('post.phone','','strip_tags');
 $password = $smscode;
 $data['password'] = password($password);
 $data['nickname'] = I('post.nickname','','strip_tags');
 $data['phone'] = I('post.phone','','strip_tags');
 $data['wx_openid'] = I('post.wx_openid','','strip_tags').';'; 
 $data['school'] = '台州学院'; 
 $data['department'] = '学院'; 
 //   这里应该要修改指定了部门等
    $group_id=6;
    $data['group_id']=$group_id;
    
    $condition['user']= $data['user'];


 


// pr($data);
$uid=M('member')->where($condition)->find();

    if($uid){
	    $this -> error("用户".$condition['user']."已存在。",U('Lilyreg/index'),15);
	}else{
	    
		$uid=M('member')->data($data)->add();
		M('auth_group_access')->data(array('group_id'=>$group_id,'uid'=>$uid))->add();
        addlog('新增UID'.$uid.json_encode($data));

        if(empty( $data['wx_openid']))	{
            $title='已成功注册';
            $content='请关闭本页面。';
            // ，并进行微信帐号绑定。
        }else{
             $title='请关闭本页面。';
            $content='已成功注册并进行了微信绑定。无需再进行微信绑定<br>
            您应该会收到三思网络发来的一条欢迎信息及一条报名成功的信息。<br>
            ';
            // ，并进行微信帐号绑定。

            R('Login/loginidentifier',array($uid));
            
          
        }	
        
        echo h5page($title,$content);       


            
        // $user不写的话发给2013014
        // $msgarr不是数组的话，随便写了
        $con5['uid']=$uid;
        $userarr=M('member')->where($con5)->find();
        // $msgarr['remark']='欢迎关注三思网络，您已注册并绑定。';
        // $msgarr['first']='具体功能请见网页介绍';
        // $msgarr['keyword3']='三思网络';
        // R('Task/SendTplMsg',array($msgarr,$userarr));
		
    $msgarr['first']='欢迎关注三思网络，您已注册并绑定。';
    $msgarr['keyword1']='台州学院';
    $msgarr['keyword2']='台院墙';
    $msgarr['keyword3']=date("Y-m-d H:i:s",time());
    $msgarr['keyword4']="";
    $msgarr['remark']="你已成功在本站的注册。\n".
                        "用户名为: ".$data['phone']."\n".
                        "密码为随机生成: ".$data['smscode']."\n".
                        "一般微信访问无需密码。";

        R('Task/SendTplMsg2',array($msgarr,$userarr));	    
	    
	    
	}


if($notice_id!=1){

//    写入noticeread
$readdata['notice_id']=$notice_id;
$readdata['time']=time();
$readdata['reader']=$userarr['user'];
$readdata['IP'] =get_client_ip();
$readdata['replyopt'] ='是';
$readdata['replycontent'] ='报名';

    $newid=R('task/addnoticeread',array($readdata));
    // if(isset($newid)){
    //   $this->success('恭喜，操作成功！',$_SERVER['HTTP_REFERER']); 
    // }



        $con5['id']=$notice_id;
        $noticearr=M('notice')->where($con5)->find();
     $msgarrnew['first']=$noticearr['title'];
    $msgarrnew['keyword1']='台州学院';
    $msgarrnew['keyword2']='台院墙';
    $msgarrnew['keyword3']=date("Y-m-d H:i:s",time());
    $msgarrnew['keyword4']='';
    $msgarrnew['remark']="您已成功报名'".$noticearr['title']."'。\n".
                        "姓名为：".$data['nickname']."\n".
                        "手机为：".$data['phone']."\n";

        R('Task/SendTplMsg2',array($msgarrnew,$userarr));    
}


     

        
    //   $this -> success('注册成功',U('Lilyreg/regsuccess'),1);
        
    }    
    
}





// 页面注册
public function easy(){
        $data=I('get.');
// 读取openid,并读取对应的人
        $code=$data['code'];
        if($code){
            $wx_openid=R('Lilyweixin/weixincodegetopenid',array($code));
            if(isset($wx_openid)){
                $conopenid['wx_openid']=array('like','%'.$openid.';%');
                $userarr=M('member')->where($conopenid)->find();            
            }
        }
// 是不是存在对应的人       
        if(empty($userarr)){
            $r['wx_openid']=$_SESSION["wx_openid"];
            Session_Start();
            if(empty($_SESSION["wx_openid"])){
                $_SESSION["wx_openid"]=$wx_openid;
            }         
        }else{
            $this -> success('你已经注册过本站',$_SERVER['HTTP_REFERER']); 
        }
    

        

// addlog('$_SESSION["smscode"]'.$_SESSION["smscode"]);
    $this->assign('data2',$r); 

        $this->display();           
         
}

public function easyreg(){
        $data=I('post.');

   $smscode=I('post.smscode','','strip_tags');

// // 短信验证码强制相等
//  $smscode=$_SESSION["smscode"];
 
   if($smscode != $_SESSION["smscode"]){
      $title='短信验证码不正确';
      $content='';
      echo h5page($title,$content);
      $this -> error('请返回重填。');
   }else{
       

       

 //先指定数据库
 $data['user'] = I('post.user','','strip_tags');
 $password = isset($_POST['password'])?trim($_POST['password']):false;
 $data['password'] = password($password);
 $data['nickname'] = I('post.nickname','','strip_tags');
 $data['wx_id'] = I('post.wx_id','','strip_tags');
 $data['phone'] = I('post.phone','','strip_tags');
 $data['qq'] = I('post.qq','','strip_tags');
 $data['email'] = I('post.email','','strip_tags');
 $data['wx_openid'] = I('post.wx_openid','','strip_tags').';'; 
 $data['school'] = I('post.classa','','strip_tags'); 
 $data['department'] = I('post.classb','','strip_tags'); 
 $data['stu_class'] = I('post.classc','','strip_tags'); 
 //   这里应该要修改指定了部门等
        // $con['mphone']=$data['phone'];
        // $db = M('teacher','think_','mysql://'.C('DB_USER').':'.C('DB_PWD').'@localhost/thinkphp2#utf8');
        // $userarr=$db->where($con)->find();   
        if($userarr){
            $data['department'] = $userarr['department'];
            $data['stu_class'] = '教师';    
            $data['group_id']=8;
        }else{
            $data['group_id']=6;
        }        


    $group_id=$data['group_id'];
    $data['smscode'] = $smscode;
    $condition['user']= $data['user'];


 


// pr($data);
echo $uid=M('member')->where($condition)->find();
    if($uid){
	    $this -> error('用户已存在。人文学生的用户名为学号，密码也为学号。',U('Lilyreg/index'),15);
	}else{
	    
		$uid=M('member')->data($data)->add();
		M('auth_group_access')->data(array('group_id'=>$group_id,'uid'=>$uid))->add();
        addlog('新增UID'.$uid.json_encode($data));

        if(empty( $data['wx_openid']))	{
            $title='已成功注册';
            $content='请关闭本页面。';
            // ，并进行微信帐号绑定。
        }else{
             $title='请关闭本页面。';
            $content='已成功注册并进行了微信绑定。无需再进行微信绑定<br>
            您应该会收到三思网络发来的一条欢迎信息。';
            // ，并进行微信帐号绑定。   
        }	
        echo h5page($title,$content);       


            
        // $user不写的话发给2013014
        // $msgarr不是数组的话，随便写了
        $con5['uid']=$uid;
        $userarr=M('member')->where($con5)->find();
        $msgarr['remark']='欢迎关注三思网络，您已注册并绑定。';
        $msgarr['first']='具体功能请见网页介绍';
        $msgarr['keyword3']='三思网络';
        R('Task/SendTplMsg',array($msgarr,$userarr));
		}

        
    //   $this -> success('注册成功',U('Lilyreg/regsuccess'),1);
        
    }
}






public function classify(){
    // 这里要手动修改
        $db = M('classleader');  

        $classabc=I('post.');  //接收模板文件jquery $(load)传来参数。data
        $postfield=$classabc['fc'];
        $getfield=$classabc['sc'];
        $postdata=$classabc['data'];

        $where[$postfield]=$postdata;
        $order=$postfield." asc";
        $query=$db->distinct(true)->field($getfield)->order($order)->where($where)->select();   //在三级分类表classc里找出字段classB_id=$classb_id

        if($query){  //判断如果有数据就显示  二级分类   如果无数据就显示 无分类
            $temp="<option selected='selected'>请选择</option>";
        }else{
            $temp="<option selected='selected'>无分类</option>";
        }
        //循环数组
        foreach ($query as $key=>$value)
            { 
                $temp.="<option value=".$query[$key][$getfield].">".$query[$key][$getfield]."</option>";
            }            

        echo $temp;
}



    public function regsuccess($title='注册成功',$content='请关闭本页面，并返回微信聊天界面。'){
echo "
<html>
<head>
  <title>".$title."</title>
  <meta charset='utf-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <link rel='stylesheet' href='http://cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap.min.css'>  
  <script src='http://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js'></script>
  <script src='http://cdn.static.runoob.com/libs/bootstrap/3.3.7/js/bootstrap.min.js'></script>

</head>
<body>
".$content."</h1>


</body>
</html>



";

    }
    public function regerror(){
      $title='绑定成功';
      $content='请关闭本页面。';
      echo h5page($title,$content);
    }    
    public function indexbak(){
        $data=I('get.');
        
        $con['mphone']=$data['phone'];

// // pr($data);
// // pr($_SERVER);
//     $code=$data['code'];
// // pr('$code： '.$code);
// // pr($data);    
//     if($code){
//         $data='';
//         $temp=R('Lilyweixin/weixincodegetopenid',array($code));
// // pr($temp);        
//         $data['wx_openid']=$temp;
//     }
//  pr($data);   
// addlog('lilyreg.index.get12'.json_encode($wx_openid));


// 数据据不同
        $db = M('teacher','think_','mysql://'.C('DB_USER').':'.C('DB_PWD').'@localhost/thinkphp2#utf8');
        $r=$db->where($con)->find();
        
        // $r=M('teacher')->where($con)->find();    
        $r['wx_openid']=$data['wx_openid'];
        
        Session_Start();
        if(empty($_SESSION["smscode"])){
            $_SESSION["smscode"]=rand(1000,9999);
        }
// addlog('$_SESSION["smscode"]'.$_SESSION["smscode"]);
    $this->assign('data',$r); 
// addlog('lilyreg.code'.$_SESSION["smscode"]);
// addlog('lilyreg.index'.json_encode($r));
    // pr($r);
   	$this -> display();




//   echo  shorturl('www.bai2du.com');     
    }    
}