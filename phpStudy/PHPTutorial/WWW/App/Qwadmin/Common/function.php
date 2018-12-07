<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2015-09-17
* 版    本：1.0.0
* 功能说明：后台公共文件。
*
**/

/**
*
* 函数：日志记录
* @param  string $log   日志内容。
* @param  string $name （可选）用户名。
*
**/
function addlog($log,$name='AdminUser'){
	
// 	$auth = cookie('auth');
// 	if(!empty($auth)){
//     	if(!$name){
//     		list($identifier, $token) = explode(',', $auth);
//     		if (ctype_alnum($identifier) && ctype_alnum($token)) {
//     			$user = M('member')->field('user')->where(array('identifier'=>$identifier))->find();
//     			$data['name'] = $user['user'];
//     		}else{
//     			$data['name'] = '空';
//     		}
//     	}else{
//     // 原来代码 $data['name'] = $name;
//     		$data['name'] = $name;
//     	}	    
// 	}else{
// 	    $data['name']='AdminUser';
// 	}
$Model = M('log');
    $data['name']='AdminUser';
	$data['t'] = time();
	$data['ip'] = $_SERVER["REMOTE_ADDR"];
	$data['log'] = $log;
	$Model->data($data)->add();
}

function addlogbak($log,$name=false){
	$Model = M('log');
	$auth = cookie('auth');
	if(!empty($auth)){
    	if(!$name){
    		list($identifier, $token) = explode(',', $auth);
    		if (ctype_alnum($identifier) && ctype_alnum($token)) {
    			$user = M('member')->field('user')->where(array('identifier'=>$identifier))->find();
    			$data['name'] = $user['user'];
    		}else{
    			$data['name'] = '空';
    		}
    	}else{
    // 原来代码 $data['name'] = $name;
    		$data['name'] = $name;
    	}	    
	}else{
	    $data['name']='AdminUser';
	}

	$data['t'] = time();
	$data['ip'] = $_SERVER["REMOTE_ADDR"];
	$data['log'] = $log;
	$Model->data($data)->add();
}

/**
*
* 函数：task的日志记录
* @param  string $log   日志内容。
* @param  string $name （可选）用户名。
*
**/
function taskaddlog($log,$name='task'){
	$Model = M('log');
	$data['name'] = $name;
	$data['t'] = time();
	$data['ip'] = '127.0.0.1';
	$data['log'] = $log;
	$Model->data($data)->add();
}


/**
*
* 函数：获取用户信息
* @param  int $uid      用户ID。
* @param  string $name  数据列（如：'uid'、'uid,user'）
*
**/
function member($uid,$field=false) {
	$model = M('Member');
	if($field){
		return $model ->field($field)-> where(array('uid'=>$uid)) -> find();
	}else{
		return $model -> where(array('uid'=>$uid)) -> find();
	}
}


/**
 * 随机字符
 * @param number $length 长度
 * @param string $type 类型
 * @param number $convert 转换大小写
 * @return string
 */
function random($length = 6, $type = 'string', $convert = 0)
{
    $config = array(
        'number' => '1234567890',
        'letter' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'string' => 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789',
        'all' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    );

    if (!isset($config[$type])) {
        $type = 'string';
    }
    $string = $config[$type];

    $code = '';
    $strlen = strlen($string) - 1;
    for ($i = 0; $i < $length; $i++) {
        $code .= $string{mt_rand(0, $strlen)};
    }
    if (!empty($convert)) {
        $code = ($convert > 0) ? strtoupper($code) : strtolower($code);
    }
    return $code;
}

