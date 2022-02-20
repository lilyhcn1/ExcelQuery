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
class RwxyComController extends ComController{    
// // //无权限
// define("LILYCOM",     "");
// use Common\Controller\BaseController;
// use Think\Controller;
// class RwxyController extends BaseController{    

public function index(){
    $url=U($Think.CONTROLLER_NAME."/uniquerydata");
        header("Location: $url");
}




public function excel___________() {
}
    


// 通用查询
public function echounisheet($dbsheetname,$data){
// C('EXCELSECRETSHEET');
$con2=R("Queryfun/constr2conarr",array($data,'eq'));
$likecon=R("Queryfun/constr2conarr",array($data,'like'));
// echo 343;pr($likecon);
// pr1($con2);
if($this->isadmin($con2)){
    unset($con2['rpw']);
    $this->echounisheetuni($dbsheetname,$con2,$likecon);
}elseif(!empty($likecon['sheetname']['0'] == 'in')){
    $this->echounisheetuni($dbsheetname,$con2,$likecon);
}elseif(empty($con2['sheetname']) || empty($con2['rpw'])){
    echo    $output="error, \nsheetname \n  or\n rpw\nis \nempty.\n";    
}else{

    $this->echounisheetuni($dbsheetname,$con2,$likecon);

}    


    
}



// 通用查询
public function echounisheetuni($dbsheetname,$con2,$likecon,$type='table'){
$db=M($dbsheetname);
    // pr1($con2,'43242');
    // pr1($likecon,'465654');

// 去除一些无关的条件
$con2=delearrfield($con2,'conall');
$con2=delearrfield($con2,'wrpw');  
$con2=delearrfield($con2,'user'); 
$likecon=delearrfield($likecon,'conall');
$likecon=delearrfield($likecon,'wrpw');  
$likecon=delearrfield($likecon,'user'); 






  
$ordstr=empty($con2['orderkey'])?"id":$con2['orderkey'];
// pr($ordstr,'$ordstr');
$isasc=($con2['isasc']=="否")?"desc":"asc";
$ordstr=$ordstr." ".$isasc;
// pr1($ordstr);
// 0. 读取第一行
    // $sheetcon['sheetname']=$con2['sheetname'];
    // $queryfirst=$db->where($sheetcon)->order('id')->find(); 
    // $queryfirst=$db->where($con2)->where($likecon)->order('id')->find(); 
    // $sheetname=empty($con2['sheetname'])?C('MLSHEETNAME'):$con2['sheetname'];
    $sheetname=R('Queryfun/getsheetname',array($con2,$likecon));
    $queryfirst=R('Queryfun/findfirstline',array($sheetname,true));
    // pr($sheetname,'sheetname');
    $queryfirst=delemptyfield($queryfirst);


// 1. 先把所有的字段都计算出来，除了wrpw
    $Model = new \Think\Model(); // 实例化一个model对象 没有对应任何数据表
    $field=$Model->query("select COLUMN_NAME from information_schema.COLUMNS where table_name ='".C('DB_PREFIX').$dbsheetname."' and table_schema = '".C('DB_NAME')."';");
// pr($field,'$field221');    
    $t1[]='wrpw';
    $field=array_column($field,'column_name');
    $field=array_diff($field,$t1);


// pr1($con2['field'],'11');
// 2. 先处理显示字段
    if(!empty($con2['field'])){
        $fieldarr=explode(',',$con2['field']);
         $field=array_intersect($fieldarr,$field);     
        if(empty($field)){
            $field[]='id';
        }  
         
    }else{
// 3. 不显字段处理
        if(!empty($con2['notfield'])){
            $todel=explode(',',$con2['notfield']);
        }else{
            $todel=explode(',',C('NOTFIELDSTR'));
        }
// pr($todel);
    // pr($todel);
        // pr($field);
        $field=array_diff($field,$todel);      
    // pr($field);
    // pr($queryfirst,'$queryfirst');
// 4. field中删除字段   
    foreach($field as $fkey=>$fvalue){ 
        if(!empty($queryfirst[$fvalue])){
            $newfieldarr[]=$fvalue;
        }
    } 
    $field=$newfieldarr;
// pr($field);        
    }
// pr($field,'$field');



// 5. 把字段写成str    
// if(!empty($con2['vlookup'])){
//     array_unshift($field,$con2['vlookup']);
// }

unset($con2['field']);
unset($con2['notfield']);
$fieldstr=implode($field,',');
if(!empty($fieldaddstr) && !empty($fieldstr)){
    $fieldstr=$fieldstr.$fieldaddstr;
}
// pr($fieldstr,'$fieldstr');

// 这里可能有问题，，，，，，，，，，，，，，，，，，，，，，，，，，
    if(empty($con2['id'])){
        if(!empty($queryfirst['id'])){
            $notfirstline['id']=array('NEQ',$queryfirst['id']);
        }else{
            $notfirstline['id']=array('NEQ',0);
        }
    }

    if(!empty($con2['id'])){
        $query=$db->where($con2)->where($notfirstline)->field($fieldstr)->order($ordstr)->select(); 
    }elseif(empty($likecon)){
        $query=$db->where($con2)->where($notfirstline)->field($fieldstr)->order($ordstr)->select(); 
    }else{
        $query=$db->where($con2)->where($likecon)->where($notfirstline)->field($fieldstr)->order($ordstr)->select(); 
    }



    // 插入字段行
    $fieldline['0']=$field;
// pr1($field,'$field');
    // 插入空行
    foreach ($field as $fieldkey) {
        $emptyline['0'][$fieldkey]="";
    }


if($type=='table'){
    if(!empty($queryfirst)){
        $sheetcon['sheetname']=$queryfirst['sheetname'];
        $firstlinearrtemp=$db->where($sheetcon)->field($fieldstr)->order('id')->find();
        $firstline['0']=$firstlinearrtemp;
    $temp=twoarraymerge($fieldline,$emptyline); 

    if(!empty($firstline)){
        $temp=twoarraymerge($temp,$firstline);  
    }
    $query=twoarraymerge($temp,$query);         
    }


    // // 输出结果
    $output=$this->simpletable($query); 
        if(count($query) < 1){
            echo    $output="error, \nnothing \nis \nfound3. \n";
        }else{
            echo $output;           
        }    
}elseif($type=='tablenoempty'){
    if(!empty($queryfirst)){
        $sheetcon['sheetname']=$queryfirst['sheetname'];
        $firstlinearrtemp=$db->where($sheetcon)->field($fieldstr)->order('id')->find();
        $firstline['0']=$firstlinearrtemp;
    // $temp=twoarraymerge($fieldline,$emptyline); 

    if(!empty($firstline)){
        $temp=twoarraymerge($temp,$firstline);  
    }
    $query=twoarraymerge($temp,$query);         
    }

// pr($query);
    // // 输出结果
    $output=$this->simpletable($query); 
        if(count($query) < 1){
            echo    $output="error, \nnothing \nis \nfound3. \n";
        }else{
            echo $output;           
        }  
}elseif($type=='openindex'){  //输出为打开文件夹

    if(!empty($queryfirst)){
        $sheetcon['sheetname']=$queryfirst['sheetname'];
        $firstlinearrtemp=$db->where($sheetcon)->field($fieldstr)->order('id')->find();
        $firstline['0']=$firstlinearrtemp;
    // $temp=twoarraymerge($fieldline,$emptyline); 

    if(!empty($firstline)){
        $temp=twoarraymerge($temp,$firstline);  
    }
    $query=twoarraymerge($temp,$query);         
    }

// pr($query);
    // // 输出结果
    $output=$this->simpletable($query,"true"); 
        if(count($query) < 1){
            echo    $output="error, \nnothing \nis \nfound3. \n";
        }else{
            echo $output;           
        }             
}elseif($type=='news'){      //新闻样式输出，写了一半
    if(!empty($queryfirst)){
        $sheetcon['sheetname']=$queryfirst['sheetname'];
        $rpw=$queryfirst['rpw'];
        $firstlinearrtemp=$db->where($sheetcon)->field($fieldstr)->order('id')->find();
        $firstline['0']=$firstlinearrtemp;
    // $temp=twoarraymerge($fieldline,$emptyline); 

    if(!empty($firstline)){
        $temp=twoarraymerge($temp,$firstline);  
    }
    $query=twoarraymerge($temp,$query);         
    }

// pr($query);
// pr($sheetname);
// pr($rpw);
    // // 输出结果
    $output=$this->simpletable($query,"news",$sheetname,$rpw); 
        if(count($query) < 1){
            echo    $output="error, \nnothing \nis \nfound3. \n";
        }else{
            echo $output;           
        }      
}elseif($type=='datajson'){    //只输出键值对的json，没有标题行
    if(!empty($queryfirst)){
        foreach($query as $kq=>$kv){
          foreach($kv as $k=>$v){
              $newquery[$kq][$queryfirst[$k]]=$kv[$k];
          }             
        }
    }
    // pr1($newquery);    
    return    $newquery;
}elseif($type=='json'){      //输出键值对的json
    if(!empty($queryfirst)){
        $sheetcon['sheetname']=$queryfirst['sheetname'];
        $firstlinearrtemp=$db->where($sheetcon)->field($fieldstr)->order('id')->find();
        $firstline['0']=$firstlinearrtemp;
    if(!empty($firstline)){
        $temp=twoarraymerge($temp,$firstline);  
    }
    $query=twoarraymerge($temp,$query);         
    }
        foreach($query as $kq=>$kv){
          foreach($kv as $k=>$v){
              $newquery[$kq][$queryfirst[$k]]=$kv[$k];
          }             
        }
    
    // pr($newquery);    
    return    $newquery;
}elseif($type=='tablejson'){
    if(!empty($queryfirst)){
        $sheetcon['sheetname']=$queryfirst['sheetname'];
        $firstlinearrtemp=$db->where($sheetcon)->field($fieldstr)->order('id')->find();
        $firstline['0']=$firstlinearrtemp;
        // pr1($firstlinearrtemp,'$firstlinearrtemp');
    // $temp=twoarraymerge($fieldline,$emptyline); 

    if(!empty($firstline)){
        $temp=twoarraymerge($temp,$firstline);  
    }
    $query=twoarraymerge($temp,$query);         
    }
    return    $query;
}elseif($type=='tubiaoxiu'){
    if(!empty($queryfirst)){
        $sheetcon['sheetname']=$queryfirst['sheetname'];
        $firstlinearrtemp=$db->where($sheetcon)->field($fieldstr)->order('id')->find();
        $firstline['0']=$firstlinearrtemp;
        // pr1($firstlinearrtemp,'$firstlinearrtemp');
    // $temp=twoarraymerge($fieldline,$emptyline); 

    if(!empty($firstline)){
        $temp=twoarraymerge($temp,$firstline);  
    }
    $query=twoarraymerge($temp,$query);         
    }
    // pr1($query);
    return    $query;
}elseif($type=='arr'){
// pr($query,'111');
    return    $query;
   
}else{
    
    return returnerror('2','echounisheetuni的type类型未指定!~');
}
     
}

    
//这里没有参数，但可以传入type参数
// table 就是输出表格
// tablenoempty 就是输出表格没有空行
// json 是key为标题，value为值
// tablejson 行+数据
// arr 只是返回数据
// tubiaoxiu,tablejson 是行标题+数据
// 查询数据结果的json数据，与echoteacherdb的结果是一致的，但显示方式不同
public function echojson(){
    $arrjson="";
    $data=I('get.');
    $type=I('get.type');
    $sheetname=I('get.sheetname');
    if(empty($data)){
        $data=I('post.');
    }
if(empty($type)){
    $type="json";
}

    $result = $this->Auth2Use();
    if(!$result){
        echo returnmsgjson('1','IP地址不在可访问列表中，禁止访问。');
        }else{
    
    // $sheetname=C('EXCELSECRETSHEET');
        $dbsheetname=C('EXCELSECRETSHEET');
        // C('EXCELSECRETSHEET');
        $con2=R("Queryfun/constr2conarr",array($data,'eq'));
        $likecon=R("Queryfun/constr2conarr",array($data,'like'));
    
        if($this->isadmin($con2)){
            unset($con2['rpw']);
            $r=$this->echounisheetuni($dbsheetname,$con2,$likecon,$type);
            
            $arrjson=returnmsgjson('0','正常返回json数据。',$r);
        }elseif(!empty($likecon['sheetname']['0'] == 'in')){
            $r=$this->echounisheetuni($dbsheetname,$con2,$likecon,$type);
            $arrjson=returnmsgjson('0','正常返回json数据。',$r);
            
        }elseif(empty($con2['sheetname']) || empty($con2['rpw'])){
             $arrjson=returnmsgjson('3','"error, \nsheetname \n  or\n rpw\nis \nempty.\n"; ');
        }else{
          $r=$this->echounisheetuni($dbsheetname,$con2,$likecon,$type);
            $arr["sheetname"]=$con2['sheetname'];
          $arrjson=returnmsgjson('0','正常返回json数据。',$r,$arr);
        }  
            
    }
echo $arrjson;

}

//只输出第一行
// type 参数  twotable为第一行两列输出,text 为第一行第一列输出
public function echoline1($type="twotable"){
$data=I('get.');
$url='http://'.$_SERVER['SERVER_NAME'].U("echojson","conall=".$data['conall']);
// pr($url,"url");
$data1 =curlurl($url);
$dataarr1=json_decode($data1,"false");
$firstarr=$dataarr1['arr']['0'];
// pr($firstarr,'$firstarr');
$out=echojsonalltypes($firstarr,$type);
echo $out;
    
}



//只输出第一列
// type 参数  twotable为第一行两列输出,text 为第一行第一列输出
public function echolist($type="twotablenokey",$num="6"){
$data=I('get.');
$url='http://'.$_SERVER['SERVER_NAME'].U("echojson","conall=".$data['conall']);
$data1 =curlurl($url);
$dataarr1=json_decode($data1,"false");
for ($i = 0; $i < $num; $i++) {
    $firstarrs[$i]=$dataarr1['arr'][$i];
}

if(isset($firstarrs[0])){
    $key=key($firstarrs[0]);
    $arr=array_column($firstarrs,$key);
}

$out=echojsonalltypes($arr,$type);
echo $out;
    
}




// 查询数据私有的数据表
public function echoteacherdbnep($type='tablenoempty'){
$data=I('get.');
if(empty($data)){
    $data=I('post.');
}
    $result = $this->Auth2Use();
    if(!$result){
        echo "error\n,IP地址\n不在可访问列表中，\n禁止访问。";
    }else{
    $dbsheetname=C('EXCELSECRETSHEET');
    // C('EXCELSECRETSHEET');
    $con2=R("Queryfun/constr2conarr",array($data,'eq'));
    $likecon=R("Queryfun/constr2conarr",array($data,'like'));
    if($this->isadmin($con2)){
        unset($con2['rpw']);
        $this->echounisheetuni($dbsheetname,$con2,$likecon,$type);
    }elseif(!empty($likecon['sheetname']['0'] == 'in')){
        $this->echounisheetuni($dbsheetname,$con2,$likecon,$type);
    }elseif(empty($con2['sheetname']) || empty($con2['rpw'])){
        echo    $output="error, \nsheetname \n  or\n rpw\nis \nempty.\n";    
    }else{
        $this->echounisheetuni($dbsheetname,$con2,$likecon,$type);
    }  
    }
}

// 查询数据私有的数据表
public function echoteacherdb($type='table'){
$data=I('get.');
// pr1($data,'$data11');
if(empty($data)){
    $data=I('post.');

}
// pr($data,"post 提交的数据");
    $result = $this->Auth2Use();
    if(!$result){
        echo "error\n,IP地址\n不在可访问列表中，\n禁止访问。";
    }else{

// $sheetname=C('EXCELSECRETSHEET');
    $dbsheetname=C('EXCELSECRETSHEET');
    // C('EXCELSECRETSHEET');
    $con2=R("Queryfun/constr2conarr",array($data,'eq'));
    $likecon=R("Queryfun/constr2conarr",array($data,'like'));
// pr($con2,'000000');
    if($this->isadmin($con2)){
        unset($con2['rpw']);
        $this->echounisheetuni($dbsheetname,$con2,$likecon);
    }elseif(!empty($likecon['sheetname']['0'] == 'in')){
        $this->echounisheetuni($dbsheetname,$con2,$likecon);
    }elseif(empty($con2['sheetname']) || empty($con2['rpw'])){
        echo    $output="error, \nsheetname \n  or\n rpw\nis \nempty.\n";    
    }else{

// echo 323;pr($likecon);
// pr1($con2);        
        $this->echounisheetuni($dbsheetname,$con2,$likecon);
    
    }  
        
    }


}
// 查询数据公开的数据表
public function echopubdb(){
$data=I('get.');
$sheetname=C('EXCELPUBSHEET');
$this->echounisheet($sheetname,$data);

}


function isadmin($con) {
    $flag=false;
    $admincon['user']=$con['user'];
    $admincon['password']=password($con['wrpw']);
    if(!empty($admincon['user']) && !empty($admincon['password']) ){
        $adminarr=M('member')->where($admincon)->find();
        if($adminarr['uid']==1){
            // 是管理员
            // unset($con['rpw']);
            $flag=ture;
        }
    }
    $admincon['user']=$con['user'];
    $admincon['password']=password($con['password']);
    if(!empty($admincon['user']) && !empty($admincon['password']) ){
        $adminarr=M('member')->where($admincon)->find();
        if($adminarr['uid']==1){
            // 是管理员
            // unset($con['rpw']);
            $flag=ture;
        }
    }    

    return $flag;
}





public function personaldata(){

$db=M(C('EXCELSECRETSHEET'));
$con['pid']=$this->USER['user'];
// pr1($this->USER);
$inforesult=R($Think.CONTROLLER_NAME."/conquery",array($db,$con));
$this->assign("inforesult",$inforesult);

    $this->display();    
}


public function echosheetname() {
// $DBNAME='rwxy';
// $DBSHEET='tzcdata';
$data=I('get.');



$db=M(C('EXCELSECRETSHEET'));
$con2=R("Queryfun/constr2conarr",array($data,'eq'));

    $query=$db->where($con2)->distinct(true)->field('sheetname')->order('id')->select();

    $output=$this->simpletable($query); 
        if(count($query)<1){
            echo    $output="error, \nnothing \nis \nfound2. \n";
        }else{
            echo $output;           
        }    
        
    
}




//   把特殊符号给删除了
public function deltextsymbol($text,$symbol="?"){
// echo mb_substr($text,0,1,"UTF-8");
// addlog($symbol,"sss-111");
    if(mb_substr($text,0,1,"UTF-8")==$symbol){
// addlog($symbol,"sss-2222");
        $newtext=mb_substr($text,1,NULL,"UTF-8");
        $newtext= $this->deltextsymbol($newtext);
        // echo 'second text is <hr>'.$newtext;
        return $newtext;
    }else{
        // pr1($text);
        return $text;
    }  
// str_replace("??","?",$text);
// str_replace(",?",",",$text);
//   return  str_replace(C('TEXTSYMBOL'),"",$text);
}   
 
//   把特殊符号给删除了
public function deltextsymboltwoarray($twoarr){
foreach($twoarr as $k1=>$v1){
    foreach($v1 as $k2=>$v2){
        $v2new=$this->deltextsymbol($v2,"?");
        $v2new=$this->deltextsymbol($v2new,C('TEXTSYMBOL'));
        $twoarrnew[$k1][$k2]=trim($v2new);
    }
}
return $twoarrnew;
}    


//   删除指定的数据表
public function delsheet($twoarr){
$data=I('post.');
// C('EXCELSECRETSHEET');
$con2=R("Queryfun/constr2conarr",array($data,'eq'));
$likecon=R("Queryfun/constr2conarr",array($data,'like'));

if($this->isadmin($con2)){
    unset($con2['rpw']);
    $this->echounisheetuni($dbsheetname,$con2,$likecon);
}elseif(!empty($likecon['sheetname']['0'] == 'in')){
    $this->echounisheetuni($dbsheetname,$con2,$likecon);
}elseif(empty($con2['sheetname']) || empty($con2['rpw'])){
    echo    $output="error, \nsheetname \n  or\n rpw\nis \nempty.\n";    
}else{

    $this->echounisheetuni($dbsheetname,$con2,$likecon);


}   
}  



public function u___________() {
}
    






// 这是数值
function isnum($val){
    if($val>100000000000 ){
        return true;
    }else{
        // pr1("非文本3");
        return false;         
    }
}



public function checkandprint($arr,$query,$haveright='0'){
// 检测权限 
$pw=$arr['pw'];
if(empty($haveright)){
    if($pw == C('PW')){  
       $haveright=True;
    }else{
       $haveright=False; 
    }      
}
  
// 输出结果
    if($haveright){
        // $output=$this->h5table($query); 
        $output=$this->simpletable($query); 
    }else{
        $output="error, \npassword \nis \nwrong. \n";
    }
    if(count($query)<1){
        $output="error, \nnothing \nis \nfound1. \n";
    }    
return $output;     
}


// 二维数组输出简单的表格
public function simpletable($data,$openindex="false",$sheetname="",$rpw=""){
     $temp1='
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"> 
       <meta name="viewport" content="initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">  
    <script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body><table class="table table-striped" style=""> <tbody>';
     $firstline='';
foreach ($data as $rows2) {
    foreach ($rows2 as $key2=>$value2) {
        $firstline=$firstline.'<th>'.$key2.'</b></th>';
    }
        if(!empty($firstline)){
            $firstline='<tr >'.$firstline.'</tr>';
            break;
        }
    
} 
// pr($firstline);

$textsymbol=C('TEXTSYMBOL');
    // pr1($firstline);

$temp2='';
if($openindex=="true"){
    foreach ($data as $rows) {
        $temp22='';
        $n=0;
        foreach ($rows as $key=>$value) {
            // $n++;
            
            $tablestyle=($n % 2 == 0)?'class="success"':'class="warning"';
            if($this->isnum($value) ){
                $temp22=$temp22
              .'<td style=\'padding:1px 5px;white-space: nowrap; overflow: hidden; font-size:14px;\'>'.$textsymbol.$value.'</td>';       
            }else{
                $temp22=$temp22
              .'<td style=\'padding:1px 5px;white-space: nowrap; overflow: hidden; font-size:14px;\'>'."<a href='cmd://".$value."'>".$value.'</a></td>';       
            }
        }
        $temp2=$temp2.'<tr>'.returnmsg($temp22,'excel').'</tr>';
    }
}if($openindex=="news"){
       
        $n=0;    
        foreach ($data as $rows) {
        $temp22='';
        foreach ($rows as $key=>$value) {

             
            $tablestyle=($n % 2 == 0)?'class="success"':'class="warning"';
            if($n==0){
                    $temp22=$temp22
                  .'<td style=\'padding:3px 5px;white-space: nowrap; overflow: hidden; font-size:14px;\'>'."<a href='/index.php/Qwadmin/Vi/uniquerydata/?sheetname=".$sheetname."' target='_blank'>".$value.'</a></td>'; 
            }else{
                if($this->isnum($value) ){
                    $temp22=$temp22
                  .'<td style=\'padding:3px 5px;white-space: nowrap; overflow: hidden; font-size:14px;\'>'.$textsymbol.$value.'</td>';       
                }else{
                    $temp22=$temp22
                  .'<td style=\'padding:3px 5px;white-space: nowrap; overflow: hidden; font-size:14px;\'>'."<a href='/index.php/Qwadmin/Vi/uniquerydata/sheetname/".$sheetname."/rpw/".$rpw."/name/".$value."' target='_blank'>".cutSubstr($value,20).'</a></td>';    
                //   pr($value);
                }
            }
            // pr($n."<br>".$temp22);
        }
        $temp2=$temp2.'<tr>'.returnmsg($temp22,'excel').'</tr>';
        $n++;
    }
}else{

    foreach ($data as $rows) {
        $temp22='';
        $n=0;
        foreach ($rows as $key=>$value) {
            $n++;
            // pr1($n);
            $tablestyle=($n % 2 == 0)?'class="success"':'class="warning"';
            if($this->isnum($value) ){
                $temp22=$temp22
              .'<td style=\'padding:3px 5px;white-space: nowrap; overflow: hidden; font-size:14px;\'>'.$textsymbol.$value.'</td>';       
            }else{
                $temp22=$temp22
              .'<td style=\'padding:3px 5px;white-space: nowrap; overflow: hidden; font-size:14px;\'>'.$value.'</td>';   
            }
        }
        $temp2=$temp2.'<tr>'.returnmsg($temp22,'excel').'</tr>';
    }
}






// pr1($temp3,'temp3');
// $temp3=returnmsg($temp3,'excel');
// pr1($temp3,'temp3');
$temp3='     </tbody>
</table>
</body>
</html>'
;

// 是不是要加第一行
// $temp2=$firstline.$temp2;
$temp=$temp1.$temp2.$temp3;


return  $temp;    
}


// 二维数组输出简单的表格
public function onlytable($data,$openindex="false",$sheetname=""){
$n=0;
$temp2="";
    foreach ($data as  $rows) {
    $temp22='';
    if($n==0){
        foreach ($rows as $key2=>$value2) {
            $temp22.="<th><a href='/index.php/Qwadmin/Vi/uniquerydata/sheetname/".$sheetname."'>".$value2.'</th>';
        }
    $temp1="<thead ><tr>".$temp22."</tr></thead ";
    }else{
       foreach($rows as $key=>$value) {
                    $temp22=$temp22
                  ."<td ><a href='/index.php/Qwadmin/Vi/uniquerydata/sheetname/".$sheetname."/name/".$value."'>".$value.'</a></td>';  
       }
       $temp2.="<tr>".$temp22."</tr>";
    }
    
    $n++;    
    }    
    return $temp1."<tbody>".$temp2."</tbody>";
}



     
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
                        // pr1($datatemp);
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
            $this->success('用户导入成功' . '，现在<span style="color:red">' . $num . '</span>条数据了！！');
        } else {
            $this->error('用户导入失败',30);
        }
    }  
 
