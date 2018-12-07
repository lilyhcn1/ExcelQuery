<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2015-09-17
* 版    本：1.0.0
* 功能说明：模块公共文件。
*
**/


function UpImage($callBack="image",$width=100,$height=100,$image=""){
    echo '<iframe scrolling="no" frameborder="0" border="0" onload="this.height=this.contentWindow.document.body.scrollHeight;this.width=this.contentWindow.document.body.scrollWidth;" width='.$width.' height="'.$height.'"  src="'.U('Upload/uploadpic').'?Width='.$width.'&Height='.$height.'&BackCall='.$callBack.'&Img='.$image.'"></iframe>
         <input type="hidden" name="'.$callBack.'" id="'.$callBack.'">';
}
function BatchImage($callBack="image",$height=300,$image=""){
    echo '<iframe scrolling="no" frameborder="0" border="0" onload="this.height=this.contentWindow.document.body.scrollHeight;this.width=this.contentWindow.document.body.scrollWidth;" src="'.U('Upload/batchpic').'?BackCall='.$callBack.'&Img='.$image.'"></iframe>
		<input type="hidden" name="'.$callBack.'" id="'.$callBack.'">';
}


/*
 * 函数：网站配置获取函数
 * @param  string $k      可选，配置名称
 * @return array          用户数据
*/
function setting($k=''){
	if($k==''){
        $setting =M('setting')->field('k,v')->select();
		foreach($setting as $k=>$v){
			$config[$v['k']] = $v['v'];
		}
		return $config;
	}else{
		$model = M('setting');
		$result=$model->where("k='{$k}'")->find(); 
		return $result['v'];
	}
}

/**
 * 函数：格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '') {
	$units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
	for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
	return round($size, 2) . $delimiter . $units[$i];
}




function logg($text){
    file_put_contents('./weixin/log.txt',$text."\r\n\r\n",FILE_APPEND);
}

function shorturl($url,$d_url='d/dwz_api.php'){
    // if(substr($d_url,1,4) !='http'){
        
    // }    
    $d_urlall='http://'.$_SERVER['SERVER_NAME'].'/'.$d_url;
    $uu=$d_urlall.'?url='.$url;
    $short_url=file_get_contents($uu);
    return $short_url;
 
}
function shorturlbyid($id,$d_url='d/dwz_api.php'){
    
     $url='http://'.$_SERVER['SERVER_NAME'].'/index.php/Qwadmin/Lilynoticeview/toreadpage.html?id='.$id;
    return shorturl($url);
 
}


// 从URL对应的网址取回内容，并利用正则表达式取出数组
function GetURLContentArr($url,$zz_title){
// $url='http://wx.lhweather.com/';
$sitecontent=file_get_contents($url);
// pr1($sitecontent);
// pr1($zz_title);
preg_match_all($zz_title,$sitecontent,$titleresult);
// pr1($titleresult);
// 分别提取标题和URL
unset($titleresult[0]);
// pr1($titleresult);
if(isset($titleresult)){
    foreach ($titleresult as $k1=>$v1) {
        foreach ($v1 as $k2=>$v2) {
            $r[$k2][$k1]=strip_tags($titleresult[$k1][$k2]);
        }
    }    
}    
 return   $r;
}

function characettouft8($data){
if( !empty($data) ){
    $fileType = mb_detect_encoding($data , array('UTF-8','GBK','LATIN1','BIG5')) ;
    if( $fileType != 'UTF-8'){
        $data = mb_convert_encoding($data ,'utf-8' , $fileType);
    }
    }
    return $data;
}

function bootstrappage($content){
$page=11;
}

/**
 * 函数：加密
 * @param string            密码
 * @return string           加密后的密码
 */
function password($password){
	/*
	*后续整强有力的加密函数
	*/
	return md5('Q'.$password.'W');

}




function shorturlself($d_url='d/dwz_api.php'){
        
        $d_urlall='http://'.$_SERVER['SERVER_NAME'].'/'.$d_url;
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
    $uu=$d_urlall.'?url='.$url;
  $short_url=file_get_contents($uu);
    return $short_url;
 
}

/**
*
* 函数：二维数组转逗号字符串
* @param  array $array   二级数组。
* @param  str $col  二级数组的列指标
*
**/
function twoarraytostr ($array,$col='id',$separator=','){
    $array2=array_column($array,$col);
    $str=arraytostr($array2,',');


    return $str;
}
/**
*
* 函数：二维数组降重，去除重复的项，但返回还是二维数组
* @param  array $twoarray   二级数组。
* @param  str $col  二级数组的列指标
*
**/
function twoarray_unique ($twoarray,$col='id'){
    $onearrayunique=array_unique(array_column($twoarrayunique,$col));
    foreach ($twoarray as $key => $arraytemp) {
       if(!in_array($arraytemp[$col],$onearrayunique) ){
           $twoarrayunique[$key]=$arraytemp;
           $onearrayunique=array_unique(array_column($twoarrayunique,$col));
       }
    }
    return $twoarrayunique;
}
/**
*
* 函数：二维标签中文化，英文的看着不舒服
* @param  array $twoarray   二级数组。
* @param  str $keyarray  二级数组的列指标
*
**/
function twoarray_chinese ($twoarray,$keyarray){
   $allkeys=array_keys($keyarray);
    foreach ($twoarray as $key1 => $arraytemp) {
        foreach ($arraytemp as $key2=>$value) {
            if(in_array($key2,$allkeys) && !empty($value)){
                $newtwoarray[$key1][$keyarray[$key2]]=$value;
            }
        }
    }
    return $newtwoarray;
}


