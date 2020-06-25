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
class ViController extends BaseController{

//namespace Qwadmin\Controller;
//use Qwadmin\Controller\ComController;
//class ViComController extends ComController{    

public function index(){
    $url=U($Think.CONTROLLER_NAME."/uniquerydata");
        header("Location: $url");
}






public function Rwxysercher_____________________(){
    
}



public function echoliststr($id=''){
$postarr=I('post.');
// pr($postarr);

}

public function echoiddata(){

$newarr=R('ApiCom/data',array($id));
// pr($id);
// pr($newarr);
echo "<h3><a href=\"".$_SERVER["HTTP_REFERER"]."\">返回</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"."<a href=\"/\">查询首页</a></h3>";


// $echohtml=R('Task/echoarrresult',array($newarr,"信息详情页"));
$echohtml=echoarrresult($newarr,"信息详情页");
echo $echohtml;

return $echohtml;
    
    
}

public function pindex(){

// $url=getwholeurl(U('ApiCom/pindex',array('false')));
// $r=file_get_contents($url);
// pr($r);
// $rr=json_decode($r,true);
// pr($rr);


$rr=R('ApiCom/pindex',array('false'));
$sheetarr=$rr['sheets']['sheetarr'];
$this->assign("sheetarr",$sheetarr);
$sheetname=I('get.sheetname');
$this->assign("postpage",U("ViCom/uniquerydata?sheetname=$sheetname"));



$this->display();


}






public function uniquerydata(){
// 列表部分都一样
$rr=R('ApiCom/pindex','false');
$sheetarr=$rr['sheets']['sheetarr'];
$this->assign("sheetarr",$sheetarr);


// $name=I('get.name');
// $sheetname=I('get.sheetname');

// $rr=R("ApiCom/searchdata"."?name=".$name."&sheetname=".$sheetname,'false');
$rr=R("ApiCom/searchdata",array('false'));
// pr($rr);
$this->assign("res",$rr['res']);


$rrnum=count($rr['res']);
if($rrnum==1 && $rr['res'][0]['sheetlistnum']==1){
    $id=$rr['res'][0]['data'][0]['id'];
    $url=U("ViCom/echoiddata?id=$id");
    header("Location: $url");
    
}

$rr1=R('ApiCom/tiplist',array('false'));
$tipliststr=$rr1['tipliststr'];
$this->assign("tiplistarr",explode(",",$tipliststr));


$sheetname=I('get.sheetname');
$this->assign("postpage",U("ViCom/uniquerydata?sheetname=$sheetname"));
$this->display();


}

public function uniquerydatabak(){
// session(null);
$db=M(C('EXCELSECRETSHEET'));
// pr(I('get.'));
$name=I('get.name');
$sheetname=I('get.sheetname');
$querycon=I('get.');
$querycon=delemptyfield($querycon);

if(!empty($sheetname)){
    $titlearr=R("Queryfun/gettitlearr",array($sheetname));
    $custom1arr=json_decode($titlearr['custom1'],true);
    $namekeys=$custom1arr['namekey'];
}
if(empty($namekeys)){
    $namekeys="name";
}


    if(!empty($querycon['rpw'])){
        $temp=$querycon['rpw'];
        session('rpw',$temp);
    }

$user_querypw=$this->USER['querypw'];
// pr($user_querypw);
if(empty($user_querypw)){
    if(!empty(session('rpw'))){
        $querycon['rpw']=$_SESSION['rpw'];
        $user_querypw=$querycon['rpw'];
    }elseif(empty($querycon['rpw'])){
        $querycon['rpw']=C("QUERYPW");
        $user_querypw=$querycon['rpw'];
    }
    else{
        
    }
//   pr($querycon);  
}
    $user_querypw=str_replace(";",",",$user_querypw);
    $user_querypw=str_replace("，",",",$user_querypw);
    $querycon['rpw']=array("in",$user_querypw);

// pr($con);




$sheetnamearr=$db->where($querycon)->distinct(true)->field('sheetname')->order('id')->select();
$datalistarr=$db->where($querycon)->distinct(true)->field($namekeys)->order('id')->limit(C('TIPNUM'))->select();
// pr($datalistarr);
$datalistonestr=twoarraycolstostr($datalistarr,$namekeys);
// pr($datalistonestr);
$datalistonearr=explode(",",$datalistonestr);

$namecon=str_replace(",","|",$namekeys);
if(!empty($name)){
    unset($querycon['name']);
    $querycon[$namecon]=$name;
    // pr($con);
}

$querycontemp=$querycon;
unset($querycontemp['rpw']);

// pr($querycontemp);

if(empty($querycontemp)){
    $inforesult="<h3><p>您能查询的数据表：</p><h3>";
    foreach($sheetnamearr as $sheetvaluearr){
        $sheetvalue=$sheetvaluearr['sheetname'];
        $inforesult.="<p> <a href=\"" . U($Think.CONTROLLER_NAME."/uniquerydata?sheetname=$sheetvalue") . "\">$sheetvalue</a></p>";
        
    }
       
    $inforesult .=$this->querypersoninfo();

}else{
    
    $inforesult=$this->echofieldcon($db,$querycon);
    // 查数据表
    $inforesult .= R('Queryfun/conquery',array($db,$querycon,$name,$this->USER));

}
// pr($inforesult);

    
// pr($datalistarr);
    $this->assign("slectsheet",$sheetname);
    $this->assign("datalistonearr",$datalistonearr);
    
    $this->assign("sheetnamearr",$sheetnamearr);
    $this->assign("inforesult",$inforesult);
    $this->assign("sheetarr",$sheetarr);


// 计算首页


    $temp=session('rpw');
        // pr($_SESSION);
    if(empty($this->USER['user'])){
        $indexpage=U($Think.CONTROLLER_NAME."/uniquerydata",array('rpw'=>$temp));

    }else{
        $indexpage=U('index/index');
    }
    session('indexpage',$indexpage);
    
    $this->assign("indexpage",$indexpage);
    $this->assign("postpage",U($Think.CONTROLLER_NAME.'/uniquerydata'));

    $this->display();    
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




// 智能显示字段或者数据表分类
public function echofieldcon($db,$querycon){
$firstlinearr=$db->where($querycon)->find();
$ordconarr=json_decode($firstlinearr['custom1'],'true');
$classkeyarr=explode(',',$ordconarr['classkey']);    
// pr($classkeyarr);

// $temp=$db->where($querycon)->Field($value)->distinct('true')->select();
    // pr($temp);
    
foreach($classkeyarr as $value){
    // pr($key);
    // pr($value);
    // $temp1=$db->where("sheetname=".$querycon['sheetname'])->Field($value)->order($value)->where('ord =0')->distinct('true')->select();
    // pr($temp1);
    $temp=$db->where($querycon)->Field($value)->distinct('true')->order('id asc')->select();
    // $temp=$db->where($querycon)->Field($value)->distinct('true')->select();
    // $temp=twoarraymerge($temp1,$temp2);
    // pr($temp);
    $tempcount=count($temp);
    // pr(count( $temp));
    $fieldcon[$value]=$temp;
}
// pr($fieldcon);


foreach($fieldcon as $key2=>$value2){

        $echohtml .="<p>";
        // pr($value2);
        foreach($value2 as $key =>$value){
            if(!empty($value[$key2])){
                
                // 
                $getconarr=I('get.');
                $getstr="";
                foreach($getconarr as $getkey=>$getvalue){
                    $getstr .="$getkey=$getvalue&";
                }
                
                 $echohtml .="<a href=\"" . U($Think.CONTROLLER_NAME."/uniquerydata?$getstr$key2=".$value[$key2]) . "\">$value[$key2]</a> | ";
            }
            
        }
        $echohtml .="</p>";
    
}
    

$echohtml=str_replace("| </p>","</p>",$echohtml);

if(strlen($echohtml) < 11){
    // $echohtml="<h3>暂无 智能字段分类。<h3>";
}else{
    $echohtml="<h3>分类查询：</h3>"."".$echohtml."";
}

return $echohtml;


}





// 查询对应的个人信息
function querypersoninfo(){
    $db=M(C('EXCELSECRETSHEET'));
    if($this->USER['user']){
       $queryconandid['pid']=$this->USER['user'];
        $queryconandid['rpw']=C("PERSONPW");
        // pr($queryconandid);
        $sheetnamearr=$db->where($queryconandid)->field('sheetname')->distinct(true)->order('id')->select();
        // pr($sheetnamearr);
        $sheetstr=twoarraytostr ($sheetnamearr,'sheetname');

        $inforesult .= R('Queryfun/conquery',array($db,$queryconandid,"",$this->USER));
        if(!empty($sheetstr)){
             $inforesult="<h3><p>您在【".$sheetstr."】表中记录</p><h3>".$inforesult;
        } 
    }
        

    return $inforesult;
}






// 结尾处
}
