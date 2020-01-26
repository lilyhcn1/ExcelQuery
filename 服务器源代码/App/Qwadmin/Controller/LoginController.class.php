<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2016-01-17
* 版    本：1.0.0
* 功能说明：后台登录控制器。
*
**/

namespace Qwadmin\Controller;
use Common\Controller\BaseController;
use Think\Auth;
class LoginController extends BaseController {
    public function index(){
    // if(!empty($_GET['Req_URL'])){
    //     $Req_URL=$_GET['Req_URL'];
    // }else{
    //     $Req_URL=session('jumpurl');
    // }
$Req_URL=$_GET['Req_URL'];	
// 	pr($_SERVER);
// 	pr($Req_URL);

	$data=I('get.');
// C('EXCELSECRETSHEET');
// pr($_SESSION,'54356');

// add-log('已进入login模块index');
// add-log('login/index.$temp1'.json_encode($temp1));	
// 用于小程序的免登陆	--------------------------------

	
		$flag = false;
        $auth = cookie('auth');
		list($identifier, $token) = explode(',', $auth);
		if (ctype_alnum($identifier) && ctype_alnum($token)) {
// 			$user = M('member')->field('uid,user,identifier,token,salt')->where(array('identifier'=>$identifier))->find();
            $user = M('member')->where(array('identifier'=>$identifier))->find();
			if($user) {
				if($token == $user['token'] && $user['identifier'] == password($user['uid'].md5($user['user'].$salt))){
					$flag = true;
					$this->USER = $user;
					
				}
			}
		}
        if ($flag) {
           $this -> error('您已经登录,正在跳转到主页',U("index/index"));
        }
       $this->assign('Req_URL',$Req_URL);
		$this -> display();
    }
public function login($dataarr=''){
// 	addlog('login'.$Req_URL);
// 	pr($_SERVER);
// 	pr($Req_URL);

	//post 一个值过来	
   $Req_URL=$_POST['Req_URL'];
   $ErrJumpURL=U("login/index").'?Req_URL='.$Req_URL;


//暂时去除验证码
//		$verify = isset($_POST['verify'])?trim($_POST['verify']):'';
//		if (!$this->check_verify($verify,'login')) {
//			$this -> error('验证码错误！',$ErrJumpURL);
//		}


		$username = isset($_POST['user'])?trim($_POST['user']):'';
		$password = isset($_POST['password'])?trim($_POST['password']):'';  
		if($password=='') {
		    $password=password($username);
		}else{
		    $password=password($password); 
		}


//  		addlog('user,pwd。',$username.'---'.$password);
		$remember = isset($_POST['remember'])?$_POST['remember']:0;
// 		if ($username=='') {
// 			$this -> error('用户名不能为空！',$ErrJumpURL);
// 		} elseif ($password=='') {
// 			$this -> error('密码必须！',$ErrJumpURL);
// 		}

		$Req_URL=$_POST['Req_URL'];

// 强增数据
// add-log(json_encode($dataarr),'342356');
    if(is_array($dataarr)){
        $Req_URL=$dataarr['Req_URL'];
        $ErrJumpURL=$dataarr['ErrJumpURL'];
        $username=$dataarr['username'];
        $password=$dataarr['password'];
    }

// add-log($Req_URL,'$Req_URL');
// add-log($username,'$username');
// add-log($password,'$password');

		$model = M("Member");
		$user = $model ->field('uid,user')-> where(array('user'=>$username,'password'=>$password)) -> find();
// add-log(json_encode($user),'$user563');	

		if($user) {
			$token = password(uniqid(rand(), TRUE));
			$salt = random(10);
			$identifier = password($user['uid'].md5($user['user'].$salt));
			$auth = $identifier.','.$token;
			
			M('member')->data(array('identifier'=>$identifier,'token'=>$token,'salt'=>$salt))->where(array('uid'=>$user['uid']))->save();

            // 直接一年之内记住我
            cookie('auth',$auth,3600*24*365);//记住我
// 			if($remember){
// 				cookie('auth',$auth,3600*24*365);//记住我
// 			}else{
// 				cookie('auth',$auth);
// 			}
			
			//$url=U('index/index');
//这里很有问题啊，以后的URL先写长点			

			if(strlen($Req_URL)<=10){
			    $url=U('index/index');
			}else{
			     $url=$Req_URL;
			}
			
			
    // session('login','yes');
    // $flag = true;
    // $this->USER = $user;   
    // cookie('auth',$user['identifier'].','.$user['token']);    
    
addlog('登录成功。jumpurl= '.$url);
addlog('server'.json_encode($_SERVER));
addlog('$Req_URL '.$Req_URL);
if(!empty($dataarr['username']) && !empty($dataarr['password']) ){
    // R('RwxyCom/phpupload',array($_POST,$_FILES,$_SERVER));
}


			header("Location: $url");
            exit(0);
		}else{
			addlog('登录失败。',$password);
			$this -> error('登录失败，请重试！',$ErrJumpURL);
		}
    }
	
	public function verify() {
		$config = array(
		'fontSize' => 14, // 验证码字体大小
		'length' => 4, // 验证码位数
		'useNoise' => false, // 关闭验证码杂点
		'useCurve'=>false, //关闭验证码的线
		'imageW'=>100,
		'imageH'=>40,
		);
		
		$verify = new \Think\Verify($config);
		$verify->codeSet = '0123456789'; 
		$verify -> entry('login');
	}
	
	function check_verify($code, $id = '') {
		$verify = new \Think\Verify();
		return $verify -> check($code, $id);
	}
	
public function loginidentifier($uid=''){
    
    $condition['uid']=$uid;
    $user=M('member')->where($condition)->find();
    // add-log('login.loginidentifier'.json_encode($user));
		if($user) {
			$token = password(uniqid(rand(), TRUE));
			$salt = random(10);
			$identifier = password($user['uid'].md5($user['user'].$salt));
			$auth = $identifier.','.$token;
			
			M('member')->data(array('identifier'=>$identifier,'token'=>$token,'salt'=>$salt))->where(array('uid'=>$user['uid']))->save();

			if($remember){
				cookie('auth',$auth,3600*24*365);//记住我
			}else{
				cookie('auth',$auth);
			}
		}
    
}


public function wxlogin($wx_openid=''){
    if(strlen($wx_openid)>20){
   	       $con11['wx_openid']=array('like',"%".$wx_openid."%");    
            $userarr1=M('member')->where($con11)->find();
            if(!empty($userarr1)){
                $dataarr2['Req_URL']="/";
                $dataarr2['ErrJumpURL']="/";
                $dataarr2['username']=$userarr1['user'];
                $dataarr2['password']=$userarr1['password'];
                R('Login/login',array($dataarr2));
            }else{
                $this -> success('小程序未绑定，请先绑定。',U("Lilyreg/zhbd?wx_openid=$wx_openid"),0);
                // R('Lilyreg/zhbd',array($wx_openid));
            }         
    }

}
	



// 结尾处	
}
