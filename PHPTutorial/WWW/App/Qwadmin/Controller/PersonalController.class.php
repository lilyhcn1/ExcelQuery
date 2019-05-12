<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2015-09-18
* 版    本：1.0.0
* 功能说明：个人中心控制器。
*
**/

namespace Qwadmin\Controller;
use Qwadmin\Controller\ComController;

class PersonalController extends ComController {

	public function profile(){
		
		$member = M('member')->where('uid='.$this->USER['uid'])->find();
		$this->assign('nav',array('Personal','profile',''));//导航
		$this->assign('member',$member);
		$this -> display();
	}
	
	public function update(){
		
		$uid = $this->USER['uid'];
		$password = isset($_POST['password'])?trim($_POST['password']):false;
		if($password) {
			$data['password'] = password($password);
		}

		$head = I('post.head','','strip_tags');
		if($head<>'') {
			$data['head'] = $head;
		}
        $data['nickname'] = isset($_POST['nickname'])?trim($_POST['nickname']):'';
		$data['sex'] = isset($_POST['sex'])?intval($_POST['sex']):0;
		$data['birthday'] = isset($_POST['birthday'])?strtotime($_POST['birthday']):0;
		$data['phone'] = isset($_POST['phone'])?trim($_POST['phone']):'';
		$data['qq'] = isset($_POST['qq'])?trim($_POST['qq']):'';
		$data['email'] = isset($_POST['email'])?trim($_POST['email']):'';
		$data['department'] = isset($_POST['department'])?trim($_POST['department']):'';
		$data['school'] = isset($_POST['school'])?trim($_POST['school']):'';	
		$data['querypw'] = isset($_POST['querypw'])?trim($_POST['querypw']):'';
		$data['querywrpw'] = isset($_POST['querywrpw'])?trim($_POST['querywrpw']):'';

		$isadmin = isset($_POST['isadmin'])?$_POST['isadmin']:'';
		if($uid <> 1) {#禁止最高管理员设为普通会员。
			$data['isadmin'] = $isadmin=='on'?1:0;
		}
		$Model = M('member');
		$Model->data($data)->where("uid=$uid")->save();
		addlog('修改个人资料');
		$this->success('操作成功！');

		
	}
}