/**
*
* 函数：删除空字段
*
**/
function delemptyfield($array){
    foreach($array as $key=>$value){
        if(!empty($value)){
            $newarr[$key]=$value;
        }
    }
    return $newarr;
}

/**
*
* 函数：删除空字段
*
**/
function delemptyfieldgetnew($array){
    $i=0;
    foreach($array as $key=>$value){
        if(!empty($value)){
            $newarr[$i++]=$value;
        }
    }
    return $newarr;
}

// 数组与对象的互转
function array2object($array) {
  if (is_array($array)) {
    $obj = new StdClass();
    foreach ($array as $key => $val){
      $obj->$key = $val;
    }
  }
  else { $obj = $array; }
  return $obj;
}
function object2array($object) {
  if (is_object($object)) {
    foreach ($object as $key => $value) {
      $array[$key] = $value;
    }
  }
  else {
    $array = $object;
  }
  return $array;
}

//一维向量转成字符串
function arraytostr ($array,$separator='<br>'){
    $str='';
    foreach($array as $k=>$v){
        $array[$k]=trim($v);
    }
$str=implode(',',$array);
   
    return $str;
}
//一维向量转成字符串new
function arraytostrnew ($array,$separator='<br>'){
    $str='';
    foreach($array as $k=>$v){
        $array[$k]=trim($v);
    }
$str=implode($separator,$array);

    
    return $str;
}
function twoarraytostr_format ($twoarray,$classcol='stu_class',$showcol='nickname',$strlen='100'){
    $arraylen=count($twoarray);
    //  pr($classcol);
    //  echo '<hr>';
$output="";  $temp='';  $flag=0;
// $output=$output.'<red>'.$twoarray['0']['stu_class'].":</red> ";
foreach($twoarray as $key=>$value){
        
    //  echo 'aaaaaaaaaaaaaaaaaaaa';
    // echo $value["$classcol"];
    // pr1($value);
    // pr1($classcol);
    //  echo 'aaaaaaaaaaaaaaaaaaaa';
    if($temp!=$value[$classcol] && $arraylen> $strlen){
           $output=$output. "<b> 【".$value[$classcol]."】</b> ";
        //   echo 'aaaaaaaaaaaaaaaaaaaa';
        // //   echo $output;
        //   echo $temp;
        //   echo $value[$classcol];
   }
    $flag=$flag+1;
    $output=$output.$value[$showcol].",";
    $temp=$value[$classcol];
    //  echo 'bbbbbbbbbbbbbb';
    //       echo $temp.'temp<br>';
    //       echo $value['stu_class'].'value<br>';
    //       echo $output.'output<br>';;
    //       echo '<hr>';
           
           

}
return $output;
}
/**
 * 数组格式输出
 * 
 */
function format_short_str($data,$user){
$output="<hr>";
$flag=0;
foreach($data as $key=>$value){
    // user,nickname,stu_class
    if($user['stu_class']==$value['stu_class']){
       if( $flag==0){
           $output=$output. $user['stu_class'].": ";
        }
        $output=$output.$value['nickname'].",";
        $flag=$flag+1;
 
    }

}
    $output=$output."<br>";
    return $output;    
}


// 就是输出二级数组一些字	
function echoarrresult($arr,$title=''){

$content=echoarrcontent($arr);
return h5page($title,$content);
 
}


// 就是输出二级数组的表格形式	
function echoarrcontent($arr){
foreach ($arr as $k1=>$v1) {
    if(empty($v1)){
        unset($arr[$k1]);
    }
}
 if(empty($content)){
 $content='
 	
<table class="table  table-bordered">
	<tbody>';     
    foreach ($arr as $key => $value) {
     $content.='		<tr><td  align="center" width="30%">'.$key.'</td><td  align="left" valign="middle" width="70%">'.$value.
		'</td></tr>';
    } 
   $content.='

	</tbody>
</table>'; 
 }

return $content;
 
}




// 返回用户所在部门的
function GetDpCon($user){
   if($user['uid']==1){
       $departmentcon='';
   }else{
       $departmentcon['department']=$user['department'];
   }
    return $departmentcon;
    
} 
// 查qq返回用户数组
function qqGetuser($qq){
$con['qq']=$qq;
$user=M('Member')->where($con)->find();
return $user;
    
} 

// 生成快捷方式
function gen_shortcut ($url,$filename="通知快捷方式"){
if (!preg_match("/^(http|ftp|https):\/\//", $url)) {
		$url = 'http://'.$_SERVER['HTTP_HOST'].$url;
} 
$Shortcut = "[InternetShortcut]
URL=".$url."
IDList=
[{000214A0-0000-0000-C000-000000000046}]
Prop3=19,2
";
$ua = $_SERVER["HTTP_USER_AGENT"];
$filename=$filename.".url";
// $filename = "网站名称.url";
$encoded_filename = urlencode($filename);
$encoded_filename = str_replace("+", "%20", $encoded_filename);
header('Content-Type: application/octet-stream');
if(preg_match("/MSIE/", $ua)){
    header('Content-Disposition: attachment; filename="'.$encoded_filename.'"');
}else if(preg_match("/Firefox/", $ua)){
    header('Content-Disposition: attachment; filename*="utf8\'\''.$filename.'"');
}else{
    header('Content-Disposition: attachment; filename="'.$filename.'"');
}
echo $Shortcut;
}







// 这样在模版中调用的话，只需要用 {$vo.title|subtext=10} 这样即可，同时实现了，如果没超出长度，则不追加省略号的效果。

function subtext($text, $length)
{
    if(mb_strlen($text, 'utf8') > $length) 
    return mb_substr($text, 0, $length, 'utf8').'...';
    return $text;
}