// 判断是否有授权  
public function Auth2Use() {
    $ip = $_SERVER['REMOTE_ADDR'];
    $ip_config=json_decode(C('IPCONFIG'),true);
    $result = IpAuth($ip, $ip_config); 
    if($ip="::1"){
      $result=true;              
    }
    return $result;
}
    
    public function excelExport() {
        // $list = array(
        //     array('id' => '1', 'username' => "87423050@qq.com", 'password' => 'sucaihuo.com'),
        //     array('id' => '2', 'username' => '41614@qq.com', 'password' => 'hjl666666'),
        //     array('id' => '3', 'username' => 'zhangliao@163.com', 'password' => 'zhangqirui'),
        // );
          $query = "SHOW FULL COLUMNS FROM student";
  $result = mysql_query($query);
  while($row=mysql_fetch_assoc($result)){
 print_r($row);
        
    // $sheet=$_GET['sheet'];
    $sheet='teacherdata';
    $db = M($sheet,'think_','mysql://root:'.C('DB_PWD').'@localhost/rwxy#utf8');   
    $list=$db->order('id asc')->select();       
        // $list = M("member")->order("uid ASC")->select();
        $title = array('uid', '用户名', '昵称','密码'); //设置要导出excel的表头
        create_xls($list);
    }
}
 
