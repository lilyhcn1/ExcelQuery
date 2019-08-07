<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2016-01-20
* 版    本：1.0.0
* 功能说明：用户控制器。
*
**/

namespace Qwadmin\Controller;
use Qwadmin\Controller\ComController;
class MemberController extends ComController {
    public function index(){
		

		$p = isset($_GET['p'])?intval($_GET['p']):'1';
		$field = isset($_GET['field'])?$_GET['field']:'';
		$keyword = isset($_GET['keyword'])?htmlentities($_GET['keyword']):'';
		$order = isset($_GET['order'])?$_GET['order']:'DESC';
		$where = '';
		
		$prefix = C('DB_PREFIX');
		if($order == 'asc'){
			$order = "{$prefix}member.t asc";
		}elseif(($order == 'desc')){
			$order = "{$prefix}member.t desc";
		}else{
			$order = "{$prefix}member.uid desc";
		}
		if($keyword <>''){
			if($field=='user'){
				$where = "{$prefix}member.user LIKE '%$keyword%'";
			}
			if($field=='phone'){
				$where = "{$prefix}member.phone LIKE '%$keyword%'";
			}
			if($field=='nickname'){
				$where = "{$prefix}member.nickname LIKE '%$keyword%'";
			}
		}
// 		$where2=
		
		
		$user = M('member');
		$pagesize = 15;#每页数量
		$offset = $pagesize*($p-1);//计算记录偏移量
		$count = $user->count();
		
		$list  = $user->field("{$prefix}member.*,{$prefix}auth_group.id as gid,{$prefix}auth_group.title")->order($order)->join("{$prefix}auth_group_access ON {$prefix}member.uid = {$prefix}auth_group_access.uid")->join("{$prefix}auth_group ON {$prefix}auth_group.id = {$prefix}auth_group_access.group_id")->where($where)->limit($offset.','.$pagesize)->select();
		
		
		//$user->getLastSql();
		$page	=	new \Think\Page($count,$pagesize); 
		$page = $page->show();
        $this->assign('list',$list);	
        $this->assign('page',$page);
		$group = M('auth_group')->field('id,title')->select();
		$this->assign('group',$group);
		$this -> display();
    }
	
	public function del(){
		
		$uids = isset($_REQUEST['uids'])?$_REQUEST['uids']:false;
		//uid为1的禁止删除
		if($uids==1 or !$uids){
			$this->error('参数错误！');
		}
		if(is_array($uids)) 
		{
			foreach($uids as $k=>$v){
				if($v==1){//uid为1的禁止删除
					unset($uids[$k]);
				}
				$uids[$k] = intval($v);
			}
			if(!$uids){
				$this->error('参数错误！');
				$uids = implode(',',$uids);
			}
		}

		$map['uid']  = array('in',$uids);
		if(M('member')->where($map)->delete()){
			M('auth_group_access')->where($map)->delete();
			addlog('删除会员UID：'.$uids);
			$this->success('恭喜，用户删除成功！');
		}else{
			$this->error('参数错误！');
		}
	}
	
	public function edit(){
		
		$uid = isset($_GET['uid'])?intval($_GET['uid']):false;
		if($uid){	
			//$member = M('member')->where("uid='$uid'")->find();
			$prefix = C('DB_PREFIX');
			$user = M('member');
			$member  = $user->field("{$prefix}member.*,{$prefix}auth_group_access.group_id")->join("{$prefix}auth_group_access ON {$prefix}member.uid = {$prefix}auth_group_access.uid")->where("{$prefix}member.uid=$uid")->find();

		}else{
			$this->error('参数错误！');
		}
		
		$usergroup = M('auth_group')->field('id,title')->select();
		$this->assign('usergroup',$usergroup);

		$this->assign('member',$member);
		$this -> display();
	}
	
