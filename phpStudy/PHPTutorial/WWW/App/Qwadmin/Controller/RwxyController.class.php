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
// namespace Qwadmin\Controller;
// use Think\Controller;
// class RwxyController extends Controller{
namespace Qwadmin\Controller;
use Common\Controller\BaseController;
use Think\Controller;

class RwxyController extends BaseController{
public function index(){
    // addlog('rwxy/index'.json_encode(I('get.')));
    $url=U('RwxyCom/uniquerydata');
        	header("Location: $url");
}

function forcequery($db,$con,$rev){
    // pr($con);
    $rev=$con['name|pid'];
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
$firstlinearr=$db->where($con)->find();
$ordconarr=json_decode($firstlinearr['custom1'],'true');
$weborderarr=explode(',',$ordconarr['weborder']);

// pr($weborderarr);
// pr($con);
$r=$db->where($con)->limit(C('QUERYLIMIT'))->order('id asc')->select();
$rnum=$db->where($con)->count();
if(empty($r)){
    $r=$this->forcequery($db,$con,$name);
    $forcenum=count($r);
}
// pr($r);
if(!empty($r)){
    $temp2['数据表名称']="信息摘要（点击查看详情）";


foreach ($r as $k1=> $value) {
    $id=$value['id'];
    $k=$k1+1;
    $temp5="";
    if(empty($weborderarr[0])){
        $temp5 .=' '.$value['d5'].' '.$value['d2'].' '.$value['d3'].' '.$value['d4'];
    }else{
        foreach($weborderarr as $k4=>$v4){
            $temp5 .= $value[$v4]." ";
        }
    }
    $temp2[$k.". ".$value['sheetname']]="<a href=\"".U($Think.CONTROLLER_NAME."/echoiddata?id=$id")."\">".$temp5."</a>";
    // pr($temp2);
}
}


$echohtml=echoarrcontent($temp2);
if(!empty($echohtml)){
    if($rnum>50){
    $temp9="(仅显示前50条)";
    $echohtml="<h3>共查询到".$rnum."条记录".$temp9."<h3>".$zy.$echohtml;
    }elseif($rnum == 0){
        $echohtml="<h3>未查询记录，模糊搜索到".$forcenum."条记录<h3>".$echohtml;
    }else{
        $echohtml="<h3>共查询到".$rnum."条记录<h3>".$echohtml;
    
    }
    
    
}


$echohtml.="<h3>以下为详细信息（若结果小于三条）：</h3>";
if(($rnum <= 3 && $rnum!=0) || ($forcenum!=0 && $forcenum <=3)){
    foreach ($r as $k2=> $value2) {
        // pr($value2);
        $id=$value2['id'];
        $newarr1 =R($Think.CONTROLLER_NAME."/echoiddatacontent",array($id));
        // pr($newarr1);
        // $echohtml .=R("Task/echoarrcontent",array($newarr1));
        $echohtml .=echoarrcontent($newarr1);
    }    
}
return $echohtml;
// $title='查询结果';
// $content="查询结果如下：\n".$temp;
// $content=R('Reply/returnmsg',array($echohtml,'web'));          
// return h5page($title,$content);

}

public function echoiddatacontent($id=''){

if(empty($id)){
    return '请输入id';
}else{
$con2['id']=$id;
// pr($con2);


$db=M(C('EXCELSECRETSHEET'));

$fieldstr=C('FIELDSTR');
$arr=$db->where($con2)->find();    
// $arr=$db->where($con2)->Field($fieldstr)->find();  
// pr($arr);

    // 查出第一行
    $firstline=$this->findfirstline($arr['sheetname']);


$arr=delemptyfield($arr);

foreach ($arr as $key=> $value) {
    // pr($key);
    if($value>100 && is_numeric($value) && !is_null($firstline[$key])){
        $newarr[$firstline[$key]]="<a href=\"tel:$value\">".$value."</a>";  
    }else{
        // $newarr[$firstline[$key]]=$value;  
        if(!empty($value) && !is_null($firstline[$key])){
            $newarr[$firstline[$key]]="<a href=\"/index.php/Qwadmin/".$Think.CONTROLLER_NAME."/uniquerydata.html?$key=$value\">".$value."</a>";
        }
           
    }
    // pr(!empty($value) && !is_null($firstline[$key]));
    // pr($newarr);

// pr($firstline[$key]);
    
}
// pr($newarr);
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


// pr($newarr);
echo "<a href=\"".$_SERVER["HTTP_REFERER"]."\">返回</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "<a href=\"".session('indexpage')."\">查询首页</a>";


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
    }elseif(empty($querycon['rpw'])){
        $querycon['rpw']=C("QUERYPW");
    }
    else{
        
    }
//   pr($querycon);  
}else{
    $user_querypw=str_replace(";",",",$user_querypw);
    $user_querypw=str_replace("，",",",$user_querypw);
    // pr($user_querypw);
    $querycon['rpw']=array("in",$user_querypw);
}

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
    $querycon['name|pid']=$name;
    // pr($con);
}

