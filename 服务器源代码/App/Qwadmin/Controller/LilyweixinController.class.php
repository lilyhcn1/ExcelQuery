<?php
/**
* 1.数据库名替换
* 2. $keyword
*
**/

namespace Qwadmin\Controller;
use Think\Controller;
use Com\Wechat;
// use Com\WechatAuth;
use Com\qywechat;

class LilyweixinController extends Controller{
    /**
     * 微信消息接口入口
     * 所有发送到微信的消息都会推送到该操作
     * 所以，微信公众平台后台填写的api地址则为该操作的访问地址
     */
    public function index($id = ''){
        //调试
        try{
            $appid = 'weixin53053067'; //AppID(应用ID)
            // wxd51ed3fc02e99078
            $token = 'weixin'; //微信后台填写的TOKEN
            $crypt = '71f74a543778e9a9cfeee6dab0c928e2';
            $wechat = new Wechat($token, $appid, $crypt);
            $data = $wechat->request();
            if($data && is_array($data)){
                $this->demo($wechat, $data);
            }
        } catch(\Exception $e){
            file_put_contents('./error.json', json_encode($e->getMessage()));
        }
    }
    
public function sansi($id = ''){
        //调试
        try{
            $appid = 'wx94144cafb998e72f'; //AppID(应用ID)
            $token = 'weixin'; //微信后台填写的TOKEN
            $crypt = 'nKTMiG0La6x2M1bCTTRnhseVMEYJ7OHJtKnxZ8Kf3VT';
            $wechat = new Wechat($token, $appid, $crypt);

            $data = $wechat->request();
            if($data && is_array($data)){
                $this->demo($wechat, $data);
            }
        } catch(\Exception $e){
            file_put_contents('./error.json', json_encode($e->getMessage()));
        }
    }  
public function wxtest($id = ''){
        //调试
        try{
            $appid = 'wx389ff9da2feede2f'; //AppID(应用ID)
            $token = 'weixin'; //微信后台填写的TOKE N
            $crypt = '88e931cde31425b19931c5f6bd28558c';
            $wechat = new Wechat($token, $appid, $crypt);

            $data = $wechat->request();
            if($data && is_array($data)){
                $this->demo($wechat, $data);
    
           }
        } catch(\Exception $e){
            file_put_contents('./error.json', json_encode($e->getMessage()));
        }
        
    } 

