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

class Manage extends ControllerCommon{
 
    public function Manage(){

        if(!empty($_POST)&&$_POST['button']){
            $action = $_POST['button'];
            if($action == 'detail'){  
                return $this->UserManageDetail();
            }
            else if($action == 'delete'){
                return $this->Delete();
            }

        }

    }

    public function Delete(){
        $user_id = $_GET['user_id'];
        ModelUser::deleteUser($user_id);
        echo "<script> alert('用户删除成功') </script>";

        return view('Manage/userManage');

    }

    public function UserManageDetail(){

        return view('Manage/UserManageDetail');

    }

    public function userManage(){

        return view('Manage/userManage');

    }

    public function userInfoDetail(){
        return view('Manage/UserManageDetail');
    }

    public function update(){
        $user_id = $_GET['user_id'];
        $user_name = $_POST['user_name'];
        ModelUser::UpdateUser($_POST['user_id'],$_POST['user_name'],$_POST['password'],$_POST['sex'],$_POST['phone'],$_POST['introduction']);
        echo "<script> alert('信息更新成功！') </script>";
        return $this->fetch('Manage/userManage');
    }




    
}