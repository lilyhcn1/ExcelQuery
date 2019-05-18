<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2016-02-16
* 版    本：1.0.0
* 功能说明：用户反馈。
*
**/

namespace Qwadmin\Controller;
use Qwadmin\Controller\ComController;

class FacebookController extends ComController {

	//新增
	public function add($act=null){
	
		if($act){
			$data['content'] = I('post.content','','strip_tags');
			if($data['content']==''){
				$this->error('反馈内容不能为空！');
			}
			$data['v'] = THINK_VERSION;
			$data['url'] = $_SERVER['SERVER_NAME'];

			$url="http://qwadmin.qiawei.com/index.php/api/facebook/add";
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_HEADER, 0);  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
			$r = curl_exec($ch);
			if($r=='ok'){
				$this->success('感谢您的反馈！');
				exit(0);
			}else{
				$this->error('系统错误，请稍后再试！');
			}
		}

		$this -> display();
	}
}