//传递数据以易于阅读的样式格式化后输出
//传递数据以易于阅读的样式格式化后输出
function pr($data,$title=''){
    // 定义样式
    $str=' <meta charset="UTF-8">  <pre style="display: block;padding: 9.5px;margin: 44px 0 0 0;font-size: 13px;line-height: 1.42857;color: #333;word-break: break-all;word-wrap: break-word;background-color: #F5F5F5;border: 1px solid #CCC;border-radius: 4px;">';
    $str=$str.$title.'<br>';
    // 如果是boolean或者null直接显示文字；否则print
    if (is_bool($data)) {
        $show_data=$data ? 'true' : 'false';
    }elseif (is_null($data)) {
        $show_data='null';
    }else{
        $show_data=print_r($data,true);
    }
    $str.=$show_data;
    $str.='</pre>';
    echo $str;
}


/**
 * 导入excel文件
 * @param  string $file excel文件路径
 * @return array        excel文件内容数组
 */
function import_excel($file){
    // 判断文件是什么格式
    $type = pathinfo($file); 
    $type = strtolower($type["extension"]);
    $type=$type==='csv' ? $type : 'Excel5';
    ini_set('max_execution_time', '0');
    Vendor('PHPExcel.PHPExcel');
    // 判断使用哪种格式
    $objReader = PHPExcel_IOFactory::createReader($type);
    $objPHPExcel = $objReader->load($file); 
    $sheet = $objPHPExcel->getSheet(0); 
    // 取得总行数 
    $highestRow = $sheet->getHighestRow();     
    // 取得总列数      
    $highestColumn = $sheet->getHighestColumn(); 
    //循环读取excel文件,读取一条,插入一条
    $data=array();
    //从第一行开始读取数据
    for($j=1;$j<=$highestRow;$j++){
        //从A列读取数据
        for($k='A';$k<=$highestColumn;$k++){
            // 读取单元格
            $data[$j][]=$objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue();
        } 
    }  
    return $data;
}


/**
 * 数组转xls格式的excel文件
 * @param  array  $data      需要生成excel文件的数组
 * @param  string $filename  生成的excel文件名
 *      示例数据：
        $data = array(
            array(NULL, 2010, 2011, 2012),
            array('Q1',   12,   15,   21),
            array('Q2',   56,   73,   86),
            array('Q3',   52,   61,   69),
            array('Q4',   30,   32,    0),
           );
 */
function create_xls($data,$title = null,$filename='member'){
    // $data, $savefile = null, $title = null, $sheetname = 'sheet1'
    ini_set('max_execution_time', '0');
    Vendor('PHPExcel.PHPExcel');
    $filename=str_replace('.xls', '', $filename).'.xls';

    //若指字了excel表头，则把表单追加到正文内容前面去 
    if (is_array($title)) { 
        array_unshift($data, $title); 
    } 
    $phpexcel = new PHPExcel();

// $data=twoarraytotext($data);

    $phpexcel->getProperties()
        ->setCreator("Maarten Balliauw")
        ->setLastModifiedBy("Maarten Balliauw")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");
    // if($pw!=''){


    // }
    $phpexcel->getActiveSheet()->fromArray($data);
    $phpexcel->getActiveSheet()->setTitle('Sheet1');
    $phpexcel->setActiveSheetIndex(0);
    
// $phpexcel->getSecurity()->setLockWindows(true);
// $phpexcel->getSecurity()->setLockStructure(true);
// $phpexcel->getSecurity()->setWorkbookPassword('vvdcsfds');

 
//  $phpexcel->getActiveSheet()->getProtection()->setPassword('PHPExcel');
// $phpexcel->getActiveSheet()->getProtection()->setSheet(true);
// $phpexcel->getActiveSheet()->getProtection()->setSort(true);
// $phpexcel->getActiveSheet()->getProtection()->setInsertRows(true);
// $phpexcel->getActiveSheet()->getProtection()->setFormatCells(true);
    
    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;filename=$filename");
// header("Content-Disposition: attachment;filename=dfdf.xlsx");    
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0
 
 



    $objwriter = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel5');
    $objwriter->save('php://output');
    exit;
}

/**
 * 发送短信
 * @param  string $sendto 发送对象，可以多人，只要用，分开
 * @param  string $msg 内容
 */
function send_phone($sendto,$msg,$uid){
    // $gxt_id=C('GXT_SCHOOLID');
    // $gxt_user=C('GXT_USERNAME');
    // $gxt_pw=C('GXT_PASSWORD');
    	$opt=UidGetSendOpt($uid) ; 
	    $gxt_id=$opt['gxtid'];
	    $gxt_user=$opt['gxtuser'];
	    $gxt_pw=$opt['gxtpw'];
	    $sendto=trim($sendto);
// addlog('function/send_phone.$uid'.json_encode($uid));    
    if(empty($gxt_id) || empty($gxt_user) || empty($gxt_pw) ){
        return array("error"=>1,"message"=>'高校通配置不完整');
    }
    if(empty($sendto) ){
        return array("error"=>1,"message"=>'发送对象为空');
    }
    $msg=UrlEncode($msg);
            $sendstr='http://www.gxtcn.com/interface.php?action=sms&operation=send&schoolid='.$gxt_id.'&username='.$gxt_user.'&password='.$gxt_pw.'&servicecode=yx&mobile='.$sendto.'&content='.$msg;
            // dump( $sendstr);
            // dump($msg);
            // echo UrlEncode($sendstr);
            $sendresult=file($sendstr);
            return($sendresult);
}



