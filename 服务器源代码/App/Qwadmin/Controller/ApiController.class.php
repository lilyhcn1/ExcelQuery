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
// //com带权限
// define("LILYCOM",     "Com");
// use Qwadmin\Controller\ComController;
// class ApiComController extends ComController{    
// //无权限
define("LILYCOM",     "");
use Common\Controller\BaseController;
use Think\Controller;
class ApiController extends BaseController{    

//开始代码    
public function index(){
    $url=U($Think.CONTROLLER_NAME."/uniquerydata");
        header("Location: $url");
}

//开始代码    
public function test(){
    $message="你是谁？";
    $api_key = "sk-20b9ad19dc904559bad23193840d1d5a";
    $base_url = "https://dashscope.aliyuncs.com/compatible-mode/v1";    
    pr("111111111111111111111111");
    $r=qwen($message,$api_key,$base_url);
    pr($r);
}



// openai的一些代码  
public function openai($message=""){
    $api_key = "sk-4CrEZisZfN9QpAHp36F739F8102046Ab86124fB074Ad33Ab";
    $base_url = "https://api.gptapi.us/v1/";
    // $api_key = "sk-20b9ad19dc904559bad23193840d1d5a";
    // $base_url = "https://dashscope.aliyuncs.com/compatible-mode/v1";    
    
    
    $rpw="CGATY5L562,rwxy85137052,3ZH9GM3F2H,N26BK48ANY,G24G5I41JO";
try{
    /* 111111111获取当前rpw对应的sheetname */
    
    $url="/index.php/Qwadmin/Api/pindex/rpw/".$rpw;
    $temparr2=getnowsiteurl2arr($url);

    foreach ($temparr2["sheets"]["sheetarr"] as $key=>$arr) {
        $sheetarr[$key]=$arr["sheetname"];
    }
    // pr($sheetarr);
    $string = implode(";", $sheetarr);
    // pr($message,"传入的message1");
    $message=getParam("message",$message);
    // pr($message,"传入的message2");
    // $postmsg="王思源的运动卡片增加10张。";
    $postmsg=$message;
    
    $message = "你从以下的文字中挑选中最有可能分类，不用给出解释。分类：".$string."。文字为： ".$postmsg;
    //----------------------------------------------------------------    

    /* 获取对话最可能的sheetname */   
   
    echo $message . "\n";
    echo "----------------------------<br>";
    // $sheetname = openaichat($message, $api_key, $base_url);
    $sheetname="零花钱、运动卡片统计"; //临时改变
    echo $sheetname; 
    
    
    /* 获取sheetname 对应的 字段 */  
    $url2="/index.php/Qwadmin/Api/gettitle?sheetname=".$sheetname;
    $titlearr=getnowsiteurl2arr($url2);
    // pr($titlearr);
    $titlestr="";
    foreach ($titlearr["titlearr"] as $key=> $value) {
        if (substr($key, 0, 1) === 'd') {
           $titlestr.=$value."：;";
        }
    }
    // // pr($titlestr);
    // $enddata=$this->getsheetenddata($sheetname);
    // foreach ($enddata as $key=> $value) {
    //     $enddatastr .=$key. " " .$value. " ," ;
    // }


    /* 用AI获取对应的字段并生成json */   
    // $message= "信息1：".$enddatastr."。信息2：".$postmsg."请合并这两则信息，不需要给出解释，最后以可以解析的Json的形式返回。字符如果不确认，那就返回0或空字符串。键值如下，值均为数值。{".$titlestr."}";
    $message= "信息1：".$postmsg."。请解析，但不需要给出任何解释，最后以的Json的形式返回。字符如果不确认，那就返回0或空字符串。键值如下，值均为数值，时间请返回8位的yyyymmdd。{".$titlestr."}";
    // pr($message);
    // return 111;
    echo "----------------------------<br>";
    // $sheetname="222";
    $jsonstr = openaichat($message, $api_key, $base_url);
    // $jsonstr ='json { "时间": 20240727, "原因": "零花钱统计", "王思源零花钱收入": 0, "王思源零花钱支出": 0, "王思源零花钱合计": 172, "王思源运动卡片收入": 10, "王思源运动卡片支出": 0, "王思源运动卡片合计": 4, "王思思零花钱收入": 0, "王思思零花钱支出": 0, "王思思零花钱合计": 183, "王思思运动卡片收入": 2, "王思思运动卡片支出": 0, "王思思运动卡片合计": 2 } ';
    //$sheetname="零花钱、运动卡片统计"; //临时改变
    pr($jsonstr,'$jsonstr');
    return 111;
   $jsonarr=json_decode(extractLastJson($jsonstr),true);
   // 移除行内注释和单独一行的注释
    
   pr($jsonarr);
//   pr($jsonarr);
//   $updateurl=wholeurl("/index.php/Qwadmin/Queryfun/update/sheetname/零花钱、运动卡片统计");
//   pr($updateurl);
//   sendPostRequest($updateurl,$jsonarr);

    $id=R("Queryfun/update",array(0,$sheetname,$jsonarr));
    
    pr($id,"id");
    return "返回id：".$id;
} catch (Exception $e) {
// 处理异常
echo "捕获到异常: " . $e->getMessage();
}
    

}






//通过多种途径查询出对应的标题数组
// public function gettitlearr($sheetname,$id='',$fieldstr='',$delempty='true'){
public function gettitle($echojson="true"){
    
    $sheetname=$sheetname?$sheetname:I("get.sheetname");
    
    $titlearr=R('Queryfun/gettitlearr',array($sheetname,'',""));

    if($echojson=="arr"){
        return $titlearr;
    }elseif($echojson=="simplearr"){
        pr($titlearr);
        $bbb=json_decode($titlearr["custom1"],true);
        $titlearr=R("Queryfun/gettitlearr",array($sheetname,"",$bbb["weborder"]));
        pr($titlearr);
        return $titlearr;
    }elseif($echojson=="true"){

        $r['code']='200';                     //200代表正常
        $r['getarr']=I('get.');
        $r['titlearr']=$titlearr;
        $rrr=returnhttpjson($r,$echojson);
        return $rr;  
    }else{
        return "echojson 的参数错误！~";  
    }
}




/* 
// 获取表格的custom的参数
// $sheetname代表表格名称，字符串格式
// $custom代表 要查询的内容，字符串格式，为空时返回所有的字典
*/
public function gettcustom($sheetname="零花钱、运动卡片统计",$custom=""){
    
    $sheetname=$sheetname?$sheetname:I("get.sheetname");
    $titlearr=R('Queryfun/gettitlearr',array($sheetname,'',""));
    $bbb=json_decode($titlearr["custom1"],true);
    if($custom==""){
        return $bbb;
    }else{
        return $bbb[$custom];
    }
    
}


/* 
// 返回表格最后一条数据
// $sheetname代表表格名称，字符串格式
*/
public function getsheetenddata($sheetname="零花钱、运动卡片统计"){
    
        $db=M(C('EXCELSECRETSHEET'));
        $sheetname=$sheetname?$sheetname:I("get.sheetname");
        $querycon['sheetname']=$sheetname;

        // 把首行排除掉
        $firsrtlinearr=$db->where($querycon)->order('id asc')->limit(1)->distinct()->find();  
        $lastlinearr=$db->where($querycon)->order('id desc')->limit(1)->distinct()->find();  

        $weborder=$this->gettcustom($sheetname,"weborder");
        $weborderarr=explode(",",$weborder);
        // pr($weborderarr);
        foreach ($weborderarr as $key=> $value) {
            $enddataarr[$firsrtlinearr[$value]]=$lastlinearr[$value];
        }
        
        

    return $enddataarr;
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
// pr($con2,'con2');
$con2=arrurldecode($con2);
// pr($con2,'con222222');
$todelall=explode(',',C('NOTFIELDSTR'));        
$idsheet=explode(",","id,sheetname");
$ttt=array_diff($todelall,$idsheet);
// pr($ttt);
$con2['notfield']=implode(",",$ttt);


// pr($con2,'con24324');
$t=R('Rwxy'.LILYCOM.'/echounisheetuni',array(C('EXCELSECRETSHEET'),$con2,'','arr'));
// pr($t,'tt3424');


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
// pr($sheets_list,'$sheets_list');
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
    $titlearrkeystr=implode(addarray($titlearr,"key"),",");
    // pr($titlearrkeystr,'keys');
    
    $temp22=R("Queryfun/idfieldtype",array(I('get.id'),'',$titlearrkeystr));
    // pr($temp22,'temp22ddd2434');
    $r['res'][$k0]['sheetfieldtype']=addarray($temp22);
    $r['res'][$k0]['sheetfieldlen']=count($r['res'][$k0]['sheetfieldname']); 
    
    // pr($sttwoarr,'$sttwoarr122');
    
    $temp=twoarraygetcols($sttwoarr,"id,".implode(",",$r['res'][$k0]['sheetfieldkey']));
    if($echojson=='true'){
        $temp=$this->d1d2tojson($temp);
    }

    // pr($this->d1d2tojson($temp),'d1d2tojson');
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
    // pr($r);
    // pr(count($r['res']));
    // pr(count($r['res'][0]['sheetlistnum']));
    // pr($r['res'][0]);
    if(count($r['res'])>1 || count($r['res'][0]['sheetlistnum']) >2){
        unset($r);
        $r['code']='203';
    }
    
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
    $sheetlistnum=count($sheetnamearr)-1;
    if($sheetlistnum==-1){
        $sheetlistnum=0;
    }
    $r['sheets']['sheetlistnum']=$sheetlistnum;             //数据表的数量
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
    // pr($con);
    // $con=arrurldecode($arr);
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
    // pr($con);
    return delemptyfield($con);

}




//新增url
public function d1d2tojson($twoarr){
       
    foreach($twoarr as $key=>$arr){
        // $con2['id']=$arr['id'];
        $titlearr=R('Queryfun/gettitlearr',array("",$arr['id']));
        $queryfirst=delemptyfield($titlearr);
        foreach ($arr as $k=>$v) {
            if($k=="id"){
                $sttwoarr[$key][$k]=$arr[$k];
            }else{
                $sttwoarr[$key][$queryfirst[$k]]=$arr[$k];
            }
        }
        $sttwoarr[$key]=delemptyfield($sttwoarr[$key]);
    }
    return $sttwoarr;
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


