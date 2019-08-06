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
class Test4Controller extends BaseController{
public function index(){
$dbsheetname=C('EXCELSECRETSHEET');
$sheetname="人文论文分析";
$con['sheetname']=array("like","%分析%");
$con['ord']=array("EXP","is null");
$twoarr=M($dbsheetname)->where($con)->order('d1 asc')->select();
foreach ($twoarr as $arr) {
    $v.=$arr['d2']."\n<br><hr>";
}

$title="人文学院中文专业认证实时数据";
$content=$v;
echo h5html($title,$content);

}
public function xs(){
$dbsheetname=C('EXCELSECRETSHEET');

$con['sheetname']=array("like","%统计0717%");
$con['ord']=array("EXP","is null");
$twoarr=M($dbsheetname)->where($con)->order('d1 asc')->select();
foreach ($twoarr as $arr) {
    $v.=$arr['d2']."\n<br><hr>";
}


$title="人文学院申硕实时数据";
$content=$v;
echo h5html($title,$content);


}




//结尾处
}