$querycontemp=$querycon;
unset($querycontemp['rpw']);
if(empty($querycontemp)){
    $inforesult="<h3><p>您能查询的数据表：</p><h3>";
    foreach($sheetnamearr as $sheetvaluearr){
        $sheetvalue=$sheetvaluearr['sheetname'];
        $inforesult.="<p> <a href=\"" . U($Think.CONTROLLER_NAME."/uniquerydata?sheetname=$sheetvalue") . "\">$sheetvalue</a></p>";
    }
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

if(empty($echohtml)){
    // $echohtml="<h3>暂无 智能字段分类。<h3>";
}else{
    $echohtml="<h3>分类查询：</h3>".$echohtml;
}

return $echohtml;


}




// 查出数据表名为sheetname,的第一行，返回一维数组
function findfirstline($sheetname){
    $db=M(C('EXCELSECRETSHEET'));
    // 查出第一行
        $sheetcon['sheetname']=$sheetname;
        $firstlinearrtemp=$db->where($sheetcon)->order('id')->find();
        // pr($firstlinearrtemp);
        $firstcon['id']=array(array("eq",$firstlinearrtemp['id']-1),array("eq",$firstlinearrtemp['id']),"OR");
        $firstcon['ord']=0;
        $firstline=$db->where($firstcon)->Field(C('FIELDSTR'))->find();  
    return $firstline;
}





public function excel___________() {
}
    


// 通用查询
public function echounisheet($sheetname,$data){

$con2=$this->constr2conarr($data,'eq');
$likecon=$this->constr2conarr($data,'like');
// pr(empty($con2['sheetname']));

if($this->isadmin($con2)){
    unset($con2['rpw']);
    $this->echounisheetuni($sheetname,$con2,$likecon);
}elseif(!empty($likecon['sheetname']['0'] == 'in')){
    $this->echounisheetuni($sheetname,$con2,$likecon);
}elseif(empty($con2['sheetname']) || empty($con2['rpw'])){
    echo    $output="error, \nsheetname \n  or\n rpw\nis \nempty.\n";    
}else{
    
    $this->echounisheetuni($sheetname,$con2,$likecon);

}    


    
}

// 通用查询
public function echounisheetuni($sheetname,$con2,$likecon){
$db=M($sheetname);
    // pr($con2);
    // pr($likecon);

   
// 去除一些无关的条件
unset($con2['conall']);
unset($con2['wrpw']);  
unset($con2['user']); 
unset($likecon['conall']);
unset($likecon['wrpw']);  
unset($likecon['user']); 
  



    // 这里计算字段
    if(!empty($con2['notfield'])){
        $todel=explode(',',$con2['notfield']);
        unset($con2['notfield']);
    }
    $Model = new \Think\Model(); // 实例化一个model对象 没有对应任何数据表

    $field=$Model->query("select COLUMN_NAME from information_schema.COLUMNS where table_name ='".C('DB_PREFIX').$sheetname."' and table_schema = '".C('DB_NAME')."';");
    
    $t1[]='wrpw';
    $field=array_column($field,'column_name');
    $field=array_diff($field,$t1);
    $field=array_diff($field,$todel);
    $fieldstr=implode($field,',');
    // pr($fieldstr);
    if(!empty($con2['field'])){
        $fieldstr=$con2['field'];
        unset($con2['field']);
    }
    // pr($con2);

// 去除一些无关的条件
// pr($con2);
// pr($likecon);

// $likecontemp['sheetname']=array('like','学生信息测试');
    $notfirstline['ord']=array('NEQ',0);
    $query=$db->where($con2)->where($likecon)->where($notfirstline)->field($fieldstr)->order('id')->select(); 
// $query=$db->where($likecontemp)->select(); 
    // $query=$db->where($con2)->select();
    // pr($query);
    
    // 插入字段行
    $fieldline['0']=$field;
    // 插入空行
    foreach ($field as $fieldkey) {
        $emptyline['0'][$fieldkey]="";
    }
    // 查出第一行    
    $queryfirst=$db->where($con2)->where($likecon)->order('id')->find(); 
// pr($queryfirst);
    if(!empty($queryfirst)){
        $sheetcon['sheetname']=$queryfirst['sheetname'];
        $firstlinearrtemp=$db->where($sheetcon)->field($fieldstr)->order('id')->find();
        $firstline['0']=$firstlinearrtemp;
        
        // $queryid=$db->where($con2)->where($likecon)->field('id')->order('id')->select(); 
        
        // pr($firstlinearrtemp);
        // pr($queryid);
        // // $qid=
        // if(isset($queryid['0']['id'])){
        //     // pr($queryid['0']['id']);
        //      if($firstlinearrtemp['id']==$queryid['0']['id']-1){
        //         $firstline['0']=$db->where($sheetcon)->field($fieldstr)->order('id')->find();
        // }
        // // pr($firstline);
        // }
       
  
    $temp=twoarraymerge($fieldline,$emptyline); 
    if(!empty($firstline)){
        $temp=twoarraymerge($temp,$firstline);  
    }
    $query=twoarraymerge($temp,$query);         
    }
    


    // // 输出结果
    $output=$this->simpletable($query); 
        if(count($query) < 4){
            echo    $output="error, \nnothing \nis \nfound. \n";
        }else{
            echo $output;           
        }        
}

