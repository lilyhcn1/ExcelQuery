<?php
/**
*
* 版权所有：三思网络<upsir.com>
* 作    者：老黄牛<53053056>
* 日    期：2018
* 版    本：1.0.0
* 功能说明：用户控制器。
*
**/




namespace Qwadmin\Controller;
use Qwadmin\Controller\ComController;
class UdController extends ComController {

public function index(){
        $url=U('Ud/sheetindex');
        	header("Location: $url");    
}


// 管理数据表，显示能管理的数据集
public function magrecords(){
$sheetname=I('get.sheetname');
$rpw=$this->USER['querypw']?$this->USER['querypw']:C('MLRPW');
$wrpw=$this->USER['querywrpw']?$this->USER['querywrpw']:C('MLRPW');
$list=$this->echorecords($sheetname,'true');

if(count($list)==1){
    $id=$list['0']['id'];
    $this->success('......',U("Ud/addedit?id=$id"),0);
}else{
    // $this->display();    
}
$this->display();    

}

// 管理个人自己的记录集
public function magmyrecords(){
$sheetname=I('get.sheetname');
$rpw=$this->USER['querypw']?$this->USER['querypw']:C('MLRPW');
$wrpw=$this->USER['querywrpw']?$this->USER['querywrpw']:C('MLRPW');
$list=$this->echorecords($sheetname,'false');

// pr($list,'$listfdsf');
if(count($list)==1){
    $id=$list['0']['id'];
    // $this->success('......',U("Ud/addedit?id=$id"),0);
}else{
    // $this->display();    
}
$this->display();    


}


public function add(){
    
    // 编辑
    $id=I('get.id');   
    $sheetname=empty(I('get.sheetname'))?C('MLSHEETNAME'):I('get.sheetname');
    $fieldstr=compute_fieldstr(C('MLNOTFIELD'));
    
    
    if($id){
        $titlearr=$this->gettitlearr('',$id);
        $con['id']=$id;
        $idarr=$db->where($con)->order('id asc')->find();

    }else{
        $rpw=empty(I('get.rpw'))?C('MLRPW'):I('get.rpw');
        $wrpw=$this->USER['querywrpw']?$this->USER['querywrpw']:C('MLRPW');
        session('wrpw',$wrpw);    
        session('rpw',$rpw);
        session('sheetname',$sheetname);
    // pr($_SESSION);
    
    
    
    
        
        
        $titlearrall=$this->gettitlearr($sheetname);
    // pr($wrpw,'$wrpw1');
        $titlearr=$this->gettitlearr($sheetname,'',$fieldstr);
    // pr($wrpw,'$wrpw2');
    
        $db=M(C('EXCELSECRETSHEET'));
        $querycon['sheetname']=$sheetname;
        $querycon['wrpw']=$wrpw;
    // pr($id,'id');    
        if(empty($id)){
            $lastfield='d1,d2,d3';
        }else{
            // $lastfield=C('FIELDSTR');
        }
    // pr($lastfield,'$lastfield');    
        $lastinputarr=$db->where($querycon)->Field($lastfield)->order('id desc')->find(); 
    }
// pr($titlearr,'$titlearr');
// pr($sheetname,'$sheetname');
// pr($lastinputarr,'$lastinputarr');    
    // $echoarr=echoarrform($titlearr);
    $this->assign('id',$id);
    $this->assign('titlearr',$titlearr);
    $this->assign('idarr',$idarr);
    $this->assign('lastinputarr',$lastinputarr);
    $this->assign('mynavline',R('Ud/mynavline',array($sheetname,$id)));
    $this->display();            

}



public function addedit(){
$db=M(C('EXCELSECRETSHEET'));    
    // 编辑
    $id=I('get.id');   
    $sheetname=empty(I('get.sheetname'))?C('MLSHEETNAME'):I('get.sheetname');
    session('sheetname',$sheetname);
    $fieldstr=compute_fieldstr(C('MLNOTFIELD'));
// pr($fieldstr);    
    
    if($id){
// pr('fdsafds');        
        $titlearr=$this->gettitlearr('',$id,$fieldstr);
        
        // 先用管理员身份查查
        $con['id']=$id;
        $con=$this->querycon($con,'true');
        $fillingarr=$db->where($con)->order('id asc')->find();
        $sheetname=$fillingarr['sheetname'];
        // 再用自己的身份查查
        if(empty($fillingarr)){
                $confalse['id']=$id;
                $con=$this->querycon($confalse,'false');
                $fillingarr=$db->where($confalse)->order('id asc')->find();    
        }        
        session('sheetname',$fillingarr['sheetname']);
// pr($fillingarr,'$fillingarr');     
    }else{
// pr($sheetname,'$sheetnamefds');
        $titlearr=$this->gettitlearr($sheetname,'',$fieldstr);


        $querycon['sheetname']=$sheetname;

        if(empty($id)){
            $lastfield='d1,d2,d3';
        }
        
        $querycon=$this->querycon($querycon,'true');
// pr($querycon,'$querycon3443');
        $fillingarr=$db->where($querycon)->Field($lastfield)->order('id desc')->find(); 

        
}
    $datalistonearr=$this->LastInputs($sheetname);
    // $datalistonearr[0]=["天台","临海"];

    $this->assign('fillingarr',$fillingarr);
    $this->assign('id',$id);
    $this->assign('titlearr',$titlearr);
    $this->assign('datalistonearr',$datalistonearr);
    $this->assign('mynavline',R('Ud/mynavline',array($sheetname,$id)));
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



public function update($id=0){
	   // pr(I('post.'),'post');
		$id=I('post.id','','strip_tags');
        $data=arrtrim(I('post.'));
        // pr(session());
		$sheetname=session('sheetname');	
// 		$rpw=session('rpw');	
// 		$wrpw=$this->USER['querywrpw']?$this->USER['querywrpw']:C('MLRPW');
	    
// pr($sheetname,'$sheetname');        
// pr($wrpw,'$wrpw');   	    
        $titlearrall=$this->gettitlearr($sheetname);
  

        $paraarr=json_decode($titlearrall['custom1'],'true');


        $user=empty($data[$paraarr['pidkey']])?$this->USER['user']:$data[$paraarr['pidkey']];
        $data['name']=$data[$paraarr['namekey']];
        // $data['pid']=$data[$paraarr['pidkey']];
        $data['pid']=$user;
        $data['rpw']=$titlearrall['rpw'];
        $data['wrpw']=$titlearrall['wrpw'];
        $data['sheetname']=$sheetname;
	    $data['data1'] = time();	 
	    
	    $data['custom1'] = json_encode($paraarr);	
// pr($data,'data34322');	    
	    
        $db=M(C('EXCELSECRETSHEET'));        
		if($id){
			$db->data($data)->where('id='.$id)->save();
			$flag=$id;
		}else{
			$flag=$db->data($data)->add();
		}
		
// 		pr($flag);
// 		$this->success('恭喜，操作成功！',U("Ud/addedit"),7);
		
		$this->success('恭喜，操作成功！',U("Ud/updatetoadd?id=$flag"));
// 		{:U('RwxyCom/echoiddata')}?id={$id}
				
}



public function updatetoadd($id=0){
if(empty($id)){
    $id=I('get.id');}
$sheetname=session('sheetname');

$newarr=R('RwxyCom/echoiddatacontent',array($id));

// pr($id);
// pr($newarr);
// echo "<h3><a href=\"".$_SERVER["HTTP_REFERER"]."\">返回</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"."<a href=\"".session('indexpage')."\">查询首页</a></h3>";


$thisline=R('Ud/mynavline',array($sheetname,$id));

$echohtml=$thisline;
$echohtml=$thisline."<br>";
// $echohtml=R('Task/echoarrresult',array($newarr,"信息详情页"));
$echohtml.=echoarrresult($newarr,"信息详情页");
echo $echohtml;

return $echohtml;
    
    
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
    $user_querywrpw=$this->USER['querywrpw']?$this->USER['querywrpw']:C('MLRPW');
    $querycon['wrpw']=array("in",returncomma($user_querywrpw));

// pr($querycon,'$querycon');

$sheetnamearr=$db->where($querycon)->distinct(true)->field('sheetname')->order('id')->select();

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

    $user_queryrpw=$this->USER['querypw']?$this->USER['querypw']:C('MLPW');
    $pid=$this->USER['user']?$this->USER['user']:C('MLPW');
    $querycon['rpw']=array("in",returncomma($user_queryrpw));
    $querycon['pid']=$pid;

// pr($querycon);
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
    $querycon=I('get.');
    $sheetname=I('get.sheetname');
    $querycon['sheetname']=$sheetname;    
$titlearrall=$this->gettitlearr($sheetname);


// 核心语句，查询所有数据集，$magage是标记是否管理
$querycon=$this->querycon($querycon,$magage);
// pr($querycon,'$querycon3213');
    if(!empty($keyword)){
        $querycon['name'] = array('like',"%".$keyword."%");
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
    $temp='';
    foreach($val1_arr as $k2=>$v2){
        if($k2 !='id'){
            $temp.=$v2.' | ';
        }
    }
    $newqueryarr[$key1]['id']=$val1_arr['id'];  
    $newqueryarr[$key1]['content']=$temp;
}
$queryarr= $newqueryarr;
// pr($queryarr);

//计算记录偏移量等
	$page	=	new \Think\Page($count,$pagesize); 
	$page = $page->show();
	$this->assign('page',$page);
//计算记录偏移量等   
    $this->assign("sheetname",$sheetname);
    $this->assign("queryarr",$queryarr);
    $this->assign("indexpage",$indexpage);
    $this->assign("postpage",U('RwxyCom/uniquerydata'));
return $r;
    
}


// 显示数据表内容
public function echosheet($sheetnamearr,$sheetname,$magage='true'){

    
    
    foreach($sheetnamearr as $sheetvaluearr){
        $sheetvalue=$sheetvaluearr['sheetname'];
        // 管理数据表数据
        if($magage=='true'){
            $title='管理数据表';
            $url= U("Ud/magrecords?sheetname=$sheetvalue");
            $inforesult1="<h3><p>".$title."</p><h3>";
        }else{
            $title='个人数据';
            $url= U("Ud/magmyrecords?sheetname=$sheetvalue");
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

public function gettitlearr($sheetname,$id='',$fieldstr='',$delempty='true'){
$db=M(C('EXCELSECRETSHEET'));
if(!empty($id)){
    $con['id']=$id;
    $idarr=$db->where($con)->order('id asc')->find();
    $titlearr=$this->gettitlearr($idarr['sheetname'],'',$fieldstr);
}elseif(empty($sheetname) ){
    $this->error('获取标题行必须要有表单名！~');
}else{
    $querycon['sheetname']=$sheetname;
    if(empty($fieldstr)){
        $titlearr=$db->where($querycon)->order('id asc')->find();
    }else{
        $titlearr=$db->where($querycon)->Field($fieldstr)->order('id asc')->find();
    }
}   
    if(empty($titlearr)){
        $this->error('错误1231，请联系表单所有者！~',U('Ud/sheetindex'));
    }else{
        if($delempty='true'){
            $titlearr=delemptyfield($titlearr);
        }
        return $titlearr;
    }    

}

public function mynavline($sheetname,$id){
        // <div class=\"col-xs-offset-2 \">
    $thisline="<div class=\"col-xs-12\">
	<div class=\"col-xs-3\">
		<h3><a href=\"".U("Ud/index?sheetname=$sheetname")."\">首页</a></h3>   
	</div> 	
	<div class=\"col-xs-3\">
		<h3><a href=\"".U("Ud/mysheet?sheetname=$sheetname")."\">个人</a></h3>   
	</div> 	
	<div class=\"col-xs-3\">
		<h3><a href=\"".U("Ud/addedit?sheetname=$sheetname")."\">新增</a></h3>   
	</div>
	<div class=\"col-xs-3\">
		<h3><a href=\"".U('RwxyCom/echoiddata?id='.$id)."\">查看</a></h3>   
	</div>
</div>";
return $thisline;
}


// 查询所有数据集，$magage是标记是否管理
public function querycon($querycon,$magage){

$rpw=$this->USER['querypw']?$this->USER['querypw']:C('MLRPW');
$wrpw=$this->USER['querywrpw']?$this->USER['querywrpw']:C('MLWRPW');
$user=$this->USER['user']?$this->USER['user']:C('MLRPW');



    if($magage=='true'){
        $querycon['wrpw']=array("in",returncomma($wrpw));
    }elseif($magage=='false'){
        $querycon['rpw']=array("in",returncomma($rpw));
        $querycon['pid']=$user;        
    }else{
        $this->error('必须有查看密码或上传密码！~');
    }    
    
    return $querycon;
}

// 智能提示
public function SmartInput($sheetname="古村落",$key='d1',$value=''){
    //   $rpw=empty(I('get.rpw'))?C('MLRPW'):I('get.rpw');
    //     $wrpw=$this->USER['querywrpw']?$this->USER['querywrpw']:C('MLRPW');
    //     session('wrpw',$wrpw);    
    //     session('rpw',$rpw);
    //     session('sheetname',$sheetname);
    //     $titlearrall=$this->gettitlearr($sheetname);
    // // pr($wrpw,'$wrpw1');
    //     $titlearr=$this->gettitlearr($sheetname,'',$fieldstr);
    // // pr($wrpw,'$wrpw2');
    
        $db=M(C('EXCELSECRETSHEET'));
        $querycon['sheetname']=$sheetname;
        if(!empty($key) && !empty($value)){
            $querycon[$key]=$value;
        }
        $smartinputtwoarr=$db->where($querycon)->Field($key)->order('id desc')->limit(10)->distinct()->select(); 
        $keyarr=twoarray2onearr($smartinputtwoarr,$key);
        // pr($smartinputarr);
        return $keyarr;

}


// 智能提示
public function LastInputs($sheetname="古村落"){
    
        $db=M(C('EXCELSECRETSHEET'));
        $querycon['sheetname']=$sheetname;
        // if(!empty($key) && !empty($value)){
        //     $querycon[$key]=$value;
        // }
        $fieldstr=compute_fieldstr(C('MLNOTFIELD'));
        $datalistonearr=$db->where($querycon)->Field($fieldstr)->order('id desc')->limit(100)->distinct()->select();
        // pr($datalistonearr);
        // $datalistonearr=delemptyfield($datalistonearr);
        // pr($datalistonearr);
        $datalistonearr=TwoArrayAllColUnique($datalistonearr);
        // pr($datalistonearr);
        // $datalistonearr=twoarray2onearr($smartinputtwoarr,$key);
        

        // pr($datalistonearr);
        return $datalistonearr;

}

// 结尾处
}
