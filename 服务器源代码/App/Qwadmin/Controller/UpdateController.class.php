<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2016-01-19
* 版    本：1.0.0
* 功能说明：升级控制器。
*
**/

namespace Qwadmin\Controller;
use Qwadmin\Controller\ComController;
use Vendor\HttpDownload;
use Vendor\Zip;
use Vendor\Tree;

class UpdateController extends ComController {
	
	//开发日志
	public function devlog($p=1){
		
		
		$p = intval($p)?$p:1;
		$m = M('devlog');
		$n = $m->count();
		$pagesize = 15;#每页数量
		$offset = $pagesize*($p-1);//计算记录偏移量
		$log = $m->order('id DESC')->limit($offset.','.$pagesize)->select();
		$page	=	new \Think\Page($n,$pagesize); 
		$page = $page->show();

		$this->assign('log',$log);
		$this->assign('page',$page);
		$this->assign('nav',array('setting','devlog'));//导航
		$this -> display();
	}
	
	public function update(){
		
		$this->assign('nav',array('setting','update',''));//导航
		$this -> display();
	}
	
	public function updating(){
		
		set_time_limit(0);
		$file = isset($_GET['file'])?$_GET['file']:false;
		if($file){

			//升级包下载
			$url = C('UPDATE_URL').$file;
			$file = new HttpDownload(); # 实例化类
			$file->OpenUrl($url); # 远程文件地址
			$result = $file->SaveToBin("update.zip"); # 保存路径及文件名
			$file->Close();
			if(!$result){
				addlog('升级包下载失败。');
				die(json_encode(array('message'=>'抱歉，升级文件下载失败，请稍后再试或前往官方下载升级包手动升级！')));
			}
			$archive   = new Zip();
			$result = $archive->unzip('update.zip','./');
			if($result==-1){
				die(json_encode(array('message'=>'抱歉，升级包解压失败，请手动解压并导入update.sql升级数据库！')));
			}
			//数据库升级
			$sql = true;
			if(file_exists('update.sql')) {
				$sql = file_get_contents('update.sql');
				$prefix = C('DB_PREFIX');
				$sql = str_replace('qw_',$prefix,$sql);
				 if(!M()->execute($sql)){
					$sql = false;
				 }
			}
			if(!$sql){
				addlog('数据库升级失败。');
				die(json_encode(array('message'=>'数据库升级失败！请手动导入update.sql文件执行数据库升级！')));
			}

			//删除升级包
			@unlink('update.zip');
			
			if(file_exists('update.sql')) {
				@unlink('update.sql');
			}
			if(file_exists('update.zip')){
				addlog('升级成功。');
				die(json_encode(array('message'=>'恭喜，请手动删除update.zip和update.sql（若存在）！')));
			}
			addlog('升级成功。');
			die(json_encode(array('message'=>'恭喜，升级成功！')));
		}else{
			die('2');//参数错误
		}
	}
}