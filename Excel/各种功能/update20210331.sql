# Host: localhost  (Version: 5.5.53)
# Date: 2021-03-31 20:37:05
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "qw_auth_rule"
#

DROP TABLE IF EXISTS `qw_auth_rule`;
CREATE TABLE `qw_auth_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `icon` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  `islink` tinyint(1) NOT NULL DEFAULT '1',
  `o` int(11) NOT NULL COMMENT '排序',
  `tips` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=141 DEFAULT CHARSET=utf8;

#
# Data for table "qw_auth_rule"
#

/*!40000 ALTER TABLE `qw_auth_rule` DISABLE KEYS */;
INSERT INTO `qw_auth_rule` VALUES (1,0,'Index/index','控制台','menu-icon fa fa-tachometer',1,1,'',1,1,'友情提示：经常查看操作日志，发现异常以便及时追查原因。'),(2,0,'','系统设置','menu-icon fa fa-cog',1,1,'',1,2,''),(3,2,'Setting/setting','网站设置','menu-icon fa fa-caret-right',1,1,'',1,3,'这是网站设置的提示。'),(4,2,'Menu/index','后台菜单','menu-icon fa fa-caret-right',1,1,'',1,4,''),(5,2,'Menu/add','新增菜单','menu-icon fa fa-caret-right',1,1,'',1,5,''),(6,4,'Menu/edit','编辑菜单','',1,1,'',0,6,''),(7,2,'Menu/update','保存菜单','menu-icon fa fa-caret-right',1,1,'',0,7,''),(8,2,'Menu/del','删除菜单','menu-icon fa fa-caret-right',1,1,'',0,8,''),(9,2,'Database/backup','数据库备份','menu-icon fa fa-caret-right',1,1,'',1,9,''),(10,9,'Database/recovery','数据库还原','',1,1,'',0,10,''),(11,2,'Update/update','在线升级','menu-icon fa fa-caret-right',1,1,'',0,11,''),(12,2,'Update/devlog','开发日志','menu-icon fa fa-caret-right',1,1,'',0,12,''),(13,0,'','用户及组','menu-icon fa fa-users',1,1,'',1,13,''),(14,13,'Member/index','用户管理','menu-icon fa fa-caret-right',1,1,'',1,14,''),(15,13,'Member/add','新增用户','menu-icon fa fa-caret-right',1,1,'',1,15,''),(16,13,'Member/edit','编辑用户','menu-icon fa fa-caret-right',1,1,'',0,16,''),(17,13,'Member/update','保存用户','menu-icon fa fa-caret-right',1,1,'',0,17,''),(18,13,'Member/del','删除用户','',1,1,'',0,18,''),(19,13,'Group/index','用户组管理','menu-icon fa fa-caret-right',1,1,'',1,19,''),(20,13,'Group/add','新增用户组','menu-icon fa fa-caret-right',1,1,'',1,20,''),(21,13,'Group/edit','编辑用户组','menu-icon fa fa-caret-right',1,1,'',0,21,''),(22,13,'Group/update','保存用户组','menu-icon fa fa-caret-right',1,1,'',0,22,''),(23,13,'Group/del','删除用户组','',1,1,'',0,23,''),(24,0,'','网站内容','menu-icon fa fa-desktop',1,1,'',0,24,''),(25,24,'Article/index','文章管理','menu-icon fa fa-caret-right',1,1,'',0,25,'网站虽然重要，身体更重要，出去走走吧。'),(26,24,'Article/add','新增文章','menu-icon fa fa-caret-right',1,1,'',0,26,''),(27,24,'Article/edit','编辑文章','menu-icon fa fa-caret-right',1,1,'',0,27,''),(29,24,'Article/update','保存文章','menu-icon fa fa-caret-right',1,1,'',0,29,''),(30,24,'Article/del','删除文章','',1,1,'',0,30,''),(31,24,'Category/index','分类管理','menu-icon fa fa-caret-right',1,1,'',0,31,''),(32,24,'Category/add','新增分类','menu-icon fa fa-caret-right',1,1,'',0,32,''),(33,24,'Category/edit','编辑分类','menu-icon fa fa-caret-right',1,1,'',0,33,''),(34,24,'Category/update','保存分类','menu-icon fa fa-caret-right',1,1,'',0,34,''),(36,24,'Category/del','删除分类','',1,1,'',0,36,''),(48,0,'Personal/index','个人中心','menu-icon fa fa-user',1,1,'',1,48,''),(49,48,'Personal/profile','个人资料','menu-icon fa fa-user',1,1,'',1,49,''),(50,48,'Logout/index','退出','',1,1,'',1,50,''),(51,9,'Database/export','备份','',1,1,'',0,51,''),(52,9,'Database/optimize','数据优化','',1,1,'',0,52,''),(53,9,'Database/repair','修复表','',1,1,'',0,53,''),(54,11,'Update/updating','升级安装','',1,1,'',0,54,''),(55,48,'Personal/update','资料保存','',1,1,'',0,55,''),(56,3,'Setting/update','设置保存','',1,1,'',0,56,''),(57,9,'Database/del','备份删除','',1,1,'',0,57,''),(58,2,'variable/index','自定义变量','',1,1,'',0,0,''),(59,58,'variable/add','新增变量','',1,1,'',0,0,''),(60,58,'variable/edit','编辑变量','',1,1,'',0,0,''),(61,58,'variable/update','保存变量','',1,1,'',0,0,''),(62,58,'variable/del','删除变量','',1,1,'',0,0,''),(114,119,'ViCom/uniquerydata','通用查询','',1,1,'',1,400,''),(116,119,'ViCom/echoiddata','单个信息','',1,1,'',0,420,''),(117,119,'RwxyCom/getresult','通用查询getresult','',1,1,'',0,440,''),(119,0,'UdCom/index','信息修改','menu-icon fa fa-meh-o',1,1,'',1,400,''),(120,119,'UdCom/index','首页','',1,1,'',0,10,''),(121,119,'AdCom/addedit','信息增更','',1,1,'',0,20,''),(122,119,'UdCom/del','删除','',1,1,'',0,40,''),(123,119,'UdCom/sheetindex','管理信息表','',1,1,'',1,10,''),(124,119,'UdCom/mysheet','个人信息表','',1,1,'',1,100,''),(125,119,'UdCom/magrecords','管理记录','',1,1,'',0,20,''),(126,119,'UdCom/magmyrecords','个人记录','',1,1,'',0,120,''),(127,119,'UdCom/update','update','',1,1,'',0,310,''),(128,119,'UdCom/updatetoadd','updatetoadd','',1,1,'',0,320,''),(130,119,'RwxyCom/phpupload','phpupload','',1,1,'',0,500,''),(131,119,'RwxyCom/echoteacherdb','echoteacherdb','',1,1,'',0,510,''),(132,119,'RwxyCom/uniquerydata','uniquerydata','',1,1,'',0,530,''),(136,119,'ViCom/pindex','Vicom','',1,1,'',0,530,''),(137,119,'RwxyCom/echoteacherdbnep','echoteacherdbnep','406',1,1,'',0,515,''),(138,119,'RwxyCom/echojson','echojson','',1,1,'',0,525,''),(139,119,'RwxyCom/echolist','echolist','',1,1,'',0,550,''),(140,119,'RwxyCom/echoline1','echoline1','',1,1,'',0,560,'');
/*!40000 ALTER TABLE `qw_auth_rule` ENABLE KEYS */;

DROP TABLE IF EXISTS `qw_auth_group`;
CREATE TABLE `qw_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

#
# Data for table "qw_auth_group"
#

/*!40000 ALTER TABLE `qw_auth_group` DISABLE KEYS */;
INSERT INTO `qw_auth_group` VALUES (1,'超级管理员',1,'1,2,58,65,59,60,61,62,3,56,4,6,5,7,8,9,10,51,52,53,57,11,54,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,29,30,31,32,33,34,36,37,38,39,40,41,42,43,44,45,46,47,63,48,49,50,55,136'),(2,'一般管理员',1,'1,13,14,15,16,17,18,19,20,21,22,23,48,49,50,55,119,123,120,125,121,122,124,126,127,128,114,116,117,130,131,137,138,132,136,139,140'),(10,'普通用户',1,'1,48,49,50,55,119,123,120,125,121,122,124,126,127,128,114,116,117,130,131,137,138,132,136,139,140');
/*!40000 ALTER TABLE `qw_auth_group` ENABLE KEYS */;
