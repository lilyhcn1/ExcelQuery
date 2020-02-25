<?php
/**
*
* 版权所有：三思网络<upsir.com>
* 作    者：老黄牛<53053056>
* 日    期：2018
* 版    本：1.0.0
*
**/
namespace Qwadmin\Controller;
use Common\Controller\BaseController;
use Think\Controller;
class UdController extends BaseController{
    
// namespace Qwadmin\Controller;
// use Qwadmin\Controller\ComController;
// class UdComController extends ComController{    

public function index(){
        $url=U($Think.CONTROLLER_NAME.'/sheetindex');
        	header("Location: $url");    
}


// 管理数据表，显示能管理的数据集
public function magrecords(){
$sheetname=I('get.sheetname');

    if(is_array($this->USER)){
        $rpw=$this->USER['querypw']?$this->USER['querypw']:C('MLRPW');
        $wrpw=$this->USER['querywrpw']?$this->USER['querywrpw']:C('MLRPW');

    }
    
        if(!empty(I('get.wrpw'))){
            // pr(I('get.'));
            $wrpw2=I('get.wrpw');
            session('wrpw',$wrpw.",".$wrpw2);
        }
        $wrpw=empty(session('wrpw'))?C('MLRPW'):session('wrpw');


// pr($wrpw); 
    session('wrpw',$wrpw);  
    // pr($_SESSION);
// echo $wrpw;

$list=$this->echorecords($sheetname,'true');
// pr($list,'3422323');

 $this->assign('thisuser',$this->USER);

if(count($list)==1){
    $id=$list['0']['id'];
    $this->success('......',U(getcomstr('Ad')."/addedit?id=$id"),0);
}else{
    $this->display();    
}

}

// 管理个人自己的记录集
public function magmyrecords(){
$sheetname=I('get.sheetname');
$rpw=$this->USER['querypw']?$this->USER['querypw']:C('MLRPW');
// $wrpw=$this->USER['querywrpw']?$this->USER['querywrpw']:C('MLRPW');
$list=$this->echorecords($sheetname,'false');
// pr($sheetname,'sheetnamef23r');
// pr($list,'$listfdsf');
if(count($list)==1){
    $id=$list['0']['id'];
}else{
    // $this->display();    
}
$this->display();    


}