// 查询数据私有的数据表
public function echoteacherdb(){
$data=I('get.');
    $ip = $_SERVER['REMOTE_ADDR'];
    $ip_config=json_decode(C('IPCONFIG'),true);
    $result = IpAuth($ip, $ip_config);
    if(!$result){
        echo "error\n,IP地址\n不在可访问列表中，\n禁止访问。";
    }else{

$sheetname=C('EXCELSECRETSHEET');
$this->echounisheet($sheetname,$data);    
        
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

    return $flag;
}


public function constr2conarr($data,$type='eq') {
foreach($data as $key=>$value){
        if(!empty($value)){
            $con2[$key]=characettouft8(unicode_to_utf8($value));
        }
    }

$conall=explode(";",$con2['conall']);
// pr($conall);
foreach($conall as $value){
    // pr($value);
   if(!empty($value)){
       if(strstr($value,"等于")){
           $ex=explode('等于',$value);
           if(count($ex)==2){
               $con2[$ex['0']]=$ex['1'];
           }else{$result="不是一个等号";}
       }elseif(strstr($value,"包含")){
           $ex=explode('包含',$value);
        //   pr($ex);
           if(count($ex)==2){
               $likecon[$ex['0']]=array('LIKE',"%".$ex['1']."%");;
           }else{$result="不是一个包含";}
       }elseif(strstr($value,"IN")){
           $ex=explode('IN',$value);
        //   pr($ex);
           if(count($ex)==2){
               $likecon[$ex['0']]=array('in',$ex['1']);;
           }else{$result="不是一个包含";}
       }
   }
}
// pr($con2);

// pr($con2);
$con2=$this->replacechinesekey($con2);
$likecon=$this->replacechinesekey($likecon);
    foreach($con2 as $key=>$value){
        if(empty($value)){
            unset($con2[$key]);
        }
    }
// pr($con2);    
 
// pr($con2);    
if(empty($likecon)){ $likecon['temp']='temp'; }//$likecon的空值处理    
if($type=='eq'){
    return $con2;
}elseif($type=='like'){
    return $likecon;
}else{return "con is error";}

}



public function personaldata(){

$db=M(C('EXCELSECRETSHEET'));
$con['pid']=$this->USER['user'];
pr($this->USER);
$inforesult=R($Think.CONTROLLER_NAME."/conquery",array($db,$con));
$this->assign("inforesult",$inforesult);

    $this->display();    
}


public function replacechinesekey($arr) {
$aa['维护人']='owner';
$aa['负责人']='owner';
$aa['数据表名']='sheetname';
$aa['学号/工号']='pid';
$aa['姓名']='name';
$aa['数据日期']='datatime';
$aa['保留1']='data1';
$aa['保留2']='data2';
$aa['只显字段']='field';
$aa['显示字段']='field';
$aa['不显字段']='notfield';
$aa['用户名']='user';
$aa['查看密码']='rpw';
$aa['上传密码']='wrpw';
$aa['姓名字段']='namekey';
$aa['学号/工号字段']='pidkey';
$aa['学号字段']='pidkey';
$aa['ID字段']='pidkey';
$aa['分类字段']='classkey';
$aa['排序字段']='orderkey';
$aa['缩略显示']='weborder';
foreach ($arr as $keycn=>$v) {
    foreach ($aa as $kval=> $vkey) {
        if($keycn==$kval){
            unset($arr[$keycn]);
            $arr[$vkey]=$v;
        }
    }
}
return $arr;
}  
public function echosheetname() {
// $DBNAME='rwxy';
// $DBSHEET='tzcdata';
$data=I('get.');



$db=M(C('EXCELSECRETSHEET'));
$con2=$this->constr2conarr($data,'eq');

    $query=$db->where($con2)->distinct(true)->field('sheetname')->order('id')->select();

    $output=$this->simpletable($query); 
        if(count($query)<1){
            echo    $output="error, \nnothing \nis \nfound. \n";
        }else{
            echo $output;           
        }    
        
    
}



public function isnum($val){
    if($val>100000000000 ){
        return true;
    }else{
        // pr("非文本3");
        return false;         
    }
}

//   把特殊符号给删除了
public function deltextsymbol($text,$symbol="?"){
// echo mb_substr($text,0,1,"UTF-8");
    if(mb_substr($text,0,1,"UTF-8")==$symbol){
        // echo 'first text is <hr>'.$text;
        $newtext=mb_substr($text,1,NULL,"UTF-8");
        $newtext= $this->deltextsymbol($newtext);
        // echo 'second text is <hr>'.$newtext;
        return $newtext;
    }else{
        // pr($text);
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
        $twoarrnew[$k1][$k2]=$v2new;
    }
}
return $twoarrnew;
}      
public function u___________() {
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
        $output="error, \nnothing \nis \nfound. \n";
    }    
