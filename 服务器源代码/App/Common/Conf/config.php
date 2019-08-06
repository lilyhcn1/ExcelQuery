<?php
/**
*
* 版权所有：三思网络<upsir.com>
* 作    者：老黄牛
* 日    期：2015-09-15
* 版    本：1.0.0
* 功能说明：配置文件。
*
**/

return array(
	'URL' =>'http://localhost/qwadmin', //网站根URL
	'DEFAULT_MODULE'        =>  'Qwadmin',
	//数据库链接配置
	//数据库类型
	'DB_TYPE' => 'mysql',
	//服务器
	'DB_HOST'=>'127.0.0.1',
	//数据库名
	'DB_NAME'=>'r34',
	//数据库用户名
	'DB_USER'=>'root',
	//数据库用户密码
	'DB_PWD'=> 'lily53053067',
	//数据库库表前缀
	'DB_PREFIX'=>'qw_',
	// 端口
	'DB_PORT'=> '3306',
	'DB_PREFIX' => 'qw_', // 数据库表前缀
	'DB_CHARSET'=>  'utf8',      // 数据库编码默认采用utf8
    'TMPL_PARSE_STRING'  =>array(
        '__LILYCDN__' => '' // CDN设置，如使用，必须设置跨域，如*，post,get, *
    ),	
//***********************************excel**********************************	
	'QUERYPW'        => 'admin', // excel默认查看密码	
	'PERSONPW'=> 'admin123',// 个人查询密码

//***********************************这是是通用数据编辑的参数**********************************		    
    'MLSHEETNAME'=>'个人查询系统',         //默认新增的表单名
    'MLRPW'=>'gcl',                //默认新增的表单名查看密码
    'MLWRPW'=>'fw3ewf',                //默认新增的上传密码

//***********************************阿里大鱼短信**********************************		
'DAYUSMSAPPKEY'=>'',
'DAYUSMSSECRET'	=>'',


    'MINI1'=>'',
    'MINI1SECRET'=>'',	
);