    public function demo($wechat, $data){
// addlog(json_encode($data)); 
//  帐号处理模块
// if(empty($userarr)){
    $fromuser=$data['FromUserName'];
    $con['wx_openid']=array('like','%'.$data['FromUserName'].';%');
    $result=M('member')->where($con)->find();
    $userarr=$result;    
// }

// 把查询记录也记录下来
$this->WeixinRecReceive($data,$userarr);

        switch ($data['MsgType']) {
            case Wechat::MSG_TYPE_EVENT:
                switch ($data['Event']) {
                    case Wechat::MSG_EVENT_SUBSCRIBE:
                        $wechat->replyText('欢迎您关注本微信平台！'
                        ."\n请先<a href=\"http://".$_SERVER['HTTP_HOST']."/index.php/Qwadmin/Lilyreg?wx_openid=".$fromuser."\">注册帐号并绑定帐号</a>。"
                        ."\n现在主要功能："
                        ."\n1. 重要通知提醒：我会通知你；"
                        ."\n2. 公共信息查询：税号，校车等；"
                        ."\n3. 图像识别功能：文本识别，植物识别等；"
                        
                        ."\n具体请查看<a href=\"http://".$_SERVER['HTTP_HOST']."/index.php/Qwadmin/Task/e \"> 现有功能 </a>。"
                        // ."\n近期活动："
                        // ."\n<a href=\"http://www.hixianchang.com/pro/mobile/index.html?/#/transferRoute.html?mobileFlag=e4obOJZj&route=applysign  \"> 台院墙周年庆签到抽奖 </a>。"
                        
                        
                        );
                        break;
                    case Wechat::MSG_EVENT_UNSUBSCRIBE:
                        //取消关注，记录日志
                        break;
                    case Wechat::MSG_EVENT_CLICK:
                        if($data['EventKey']=='getnotice'){
                            $con['wx_openid']=array('like','%'.$data['FromUserName'].';%');
                            $userarr=M('member')->where($con)->find();
                            $user=$userarr['user'];
                            // addlog('Lilyweixin.demo'.json_encode($con));
                
                            $ReplyMsg=R('Reply/getrev_shuodao',array($user));
                          
                            // $ReplyMsg='fdf';
                            $wechat->replyText($ReplyMsg);
                        }else{
                            $wechat->replyText("你好！您的事件类型：{$data['Event']}，EventKey：{$data['EventKey']}");
                        }
                        break;
                    default:
                        $wechat->replyText("欢迎访问老黄牛服务号！您的事件类型：{$data['Event']}，EventKey：{$data['EventKey']}");
                        break;
                }
                break;
            case Wechat::MSG_TYPE_TEXT:
            
 //内容处理区块 //内容处理区块 //内容处理区块 //内容处理区块           
 //内容处理区块 //内容处理区块 //内容处理区块 //内容处理区块        
 //内容处理区块 //内容处理区块 //内容处理区块 //内容处理区块 
 
 
   $rev=$data['Content'];  
//   addlog('$rev 4-18'.$rev);

// 如果查找不到微信fromusername
if(!isset($result)){
        $userpwd=explode('+',$rev);

     $con2['user']=$userpwd['0']; //用户名
     $con2['password']=password($userpwd['1']); //密码
     $con2['nickname']=trim($userpwd['2']);
     $checkpwd=$result=M('member')->where($con2)->find();
     if($checkpwd){
        if(empty($checkpwd['wx_openid'])){
            $checkpwd['wx_openid']=$data['FromUserName'].';';    
        }else{
            $checkpwd['wx_openid']=$checkpwd['wx_openid'].$data['FromUserName'].';';
        }
        
        M('member')->save($checkpwd);
        
        $replyMsg=$checkpwd['nickname'].'您好，您已成功在本微信绑定帐号。'."\n".'您属于 '.$checkpwd['department'].' 的 '.$checkpwd['stu_class'].' 组。'."\n现有功能如下：\n1. 发送教师的姓名或短号或长号给 小王机器人;\n2. 发送关键词：集团彩云，税号，小王，收废纸，食缘等;\n3. 发送关键词天气、笑话等。\n4. 查询人文学院网站上的信息。"; 
     }else{
     $replyMsg="未绑定帐号。\n如无帐号，请先<a href=\"http://".$_SERVER['HTTP_HOST']."/index.php/Qwadmin/Lilyreg?wx_openid=".$fromuser."\">注册绑定帐号(合)</a>。\n如有帐号，可以<a href=\"http://".$_SERVER['HTTP_HOST']."/index.php/Qwadmin/Lilyreg/zhbd?wx_openid=$fromuser\">绑定帐号</a>。";
     
     }
 
    
}else{
// 如果查找到微信fromusername

// 帐号处理模块结束
$userarr=$result;
$replyMsg=R('Reply/weixin',array($userarr,$rev));


}
// 这里是文本处理，有统计两个字的话，分隔开
$replyMsgarr=explode(C('TONGJI'),$replyMsg);
if(sizeof($replyMsgarr)==2){
    $title =mb_substr($replyMsgarr[0],0,C('TITLE_LEN'),"UTF-8");
    $discription=mb_substr($replyMsgarr[0],0,NULL,"UTF-8");
    $notice_id=str_replace(C('SHOUDAO'),"",$replyMsgarr[1]);
// $url=R('Lilyweixin/weixinjumpurl',array($notice_id));  
    $url='http://'.$_SERVER['HTTP_HOST'].'/index.php/Qwadmin/Lilynoticeview/toreadpage.html?id='.$notice_id;

    // $picurl='https://www.baidu.com/img/bd_logo1.png';
    $wechat->replyNewsOnce($title, $discription, $url, $picurl);
  
}else{
    R('Reply/returnmsg',array($ReplyMsg,'weixin'));
    $wechat->replyText($replyMsg);
   
}












 //内容处理区块 //内容处理区块 //内容处理区块 //内容处理区块           
 //内容处理区块 //内容处理区块 //内容处理区块 //内容处理区块        
 //内容处理区块 //内容处理区块 //内容处理区块 //内容处理区块 
                break;
            case Wechat::MSG_TYPE_IMAGE:

                $picurl=$data['PicUrl'];
// addlog($picurl);
                $result=R('Reply/baiduocr',array($data,$wechat,$userarr));

                
                // if(isset($result['error_code'])){
                //     $wechat->replyText('无法识别的图片.');    
                // }else{
                //     $carnumber=$result['words_result']['number'];
                //     $data['MsgType']='text';
                //     $data['Content']=$carnumber;
                //     $this->demo($wechat,$data);                   
                // }
                   break;   

            
             case Wechat::MSG_TYPE_LINK:
                 addlog('接收到链接'.$data['Url']);
                 
                 break;
             case Wechat::MSG_TYPE_VOICE:
// addlog(json_encode($data));
                
                 if(!empty($data['Recognition'])){
                    $data['MsgType']='text';
                    $rev=$data['Recognition'];
                    
                    $rev=str_replace(",",'',$rev);
                    $rev=str_replace("，",'',$rev);
                    $rev=str_replace("。",'',$rev);
                    $rev=str_replace(".",'',$rev);
                    $data['Content']=$rev;
                    $result2=R('Lilyweixin/demo',array($wechat,$data));   
                 }else{
                    $result=R('Reply/baiduvoiceocr',array($data,$wechat));    
                 }
// addlog('Lilyweixin.demo.voice'.$voice);                 
                 
                 break;
            default:
                # code...
                break;
        }
    }


    

 
 


 
 
