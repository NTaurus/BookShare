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
use think\Url;
use app\common\controller\Common as ControllerCommon;

class Message extends ControllerCommon
{

    // 消息分发
    public function message(){

        if(!empty($_POST)&&$_POST['button']){
            $action = $_POST['button'];
            if($action == 'lend_detail_submit'){  
                return $this->lendReply();
            }
            else if($action == 'lend_detail_back'){
                return $this->lendMessage();
            }
            else if($action == 'borrow_detail_submit'){
                return $this->returnCommit();
            }
            else if($action == 'borrow_detail_back'){
                return $this->borrowMessage();
            }
            else if($action == 'return_detail_submit'){
                return $this->returnCommit();
            }
            else if($action == 'return_detail_back'){
                return $this->returnBook();
            }
            else if($action == 'confirm_detail_submit'){
                return $this->returnConfirmAction();
            }
            else if($action == 'confirm_detail_back'){
                return $this->returnConfirm();
            }

        }
    }

    public function lendReply(){
        $borrow_id = $_GET['borrow_id'];
        // 更新owner表
        $status = $_POST['is_lend']=='同意借书'?1:0;
        $res = ModelBorrow::getOwnerById($borrow_id);
        ModelOwner::updateStatus($res['owner_id'],$status);

        // 更新borrow表
        $is_lend = $_POST['is_lend'];
        $status = $is_lend == '同意借书'?'已借未还':'不借了';
        $remark = $_POST['owner_remark'];
        ModelBorrow::lendReply($borrow_id,$remark, $status);

        echo "<script> alert('申请提交成功！') </script>";
        $this->assign("borrow_id", $borrow_id);
        return $this->fetch('message/lendMessageDetail');
    }

    public function borrowMessage(){
        return $this->fetch('message/borrowMessage');
    }

    public function lendMessage(){
        return $this->fetch('message/lendMessage');
    }

    public function lendMessageDetail(){
        $borrow_id = $_POST['lend_detail'];
        $this->assign("borrow_id", $borrow_id);
        return $this->fetch('message/lendMessageDetail');
    }

    public function borrowMessageDetail(){
        $borrow_id = $_POST['lend_detail'];
        $this->assign("borrow_id", $borrow_id);
        return $this->fetch('message/borrowMessageDetail');
    }

    public function returnCommit(){
        $borrow_id = $_GET['borrow_id'];
        $role = $_GET['role']==1?'借书人':'分享人';
        ModelBorrow::returnCommit($borrow_id, $role);

        echo "<script> alert('还书申请提交成功！') </script>";
        $this->assign("borrow_id", $borrow_id);
        return $this->fetch('message/returnBookDetail');
    }


    public function returnBook(){
        return $this->fetch('message/returnBook');
    }

    public function returnBookDetail(){
        $borrow_id = $_POST['lend_detail'];
        $this->assign("borrow_id", $borrow_id);
        return $this->fetch('message/returnBookDetail');
    }

    public function returnConfirm(){
        return $this->fetch('message/returnConfirm');
    }

    public function returnConfirmDetail(){
        $borrow_id = $_POST['lend_detail'];
        $this->assign("borrow_id", $borrow_id);
        return $this->fetch('message/returnConfirmDetail');
    }
    
    public function returnConfirmAction(){
        // 确认收到书
        $borrow_id = $_GET['borrow_id'];
        $role = $_GET['role']==1?'借书人':'分享人';
        ModelBorrow::returnCommit($borrow_id, $role);
        // 更新图书状态
        ModelBorrow::returnBook($borrow_id);
        echo "<script> alert('确认还书成功！') </script>";
        $this->assign("borrow_id", $borrow_id);
        return $this->fetch('message/returnConfirmDetail');
    }
}