	public function del(){
		
		$uids = isset($_REQUEST['uids'])?$_REQUEST['uids']:false;
		//uid为1的禁止删除
		if(!$uids){
			$this->error('参数错误！1');
		}
		if(is_array($uids)) 
		{
			foreach($uids as $k=>$v){
				$uids[$k] = intval($v);
			}
			if(!$uids){
				$this->error('参数错误！2');
				$uids = implode(',',$uids);
			}
		}

		$map['id']  = array('in',$uids);
$db=M(C('EXCELSECRETSHEET'));
		if($db->where($map)->delete()){
		//	M('auth_group_access')->where($map)->delete();
// 			addlog('删除会员UID：'.$uids);
			$this->success('恭喜，用户删除成功！');
		}else{
			$this->error('参数错误！3');
		}
	}



// 显示所有的数据表
public function sheetindex(){


$db=M(C('EXCELSECRETSHEET'));
// pr(I('get.'));
$name=I('get.name');
$sheetname=I('get.sheetname');
$querycon=I('get.');
$querycon=delemptyfield($querycon);

// pr($this->USER,'$this->USER');
    if(empty($this->USER)){
        session('wrpw',I('get.wrpw'));
        $user_querywrpw=empty(session('wrpw'))?C('MLRPW'):session('wrpw');
        
    }else{
        $user_querywrpw=$this->USER['querywrpw']?$this->USER['querywrpw']:C('MLRPW');
    }
    session('wrpw',$user_querywrpw);
    
    
    $querycon['wrpw']=array("in",returncomma($user_querywrpw));

// pr($querycon,'$querycon23');

$sheetnamearr=$db->where($querycon)->distinct(true)->field('sheetname')->order('id')->select();
// pr($sheetnamearr,'$sheetnamearr43');
    $this->echosheet($sheetnamearr,$sheetname,$magage='true');
    $this->display();        
}



// 显示所有的数据表
public function mysheet(){
    $db=M(C('EXCELSECRETSHEET'));
    $name=I('get.name');
    $sheetname=I('get.sheetname');
    $querycon=I('get.');
    $querycon=delemptyfield($querycon);

    session('rpw',I('get.rpw'));
    session('sheetname',I('get.sheetname'));
    if(empty($this->USER)){
        $rpw=empty(session('rpw'))?C('MLRPW'):session('rpw');
        
    }else{
        $rpw=$this->USER['querypw']?$this->USER['querypw']:C('MLRPW');
    }
    session('rpw',$rpw);
    
    
    // $querycon['wrpw']=array("in",returncomma($user_querywrpw));


    // $user_querypw=$this->USER['querypw']?$this->USER['querypw']:C('MLPW');
    $pid=$this->USER['user']?$this->USER['user']:C('MLPW');
    $querycon['rpw']=array("in",returncomma($rpw));
    $querycon['pid']=$pid;

// pr($querycon,'5634ve');
$sheetnamearr=$db->where($querycon)->distinct(true)->field('sheetname')->order('id')->select();
// pr($sheetnamearr);
    $this->echosheet($sheetnamearr,$sheetname,$magage='false');
    
    $this->display();        
}




// 显示数据条数
public function echorecords($sheetname,$magage='true'){

// 计算首页计算首面
        $db=M(C('EXCELSECRETSHEET'));
		$p = isset($_GET['p'])?intval($_GET['p']):'1';		
		$pagesize = C('PAGESIZE');#每页数量
		$offset = $pagesize*($p-1);//计算记录偏移量
// 计算首页 结束    
// pr('fdffffffffffff');
// pr(I('get.'));
    // $querycon=I('get.');
    // $sheetname=I('get.sheetname');
    // pr(I('get.'));
$keyword=I('get.keyword');    
    $temp22=I('get.sheetname');
    if(!empty($temp22)){
        session('sheetname',$temp22);
    }
    // pr($_SESSION);
    $querycon['sheetname']=empty(session('sheetname'))?I('get.sheetname'):session(sheetname);    
$titlearrall=R('Queryfun/gettitlearr',array($sheetname));

// pr($titlearrall,'$titlearrall');
// pr($querycon,'$querycong563');

// 核心语句，查询所有数据集，$magage是标记是否管理

$querycon=R('Queryfun/querycon',array($querycon,$magage,$this->USER));
// pr($querycon,'$querycon3213');
    if(!empty($keyword)){
        // $querycon['name'] = array('like',"%".$keyword."%");
        $querycon['name'] =$keyword;
    }
    
    $querycon=delemptyfield($querycon);

// pr($querycon,'$querycon,fdsafds');

    $ordconarr=json_decode($titlearrall['custom1'],'true');
    $fieldstr=$ordconarr['weborder'];
    if(empty($fieldstr)){
        $fieldstr='d1,d2,d3,d4,d5';
    }
 
    
    $fieldstr="id,".$fieldstr;

$count = $db->where($querycon)->limit($offset.','.$pagesize)->count();
$r=$db->where($querycon)->limit($offset.','.$pagesize)->field($fieldstr)->order('id desc')->select();
// pr($r,'r,fddsfdsaf');
// pr($querycon,'fdsfdsfds333');
foreach($r as $key1=>$val1_arr){
// pr($val1_arr);
    // $temp='';
    // foreach($val1_arr as $k2=>$v2){
    //     if($k2 !='id'){
    //         $temp.=$v2.' | ';
    //     }
    // }
    // $newqueryarr[$key1]['id']=$val1_arr['id'];  
    // $newqueryarr[][$key1]['content']=$temp;
    foreach($val1_arr as $k2=>$v2){
         $newqueryarr[$key1][$k2]=$v2;
    }    

}



$queryarr= $newqueryarr;
// pr($queryarr,'queryqrr');

//计算记录偏移量等
	$page	=	new \Think\Page($count,$pagesize); 
	$page = $page->show();
	$this->assign('page',$page);
//计算记录偏移量等   
    $this->assign("sheetname",$sheetname);
    $this->assign("queryarr",$queryarr);
    $this->assign("indexpage",$indexpage);
    $this->assign("postpage",U(getcomstr('Rwxy').'/uniquerydata'));
return $r;
    
}


// 显示数据表内容
public function echosheet($sheetnamearr,$sheetname,$magage='true'){

    
    
    foreach($sheetnamearr as $sheetvaluearr){
        $sheetvalue=$sheetvaluearr['sheetname'];
        // 管理数据表数据
        if($magage=='true'){
            $title='管理数据表';
            $url= U($Think.CONTROLLER_NAME."/magrecords?sheetname=$sheetvalue");
            $inforesult1="<h3><p>".$title."</p><h3>";
        }else{
            $title='个人数据';
            $url= U($Think.CONTROLLER_NAME."/magmyrecords?sheetname=$sheetvalue");
            $inforesult1="<h3><p>".$title."</p><h3>";
        }
        
        $inforesult.="<p> <a href=\"" .$url . "\">$sheetvalue</a></p>";
    }

    if(empty($inforesult)){
        $inforesult='<h3><p>您的数据表为空！~</p><h3>';
    }else{
        $inforesult=$inforesult1.$inforesult;
    }

// pr($sheetname,'sheetname');
    $this->assign("sheetname",$sheetname);
    $this->assign("sheetnamearr",$sheetnamearr);
    $this->assign("inforesult",$inforesult);


// 计算首页
    $temp=session('rpw');
    if(empty($this->USER['user'])){
        $indexpage=U($Think.CONTROLLER_NAME."/uniquerydata",array('rpw'=>$temp));

    }else{
        $indexpage=U('index/index');
    }
    session('indexpage',$indexpage);
    $this->assign("indexpage",$indexpage);
    
}





// 结尾处
}
