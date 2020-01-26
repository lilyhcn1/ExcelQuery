<?php
namespace Qwadmin\Controller;
use Think\Controller;
class DatauseController extends Controller{
public function index(){


}
public function HstbHttp(){
    // addlog('$ReplyMsg','QQreply1');
$input = file_get_contents("php://input"); //接收提交的所有POST数据
$input = urldecode($input);//对提交的POST数据解码
$Array = json_decode($input);//对解码后的数据进行Json解析

$constr=I('get.conall');
// $arr=json_decode($Array);
    $QQReplyMsg=R('Datause/echohstb',array($Array,$constr));  
addlog($QQReplyMsg,'QQreply9');
echo $QQReplyMsg;    
}



public function Kqtestnew(){
$arrstr=
<<<aaa
{"type":2,"subType":1,"time":4705,"qq":53053067,"group":237676351,"msg":"思源群？","font":141292200,"authTime":"1524735569","authToken":"39799535417431c634dbae480c8f74de"}
aaa;
// $url=I('get.url');
$constr=I('get.conall');
$arr=json_decode($arrstr);
    $QQReplyMsg=R('Datause/echohstb',array($arr,$constr));  


pr($QQReplyMsg);
}


// 处理QQ群信息
public function echohstb($Array='',$constr){
    // pr($Array);
// $Array=json_decode($Arrayjson);    
    addlog('qq上传文件'.json_encode($Array));
$rev=$Array->{'msg'};
$QQ=$Array->{'qq'};
$Type=$Array->{'type'};
$Group=$Array->{'group'};
// $Msg='您收到的信息为:'.$rev;
$ReplyArr = array(
'Type'=>$Type,
'QQ'=>$QQ,
'Msg'=>$Msg,
'Group'=>$Group,
"Send"=>1
);
    // addlog('$ReplyArr2'.json_encode($ReplyArr));
// pr($ReplyArr);
$con['qq']=$QQ;
$userarr=M('Member')->where($con)->find();
// pr($userarr);
addlog($rev,'rev2222');
// 群信息的处理
 if($Type=='2'  ){   

    if(empty($QQReplyMsg)){
    
                $Is_Quesion=R('Datause/Is_Quesion',array($rev));  
                // pr($rev,'rev');

                // pr($keywordarr);
                if($Is_Quesion){
                    $allkeywordstr=$this->UpdateExistKeyword(); 
                    $keywordarr=$this->FindKeywordInRev($rev,$allkeywordstr); 
                    // pr($keywordarr);
                    if(!empty($keywordarr)){

                        $url='http://'.$_SERVER['SERVER_NAME'].U('Rwxy/echojson')."?conall=".';'.$constr;
// pr($url,'url');            
// addlog($url,'url');
                        foreach($keywordarr as $kkey=>$keyword){
                            //查询条件
                            $constr="d3包含".$keyword.";";
                            
                            $temp=$this->gethttpjson($url,$constr);
// pr($temp,'$temp22222');  $QQReplyMsg=R('Reply/returnmsg',array($ReplyMsg,'weixin'));                                 
                            // $ReplyMsg.=returnmsg(jsonkeyval($temp),'weixin');
                            $ReplyMsg.=jsonkeyval($temp);
                                // $ReplyMsg="fdfdf";
// pr($ReplyMsg,'$ReplyMsg22');    
                     
                        }
                        // $ReplyMsg.=" 【欢迎邀请我入群！~】";


                    }
                }
    }
addlog($ReplyMsg,'$ReplyMsg222222222');    
    $ReplyArr['Msg']=$ReplyMsg;
    return json_encode($ReplyArr);
}
// elseif($Type=='1' && $QQ=='53053067' ){  //这里是QQ私聊回复，很容易封QQ号，建议不要启用

//         $ReplyMsg=R('Reply/qq',array($userarr,$rev));   
//         // $QQReplyMsg=R('Reply/returnmsg',array($ReplyMsg,'weixin'));
//         $ReplyArr['Msg']=$QQReplyMsg;
//         return json_encode($ReplyArr);
// }





    
    
}






// 公开信息的QQ群查询
public function gethttpjson($url="",$constr="姓名包含餐厅"){
    $urlstr=$url.$constr;
// addlog($urlstr,'$urlstr');

$jsonarr=json_decode(file_get_contents($urlstr),true);
// pr($jsonarr);
if($jsonarr['code']=="0" && !empty($jsonarr['arr'])){
    // return $jsonarr['arr'];
    $ddd=json_decode($jsonarr['arr'],true);

    return $ddd;
}

}

public function changemsgtype($Array,$type='http'){

    if($type=='http'){
        $Arraynew['rev']=urldecode($Array->{'message'});
        $Arraynew['qq']=$Array->{'user_id'};     
        $Arraynew['msgtype']=$Array->{'type'};
    }elseif($type=='hstb'){
        // pr('11111111111111');
        $Arraynew['rev']=$Array->{'msg'};
        // pr($Array->{'msg'});
        $Arraynew['qq']=$Array->{'qq'};
        if($Array->{'type'}==1){
            $Arraynew['type']='private';
        }elseif($Array->{'type'}==2){
            $Arraynew['type']='group';
        }
        
    }
    // pr('$Arraynew'.$Arraynew);
    return $Arraynew;
}



    


public function Is_Quesion($words){
    $Is_Quesion=0;
    $quseionword="?,？,怎么,什么,吗,多少,求,有知道,请问,是谁,谁有,哪个,哪里,在哪,有没有";
    $quseionlen='90';
    if(mb_strlen($words) < $quseionlen){
        $keyword=explode(',',$quseionword);
        // pr($keyword);
        foreach ($keyword as $value) {
            if(strstr($words,$value)){
                $Is_Quesion=1;
                break;
            }
        }
    }
    return $Is_Quesion;
}



public function UpdateExistKeyword(){ 
$con['sheetname']='台院公开信息';
$con['d3']= array('exp',' is not NULL');
$keywordtwoarr=M(C('EXCELSECRETSHEET'))->where($con)->select();
// pr($keywordtwoarr,'$keywordtwoarr');
$keywordarr=array_column($keywordtwoarr,'d3');

foreach ($keywordarr as $keywords) {
    $keyarr=explode(';',$keywords);
    foreach ($keyarr as $value) {
        if(!empty(trim($value))){
            $allkeyword[]=trim($value);
        }
    }
    
}
// pr($allkeyword); 
 $allkeywordstr=implode(';',$allkeyword);   
//     // pr($allkeywordstr);

// $data['content']=$allkeywordstr;
// $data['id']=0;
// pr($allkeywordstr);
return $allkeywordstr;
// $keywordtwoarr=M('info')->save($data);
// echo '关键词列表已更新';
}    



public function FindKeywordInRev($rev,$allkeywordstr){ 
 $keywordarr=explode(';',$allkeywordstr);  

// pr($rev);
$findkeyword=''; 
foreach($keywordarr as $keyword){
        if(strstr($rev,$keyword)){
            $findkeyword[]=$keyword;
            break;
        }
}
// pr($findkeyword);
return $findkeyword;


}  


public function RecReceive($Array,$rs='收'){
$data['qq']=$Array->{'QQ'};
if(is_null($Array->{'QQ'})){
   $data['qq']='000'; 
}
$data['rs']=$rs;
$data['type']=$Array->{'type'};
$data['group']=$Array->{'Group'};
$data['msg']=urldecode($Array->{'Msg'});
$data['time']=time();
// $data['qq']=$Array->{'QQ'};
M('recivedqqmsgs')->add($data);
return $data;
}



// 回复群消息
public function SendGroupMsg($Array,$msg){
    $msg = str_replace(PHP_EOL, '\n', $msg); 
addlog($msg);    
        $ReplyMsgjson = '{"data":{'.
                '"Type":'.'2'.','.
                '"Group":'.$Array->{'Group'}.','.
                '"Msg":"'.$msg.'"}}';      
echo $ReplyMsgjson;
 echo R('Kq/RecAndSend',array($ReplyMsgjson));    
 
 
 
 
 
}    

// 回复QQ消息
public function ReplyPraMsg($Array,$msg){
    $msg = str_replace(PHP_EOL, '\n', $msg); 
            $ReplyMsgjson = '{"data":{'.
                '"type":1,'.
                 '"Subtype":'.'2'.','.
                '"QQ":'.$Array->{'QQ'}.','.
                '"Msg":"'.$msg.'"}}';
echo R('Kq/RecAndSend',array($ReplyMsgjson));    
}  

// 发送QQ私人消息
public function SendPraMsg($Array,$msg){
    $msg = str_replace(PHP_EOL, '\n', $msg); 
            $ReplyMsgjson = '{"data":{'.
                '"type":'.'1'.','.
                 '"QQ":'.$Array->{'QQ'}.','.
                '"Msg":"'.$msg.'"}}';
echo R('Kq/RecAndSend',array($ReplyMsgjson));    
}  
// 发送QQ私人消息
public function SendPraMsg2($qq,$msg){
    $msg = str_replace(PHP_EOL, '\n', $msg); 
            $ReplyMsgjson = '{"data":{'.
                '"type":'.'1'.','.
                 '"QQ":'.$Array->{$qq}.','.
                '"Msg":"'.$msg.'"}}';
echo R('Kq/RecAndSend',array($ReplyMsgjson));    
}  


public function RecAndSend($ReplyMsgjson){
    $replyarray= json_decode($ReplyMsgjson)->data;           
    R('Kq/RecReceive',array($replyarray,'发'));  
    return $ReplyMsgjson;
}


// 利用QQ或群来返回$userarr
public function GetArrayUser($Array){
  $userarr=qqGetuser($Array->{'QQ'});
  $group=$Array->{'Group'};
  if(empty($userarr)){
    // if($group =='45758808'){
        $userarr['stu_class']='教师';
        $userarr['nickname']='权限';
        $userarr['department']=$Array->{'QQ'}.',';
    // }
  }
  return $userarr;
}


public function QQMsgDeal($Array){
//   读取群消息对应的消息ID数组
    $data=R('Kq/RecReceive',array($Array,'收')); 
    
    $con['creattime']=array('GT',time()-C('QQGROUPMSGTIME'));

    // 读取半小时内的所有新建通知，并用群号进行过滤
    $ToDealMsg=M('notice')
        ->join('qw_notice_opt ON qw_notice.sender = qw_notice_opt.user')
        ->where($con)->order('creattime desc')->select();


    if(!empty($ToDealMsg)){
        $ToDealMsg2= twoarrayfindval($ToDealMsg,'qqgroup',$data['group']);
    // 记录回复消息为已读
        $con3['qq']=$Array->{'QQ'};
        $userarr=M('Member')->where($con3)->find();

        if($ToDealMsg2 && $userarr){
           $notice_id=$ToDealMsg2['0']['id']; 
           $DealQQMsg=R('Task/rec_readnew',array($notice_id,$userarr,'qq'));
        }
    
        
    }
    

}



//结尾处
}