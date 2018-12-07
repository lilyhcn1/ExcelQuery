<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2016-01-21
* 版    本：1.0.0
* 功能说明：前台控制器演示。
*
**/
namespace Home\Controller;
use Think\Controller;
use Vendor\Page;
class IndexController extends ComController {
    public function index(){

		$this -> display();
    }
	/*
	//一些前台DEMO
	//单页
    public function single($aid){
		
		$aid = intval($aid);
		$article = M('article')->where('aid='.$aid)->find();
		$this->assign('article',$article);
		$this->assign('nav',$aid);
		$this -> display();
    }
	//文章
    public function article($aid){
		
		$aid = intval($aid);
		$article = M('article')->where('aid='.$aid)->find();
		$sort = M('asort')->field('name,id')->where("id='{$article['sid']}'")->find();
		$this->assign('article',$article);
		$this->assign('sort',$sort);
		$this -> display();
    }
	
	//列表
    public function articlelist($sid='',$p=1){
		$sid = intval($sid);
		$p = intval($p)>=1?$p:1;
		$sort = M('asort')->field('name,id')->where("id='$sid'")->find();
		if(!$sort) {
			$this -> error('参数错误！');
		}
		$sorts = M('asort')->field('id')->where("id='$sid' or pid='$sid'")->select();
		$sids = array();
		foreach($sorts as $k=>$v){
			$sids[] = $v['id'];
		}
		$sids = implode(',',$sids);

		$m = M('article');
		$pagesize = 2;#每页数量
		$offset = $pagesize*($p-1);//计算记录偏移量
		$count = $m->where("sid in($sids)")->count();
		$list  = $m->field('aid,title,description,thumbnail,t')->where("sid in($sids)")->order("aid desc")->limit($offset.','.$pagesize)->select();
		//echo $m->getlastsql();
		$params = array(
			'total_rows'=>$count, #(必须)
			'method'    =>'html', #(必须)
			'parameter' =>"/list-{$sid}-?.html",  #(必须)
			'now_page'  =>$p,  #(必须)
			'list_rows' =>$pagesize, #(可选) 默认为15
		);
		$page = new Page($params);
		$this->assign('list',$list);	
		$this->assign('page',$page->show(1));
		$this->assign('sort',$sort);
		$this->assign('p',$p);
		$this->assign('n',$count);

		$this -> display();
    }
	*/
}