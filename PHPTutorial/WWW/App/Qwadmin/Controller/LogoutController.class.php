<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2016-01-17
* 版    本：1.0.0
* 功能说明：后台登出控制器。
*
**/

namespace Qwadmin\Controller;
use Qwadmin\Controller\ComController;
class LogoutController extends ComController {
    public function index(){
		cookie('auth',null);
		$url = U("login/index");
		header("Location: {$url}");
		exit(0);
    }
}