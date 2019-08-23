<?php
/**
 *
 * 版权所有：恰维网络<qwadmin.qiawei.com>
 * 作    者：寒川<hanchuan@qiawei.com>
 * 日    期：2015-09-17
 * 版    本：1.0.0
 * 功能说明：后台公用控制器。
 *
 **/

namespace Qwadmin\Controller;
use Common\Controller\BaseController;
use Think\Auth;

class ComController extends BaseController
{
    public $USER;

    public function _initialize()
    {
    $flag = false; 
    $code=$_GET['code'];
    // addlog($code,'$code');


//addlog(json_encode($this->USER),'user32432');

if($this->USER){
//addlog(json_encode($this->USER),'查到了user');
    session('login','yes');
    $flag = true;
    $this->USER = $iduser;   
    cookie('auth',$iduser['identifier'].','.$iduser['token']);    
}elseif($code){  //openid 进行登陆
// 读取openid
    if(empty($_SESSION["wx_openid"])){
        $openid=R('Lilyweixin/weixincodegetopenid',array($code));
        Session_Start();
        if(empty($_SESSION["wx_openid"])){
            $_SESSION["wx_openid"]=$openid;
        }        
    }else{
        $openid=$_SESSION["wx_openid"];
    }

    $conopenid['wx_openid']=array('like','%'.$openid.';%');
    $iduser=M('member')->where($conopenid)->find();
    if($iduser){
    		$flag = true;
    		$this->USER = $iduser;   
    		cookie('auth',$iduser['identifier'].','.$iduser['token']);
    		session('login','yes');
    }
}else{  //输入用户名及密码进行登陆
    $postdata=empty(I('post.'))?I('get.'):I('post.');
    $con2=R("Queryfun/constr2conarr",array($postdata,'eq'));
// pr($con2,'5432');
    $username=$con2['user'];
    $password=$con2['password'];
// addlog('已进_initialize模块user '.$username);    
		$model = M("Member");
		$iduser = $model ->field('uid,user')-> where(array('user'=>$username,'password'=>password($password))) -> find();
// 		pr($iduser,'73f');
    if($iduser){
    		$flag = true;
    		$this->USER = $iduser;   
    		cookie('auth',$iduser['identifier'].','.$iduser['token']);
    		session('login','yes');
    // 		addlog('已进_initialize模块user登陆了 '.$username);    
    }

}
       C(setting());
        $auth = cookie('auth');
		list($identifier, $token) = explode(',', $auth);
		if (ctype_alnum($identifier) && ctype_alnum($token)) {
// 			$user = M('member')->field('uid,user,identifier,token,salt,nickname')->where(array('identifier'=>$identifier))->find();
			$user = M('member')->where(array('identifier'=>$identifier))->find();			
			if($user) {
				if($token == $user['token'] && $user['identifier'] == password($user['uid'].md5($user['user'].$user['salt']))){
					$flag = true;
					$this->USER = $user;
					session('login','yes');
				}
			}
		}


//这里出错跳转操作
    $Req_URL=$_SERVER['REQUEST_URI'];
    $url = U("login/index").'?Req_URL='.$Req_URL;
        
//        $url = U("login/index");
        if (!$flag) {
            header("Location: {$url}");
            exit(0);
        }
        $m = M();
        $prefix = C('DB_PREFIX');
        $UID = $this->USER['uid'];
        $userinfo = $m->query("SELECT * FROM {$prefix}auth_group g left join {$prefix}auth_group_access a on g.id=a.group_id where a.uid=$UID");
        $Auth = new Auth();
        $allow_controller_name = array('Upload');//放行控制器名称
        $allow_action_name = array();//放行函数名称
        if ($userinfo[0]['group_id'] != 1 && !$Auth->check(CONTROLLER_NAME . '/' . ACTION_NAME, $UID) && !in_array(CONTROLLER_NAME, $allow_controller_name) && !in_array(ACTION_NAME, $allow_action_name)) {
            $this->error('没有权限访问本页面!',U('index/index'));
        }

        $user = member(intval($UID));
        $this->assign('user', $user);


        $current_action_name = ACTION_NAME == 'edit' ? "index" : ACTION_NAME;
        $current = $m->query("SELECT s.id,s.title,s.name,s.tips,s.pid,p.pid as ppid,p.title as ptitle FROM {$prefix}auth_rule s left join {$prefix}auth_rule p on p.id=s.pid where s.name='" . CONTROLLER_NAME . '/' . $current_action_name . "'");
        $this->assign('current', $current[0]);


        $menu_access_id = $userinfo[0]['rules'];

        if ($userinfo[0]['group_id'] != 1) {

            $menu_where = "AND id in ($menu_access_id)";

        } else {

            $menu_where = '';
        }
        $menu = M('auth_rule')->field('id,title,pid,name,icon')->where("islink=1 $menu_where ")->order('o ASC')->select();
        $menu = $this->getMenu($menu);
        $this->assign('menu', $menu);


    }


    protected function getMenu($items, $id = 'id', $pid = 'pid', $son = 'children')

    {
        $tree = array();
        $tmpMap = array();

        foreach ($items as $item) {
            $tmpMap[$item[$id]] = $item;
        }

        foreach ($items as $item) {
            if (isset($tmpMap[$item[$pid]])) {
                $tmpMap[$item[$pid]][$son][] = &$tmpMap[$item[$id]];
            } else {
                $tree[] = &$tmpMap[$item[$id]];
            }
        }
        return $tree;
    }
}