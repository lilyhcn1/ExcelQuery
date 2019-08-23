<?php
/**
 * 赞博客，赞生活 v1.0
 * =====================================
 * 安装配置文件
 * +++++++++++++++++++++++++++++++++++++
 * 天下大事必作于细，天下难事必作于易
 * =====================================
 * $Author: 陈海赞 QQ:526199364$
 */
return array(
		/* ------系统------ */
		//系统名称
		'name'=>'通用查询系统',
		//系统版本
		'version'=>'1.0',
		//系统powered
		'powered'=>'Powered by upsir.com',
		//系统脚部信息
		'footerInfo'=> 'Copyright © 2012-2013 upsir.com Corporation',

		/* ------站点------ */
		//数据库文件
		'sqlFileName'=>'db.sql',
		//数据库配置文件
		'dbConfig'=>'../../App/Common/Conf/config.php',
		//数据库名
		'dbName' => '',
		//数据库表前缀
		'dbPrefix' => 'qw_',
		//站点名称
		'siteName' => '通用数据查询系统',
		//站点关键字
		'siteKeywords' => '通用数据查询系统',
		//站点描述
		'siteDescription' => '通用数据查询系统',
		//附件上传的目录
		'uploaddir' => 'Uploads',
		//需要读写权限的目录
		'dirAccess' => array(
			'../../d',
			'../../Public',
			'../../temp',
			'../../App/install/templates/',
			'../../Uploads',
		),
		/* ------写入数据库完成后处理的文件------ */
		'handleFile' => 'main.php',
		/* ------生成数据库配置文件的模板------ */
		'dbSetFile'=> 'config.ini.php',
		/* ------安装验证/生成文件;非云平台安装有效------ */
		'installFile' => './install.lock',
		'alreadyInstallInfo' => '你已经安装过该系统，如果想重新安装，请先删除站App/install/install.lock 文件，然后再尝试安装！',
	);