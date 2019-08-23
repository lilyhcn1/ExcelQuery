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
class RwxyController extends BaseController{
    
// namespace Qwadmin\Controller;
// use Qwadmin\Controller\ComController;
// class RwxyComController extends ComController{    
    
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
echo 343;pr($likecon);
pr($con2);
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
public function echounisheetuni($dbsheetname,$con2,$likecon){
$db=M($dbsheetname);
    // pr($con2);
    // pr($likecon);

   
// 去除一些无关的条件
unset($con2['conall']);
unset($con2['wrpw']);  
unset($con2['user']); 
unset($likecon['conall']);
unset($likecon['wrpw']);  
unset($likecon['user']); 


  
$ordstr=empty($con2['orderkey'])?"id":$con2['orderkey'];

 
// 0. 读取第一行
    // $sheetcon['sheetname']=$con2['sheetname'];
    // $queryfirst=$db->where($sheetcon)->order('id')->find(); 
    // $queryfirst=$db->where($con2)->where($likecon)->order('id')->find(); 
    $sheetname=empty($con2['sheetname'])?C('MLSHEETNAME'):$con2['sheetname'];
    $queryfirst=R('Queryfun/findfirstline',array($sheetname,true));
    // pr($queryfirst);
    $queryfirst=delemptyfield($queryfirst);


// 1. 先把所有的字段都计算出来，除了wrpw
    $Model = new \Think\Model(); // 实例化一个model对象 没有对应任何数据表
    $field=$Model->query("select COLUMN_NAME from information_schema.COLUMNS where table_name ='".C('DB_PREFIX').$dbsheetname."' and table_schema = '".C('DB_NAME')."';");
// pr($field,'$field221');    
    $t1[]='wrpw';
    $field=array_column($field,'column_name');
    $field=array_diff($field,$t1);


// pr($con2['field'],'11');
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
        $field=array_diff($field,$todel);        
// 4. field中删除字段   
    foreach($field as $fkey=>$fvalue){ 
        if(!empty($queryfirst[$fvalue])){
            $newfieldarr[]=$fvalue;
        }
    } 
    $field=$newfieldarr;
        
    }




// 5. 把字段写成str    
// if(!empty($con2['vlookup'])){
//     array_unshift($field,$con2['vlookup']);
// }

unset($con2['field']);
unset($con2['notfield']);
$fieldstr=implode($field,',');
// pr($fieldstr,'$fieldstr');

// 这里可能有问题，，，，，，，，，，，，，，，，，，，，，，，，，，
    if(!empty($queryfirst['id'])){
        $notfirstline['id']=array('NEQ',$queryfirst['id']);
    }else{
        $notfirstline['id']=array('NEQ',0);
    }
    // $notfirstline['id']=array('NEQ',0);
    // pr($ordstr);
    $query=$db->where($con2)->where($likecon)->where($notfirstline)->field($fieldstr)->order($ordstr)->select(); 
// pr($con2,'con211');
// pr($likecon,'likecon22');
// pr($query,'$query');    
    // 插入字段行
    $fieldline['0']=$field;
// pr($field,'$field');
    // 插入空行
    foreach ($field as $fieldkey) {
        $emptyline['0'][$fieldkey]="";
    }

// pr($queryfirst);
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
// pr($query,'query');


    // // 输出结果
    $output=$this->simpletable($query); 
        if(count($query) < 4){
            echo    $output="error, \nnothing \nis \nfound3. \n";
        }else{
            echo $output;           
        }        
}

