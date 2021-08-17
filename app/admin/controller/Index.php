<?php
// 声明命名空间

namespace app\admin\controller;

// 引入系统控制器

use think\Controller;
use app\admin\model\Admin as ModelAdmin;
use think\helper\hash\Md5;
use think\Session;

// 声明控制器

class Index extends Controller{
 
    // 登录页面

    public function index(){
        // 加载登录页面
        return view('index');

    }

    public function logout(){
        // 清除session（当前作用域）
        Session::clear();
        Session::destroy();
        echo "<script> alert('退出成功！正在跳转...') </script>";
        return $this->fetch('index');
    }

    // 处理登录的提交页面

    public function check(){

        if (isset($_REQUEST['authcode'])) {
            
            // session_start();
            Session::start();
            if (strtolower($_REQUEST['authcode'])==$_SESSION['authcode']) {
    
                        // 接收数据
                $adminname = $_POST['adminname'];
                $password = $_POST['password'];
                // $remeber=$_POST['remeber'];
                // 判断是否登录成功
                $admin = ModelAdmin::getOneadmin($adminname);
                if(!$admin){
                    echo "<script> alert('账号不存在,请重新输入！') </script>";
                }
                if ($admin['password'] == Md5($password)) {
                    echo "<script> alert('登录成功！正在跳转...') </script>";
                    Session::set(Md5('login_admin'),$adminname);
                    Session::set(Md5('is_admin'),'1');
                    Session::set(Md5('admin_name'),$admin['admin_name']);
                    return $this->fetch('Manage/userManage');
                }
                else{
                    // 失败之后跳转
                    echo "<script> alert('账号或密码错误，请重新输入！') </script>";
                    return $this->fetch('index');
                }
            }
            else{
                echo "<script> alert('验证码错误！') </script>";
                return $this->fetch('index');
            }    
        }

    }
}