/**
 * 发送短信后返回的信息处理
 * @param  string $sendresult 发送短信后返回的信息
 */
function send_phone_msg_deal($sendresult,$notice_id){
  	  $msg_two_array=rec_msg_to_array($sendresult);
addlog('send_phone_msg_deal.$sendresult'.arraytostr($sendresult));

      $msg_two_array2=GetSuccessSend($msg_two_array);
// addlog('send_phone_msg_deal.$msg_two_array2'.$msg_two_array2);
  	  $msg_str=twoarraytostr($msg_two_array2,0,',');
// pr($msg_str);
  	  $phoneusers=GetName($msg_str,'phone',',','nickname');
// addlog('send_phone_msg_deal.$msg_str'.$msg_str);  	  
  	  $phoneids=GetName($msg_str,'phone',',','user');
// addlog('send_phone_msg_deal.$phoneids'.$phoneids);
      $phoneids_array=explode(',',$phoneids);
      
foreach ($phoneids_array as $touserid) {
        $sendresult=date('H:i',time()).'短信';
        // 把发送记录记录数据库
        sendrec2db($sendresult,$notice_id,$touserid);
}      
      
// echo '$phoneids_array';
// pr($phoneids_array);     
// addlog('send_phone_msg_deal.$phoneids_array'.json_encode($phoneids_array));
      $notread_twoarray=GetNotRead($notice_id);
      $notread_array=array_column($notread_twoarray,'user');
// echo '$notread_array';      
// pr($notread_array);      
      $not_success_id_array=array_diff($notread_array,$phoneids_array);

// echo '$not_success_id_array';      
// pr($not_success_id_array); 
      $not_success_id_str=arraytostr($not_success_id_array,',');
// echo '$not_success_id_str';      
// pr($not_success_id_str); 
      $not_success_name=GetName($not_success_id_str,'user',',','nickname');
// echo '$not_success_name';      
// pr($not_success_name); 
	  $returndata="短信发送时间：".date("Y-m-d H:i:s")
	              .'<br>'."失败：".$not_success_name
            	  .'<br>'."成功发送：".$phoneusers;

// 找出原来的数据库里写的
$condd['id']= $notice_id;
$dd= M('notice')->where($condd)->find();
$dd2=json_encode($dd['phone_send_rec']);
 	  $data['id']=$id;
 	  $data['phone_send_rec']=$returndata.$dd2."<hr><hr>".json_encode($msg_two_array);
// pr1($data);
 	  M('notice')->data($data)->save();	  
      return $returndata;
}

/**
 * 把发送结果记录数据库
 * @param  string $sendresult 发送结果
 */
function sendrec2db($sendresult,$notice_id,$user){
        $con6['notice_id']=$notice_id;
        $con6['reader']=$user;    
// addlog('TplMsg'.json_encode($con6));        
        $readrec=M('notice_read')->where($con6)->find();
// addlog('TplMsg'.json_encode($readrec));
if(isset($readrec['sendrec'])){
    $readrec['sendrec']=$readrec['sendrec'].'->';
}
        if($readrec){
          $readrec['sendrec']=  $readrec['sendrec'].$sendresult;
            M('notice_read')->save($readrec);
        }    
    
    
}

/**
 * 把接收到数据记录数据库
 * @param  $rev 接收结果
 */
function revrec2db($rev,$notice_id,$userarr){
        $con6['notice_id']=$notice_id;
        
        
}


/**
 * 发送短信后返回的向量中得到发送成功的
 * @param  string $sendresult 发送短信后返回的向量
 */
function GetSuccessSend($msgarray){
foreach($msgarray as  $key=>$value){
    if(trim($value[3]) !='成功'){
        unset($msgarray[$key]);
    }
}
    return $msgarray;
}

/**
 * 接收短信 并转成二维向量
 */
function rec_msg_to_array($msgarraytemp){

     foreach($msgarraytemp as $k=>$v){
         $msgarray[$k]=explode("\t",$msgarraytemp[$k]);
     } 
     return $msgarray;
}








/**
 * 发送阿里大于的验证码 大鱼
 * @$RecNum  接收短信的号码
 * @$code  验证码
 */
function send_dayusms($RecNum,$code){
include('./ThinkPHP/Library/Vendor/dayu/TopSdk.php');  
$appkey=C('DAYUSMSAPPKEY');
$secret=C('DAYUSMSSECRET');


$c = new TopClient;
$c ->appkey = $appkey ;
$c ->secretKey = $secret ; 
$req = new AlibabaAliqinFcSmsNumSendRequest;
$req ->setExtend( "" );
$req ->setSmsType( "normal" );
$req ->setSmsFreeSignName( '老黄牛' );
$req ->setSmsParam( "{code:'".$code."'}" );
$req ->setRecNum( $RecNum );
$req ->setSmsTemplateCode( "SMS_45110016" );
$resp = $c ->execute( $req );
pr($req);
pr($resp);
    
}



function readOnlyExcel($file,$type='Excel2007'){
    Vendor('PHPExcel.PHPExcel');
    $result = array();
    $objReader = \PHPExcel_IOFactory::createReader($type);
    $objReader->setReadDataOnly(TRUE);
    $objPHPExcel  = $objReader->load($file);           //载入Excel文件 

    $sheet				= $objPHPExcel->getSheet(0);  //读取excel文件中的第一个工作表
            $highestRow			= $sheet->getHighestRow();    //取得一共有多少行
            $highestColumn		= $sheet->getHighestColumn();     //取得最大的列号
            $highestColumnIndex	= \PHPExcel_Cell::columnIndexFromString($highestColumn);//字母列转换为数字列 如:AA变为27

    /** 循环读取每个单元格的数据 */
            for($i=($type=='Excel2007'?1:2);$i<=$highestRow;$i++)      //行数是以第1行开始
    {
        $row = array();
        for($k=0;$k<$highestColumnIndex;$k++)           //列数是以第0列开始
        {
            // $v = $sheet->getCellByColumnAndRow($k,$i)->getValue();//读取单元格
            
            $v = $sheet->getCellByColumnAndRow($k,$i)->getFormattedValue();
            if(is_object($v))
            {
                array_push($row,'');
                continue;
            }
            array_push($row,$v);
        }
        array_push($result,$row);
    }

    return $result;
}