// 查询数据私有的数据表
public function echoteacherdb(){
$data=I('get.');
// pr($data,'$data11');
if(empty($data)){
    $data=I('post.');
    // addlog("post 提交的数据");
}
// pr($data);
    $result = $this->Auth2Use();
    if(!$result){
        echo "error\n,IP地址\n不在可访问列表中，\n禁止访问。";
    }else{

// $sheetname=C('EXCELSECRETSHEET');
    $dbsheetname=C('EXCELSECRETSHEET');
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

// echo 323;pr($likecon);
// pr($con2);        
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
pr($this->USER);
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
        // pr("非文本3");
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
public function simpletable($data){
     $temp1='
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"> 
    <title></title>
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">  
    <script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>

<body><table class="table table-striped"> <tbody>';
     $firstline='';
foreach ($data as $rows2) {
    foreach ($rows2 as $key2=>$value2) {
        $firstline=$firstline.'<th>'.$key2.'</b></th>';
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
    $n=0;
    foreach ($rows as $key=>$value) {
        // $n++;
        // pr($n);
        $tablestyle=($n % 2 == 0)?'class="success"':'class="warning"';
        if($this->isnum($value) ){
            $temp22=$temp22
          .'<td>'.$textsymbol.$value.'</td>';       

        }else{
            $temp22=$temp22
        //   .'<td '.$tablestyle.' >'.$value.'</td>';   
          .'<td >'.$value.'</td>';   
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
 


// 准备弃用
// php
public function phpupload($post="",$files="",$server="") {
//     $_POST=$post;
// $_FILES=$files;
$post=empty($_POST)?$post:$_POST;
$files=empty($_FILES)?$files:$_FILES;
$server=empty($_SERVER)?$server:$_SERVER;
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
    // pr($path);
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
    // pr($data);
$DBNAME='rwxy';
$DBSHEET='tzcdata';
// $conall=$data['conall'];
$con2=R("Queryfun/constr2conarr",array($data,'eq'));
// pr($con2,'$con2');
// pr($data);
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

$con2temp=$con2;
unset($con2temp['wrpw']);unset($con2temp['rpw']);unset($con2temp['namekey']);unset($con2temp['pidkey']);
unset($con2temp['sheetname']);unset($con2temp['notfield']);unset($con2temp['password']);
// pr($wrpw,'$wrpw');
// pr($sheetname,'$sheetname');
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


// pr($datatwoarr);
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
                $twoarrexcel[$key]['name']=$twoarrexcel[$key][$con2['namekey']];
            }
            if(!empty($con2['pidkey'])){
                $twoarrexcel[$key]['pid']=$twoarrexcel[$key][$con2['pidkey']];
            }
            if(empty($twoarrexcel[$key]['rpw'])){
                 $twoarrexcel[$key]['rpw']=$rpw;
            }               
        }
    }
    $twoarrexcel=delemptyfieldtwoarr($twoarrexcel);
    $twoarrexcel=$this->deltextsymboltwoarray($twoarrexcel);





    
// pr(count($twoarrexcel),'$twoarrexcel');
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
$recurl=U('Rwxy/echoteacherdb',"conall=;数据表名等于".$sheetname.";不显字段等于id,wrpw,data1,data2,ord,rpw,name,pid,custom1,custom2,sheetname;查看密码等于".$rpw."","",true);
$queryurl=U('Vi/uniquerydata',"rpw=".$rpw."&sheetname=".$sheetname,"",true);
$phonemodify=U('Ud/magrecords',"wrpw=".$wrpw."&sheetname=".$sheetname,"",true);


$phoneurl2=U('AdCom/addedit',"sheetname=".$sheetname,"",true);
$recurl2=U('RwxyCom/echoteacherdb',"conall=;数据表名等于".$sheetname.";不显字段等于id,wrpw,data1,data2,ord,rpw,name,pid,custom1,custom2,sheetname;查看密码等于".$rpw."","",true);
$queryurl2=U('ViCom/uniquerydata',"rpw=".$rpw."&sheetname=".$sheetname,"",true);
$phonemodify2=U('UdCom/magrecords',"wrpw=".$wrpw."&sheetname=".$sheetname,"",true);
// $queryurl=U(getcomstr('Vi').'/uniquerydata',"rpw=".$rpw."&sheetname=".$sheetname,"",true);

$urls="<hr><hr>"
    ."<hr>您的在线填表网址为：<hr>"."<a href=\"".$phoneurl."\">".$phoneurl."</a>"
    ."<hr>填表短网址为：<hr>"."<a href=\"".shorturl($phoneurl)."\">".shorturl($phoneurl)."</a>"
    ."<hr><br>-------------以下为保密区，请注意保密。-----------------------------------<br>"
    ."<hr>您的所有数据网址为（请保密）：<hr>"."<a href=\"".$recurl."\">".$recurl."</a>"
    ."<hr>您的免密码查询网址为（请保密）：<hr>"."<a href=\"".$queryurl."\">".$queryurl."</a>"
    ."<hr>您的在线修改网址为（请保密）：<hr>"."<a href=\"".$phonemodify."\">".$phonemodify."</a>"
    ."<hr><br>-------------以下网址必须先登陆才能访问，请注意保密。-----------------------------------<br>"
    ."<hr>您的在线填表网址为：<hr>"."<a href=\"".$phoneurl2."\">".$phoneurl2."</a>"
    ."<hr>您的所有数据网址为（请保密）：<hr>"."<a href=\"".$recurl2."\">".$recurl2."</a>"
    ."<hr>您的免密码查询网址为（请保密）：<hr>"."<a href=\"".$queryurl2."\">".$queryurl2."</a>"
    ."<hr>您的在线修改网址为（请保密）：<hr>"."<a href=\"".$phonemodify2."\">".$phonemodify2."</a>"    
    
    
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
// pr($twoarrexcel[2]);
// pr("实际密码是".$existdata['2']['wrpw']."输入密码".$wrpw);
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
// pr($existdatacount,'写入后的数据量为');

$result='用户成功清空原有数据，并导入' . '<span style="color:red">' . $newcout . "</span>条数据了！"."，其中失败".$newfailcout."条";
return $result;
} 




// 结尾处
}
