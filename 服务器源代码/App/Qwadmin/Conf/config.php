<?php
return array(
	'UPDATE_URL'=>'http://update.qd.qiawei.com/',
	'NEWS_URL'=>'http://qwadmin.qiawei.com/api/news/',
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
	
//***********************************excel**********************************	
    'TEXTSYMBOL'=> '&thinsp;',// 数字占位符&thinsp;
    'QUERYLIMIT'        => '50', // excel默认查看密码	
	'EXCELPUBSHEET'=> 'unipub', // 公开数据表
	'EXCELSECRETSHEET'=> 'unisecret', // 私有数据表
	'DATALISTLIMIT'=> '50', // 公开数据表
	'FIELDSTR' => 'sheetname,name,d1,d2,d3,d4,d5,d6,d7,d8,d9,d10,d11,d12,d13,d14,d15,d16,d17,d18,d19,d20,d21,d22,d23,d24,d25,d26,d27,d28,d29,d30,d31,d32,d33,d34,d35,d36,d37,d38,d39,d40,d41,d42,d43,d44,d45,d46,d47,d48,d49,d50',
	'DATAFIELDSTR' => 'd1,d2,d3,d4,d5,d6,d7,d8,d9,d10,d11,d12,d13,d14,d15,d16,d17,d18,d19,d20,d21,d22,d23,d24,d25,d26,d27,d28,d29,d30,d31,d32,d33,d34,d35,d36,d37,d38,d39,d40,d41,d42,d43,d44,d45,d46,d47,d48,d49,d50',	
	'NOTFIELDSTR' => 'id,wrpw,rpw,r,w,data1,data2,ord,name,pid,custom1,custom2,sheetname,t',
	'MLNOTFIELD'=>'id,ord,r,w,custom1,custom2,name,pid,sheetname,rpw,wrpw,t',    //默认新增的表单名的排除字段
    // IP地址段，在这里的可以访问，要不访问不了。 	
// 	'IPCONFIG'=>'[{"start":"192.168.0.0","end":"192.168.255.255"},{"start":"122.226.131.1","end":"122.226.131.255"},{"start":"127.0.0.1","end":"127.0.255.255"}]',
	'IPCONFIG'=>'[{"start":"0.0.0.0","end":"255.255.255.255"}]',	
    'CSVNUM' =>10,
    	
//***********************************这是是通用数据编辑的参数**********************************		 	
    'MAXFILESIZE'=> '999999999',    //上传插件的文件大小限制
    'UPTYPES'=>  "  image/jpeg,
                    image/jpg,
                    image/jpeg,
                    image/png,
                    image/pjpeg,
                    image/gif,
                    image/bmp,
                    image/x-png,
                    application/msword,
                    application/pdf,
                    aplication/zip,
                    aplication/mp4,
                    aplication/rar
                    
                    ", //准备弃用
    'EXTS'=>  "  jpeg,jpg,jpeg,png,pjpeg,gif,bmp,pdf,zip,rar,doc,docx,xls,xlsx,xlsm,mp4,ppt,pptx,iso
                    ",      
    'PHOTOEXTS'  =>"jpeg,jpg,jpeg,png,pjpeg,gif,bmp",
    'ZDYUPLOAD'  =>"true",   //自定义上传时不改文件名，怕有危险，弄个开关
    'ZDYUPLOADSYMBOL'  =>"自定义",   //自定义时的标志符
    'PAGESIZE'=>    '15',
    'PICWIDTH'=>     '150',
    'TIPNUM' => '9999', //自动提示的数目，越大越卡
    'FORCEQUERYNUM' => '99999', //查询结果为空时，是否
    'EDITTIME' => '600', //强制查询的极限，
    'AILINKLEN'=> '20', //文本超过这个数值就不再加链接了，直接输出文本
    'AILINK' => 'auto', //auto猜测并自动加链接，link就加成搜索，nolink不加链接，
);