function readOnlyExcelCSV($file,$type='CSV'){
    echo "111111111111111";
     Vendor('PHPExcel.PHPExcel');
    $result = array();
    $objReader = \PHPExcel_IOFactory::createReader($type);
    $objReader->setReadDataOnly(TRUE);
setlocale(LC_ALL, 'zh_CN'); 
$objReader->setInputEncoding('GBK'); 
$objReader->setDelimiter(','); 


    $objPHPExcel  = $objReader->load($file);           //载入Excel文件 

    $sheet				= $objPHPExcel->getSheet(0);  //读取excel文件中的第一个工作表
            $highestRow			= $sheet->getHighestRow();    //取得一共有多少行
            $highestColumn		= $sheet->getHighestColumn();     //取得最大的列号
            $highestColumnIndex	= \PHPExcel_Cell::columnIndexFromString($highestColumn);//字母列转换为数字列 如:AA变为27
// row.getCell(0).setCellType(HSSFCell.CELL_TYPE_STRING);
    /** 循环读取每个单元格的数据 */
    for($i=1;$i<=$highestRow;$i++)      //行数是以第1行开始
    {
        $row = array();
        for($k=0;$k<$highestColumnIndex;$k++)           //列数是以第0列开始
        {
            
            // row.getCell(0).setCellType(HSSFCell.CELL_TYPE_STRING);
            // $v=$sheet->setCellValueExplicit($k$i, '330602198804224688', PHPExcel_Cell_DataType::TYPE_STRING);
            $v = $sheet->getCellByColumnAndRow($k,$i)->getValue();
            echo "\n".$v;
        //   if($v>10000000000){
        //       $v= number_format( $v,0,'','');
        //   }
            // $v=iconv('GB2312', 'UTF-8',$v);
            if(is_object($v))
            {
                array_push($row,'');
                continue;
            }
            array_push($row,$v);
        }
        array_push($result,$row);
    }

    return $result;

}



/**
 * 读取CSV文件
 * @param string $csv_file csv文件路径
 * @param int $lines       读取行数
 * @param int $offset      起始行数
 * @return array|bool
 */
function ReadCSV($uploadfile='') {
        // echo "readcsv2.start...";
        // echo $uploadfile;
$file = fopen($uploadfile, "r");
    while (!feof($file)) {
        $data[] = fgetcsv($file);
    }
     $data = eval('return ' . iconv('gbk', 'utf-8', var_export($data, true)) . ';');
    foreach ($data as $key => $value) {
        if (!$value) {
            unset($data[$key]);
         }
        //  pr($data);
     }
    fclose($file);
    return $data;
}
function ReadCSV1($uploadfile='') {

$fp = fopen($uploadfile, 'r');
while ( ($row = fgetcsv($fp)) !== FALSE ) {
    //从文件指针中读入一行并解析CSV
    $arr[] = $row;
}
fclose($fp);

}



/**
 * 发送邮件
 * @param  string $address 需要发送的邮箱地址 发送给多个地址需要写成数组形式
 * @param  string $subject 标题
 * @param  string $content 内容
 * @return boolean       是否成功
 */
function send_email($address,$subject,$content){
    $email_smtp=C('EMAIL_SMTP');
    $email_username=C('EMAIL_USERNAME');
    $email_password=C('EMAIL_PASSWORD');
    $email_from_name=C('EMAIL_FROM_NAME');
    if(empty($email_smtp) || empty($email_username) || empty($email_password) || empty($email_from_name)){
        return array("error"=>1,"message"=>'邮箱配置不完整');
    }
    require_once './ThinkPHP/Library/Org/Nx/class.phpmailer.php';
    require_once './ThinkPHP/Library/Org/Nx/class.smtp.php';
    $phpmailer=new \Phpmailer();
    // 设置PHPMailer使用SMTP服务器发送Email
    $phpmailer->IsSMTP();
    // 设置为html格式
    $phpmailer->IsHTML(true);
    // 设置邮件的字符编码'
    $phpmailer->CharSet='UTF-8';
    // 设置SMTP服务器。
    $phpmailer->Host=$email_smtp;
    // 设置为"需要验证"
    $phpmailer->SMTPAuth=true;
    // 设置用户名
    $phpmailer->Username=$email_username;
    // 设置密码
    $phpmailer->Password=$email_password;
    // 设置邮件头的From字段。
    $phpmailer->From=$email_username;
    // 设置发件人名字
    $phpmailer->FromName=$email_from_name;
    // 添加收件人地址，可以多次使用来添加多个收件人
    
    if(is_array($address)){
        foreach($address as $addressv){
            echo $addressv;
            $phpmailer->AddAddress($addressv);
        }
    }else{
        $phpmailer->AddAddress($address);
    }
    // 设置邮件标题
    $phpmailer->Subject=$subject;
    // 设置邮件正文
    $phpmailer->Body=$content;
    // 发送邮件。
    if(!$phpmailer->Send()) {
        $phpmailererror=$phpmailer->ErrorInfo;
        return array("error"=>1,"message"=>$phpmailererror);
    }else{
        return array("error"=>0);
    }
}

