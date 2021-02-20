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
class QueryfunController extends BaseController {

    public function index() {
        $twoarr[0]['d1'] = "d1d1";
        $twoarr[1]['d2'] = "2222";
        $twoarr[1]['d12'] = "d1d1";
        // // pr1($twoarr);
        // // pr1(arraygetkeys($twoarr,"d1,d2,d4"));

    }


    // 把excel 传过来的中文字符串转成标准格式
    public function req_url2getstr($req_url) {
        if(!empty($req_url['Req_URL'])){
            $temp1=$req_url['Req_URL'];
            list($temp2,$getstr)=explode("?",$temp1);
            $getstr['conall']=$getstr;
            return $getstr;
        }else{
            die('req_url 找不到对应的值。');
        }
        
    }

    // 把excel 传过来的中文字符串转成标准格式
    public function constr2conarr($data,$type = 'eq') {
// pr1($data,'data34234');        
        foreach ($data as $key => $value) {
            if (!empty($value)) {
                // $con2[$key]=characettouft8(unicode_to_utf8($value));
                $con2[$key] = $value;
                //修正
            }
        }
// // pr1($con2,"con285634");
        $conall = explode(";",$con2['conall']);
// // pr1($conall,"CONALLfd34232");
        foreach ($conall as $value) {
            $ex = '';
            // // pr1($value,'value11');
            // // pr1(strstr($value,"不等"));
            // echo '大于等1';// pr1(strstr($value,"大于等1"));
            if (!empty($value)) {
                if (strstr($value,"等于")) {
                    $ex = explode('等于',$value);
                    if (count($ex) == 2) {
                        $con2[$ex['0']] = $ex['1'];
                    } else {
                        $result = "不是一个等号";
                    }
                } elseif (strstr($value,"包含")) {
                    $ex = explode('包含',$value);
                    //   // pr1($ex);
                    if (count($ex) == 2) {
                        $likecon[$ex['0']] = array('LIKE',"%".$ex['1']."%");;
                    } else {
                        $result = "不是一个包含";
                    }
                } elseif (strstr($value,"IN")) {
                    $ex = explode('IN',$value);

                    if (count($ex) == 2) {
                        $likecon[$ex['0']] = array('in',$ex['1']);
                    } else {
                        $result = "不是一个包含";
                    }
                } elseif (strstr($value,"非空")) {
                    $ex = explode('非空',$value);
                    $likecon[$ex['0']] = array('exp','is not null');
                } elseif (strstr($value,"是空")) {
                    $ex = explode('是空',$value);
                    $likecon[$ex['0']] = array('exp','is  null');
                } elseif (strstr($value,"大于等")) {
                    $ex = explode('大于等',$value);

                    if (count($ex) == 2) {
                        if (empty($likecon[$ex['0']])) {
                            $likecon[$ex['0']] = array(array('EGT',$ex['1']));
                        } else {
                            if (getmaxdim($likecon) == 3) {
                                $likecon[$ex['0']][] = array('EGT',$ex['1']);
                            }
                        }

                    } else {
                        $result = "不是一个>=";
                    }
                } elseif (strstr($value,"小于等")) {
                    $ex = explode('小于等',$value);
                    //   // pr1($ex);
                    if (count($ex) == 2) {
                        if (empty($likecon[$ex['0']])) {
                            $likecon[$ex['0']] = array(array('ELT',$ex['1']));
                        } else {
                            if (getmaxdim($likecon) == 3) {
                                $likecon[$ex['0']][] = array('ELT',$ex['1']);
                            }
                        }

                    } else {
                        $result = "不是一个<=";
                    }
                }
            }
        }
        $con2 = $this->replacechinesekey($con2);
        $likecon = $this->replacechinesekey($likecon);
        foreach ($con2 as $key => $value) {
            if (empty($value)) {
                unset($con2[$key]);
            }
        }

        if (empty($likecon)) {
            $likecon['temp'] = 'temp';
        }

        if ($type == 'eq') {
            return $this->replace_error_con($con2);
        } elseif ($type == 'like') {
            return $this->replace_error_con($likecon);
        } else {
            return "con is error";
        }

        
    }

