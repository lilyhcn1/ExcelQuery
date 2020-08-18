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

    
    // 结尾处
}