// $grobalintval 全局的执行间隔，以分钟计
// $thisintval  这里的执行间隔，以分钟计
function intvalexec($grobalintval,$thisintval,$targetval='0'){
$grobalintval_min =$grobalintval * 60;
$nowintval= ceil (  ((time() +3600*8) % (3600*24)) / $grobalintval_min  );
// add2log('function.intvalexec.$nowintval'.$nowintval."   --- ".$thisintval);
    if($nowintval % ($thisintval / $grobalintval) == $targetval) {
        return true;
    }else{
        return false;
    }
}


// 定时执行函数，循环返回 true   false
// $grobalintval 全局的执行间隔，以分钟计
// $thisintval  这里的执行间隔，以分钟计
function timeexec($torun_time,$grobalintval){
$grobalintval_min =$grobalintval * 60;
$nowintval= floor  (  ((time() +3600*8) % (3600*24)) / $grobalintval_min  );
// echo $nowintval."   --- ".$thisintval;
    if($nowintval % ($thisintval / $grobalintval) == 0) {
        return true;
    }else{
        return false;
    }
}
// 两个二维数组合并在一起
function twoarraymerge($twoarr1,$twoarr2,$tobe="1"){ 
    $newtwoarr='';
    $n=0;
if($tobe=="1"){
    foreach($twoarr1 as $k=>$v){
        $newtwoarr[$n++]=$v;
    }
    foreach($twoarr2 as $v){
        $newtwoarr[$n++]=$v;
    }    
}
if($tobe=="2"){
    foreach($twoarr2 as $k2=>$v2){
        $keyarr2[]=$k2;
    }
    foreach($twoarr1 as $k=>$v){
        foreach($keyarr2 as $k3=>$v3){
            $newtwoarr[$n++][$k3]=$twoarr1[$k3];
        }
    }
    foreach($twoarr2 as $v){
        $newtwoarr[$n++]=$v;
    }       
}


    return $newtwoarr;
}

// 从二维数组中取部分
// $twoarr 是二维数组
// $keyarr 是key数组
function twoarrayshort($twoarr,$keyarr){ 
  
    $twoarrnew='';
foreach($twoarr as $twoarrkey => $arr){
    foreach($keyarr as $key){
        if(!empty($key)){
            if(isset($arr[$key]) || is_null($arr[$key])){
                $twoarrnew[$twoarrkey][$key]=$arr[$key];
            }             
        }


        
    } 
} 

return $twoarrnew;


} 
// 从二维数组中的NULL全部填起来,null用空格，有字用\'
function twoarraytotext($twoarr){ 
    $twoarrnew='';
foreach($twoarr as $twoarrkey => $arr){
    foreach($arr as $key=>$value){
        if(empty($value)){
            $twoarrnew[$twoarrkey][$key]=' ';
        }else{
            $twoarrnew[$twoarrkey][$key]=' '.$value;
        }
        
    } 
} 

return $twoarrnew;


} 

/*
这里专门写与通知系统有关的函数

*/

// 获取通知中 要读 的人员
function GetToReadOld($notice_id){
    if(!$notice_id){
        echo "请输入通知的ID";
    }else{
       
            // 全部人员
        $cond_toreadtemp['id']=$notice_id;
        $list_toreadtemp=M('notice')
        	            ->field('sendto')->where($cond_toreadtemp)->find();
        $list_toreadtempstr=explode("\n",$list_toreadtemp['sendto']);
        $list_toreadtempstr=arraytostr($list_toreadtempstr,",");
// echo ($list_toreadtempstr);
        	$cond_toread['user']=array('in',$list_toreadtempstr);
        	$list_toread=M('member')
        	->where($cond_toread)->order('stu_class asc')->select();
    }

    // echo '函数内';
    // pr1($list_toread);
    return $list_toread;    
}



function arrtrim($arr){
    foreach($arr as $k=>$v){
        $arrnew[$k]=trim($v);
    }
    return $arrnew;
}


// 返回表格样式的数据输出
function h5table($data){
     $temp2=$temp2.'<table class="table table-striped"> <tbody>';
foreach ($data as $rows) {
     
    foreach ($rows as $key=>$value) {
        $temp2=$temp2.'<tr>';
        if(!($key == '长号' || $key == '手机')){
            $temp2=$temp2
          .'<td>'.$key.'</td>'
          .'<td>'.$value.'</td>';
          
        }else{
            $temp2=$temp2
                .'<td>'.$key.'</td>'
                 .'<td>'."<a  herf=\"tel:".$value."\"> ".$value .'(点击拨号)</a>'.'</td>';
        }
    $temp2=$temp2.'</tr>';
    }

}      
    $temp2=$temp2.'     </tbody>
</table>';
$temp=$temp1.$temp2.$temp3;
return $temp;

}


// 返回数据输出
function h5html($title='',$content=''){
return "
<html>
<head>
  <title>".$title."</title>
  <meta charset='utf-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <link rel='stylesheet' href='http://cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap.min.css'>  
  <script src='http://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js'></script>
  <script src='http://cdn.static.runoob.com/libs/bootstrap/3.3.7/js/bootstrap.min.js'></script>

</head>
<body>
<h4>
".$content."</h4>


</body>
</html>



";
}


// 返回表格样式的数据输出
function text2html($data,$type='text'){
    if($type='text'){
        $str=str_replace("\\n","<br>",$data);
    }elseif($type='html'){
        $str=str_replace("<br>","\\n",$data);
    }
return $str;
}