 // 微信企业号的配置
public function qy_reply($id = ''){
Vendor('wechat.qywechat','','.class.php');
$options = array(
        'token'=>'0IcfpaX3gST1LkVnJVGvzOq',	//填写应用接口的Token
        'encodingaeskey'=>'976I6VN67pCG69GyTN0l76txPsKWvgIGHuxh8GCu3Pn',//填写加密用的EncodingAESKey
         'appid'=>'wx2698f2a5170e4986',	//填写高级调用功能的appid
        // 'debug'=>true,
        // 'logcallback'=>'logg'

);
// logg("GET参数为：\n".var_export($_GET,true));
$weObj = new \Wechat($options);
$ret=$weObj->valid();
if (!$ret) {
	logg("验证失败！");
	var_dump($ret);
	exit;
}
$f = $weObj->getRev()->getRevFrom();
$t = $weObj->getRevType();
$d = $weObj->getRevData();

if(isset($d)){
    if($t=='text'){
        $replyMsg=R('Lilyweixin/qy_getmsg',array($weObj));
        // addlog($replyMsg);
        $weObj->text($replyMsg)->reply(); 
    }elseif(($t=='image')){
        $weObj->text('图片')->reply();
    }
        
        
        
    }

}    

 
 
 
 
    
public function qy_getmsg($weObj){

$t = $weObj->getRevType();
//  = $weObj->getRevData();  
$d= $weObj->getRevData(); 
$rev=$d['Content'];;   


//  帐号处理模块
$fromuser= $weObj->getRev()->getRevFrom();
$con['user']=$fromuser;
$userarr=M('member')->where($con)->find();
// addlog(json_encode($userarr));
if(empty($userarr)){
        $userarr['nickname']='人文教师';
        $userarr['stu_class']='教师';
        $userarr['department']='人文学院';
}
// $replyMsg='fdsf';
$replyMsg=R('Reply/weixin',array($userarr,$rev));  
return $replyMsg;
}

public function qy_sendmsg($weObj){
// $f = $weObj->getRev()->getRevFrom();
// $t = $weObj->getRevType();
//  = $weObj->getRevData();  
$d= $weObj->getRevData(); 
$rev=$d['Content'];;   
addlog('rev'.json_encode($rev));
//  帐号处理模块
$fromuser= $weObj->getRev()->getRevFrom();
$con['user']=$fromuser;
$userarr=M('member')->where($con)->find();
// addlog(json_encode($userarr));
$replyMsg=R('Reply/weixin',array($userarr,$rev));  
return $replyMsg;
}

