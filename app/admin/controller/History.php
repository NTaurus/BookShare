<?php
// 声明命名空间

namespace app\admin\controller;

// 引入系统控制器

use think\Controller;
use think\helper\hash\Md5;
use think\Session;
use app\admin\controller\Common as ControllerCommon;
use app\common\model\User as ModelUser;
// 声明控制器

class History extends ControllerCommon{
 
    // public function History(){

    //         // 消息分发
    // if(!empty($_POST)&&$_POST['button']){
    //     $action = $_POST['button'];
    //     if($action == 'borrow_detail_history'){  
    //         return $this->borrowInfoDetail($_GET['borrow_id']);
    //     }
    //     // else if($action == 'borrow_detail_submit2'){
    //     //     return $this->returnCommit();
    //     // }
    //     // else if($action == 'borrow_detail_back'){
    //     //     return $this->borrowHistory();
    //     // }
    //   }

    // }



    public function borrowInfo(){
        return view('History/borrowHistory');
    }
    public function borrowInfoDetail(){
        // $borrow_id = $_POST['borrow_detail_history'];
        $borrow_id=$_POST['borrow_detail_history'];
        // echo $borrow_id;
        $this->assign("borrow_id", $borrow_id);
        return $this->fetch('History/borrowHistoryDetail');
          
    }
    
}