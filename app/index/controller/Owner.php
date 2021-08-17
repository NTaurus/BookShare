<?php
namespace app\index\controller;
use app\common\model\BookModel;
use app\common;
use app\common\model\Book as ModelBook;
use app\common\model\Owner as ModelOwner;

use think\Db;
use think\Controller;
use think\helper\hash\Md5;
use think\Session;
use app\common\controller\Common as ControllerCommon;

class Owner extends ControllerCommon
{
    public function updataStatus(){
        $status = $_POST['is_lend']=='已借出'?1:0;
        $owner_id = $_GET['owner_id'];
        ModelOwner::updateStatus($owner_id,$status);
        echo "<script> alert('保存成功！') </script>";
        $this->assign("data", $owner_id);
        return $this->fetch('books/bookDetail');
      }

}