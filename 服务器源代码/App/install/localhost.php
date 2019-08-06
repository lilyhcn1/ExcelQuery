<?php
/**
 * 赞博客，赞生活 v1.0
 * =====================================
 * 本地环境安装，非云平台
 * +++++++++++++++++++++++++++++++++++++
 * 天下大事必作于细，天下难事必作于易
 * =====================================
 * $Author: 陈海赞 QQ:526199364$
 */
//检测是否已经安装
if(file_exists($config['installFile'])){
	exit(get_tip_html($config['alreadyInstallInfo']));
}

//写入文件
function filewrite($file){
	@touch($file);
}