    public function menu(){
           $appid = 'weixin53053067'; //AppID(应用ID)
            $token = 'weixin'; //微信后台填写的TOKEN
            $crypt = 'vqSYmZnLkMlwcRKiQcFom8d8PHpPSPOtig0RoSM2Vpo';
            $wechat = new Wechat($token, $appid, $crypt);
            $data = $wechat->request();
        echo '';
        
    }


    
public function get_user_info(){
            $appid = 'wx94144cafb998e72f'; //AppID(应用ID)
            $token = 'weixin'; //微信后台填写的TOKEN
            $crypt = '6c25b0cee6994edf5d8d965fb23df79a';

$tokenurl='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$crypt;

$access_token=json_decode(file_get_contents($tokenurl))->access_token;
pr($access_token);
// $access_token="8iAjvl6bMACj-Fb80121YUzKqOSNpRoj4qc9B2mVipr3I_u6M4GNYaZMJoMiWWKsNSUIA6tFFUYlnIGLSjriCtUqtDkmvkhwAQcfkTwK_gFzVlBb8HqnDM2GNIqEM0LZZNKfAEARFC";
$openid='oAkdFwO6WDIGdtej8susFr07I2yI';
$url2='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN ';
$userinfo=$access_token=file_get_contents($url2);;
pr(json_decode($userinfo))    ;   

        
        
    }
    
public function get_voice($media_id){
                // $appid = 'weixin53053067'; //AppID(应用ID)
            // // wxd51ed3fc02e99078
            // $token = 'weixin'; //微信后台填写的TOKEN
            // $crypt = '71f74a543778e9a9cfeee6dab0c928e2';
            $appid = 'wxd51ed3fc02e99078'; //AppID(应用ID)
            $token = 'weixin'; //微信后台填写的TOKEN
            $crypt = '71f74a543778e9a9cfeee6dab0c928e2';

$tokenurl='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$crypt;

$access_token=json_decode(file_get_contents($tokenurl))->access_token;
// pr($access_token);
// $access_token="8iAjvl6bMACj-Fb80121YUzKqOSNpRoj4qc9B2mVipr3I_u6M4GNYaZMJoMiWWKsNSUIA6tFFUYlnIGLSjriCtUqtDkmvkhwAQcfkTwK_gFzVlBb8HqnDM2GNIqEM0LZZNKfAEARFC";
// $media_id='tVJT_A7SWxEk6XT0XeWfEKlW8OS2g08xkJB9wM9Fi-Rxp2kS4swVe9-KOw4dVe4h';
$url2='http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$access_token.'&media_id='.$media_id;
$voice=file_get_contents($url2);
// pr($voicearr)    ;   
return $voice;
        
        
    }
    
