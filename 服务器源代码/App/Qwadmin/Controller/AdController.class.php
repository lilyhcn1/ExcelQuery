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
class AdController extends BaseController{

// namespace Qwadmin\Controller;
// use Qwadmin\Controller\ComController;
// class AdComController extends ComController{    
 
public function index(){
    $url=U($Think.CONTROLLER_NAME."/uniquerydata");
        header("Location: $url");
}


public function addedit(){
$db=M(C('EXCELSECRETSHEET'));    
    // 编辑
    $id=I('get.id');   
    $sheetname=empty(I('get.sheetname'))?C('MLSHEETNAME'):I('get.sheetname');
    session('sheetname',$sheetname);
    $fieldstr=compute_fieldstr(C('MLNOTFIELD'));
// pr($fieldstr);  
// pr($id,'d2ff3r');    
// pr($id,'567564');     
    // 用户填表权限检测
    $paraarr=R("Queryfun/Auth2FillForm",array($sheetname,'','',$id));    
    // $titleexplain=$paraarr['titleexplain'];
    
// pr($id,'ef43rfsd2343333333');        
    if($id){
        // 用户修改表格的权限检测
        $bu=R("Queryfun/Auth2edit",array($sheetname,'','',$id));       
        $titlearr=R('Queryfun/gettitlearr',array('',$id,$fieldstr));
        
        // 先用管理员身份查查
        $con['id']=$id;
        $con=R('Queryfun/querycon',array($con,'true'));
        $fillingarr=$db->where($con)->order('id asc')->find();
        $sheetname=$fillingarr['sheetname'];
        // 再用自己的身份查查
        if(empty($fillingarr)){
                $confalse['id']=$id;
                $con=R('Queryfun/querycon',array($confalse,'false'));
                $fillingarr=$db->where($confalse)->order('id asc')->find();    
        }        
        session('sheetname',$fillingarr['sheetname']);
// pr($fillingarr,'$fillingarr');     
    }else{
        $fillingarr=arraygetkeys(I('get.'));
        $titlearr=R('Queryfun/gettitlearr',array($sheetname,'',$fieldstr));
        $querycon['sheetname']=$sheetname;
        $querycon=R('Queryfun/querycon',array($querycon,'true'));

}

    $datalistonearr=R('Queryfun/LastInputs',array($sheetname));
    // $datalistonearr[0]=["天台","临海"];

    $this->assign('fillingarr',$fillingarr);
    $this->assign('paraarr',$paraarr);
    $this->assign('id',$id);
    $this->assign('sheetname',$sheetname);    
    $this->assign('titlearr',$titlearr);
    $this->assign('datalistonearr',$datalistonearr);
    $this->assign('mynavline',R('Queryfun/mynavline',array($sheetname,$id,$this->USER)));
    $this->display();           
    // }else{
    //     $this->error("错误，未登陆。",U("index/index"));
    // }

}




// 结尾处
}