	public function update($ajax=''){
// pr($ajax);
		if($ajax=='yes'){
			$uid = I('get.uid',0,'intval');
			$gid = I('get.gid',0,'intval');
			
			M('auth_group_access')->data(array('group_id'=>$gid))->where("uid='$uid'")->save();
			die('1');
		}
		
		$uid = isset($_POST['uid'])?intval($_POST['uid']):false;
		$user = isset($_POST['user'])?htmlspecialchars($_POST['user'], ENT_QUOTES):'';
		$group_id = isset($_POST['group_id'])?intval($_POST['group_id']):0;
		if(!$group_id){
			$this->error('请选择用户组！');
		}
		$password = isset($_POST['password'])?trim($_POST['password']):false;
		if($password) {
			$data['password'] = password($password);
		}
		$head = I('post.head','','strip_tags');
		if($head<>'') {
			$data['head'] = $head;
		}
		$data['nickname'] = isset($_POST['nickname'])?$_POST['nickname']:0;
		$data['sex'] = isset($_POST['sex'])?intval($_POST['sex']):0;
		$data['birthday'] = isset($_POST['birthday'])?strtotime($_POST['birthday']):0;
		$data['phone'] = isset($_POST['phone'])?trim($_POST['phone']):'';
		$data['qq'] = isset($_POST['qq'])?trim($_POST['qq']):'';
		$data['email'] = isset($_POST['email'])?trim($_POST['email']):'';
		$data['department'] = isset($_POST['department'])?trim($_POST['department']):'';
		$data['stu_class'] =  isset($_POST['stu_class'])?trim($_POST['stu_class']):'';
		$data['querypw'] =  isset($_POST['querypw'])?trim($_POST['querypw']):'';
		$data['querywrpw'] =  isset($_POST['querywrpw'])?trim($_POST['querywrpw']):'';
		
		
		if(!$uid){
			if($user==''){
				$this->error('用户名称不能为空！');
			}
			if(!$password){
				$this->error('用户密码不能为空！');
			}
			if(M('member')->where("user='$user}'")->count()){
				$this->error('用户名已被占用！');
			}
			$data['user'] = $user;
			$uid = M('member')->data($data)->add();
			M('auth_group_access')->data(array('group_id'=>$group_id,'uid'=>$uid))->add();
			addlog('新增会员，会员UID：'.$uid);
		}else{
			M('auth_group_access')->data(array('group_id'=>$group_id))->where("uid=$uid")->save();
			M('member')->data($data)->where("uid=$uid")->save();
			addlog('编辑会员信息，会员UID：'.$uid);
		}
		$this->success('操作成功！');
	}
	
	
	public function add(){

		$usergroup = M('auth_group')->field('id,title')->order('id desc')->select();
		$this->assign('usergroup',$usergroup);
		$this -> display();
	}
	
	
	
// 	自己新增的用户
	
	
	 
     //保存导入数据
    public function save_import($data) {
        // $data=import_excel($filename);
        $db=M('member');

        foreach ($data as $k => $v) {
            if ($k > 1) {
                //  pr($v);
                $datatemp['user'] = $v['1'];
                $datatemp['nickname'] = $v['2'];
                $datatemp['password'] = password($v['3']);
                $datatemp['stu_class'] = $v['5'];
                $datatemp['phone'] = $v['6'];
                $datatemp['qq'] = $v['7'];
                $datatemp['wx_id'] = $v['8'];
                $datatemp['email'] = $v['9'];
                $datatemp['department'] = $v['10'];
                $datatemp['querypw'] = $v['11'];
                $datatemp['querywrpw'] = $v['12'];
                
// addlog(json_encode($v));
                $data_access['group_id'] =$v['4'];
                // $condition['uid']= $v['0'];
                // $haveuid= $db->where($condition)->find();
                if(empty($v['0'] )){
                    $datauser['user'] = $v['1'];
                    if ($db->where($datauser)->find()){
                        echo  $v['1']." - ".  $v['2']."已存在，新增失败，如要更新，请输入uid.";
                        $result='0';
                    }else{
                        $result = $db->add($datatemp);
                        // echo $result;
                         $data_access['uid'] =$result;
                         $result = M('auth_group_access')->add($data_access);
                        // echo '空';
                        // pr($datatemp);
                    }
                }else{
                     $datatemp['uid'] = $v['0'];
                    $result = $db->save($datatemp);
                    
                    
                     $data_access['uid']=$v['0'];
                     $condition['uid']= $v['0'];
                     M('auth_group_access')->where($condition)->delete();
                     $result = M('auth_group_access')->add($data_access);
                    // echo '非空';
                    //   pr($datatemp);
                }
            }
        }
        if ($result) {
            $num = $db->count();
            $this->success('用户导入成功' . '，现在<span style="color:red">' . $num . '</span>条数据了！');
        } else {
            $this->error('用户导入失败');
        }
    }  
  
  
    
    public function excelExport() {
        $list = M("member")->field("uid,user,nickname,password")->order("uid ASC")->select();
        $title = array('uid', '用户名', '昵称','密码'); //设置要导出excel的表头
        create_xls($list, $title);
    }

    public function upload() {
        header("Content-Type:text/html;charset=utf-8");
        $upload = new \Think\Upload(); // 实例化上传类
        $upload->maxSize = 3145728; // 设置附件上传大小
        $upload->exts = array('xls', 'xlsx'); // 设置附件上传类
        $upload->savePath = '/'; // 设置附件上传目录
        // 上传文件
        $info = $upload->uploadOne($_FILES['file']);
        $filename = 'Uploads' . $info['savepath'] . $info['savename'];
// addlog('Uploads.$info'.json_encode($info));        
        $exts = $info['ext'];
        if (!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        } else {// 上传成功

           $data=import_excel($filename);
addlog(json_encode($data));           
            $this->save_import($data);
        }
    }
	
	
	
	
	
}