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
class RwxyComController extends ComController{
public function index(){
    $url=U($Think.CONTROLLER_NAME."/uniquerydata");
        header("Location: $url");
}

function forcequery($db,$con,$rev){
    // pr($con);
    $rev=$con['name'];
    $rpw=$con['rpw'];
    $forcecon['rpw']=$rpw;
    $forcecon['d1|d2|d3|d4|d5|d6|d7|d8|d9|d10|d11|d12|d13|d14|d15|d16|d17|d18|d19|d20|d21|d22|d23|d24|d25|d26|d27|d28|d29|d30|d31|d32|d33|d34|d35|d36|d37|d38|d39|d40|d41|d42|d43|d44|d45|d46|d47|d48|d49|d50']=array('like',"%".$rev."%");
    // pr($forcecon);
    if($rev && $rpw){
        $forceresulttwoarr=$db->where($forcecon)->select();
        // pr($forceresulttwoarr);
    }
    return $forceresulttwoarr;
}


// 查询结果
public function conquery($db,$con,$name=""){
// $firstlinearr=$db->where($con)->find();
// $ordconarr=json_decode($firstlinearr['custom1'],'true');
// $weborderarr=explode(',',$ordconarr['weborder']);

// pr($weborderarr);
// pr($con);
$r=$db->where($con)->limit(C('QUERYLIMIT'))->order('id asc')->select();
$rnum=$db->where($con)->count();
if(empty($r)){
    $r=$this->forcequery($db,$con,$name);
    $rnum=count($r);
}
// pr($r);
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
    $temp2[$k.". ".$value['sheetname']]="<a href=\"".U($Think.CONTROLLER_NAME."/echoiddata?id=$id")."\">".$temp5."</a>";
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
         $newarr1 =R($Think.CONTROLLER_NAME."/echoiddatacontent",array($id));
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

public function echoiddatacontent($id=''){
// echo "fds";pr($id);
if(empty($id)){
    return '请输入id';
}else{
$con2['id']=$id;
// pr($con2);


$db=M(C('EXCELSECRETSHEET'));

$fieldstr=C('FIELDSTR');
$arr=$db->where($con2)->find();    
// $arr=$db->where($con2)->Field($fieldstr)->find();  
// pr($arr['sheetname']);

    // 查出第一行
    $firstline=$this->findfirstline($arr['sheetname']);


$arr=delemptyfield($arr);
// pr($arr);
// pr($firstline);
foreach ($arr as $key=> $value) {
// $value=returnmsg($value,'weixin');
if(!is_null($firstline[$key])){
    // echo '432432423';pr($value);
    if($this->isphone($value) ){
        $newarr[$firstline[$key]]="<a href=\"tel:$value\">".'<span class="glyphicon glyphicon-earphone"></span>'.$value."</a>";  
    }elseif($this->isurl($value)){
        // pr('22222'.$value);
        $newarr[$firstline[$key]]=autolink($value);
    }elseif(mb_strlen($value)<20){
        // pr('333333'.$value);
        if(!empty($value)){
            $newarr[$firstline[$key]]="<a href=\"/index.php/Qwadmin/".$Think.CONTROLLER_NAME."/uniquerydata.html?$key=$value\">".'<span class="glyphicon glyphicon-search"></span>'.$value."</a>";
        }        //	glyphicon glyphicon-search
    }else{
        if(!empty($value) ){
            $newarr[$firstline[$key]]=$value;
        }
    }    
}    
    
// pr($newarr);
    
}

}
return $newarr;
}

public function echoliststr($id=''){
$postarr=I('post.');
pr($postarr);

}

public function echoiddata($id=''){
if(empty($id)){
    $id=I('get.id');}

$newarr=$this->echoiddatacontent($id);

// pr($id);
// pr($newarr);
echo "<h3><a href=\"".$_SERVER["HTTP_REFERER"]."\">返回</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"."<a href=\"".session('indexpage')."\">查询首页</a></h3>";


// $echohtml=R('Task/echoarrresult',array($newarr,"信息详情页"));
$echohtml=echoarrresult($newarr,"信息详情页");
echo $echohtml;

return $echohtml;
    
    
}




public function uniquerydata(){
// session(null);
$db=M(C('EXCELSECRETSHEET'));
// pr(I('get.'));
$name=I('get.name');
$sheetname=I('get.sheetname');
$querycon=I('get.');
$querycon=delemptyfield($querycon);


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
$datalistarr=$db->where($querycon)->distinct(true)->field('name')->order('id')->select();
$datalistonearr=array_column($datalistarr,'name');
$datalistonearr=delemptyfieldgetnew($datalistonearr);
// pr($datalistonearr);
// $datalistjson=json_encode($datalistonearr);
// addlog($datalistjson);
    foreach($datalistonearr as $key11=>$value11){
        if(!empty($value11)){
            $newarr[$key11]=preg_replace( '/[\x00-\x1F]/','',$value11);
        }
    }
$datalistonearr=$newarr;
// pr($datalistonearr);

if(!empty($name)){
    unset($querycon['name']);
    $querycon['name']=$name;
    // pr($con);
}

$querycontemp=$querycon;
unset($querycontemp['rpw']);

if(empty($querycontemp)){
    $inforesult="<h3><p>您能查询的数据表：</p><h3>";
    foreach($sheetnamearr as $sheetvaluearr){
        $sheetvalue=$sheetvaluearr['sheetname'];
        $inforesult.="<p> <a href=\"" . U($Think.CONTROLLER_NAME."/uniquerydata?sheetname=$sheetvalue") . "\">$sheetvalue</a></p>";
        
        // $inforesult.="<hr>";
    }
       
    $inforesult .=$this->querypersoninfo();

}else{
    
    $inforesult=$this->echofieldcon($db,$querycon);
    // 查数据表
    // echo "222222222222222222222";
    $inforesult .= $this->conquery($db,$querycon,$name);
}

    
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




// 查出数据表名为sheetname,的第一行，返回一维数组
function findfirstline($sheetname){
    $db=M(C('EXCELSECRETSHEET'));
    // 查出第一行
        $sheetcon['sheetname']=$sheetname;
        // $firstlinearrtemp=$db->where($sheetcon)->order('id')->find();
        // // pr($firstlinearrtemp);
        // $firstcon['id']=array(array("eq",$firstlinearrtemp['id']-1),array("eq",$firstlinearrtemp['id']),"OR");
        // $firstcon['ord']=0;
        $firstline=$db->where($sheetcon)->Field(C('FIELDSTR'))->order('id asc')->find();  
    return $firstline;
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

        $inforesult .= $this->conquery($db,$queryconandid,"");
        if(!empty($sheetstr)){
             $inforesult="<h3><p>您在【".$sheetstr."】数据表中的个人记录</p><h3>".$inforesult;
        } 
    }
        

    return $inforesult;
}





// 这是数值
function isphone($value){
    if(($value>600 && $value < 900 ) ||($value>500000 && $value < 699999 ) || ($value>13000000000 && $value < 19000000000 ) || ($value>10000000 && $value < 100000000 )){
        return true;
    }else{
        // pr("非文本3");
        return false;         
    }
}
// 里面包括网址
function isurl($val){
    if(strstr($val,'http')){
        return true;
    }else{
        return false;         
    }
}








    


// 结尾处
}