    // 删除不正常的条件
 public function replace_error_con($arr) {
foreach($arr as $k=>$v){
    if(strlen($k)>1){
        $arrnew[$k]=$v;
    }
    
}
return $arrnew;
}
    // 替换中文为英文
    public function replacechinesekey($arr) {
        $aa['维护人'] = 'owner';
        $aa['负责人'] = 'owner';
        $aa['数据表名'] = 'sheetname';
        $aa['学号/工号'] = 'pid';
        $aa['姓名'] = 'name';
        $aa['数据日期'] = 'datatime';
        $aa['保留1'] = 'data1';
        $aa['保留2'] = 'data2';
        $aa['只显字段'] = 'field';
        $aa['显示字段'] = 'field';
        $aa['不显字段'] = 'notfield';
        $aa['上传字段'] = 'uploadfields';
        $aa['用户名'] = 'user';
        $aa['用户密码'] = 'password';
        $aa['查看密码'] = 'rpw';
        $aa['上传密码'] = 'wrpw';

        $aa['姓名字段'] = 'namekey';
        $aa['学号/工号字段'] = 'pidkey';
        $aa['学号字段'] = 'pidkey';
        $aa['ID字段'] = 'pidkey';
        $aa['分类字段'] = 'classkey';
        $aa['排序字段'] = 'orderkey';
        $aa['是否升序'] = 'isasc';        
        $aa['缩略显示'] = 'weborder';
        $aa['覆盖上传'] = 'replaceadd';
        $aa['匿名填表'] = 'anonymousfill';
        $aa['提示字段'] = 'autotip';
        $aa['不提示字段'] = 'notautotip';
        $aa['显示条数'] = 'limitnum';        
        $aa['表格说明'] = 'titleexplain';        
        $aa['初始数据行数'] = 'tipnumlimt';               
        $aa['读权限字段'] = 'rpw_right';         
        $aa['写权限字段'] = 'wrpw_right';         
        
        
        foreach ($arr as $keycn => $v) {
            foreach ($aa as $kval => $vkey) {
                if ($keycn == $kval) {
                    unset($arr[$keycn]);
                    $arr[$vkey] = $v;
                }
            }
        }
        return $arr;
    }



// 查出数据表名为sheetname,的第一行，返回一维数组
public function findfirstline($sheetname,$forall=false){
    $db=M(C('EXCELSECRETSHEET'));
    // 查出第一行
        $sheetcon['sheetname']=$sheetname;
        // $firstlinearrtemp=$db->where($sheetcon)->order('id')->find();
        // // // pr1($firstlinearrtemp);
        // $firstcon['id']=array(array("eq",$firstlinearrtemp['id']-1),array("eq",$firstlinearrtemp['id']),"OR");
        // $firstcon['ord']=0;
        if($forall){
            $firstline=$db->where($sheetcon)->order('id asc')->find();  
        }else{
            $firstline=$db->where($sheetcon)->Field(C('FIELDSTR'))->order('id asc')->find();  
        }
        
// // pr1($sheetcon,'$sheetcon234');        
// // pr1($firstline,'$firstliner5435');    
    return $firstline;
}




function forcequery($db,$con,$rev){
    // // pr1($con);
    $rev=$con['name'];
    $rpw=$con['rpw'];
    $forcecon['rpw']=$rpw;
    $forcecon['d1|d2|d3|d4|d5|d6|d7|d8|d9|d10|d11|d12|d13|d14|d15|d16|d17|d18|d19|d20|d21|d22|d23|d24|d25|d26|d27|d28|d29|d30|d31|d32|d33|d34|d35|d36|d37|d38|d39|d40|d41|d42|d43|d44|d45|d46|d47|d48|d49|d50']=array('like',"%".$rev."%");
    // // pr1($forcecon);
    if($rev && $rpw){
        $forceresulttwoarr=$db->where($forcecon)->select();
        // // pr1($forceresulttwoarr);
    }
    return $forceresulttwoarr;
}


// 查询结果
public function conquery($db,$con,$name="",$thisuser=""){
// $firstlinearr=$db->where($con)->find();
// $ordconarr=json_decode($firstlinearr['custom1'],'true');
// $weborderarr=explode(',',$ordconarr['weborder']);

// // pr1($weborderarr);
// // pr1($con);
$r=$db->where($con)->limit(C('QUERYLIMIT'))->order('id asc')->select();
$rnum=$db->where($con)->count();
// // pr1($rnum);
if(empty($r) && $rnum > C('FORCEQUERYNUM')){
    $r=$this->forcequery($db,$con,$name);
    $rnum=count($r);
}
// // pr1($r);
if(!empty($r)){
    $temp2['数据表名称']="信息摘要（点击查看详情）";


foreach ($r as $k1=> $value) {
    // // pr1($value);
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
// // pr1($_SESSION,'756');    
    $temp2[$k.". ".$value['sheetname']]="<a href=\"".U(getcomstr('Vi',$thisuser)."/echoiddata?id=$id")."\">".$temp5."</a>";
    // // pr1($temp2);
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
         // // pr1($value2);
         $id=$value2['id'];
         $newarr1 =R("Queryfun/echoiddatacontent",array($id));
        //   // pr1($newarr1);
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





public function update($id=0){
	   // // pr1(I('post.'),'post');
	   
		$id=I('post.id','','strip_tags');
        $data=arrtrim(I('post.'));
        // // pr1(session());
		$sheetname=session('sheetname');	
// 		$rpw=session('rpw');	
// 		$wrpw=$this->USER['querywrpw']?$this->USER['querywrpw']:C('MLRPW');


	    
// // pr1($sheetname,'$sheetname');        
// // pr1($wrpw,'$wrpw');   	    
        $titlearrall=R('Queryfun/gettitlearr',array($sheetname));
        $paraarr=json_decode($titlearrall['custom1'],'true');


        // 用户填表权限检测
        $this->Auth2FillForm($sheetname,$titlearrall,$paraar);

        $user=empty($data[$paraarr['pidkey']])?$this->USER['user']:$data[$paraarr['pidkey']];

	    // 保存文件并保存链接
	   // // pr1($_FILES,'$_FILES');
	    if(R("Queryfun/fileisnotempty",array($_FILES))){
    	    $uploadfilearr=savefile();
    	    foreach ($uploadfilearr as $k4=>$v4) {
    	        $data[$k4]=$v4;
    	    }
	        
	    }


        $data['name']=$data[$paraarr['namekey']];
        // $data['pid']=$data[$paraarr['pidkey']];
        $data['pid']=$user;
        $data['rpw']=$titlearrall['rpw'];
        $data['wrpw']=$titlearrall['wrpw'];
        $data['sheetname']=$sheetname;
        $data['t']=time();
	    
	    $data['custom1'] = json_encode($paraarr);	
// // pr1($data,'data34322');	    
// // pr1($paraarr['rpw_right'],'324');    
        $db=M(C('EXCELSECRETSHEET'));        
		if($id){
			$db->data($data)->where('id='.$id)->save();
			$flag=$id;
			$this->success('恭喜，操作成功！',U($Think.CONTROLLER_NAME."/updatetoadd?id=$flag"));
// 			$this->success('恭喜，操作成功！',U($Think.CONTROLLER_NAME."/magrecords?sheetname=$sheetname"));
		}else{
    		$data['r']=$data[$paraarr['rpw_right']];
            $data['w']=$data[$paraarr['wrpw_right']];
			$flag=$db->data($data)->add();
// 			$this->success('恭喜，操作成功！',U($Think.CONTROLLER_NAME."/magrecords?sheetname=$sheetname"));
			$this->success('恭喜，操作成功！',U($Think.CONTROLLER_NAME."/updatetoadd?id=$flag"));
		}
		
// 		// pr1($flag);
// 		$this->success('恭喜，操作成功！',U($Think.CONTROLLER_NAME."/addedit"),7);
		
		
// 		{:U('RwxyCom/echoiddata')}?id={$id}
				
}



public function updatetoadd($id=0){
if(empty($id)){
    $id=I('get.id');}
$sheetname=session('sheetname');
$bu=R("Queryfun/Auth2edit",array($sheetname,'','',$id));       
$newarr=R('Queryfun/echoiddatacontent',array($id,$this->USER));

// // pr1($id);
// // pr1($newarr);
// echo "<h3><a href=\"".$_SERVER["HTTP_REFERER"]."\">返回</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"."<a href=\"".session('indexpage')."\">查询首页</a></h3>";


$thisline=R($Think.CONTROLLER_NAME.'/mynavline',array($sheetname,$id));

$echohtml=$thisline;
$echohtml=$thisline."<br>";
// $echohtml=R('Task/echoarrresult',array($newarr,"信息详情页"));
$echohtml.=echoarrresult($newarr,"信息详情页");
echo $echohtml;

return $echohtml;
    
    
}


// 判断是否有授权,看能否填表 
public function Auth2FillForm($sheetname,$titlearrall="",$paraarr="",$id="") {
// // pr1($titlearrall,"fds234");
// // pr1($titlearrall,"34534");
    if(empty($titlearrall) ||empty($paraarr) ){
// // pr1($titlearrall,"3333333");
// // pr1($titlearrall,"342222234534");        
        $titlearrall=R('Queryfun/gettitlearr',array($sheetname,$id));
        $paraarr=json_decode($titlearrall['custom1'],'true');       
    }
    // // pr1($paraarr);
    // // pr1($_SERVER);
    if($paraarr['anonymousfill']=="否"){
        if(session('login')=='yes'){
            return $paraarr;
        }else{
            // session('jumpurl',$_SERVER['REQUEST_URI']);
            // // pr1('REQUEST_URI '.$_SERVER['REQUEST_URI']);
            // // pr1('jumpurl '.session('jumpurl'));
            $this->error("错误，此表单需要登陆后才能填写！~",U("login/index").'?Req_URL='.$_SERVER['REQUEST_URI'],1);
            return $paraarr;
        }
    }else{
        return $paraarr;
    }
}

// 判断是否有授权,看能否修改表格,一般10分钟内可以修改
public function Auth2edit($sheetname,$titlearrall="",$paraarr="",$id="") {
// if(session('login')=='yes'){
//     return true;
// }

// 这里有漏洞，别人编辑了，10分种之内可编辑。
    $thisuser=$this->getthisuser();   
    $db=M(C('EXCELSECRETSHEET'));
    if(empty($titlearrall) ||empty($paraarr) ){
        $titlearrall=R('Queryfun/gettitlearr',array($sheetname,$id));
        $paraarr=json_decode($titlearrall['custom1'],'true');       
    }   

if(!empty(session('wrpw')) && !empty($id)){
    $wrpw=session('wrpw');

    $querycon22['wrpw']=array("in",returncomma($wrpw));
    $querycon22['id']=$id;
    $l=$db->where($querycon22)->find();
// // pr1($querycon22,'l,$querycon22');    
// // pr1($l,'l,342');
    if(!empty($l)){
        return true;
    }
}
if(!empty($thisuser['user'])){
    if(empty($id)){$this->error("错误q100，请输入ID~",U("index/index"));}
    $con['id']=$id;
    // $con['sheetname']=$sheetname;
    $r=$db->where($con)->find();
    $w=$r['w'];

    if($r==$thisuser['user']){
        return true;
    }else{
        $this->error("错误q103，未授权修改！~",U("index/index"));
    }

}

    if(empty($id)){$this->error("错误q100，请输入ID~",U("index/index"));}
    $con['id']=$id;
    // $con['sheetname']=$sheetname;
    $t=$db->where($con)->find();
// pr($id,'id');    
// pr($t,'id_data');
    $edtime=$t['t'];
    // pr($edtime,'$edtime');
    // pr($edtime+C('EDITTIME'),'$edtime+C');
    if($edtime){
        if(time() > $edtime+C('EDITTIME')){
            $this->error("错误q101，此单元格修改时间超时，请登陆后修改！~",U("index/index"));
            return false;
        }else{
            return true;
        }
    }else{
        $this->error("错误q102，找不到对应的记录！~",U("index/index"));
    }


}


public function getthisuser(){
    if(!empty($this->USER)){
        $thisuser=$this->USER;
    }else{
        $thisuseruser=session("thisuseruser");
        if(empty($thisuseruser)){
            $thisuser=null;
        }else{
            $db=M('member');
            $con['user']=$thisuseruser;
            $thisuser=$db->where($con)->find();
        }
    }
    return $thisuser;
}

//设定session的值，并取出，有值取值并设定，没值用原来的
//两个都没有，返回默认值
public function set_session($key,$val=""){
    $value=I('get.'.$key);
        if(!empty($value)){
            session($key,$value);
           return $value;
        }else{
            if(session($key)){
                return $value;
            }else{
                // switch ($key)
                // {
                // case "querypw":
                //   return C('MLRPW');
                //   break;  
                // case "querywrpw":
                //   return C('MLRPW');
                //   break;  
                // case "sheetname":
                //   return C('MLSHEETNAME');
                //   break;                    
                // default:
                 
                // }
                return $val;
            }
        }
}


//
public function ud___________________(){
}

// 查询所有数据集，$magage是标记是否管理
public function querycon($querycon,$magage,$thisuser=""){

$wrpw=empty(session('wrpw'))?C('MLWRPW'):session('wrpw');    
$rpw=empty(session('rpw'))?C('MLRPW'):session('rpw'); 
// // pr1($wrpw);
    if($magage=='true'){
        $querycon['wrpw']=array("in",returncomma($wrpw));
    }elseif($magage=='false'){
        $querycon['rpw']=array("in",returncomma($rpw));
        // // pr1($thisuser);
        if(!empty($thisuser)){
            $querycon['pid']=$thisuser['user'];      
        }
    }elseif($magage=='r'){
        if(!empty($thisuser)){
            $querycon['r']=$thisuser['user'];      
        }
                
    }else{
        $this->error('必须有查看密码或上传密码！~');
    }    
    
    return $querycon;
}


//通过多种途径查询出对应的标题数组
public function gettitlearr($sheetname,$id='',$fieldstr='',$delempty='true'){
$db=M(C('EXCELSECRETSHEET'));
// // pr1($id,"343");    
if(!empty($id)){
    $con['id']=$id;
    $idarr=$db->where($con)->order('id asc')->find();
// // pr1($idarr,"343");    
    $titlearr=$this->gettitlearr($idarr['sheetname'],'',$fieldstr);
}elseif(empty($sheetname) ){
    $this->error('未找到您的个人记录！~');
}else{
    $querycon['sheetname']=$sheetname;
    if(empty($fieldstr)){
        $titlearr=$db->where($querycon)->order('id asc')->find();
    }else{
        $titlearr=$db->where($querycon)->Field($fieldstr)->order('id asc')->find();
    }
}   
    if(empty($titlearr)){
        $this->error('错误1231，请联系表单所有者！~',U($Think.CONTROLLER_NAME.'/sheetindex'));
    }else{
        if($delempty='true'){
            $titlearr=delemptyfield($titlearr);
        }
        return $titlearr;
    }    

}




function echoiddatacontent($id='',$thisuser=""){
// // pr1($thisuser);
if(empty($id)){
    return '请输入id';
}else{
$con2['id']=$id;
// // pr1($con2);


$db=M(C('EXCELSECRETSHEET'));

$fieldstr=C('FIELDSTR');
$arr=$db->where($con2)->find();    
// $arr=$db->where($con2)->Field($fieldstr)->finfindfirstlined();  
// // pr1($arr['sheetname']);
// // pr1($arr,'234234');

    // 查出第一行
    $firstline=R('Queryfun/findfirstline',array($arr['sheetname'],''));

// // pr1($firstline,'43534');
$arr=delemptyfield($arr);
// // pr1($arr,'4323');
foreach ($arr as $key=> $value) {

switch (C('AILINK')) {
    // case 'auto':   //自动就改成默认吧，免得出错
    // break;
    
    case 'link':
if(!is_null($firstline[$key])){

        if(!empty($value)){
            $newarr[$firstline[$key]]="<a href=\"/index.php/Qwadmin/".getcomstr('Vi',$thisuser)."/uniquerydata.html?$key=$value\">".'<span class="glyphicon glyphicon-search"></span>'.$value."</a>";
        }        
    
}        
        break;
    
    case 'nolink':
if(!is_null($firstline[$key])){
        if(!empty($value) ){
            $newarr[$firstline[$key]]=$value;
        }
}        
        break;    
    default:
        
if(!is_null($firstline[$key])){
    if($this->isimg($firstline,$key,$value)){//专门图片的处理

        $newarr[$firstline[$key]]="<a href=\"".$value."\" class=\"thumbnail\">
                                        <img src=\"/temp".$value."\">
                                    </a>";
    }elseif($this->isbigimg($value)){      //大图的处理

        $newarr[$firstline[$key]]="<a href=\"".$value."\" class=\"thumbnail\">
                                        <img src=\"".$value."\">
                                    </a>";
    }elseif($this->isdateortime($firstline[$key])){
        $newarr[$firstline[$key]]="<a href=\"/index.php/Qwadmin/".getcomstr('Vi',$thisuser)."/uniquerydata.html?$key=$value\">".'<span class="glyphicon glyphicon-search"></span>'.exceldatechange($value)."</a>";
 
    }elseif($this->isphone($value) ){
        $newarr[$firstline[$key]]="<a href=\"tel:$value\">".'<span class="glyphicon glyphicon-earphone"></span>'.$value."</a>";  
    }elseif($this->isurl($value)){
        $newarr[$firstline[$key]]=autolink($value);
    }elseif(mb_strlen($value)<C('AILINKLEN')){
        // // pr1('333333'.$value);
        if(!empty($value)){
            $newarr[$firstline[$key]]="<a href=\"/index.php/Qwadmin/".getcomstr('Vi',$thisuser)."/uniquerydata.html?$key=$value\">".'<span class="glyphicon glyphicon-search"></span>'.$value."</a>";
        }      
    }else{
        if(!empty($value) ){
            $newarr[$firstline[$key]]=$value;
        }
    }    
}
        break;
        break;
}

    
    
// // pr1($newarr);
    
}

}
return $newarr;
}





// 这是数值
function isphone($value){
    if(($value>600 && $value < 900 ) ||($value>500000 && $value < 699999 ) || ($value>13000000000 && $value < 19000000000 ) ){
        // // pr1($value);
        return true;
    }else{
        // // pr1("非文本3");
        return false;         
    }
}
// 里面包括网址
function isurl($val){
    $keystr=mb_substr($val,0,1,"UTF-8");
    if(strstr($val,'http')){
        return true;
    }if($keystr=='/'){
         return true;
    }else{
        return false;         
    }
}
// 是否是图片
function isimg($firstline,$key){
$keystr=mb_substr($firstline[$key],0,2,"UTF-8");
// // pr1($keystr);
if($keystr=="照片"){
    return true;
}else{
    return false;     
}
}


// 是否是大图片
public function isbigimg($value){
$ext=substr($value, strrpos($value, '.')+1);
if(in_array($ext,explode(",",C('PHOTOEXTS')))){
    return true;
}
return false;

}

// 是否是时间或日期
function isdateortime($v){
$keystr=mb_substr($v,-2,2,"UTF-8");    
$keystr=$v;
// // pr1($keystr,'keystr');
    $f1=strstr($keystr,'时间 ');
    $f2=strstr($keystr,'日期 ');
    // // pr1($keystr);
    if($f1 || $f2){
        return true;
    }else{
        return false;     
    }
}
// 是否是GPS
function isGPS($v){
$keystr1=mb_substr($v,-3,3,"UTF-8");  
$keystr2=mb_substr($v,-3,3,"UTF-8");   
// // pr1($keystr,'keystr');
    $f1=strstr($keystr,'位置 ');
    $f2=strstr($keystr,'GPS');
    // // pr1($keystr);
    if($f1 || $f2){
        return true;
    }else{
        return false;     
    }
}




public function mynavline($sheetname,$id,$thisuser=""){
        // <div class=\"col-xs-offset-2 \">
// 	<div class=\"col-xs-3\">
// 		<h3><a href=\"".U($Think.CONTROLLER_NAME."/mysheet?sheetname=$sheetname")."\">个人</a></h3>   
// 	</div> 	
    $thisline="<div class=\"col-xs-12\">
	<div class=\"col-xs-4\">
		<h3><a href=\"".U(getcomstr('Ud')."/index?sheetname=$sheetname")."\">首页</a></h3>   
	</div> 	

	<div class=\"col-xs-4\">
		<h3><a href=\"".U(getcomstr('Ad')."/addedit?sheetname=$sheetname")."\">新增</a></h3>   
	</div>
	<div class=\"col-xs-4\">
		<h3><a href=\"".U(getcomstr('Ad')."/addedit?id=$id")."\">更改</a></h3>   
	</div> 		

</div>";
// 	<div class=\"col-xs-3\">
// 		<h3><a href=\"".U(getcomstr('Rwxy',$this->USER).'/echoiddata?id='.$id)."\">查看</a></h3>   
// 	</div>
return $thisline;
}



// 智能提示
public function SmartInput($sheetname="古村落",$key='d1',$value=''){

        $db=M(C('EXCELSECRETSHEET'));
        $querycon['sheetname']=$sheetname;
        if(!empty($key) && !empty($value)){
            $querycon[$key]=$value;
        }
        $smartinputtwoarr=$db->where($querycon)->Field($key)->order('id desc')->limit(C('TIPNUM'))->distinct()->select(); 
        $keyarr=twoarray2onearr($smartinputtwoarr,$key);
        // // pr1($smartinputarr);
        return $keyarr;

}


// 智能提示
public function LastInputs($sheetname="古村落"){
    
        $db=M(C('EXCELSECRETSHEET'));
        $querycon['sheetname']=$sheetname;
        // if(!empty($key) && !empty($value)){
        //     $querycon[$key]=$value;
        // }
        // 把首行排除掉
        $firsrtlinearr=$db->where($querycon)->order('id asc')->limit(100)->distinct()->find();  
// // pr1('$firsrtlinearr',$firsrtlinearr);

        $custom1arr=json_decode($firsrtlinearr['custom1'],true);
// var_dump($custom1arr);
        $autotip=$custom1arr['autotip'];
        $notautotip=$custom1arr['notautotip'];
        $tipnumlimt=$custom1arr['tipnumlimt'];

// // pr1('$tipnumlimt',$tipnumlimt);        
// // pr1('$custom1arr',$custom1arr);
        if(!empty($autotip)){
            $fieldstr=$autotip;
        }elseif(!empty($notautotip)){
            $fieldstr=StrMinusStr2(compute_fieldstr(C('MLNOTFIELD')),$notautotip);
        }else{
            $fieldstr=compute_fieldstr(C('MLNOTFIELD'));
        }
        
        // 不管怎么说，再删去特殊字段，如文件，照片
        $fieldstr=StrMinusStr2($fieldstr,$this->AutoTipdelField($firsrtlinearr));
        // // pr1($fieldstr);
        
        // $newfieldstr=$this->AutoTipField($firsrtlinearr);
        // // pr1($newfieldstr);
        

        
        // // pr1($firsrtlinearr);
        if(!empty($firsrtlinearr)){
            $querycon['id']=array('neq',$firsrtlinearr['id']);
        }
        // // pr1($querycon);
        $limitnum=empty($tipnumlimt)?C('TIPNUM'):$tipnumlimt;
// // pr1('$limitnum',$limitnum);


        $datalistonearr=$db->where($querycon)->Field($fieldstr)->order('t desc,id desc')->limit($limitnum)->distinct()->select();
        // // pr1($datalistonearr);
        // $datalistonearr=delemptyfield($datalistonearr);
        // // pr1($datalistonearr);
        $datalistonearr=TwoArrayAllColUnique($datalistonearr);
        // // pr1($datalistonearr);
        // $datalistonearr=twoarray2onearr($smartinputtwoarr,$key);
        

        // // pr1($datalistonearr);
        return $datalistonearr;

}


// 智能提示删除部分区域
public function AutoTipdelField($firsrtlinearr){
    foreach($firsrtlinearr as $key=>$value){
        if(strstr($value,"文件") || strstr($value,"照片") ){
            $fieldstrkey[]=$key;
        }
    }
    return implode(",",$fieldstrkey);
}

// 看看$_FILES
public function fileisnotempty($file){
    foreach($file as $key=>$value){
        if(!empty($value['name']) ){
            return true;
        }
    }
    return false;
}


}