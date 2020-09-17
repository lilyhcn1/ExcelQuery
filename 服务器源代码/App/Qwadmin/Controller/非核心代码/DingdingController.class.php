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
use Think\Controller;
class DingdingController extends Controller{    
public function index(){
$aa= $this->getqqnickname('53053067',"45758808");
pr($aa);
}

/*  
* 钉钉转发消息
* 直接含配置了，这样方便点
*/
public function relaymsg($obj){
// 准备转发
$qqtoding[1]["qq_group"]="237676351";//王进利几人小号群
$qqtoding[1]["ding_token"]="e38e56c7aef51091217d318404d4f67dbfabe185ec28335224438ae4e8bcb57d";

$qqtoding[2]["qq_group"]="539844557"; 
$qqtoding[2]["ding_token"]="eeb5228913253e67116b4f0153c9f45c95958de7dc5afa62e840edb0982f7fda";  

$qqtoding[3]["qq_group"]="375053328";  //人文学院
// $qqtoding[3]["ding_token"]="114b1f28556a919d553324395f0d0fec2aa4745f970e6d0c95c1bee3d89ba5e1";   
$qqtoding[3]["ding_token"]="390112adb41419e7fd63f56b133919f7c18513c08d391198238f2d0ca6d797a9"; //浙政钉


$qqtoding[4]["qq_group"]="127355468";
$qqtoding[4]["ding_token"]="5cf23acaf6a50fbaab5c5d2b73825130815760f3aaca21b1b8a2255b2777e273"; 

$qqtoding[5]["qq_group"]="9360940";   //商学院
$qqtoding[5]["ding_token"]="09e6d2b9e6c969a41418a9c06f13ae8115d076908d1c1140bbd7adbbf52f4904"; 
$qqtoding[6]["qq_group"]="61847138"; //马学院
$qqtoding[6]["ding_token"]="82ff2d212a12e96bc3d417592135c643516a0b7334a4e4eb0fc527cb5ef87636"; 



$haverelay=false;

// pr($obj);
$Array=$obj;
$msg=$Array->{'Msg'}->{'Text'};
$QQ=$Array->{'FromQQ'}->{'UIN'};
$qq_group=$Array->{'FromGroup'}->{'GIN'};



// pr($msg,'$msg');
// 先确认是否有转发关键词
if($this->have_relay_keyword($msg)){


$ding_token_twoarr=twoarrayfindval($qqtoding,"qq_group",$qq_group);
if(!empty($ding_token_twoarr)){
    foreach($ding_token_twoarr as $ding_tokentemp ){
        $msg1=$this->newqqtodingmsg($Array);
        pr($msg1,'msg1');
        pr($ding_tokentemp["ding_token"],'$ding_tokentemp["ding_token"]');
        $this->sendmsg($ding_tokentemp["ding_token"],$msg1);
        // $this->sendmsg("eeb5228913253e67116b4f0153c9f45c95958de7dc5afa62e840edb0982f7fda","dsdsdsdsdsd");
        $haverelay=true;
    }
    
}

}
// pr($haverelay);
return $haverelay;
}

/*  
* QQ中是否含转发关键词
* 直接含配置了，这样方便点
*/
public function have_relay_keyword($str){
$keywordarr[1]="各";
$keywordarr[2]="通知";
// pr($keywordarr);
// pr($str);
$have=false;
foreach($keywordarr as $k=> $v){
    // pr($v);
    // pr($str);
    // pr(strstr($str,$v));
        if(strstr($str,$v)){
            $have= true;
            return true;
        }     
}
return $have;
}



public function newqqtodingmsg($Array){
// $message=$Array->{'msg'};
// $QQ=$Array->{'qq'};
// $qq_group=$Array->{'group'};
$message=$Array->{'Msg'}->{'Text'};
$QQ=$Array->{'FromQQ'}->{'UIN'};
$qq_group=$Array->{'FromGroup'}->{'GIN'};

$nicknamearr=$this->getqqnickname($QQ,$qq_group);
if(empty($nicknamearr)){
    $msg= "QQ群【".$qq_group."】的QQ号【".$QQ."】：\n".$message;
}else{
    $msg= "【".$nicknamearr["d1"]."】群的【".$nicknamearr["d4"]."】：\n".$message;
}

return $msg;
}




/*  
* 钉钉主动发送消息
* $access_token 机器人的口令
* $message 要发送的消息
*/
public function sendmsg($access_token="eeb5228913253e67116b4f0153c9f45c95958de7dc5afa62e840edb0982f7fda",$msg){

$webhook = "https://oapi.dingtalk.com/robot/send?access_token=$access_token";
$data = array ('msgtype' => 'text','text' => array ('content' => $msg));
$data_string = json_encode($data);
pr($webhook);
pr($data,'$data');

$result = $this->request_by_curl($webhook, $data_string);  
// echo $result;
}



public function getqqnickname($QQ,$qq_group){
    

$DBNAME='r34';
$DBSHEET='unisecret';

$db = M($DBSHEET,'qw_','mysql://root:lily53053067@localhost/'.$DBNAME.'#utf8'); 
// pr($db);
// $name=I('get.name');
$con['sheetname']= "QQ昵称";
// pr($QQ);
// pr($qq_group);
if(!empty($QQ)){
        // 查数据表
    $con['rpw']='85137052';
    $con['name|pid']=$QQ;
    $con['d2']=$qq_group;
// pr($con);

$r=$db->where($con)->find();

} 
return $r;

}



function request_by_curl($remote_server, $post_string) {  
    $ch = curl_init();  
    curl_setopt($ch, CURLOPT_URL, $remote_server);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
    curl_setopt($ch, CURLOPT_POST, 1); 
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Content-Type: application/json;charset=utf-8'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
    $data = curl_exec($ch);
    curl_close($ch);  
               
    return $data;  
}  

// 结尾处
} 