return $output;     
}


// 二维数组输出简单的表格
public function simpletable($data){
     $temp1='
     <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title></title>
</head>

<body><table class="table table-striped"> <tbody>';
     $firstline='';
foreach ($data as $rows2) {
    foreach ($rows2 as $key2=>$value2) {
        $firstline=$firstline.'<th>'.$key2.'</th>';
    }
        if(!empty($firstline)){
            $firstline='<tr>'.$firstline.'</tr>';
            break;
        }
    
} 

$textsymbol=C('TEXTSYMBOL');
    // pr($firstline);
$temp2='';   
foreach ($data as $rows) {
    $temp22='';
    foreach ($rows as $key=>$value) {
    // $value=$this->deltextsymbol($value); 
        if($this->isnum($value) ){
            $temp22=$temp22
          .'<td>'.$textsymbol.$value.'</td>';       

        }else{
            $temp22=$temp22
          .'<td>'.$value.'</td>';                 
        }
       
    }
    $temp2=$temp2.'<tr>'.$temp22.'</tr>';
}      
$temp3='     </tbody>
</table>
</body>
</html>'
;

// 是不是要加第一行
// $temp2=$firstline.$temp2;
$temp=$temp1.$temp2.$temp3;


return $temp;    
}



	 
     //保存导入数据
    public function save_import($data) {
        // $data=import_excel($filename);
        $db=M('member');

        foreach ($data as $k => $v) {
            if ($k > 1) {
                 pr($v);
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
            $this->success('用户导入成功' . '，现在<span style="color:red">' . $num . '</span>条数据了！,30');
        } else {
            $this->error('用户导入失败',30);
        }
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
 


// 准备弃用
// php
public function phpupload() {
    $ip = $_SERVER['REMOTE_ADDR'];
    $ip_config=json_decode(C('IPCONFIG'),true);
    $result = IpAuth($ip, $ip_config);
    // pr($_FILES);
    if(!$result){
        echo "error\n,IP地址\n不在可访问列表中，\n禁止访问。";
    }else{

     echo "本文件上传时间是：".date("Y-m-d H:i:s");
if (($_FILES["file"]["type"] == "application/vnd.ms-excel")
|| ($_FILES["file"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
|| ($_FILES["file"]["type"] == "application/octet-stream"))
{

  if ($_FILES["file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
  else
    {
    // echo "Upload: " . $_FILES["file"]["name"] . "<br />";
    // echo "Type: " . $_FILES["file"]["type"] . "<br />";
    // echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
    // echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

    $path=$_SERVER["DOCUMENT_ROOT"] .'/Uploads/'. $_FILES["file"]["name"];
    // pr($path);
      move_uploaded_file($_FILES["file"]["tmp_name"],$path);
    
    }

    // echo "Stored in: " .$path;


    $data=I('get.');
$this->dealuploadexcel($path,$data);

}
else
  {
  echo "Invalid file";
  }

        
    }    

}





// 处理上传过来的数据
public function dealuploadexcel($filename,$data) {
    // pr($data);
$DBNAME='rwxy';
$DBSHEET='tzcdata';
// $conall=$data['conall'];
$con2=$this->constr2conarr($data,'eq');
// pr($con2);
// pr($data);
unset($con2['conall']);
$wrpw=$con2['wrpw'];
$rpw=$con2['rpw'];
$sheetname=$con2['sheetname'];
$namekey=$con2['namekey'];
$pidkey=$con2['pidkey'];

$classkey=$con2['classkey'];
$orderkey=$con2['orderkey'];
$con2temp=$con2;
unset($con2temp['wrpw']);unset($con2temp['rpw']);unset($con2temp['namekey']);unset($con2temp['pidkey']);
unset($con2temp['sheetname']);unset($con2temp['notfield']);

if(!empty($wrpw) && !empty($sheetname)){  //pw非空再说
    $filenameright3=substr($filename,-3);
    if($filenameright3=="lsx"){
       $datatwoarr=readOnlyExcel($filename);
    }else{
        $datatwoarr=readCSV($filename);
    }

// pr($datatwoarr);
    foreach ($datatwoarr as $key => $dataarr) {
        if($key==0){
            $title=$dataarr;
        }else{
            foreach($dataarr as $key2=>$value2){
                $twoarrexcel[$key][$title[$key2]]=$value2;
            }
            $twoarrexcel[$key]['wrpw']=$wrpw;
            $twoarrexcel[$key]['rpw']=$rpw;
            $twoarrexcel[$key]['sheetname']=$sheetname;
            $twoarrexcel[$key]['ord']=$key -1;
            $twoarrexcel[$key]['custom1']=json_encode($con2temp);
            if(!empty($con2['namekey'])){
                $twoarrexcel[$key]['name']=$twoarrexcel[$key][$con2['namekey']];
            }
            if(!empty($con2['pidkey'])){
                $twoarrexcel[$key]['pid']=$twoarrexcel[$key][$con2['pidkey']];
            }            
        }
    }
    // echo "\n2";
    // pr($datatwoarr);
    // echo "\n3";
    // pr($twoarrexcel);
    // echo json_encode($twoarrexcel);

    $db=M(C('EXCELSECRETSHEET'));

// 密码与先有的一样才行
$existdatacon['sheetname']=$twoarrexcel['2']['sheetname'];
$existdata=$db->where($existdatacon)->order('id asc')->select();
// pr($existdata);
// pr("实际密码是".$existdata['2']['wrpw']."输入密码".$wrpw);
if($existdata['2']['wrpw']==$wrpw || empty($existdata)){
// 把数据表中的数据删了
    if(isset($twoarrexcel['2']['sheetname'])){ //第0行数据库字段名，第1行中文字段名
        $delcon['sheetname']=$twoarrexcel['2']['sheetname'];
        $delcon['wrpw']=$wrpw;
// 删除标题行
        $firstlinearrtemp=$db->where($delcon)->find();
        $firstcon['id']=$firstlinearrtemp['id']-1;
        $firstcon['ord']=0;
        $db->where($firstcon)->delete();        
// 删作数据行
        $db->where($delcon)->delete();
        
        

        $twoarrexcel=$this->deltextsymboltwoarray($twoarrexcel);
        $result=$this->dbadddata($db,$twoarrexcel);
    }else{ $result='sheetname is empty.';}
}else{ $result='error,password is wrong, or exist other same sheetname.';} 

    
    // $result=dbadddata($db,$twoarrexcel);
    
}else{ $result="password  or sheetname is empty";}


pr($result);    
}


public function dbadddata($db,$datatwoarr) {

// pr($datatwoarr);
// 先确认导入的字段
$newcout=0;
$newfailcout=0;
foreach($datatwoarr as $key=>$dataarr){
 reset($dataarr);
 $id=current($dataarr);
$dataarr['id']='';
// pr($dataarr);
    $new=$db->data($dataarr)->add(); 
// pr($new);
    if($new>0){
       $newcout++; 
    }else{
       $newfailcout++; 
    }
    // pr($newcout);
}
$result='用户成功清空原有数据，并导入' . '<span style="color:red">' . $newcout . "</span>条数据了！"."，其中失败".$newfailcout."条";
return $result;
} 






// 结尾处
}