// 从二维数组找回指定的东西
function twoarrayfindval($twoarr,$key,$val) {   
    $i=0;
    foreach($twoarr as $ktemp=>$vtemp){
        if($vtemp[$key] == $val){
            $twoarrnew[$i]=$vtemp;
            $i=$i+1;
        }
    }
    return $twoarrnew;
}
// 按号段得到对应的手机邮箱
function GetPhoneEmail($phone) {   
$yd='__134、135、136、137、138、139、147、150、151、152、157、158、159、182、183、187、188';
$telcom='__130、131、132、155、156、185、186、145';
$dx='__133、153、180、189';
$f3=substr($phone,0,3);

if(strpos($yd,$f3)){
    $phoneemail=$phone.'@139.com';
}elseif(strpos($telcom,$f3)){
    $phoneemail=$phone.'@wo.cn';
}elseif(strpos($dx,$f3)){
    $phoneemail=$phone.'@189.cn';
}else{
    $phoneemail='Not find email.';
};
    return $phoneemail;
}

// 从数据新增或保存
function DbAddOrSave($db,$key,$arr){   
    $i=0;
    foreach($twoarr as $ktemp=>$vtemp){
        if($vtemp[$key] == $val){
            $twoarrnew[$i]=$vtemp;
            $i=$i+1;
        }
    }
    return $twoarrnew;
}

//新增twoarray数据
function dbadddata($db,$datatwoarr) {
// 先确认导入的字段
foreach($datatwoarr as $key=>$dataarr){
 reset($dataarr);
 $id=current($dataarr);
$newcout=0;
$newfailcout=0;
$dataarr['id']='';
pr($dataarr);
    $new=$db->data($dataarr)->add(); 
pr($new);
    if($new>0){
       $newcout++; 
    }else{
       $newfailcout++; 
    }
}
$result='用户成功新增' . '，<span style="color:red">' . $newcout . "</span>条数据了！"."失败".$updatecout."条";
return $result;
} 


// 把json的数据进行输出
function jsonFormat($data, $indent=null){  
  
    // 对数组中每个元素递归进行urlencode操作，保护中文字符  
    array_walk_recursive($data, 'jsonFormatProtect');  
  
    // json encode  
    $data = json_encode($data);  
  
    // 将urlencode的内容进行urldecode  
    $data = urldecode($data);  
  
    // 缩进处理  
    $ret = '';  
    $pos = 0;  
    $length = strlen($data);  
    $indent = isset($indent)? $indent : '    ';  
    $newline = "\n";  
    $prevchar = '';  
    $outofquotes = true;  
  
    for($i=0; $i<=$length; $i++){  
  
        $char = substr($data, $i, 1);  
  
        if($char=='"' && $prevchar!='\\'){  
            $outofquotes = !$outofquotes;  
        }elseif(($char=='}' || $char==']') && $outofquotes){  
            $ret .= $newline;  
            $pos --;  
            for($j=0; $j<$pos; $j++){  
                $ret .= $indent;  
            }  
        }  
  
        $ret .= $char;  
          
        if(($char==',' || $char=='{' || $char=='[') && $outofquotes){  
            $ret .= $newline;  
            if($char=='{' || $char=='['){  
                $pos ++;  
            }  
  
            for($j=0; $j<$pos; $j++){  
                $ret .= $indent;  
            }  
        }  
  
        $prevchar = $char;  
    }  
  
    return $ret;  
}  
// 把json的数据进行输出
function LilyjsonFormat($data, $indent=null){  
  
    // 对数组中每个元素递归进行urlencode操作，保护中文字符  
    array_walk_recursive($data, 'jsonFormatProtect');  
  
    // json encode  
    $data = json_encode($data);  
  
    // 将urlencode的内容进行urldecode  
    $data = urldecode($data);  
  
    // 缩进处理  
    $ret = '';  
    $pos = 0;  
    $length = strlen($data);  
    $indent = isset($indent)? $indent : '    ';  
    $newline = "\n";  
    $prevchar = '';  
    $outofquotes = true;  
  
    for($i=0; $i<=$length; $i++){  
  
        $char = substr($data, $i, 1);  
  
        if($char=='"' && $prevchar!='\\'){  
            $outofquotes = !$outofquotes;  
        }elseif(($char=='}' || $char==']') && $outofquotes){  
            $ret .= $newline;  
            $pos --;  
            for($j=0; $j<$pos; $j++){  
                $ret .= $indent;  
            }  
        }  
  
        $ret .= $char;  
          
        if(($char==',' || $char=='{' || $char=='[') && $outofquotes){  
            $ret .= $newline;  
            if($char=='{' || $char=='['){  
                $pos ++;  
            }  
  
            for($j=0; $j<$pos; $j++){  
                $ret .= $indent;  
            }  
        }  
  
        $prevchar = $char;  
    }  
  
    return $ret;  
}  
  
/** 将数组元素进行urlencode 
* @param String $val 
*/  
function jsonFormatProtect(&$val){  
    if($val!==true && $val!==false && $val!==null){  
        $val = urlencode($val);  
    }  
}  


