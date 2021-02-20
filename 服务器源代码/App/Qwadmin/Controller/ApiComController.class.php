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
//com带权限
define("LILYCOM",     "Com");
use Qwadmin\Controller\ComController;
class ApiComController extends ComController{    
// // //无权限
// define("LILYCOM",     "");
// use Common\Controller\BaseController;
// use Think\Controller;
// class ApiController extends BaseController{    
   
//开始代码    
public function index(){
    $url=U($Think.CONTROLLER_NAME."/uniquerydata");
        header("Location: $url");
}


// 返回datalist
public function tiplist($echojson="true"){
    $db=M(C('EXCELSECRETSHEET'));
    $querycon=$this->consafe(I('get.'));
    
// pr($querycon,'$queryconfds23');    
    
    if(!empty($querycon['name'])){
        $sheetnamearr=$db->where($querycon)->distinct(true)->field('name')->order('id')->select();
        $tipliststr=twoarraytostr ($sheetnamearr,'name');
    }else{
        $tipliststr=",";
    }

//返回code等
    $r['code']='200';                     //200代表正常
    $r['getarr']=I('get.');
    $r['tipliststr']=$tipliststr;


return returnhttpjson($r,$echojson);
}



/* 
// 通用查询的各种输出形式
// $con2代表明确的条件，数组格式
// $likecon代表模糊查询，向量格式
*/
public function searchdata($echojson="true",$type="search"){
$con2=$this->consafe(I('get.'));

$todelall=explode(',',C('NOTFIELDSTR'));        
$idsheet=explode(",","id,sheetname");
$ttt=array_diff($todelall,$idsheet);
// pr($ttt);
$con2['notfield']=implode(",",$ttt);
// pr($con2,'con2dfwfef');
// pr($t,"2er2er23");
$t=R('Rwxy'.LILYCOM.'/echounisheetuni',array(C('EXCELSECRETSHEET'),$con2,'','arr'));

// pr($t,'t2222');

$r['code']='200';   
$r['getarr']=I('get.');
// $r['res']['listnum']=count($t); 
//用户相关信息返回
$userarr=$this->USER;
if(!is_array($userarr)){
    $r['userarr']=$userarr;   
}



emptyexit($r['code']);

//查询结果返回
$sheets_list=twoarray2onearr($t,'sheetname');
// pr('1111111111111');
foreach($sheets_list as $k0=>$sheetname){
    $sttwoarr=twoarrayfindval($t,'sheetname',$sheetname);
    $r['res'][$k0]['sheetname']=$sheetname;
    $r['res'][$k0]['sheetlistnum']=count($sttwoarr); //输出表格数据长度
    
    
    //标题相关信息
    $titleallarr=R("Queryfun/gettitlearr",array($sheetname));
    $custom1=json_decode($titleallarr['custom1'],true);
// pr($custom1,'$custom1');    
    $field=empty($custom1['weborder'])?"d1,d2,d3,d4,d5":$custom1['weborder'];
    

    //这里看是否强制改变输出的字段
    if($type=="detail"){
        $field=C('DATAFIELDSTR');  //这里强制更改输出信息
    }

    $titlearr=R("Queryfun/gettitlearr",array($sheetname,"",$field));
    $r['res'][$k0]['sheetfieldlen']=0; 
    $r['res'][$k0]['sheetfieldname']=addarray($titlearr);
    $r['res'][$k0]['sheetfieldkey']=addarray($titlearr,"key");
    $r['res'][$k0]['sheetfieldlen']=count($r['res'][$k0]['sheetfieldname']); 
 
    
    $temp=twoarraygetcols($sttwoarr,"id,".implode(",",$r['res'][$k0]['sheetfieldkey']));
    $temp2=$this->resaddurl($temp);
    $r['res'][$k0]['data']=$temp2;
        
}
return returnhttpjson($r,$echojson);
}      

/* 
// 通用查询的各种输出形式
// $con2代表明确的条件，数组格式
// $likecon代表模糊查询，向量格式
*/
public function detail($echojson="true"){
    $r=$this->searchdata("false","detail");
    return returnhttpjson($r,$echojson);
} 





/* 
// 通用查询的各种输出形式
// $con2代表明确的条件，数组格式
// $likecon代表模糊查询，向量格式
*/
public function data($echojson="true",$id=""){
if(empty($id)){
    $id=I('get.id');}

$r=R('Queryfun/echoiddatacontent',array($id,$this->USER));    
return returnhttpjson($r,$echojson);    
}

public function pindex($echojson="true"){
    //get条件的转化及rpw
    $con2=$this->consafe(I('get.'));
    
    $db=M(C('EXCELSECRETSHEET'));
    $sheetnamearr=$db->where($con2)->distinct(true)->field('sheetname')->order('id')->select();
    // pr($sheetnamearr);


//返回code等
    $r['code']='200';                     //200代表正常
    $r['getarr']=I('get.');
    $r['sheets']['sheetlistnum']=count($sheetnamearr);             //数据表的数量
foreach($sheetnamearr as $k=>$v){
    $sheetname=$v['sheetname'];
    $r['sheets']['sheetarr'][$k]['sheetname']=$sheetname;        //数据表名
    $r['sheets']['sheetarr'][$k]['url']=U("Vi".LILYCOM."/uniquerydata?sheetname=$sheetname");  //对应的网址    
}    

return returnhttpjson($r,$echojson);
}


public function login(){
	//post 一个值过来	
   $Req_URL=$_POST['Req_URL'];
		$username = isset($_POST['user'])?trim($_POST['user']):'';
		$password = isset($_POST['password'])?trim($_POST['password']):'';  
		if($password=='') {
		    $password=password($username);
		}else{
		    $password=password($password); 
		}
		$remember = isset($_POST['remember'])?$_POST['remember']:0;

		$Req_URL=$_POST['Req_URL'];

// 强增数据
    if(is_array($dataarr)){
        $Req_URL=$dataarr['Req_URL'];
        $ErrJumpURL=$dataarr['ErrJumpURL'];
        $username=$dataarr['username'];
        $password=$dataarr['password'];
    }


		$model = M("Member");
		$user = $model-> where(array('user'=>$username,'password'=>$password)) -> find();
	

		if($user) {
			$token = password(uniqid(rand(), TRUE));
			$salt = random(10);
			$identifier = password($user['uid'].md5($user['user'].$salt));
			$auth = $identifier.','.$token;
			
			M('member')->data(array('identifier'=>$identifier,'token'=>$token,'salt'=>$salt))->where(array('uid'=>$user['uid']))->save();

            // 直接一年之内记住我
            cookie('auth',$auth,3600*24*365);//记住我
		

			if(strlen($Req_URL)<=10){
			    $url=U('index/index');
			}else{
			     $url=$Req_URL;
			}

            $r['code']='200';                     //200代表正常,401未授权
            $r['getarr']=I('post.');
            $r['userarr']=$user;
            $r['Req_URL']=$url;

		}else{
            $r['code']='401';                     //200代表正常,401未授权
            $r['getarr']=I('post.');
            $r['userarr']=null;
            $r['Req_URL']=$url;
		}
		echo json_encode($r);


}


// con条件的安全性检查
//把get中的条件进行检查，并把name的查询转变为模糊查询。
//添加rpw条件
public function consafe($con,$type='likecon'){
    $con=delemptyfield($con);

if(!empty($con['name'])){
   $con['name']=array('like',"%".$con['name']."%");
}    

    if(empty($this->USER)){
        if(empty($con['rpw'])){
            $rpw=empty(session('rpw'))?C('MLRPW'):session('rpw');
        }else{
            $rpw=$con['rpw'];
            session('rpw',$rpw);
        }
    }else{
        $rpw=$this->USER['querypw']?$this->USER['querypw']:C('MLRPW');
    }

    $con['rpw']=array("in",returncomma($rpw));    
    return delemptyfield($con);

}








//新增url
public function resaddurl($sttwoarr){
    foreach($sttwoarr as $key=>$val){
        $id=$sttwoarr[$key]['id'];
        $sttwoarr[$key]['url']=U("Vi".LILYCOM."/echoiddata?id=$id");
    }
    return $sttwoarr;
}

// 结尾处
}

