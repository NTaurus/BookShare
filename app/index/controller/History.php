<?php
namespace app\index\controller;
use app\common\model\BookModel;
use app\common;
use app\common\model\Book as ModelBook;
use app\common\model\Owner as ModelOwner;
use app\common\model\Borrow as ModelBorrow;

use think\Db;
use think\Controller;
use think\helper\hash\Md5;
use think\Session;
use app\common\controller\Common as ControllerCommon;

class History extends ControllerCommon
{

  public function history(){
    
    // 消息分发
    if(!empty($_POST)&&$_POST['button']){
      $action = $_POST['button'];
      if($action == 'borrow_detail_submit1'){  
          return $this->borrowCancel($_GET['borrow_id']);
      }
      else if($action == 'borrow_detail_submit2'){
          return $this->returnCommit();
      }
      else if($action == 'borrow_detail_back'){
          return $this->borrowHistory();
      }
    }
  }

  public function returnCommit(){
    $borrow_id = $_GET['borrow_id'];
    $role = $_GET['role']==1?'借书人':'分享人';
    ModelBorrow::returnCommit($borrow_id, $role);

    echo "<script> alert('还书申请提交成功！') </script>";
    $this->assign("borrow_id", $borrow_id);
    return $this->fetch('history/borrowHistoryDetail');
}

  public function borrowCancel($borrow_id)
  {
    ModelBorrow::borrowCancel($borrow_id);
    echo "<script> alert('取消申请成功！') </script>";
    return $this->fetch('history/borrowhistory');
  }


  // public function borrowCancel($borrow_id)
  // {
  //   ModelBorrow::borrowCancel($borrow_id);
  //   echo "<script> alert('取消申请成功！') </script>";
  //   return $this->fetch('history/borrowhistory');
  // }

  public function borrowHistory(){
      return $this->fetch('history/borrowhistory');
  }
    
  public function lendHistory(){
    return $this->fetch('history/lendhistory');
  }

  public function lendHistoryDetail(){
    $borrow_id = $_POST['borrow_detail_history'];
    $this->assign("borrow_id", $borrow_id);
    return $this->fetch('history/lendHistoryDetail');
  }

  public function borrowHistoryDetail(){
    $borrow_id = $_POST['borrow_detail_history'];
    $this->assign("borrow_id", $borrow_id);
    return $this->fetch('history/borrowHistoryDetail');
  }
}