// 发送post数据
function http_request($url,$data=array()){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    // 我们在POST数据哦！
    curl_setopt($ch, CURLOPT_POST, 1);
    // 把post的变量加上
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
/**
 * 生成二维码
 * @param  string  $url  url连接
 * @param  integer $size 尺寸 纯数字
 */
function qrcode($url,$size=2){
    Vendor('Phpqrcode.phpqrcode');
    QRcode::png($url,false,QR_ECLEVEL_L,$size,2,false,0xFFFFFF,0x000000);

}

/**
 * 分成两行
 * @param  string  $text 要分开的文本
 */
function addbr($text="短信测试"){
// pr($text);
$len=mb_strlen($text, 'utf-8');
// pr($len);
$textleft=mb_substr($text,0,ceil($len/2), 'utf-8');
// pr($textleft);
$textright=mb_substr($text,ceil($len/2),floor($len/2), 'utf-8');
return $textleft.'<br>'.$textright;
}


/**
 * 生成二维码
 * @param  string  $url  url连接
 * @param  integer $size 尺寸 纯数字
 */
function h5page($title,$content){
$allpage="<html>
<head>
  <title>".$title."</title>
  <meta charset=\"utf-8\">
  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
  <link rel=\"stylesheet\" href=\"http://cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap.min.css\">  
  <script src=\"http://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js\"></script>
  <script src=\"http://cdn.static.runoob.com/libs/bootstrap/3.3.7/js/bootstrap.min.js\"></script>
<style type=\"text/css\">
.redbold {
	color: #FF0000;
	font-weight: bold;
}
</style>
</head>
<body style=\"padding:15px\">
<h1>".$title."</h1>
<hr><h4>".$content."</h4><hr>
</body>
</html>";
return $allpage;
}


/*
 * @purpose: 使用curl并行处理url
 * @return: array 每个url获取的数据
 * @param: $urls array url列表
 * @param: $callback string 需要进行内容处理的回调函数。示例：func(array)
 */
function curl($urls = array(), $callback = '')
{
    $response = array();
    if (empty($urls)) {
        return $response;
    }
    $chs = curl_multi_init();
    $map = array();
    foreach($urls as $url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOSIGNAL, true);
        curl_multi_add_handle($chs, $ch);
        $map[strval($ch)] = $url;
    }
    do{
        if (($status = curl_multi_exec($chs, $active)) != CURLM_CALL_MULTI_PERFORM) {
            if ($status != CURLM_OK) { break; } //如果没有准备就绪，就再次调用curl_multi_exec
            while ($done = curl_multi_info_read($chs)) {
                $info = curl_getinfo($done["handle"]);
                $error = curl_error($done["handle"]);
                $result = curl_multi_getcontent($done["handle"]);
                $url = $map[strval($done["handle"])];
                $rtn = compact('info', 'error', 'result', 'url');
                if (trim($callback)) {
                    $callback($rtn);
                }
                $response[$url] = $rtn;
                curl_multi_remove_handle($chs, $done['handle']);
                curl_close($done['handle']);
                //如果仍然有未处理完毕的句柄，那么就select
                if ($active > 0) {
                    curl_multi_select($chs, 0.5); //此处会导致阻塞大概0.5秒。
                }
            }
        }
    }
    while($active > 0); //还有句柄处理还在进行中
    curl_multi_close($chs);
    return $response;
}

// 修改了网上的模板，原来是url会变，我现在是post会变
function  curlpost($url,$postarr, $callback = '')
{
    $response = array();

    $chs = curl_multi_init();
    $map = array();
    foreach($postarr as $post){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOSIGNAL, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查  
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在  

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_multi_add_handle($chs, $ch);
        $map[strval($ch)] = $url;
    }
    do{
        if (($status = curl_multi_exec($chs, $active)) != CURLM_CALL_MULTI_PERFORM) {
            if ($status != CURLM_OK) { break; } //如果没有准备就绪，就再次调用curl_multi_exec
            while ($done = curl_multi_info_read($chs)) {
                $info = curl_getinfo($done["handle"]);
                $error = curl_error($done["handle"]);
                $result = curl_multi_getcontent($done["handle"]);
                $url = $map[strval($done["handle"])];
                $rtn = compact('info', 'error', 'result', 'url');
                if (trim($callback)) {
                    $callback($rtn);
                }
                $response[$url] = $rtn;
                curl_multi_remove_handle($chs, $done['handle']);
                curl_close($done['handle']);
                //如果仍然有未处理完毕的句柄，那么就select
                if ($active > 0) {
                    curl_multi_select($chs, 0.5); //此处会导致阻塞大概0.5秒。
                }
            }
        }
    }
    while($active > 0); //还有句柄处理还在进行中
    curl_multi_close($chs);
    return $response;
}
 
//使用方法
function deal($data){
    if ($data["error"] == '') {
        echo $data["url"]." -正确- ".$data["info"]["http_code"]."\n";
        pr($data);
    } else {
        echo $data["url"]." -错误- ".$data["error"]."\n";
        pr($data);
    }
}



function unicode_to_utf8($str) {
   $str= preg_replace("#\\\u([0-9a-f]+)#ie","iconv('UCS-2','UTF-8', pack('H4', '\\1'))",$str);
    return $str;
}



function IpAuth($ip, $config){
    $ipArr = explode(".", $ip);
    for ( $i=0; $i<count($config); $i++ ){
        $ips = explode(".", $config[$i]['start']);
        $ipe = explode(".", $config[$i]['end']);
        for( $j=0; $j<4; $j++ ){
            if( $ips[$j]==$ipArr[$j] || $ipArr[$j]==$ipe[$j] ){
                if($j == 3){
                    return true;
                }else{
                    continue;
                }
            }else if( $ips[$j]<$ipArr[$j] && $ipArr[$j]<$ipe[$j] ){
                return true;
            }else{
                continue 2;
            }
        }
    }
    return false;
}

// 跳转下载



// 跳转下载
function h5jump($furl=''){
return "
<html>
<head>
  <title>".$title."</title>
  <meta charset='utf-8'>
 <meta http-equiv=\"refresh\" content=\"0;URL=".$furl."\">

</head>
<body>
</body>
</html>";
}
