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
class ApiController extends BaseController{
public function index(){
    $url=U($Think.CONTROLLER_NAME."/uniquerydata");
        header("Location: $url");
}


// 返回datalist
public function getdatalistarr($querycon){
$db=M(C('EXCELSECRETSHEET'));
$datalistarr=$db->where($querycon)->distinct(true)->field('name')->order('id')->limit(C('TIPNUM'))->select();
// pr($datalistarr);
$datalistonearr=array_column($datalistarr,'name');
$datalistonearr=delemptyfieldgetnew($datalistonearr);

    foreach($datalistonearr as $key11=>$value11){
        if(!empty($value11)){
            $newarr[$key11]=preg_replace( '/[\x00-\x1F]/','',$value11);
        }
    }
$datalistonearr=$newarr;

    return $datalistonearr;
}



/* 
// 通用查询的各种输出形式
// $con2代表明确的条件，数组格式
// $likecon代表模糊查询，向量格式
*/
public function searchdata(){
$con2=$this->consafecheck(I('get.'));
// $con2['sheetname']="申报表3T";
$con2['notfield']="wrpw,data1,data2,ord,rpw,name,pid,custom1,custom2";

$t=R('Rwxy/echounisheetuni',array(C('EXCELSECRETSHEET'),$con2,"",'arr'));
$r['code']='200';   
$r['getarr']=$this->consafecheck(I('get.'));
// $r['res']['listnum']=count($t); 
//用户相关信息返回
$r['userarr']['user']='2013014';   


emptyexit($r['code']);

//查询结果返回
$sheets_list=twoarray2onearr($t,'sheetname');
foreach($sheets_list as $k0=>$sheetname){
    $sttwoarr=twoarrayfindval($t,'sheetname',$sheetname);
    $r['res'][$k0]['sheetname']=$sheetname;
    $r['res'][$k0]['sheetlistnum']=count($sttwoarr); //输出表格数据长度
    
    
    //标题相关信息
    $titlearr=R("Queryfun/gettitlearr",array($sheetname,"",C('DATAFIELDSTR')));
    // pr($titlearr);
    $titleallarr=R("Queryfun/gettitlearr",array($sheetname));
    $r['res'][$k0]['sheetfieldlen']=0; 
    $r['res'][$k0]['sheetfieldname']=addarray($titlearr);
    $r['res'][$k0]['sheetfieldkey']=addarray($titlearr,"key");
    $r['res'][$k0]['sheetfieldlen']=count($r['res'][$k0]['sheetfieldname']); 
    // pr($sttwoarr,'$sttwoarr');
    $r['res'][$k0]['data']=twoarraygetcols($sttwoarr,implode(",",$r['res'][$k0]['sheetfieldkey']));

}


echo json_encode($r); 

}    

/* 
// 通用查询的各种输出形式
// $con2代表明确的条件，数组格式
// $likecon代表模糊查询，向量格式
*/
public function pindex(){
$r=$this->r_pindex(I('get.'),I('post.'));
echo json_encode($r);
}

public function r_pindex(){
    // pr($_SESSION);
    $r['code']='200';                     //200代表正常
    $r['getarr']=I('get.');
    $r['sheets']['sheetlistnum']="5";             //数据表的数量

    $r['sheets']['sheetarr'][0]['sheetname']="申报表3T";        //数据表名
    $r['sheets']['sheetarr'][0]['url']="/index.php/Qwadmin/ViCom/uniquerydata/sheetname/申报表3T";        //对应的网址
    $r['sheets']['sheetarr'][1]['sheetname']="经费使用测试";        //数据表名
    $r['sheets']['sheetarr'][1]['url']="/index.php/Qwadmin/ViCom/uniquerydata/sheetname/经费使用测试";        //对应的网址
    $r['sheets']['sheetarr'][2]['sheetname']="测试2";        //数据表名
    $r['sheets']['sheetarr'][2]['url']="/index.php/Qwadmin/ViCom/uniquerydata/sheetname/测试2";        //对应的网址
    $r['sheets']['sheetarr'][3]['sheetname']="测试3";        //数据表名
    $r['sheets']['sheetarr'][3]['url']="/index.php/Qwadmin/ViCom/uniquerydata/sheetname/测试3";        //对应的网址
    $r['sheets']['sheetarr'][4]['sheetname']="测试4";        //数据表名
    $r['sheets']['sheetarr'][4]['url']="/index.php/Qwadmin/ViCom/uniquerydata/sheetname/测试4";        //对应的网址

return $r;

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

public function test(){
    pr($_SESSION);
    pr($this->user);
}
public function returnrestful(){
    $r['code']='200';                     //200代表正常
    $r['getarr']=I('get.');
    $r['res']['listnum']="22";             //总输出的记录条数
    $r['res'][0]['sheetname']="申报表3T";
    $r['res'][0]['sheetlistnum']="3";       //输出的这个表的记录条数，不包括标题
    $r['res'][0]['sheetfieldlen']="4";  //输出的字段长度
    
    $r['res'][0]['sheetfieldname'][0]="id";
    $r['res'][0]['sheetfieldname'][1]="姓名";
    $r['res'][0]['sheetfieldname'][2]="电话";
    $r['res'][0]['sheetfieldname'][3]="手机号";
    // $r['res'][0]['sheetfieldtype'][0]="text";//暂时都只有文本
    // $r['res'][0]['sheetfieldtype'][1]="url";
    // $r['res'][0]['sheetfieldtype'][2]="phone";
    // $r['res'][0]['sheetfieldtype'][3]="text";    
    
    $r['res'][0]['data'][0]['url']="http://www.baidu.com";     //进入详情页的网址
    $r['res'][0]['data'][0][0]="1";
    $r['res'][0]['data'][0][1]="王一";
    $r['res'][0]['data'][0][2]="1111";
    $r['res'][0]['data'][0][3]="111112";
    
    $r['res'][0]['data'][1]['url']="http://www.sina.com.cn";
    $r['res'][0]['data'][1][0]="2";
    $r['res'][0]['data'][1][1]="赵二";
    $r['res'][0]['data'][1][2]="222222";
    $r['res'][0]['data'][1][3]="22222234";
    
    
    return $r;
    
}


// 查询结果
public function searchdata1(){

$con=$this->consafecheck(I('get.'));


$r=R('Rwxy/echounisheetuni',array($dbsheetname,$con2,$likecon,$type='arr'));
pr($r);


$db=M(C('EXCELSECRETSHEET'));
$r=$db->where($con)->limit(C('QUERYLIMIT'))->order('id asc')->select();
$rnum=$db->where($con)->count();
pr($rnum);
// 是否应用强制查询
if(empty($r) && $rnum > C('FORCEQUERYNUM')){
    $r=$this->forcequery($db,$con,$name);
    $rnum=count($r);
}

// delemptyfield
pr($r);
if(!empty($r)){
    $temp2['数据表名称']="信息摘要（点击查看详情）";


foreach ($r as $k1=> $value) {
    // pr($value);
    $id=$value['id'];
    $k=$k1+1;
    $temp5="";
    
    $ordconarr=json_decode($value['custom1'],'true');
    $weborderarr=explode(',',$ordconarr['weborder']);
    if(empty($weborderarr[0])){
        $temp5 .=$value['d1'].' | '.' '.$value['d2'].' | '.$value['d3'].' | '.$value['d4'].' | '.$value['d5'];
    }else{
        foreach($weborderarr as $k4=>$v4){
            $temp5 .= $value[$v4].' | ';
        }
    }
// pr($_SESSION,'756');    
    $temp2[$k.". ".$value['sheetname']]="<a href=\"".U(getcomstr('Vi',$thisuser)."/echoiddata?id=$id")."\">".$temp5."</a>";
    // pr($temp2);
}
}


$echohtml=echoarrcontent($temp2);
if(!empty($echohtml)){
    if($rnum>50 ){
    $temp9="(仅显示前50条)";}
    if(!empty($r)){
    $echohtml="<h3>共查询到".$rnum."条记录".$temp9."<h3>".$zy.$echohtml;}
}



 if($rnum <= 3 && $rnum > 0){
     foreach ($r as $k2=> $value2) {
         // pr($value2);
         $id=$value2['id'];
         $newarr1 =R("Queryfun/echoiddatacontent",array($id));
        //   pr($newarr1);
          $echohtml .=R("Task/echoarrcontent",array($newarr1));
         $echohtml .=echoarrcontent($newarr1);
     }  
     // "<h3>以下为详细信息（若结果小于三条）：</h3>".
     $echohtml =$echohtml;
 }
return $echohtml;
// $title='查询结果';
// $content="查询结果如下：\n".$temp;
// $content=R('Reply/returnmsg',array($echohtml,'web'));          
// return h5page($title,$content);

}

// con条件的安全性检查
public function consafecheck($getcon){
    return $getcon;
}

// 结尾处
}

