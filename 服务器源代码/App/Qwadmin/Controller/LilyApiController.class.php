<?php
/**
*

*
**/
namespace Qwadmin\Controller;
use Think\Controller;
class LilyApiController extends Controller{
// namespace Qwadmin\Controller;
// use Qwadmin\Controller\ComController;
// class InfoController extends ComController {

public function index(){
$realauprice=$this->getrealauprice();
$reallinhaiweather=$this->getreallinhaiweather();
// echo $realauprice."".$reallinhaiweather;
echo $realauprice;
} 

//获取实时黄金价格
public function getrealauprice(){
$apiurl1="https://api.jijinhao.com/quoteCenter/realTime.htm?codes=JO_62286";
$data1 = curlurl($apiurl1); 
$data1=str_replace("var quote_json = ","",$data1);
$dataarr1=json_decode($data1,"false");

$au=$dataarr1['JO_62286']['q63'];
$autime=$dataarr1['JO_62286']['time'];

$autext=date("m-d H:i",$autime/1000)."为【".$au."】";
return $autext;
}

//获取实时临海天气
public function getreallinhaiweather22(){
$url="http://www.tz121.com/linhai/Forecast/Local/Short";
$content=curlurl($url);
pr($content);
// 正则表达式
$left='短期预报'; $right='沿海';
$result1=RegGetcontentFirst($left,$right,$content);
pr($result1);
$left='今天'; $right='度。';
$weather="今天".RegGetcontentFirst($left,$right,$result1).'度。';
$left='年'; $right='发布';
$ftime=RegGetcontentFirst($left,$right,$result1);
$aa=$autext."\n<br>"."".$ftime."临海气象台发布：<br>".$weather."";

return $aa;
}


//获取实时临海天气
public function getreallinhaiweather(){
$url="http://www.linhai.gov.cn/art/2020/12/16/art_1478632_59001912.html";
$content=file_get_contents($url);
// 正则表达式
$left='<p style="text-indent: 2em;">'; $right='</p>';
$result1=RegGetcontentFirst($left,$right,$content);
$left='今天'; $right='度。';
$weather="今天".RegGetcontentFirst($left,$right,$result1).'度。';
$left='年'; $right='发布';
$ftime=RegGetcontentFirst($left,$right,$result1);
$aa=$autext."\n<br>"."".$ftime."临海气象台发布：<br>".$weather."";

return $aa;
}
// 获取人文学院教师信息
public function getrwinfo1($url="http://rw.r34.cc/index.php/Qwadmin/Rwxy/echojson/conall/%3B%E6%95%B0%E6%8D%AE%E8%A1%A8%E5%90%8D%E7%AD%89%E4%BA%8E%E4%BA%BA%E6%96%87%E6%95%99%E5%B8%88%E7%8A%B6%E6%80%81%E6%95%B0%E6%8D%AE%3B%E6%9F%A5%E7%9C%8B%E5%AF%86%E7%A0%81%E7%AD%89%E4%BA%8Etemp1234"){

$firstarr = $this->geturlfirstarr($url);    
$out=$this->echojsonalltypes($firstarr,"text");
echo $out;
}

// 获取人文学院教师信息
public function getrwinfo2($url="http://rw.r34.cc/index.php/Qwadmin/Rwxy/echojson/conall/%3B%E6%95%B0%E6%8D%AE%E8%A1%A8%E5%90%8D%E7%AD%89%E4%BA%8E%E4%BA%BA%E6%96%87%E6%95%99%E5%B8%88%E7%8A%B6%E6%80%81%E6%95%B0%E6%8D%AE2%3B%E6%9F%A5%E7%9C%8B%E5%AF%86%E7%A0%81%E7%AD%89%E4%BA%8Etemp1234"){
$firstarr = $this->geturlfirstarr($url);    
// pr($firstarr);
$out=$this->echojsonalltypes($firstarr,"texttable");
echo $out;
}

// 获取人文学院教师信息
public function getrwinfo3($url="http://rw.r34.cc/index.php/Qwadmin/Rwxy/echojson/conall/%3B%E6%95%B0%E6%8D%AE%E8%A1%A8%E5%90%8D%E7%AD%89%E4%BA%8E%E4%BA%BA%E6%96%87%E6%95%99%E5%B8%88%E7%8A%B6%E6%80%81%E6%95%B0%E6%8D%AE2%3B%E6%9F%A5%E7%9C%8B%E5%AF%86%E7%A0%81%E7%AD%89%E4%BA%8Etemp1234"){
$firstarr = $this->geturlfirstarr($url);    
$out=$this->echojsonalltypes($firstarr,"twotable");
echo $out;
}



// 获取arr的第一个信息
public function echojsonalltypes($arr,$type="text"){
switch ($type) {
    case 'twotable':
        return h5html("",h5twotable($arr),"empty");
        break;
    case 'keyvalue':
        foreach ($arr as $key=>$value) {
            $out.="[".$key."]".$value."<br/>";
        }
        return $out;
        break;
    case 'texttable':
        $i=0;
        foreach ($arr as $key=>$value) {
            $i++;
            $ktext=$this->texttable($key,8);
            $kvalue=$this->texttable($value,8);
            $out.=$ktext.$kvalue;
            if($i % 2==0){
                $out.="<br/>";
            }
        }
        return $out;
        break;        
    default:
        foreach ($arr as $key=>$value) {
            $out.=$value;
            break;
        }
        return $out;
        break;
}

}


// 获取arr的第一个信息
public function geturlfirstarr($url,$field="arr"){
$data1 = curlurl($url); 
$dataarr1=json_decode($data1,"false");
$firstarr=$dataarr1[$field]['0'];
return $firstarr;
}


// 获取arr的第一个信息
public function texttable($text,$belen){
 $text_len = mb_strlen($text,'UTF8'); 

//  $space = '&nbsp; ';
$space1 = '&nbsp; ';
$space2 = '&nbsp; ';
 if($text_len>$belen){
    return $text;
 }else{
  
    $fend=floor(($belen-$text_len)/2)-1;
     for($i=0; $i<=$fend; $i++){
      $zero1 .= $space1;
     }  
    $estart=$text_len+$fend;
     for($i=$estart+1; $i<$belen; $i++){
      $zero2 .= $space2;
     } 
 $newtext=$zero1.$text.$zero2;
 }
// pr($text_len,'$text_len');
// pr($belen,'$belen');   
// pr($newtext,'$newtext');   
return $newtext;
}




	
}