    public function send_mbmsg(){
Vendor('wechat.wechat','','.class.php');
 $options = array(
			'token'=>'weixin', //填写你设定的key
			'encodingaeskey'=>'6c25b0cee6994edf5d8d965fb23df79a', //填写加密用的EncodingAESKey
			'appid'=>'wx94144cafb998e72f', //填写高级调用功能的app id
			'appsecret'=>'xxxxxxxxxxxxxxxxxxx', //填写高级调用功能的密钥
		);
	 $weObj = new Wechat($options);
   $weObj->valid();
   $type = $weObj->getRev()->getRevType();
   switch($type) {
   		case Wechat::MSGTYPE_TEXT:
   			$weObj->text("hello, I'm wechat")->reply();
   			exit;
   			break;
   		case Wechat::MSGTYPE_EVENT:
   			
   			break;
   		case Wechat::MSGTYPE_IMAGE:
   			
   			break;
   		default:
   			$weObj->text("help info")->reply();
   }

//   //获取菜单操作:
//   $menu = $weObj->getMenu();
//   //设置菜单
//   $newmenu =  array(
//   		"button"=>
//   			array(
//   				array('type'=>'click','name'=>'最新消息','key'=>'MENU_KEY_NEWS'),
//   				array('type'=>'view','name'=>'我要搜索','url'=>'http://www.baidu.com'),
//   				)
//   		);
//   $result = $weObj->createMenu($newmenu);

        
        
    }        
//学府家园自动回复 
public function xfjy($id = ''){
        //调试
        try{
            // $appid = 'weixin53053067'; //AppID(应用ID)
            $token = 'weixin'; //微信后台填写的TOKEN
            $crypt = 'vqSYmZnLkMlwcRKiQcFom8d8PHpPSPOtig0RoSM2Vpo';
            $wechat = new Wechat($token, $appid, $crypt);
            $data = $wechat->request();
            if($data && is_array($data)){
                $this->demo_xfjy($wechat, $data);
            }
        } catch(\Exception $e){
            file_put_contents('./error.json', json_encode($e->getMessage()));
        }
    }    
    private function demo_xfjy($wechat, $data){
//  帐号处理模块
$fromuser=$data['FromUserName'];
$con['wx_openid']=array('like','%'.$data['FromUserName'].';%');
$result=M('member')->where($con)->find();
$userarr=$result;
// 把查询记录也记录下来
$this->WeixinRecReceive($data,$userarr);

        switch ($data['MsgType']) {
            case Wechat::MSG_TYPE_EVENT:
                switch ($data['Event']) {
                    case Wechat::MSG_EVENT_SUBSCRIBE:
                        $wechat->replyText('欢迎您关注本微信平台！'."\n现有功能如下：\n1. 汽车车牌号到本微信号查询对应的房间号，浙J开头的车牌，可直接输入后几位。;"
                        );
                        break;
                    case Wechat::MSG_EVENT_UNSUBSCRIBE:
                        //取消关注，记录日志
                        break;
                    default:
                        $wechat->replyText("欢迎访问老黄牛服务号！您的事件类型：{$data['Event']}，EventKey：{$data['EventKey']}");
                        break;
                }
                break;
            case Wechat::MSG_TYPE_TEXT:
               
 //内容处理区块 //内容处理区块 //内容处理区块 //内容处理区块           
 //内容处理区块 //内容处理区块 //内容处理区块 //内容处理区块        
 //内容处理区块 //内容处理区块 //内容处理区块 //内容处理区块 
 
 
   $rev=$data['Content'];           


// 帐号处理模块结束
$replyMsg=R('Reply/carno_xfjy',array($rev));    
// $replyMsg=$rev;
// addlog(json_encode($replyMsg));
// $replyMsg='fdsfds';
                $wechat->replyText($replyMsg);




 //内容处理区块 //内容处理区块 //内容处理区块 //内容处理区块           
 //内容处理区块 //内容处理区块 //内容处理区块 //内容处理区块        
 //内容处理区块 //内容处理区块 //内容处理区块 //内容处理区块 
                break;
            case Wechat::MSG_TYPE_IMAGE:
                $picurl=$data['PicUrl'];
                $result=R('Reply/baiduocr',array($data,$wechat,$userarr));
// addlog(json_encode($result));
                
                if(isset($result['error_code'])){
                    $wechat->replyText('无法识别的图片.');    
                }else{
                    $carnumber=$result['words_result']['number'];
                    $data['MsgType']='text';
                    $data['Content']=$carnumber;
                    $this->demo_xfjy($wechat,$data);                   
                }
                   break;   
            
            default:
                # code...
                break;
        }
    }
    
//学府家园自动回复 
public function jw_xf($id = ''){
        //调试
        try{
            // $appid = 'weixin53053067'; //AppID(应用ID)
            $token = 'weixin'; //微信后台填写的TOKEN
            $crypt = '88e931cde31425b19931c5f6bd28558c';
            $wechat = new Wechat($token, $appid, $crypt);
            $data = $wechat->request();
            if($data && is_array($data)){
                $this->demo_jw_xf($wechat, $data);
            }
        } catch(\Exception $e){
            file_put_contents('./error.json', json_encode($e->getMessage()));
        }
    }    
    private function demo_jw_xf($wechat, $data){
        switch ($data['MsgType']) {
            case Wechat::MSG_TYPE_EVENT:
                switch ($data['Event']) {
                    case Wechat::MSG_EVENT_SUBSCRIBE:
                        $wechat->replyText('欢迎您关注本微信平台！'."\n本平台可以查教师的课程。;"
                        );
                        break;
                    case Wechat::MSG_EVENT_UNSUBSCRIBE:
                        //取消关注，记录日志
                        break;
                    default:
                        $wechat->replyText("欢迎访问老黄牛服务号！您的事件类型：{$data['Event']}，EventKey：{$data['EventKey']}");
                        break;
                }
                break;
            case Wechat::MSG_TYPE_TEXT:
               
 //内容处理区块 //内容处理区块 //内容处理区块 //内容处理区块           
 //内容处理区块 //内容处理区块 //内容处理区块 //内容处理区块        
 //内容处理区块 //内容处理区块 //内容处理区块 //内容处理区块 
 
 
   $rev=$data['Content'];           


// 帐号处理模块结束
$replyMsgtemp=R('Reply/jw_courseinfo',array($rev));   
$replyMsg=R('Reply/returnmsg',array($replyMsgtemp,'weixin'));
// $replyMsg=$rev;
// addlog(json_encode($replyMsg));
//$replyMsg='fdsfds';
                $wechat->replyText($replyMsg);




 //内容处理区块 //内容处理区块 //内容处理区块 //内容处理区块           
 //内容处理区块 //内容处理区块 //内容处理区块 //内容处理区块        
 //内容处理区块 //内容处理区块 //内容处理区块 //内容处理区块 
                break;
            
            default:
                # code...
                break;
        }
    }    
    
    
    
    
public function WeixinRecReceive($Array,$userarr){
if(empty($userarr)){
    $data['qq']='微信:未知用户';
}else{
    $data['qq']='微信:'.$userarr['nickname'];
}
if(empty($Array['Content'])){
    $data['msg']='图片信息';     
}else{
    $data['msg']=$Array['Content']; 
}



$data['rs']='微信';
$data['time']=time();
// $data['qq']=$Array->{'QQ'};
M('recivedqqmsgs')->add($data);
return $data;
}    


// 要删除// 要删除// 要删除// 要删除// 要删除// 要删除
public function TplMsg2($notice_id='90032'){
        $NotReadarr=GetNotRead($notice_id);
// pr($NotReadarr);  
// openid清洗，把用不着的洗去
        foreach ($NotReadarr as $key=>$value) {
            $temp=explode(';',$value['wx_openid']);
            foreach ($temp as $value2) {
                if(!empty($value2) && substr($value2,0,3)==C('OPENIDFIRST3')){
                    // $sendopenids[]=$value2;    
                    $NotReadarr[$key]['wx_openid']=$value2;
                }
            }
        }
$sendopenids=array_column($NotReadarr,'wx_openid');        
pr($sendopenids);
// pr($NotReadarr);

    
        $con['id']=$notice_id;
        $notices=M('notice')->where($con)->find();
        
    
    
    
    
    $appid=C('APPID');
    $appsecret=C('APPSECRET');
    $json_token=http_request("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret);
    $access_token=json_decode($json_token,true);
    //获得access_token
    
    $this->access_token=$access_token[access_token];
    $tokenkey=$this->access_token;

foreach ($NotReadarr as $touserarr) {
    //模板消息	
    $template=array(
    'touser'=>$touserarr['wx_openid'],
    'template_id'=>"EfS-pdAN9tW1loNmLVQ1c9GiWJ1iyGg0BPoqXbEvMYU",
    'url'=>R('Lilyweixin/weixinjumpurl',array($notice_id)),
    'topcolor'=>"#7B68EE",
    'data'=>array(
        'first'=>array('value'=>$notices['title'], "color"=>"#FF0000"),
        'keyword1'=>array('value'=>'本信息在微信中打开可以免登陆查看'),
        'keyword2'=>array('value'=>'但不能在QQ及网页中查看'),
        'keyword3'=>array('value'=>GetName($notices['sender'],'user'), "color"=>"#FF0000"),
        'keyword4'=>array('value'=>date("Y-m-d H:i:s",$notices['creattime'])),
        'remark'=>array('value'=>$notices['content'], "color"=>"#FF0000"),
    )
    );
    $json_template=json_encode($template);
// echo $json_template;
// echo $this->access_token;
    $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$tokenkey;
    $res=http_request($url,urldecode($json_template));
    if ($res[errcode]=='0') echo '模板消息发送成功!';
pr($res);
}


}


   
//获得id对应的微信跳转URL
public function weixinjumpurl($notice_id){
    $url='http://'.$_SERVER['HTTP_HOST'].'/index.php/Qwadmin/Lilynoticeview/toreadpage.html?id='.$notice_id;
// $url='http://r34.cc/index.php/Qwadmin/Lilynoticeview/toreadpage.html?id=220';
// https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx94144cafb998e72f&redirect_uri=http://r34.cc/index.php/Qwadmin/Lilynoticeview/toreadpage.html?id=220&response_type=code&scope=snsapi_base&state=123#wechat_redirect

    $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx94144cafb998e72f&redirect_uri=".$url."&response_type=code&scope=snsapi_base&state=123#wechat_redirect";     


return $url;
}





//小程序用code获得openid,uionid
public function Miniproinitialize(){
$code= I('post.code');
$encryptedData=I('post.encryptedData');
$iv = I('post.iv');     
$openid=R('Lilyweixin/MiniproGetOpenId',array($code,$encryptedData,$iv));
// addlog('openid = '.$openid);
if(!empty($openid)){
    
    $con['wx_openid']=array('like',"%".$openid."%");    
    // $userarr=M('member')->where($con)->filed("")->find();
    // $dataarr['Req_URL']="/";
    // $dataarr['ErrJumpURL']="/";
    // $dataarr['username']=$userarr['user'];
    // $dataarr['password']=$userarr['password'];
    // R('Login/login',array($dataarr));
    
    $con['wx_openid']=$openid;
    echo json_encode($con);
}else{
    echo "can't find openid";
}


// addlog("111111111".json_encode($userarr));

}





//小程序用code获得openid,uionid
public function MiniproGetOpenId($code,$encryptedData,$iv){
require './ThinkPHP/Library/Vendor/wxdecode/wxBizDataCrypt.php';  




// 小程序和公众号的id不一样
    $appid = C('MINI1');
    $appsecret=C('MINI1SECRET');  

// 获取$sessionKey
 $apiUrl = "https://api.weixin.qq.com/sns/jscode2session?appid=$appid&secret=$appsecret&js_code=$code&grant_type=authorization_code";
// addlog('wxUserInfo.$apiUrl'.$apiUrl);
 $apiData = json_decode(http_request($apiUrl));
// addlog('wxUserInfo.apidata'.json_encode($apiData));
$sessionKey=$apiData->{'session_key'};
$openid=$apiData->{'openid'};

// 获取对应的openid
if(isset($sessionKey)) {
return $openid;
    
}
//     $pc = new \WXBizDataCrypt($appid, $sessionKey);
//     $errCode =$pc->decryptData($encryptedData, $iv, $rdata );

// addlog($rdata);
// $rdata=json_decode($rdata);
// $unionId=$rdata->{'unionId'};
//     if ($errCode == 0) {
//         $echor=$unionId;
//     } else {
//         $echor=json_encode($errCode);
//     }
//  }else{
//     $echor='errorcode error2.';
//  }

// return $echor;

}


//用code获得openid
public function weixincodegetopenid($code='0'){
if($code=='0'){
    $code=I('post.code');
}
if($code){
    $appid = C('APPID');
    $token = C('TOKEN');
    $crypt = C('CRYPT');
    $appsecret=C('APPSECRET');
addlog('Lilyweixin.weixincodegetopenid.$code'.json_encode($code));      
     
    $tokenurl="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code=".$code."&grant_type=authorization_code";
    $access_tokenarr=json_decode(file_get_contents($tokenurl));
addlog('Lilyweixin.weixincodegetopenid.$access_tokenarr'.json_encode($access_tokenarr));    
    $openid=$access_tokenarr->openid;
addlog('Lilyweixin.weixincodegetopenid.openid'.$openid);      
addlog('Lilyweixin.weixincodegetopenid.$access_tokenarr'.json_encode($access_tokenarr));          
}else{
    $openid='weixincodegetopenid code 不存在';
}
    return  $openid; 
}      

// public function qy_reply2($id = ''){
// Vendor('wechat.qywechat','','.class.php');
// $options = array(
//         'token'=>'0IcfpaX3gST1LkVnJVGvzOq',	//填写应用接口的Token
//         'encodingaeskey'=>'976I6VN67pCG69GyTN0l76txPsKWvgIGHuxh8GCu3Pn',//填写加密用的EncodingAESKey
//          'appid'=>'wx2698f2a5170e4986',	//填写高级调用功能的appid
//         // 'debug'=>true,
//         // 'logcallback'=>'logg'

// );
// // logg("GET参数为：\n".var_export($_GET,true));
// $weObj = new \Wechat($options);
// $ret=$weObj->valid();
// if (!$ret) {
// 	logg("验证失败！");
// 	var_dump($ret);
// 	exit;
// }
// $f = $weObj->getRev()->getRevFrom();
// $t = $weObj->getRevType();
// $d = $weObj->getRevData();
// // if(isset($d)){
//     $replyMsg=R('Lilyweixin/qy_getmsg',array($weObj));
//     addlog($replyMsg);
//     $weObj->text($replyMsg)->reply(); 
//     $userinfo=$weObj->getUserInfo('2013014');
//     addlog('$userinfo'.json_encode($userinfo));
// // }else{
//     // $SendMsgResult=R('Lilyweixin/qy_sendmsg',array($weObj));
// 	$MsgData=array(
// 	         "touser" => "2013014",
// 	         "agentid" => "21",	//应用id
// 	         "msgtype" => "text",  //根据信息类型，选择下面对应的信息结构体
	
// 	         "text" => array(
// 	                 "content" => "Holiday Request For Pony(http://xxxxx)"
// 	         ),
// 			 );
// 	$SendMsgResult=$weObj->sendMessage($MsgData);	
// 	addlog('$SendMsgResult'.json_encode($SendMsgResult));
// // }

// }      





// // 微信企业号的配置
// public function qy_replyold2($id = ''){
// Vendor('wechat.qywechat','','.class.php');
// $options = array(
//         'token'=>'0IcfpaX3gST1LkVnJVGvzOq',	//填写应用接口的Token
//         'encodingaeskey'=>'976I6VN67pCG69GyTN0l76txPsKWvgIGHuxh8GCu3Pn',//填写加密用的EncodingAESKey
//          'appid'=>'wx2698f2a5170e4986',	//填写高级调用功能的appid
//         // 'debug'=>true,
//         // 'logcallback'=>'logg'

// );
// // logg("GET参数为：\n".var_export($_GET,true));
// $weObj = new \Wechat($options);
// $ret=$weObj->valid();
// if (!$ret) {
// 	logg("验证失败！");
// 	var_dump($ret);
// 	exit;
// }
// $f = $weObj->getRev()->getRevFrom();
// $t = $weObj->getRevType();
// $d = $weObj->getRevData();

// if(isset($d)){
//     $replyMsg=R('Lilyweixin/qy_getmsg',array($weObj));
//     addlog($replyMsg);
//     $weObj->text($replyMsg)->reply(); 
// }else{
//     // $SendMsgResult=R('Lilyweixin/qy_sendmsg',array($weObj));
// 	$MsgData=array(
// 	         "touser" => "2013014",
// 	         "agentid" => "13",	//应用id
// 	         "msgtype" => "text",  //根据信息类型，选择下面对应的信息结构体
	
// 	         "text" => array(
// 	                 "content" => "Holiday Request For Pony(http://xxxxx)"
// 	         ),
// 			 );
// 	$SendMsgResult=$weObj->sendMessage($MsgData);	
// 	pr($SendMsgResult);
// }

// }    
    
    // 结尾处
}