// 供excel上传文件专用
public function phpuploadonefile($post="",$files="",$server="") {
$post=empty($_POST)?$post:$_POST;
$files=empty($_FILES)?$files:$_FILES;
$server=empty($_SERVER)?$server:$_SERVER;
// addlog(json_encode($post),'post34');
// addlog(json_encode($files),'$_FILES4353456');
// pr($post,'$post');
$nowfield=$post['nowfield'];
$ufilename=$post['ufilename'];

//如果有自定义几个字，那不改文件名
$aa=strstr($nowfield,C('ZDYUPLOADSYMBOL'));
if(!$aa){
    $ufilename="";
}

    $result = $this->Auth2Use();
    $returnfileuploadarr=savefile($ufilename);
// addlog(json_encode($returnfileuploadarr),'$returnfileuploadarr');    
    $returnurl=$returnfileuploadarr['file'];
    echo $returnurl;
    
   
}

// 准备弃用
// php
public function phpupload($post="",$files="",$server="") {
//     $_POST=$post;
// $_FILES=$files;
$post=empty($_POST)?$post:$_POST;
$files=empty($_FILES)?$files:$_FILES;
$server=empty($_SERVER)?$server:$_SERVER;
// pr($post);
// addlog(json_encode($post),'post34');
// addlog(json_encode($files),'$_FILES4353456');

    $result = $this->Auth2Use();

    if(!$result){
        echo "error\n,IP地址\n不在可访问列表中，\n禁止访问。";
    }else{
        $fileuploadtime="本文件上传时间是：".date("Y-m-d H:i:s");
        echo h5page('',$fileuploadtime);
 
if (($files["file"]["type"] == "application/vnd.ms-excel")
|| ($files["file"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
|| ($files["file"]["type"] == "application/octet-stream"))
{

  if ($files["file"]["error"] > 0)
    {
    echo "Return Code: " . $files["file"]["error"] . "<br />";
    }
  else
    {
    $path=$server["DOCUMENT_ROOT"] .'/Uploads/'. $files["file"]["name"];
    // pr1($path);
      move_uploaded_file($files["file"]["tmp_name"],$path);
    
    }

    // echo "Stored in: " .$path;


    $data=!empty($post)?$post:I('get.');
    
$this->dealuploadexcel($path,$data);

}
else
  {
$result='Invalid file';      
echo h5page('上传结果',$result);
 
  }

        
    }    

}





// 处理上传过来的数据
public function dealuploadexcel($filename,$data) {
    // pr1($data);
$DBNAME='rwxy';
$DBSHEET='tzcdata';
// $conall=$data['conall'];
$con2=R("Queryfun/constr2conarr",array($data,'eq'));
// pr1($con2,'$con2');
// pr1($data);
unset($con2['conall']);
$wrpw=$con2['wrpw'];
$rpw=$con2['rpw'];
$sheetname=$con2['sheetname'];
$namekey=$con2['namekey'];
$pidkey=$con2['pidkey'];

$classkey=$con2['classkey'];
$orderkey=$con2['orderkey'];
$replaceadd=$con2['replaceadd'];
$autotip=$con2['autotip'];
$notautotip=$con2['notautotip'];
$uploadfields=$con2['uploadfields'];

$rpw_right=$con2['rpw_right'];
$wrpw_right=$con2['wrpw_right'];


$con2temp=$con2;
unset($con2temp['wrpw']);unset($con2temp['rpw']);unset($con2temp['pidkey']);
unset($con2temp['sheetname']);unset($con2temp['notfield']);unset($con2temp['password']);
// pr1($wrpw,'$wrpw');
// pr1($sheetname,'$sheetname');
if(!empty($wrpw) && !empty($sheetname)){  //pw非空再说
    $filenameright3=substr($filename,-3);
    if($filenameright3=="lsx"){
       $datatwoarr=readOnlyExcel($filename);
    }else{
        $datatwoarr=readCSV($filename);
    }
//处理上传字段
if(!empty($uploadfields)){
    $twoarrexcel=arraygetkeys($twoarrexcel,$uploadfields);
}


// pr1($datatwoarr);
    foreach ($datatwoarr as $key => $dataarr) {
        if($key==0){
            $title=$dataarr;
        }else{
            foreach($dataarr as $key2=>$value2){
                $twoarrexcel[$key][$title[$key2]]=$value2;
            }
            $twoarrexcel[$key]['wrpw']=$wrpw;
           
            $twoarrexcel[$key]['sheetname']=$sheetname;
            $twoarrexcel[$key]['ord']=$key -1;
            $twoarrexcel[$key]['custom1']=json_encode($con2temp);
            if(!empty($con2['namekey'])){
                $tnamekey=$this->namekeysgetfirst($con2['namekey']);
                $twoarrexcel[$key]['name']=$twoarrexcel[$key][$tnamekey];
            }
            if(!empty($con2['pidkey'])){
                $twoarrexcel[$key]['pid']=$twoarrexcel[$key][$con2['pidkey']];
            }
            if(empty($twoarrexcel[$key]['rpw'])){
                 $twoarrexcel[$key]['rpw']=$rpw;
            }      
            //写入可读权限
            if(!empty($rpw_right)){
                 $twoarrexcel[$key]['r']=$twoarrexcel[$key][$rpw_right];
            }     
            //写入可写权限
            if(!empty($wrpw_right)){
                 $twoarrexcel[$key]['w']=$twoarrexcel[$key][$wrpw_right];
            }   
            //写入时间
            $twoarrexcel[$key]['t']=time();
            
        }
    }
    $twoarrexcel=delemptyfieldtwoarr($twoarrexcel);
    $twoarrexcel=$this->deltextsymboltwoarray($twoarrexcel);





    
// pr1($twoarrexcel,'$twoarrexcel1212');
    if($replaceadd=='否'){
        $result=$this->data2add($sheetname,$wrpw,$twoarrexcel);
    }else{
        $result=$this->deldata2add($sheetname,$wrpw,$twoarrexcel);
    }
    


    
    
}else{ $result="password  or sheetname is empty";}

$db=M(C('EXCELSECRETSHEET'));
$con23['sheetname']=$sheetname;
$num23=$db->where($con23)->count();$num23=$num23-1;
$result.="。 【".$sheetname."】现有".$num23."条数据。";
$phoneurl=U('Ad/addedit',"sheetname=".$sheetname,"",true);
$recurl11=U('Rwxy/echoteacherdbnep',"conall=;数据表名等于".$sheetname.";查看密码等于".$rpw."","",true);
$recurl12=U('Rwxy/echojson',"type=json&conall=;数据表名等于".$sheetname.";查看密码等于".$rpw."","",true);
$recurl13=U('Rwxy/echojson',"type=tablejson&conall=;数据表名等于".$sheetname.";查看密码等于".$rpw."","",true);
$recurl14=U('Rwxy/echolist',"conall=;数据表名等于".$sheetname.";查看密码等于".$rpw."","",true);
$recurl15=U('Rwxy/echoline1',"conall=;数据表名等于".$sheetname.";查看密码等于".$rpw."","",true);
$recurl16=U('Rwxy/echoline1',"type=text&conall=;数据表名等于".$sheetname.";查看密码等于".$rpw."","",true);
$recurl17=U('Vi/codeindex',"sheetname=".$sheetname,"",true);



$queryurl=U('Vi/uniquerydata',"rpw=".$rpw."&sheetname=".$sheetname,"",true);
$phonemodify=U('Ud/magrecords',"wrpw=".$wrpw."&sheetname=".$sheetname,"",true);


$phoneurl2=U('AdCom/addedit',"sheetname=".$sheetname,"",true);
$recurl21=U('RwxyCom/echoteacherdbnep',"conall=;数据表名等于".$sheetname.";查看密码等于".$rpw."","",true);
$recurl22=U('RwxyCom/echojson',"type=json&conall=;数据表名等于".$sheetname.";查看密码等于".$rpw."","",true);
$recurl23=U('RwxyCom/echojson',"type=tablejson&conall=;数据表名等于".$sheetname.";查看密码等于".$rpw."","",true);
$recurl24=U('RwxyCom/echolist',"conall=;数据表名等于".$sheetname.";查看密码等于".$rpw."","",true);
$recurl25=U('RwxyCom/echoline1',"conall=;数据表名等于".$sheetname.";查看密码等于".$rpw."","",true);
$recurl26=U('RwxyCom/echoline1',"type=text&conall=;数据表名等于".$sheetname.";查看密码等于".$rpw."","",true);
$recurl27=U('ViCom/codeindex',"sheetname=".$sheetname,"",true);

$queryurl2=U('ViCom/uniquerydata',"rpw=".$rpw."&sheetname=".$sheetname,"",true);
$phonemodify2=U('UdCom/magrecords',"wrpw=".$wrpw."&sheetname=".$sheetname,"",true);
// $queryurl=U(getcomstr('Vi').'/uniquerydata',"rpw=".$rpw."&sheetname=".$sheetname,"",true);

$urls=
"<hr><hr>"
    // ."<hr>"."<a href=\"".$phoneurl."\">".$phoneurl."</a>"
    // ."<hr>填表的网址为<hr>"."<a href=\"".shorturl($phoneurl)."\">".shorturl($phoneurl)."</a>"
    ."<hr>填表的网址为<hr>"."<a href=\"".$phoneurl."\">".$phoneurl."</a>"
    ."<hr><br>-------------以下为保密区，请注意保密。-----------------------------------<br>"
    ."<hr>"."<a title='所有数据输出' href=\"".$recurl11."\">"."您的所有数据网址为（请保密）"."</a>"."|"
    ."<a title='键值对方式输出' href=\"".$recurl12."\">"." 格式二"."</a>"
    ."<a title='表格行json，图表秀' href=\"".$recurl13."\">"." 格式三"."</a>"
    ."<a title='只第一列输出' href=\"".$recurl14."\">"." 格式四"."</a>"
    ."<a title='只第一行输出' href=\"".$recurl15."\">"." 格式五"."</a>"
    ."<a title='只第一个值输出' href=\"".$recurl16."\">"." 格式六"."</a>"
    ."<a title='导航' href=\"".$recurl17."\">"." 导航格式"."</a>"
    ."<hr>"."<a href=\"".$queryurl."\">"."您的免密码查询网址为（请保密）"."</a>"
    ."<hr>"."<a href=\"".$phonemodify."\">"."您的在线修改网址为（请保密）"."</a>"
    ."<hr><br>-------------以下网址必须先登陆才能访问，请注意保密。-----------------------------------<br>"
    ."<hr>"."<a href=\"".$phoneurl2."\">"."您的在线填表网址为"."</a>"
    ."<hr>"."<a title='所有数据输出' href=\"".$recurl21."\">"."您的所有数据网址为（请保密）"."</a>"."|"
    ."<a title='键值对方式输出' href=\"".$recurl22."\">"." 格式二"."</a>"
    ."<a title='表格行json，图表秀' href=\"".$recurl23."\">"." 格式三"."</a>"
    ."<a title='只第一列输出' href=\"".$recurl24."\">"." 格式四"."</a>"
    ."<a title='只第一行输出' href=\"".$recurl25."\">"." 格式五"."</a>"
    ."<a title='只第一个值输出' href=\"".$recurl26."\">"." 格式六"."</a>"
    ."<a title='导航' href=\"".$recurl27."\">"." 导航格式"."</a>"
    
    ."<hr>"."<a href=\"".$queryurl2."\">"."您的免密码查询网址为（请保密）"."</a>"
    ."<hr>"."<a href=\"".$phonemodify2."\">"."您的在线修改网址为（请保密）"."</a>"    
   
    ;
$result.=$urls;
$resultweb=h5page('上传结果',$result);
echo $resultweb;

}


public function deldata2add($sheetname,$wrpw,$twoarrexcel) {

$db=M(C('EXCELSECRETSHEET'));

// 密码与先有的一样才行
$existdatacon['sheetname']=$sheetname;
$existdata=$db->where($existdatacon)->order('id')->find();
// pr1($twoarrexcel[2]);
// pr1("实际密码是".$existdata['2']['wrpw']."输入密码".$wrpw);
if($existdata['wrpw']==$wrpw || empty($existdata)){
// 把数据表中的数据删了
        if(isset($existdata['sheetname']) ){ //第0行数据库字段名，第1行中文字段名
            $firstupload="暂时这样填";
            // 第一次上传就删除所有数据
            if($firstupload){
                $delcon['sheetname']=$sheetname;
                $delcon['wrpw']=$wrpw;
                $db->where($delcon)->delete();                
            }
        }else{ $result='sheetname is empty2. or uplosanum';}
        $result=$this->dbadddata($twoarrexcel);
    }else{ $result='error,password is wrong, or exist other same sheetname.';} 
return $result;
}


//   新增数据表，不覆盖原有数据
public function data2add($sheetname,$wrpw,$twoarrexcel) {
$twoarrexcel=deltwoarryfirstline($twoarrexcel);
$result=$this->dbadddata($twoarrexcel);
return $result;
}


public function dbadddata($datatwoarr) {
$db=M(C('EXCELSECRETSHEET'));

// // 先确认导入的字段
$newcout=0;
$newfailcout=0;
foreach($datatwoarr as $key=>$dataarr){
    $new1=$db->add($dataarr); 
    if($new1>0){
      $newcout++; 
    }else{
      $newfailcout++; 
    }
    
}
$newcout=$newcout-1;

// $existdatacount=$db->where($existdatacon)->order('id')->count();
// pr1($existdatacount,'写入后的数据量为');

$result='用户成功清空原有数据，并导入' . '<span style="color:red">' . $newcout . "</span>条数据了！"."，其中失败".$newfailcout."条";
return $result;
}

public function namekeysgetfirst($namekeys) {
    $namekeyarr=explode(",",$namekeys);
    if(count($namekeyarr)>1){
        $namekey=$namekeyarr[0];
    }else{
        $namekey=$namekeys;
    }
    return $namekey;
}




// 结尾处
}