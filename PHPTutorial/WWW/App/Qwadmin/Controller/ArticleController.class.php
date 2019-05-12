<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2016-09-20
* 版    本：1.0.0
* 功能说明：文章控制器。
*
**/

namespace Qwadmin\Controller;
use Qwadmin\Controller\ComController;
use Vendor\Tree;

class ArticleController extends ComController {

	public function add(){
		
		$category = M('category')->field('id,pid,name')->order('o asc')->select();
		$tree = new Tree($category);
		$str = "<option value=\$id \$selected>\$spacer\$name</option>"; //生成的形式
		$category = $tree->get_tree(0,$str,0);
		$this->assign('category',$category);//导航
		$this -> display();
	}
		
	public function index($sid=0,$p=1){
		
		$sid = intval($sid);
		$p = intval($p)>0?$p:1;
		
		$article = M('article');
		$pagesize = 20;#每页数量
		$offset = $pagesize*($p-1);//计算记录偏移量
		$prefix = C('DB_PREFIX');
		if($sid){
			$where = "{$prefix}article.sid=$sid";
		}else{
			$where = '';
		}
		$count = $article->where($where)->count();
		$list  = $article->field("{$prefix}article.*,{$prefix}category.name")->where($where)->order("{$prefix}article.aid desc")->join("{$prefix}category ON {$prefix}category.id = {$prefix}article.sid")->limit($offset.','.$pagesize)->select();
		
		$page	=	new \Think\Page($count,$pagesize); 
		$page = $page->show();
        $this->assign('list',$list);	
        $this->assign('page',$page);
		$this -> display();
	}
	
	public function del(){
		
		$aids = isset($_REQUEST['aids'])?$_REQUEST['aids']:false;
		if($aids){
			if(is_array($aids)){
				$aids = implode(',',$aids);
				$map['aid']  = array('in',$aids);
			}else{
				$map = 'aid='.$aids;
			}
			if(M('article')->where($map)->delete()){
				addlog('删除文章，AID：'.$aids);
				$this->success('恭喜，文章删除成功！');
			}else{
				$this->error('参数错误！');
			}
		}else{
			$this->error('参数错误！');
		}

	}
	
	public function edit($aid){
		
		$aid = intval($aid);
		$article = M('article')->where('aid='.$aid)->find();
		if($article){
			
			$category = M('category')->field('id,pid,name')->order('o asc')->select();
			$tree = new Tree($category);
			$str = "<option value=\$id \$selected>\$spacer\$name</option>"; //生成的形式
			$category = $tree->get_tree(0,$str,$article['sid']);
			$this->assign('category',$category);//导航
			
			$this->assign('article',$article);
		}else{
			$this->error('参数错误！');
		}
		$this -> display();
	}
	
	public function update($aid=0){
		
		$aid = intval($aid);
		$data['sid'] = isset($_POST['sid'])?intval($_POST['sid']):0;
		$data['title'] = isset($_POST['title'])?$_POST['title']:false;
		$data['keywords'] = I('post.keywords','','strip_tags');
		$data['description'] = I('post.description','','strip_tags');
		$data['content'] = isset($_POST['content'])?$_POST['content']:false;
		$data['thumbnail'] = I('post.thumbnail','','strip_tags');
		$data['t'] = time();
		if(!$data['sid'] or !$data['title'] or !$data['content']){
			$this->error('警告！文章分类、文章标题及文章内容为必填项目。');
		}
		if($aid){
			M('article')->data($data)->where('aid='.$aid)->save();
			addlog('编辑文章，AID：'.$aid);
			$this->success('恭喜！文章编辑成功！');
		}else{
			$aid = M('article')->data($data)->add();
			if($aid){
				addlog('新增文章，AID：'.$aid);
				$this->success('恭喜！文章新增成功！');
			}else{
				$this->error('抱歉，未知错误！');
			}
			
		}
	}
}