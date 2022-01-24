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
define("LILYCOM",     "Com");  //统一写com用的

// use Common\Controller\BaseController;
// use Think\Controller;
// class ViController extends BaseController{

namespace Qwadmin\Controller;
use Qwadmin\Controller\ComController;
class ViComController extends ComController{    

public function index(){
    $url=U($Think.CONTROLLER_NAME."/pindex");
        // $url=U($Think.CONTROLLER_NAME."/uniquerydata");
        header("Location: $url");
}






public function Rwxysercher_____________________(){
    
}



public function echoliststr($id=''){
$postarr=I('post.');
// pr1($postarr);

}

public function echoiddata(){

$newarr=R('Api'.LILYCOM.'/data',array($id));
// pr1($id);
// pr($newarr);
echo "<h3><a href=\"".$_SERVER["HTTP_REFERER"]."\">返回</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"."<a href=\"/\">查询首页</a></h3>";


// $echohtml=R('Task/echoarrresult',array($newarr,"信息详情页"));
$echohtml=echoarrresult($newarr,"信息详情页");
echo $echohtml;

return $echohtml;
    
    
}

public function echoiddata2col(){

$newarr=R('Api'.LILYCOM.'/data',array($id));
// pr1($id);
// pr($newarr);
echo "<h3><a href=\"".$_SERVER["HTTP_REFERER"]."\">返回</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"."<a href=\"/\">查询首页</a></h3>";

$content=echoarrcontent2col($newarr);
// echo $content;
// $echohtml=R('Task/echoarrresult',array($newarr,"信息详情页"));
$echohtml=h5page("信息详情页",$content);
echo $echohtml;

// return $echohtml;
    
    
}



public function pindex(){

$rr=R('Api'.LILYCOM.'/pindex',array('false'));
$sheetarr=$rr['sheets']['sheetarr'];
$this->assign("sheetarr",$sheetarr);
$sheetname=I('get.sheetname');
$this->assign("postpage",U("Vi".LILYCOM."/uniquerydata?sheetname=$sheetname"));



$this->display("Vi/pindex");


}






public function uniquerydata(){
// 列表部分都一样
$pp=R('Api'.LILYCOM.'/pindex','false');
$sheetarr=$pp['sheets']['sheetarr'];
$getarr=I('get.');
$name=I('get.name');
$sheetname=I('get.sheetname');

    $rr=R("Api".LILYCOM."/searchdata",array('false'));
// pr($res);
    $rrnum=count($rr['res']);
    if($rrnum==1 && $rr['res'][0]['sheetlistnum']==1){
        $id=$rr['res'][0]['data'][0]['id'];
        $url=U("Vi".LILYCOM."/echoiddata?id=$id");
        header("Location: $url");
    }
    
    $rr1=R("Api".LILYCOM.'/tiplist',array('false'));
    $tipliststr=$rr1['tipliststr'];
    $this->assign("tiplistarr",explode(",",$tipliststr));
// pr($name,'name');
// pr($sheetname,'$sheetname');
// pr(!empty($name) || !empty($sheetname),'非空计算');
if(!empty($getarr)){
    
    // pr("",'有关键词');
    // pr($rr);
    $this->assign("res",$rr['res']);    
}
    

$this->assign("sheetarr",$sheetarr);
$sheetname=I('get.sheetname');
$this->assign("postpage",U("Vi".LILYCOM."/uniquerydata?sheetname=$sheetname"));
$this->display("Vi/uniquerydata");


}


// 返回datalist
public function getdatalistarr($querycon){
$db=M(C('EXCELSECRETSHEET'));
$datalistarr=$db->where($querycon)->distinct(true)->field('name')->order('id')->limit(C('TIPNUM'))->select();
// pr1($datalistarr);
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
// pr1($classkeyarr);

// $temp=$db->where($querycon)->Field($value)->distinct('true')->select();
    // pr1($temp);
    
foreach($classkeyarr as $value){
    // pr1($key);
    // pr1($value);
    // $temp1=$db->where("sheetname=".$querycon['sheetname'])->Field($value)->order($value)->where('ord =0')->distinct('true')->select();
    // pr1($temp1);
    $temp=$db->where($querycon)->Field($value)->distinct('true')->order('id asc')->select();
    // $temp=$db->where($querycon)->Field($value)->distinct('true')->select();
    // $temp=twoarraymerge($temp1,$temp2);
    // pr1($temp);
    $tempcount=count($temp);
    // pr1(count( $temp));
    $fieldcon[$value]=$temp;
}
// pr1($fieldcon);


foreach($fieldcon as $key2=>$value2){

        $echohtml .="<p>";
        // pr1($value2);
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
        // pr1($queryconandid);
        $sheetnamearr=$db->where($queryconandid)->field('sheetname')->distinct(true)->order('id')->select();
        // pr1($sheetnamearr);
        $sheetstr=twoarraytostr ($sheetnamearr,'sheetname');

        $inforesult .= R('Queryfun/conquery',array($db,$queryconandid,"",$this->USER));
        if(!empty($sheetstr)){
             $inforesult="<h3><p>您在【".$sheetstr."】表中记录</p><h3>".$inforesult;
        } 
    }
        

    return $inforesult;
}


//通过代码生成
public function codeindex(){
    $db=M(C('EXCELSECRETSHEET'));
    $temp="";
    $concode['sheetname']="老黄牛首页代码";
// $concode['d1']="banner";

// $codevalue='参数13参数13参数13
// ';
// $aa=utf8_str_replace("参数13",  "111111111111111",$codevalue);
// pr($aa);

// $codearr=$db->where($concode)->field('d1,d2')->distinct(true)->order('id')->select();
// pr($codearr);
$sheetname=I("get.sheetname");
$con['sheetname']=$sheetname;

$indexarr=$db->where($con)->distinct(true)->order('id')->select();
// pr($indexarr);
foreach ($indexarr as $key=>$valarr){
// pr($key);
        $concode['d1']=$valarr['d1'];
        // pr($concode);
        $codearr=$db->where($concode)->field('d2')->find();
        // pr($codearr);
        $codevalue=$codearr["d2"];
        // echo "原代码：\n".$codevalue;
        for ($i=1; $i<=50; $i++){
            $t=$valarr["d".$i];
            // if(!empty($t)){
                // echo "-----------".$t;
                $xs="参数".str_pad($i,2,"0",STR_PAD_LEFT);   
                $codevalue=utf8_str_replace($xs, $t, $codevalue);
            // }
        }
        // echo "此时代码：\n".$codevalue;
        $temp=$temp.$codevalue;
}

echo $temp;
}




// 结尾处
}







