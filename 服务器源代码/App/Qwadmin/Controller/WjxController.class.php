<?php
namespace Qwadmin\Controller;
use Common\Controller\BaseController;
use Think\Controller;
class WjxController extends BaseController{
    
public function index(){

$this->display();

    
} 

public function wjxupdate(){

$postarr=I('post.');
$keyvalue[0]="id";
$keyvalue[1]="name";
$keyvalue[2]="class";

// // pr($postarr);
// $touser=$postarr['touser'];
// $temp1=explode("\n",$touser);
// foreach ($temp1 as $key=>$value) {
//     if(strstr($value,"\t")){
//         $temp2=str_replace("\t",",",$value);
        
//     }
//     $temp3.=$temp2."\n";
// }

// $postarr['touser']=delendchar($temp3,"\n");
echo json_encode($postarr);
// pr($postarr);
// $this->display();
    
} 



public function wjxarrupdate(){

$postarr=I('post.');
$keyvalue[0]="id";
$keyvalue[1]="name";
$keyvalue[2]="class";

// pr($postarr);
$touser=$postarr['touser'];
$temp1=explode("\n",$touser);
foreach ($temp1 as $key=>$value) {
    if(strstr($value,"\t")){
        $temp2=explode("\t",$value);
    }
    foreach ($temp2 as $k2=>$v2) {
        // pr($temp2[$k2]);
        $temp3[$key][$keyvalue[$k2]]=empty($temp2[$k2])?"":$temp2[$k2];
    }
    unset($temp2);
    
    
}
$postarr['touser']=$temp3;
// echo json_encode($postarr);
pr($postarr);
// $this->display();
    
} 






// 结束	
}