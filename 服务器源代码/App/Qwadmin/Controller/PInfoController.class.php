<?php
namespace Qwadmin\Controller;
use Common\Controller\BaseController;
use Think\Controller;
class PInfoController extends BaseController{
    
public function index(){

echo "index.";
    } 


public function exam(){
$userid='2013014';
$sheetname='王进利记住资料';

$key['标题']='d1';
$key['内容']='d2';
$key['关键词']='d3';
$key['时间']='d4';
$key['owner']='d5';
$key['familiarity']='d6';
$key['studybook']='d7';
$key['studychapter']='d8';

if(empty(session("studied"))){
    session("studied",array(1));
}
// $t=session("studied");
// pr($t);
// pr(array_push($t,22));
// pr($t);
$db=M(C('EXCELSECRETSHEET'));
$userarr=$this->USER;
// pr($userarr);
$con[$key['owner']]=$userid;
$con['sheetname']=$sheetname;
$con[$key['familiarity']]=array('gt','0');
$con['id']=array('not in',session("studied"));
// pr($con);
// pr(session("studied"));
// session("studied",);

$toexam=$db->limit(1)->where($con)->order($key['familiarity'].' asc')->find();
// $toexam=delemptyfieldtwoarr($toexam);

// pr($key);
// pr($toexam);
// pr($toexam,"exam");
// $this->examoutput($toexam,$key['标题'],"<br><br><br>");
// $this->examoutput($toexam,$key['内容'],"<br>",'true');
$this->assign("examarr",$toexam);
$this->assign("key",$key);
// $this->assign("title",$toexam[$key['标题']]);
// $this->assign("content",$toexam[$key['内容']]);
// pr($toexam[$key['标题']]);
$this->display();
}	


public function examupdate(){
    // pr(I('post.'));
$id=I('post.id');
$familiarity=I('post.familiarity');



$userid='2013014';
$sheetname='王进利记住资料';

$key['标题']='d1';
$key['内容']='d2';
$key['关键词']='d3';
$key['时间']='d4';
$key['owner']='d5';
$key['familiarity']='d6';
$key['studybook']='d7';
$key['studychapter']='d8';


$db=M(C('EXCELSECRETSHEET'));

$con['id']=$id;
$arr=$db->where($con)->find();
$value=$arr[$key['familiarity']];
// 更新值
$value=$this->calcfami($value,$familiarity);
// pr($value,'$value');
// pr(session("studied"),"更新ej");
$t=session("studied");
array_push($t,$id);
session("studied",$t);
// pr(session("studied"),"更新后");

$arr[$key['familiarity']]=$value;
$temp=$db->save($arr);   
        $url=U('exam');
        	header("Location: $url");
// $this->success("你已经做了一题了",U('exam'),1);


}

public function calcfami($value,$familiarity){
    $value=$value*$familiarity;
    
    if($value<0){
       $value="-1"; 
    }elseif($value>100){
        $value=100;
    }else{
        $value=ceil($value);
    }
    return $value;
}



// 结束	
}