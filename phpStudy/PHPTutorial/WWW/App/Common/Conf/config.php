<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2015-09-15
* 版    本：1.0.0
* 功能说明：配置文件。
*
**/
return array(
	'URL' =>'http://localhost/qwadmin', //网站根URL
	'DEFAULT_MODULE'        =>  'Qwadmin',
	//数据库链接配置
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => '127.0.0.1', // 服务器地址
	'DB_NAME'   => 'r34', // 数据库名
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => 'admin', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => 'qw_', // 数据库表前缀
	'DB_CHARSET'=>  'utf8',      // 数据库编码默认采用utf8
	'LILYCDN'=>  '',      // CDN配置
//***********************************备份配置**********************************
	//备份配置
	'DB_PATH_NAME'=> 'db',        //备份目录名称,主要是为了创建备份目录
	'DB_PATH'     => './db/',     //数据库备份路径必须以 / 结尾；
	'DB_PART'     => '20971520',  //该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M
	'DB_COMPRESS' => '1',         //压缩备份文件需要PHP环境支持gzopen,gzwrite函数        0:不压缩 1:启用压缩
	'DB_LEVEL'    => '9',         //压缩级别   1:普通   4:一般   9:最高
//***********************************调试设置**********************************
	'SHOW_PAGE_TRACE' => false, //在配置文件设为false就行了

	'RIGHTREPLY'=>'false',
//     'APP_DEBUG'=>'true',
//  'DB_FIELD_CACHE'=>'false',
//  'HTML_CACHE_ON'=>'false',

 


//***********************************excel**********************************	
	'QUERYPW'        => 'admin', // excel默认查看密码	
    'TEXTSYMBOL'=> '&thinsp;',// 数字占位符&thinsp;
    'PERSONPW'=> 'admin123',// 个人查询密码
    'QUERYLIMIT'        => '50', // excel默认查看密码	
	'EXCELPUBSHEET'=> 'unipub', // 公开数据表
	'EXCELSECRETSHEET'=> 'unisecret', // 私有数据表
	'DATALISTLIMIT'=> '50', // 公开数据表
	'FIELDSTR' => 'sheetname,name,d1,d2,d3,d4,d5,d6,d7,d8,d9,d10,d11,d12,d13,d14,d15,d16,d17,d18,d19,d20,d21,d22,d23,d24,d25,d26,d27,d28,d29,d30,d31,d32,d33,d34,d35,d36,d37,d38,d39,d40,d41,d42,d43,d44,d45,d46,d47,d48,d49,d50',
    // IP地址段，在这里的可以访问，要不访问不了。 	
// 	'IPCONFIG'=>'[{"start":"192.168.0.0","end":"192.168.255.255"},{"start":"122.226.131.1","end":"122.226.131.255"},{"start":"127.0.0.1","end":"127.0.255.255"}]',
	'IPCONFIG'=>'[{"start":"0.0.0.0","end":"255.255.255.255"}]',	
    'CSVNUM' =>10,
//***********************************阿里大鱼短信**********************************		
'DAYUSMSAPPKEY'=>'',
'DAYUSMSSECRET'	=>'',


    'MINI1'=>'',
    'MINI1SECRET'=>'',	
);
